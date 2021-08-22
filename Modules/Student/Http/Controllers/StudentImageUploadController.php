<?php

namespace Modules\Student\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Student\Entities\StudentAttachment;
use App\Content;
use Illuminate\Support\Facades\File;
use App\User;

class StudentImageUploadController extends Controller
{

    private $academicHelper;
    private $academicsYear;
    private $studentProfileView;
    private $studentInformation;
    private $studentAttachment;
    private $user;
    private $content;
    private $photos_path;

    public function __construct(AcademicHelper $academicHelper, StudentProfileView $studentProfileView, AcademicsYear $academicsYear, StudentInformation $studentInformation, User $user, StudentAttachment $studentAttachment, Content $content)
    {
        $this->academicHelper = $academicHelper;
        $this->academicsYear = $academicsYear;
        $this->studentProfileView = $studentProfileView;
        $this->studentInformation = $studentInformation;
        $this->studentAttachment = $studentAttachment;
        $this->user = $user;
        $this->content = $content;
        $this->photos_path = 'assets/users/images/';
    }
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // Academic year
        $academicYears = $this->academicsYear->where([ 'institute_id'=>$this->academicHelper->getInstitute(), 'campus_id'=>$this->academicHelper->getCampus()])->get();
        // all inputs
        $allInputs = array('year' => null, 'level' => null, 'batch' => null, 'section' => null, 'gr_no' => null, 'email' => null);
        // return view with variables
        return view('student::pages.image-upload.image-upload-student',compact('academicYears', 'allInputs'));
    }

    public function create(Request $request)
    {

        $academicYear  = $request->input('academic_year');
        $academicLevel = $request->input('academic_level');
        $batch         = $request->input('batch');
        $section       = $request->input('section');
        // input institute and campus id
        $allSearchInputs['campus'] = $this->academicHelper->getCampus();
        $allSearchInputs['institute'] = $this->academicHelper->getInstitute();
        // qry
        $allSearchInputs = array();

        // check academicYear
        if ($academicYear) $allSearchInputs['academic_year'] = $academicYear;
        // check academicLevel
        if ($academicLevel) $allSearchInputs['academic_level'] = $academicLevel;
        // check batch
        if ($batch) $allSearchInputs['batch'] = $batch;
        // check section
        if ($section) $allSearchInputs['section'] = $section;
        // student list
        $studentList = $this->studentProfileView->where($allSearchInputs)->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')->get(['std_id']);
        // student array list
        $stdArrayList = $this->stdArrayListMaker($studentList);
        //return view('student::pages.image-upload.image-upload-student');
        // html view
        $html = view('student::pages.image-upload.image-upload-form' , compact('stdArrayList'))->render();
        // return
        return ['std_list'=>$stdArrayList, 'html'=>$html, 'std_count'=>count($stdArrayList)];
    }


    public function store(Request $request)
    {
        $photos = $request->file('file');

        if (!is_array($photos)) {
            $photos = [$photos];
        }

        if (!is_dir($this->photos_path)) {
            mkdir($this->photos_path, 0777);
        }

        for ($i = 0; $i < count($photos); $i++) {
            // image file
            $photoFile = $photos[$i];
            // checking image file
            if(!empty($photoFile)){
                // image details
                $name = sha1(date('YmdHis') . str_random(30));
                $save_name = $name . '.' . $photoFile->getClientOriginalExtension();
                $resize_name = $name . str_random(2) . '.' . $photoFile->getClientOriginalExtension();

//            Image::make($photo)->resize(250, null, function ($constraints) {
//                    $constraints->aspectRatio();
//                })->save($this->photos_path . '/' . $resize_name);

                $fileExtension   = $photoFile->getClientOriginalExtension();
                $contentName     = $photoFile->getClientOriginalName();
                $std_id   = pathinfo($contentName, PATHINFO_FILENAME);
                $contentFileName = $save_name;

                // Start transaction!
                DB::beginTransaction();

                try {
                    // student attachment
                    $photoProfile = $this->studentAttachment->where(['std_id'=>$std_id])->first();

                    // update photo
                    if ($photoProfile) {
                        // content profile
                        $contentProfile = $photoProfile->singleContent();
                        // old image path
                        $oldImagePath = $contentProfile->path . $contentProfile->name;
                        $uploaded     = $photoFile->move($this->photos_path, $contentFileName);

                        // checking
                        if ($uploaded) {
                            // update content
                            $contentProfile->name      = $contentFileName;
                            $contentProfile->file_name = $contentFileName;
                            $contentProfile->path      = $this->photos_path;
                            $contentProfile->mime      = $fileExtension;
                            // save content
                            if ($contentProfile->save()) {
                                // check image path
                                if ($oldImagePath != $contentProfile->path . $contentProfile->name) {
                                    // now deleting the post image
                                    File::delete($oldImagePath);
                                }
                            }
                        }
                    } else {
                        // user document
                        $uploaded     = $photoFile->move($this->photos_path, $contentFileName);
                        if($uploaded){
                            $contentProfile = new $this->content();
                            // storing user document
                            $contentProfile->name      = $contentFileName;
                            $contentProfile->file_name = $contentFileName;
                            $contentProfile->path      = $this->photos_path;
                            $contentProfile->mime      = $fileExtension;
                            // save and checking
                            if($contentProfile->save()){
                                // upload file to the student attachment database
                                $studentAttachment = new $this->studentAttachment();
                                // storing student attachment
                                $studentAttachment->std_id     = $std_id;
                                $studentAttachment->doc_id     = $contentProfile->id;
                                $studentAttachment->doc_type   = "PROFILE_PHOTO";
                                $studentAttachment->doc_status = 0;
                                // save student attachment profile
                                $studentAttachment->save();
                            }
                        }

                    }
                } catch (ValidationException $e) {
                    // Rollback and then redirect
                    // back to form with errors
                    DB::rollback();
                    return redirect()->back()->withErrors($e->getErrors())->withInput();
                } catch (\Exception $e) {
                    DB::rollback();
                    throw $e;
                }
            }
            // If we reach here, then data is valid and working.  Commit the queries!
            DB::commit();
        }

        // return
        return Response::json(['message' => 'Image saved Successfully'], 200);
    }



    /**
     * Remove the specified resource from storage.
     * @return Response|array
     */
    public function stdArrayListMaker($studentList)
    {
        // response array
        $responseArray = array();

        // Checking student list
        if($studentList){
            // student list looping
            foreach ($studentList as $stdProfile){
                // student array list input
                $responseArray[] = $stdProfile->std_id;
            }
        }
        // return response array
        return $responseArray;
    }
}
