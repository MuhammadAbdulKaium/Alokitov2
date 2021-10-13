<?php

namespace Modules\CadetFees\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\Batch;
use Modules\CadetFees\Entities\CadetFeesAssign;
use Modules\CadetFees\Entities\CadetFeesGenerate;
use Modules\CadetFees\Entities\CadetFeesPayment;
use Modules\CadetFees\Entities\FeesStructure;
use Modules\RoleManagement\Entities\User;
use Modules\Student\Entities\StudentEnrollment;
use DateTime;

class CadetFeesController extends Controller
{
    private $academicHelper;
    private $academicsLevel;


    public function __construct(AcademicHelper $academicHelper,AcademicsLevel $academicsLevel)
    {
        $this->academicHelper = $academicHelper;
        $this->academicsLevel = $academicsLevel;
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('cadetfees::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('cadetfees::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('cadetfees::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('cadetfees::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
    public function createFees()
    {
        $classes=Batch::where([
            'campus' => $this->academicHelper->getCampus(),
            'institute' => $this->academicHelper->getInstitute(),
        ])->get();
        $academicLevels = $this->academicsLevel->where([
            'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()
        ])->get();
        $structureNames= FeesStructure::where([
            'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()
        ])->get();
        $allInputs = array('year' => null, 'level' => null, 'batch' => null, 'section' => null, 'gr_no' => null, 'email' => null);

        return view('cadetfees::index',compact('academicLevels','allInputs','structureNames'))->with('allEnrollments', null);

    }
    public function assignCadetFees(Request $request)
    {
        DB::beginTransaction();
        try {
            $requestedHead = $request->head_amount_id;
            $studentIds = $request->std_id;
            $headIds = [];
            $totalFees = [];
            $feesDetails = [];

            foreach ($request->head_amount_id as $key => $amount) {
                array_push($headIds, $key);
            }

            if (sizeof($headIds) > 0) {
                foreach ($studentIds as $studentId) {
                    $totalFees[$studentId] = 0;
                    $feesDetails[$studentId] = [];
                }

                foreach ($studentIds as $key => $value) {
                    foreach ($headIds as $headId) {
                        $totalFees[$studentIds[$key]] += $requestedHead[$headId][$key];
                        $feesDetails[$studentIds[$key]][$headId] = $requestedHead[$headId][$key];
                    }
                }

            }
            foreach ($request->std_id as $key => $std) {
                $studentFees = new CadetFeesAssign();
                $studentFees->std_id = $std;
                $studentFees->total_fees = $totalFees[$std];
                $studentFees->fees_details = json_encode($feesDetails[$std]);
                $studentFees->structure_id = $request->structure_id[$key];
                $studentFees->batch = $request->batch[$key];
                $studentFees->batch = $request->batch[$key];
                $studentFees->section = $request->section[$key];
                $studentFees->academic_level = $request->academic_level[$key];
                $studentFees->academic_year = $request->academic_year[$key];
                $studentFees->created_by = Auth::user()->id;
                $feesAssignDone = $studentFees->save();

            }
            DB::commit();
            if ($feesAssignDone) {
                Session::flash('message', 'Success!Data has been saved successfully.');
                return redirect()->back();
            } else {
                Session::flash('message', 'Success!Data has not been saved successfully.');
                return redirect()->back();

            }
        }
                 catch (\Exception $e) {
                DB::rollback();
                Session::flash('errorMessage', 'Error! Error creating house.');
                return redirect()->back();
        }

    }
    public function generateCadetFees()
    {
        $academicLevels = $this->academicsLevel->where([
            'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()
        ])->get();
        $allInputs = array('year' => null, 'level' => null, 'batch' => null, 'section' => null, 'gr_no' => null, 'email' => null);
        return view('cadetfees::generate',compact('academicLevels','allInputs'));
    }
    public function storeGenerateCadetFees(Request $request)
    {
        $academicYearProfile = AcademicsYear::where(['campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'status' => '1'])->first();
        for ($i = 0; $i < count($request->std_id); $i++) {

            $checkFeesAssign = CadetFeesGenerate::where(['campus_id' => $this->academicHelper->getCampus(),
                'instittute_id' => $this->academicHelper->getInstitute(),
                'academic_year' => $academicYearProfile->id,
                'academic_level' => $request['academic_level'][$i],
                'batch' => $request['batch'][$i],
                'section' => $request['section'][$i],
                'std_id' => $request['std_id'][$i],
                'month_name' => $request['month_name'][$i]
            ])->first();
            if (!$checkFeesAssign) {
                $cadetFeesAssign = new CadetFeesGenerate();
                $cadetFeesAssign->std_id = $request['std_id'][$i];
                $cadetFeesAssign->academic_year = $academicYearProfile->id;
                $cadetFeesAssign->fees = $request['amount'][$i];
                $cadetFeesAssign->late_fine = $request['fine'][$i];
                $cadetFeesAssign->academic_level = $request['academic_level'][$i];
                $cadetFeesAssign->batch = $request['batch'][$i];
                $cadetFeesAssign->section = $request['section'][$i];
                $cadetFeesAssign->fine_type = $request['fine_type'][$i];
                $cadetFeesAssign->campus_id = $this->academicHelper->getCampus();
                $cadetFeesAssign->instittute_id = $this->academicHelper->getInstitute();
                $cadetFeesAssign->month_name = $request['month_name'][$i];
                $cadetFeesAssign->payment_last_date = $request['payment_last_date'][$i];
                $cadetFeesAssign->created_by = Auth::user()->id;
                $assignDataStore = $cadetFeesAssign->save();
                if ($assignDataStore) {
                    Session::flash('message', 'Success!Data has been Generate successfully.');
                } else {
                    Session::flash('message', 'Success!Data has not been Generate successfully.');
                    return redirect()->back();

                }
            }
//            return redirect()->back();

            if($checkFeesAssign)
            {
                if(count($request->std_id) == $checkFeesAssign->count()){
                    Session::flash('message', 'All Ready all student Fees Generate.');
                    return redirect()->back();
                }
            }

        }

        }

    public function manualCadetFees(){
        return view('cadetfees::payment.manual.index');

    }
    public function searchCadetFeesManually(Request $request){
        $feesCheck=User::join('student_informations','users.id','student_informations.user_id')
            ->join('cadet_fees_generate','student_informations.id','cadet_fees_generate.std_id')
            ->join('academics_year','cadet_fees_generate.academic_year','academics_year.id')
            ->join('academics_level','cadet_fees_generate.academic_level','academics_level.id')
            ->join('batch','cadet_fees_generate.batch','batch.id')
            ->join('section','cadet_fees_generate.section','section.id')
            ->where('users.email','=',$request->std_id)
            ->select('cadet_fees_generate.*','academics_year.year_name','academics_level.level_name','batch.batch_name','section.section_name')
            ->get();

        $userDetails=User::join('student_informations','users.id','student_informations.user_id')
            ->join('cadet_fees_generate','student_informations.id','cadet_fees_generate.std_id')
//            ->join('academics_year','cadet_fees_generate.academics_year','academics_year.id')
            ->where('users.email','=',$request->std_id)
            ->select('student_informations.*')
            ->first();

        $stdListView = view('cadetfees::payment.manual.includes.fees-details', compact('feesCheck','userDetails'))->render();
        return ['status' => 'success', 'msg' => 'Student List found', 'html' => $stdListView];
    }
    public function calculateCadetFeesManually($id)
    {
        $feesDetails = CadetFeesGenerate::where('id',$id)->first();
        $fine=$feesDetails->late_fine;
        $fine_type=$feesDetails->fine_type;
        $last_date=$feesDetails->payment_last_date;
        $today=Carbon::now()->format('Y-m-d');
        $last_date_payment = \Carbon\Carbon::createFromFormat('Y-m-d', $last_date);
        $current_date = \Carbon\Carbon::createFromFormat('Y-m-d', $today);
        if($fine_type==1)
        {
            if($current_date>$last_date_payment)
            {
                $different_days = $last_date_payment->diffInDays($current_date);
                $total_amount=$fine*$different_days+$feesDetails->fees;
                $total_late_fine=$fine*$different_days;
            }
            else{
                $total_amount=$feesDetails->fees;
            }

        }
        if($fine_type==2)
        {
            if($current_date>$last_date_payment)
            {
                $total_amount=$feesDetails->fees+$fine;
                $total_late_fine=$fine;
            }
            else{
                $total_amount=$feesDetails->fees;
            }


        }

        return view('cadetfees::payment.manual.modal.payment',compact('total_amount','feesDetails','different_days','total_late_fine'));
    }
    public function paidCadetFeesManually(Request $request, $id)
    {
        $feesDetails = CadetFeesGenerate::where('id',$id)->first();

        $payment=new CadetFeesPayment();
        $payment->generate_id=$id;
        $payment->std_id=$feesDetails->std_id;
        $payment->academic_year=$feesDetails->academic_year;
        $payment->fees=$feesDetails->fees;
        $payment->late_fine=$feesDetails->late_fine;
        $payment->fine_type=$feesDetails->fine_type;
        $payment->late_days=$request->different_days;
        $payment->fine_amount=$request->total_late_fine;
        $payment->total_amount=$request->total_amount;
        $payment->academic_level=$feesDetails->academic_level;
        $payment->batch=$feesDetails->batch;
        $payment->section=$feesDetails->section;
        $payment->month_name=$feesDetails->month_name;
        $payment->payment_methods=1;
        $payment->payment_date=Carbon::now()->format('Y-m-d');
        $payment->created_by=Auth::user()->id;
        $payment->campus_id = $this->academicHelper->getCampus();
        $payment->instittute_id = $this->academicHelper->getInstitute();
        $paymentDone=$payment->save();
        if ($paymentDone) {
            $feesDetails->update(['status'=> 1]);
            Session::flash('message', 'Success!Payment has been Paid successfully.');
            return redirect()->back();

        } else {
            Session::flash('message', 'Success!Payment has not been Paid successfully.');
            return redirect()->back();

        }

    }

}
