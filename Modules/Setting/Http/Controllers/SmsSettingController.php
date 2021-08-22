<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\AutoSmsModule;
use Modules\Setting\Entities\AutoSmsSetting;
use Modules\Communication\Entities\SmsTemplate;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\AcademicHelper;

class SmsSettingController extends Controller
{

    private  $institute;
    private  $smsTemplate;
    private  $autoSmsModule;
    private  $autoSmsSetting;
    private  $academicHelper;

    public function __construct(Institute $institute, AutoSmsSetting $autoSmsSetting,SmsTemplate $smsTemplate,AutoSmsModule $autoSmsModule, AcademicHelper $academicHelper)
    {
        $this->institute             = $institute;
        $this->smsTemplate           = $smsTemplate;
        $this->autoSmsModule          = $autoSmsModule;
        $this->autoSmsSetting          = $autoSmsSetting;
        $this->academicHelper          = $academicHelper;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $institute_id=session()->get('institute');
        $institute=Institute::where('id',$institute_id)->first();
        return view('setting::sms.index',compact('institute'));
    }



    // sms setting tab

    public function allSmsSetting($tabName)
    {

        $instituteId= $this->academicHelper->getInstitute();
        $campusId= $this->academicHelper->getCampus();


        switch ($tabName) {

            case 'getway':
                // return veiw with variables
                $institute = $this->getInstituteInformation();
                return view('setting::sms.index', compact('institute'))->with('page', 'getway');
                break;

            case 'autosmsmodule':
                // return veiw with variables
                $institute = $this->getInstituteInformation();
                $smsTemplateList = $this->smsTemplate->all();

                // get all auto sms modules list
                $autoSmsModuleList = $this->autoSmsModule->where('ins_id',$instituteId)->where('campus_id',$campusId)->get();
                return view('setting::sms.autosms_module', compact('institute', 'smsTemplateList', 'autoSmsModuleList'))->with('page', 'autosmsmodule');
                break;

            case 'autosmssetting':
                $institute = $this->getInstituteInformation();
                // return veiw with variables

                // get all auto sms modules list
                $autoSmsModuleList = $this->autoSmsModule->where('ins_id',$instituteId)->where('campus_id',$campusId)->get();
                $autoSmsSettingsList = $this->autoSmsSetting->where('ins_id',$instituteId)->where('campus_id',$campusId)->get();


                foreach ($autoSmsSettingsList as $smsSetting) {
                    $userTypeArray[] = json_decode($smsSetting->user_type);
                }


//                return $userTypeArray;

                return view('setting::sms.autosms_setting',compact('institute','autoSmsModuleList','autoSmsSettingsList','userTypeArray'))->with('page', 'autosmssetting');
                break;

            default:
                // return veiw with variables
                return view('setting::sms.index',compact('institute'))->with('page', 'getway');
                break;
        }
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('setting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('setting::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }


    public  function getInstituteInformation(){
        $institute_id=session()->get('institute');
        $institute=Institute::where('id',$institute_id)->first();
        return $institute;
    }
}
