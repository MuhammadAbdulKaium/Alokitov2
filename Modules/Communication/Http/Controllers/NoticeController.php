<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Communication\Entities\Notice;
use Illuminate\Support\Facades\DB;
use App\Content;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Str;

class NoticeController extends Controller
{


    private  $notice;
    private  $academicHelper;

    public function __construct(Notice $notice, AcademicHelper $academicHelper)
    {
        $this->notice                 = $notice;
        $this->academicHelper                 = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $instituteId= $this->academicHelper->getInstitute();
        $campusId= $this->academicHelper->getCampus();

        $notices=$this->notice->where('institute_id',$instituteId)->where('campus_id',$campusId)->orderBy('id','desc')->paginate(10);
        return view('communication::pages.notice.index',compact('notices'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function create(Request $request)
    {

        $noticeFile     = $request->file('notice_file_path');
        if(!empty($noticeFile)) {
            $fileExtension = $noticeFile->getClientOriginalExtension();
            //$contentName     = $photoFile->getClientOriginalName();
            $contentName = "notice" . date("Ymdhis") . mt_rand(100000, 999999) . "." . $fileExtension;
            $contentFileName = $contentName;
            $destinationPath = 'assets/communication/notice/';

            // DB beginTransaction
            DB::beginTransaction();

            try {

                $uploaded = $noticeFile->move($destinationPath, $contentFileName);
                // storing file name to the database
                if ($uploaded) {
                    // user documet
                    $notice_document = new Content();
                    // storing user documetn
                    $notice_document->name = $contentName;
                    $notice_document->file_name = $contentFileName;
                    $notice_document->path = $destinationPath;
                    $notice_document->mime = $fileExtension;
                    $notice_document->save();
                } else {
                    Session::flash('warning', 'unable to upload photo');
                }
            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                // Redirecting with error message
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        }

        // crate fees items
        try {
            // delete action
            $notice=new $this->notice();
            $notice->institute_id=$this->academicHelper->getInstitute();
            $notice->campus_id=$this->academicHelper->getCampus();
            $notice->title=$request->input('notice_title');
            $notice->notice_date=date('Y-m-d H:i:s', strtotime($request->input('notice_date')));
            $notice->desc=$request->input('notice_description');
            $notice->user_type=$request->input('notice_user_type');
            if(!empty($noticeFile)) { $notice->notice_file=$notice_document->id;}
            $notice->status=1;
            $notice->save();

        } catch (ValidationException $e) {
            // Rollback and then redirect
            // back to form with errors
            DB::rollback();
            return redirect()->back()
                ->withErrors($e->getErrors())
                ->withInput();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }

        // Commit the queries!
        DB::commit();
        // return

        Session::flash('success', 'Notice Create Successfully');
        return redirect()->back();
    }


    //notice update
    public  function noticeUpdate(Request $request){

        $noticeFile     = $request->file('notice_file_path');
        if(!empty($noticeFile)) {

            // DB beginTransaction
            DB::beginTransaction();

            try {
                $fileExtension = $noticeFile->getClientOriginalExtension();
                //$contentName     = $photoFile->getClientOriginalName();
                $contentName     = "notice" . date("Ymdhis") . mt_rand(100000, 999999) . "." . $fileExtension;
                $contentFileName = $contentName;
                $destinationPath = 'assets/communication/notice/';

                $uploaded = $noticeFile->move($destinationPath, $contentFileName);
                // storing file name to the database
                if ($uploaded) {
                    // user documet
                    $notice_document = new Content();
                    // storing user documetn
                    $notice_document->name      = $contentName;
                    $notice_document->file_name = $contentFileName;
                    $notice_document->path      = $destinationPath;
                    $notice_document->mime      = $fileExtension;
                    $notice_document->save();
                } else {
                    Session::flash('warning', 'unable to upload photo');
                }
            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                // Redirecting with error message
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // crate notice
            try {
                // delete action
                $notice= $this->notice->find($request->input('notice_id'));
                $notice->institute_id=$this->academicHelper->getInstitute();
                $notice->campus_id=$this->academicHelper->getCampus();
                $notice->title=$request->input('notice_title');
                $notice->notice_date=date('Y-m-d H:i:s', strtotime($request->input('notice_date')));
                $notice->desc=$request->input('notice_description');
                $notice->user_type=$request->input('notice_user_type');
                $notice->notice_file=$notice_document->id;
                $notice->status=1;
                $notice->save();

            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // Commit the queries!
            DB::commit();
            // return

            Session::flash('success', 'Notice Create Successfully');
            return redirect()->back();


        } else {

            // DB beginTransaction
            DB::beginTransaction();

            // crate fees items
            try {
                // delete action
                $notice= $this->notice->find($request->input('notice_id'));
                $notice->institute_id=$this->academicHelper->getInstitute();
                $notice->campus_id=$this->academicHelper->getCampus();
                $notice->title=$request->input('notice_title');
                $notice->notice_date=date('Y-m-d H:i:s', strtotime($request->input('notice_date')));
                $notice->desc=$request->input('notice_description');
                $notice->user_type=$request->input('notice_user_type');
                $notice->status=1;
                $notice->save();

            } catch (ValidationException $e) {
                // Rollback and then redirect
                // back to form with errors
                DB::rollback();
                return redirect()->back()
                    ->withErrors($e->getErrors())
                    ->withInput();
            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

            // Commit the queries!
            DB::commit();
            // return

            Session::flash('update', 'Notice update Successfully');
            return redirect()->back();

        }
        exit();
    }


    //notice view

    public  function  noticeView($notice_id){
        $noticeProfile=$this->notice->find($notice_id);
        return view('communication::pages.modal.notice.notice_view',compact('noticeProfile'));
    }


    //notice delete

    public function  noticeDelete($notice_id){
        $noticeProfile=$this->notice->find($notice_id);
        $noticeProfile->delete();
    }

    //notice Cancel

    public function  noticeCancel($notice_id){
        $noticeProfile=$this->notice->find($notice_id);
        $noticeProfile->status=2;
        $noticeProfile->save();
    }

    //get notice by user type

    public  function getNoticeByUserType($user_type){
        $notices=$this->notice->where('institute_id',$this->academicHelper->getInstitute())->where('campus_id',$this->academicHelper->getCampus())->where('user_type',$user_type)->where('status',1)->orderBy('notice_date','desc')->limit(10)->get();
        return view('communication::pages.modal.notice.notice_list',compact('notices'));
    }


    //notice edit

    public function noticeEdit($notice_id){
        $noticeProfile=$this->notice->find($notice_id);
        $notices=$this->notice->where('institute_id',$this->academicHelper->getInstitute())->where('campus_id',$this->academicHelper->getCampus())->orderBy('id','desc')->paginate(10);
        return view('communication::pages.notice.index',compact('noticeProfile','notices'));

    }

    ///////////// ajax or api request //////////////////
    public function getNoticeList(Request $request)
    {
        // request details
        $user = $request->input('user');
        $campus = $request->input('campus');
        $instituteId = $request->input('institute');
        // notice list
        $noticeList = $this->notice->where(['institute_id'=>$instituteId, 'campus_id'=>$campus, 'user_type'=>$user, 'status'=>1])
            ->orderBy('notice_date','desc')->limit(10)->get();

        // response date
        $responseData = array();
        // notice looping
        if($noticeList->count()>0){
            foreach ($noticeList as $notice){
                // notice file
                $noticeFile = $notice->notice_file;
                // response date set
                $responseData[] = (object)[
                    'id'=>$notice->id,
                    'date'=>$notice->notice_date,
                    'title'=>$notice->title,
                    'description'=>$notice->desc,
                    'file'=>$noticeFile!=null?1:0,
                    'file_path'=>$noticeFile!=null?url('/assets/communication/notice/'.$notice->content()->file_name):'',
                    'user_type'=>$notice->user_type,
                    'campus_id'=>$notice->campus_id,
                    'institute_id'=>$notice->institute_id,
                ];
            }
            // return
            return $responseData;
        }else{
            return $noticeList;
        }

    }
}
