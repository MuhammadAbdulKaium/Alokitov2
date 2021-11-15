<?php

namespace Modules\CadetFees\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Academics\Entities\AcademicsLevel;
use Carbon\Carbon;
use Modules\CadetFees\Entities\CadetFeesGenerate;
use Modules\CadetFees\Entities\FeesStructure;
use Modules\CadetFees\Entities\StudentFeesCollctionHistory;
use Modules\CadetFees\Entities\StudentFeesCollection;
use Modules\RoleManagement\Entities\User;
use DateTime;
use DB;

class CadetFeesCollectionController extends Controller
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
        $feesStructureList =FeesStructure::where(['campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute()
        ])->get();
        $years= Carbon::now()->format('Y');
        $academicLevels = $this->academicsLevel->where([
            'institute_id' => $this->academicHelper->getInstitute(),
            'campus_id' => $this->academicHelper->getCampus()
        ])->get();
        return view('cadetfees::feesCollection.index',compact('academicLevels','years','feesStructureList'));
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
//        return $request->all();
        for($i=0;$i<count($request->checkbox);$i++)
        {
            $today = Carbon::now()->isoFormat('YYYY-MM-DD');
            $genDetails = CadetFeesGenerate::where('id',$request->checkbox[$i])->first();
            $feesCollectionDetails= StudentFeesCollection::where('fees_generate_id',$genDetails->id)->first();
            $to =Carbon::createFromFormat('Y-m-d', $genDetails->payment_last_date);
            $from =Carbon::createFromFormat('Y-m-d', $today);
//            return $feesCollectionDetails;
//            For previous record
            if($feesCollectionDetails) {
//            If Due has
                if($request->discount[$i]==null)
                {
                    $request->discount[$i]=0;
                }
                if ($feesCollectionDetails->total_dues > 0) {
                    if ($to < $from) {
                        if ($genDetails->fine_type == 1) {
                            $diff_in_days = $from->diffInDays($to);
                            $fine_amount= $diff_in_days * $genDetails->late_fine;
                            $total_payable = $fine_amount + $genDetails->fees;
                            $paid_amount=($feesCollectionDetails->paid_amount)+$request->current_pay[$i];
                            $due=$total_payable-($paid_amount+$request->discount[$i]+$feesCollectionDetails->discount);
                        }
                        else{
                            $fine_amount=$genDetails->late_fine;
                            $total_payable = $fine_amount + $genDetails->fees;
                            $paid_amount=($feesCollectionDetails->paid_amount)+$request->current_pay[$i];
                            $due=$total_payable-($paid_amount+$request->discount[$i]+$feesCollectionDetails->discount);
                        }
                    }
                    else{
                        $fine_amount=0;
                        $total_payable =$genDetails->fees;
                        $paid_amount=($feesCollectionDetails->paid_amount)+$request->current_pay[$i];
                        $due=$total_payable-($paid_amount+$request->discount[$i]+$feesCollectionDetails->discount);
                    }
                    $data=array();
                    $data['paid_amount']=$paid_amount;
                    $data['fine_amount']=$fine_amount;
                    $data['total_payable']=$total_payable;
                    $data['total_dues']=$due;
                    $data['discount']=$request->discount[$i];
                    $feesCollectionDone = DB::table('student_fees_collection')->where('id',$feesCollectionDetails->id)->update($data);
                    if($feesCollectionDone)
                    {
                        if($total_payable == $feesCollectionDetails->paid_amount+$request->current_pay[$i]+$request->discount[$i]+$feesCollectionDetails->discount){
                            $status = 1;
                        }
                        else{
                            $status = 2;
                        }
                        $feesStaus = array();
                        $feesStaus['status'] = $status;
                        $feesGenUpdate = DB::table('cadet_fees_generate')->where('id',$genDetails->id)->update($feesStaus);
                    }
                    $feesCollection = new StudentFeesCollctionHistory();
                    $feesCollection->std_id = $genDetails->std_id;
                    $feesCollection->fees_generate_id = $genDetails->id;
                    $feesCollection->fees_amount = $genDetails->fees;
                    $feesCollection->fine_amount = $fine_amount;
                    $feesCollection->total_payable = $total_payable;
                    $feesCollection->paid_amount = $request->current_pay[$i];
                    $feesCollection->discount = $request->discount[$i];
                    $feesCollection->payment_type = 1;
                    $feesCollection->total_dues = $due;
                    $feesCollection->paid_by = Auth::user()->id;
                    $feesCollection->created_by = Auth::user()->id;
                    $feesCollectionHistoryDone = $feesCollection->save();
                } // If Not Due

            }
//            For New Record
            else{
                if($to<$from){
                    if($genDetails->fine_type==1)
                    {
                        $diff_in_days = $from->diffInDays($to);
                        $fine_amount= $diff_in_days * $genDetails->late_fine;
                        $total_payable = $fine_amount + $genDetails->fees;
                    }
                    else{
                        $fine_amount = $genDetails->late_fine;
                        $total_payable = $fine_amount + $genDetails->fees;
                    }
                }
                else{
                    $fine_amount = 0;
                    $total_payable = $genDetails->fees;
                }

                if($request->discount[$i]==null)
                {
                    $request->discount[$i]=0;
                }
                $feesCollection = new StudentFeesCollection();
                $feesCollection->std_id = $genDetails->std_id;
                $feesCollection->fees_generate_id = $genDetails->id;
                $feesCollection->fees_amount = $genDetails->fees;
                $feesCollection->fine_amount = $fine_amount;
                $feesCollection->total_payable = $total_payable;
                $feesCollection->paid_amount = $request->current_pay[$i];
                $feesCollection->discount = $request->discount[$i];
                $feesCollection->payment_type = 1;
                $feesCollection->total_dues = $total_payable - $request->current_pay[$i] - $request->discount[$i];
                $feesCollection->paid_by = Auth::user()->id;
                $feesCollection->created_by = Auth::user()->id;
                $feesCollectionDone = $feesCollection->save();

                if($feesCollectionDone)
                {
                    if($total_payable == $request->current_pay[$i]+$request->discount[$i]){
                        $status = 1;
                    }
                    elseif($request->current_pay[$i]>0){
                        $status = 2;
                    }
                    $feesStaus = array();
                    $feesStaus['status'] = $status;
                    $feesGenUpdate = DB::table('cadet_fees_generate')->where('id',$genDetails->id)->update($feesStaus);

                    $feesCollection = new StudentFeesCollctionHistory();
                    $feesCollection->std_id = $genDetails->std_id;
                    $feesCollection->fees_generate_id = $genDetails->id;
                    $feesCollection->fees_amount = $genDetails->fees;
                    $feesCollection->fine_amount = $fine_amount;
                    $feesCollection->total_payable = $total_payable;
                    $feesCollection->paid_amount = $request->current_pay[$i];
                    $feesCollection->discount = $request->discount[$i];
                    $feesCollection->payment_type = 1;
                    $feesCollection->total_dues = $total_payable - $request->current_pay[$i] - $request->discount[$i];
                    $feesCollection->paid_by = Auth::user()->id;
                    $feesCollection->created_by = Auth::user()->id;
                    $feesCollectionDone = $feesCollection->save();
                }
            }

            //------------------------//
//            if($to<$from)
//            {
//                if($genDetails->fine_type==1)
//                {
//                    $diff_in_days = $from->diffInDays($to);
//                    $fine_amount= $diff_in_days * $genDetails->late_fine;
//                    $total_payable = $fine_amount + $genDetails->fees;
//                }
//                else{
//                    $fine_amount = $genDetails->late_fine;
//                    $total_payable = $fine_amount + $genDetails->fees;
//                }
//
//            }
//            else{
//                $fine_amount = 0;
//                $total_payable = $genDetails->fees;
//            }
//
//            if($feesCollectionDetails)
//            {
//               $total_paid_fees = $request->current_pay[$i]+ $feesCollectionDetails->paid_amount-$request->discount[$i];
//               $data=array();
//               $data['paid_amount']=$total_paid_fees;
//               $data['fine_amount']=$fine_amount;
//               $data['total_payable']=$total_payable;
//               $data['total_dues']=$feesCollectionDetails->total_dues - $request->current_pay[$i]-$request->discount[$i];
//               $feesCollectionDone = DB::table('student_fees_collection')->where('id',$feesCollectionDetails->id)->update($data);
//                if($feesCollectionDone)
//                {
//                    if($total_payable == $total_paid_fees){
//                        $status = 1;
//                    }
//                    elseif($request->current_pay[$i]>0){
//                        $status = 2;
//                    }
//                    $feesStaus = array();
//                    $feesStaus['status'] = $status;
//                    $feesGenUpdate = DB::table('cadet_fees_generate')->where('id',$genDetails->id)->update($feesStaus);
//
//                    $feesCollection = new StudentFeesCollctionHistory();
//                    $feesCollection->std_id = $genDetails->std_id;
//                    $feesCollection->fees_generate_id = $genDetails->id;
//                    $feesCollection->fees_amount = $genDetails->fees;
//                    $feesCollection->fine_amount = $fine_amount;
//                    $feesCollection->total_payable = $total_payable;
//                    $feesCollection->paid_amount = $request->current_pay[$i] -$request->discount[$i];
//                    $feesCollection->discount = $request->discount[$i];
//                    $feesCollection->payment_type = 1;
//                    $feesCollection->total_dues = $total_payable - $request->current_pay[$i]-$request->discount[$i];
//                    $feesCollection->paid_by = Auth::user()->id;
//                    $feesCollection->created_by = Auth::user()->id;
//                    $feesCollectionDone = $feesCollection->save();
//                }
//            }
//            else{
//               if($request->discount[$i]==null)
//               {
//                   $request->discount[$i]=0;
//               }
//                $feesCollection = new StudentFeesCollection();
//                $feesCollection->std_id = $genDetails->std_id;
//                $feesCollection->fees_generate_id = $genDetails->id;
//                $feesCollection->fees_amount = $genDetails->fees;
//                $feesCollection->fine_amount = $fine_amount;
//                $feesCollection->total_payable = $total_payable;
//                $feesCollection->paid_amount = $request->current_pay[$i] - $request->discount[$i];
//                $feesCollection->discount = $request->discount[$i];
//                $feesCollection->payment_type = 1;
//                $feesCollection->total_dues = $total_payable - $request->current_pay[$i] - $request->discount[$i];
//                $feesCollection->paid_by = Auth::user()->id;
//                $feesCollection->created_by = Auth::user()->id;
//                $feesCollectionDone = $feesCollection->save();
//
//                if($feesCollectionDone)
//                {
//                    if($total_payable == $request->current_pay[$i]){
//                        $status = 1;
//                    }
//                    elseif($request->current_pay[$i]>0){
//                        $status = 2;
//                    }
//                    $feesStaus = array();
//                    $feesStaus['status'] = $status;
//                    $feesGenUpdate = DB::table('cadet_fees_generate')->where('id',$genDetails->id)->update($feesStaus);
//
//                    $feesCollection = new StudentFeesCollctionHistory();
//                    $feesCollection->std_id = $genDetails->std_id;
//                    $feesCollection->fees_generate_id = $genDetails->id;
//                    $feesCollection->fees_amount = $genDetails->fees;
//                    $feesCollection->fine_amount = $fine_amount;
//                    $feesCollection->total_payable = $total_payable;
//                    $feesCollection->paid_amount = $request->current_pay[$i] - $request->discount[$i];
//                    $feesCollection->discount = $request->discount[$i];
//                    $feesCollection->payment_type = 1;
//                    $feesCollection->total_dues = $total_payable - $request->current_pay[$i] - $request->discount[$i];
//                    $feesCollection->paid_by = Auth::user()->id;
//                    $feesCollection->created_by = Auth::user()->id;
//                    $feesCollectionDone = $feesCollection->save();
//                }
//            }
//
        }
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
}
