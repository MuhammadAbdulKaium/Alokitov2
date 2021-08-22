<?php

namespace Modules\Reports\Http\Controllers;

use App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Student\Entities\StudentProfileView;
use Modules\Reports\Entities\IdCardTemplate;
use PDF;
use Modules\Setting\Entities\IdCardSetting;
class StudentIdCardController extends Controller
{

    private $idCardTemplate;
    private $academicHelper;
    private $studentProfileView;
    private $idCardSetting;
    private $data;

    public function __construct(AcademicHelper $academicHelper, IdCardSetting $idCardSetting, StudentProfileView $studentProfileView, IdCardTemplate $idCardTemplate)
    {
        $this->idCardTemplate = $idCardTemplate;
        $this->academicHelper = $academicHelper;
        $this->studentProfileView = $studentProfileView;
        $this->idCardSetting = $idCardSetting;
    }

    // get batch section student ic card list
    public function getStdIdCardList(Request $request)
    {
//        return $request->all();
        // institute details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        $academicYear=$this->academicHelper->getAcademicYearProfile();
        $arrayWhere=[];
        $arrayWhere['academic_level']=$request->input('academic_level');
        $arrayWhere['batch']=$request->input('batch');
        $arrayWhere['section']=$request->input('section');
        $arrayWhere['campus']=$campusId;
        $arrayWhere['institute']=$instituteId;
        if(!empty($request->gr_no)){
            $arrayWhere['gr_no']=$request->gr_no;
        }

        // batch section student list
         $this->data['studentList'] = $this->studentProfileView->where($arrayWhere)->orderBy(DB::raw('ABS(gr_no)'), 'asc')->get();
        // institute profile
        $this->data['instituteInfo']  = $this->academicHelper->getInstituteProfile();
        //find institute ID card setting
         $templateProfile = $this->idCardSetting->where(['campus_id'=>$campusId, 'institution_id'=>$instituteId])->first() ;
        $this->data['templateProfile']=$templateProfile;
        $this->data['campusId']=$campusId;
        return $this->idcardViewByInstituteId($instituteId,$this->data);
    }

    // get batch section student ic card list
    public function downloadStdIdCardList(Request $request)
    {
        // request details
        $academicYear = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch = $request->input('batch');
        $section = $request->input('section');
        $request_type = $request->input('request_type'); // pdf or xls
        $fontSize = $request->input('font_size', null);
        $width = $request->input('width', null);
        $height = $request->input('height', null);
        $margin_bottom = $request->input('margin_bottom', null);
        // institute details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // institute profile
        $instituteInfo = $this->academicHelper->getInstituteProfile();
        // institute information
        $templateProfile = $this->idCardTemplate->where(['campus'=>$campusId, 'institute'=>$instituteId])->first();
        // template setting
        $tempType =  $templateProfile->temp_type;

        // batch section student list
        $studentList = $this->studentProfileView->where([
            'academic_year'=> $academicYear, 'academic_level'=> $academicLevel,
            'batch'=> $batch, 'section'=> $section, 'campus'=> $campusId, 'institute'=> $instituteId
        ])->get();


        return view('reports::pages.report.id-card-land-test', compact('studentList', 'instituteInfo', 'templateProfile'));

//        // share all variables with the view
        view()->share(compact('studentList', 'instituteInfo', 'templateProfile'));
        // generate pdf
        $pdf = App::make('dompdf.wrapper');

        $pdf = PDF::loadView('reports::pages.report.id-card-land-test');
        $pdf->loadView('reports::pages.report.id-card-land-test')->setPaper('a4', 'portrait');

        return $pdf->stream('class_section_student_id_card.pdf');

        //  checking id card template type (landscape or portrait)
        if($tempType==0){
            if($templateProfile->temp_id==1){
                $pdf->loadView('reports::pages.report.id-card-land-template-one')->setPaper('a4', 'landscape');
            }
            elseif($templateProfile->temp_id==2) {
                $pdf->loadView('reports::pages.report.id-card-land-template-two')->setPaper('a4', 'landscape');
            }

            elseif($templateProfile->temp_id==3) {
                $pdf = PDF::loadView('reports::pages.report.id-card-land-test');
                $pdf->loadView('reports::pages.report.id-card-land-test')->setPaper('a4', 'landscape');
//              return view('reports::pages.report.id-card-land-test');
//return view('reports::pages.report.id-card-land-template-three');
//             $pdf->loadView('reports::pages.report.id-card-land-template-three')->setPaper('a4', 'landscape');
            }

            elseif($templateProfile->temp_id==4) {
//                $pdf->loadView('reports::pages.report.id-card-land-template-three')->setPaper('a4', 'landscape');
                $pdf->loadView('reports::pages.report.id-card-land-test')->setPaper('a4', 'landscape');
            }
        }else{
            if($templateProfile->temp_id==1) {
                $pdf->loadView('reports::pages.report.id-card-port-template-one')->setPaper('a4', 'portrait');
            }
            elseif($templateProfile->temp_id==2) {
                $pdf->loadView('reports::pages.report.id-card-port-template-two')->setPaper('a4', 'portrait');
            }
        }
        // return
    }





    /////////////////////// ID Card Template Setting /////////////////////////////

    // store id card setting
    public function storeTemplateSetting(Request $request)
    {
//        return $request->all();
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();

        if(!empty($request->id_card_setting_id)){

            if ($request->hasFile('signature')) {
                $file = $request->file('signature');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('./assets/signature/', $fileName);
                $idCardProfile= $this->idCardSetting->find($request->id_card_setting_id);
                $idCardProfile->signature=$fileName;
                $idCardProfile->save();
            }
            // create object
            $idCardProfile= $this->idCardSetting->find($request->id_card_setting_id);
            $idCardProfile->institution_id=$institute;
            $idCardProfile->campus_id=$campus;
            $idCardProfile->idcard_valid=$request->idcard_valid;
            $result=$idCardProfile->save();

        }else {

            $fileName = null;
            if ($request->hasFile('signature')) {
                $file = $request->file('signature');
                $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
                $file->move('./assets/signature/', $fileName);
            }
            // create object
            $idCardSettingObj=new $this->idCardSetting;
            $idCardSettingObj->institution_id=$institute;
            $idCardSettingObj->campus_id=$campus;
            $idCardSettingObj->signature=$fileName;
            $idCardSettingObj->idcard_valid=$request->idcard_valid;
            $result=$idCardSettingObj->save();

        }
        // checking
        if($result){
            // return msg
            return ['status'=>'success',  'msg'=>'Template Submitted !!!'];
        }else{
            // return msg
            return ['status'=>'failed', 'msg'=>'Unable to Submit Template !!!'];
        }

    }



    /// template id wise template view
    public function idcardViewByInstituteId($instituteID,$data){
        return view('reports::pages.report.id-card.'.$instituteID,$data);
    }


}
