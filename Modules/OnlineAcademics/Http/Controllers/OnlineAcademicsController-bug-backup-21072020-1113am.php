<?php

namespace Modules\OnlineAcademics\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Academics\Entities\AcademicsLevel;
use Modules\Academics\Entities\ClassSubject;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\Academics\Entities\SubjectTeacher;
use Modules\Academics\Entities\ClassPeriod;
use Modules\Academics\Entities\ClassPeriodCategory;
use Modules\Academics\Entities\ClassSectionPeriod;
use Modules\Academics\Entities\ManageTimetable\TimeTable;
use Modules\OnlineAcademics\Entities\OnlineClassHistory;
use Modules\OnlineAcademics\Entities\OnlineClassSchedule;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Subject;
use App\OnlineClassTopic;
use Redirect;
use Session;
use Validator;


class OnlineAcademicsController extends Controller
{
    private     $academicsYear;
    private     $academicsLevel;
    private     $academicHelper;
    private     $subject;
    private     $OnlineClassTopic;
    private     $employeeInformation;
    private     $subjectTeacher;

    private     $timeTable;
    private     $classPeriod;
    private     $classSectionPeriodCategory;
    private     $classPeriodCategory;
    private     $classSubject;

    private     $batch;
    private     $section;
    private     $studentInformation;
    private     $studentProfileView;
    private     $OnlineClassHistory;
    private     $OnlineClassSchedule;

    public function __construct(TimeTable $timeTable,AcademicHelper $academicHelper, AcademicsLevel $academicsLevel, ClassSubject $classSubject, AcademicsYear $academicsYear, Subject $subject,OnlineClassTopic $OnlineClassTopic,EmployeeInformation $employeeInformation, SubjectTeacher $subjectTeacher, ClassPeriod $classPeriod, ClassSectionPeriod $classSectionPeriodCategory, ClassPeriodCategory $classPeriodCategory, Batch $batch, Section $section, StudentInformation $studentInformation, StudentProfileView $studentProfileView, OnlineClassHistory $OnlineClassHistory, OnlineClassSchedule $OnlineClassSchedule)

    {
        
        $this->timeTable                    = $timeTable;
        $this->academicsYear                = $academicsYear;
        $this->academicsLevel               = $academicsLevel;
        $this->classSubject                 = $classSubject;
        $this->academicHelper               = $academicHelper;
        $this->subject                      = $subject;
        $this->OnlineClassTopic             = $OnlineClassTopic;
        $this->employeeInformation          = $employeeInformation;
        $this->subjectTeacher               = $subjectTeacher;
        $this->classPeriod                  = $classPeriod;
        $this->classSectionPeriodCategory   = $classSectionPeriodCategory;
        $this->classPeriodCategory          = $classPeriodCategory;
        $this->batch                        = $batch;
        $this->section                      = $section;
        $this->studentInformation           = $studentInformation;
        $this->studentProfileView           = $studentProfileView;
        $this->OnlineClassHistory           = $OnlineClassHistory;
        $this->OnlineClassSchedule          = $OnlineClassSchedule;

    }


    public function index($topic_name)
    {
        // all academics levels

  $empList        = EmployeeInformation::where('institute_id', institution_id())->where('campus_id',campus_id())->where('status',1)->get();

 
     $personalInfo  = $this->studentInformation->where(['user_id'=> Auth::user()->id ])->first();

        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();

        $topic_name = (isset($topic_name) ? $topic_name : "classtopic");

        switch ($topic_name) {

            case 'classtopic':                 
                $qry = [
                    'institute_id'=>$this->academicHelper->getInstitute(),
                    'campus_id'=>$this->academicHelper->getCampus()
                ];
                // all academics year
                $academicYears  = $this->academicsYear->where($qry)->get();
                $subjectList    = $this->subject->get();

                $topic_list     = $this->OnlineClassTopic->get();

                return view('onlineacademics::pages.classtopic',compact('topic_name','academicYears','subjectList','topic_list','allAcademicsLevel'))->with('page');
                break;

            case 'ClassHistory':               
                $qry = [
                    'institute_id'=>$this->academicHelper->getInstitute(),
                    'campus_id'=>$this->academicHelper->getCampus()
                ];
                // all academics year
                $academicYears  = $this->academicsYear->where($qry)->get();
             


                $subjectList    = $this->subject->get();

                $topic_list     = $this->OnlineClassTopic->get();

                $empList = EmployeeInformation::where('institute_id', institution_id())->where('campus_id',campus_id())->where('status',1)->get();

                return view('onlineacademics::pages.ClassHistory',compact('topic_name','academicYears','subjectList','topic_list','empList','allAcademicsLevel','personalInfo'))->with('page');
                break;     

            case 'onlineclass':
                $qry = [
                    'institute_id'=>$this->academicHelper->getInstitute(),
                    'campus_id'=>$this->academicHelper->getCampus()
                ];
                // all academics year
                $academicYears  = $this->academicsYear->where($qry)->get();
                
                 

                $subjectList    = $this->subject->get();

                $topic_list     = $this->OnlineClassTopic->get();

                $empList = EmployeeInformation::where('institute_id', institution_id())->where('campus_id',campus_id())->where('status',1)->get();

                
                return view('onlineacademics::pages.onlineclass',compact('topic_name','academicYears','subjectList','topic_list','empList','allAcademicsLevel'))->with('page');
                break;     
         
                default:
                    return view('onlineacademics::pages.classtopic',compact('topic_name'))->with('page');
                break;
         }
    }
    
    public function create()
    {
        return view('onlineacademics::create');
    }
    
    public function store(Request $request)
    {
    }
    
    public function show()
    {
        return view('onlineacademics::show');
    }

    public function edit($id)
    {
        $topicId = $id ? $id : 0;
        $topic_name = "classtopic";

        $topic_info = OnlineClassTopic::find($topicId);

        $qry = [
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus()
        ];
        // all academics year
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();

        $topic_list     = $this->OnlineClassTopic->get();

        Session::flash('message', 'Edit Data Selected.');

        return view('onlineacademics::pages.classtopic',compact('topic_name','allAcademicsLevel','topic_list','topic_info'))->with('page');

    }
    
    public function update(Request $request,$id)
    {

    
        
        $updateId           = $id;
        $institute_id       = $this->academicHelper->getInstitute();
        $campus_id          = $this->academicHelper->getCampus();
        $class_topic        = $request->input('class_topic');

        $academic_year_name = DB::table('academics_year')
        ->where('institute_id',$institute_id)
        ->where('campus_id',$campus_id)
        ->where('id',$request->input('academic_year_id'))   
        ->value('year_name');

        $academic_level_name = DB::table('academics_level')
        ->where('institute_id',$institute_id)
        ->where('campus_id',$campus_id)
        ->where('id',$request->input('academic_level_id'))   
        ->value('level_name');

        $academic_class_name = DB::table('batch')
        ->where('institute',$institute_id)
        ->where('campus',$campus_id)
        //->where('academics_year_id',$request->input('academic_year_id')) 
        ->where('academics_level_id',$request->input('academic_level_id'))
        ->where('id',$request->input('batch'))   
        ->value('batch_name');

        $academic_section_name = DB::table('section')
        ->where('institute',$institute_id)
        ->where('campus',$campus_id)
        //->where('academics_year_id',$request->input('academic_year_id')) 
        ->where('id',$request->input('section'))   
        ->value('section_name');

        $academic_subject_id = DB::table('class_subjects')
        ->where('class_id',$request->input('batch'))
        ->where('section_id',$request->input('section'))
        ->where('id',$request->input('subject'))   
        ->value('subject_id');

        $academic_subject_name = DB::table('subject')
        ->where('institute',$institute_id)
        ->where('campus',$campus_id)
        //->where('academic_year',$request->input('academic_year_id')) 
        ->where('id',$request->input('subject'))
        ->value('subject_name');


        // all class subject teachers
        $subjectTeachers = $this->subjectTeacher->where(['class_subject_id'=>$request->input('subject'),'is_active'=>1])->first();

        $assign_teacher_id = $subjectTeachers->employee_id;

        $assign_teacher_name = DB::table('employee_informations')
        ->where('institute_id',$institute_id)
        ->where('campus_id',$campus_id)
        ->where('id',$assign_teacher_id)
        ->first();

        
        

        $UpdateInfo = DB::table('online_class_topics')->where('id', $updateId)->first();

        $class_teacher_name    = $UpdateInfo->class_teacher;
        $teacherFullName = '';
        if(isset($class_teacher_name) && !empty($class_teacher_name)){
            $teacherFullName = $class_teacher_name;
        }
        else{
            $teacherFullName = $assign_teacher_name->first_name.' '.$assign_teacher_name->last_name;
        }


        $topic_attachment = "";
        
        if($request->hasFile('topic_attachment') == ""){
            $topic_attachment   = $UpdateInfo->file_path;
            
        }
        else{

            $getPhoto = $request->file('topic_attachment');
            $topic_attachment = time().'.'.$getPhoto->getClientOriginalExtension();
            $destinationPath = public_path('/upload/online_class_topic/');

            $getPhoto->move($destinationPath, $topic_attachment);
        }    

        $update = OnlineClassTopic::where("id", $updateId)->update([
            "institute_id"  => $institute_id,
            "campus_id" => $campus_id,
            "academic_year_id" => $request->input('academic_year_id') ,
            "academic_year" => $academic_year_name ? $academic_year_name : 'null',
            "academic_level_id" => $request->input('academic_level_id'),
            "academic_level" => $academic_level_name ? $academic_level_name : 'null',
            "academic_class_id" => $request->input('batch'),
            "academic_class" => $academic_class_name ? $academic_class_name : 'null',
            "academic_section_id" => $request->input('section'),
            "academic_section" => $academic_section_name ? $academic_section_name : 'null',
            "class_subject_id" => $request->input('subject'),
            "class_subject" => $academic_subject_name ? $academic_subject_name : 'null',
            "class_teacher" => $teacherFullName ? $teacherFullName : 'null',
            "class_topic" => $class_topic ? $class_topic : 'null',
            "file_path" => $topic_attachment ? $topic_attachment : 'null',     
            'updated_at'=>Carbon::now()
        ]);


        $qry = [
        'institute_id'=>$this->academicHelper->getInstitute(),
        'campus_id'=>$this->academicHelper->getCampus()
        ];
        // all academics year
        $academicYears  = $this->academicsYear->where($qry)->get();
        $subjectList    = $this->subject->get();

        $topic_list     = $this->OnlineClassTopic->get();
        $topic_name     = "classtopic";
        
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();

        if($update){
            Session::flash('message', 'Data Update successfully.');
            
            return view('onlineacademics::pages.classtopic',compact('topic_name','allAcademicsLevel','subjectList','topic_list'))->with('page');
        }
        else{
            Session::flash('message', 'Data Update Failed');
            return redirect()->back();
        }

    }

    public function destroy($id)
    {

        $ClassTopic = $this->OnlineClassTopic->find($id)->delete();
        if($ClassTopic){
            Session::flash('message', 'Success!Data has been deleted successfully.');
            return redirect()->back();
        } else{
            Session::flash('message', 'Failed!Data has not been deleted successfully.');
            return redirect()->back();
        }
    }

    public function getTopicInfo(Request $request)
    {

        //dd($request->all());
        // all academics levels
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();

        $topic_name = (isset($topic_name) ? $topic_name : "classtopic");

        $institute_id       = $this->academicHelper->getInstitute();
        $campus_id          = $this->academicHelper->getCampus();
        $class_topic        = $request->input('class_topic');

        $academic_year_id       = $request->input('academic_year_id');
        $academic_level_id      = $request->input('academic_level_id');
        $academic_class_id      = $request->input('batch');
        $academic_section_id    = $request->input('section');
        $academic_subject_id    = $request->input('subject');

        

        $validator = Validator::make($request->all(), [
            'academic_level_id'=>'required',
            'batch'=>'required',
            'section'=>'required',
            'subject'=>'required',
            'class_topic'=>'required'
        ]);

        if ($validator->passes()){
            
            if(!empty($class_topic)){

                if ( !empty( $request->except('_token') ) ) {

                    $academic_subject_id = DB::table('class_subjects')
                    ->where('class_id',$request->input('batch'))
                    ->where('section_id',$request->input('section'))
                    ->where('id',$request->input('subject'))   
                    ->value('subject_id');

                    $CheckTopic = DB::table('online_class_topics')
                    ->where('institute_id', $institute_id)
                    ->where('campus_id', $campus_id)
                    ->where('academic_year_id', $request->input('academic_year_id'))
                    ->where('academic_level_id', $request->input('academic_level_id'))
                    ->where('academic_class_id', $request->input('batch'))
                    ->where('academic_section_id', $request->input('section'))
                    ->where('class_subject_id', $academic_subject_id)
                    ->where('class_topic', 'like','%'.$class_topic.'%')->first();
                    
                    if(isset($CheckTopic)){
                        
                        $topic_list     = $this->OnlineClassTopic->get();

                        $qry = [
                            'institute_id'=>$this->academicHelper->getInstitute(),
                            'campus_id'=>$this->academicHelper->getCampus()
                        ];
                        // all academics year
                        $academicYears  = $this->academicsYear->where($qry)->get();

                        Session::flash('error', 'Topic Name All Ready Exit');
                        return view('onlineacademics::pages.classtopic',compact('academicYears','topic_list','topic_name'))->with('page');
                    }
                    else{

                        $topic_attachment = "";
                        if($request->hasFile('topic_attachment')){
                            $getPhoto = $request->file('topic_attachment');
                            $topic_attachment = time().'.'.$getPhoto->getClientOriginalExtension();
                            $destinationPath = public_path('/upload/online_class_topic/');
                            
                            $getPhoto->move($destinationPath, $topic_attachment);
                        }

                        
                        
                        $academic_year_name = DB::table('academics_year')
                        ->where('institute_id',$institute_id)
                        ->where('campus_id',$campus_id)
                        ->where('id',$request->input('academic_year_id'))   
                        ->value('year_name');

                        $academic_level_name = DB::table('academics_level')
                        ->where('institute_id',$institute_id)
                        ->where('campus_id',$campus_id)
                        ->where('id',$request->input('academic_level_id'))   
                        ->value('level_name');

                        $academic_class_name = DB::table('batch')
                        ->where('institute',$institute_id)
                        ->where('campus',$campus_id)
                        //->where('academics_year_id',$request->input('academic_year_id')) 
                        ->where('academics_level_id',$request->input('academic_level_id'))
                        ->where('id',$request->input('batch'))   
                        ->value('batch_name');

                        $academic_section_name = DB::table('section')
                        ->where('institute',$institute_id)
                        ->where('campus',$campus_id)
                        //->where('academics_year_id',$request->input('academic_year_id')) 
                        ->where('id',$request->input('section'))   
                        ->value('section_name');

                        $academic_subject_id = DB::table('class_subjects')
                        ->where('class_id',$request->input('batch'))
                        ->where('section_id',$request->input('section'))
                        ->where('id',$request->input('subject'))   
                        ->value('subject_id');

                        $academic_subject_name = DB::table('subject')
                        ->where('institute',$institute_id)
                        ->where('campus',$campus_id)
                        //->where('academic_year',$request->input('academic_year_id')) 
                        ->where('id',$academic_subject_id)   
                        ->value('subject_name');


                        // all class subject teachers
                        $subjectTeachers = $this->subjectTeacher->where(['class_subject_id'=>$request->input('subject'),'is_active'=>1])->first();

                        $assign_teacher_id = $subjectTeachers->employee_id;

                        $assign_teacher_name = DB::table('employee_informations')
                        ->where('institute_id',$institute_id)
                        ->where('campus_id',$campus_id)
                        ->where('id',$assign_teacher_id)
                        ->first();

                        $teacherFullName = $assign_teacher_name->first_name.' '.$assign_teacher_name->last_name;

                        $NewTopic = new OnlineClassTopic();

                        $NewTopic->institute_id             = $institute_id;
                        $NewTopic->campus_id                = $campus_id;
                        $NewTopic->academic_year_id         = $request->input('academic_year_id');
                        $NewTopic->academic_year            = $academic_year_name ? $academic_year_name : 'null';
                        $NewTopic->academic_level_id        = $request->input('academic_level_id');
                        $NewTopic->academic_level           = $academic_level_name ? $academic_level_name : 'null';
                        $NewTopic->academic_class_id        = $request->input('batch');
                        $NewTopic->academic_class           = $academic_class_name ? $academic_class_name : 'null';
                        $NewTopic->academic_section_id      = $request->input('section');
                        $NewTopic->academic_section         = $academic_section_name ? $academic_section_name : 'null';
                        $NewTopic->class_subject_id         = $academic_subject_id ? $academic_subject_id : 'null';
                        $NewTopic->class_subject            = $academic_subject_name ? $academic_subject_name : 'null';
                        $NewTopic->class_teacher            = $teacherFullName ? $teacherFullName : 'null';
                        $NewTopic->class_topic              = $class_topic ? $class_topic : 'null';
                        $NewTopic->file_path                = $topic_attachment ? $topic_attachment : 'null';
                        $NewTopic->class_teacher_id         = $assign_teacher_id ? $assign_teacher_id : 'null';

                        $NewTopic->save();

                        Session::flash('message', 'New Topic Created Successfully');
                        //return redirect()->back();

                        $qry = [
                            'institute_id'=>$this->academicHelper->getInstitute(),
                            'campus_id'=>$this->academicHelper->getCampus()
                        ];
                        // all academics year
                        $academicYears  = $this->academicsYear->where($qry)->get();
                        $topic_list     = $this->OnlineClassTopic->get();
                        $topic_name     = "classtopic";
                        return view('onlineacademics::pages.classtopic',compact('academicYears','topic_list','topic_name','allAcademicsLevel'))->with('page');

                    }
     
                }    
                
            }
            else{

                    $academic_year_name = DB::table('academics_year')
                    ->where('institute_id',$institute_id)
                    ->where('campus_id',$campus_id)
                    ->where('id',$request->input('academic_year_id'))   
                    ->value('year_name');

                    $academic_level_name = DB::table('academics_level')
                    ->where('institute_id',$institute_id)
                    ->where('campus_id',$campus_id)
                    ->where('id',$request->input('academic_level_id'))   
                    ->value('level_name');

                    $academic_class_name = DB::table('batch')
                    ->where('institute',$institute_id)
                    ->where('campus',$campus_id)
                    //->where('academics_year_id',$request->input('academic_year_id')) 
                    ->where('academics_level_id',$request->input('academic_level_id'))
                    ->where('id',$request->input('batch'))   
                    ->value('batch_name');

                    $academic_section_name = DB::table('section')
                    ->where('institute',$institute_id)
                    ->where('campus',$campus_id)
                    //->where('academics_year_id',$request->input('academic_year_id')) 
                    ->where('id',$request->input('section'))   
                    ->value('section_name');

                    $academic_subject_id = DB::table('class_subjects')
                    ->where('class_id',$request->input('batch'))
                    ->where('section_id',$request->input('section'))
                    ->where('id',$request->input('subject'))   
                    ->value('subject_id');

                    $academic_subject_name = DB::table('subject')
                    ->where('institute',$institute_id)
                    ->where('campus',$campus_id)
                    //->where('academic_year',$request->input('academic_year_id')) 
                    ->where('id',$academic_subject_id)   
                    ->value('subject_name');

                    $academic_year_id       = $request->input('academic_year_id');
                    $academic_year          = $academic_year_name ? $academic_year_name : 'null';
                    $academic_level_id      = $request->input('academic_level_id');
                    $academic_level         = $academic_level_name ? $academic_level_name : 'null';
                    $academic_class_id      = $request->input('batch');
                    $academic_class_name    = $academic_class_name ? $academic_class_name : 'null';
                    $academic_section_id    = $request->input('section');
                    $academic_section       = $academic_section_name ? $academic_section_name : 'null';
                    $academic_subject_id    = $academic_subject_id ? $academic_subject_id : 'null';
                    $academic_subject       = $academic_subject_name ? $academic_subject_name : 'null';
                    $class_topic            = $request->input('class_topic');

                    $topic_list = "";

                    if ( !empty( $request->except('_token') ) ) {

                        $topic_list = DB::table('online_class_topics')
                        ->when($institute_id, function ($query, $institute_id) {
                            return $query->where('institute_id', $institute_id);
                        })
                        ->when($campus_id, function ($query, $campus_id) {
                            return $query->where('campus_id', $campus_id);
                        })
                        ->when($academic_level_id, function ($query, $academic_level_id) {
                            return $query->where('academic_level_id', $academic_level_id);
                        })
                        ->when($academic_class_id, function ($query, $academic_class_id) {
                            return $query->where('academic_class_id', $academic_class_id);
                        })
                        ->when($academic_section_id, function ($query, $academic_section_id) {
                            return $query->where('academic_section_id', $academic_section_id);
                        })
                        ->when($academic_subject_id, function ($query, $academic_subject_id) {
                            return $query->where('class_subject_id', $academic_subject_id);
                        })
                        //->toSql();
                        ->get();
                    
                    }

                    $qry = [
                        'institute_id'=>$this->academicHelper->getInstitute(),
                        'campus_id'=>$this->academicHelper->getCampus()
                    ];
                    // all academics year
                    $academicYears  = $this->academicsYear->where($qry)->get();
                    $topic_name = "classtopic";

                    return view('onlineacademics::pages.classtopic',compact('topic_list','topic_name','allAcademicsLevel'))->with('page');

            }

        }
        else {
            
            $topic_list     = $this->OnlineClassTopic->get();
            Session::flash('warning', 'Invalid Information. please try with correct Information');
            return view('onlineacademics::pages.classtopic',compact('topic_list','topic_name','allAcademicsLevel'))->with('page')->withErrors($validator);
        }
    }

    public function findtopic(Request $request)
    {
        
        //dd($request->all());
        $institute_id   =   $this->academicHelper->getInstitute();
        $campus_id      =   $this->academicHelper->getCampus();
        // input details
        $level_id       = $request->input('level_id');
        $class_id       = $request->input('class_id');
        $section_id     = $request->input('section_id');
        $subject_id     = $request->input('subject_id');
        $class_topic    = $request->input('class_topic');

        $academic_subject_id = DB::table('class_subjects')
        ->where('class_id',$request->input('class_id'))
        ->where('section_id',$request->input('section_id'))
        ->where('id',$request->input('subject_id'))   
        ->value('subject_id');

        $topic_list = $this->OnlineClassTopic
        ->when($institute_id, function ($query, $institute_id) {
            return $query->where('institute_id', $institute_id);
        })
        ->when($campus_id, function ($query, $campus_id) {
            return $query->where('campus_id', $campus_id);
        })
        ->when($level_id, function ($query, $level_id) {
            return $query->where('academic_level_id', $level_id);
        })
        ->when($class_id, function ($query, $class_id) {
            return $query->where('academic_class_id', $class_id);
        })
        ->when($section_id, function ($query, $section_id) {
            return $query->where('academic_section_id', $section_id);
        })
        ->when($academic_subject_id, function ($query, $academic_subject_id) {
            return $query->where('class_subject_id', $academic_subject_id);
        })
        ->when($class_topic, function ($query, $class_topic) {
            return $query->where('class_topic', 'like','%'.$class_topic.'%');
        })
        //->toSql();
        ->get();

        return view('onlineacademics::pages.includes.topic-list',compact('topic_list'));
    }

    public function ajax_topic(Request $request)
    {
        // input details
        $class_id      = $request->input('class_id');
        $section_id    = $request->input('section_id');
        $subject_id    = $request->input('subject_id');
        $teacher_id    = $request->input('teacher_id');


        $academic_subject_id = DB::table('class_subjects')
        ->where('class_id',$request->input('class_id'))
        ->where('section_id',$request->input('section_id'))
        ->where('id',$request->input('subject_id'))   
        ->value('subject_id');


        // response array
        $data = array();
        // all class subject
        $allClassSubjectTopic = $this->OnlineClassTopic->where(['academic_class_id'=>$class_id, 'academic_section_id'=>$section_id, 'class_subject_id'=>$academic_subject_id, 'class_teacher_id'=>$teacher_id])->orderBy('id', 'ASC')->get();

        $data = [];
        // active user information
        $userInfo = Auth::user();
        // checking user role
        if($userInfo->hasRole('teacher')){
            // find user employee profile
            $teacherInfoProfile = $userInfo->employeeInformation();
            // find class teacher subject list
            $classTeacherSubjects = $this->subjectTeacher->where(['employee_id'=>$teacherInfoProfile->id, 'is_active'=>1])->get();
            // Teacher subject looping for finding subjects of this class-section
            foreach ($classTeacherSubjects as $teacherSubject){
                // find class subject profile anc checking
                if($classSubject = $teacherSubject->classSubject()){
                    // checking class subject details
                    if(($classSubject->class_id==$class) AND ($classSubject->section_id==$section)){
                        $data[] = $this->ClassSubjectTopicReturnPack($classSubject);
                    }
                }
            }
        }else{
            // looping for adding division into the batch name
            foreach ($allClassSubjectTopic as $classSubject) {
                $data[] = $this->ClassSubjectTopicReturnPack($classSubject);
            }
        }
        //then sent this data to ajax success
        return $data;
    }

    public function ClassSubjectTopicReturnPack($classSubject)
    {
        return [
            'id' => $classSubject->id,
            'sub_topic' => $classSubject->class_topic
        ];
    }

    public function ClassHistory(Request $request)
    {
        
        //dd($request->all());

        $timestamp = strtotime($request->input('start_date'));
        $day1 = date('l', $timestamp);

        $timestamp2 = strtotime($request->input('end_date'));
        $day2 = date('l', $timestamp2);
        //var_dump($day1,$day2);



        $start_date = strtotime($request->input('start_date')); // or your date as well
        $end_date   = strtotime($request->input('end_date'));
        $datediff   = $start_date - $end_date;

        //echo round($datediff / (60 * 60 * 24));
        $total_Day_Date = [];
        for ($i=$start_date; $i<=$end_date; $i+=86400) {  
            //echo date("m-d-Y", $i).'<br />'; 
            $day    = date('l', $i);
            $date   = date("m-d-Y", $i);
            $total_Day_Date[$day] =  $date; //date("m-d-Y", $i);
            //$totalDay[]  =  date('l', $i);  
        } 

        $topic_name = "ClassHistory";
        // all academics levels
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();

        $qry = [
            'institute_id'  =>$this->academicHelper->getInstitute(),
            'campus_id'     =>$this->academicHelper->getCampus()
        ];
        // all academics year
        $academicYears  = $this->academicsYear->where($qry)->get();
        $subjectList    = $this->subject->get();
        $topic_list     = $this->OnlineClassTopic->get();
        $empList        = EmployeeInformation::where('institute_id', institution_id())->where('campus_id',campus_id())->where('status',1)->get();


        $institute_id               = $this->academicHelper->getInstitute();
        $campus_id                  = $this->academicHelper->getCampus();
        $level                      = $request->input('level');
        $batch                      = $request->input('batch');
        $section                    = $request->input('section');
        $shift                      = $request->input('shift');
        $subject                    = $request->input('subject');
        $teacher_id                 = $request->input('teacher_id');
        $subject_class_topic        = $request->input('subject_class_topic');
        $start_date                 = date("m-d-Y", strtotime($request->input('start_date')));
        $end_date                   = date("m-d-Y", strtotime($request->input('end_date')));
        $status                     = $request->input('status');

        //die();

        /* New Search Part Integration Start 02/07/2020 */
        //dd($request->all());
        //$institute_id   =   $this->academicHelper->getInstitute();
        //$campus_id      =   $this->academicHelper->getCampus();
       

        $academic_subject_id = DB::table('class_subjects')
        ->where('class_id',$request->input('class_id'))
        ->where('section_id',$request->input('section_id'))
        ->where('id',$request->input('subject_id'))   
        ->value('subject_id');

        $class_schedule_list = $this->OnlineClassSchedule
        ->when($institute_id, function ($query, $institute_id) {
            return $query->where('institute_id', $institute_id);
        })
        ->when($campus_id, function ($query, $campus_id) {
            return $query->where('campus_id', $campus_id);
        })
        ->when($level, function ($query, $level) {
            return $query->where('academic_level_id', $level);
        })
        ->when($batch, function ($query, $batch) {
            return $query->where('academic_class_id', $batch);
        })
        ->when($section, function ($query, $section) {
            return $query->where('academic_section_id', $section);
        })
        ->when($shift, function ($query, $shift) {
            return $query->where('academic_shift_id', $shift);
        })
        ->when($academic_subject_id, function ($query, $academic_subject_id) {
            return $query->where('class_subject_id', $academic_subject_id);
        })
        ->when($teacher_id, function ($query, $teacher_id) {
            return $query->where('class_teacher_id', $teacher_id);
        })
        ->when($subject_class_topic, function ($query, $subject_class_topic) {
            return $query->where('class_topic_id', $subject_class_topic);
        })
         ->when($start_date, function ($query, $start_date) {
             return $query->where('class_opening_date', $start_date);
         })
         ->when($end_date, function ($query, $end_date) {
             return $query->where('class_opening_date', $end_date);
         })
        ->when($status, function ($query, $status) {
            return $query->where('class_status', $status);
        })
        //->toSql();
        ->get();

        $scheduledData = [];
        foreach($class_schedule_list as $scheduledList){
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->institute_id;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->campus_id;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->academic_level_id;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->academic_class_id;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->academic_section_id;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->academic_shift_id;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_subject_id;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_teacher_id;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_topic_id;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_opening_date;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_routine_time;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_status;

            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_teacher_remarks;

            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_total_student;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->student_present;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->student_absent;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->student_leave;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_conduct_time;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_teacher_status;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_start_time;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_end_time;

            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_topic_name;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_duration_time;
            $scheduledData[$scheduledList->class_subject_id][] = $scheduledList->class_scheduled_id;
        }




        /* New Search Part Integration Start 02/07/2020 */

        // all timetables
        $allTimetables = $this->timeTable->where([
            'batch'=>$batch,
            'section'=>$section,
            'shift'=>$shift,
            //'academic_year'=>$academicYear,
            'campus'=>$campus_id,
            'institute'=>$institute_id
        ])->get();

        // batch section assigned period id
        $batchSectionPeriodId = $this->getBatchSectionPeriodCategoryId($institute_id, $campus_id, null, $level, $batch, $section, $shift);
        // all class periods
        $allClassPeriods = $this->getAcademicClassPeriods($batchSectionPeriodId, $institute_id, $campus_id, null);
        // all class subject
        $allClassSubjects = $this->classSubject->where(['class_id'=>$batch,'section_id'=>$section])->get();

        $PeriodList     = [];
        $ClassDuration  = [];
        
        foreach($allClassPeriods as $key=>$period){

           
            $period->period_start_hour % 10 ==  $period->period_start_hour?'0':'';
            
            $period->period_start_min % 10  ==  $period->period_start_min?'0':'';
            
            $start_time_format = $period->period_start_meridiem == 0?'AM':'PM';

            $period_start_hour = '';
            if($period->period_start_hour < 10){
                $period_start_hour = '0'.$period->period_start_hour;
            }
            else{
                $period_start_hour = $period->period_start_hour;
            }

            $period_start_min = '';
            if($period->period_start_min < 10){
                $period_start_min = '0'.$period->period_start_min;
            }
            else{
                $period_start_min = $period->period_start_min;
            }

            //$starting_time  = $period->period_start_hour.':'.$period->period_start_min.' '.$start_time_format;

            $starting_time  = $period_start_hour.':'.$period_start_min.' '.$start_time_format;

            $period->period_end_hour % 10 ==  $period->period_end_hour?'0':'';
            $period->period_end_min % 10  ==  $period->period_end_min?'0':'';
            $end_time_format = $period->period_end_meridiem  ==  0 ? 'AM' : 'PM';


            $period_end_hour = '';
            if($period->period_end_hour < 10){
                $period_end_hour = '0'.$period->period_end_hour;
            }
            else{
                $period_end_hour = $period->period_end_hour;
            }

            $period_end_min = '';
            if($period->period_end_min < 10){
                $period_end_min = '0'.$period->period_end_min;
            }
            else{
                $period_end_min = $period->period_end_min;
            }

            //$ending_time    = $period->period_end_hour.':'.$period->period_end_min.' '.$end_time_format;

            $ending_time    = $period_end_hour.':'.$period_end_min.' '.$end_time_format;

            $PeriodList[$key+1]   = $starting_time.' - '.$ending_time;

            // $v =  strtotime($ending_time) - strtotime($starting_time);
            // echo date("h:i", $v);

            $to_time    = strtotime($period->period_end_hour.':'.$period->period_end_min);
            $from_time  = strtotime($period->period_start_hour.':'.$period->period_start_min);
            //echo "Time Check For ".round(abs($to_time - $from_time) / 60,2). " minute"."<br/>";

            $ClassDuration[$key+1] = round(abs($to_time - $from_time) / 60,2);
        }

    
        $ClassName = $this->batch->where(['academics_level_id'=>$level, 'campus'=> $campus_id, 'institute'=>$institute_id,'id'=>$batch])->first();

        $SectionName = $this->section->where(['batch_id' => $batch, 'institute'=>$institute_id])->first();


        $topic_info = OnlineClassTopic::where(['academic_level_id'=>$level, 'academic_class_id'=>$batch, 'academic_section_id'=>$section])->get();

        $topicList = [];
        if(isset($topic_info) && !empty($topic_info) && is_array($topic_info)){
            foreach($topic_info as $key=>$topic){
                $topicList[$key+1] = $topic->class_topic;
            }
        }
        else{
            foreach($topic_info as $key=>$topic){
                $topicList[$key+1] = "Topic Not Found";
            }
        }

        $studentList = $this->studentProfileView
            ->where('batch',$batch)
            ->where('section',$section)
            ->where('academic_level',$level)
            ->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')
            ->count();
        
        //Class subject Topic Start
        $level          =   $request->input('level');
        $batch          =   $request->input('batch');
        $section        =   $request->input('section');

        $ClassSubjectTopic = $this->OnlineClassTopic->where(['institute_id'=>$institute_id,'campus_id'=>$campus_id,'academic_level_id'=>$level,'academic_class_id'=>$batch, 'academic_section_id'=>$section])->orderBy('id', 'ASC')->get();

        $topicNameList = [];
        foreach($ClassSubjectTopic as $topicName){
            $topicNameList[$topicName->class_subject_id] = $topicName->class_topic;
        }    
        //Class subject Topic End

        //Class History Status Start
        $ClassHistoryStatus = $this->OnlineClassHistory->where(['institute_id'=>$institute_id,'campus_id'=>$campus_id,'academic_level_id'=>$level,'academic_class_id'=>$batch, 'academic_section_id'=>$section])->orderBy('id', 'ASC')->get();

        $HistoryStatusList = [];
        foreach($ClassHistoryStatus as $ClassStatus){
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->conduct_time;
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->teacher_class_status;
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->student_present;
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->student_absent;
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->student_leave;
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->teacher_remarks;
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->status;  
        }
        //Class History Status End

        //echo "<pre>";
       //  print_r($PeriodList[1]);
       //  print_r (explode("-",$PeriodList[1]));

       //  print_r($PeriodList[2]);
       //  print_r($PeriodList[3]);
       //  print_r($PeriodList[4]);
       // print_r($PeriodList[5]);
       // print_r($PeriodList[6]);
       //  exit;
        //print_r($allClassPeriods);
        //print_r($topic_info);
        //print_r($topicList);
        //print_r($ClassSubjectTopic);
        //print_r($topicNameList);
        //print_r($HistoryStatusList);
        //print_r($class_schedule_list);
        //print_r($allTimetables);
        //print_r($scheduledData);
        //print_r($ClassDuration);
        //echo "</pre>";

        //die();


        //echo "Check-".$HistoryStatusList[195][0];
        //die();
        return view('onlineacademics::pages.includes.class-schedule-list',compact('topic_name','academicYears','subjectList','topic_list','empList','allAcademicsLevel','allClassPeriods', 'allClassSubjects', 'allTimetables', 'batchSectionPeriodId','total_Day_Date','PeriodList','ClassName','SectionName','topicList','studentList', 'ClassDuration','topicNameList','HistoryStatusList','scheduledData','shift'))->with('page');

    }

    public function ClassHistory_old(Request $request)
    {
        //echo "IID-".$institute_id               = $this->academicHelper->getInstitute();
        //echo "CID-".$campus_id                  = $this->academicHelper->getCampus();
        //dd($request->all());

        $timestamp = strtotime($request->input('start_date'));
        $day1 = date('l', $timestamp);

        $timestamp2 = strtotime($request->input('end_date'));
        $day2 = date('l', $timestamp2);
        //var_dump($day1,$day2);



        $start_date = strtotime($request->input('start_date')); // or your date as well
        $end_date   = strtotime($request->input('end_date'));
        $datediff   = $start_date - $end_date;

        //echo round($datediff / (60 * 60 * 24));
        $total_Day_Date = [];
        for ($i=$start_date; $i<=$end_date; $i+=86400) {  
            //echo date("m-d-Y", $i).'<br />'; 
            $day    = date('l', $i);
            $date   = date("m-d-Y", $i);
            $total_Day_Date[$day] =  $date; //date("m-d-Y", $i);
            //$totalDay[]  =  date('l', $i);  
        } 

        //dd($request->all());
        $topic_name = "ClassHistory";
        // all academics levels
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();

        $qry = [
            'institute_id'  =>$this->academicHelper->getInstitute(),
            'campus_id'     =>$this->academicHelper->getCampus()
        ];
        // all academics year
        $academicYears  = $this->academicsYear->where($qry)->get();
        $subjectList    = $this->subject->get();
        $topic_list     = $this->OnlineClassTopic->get();
        $empList        = EmployeeInformation::where('institute_id', institution_id())->where('campus_id',campus_id())->where('status',1)->get();




        $institute_id               = $this->academicHelper->getInstitute();
        $campus_id                  = $this->academicHelper->getCampus();
        $level                      = $request->input('level');
        $batch                      = $request->input('batch');
        $section                    = $request->input('section');
        $shift                      = $request->input('shift');
        $subject                    = $request->input('subject');
        $teacher_id                 = $request->input('teacher_id');
        $subject_class_topic        = $request->input('subject_class_topic');
        $start_date                 = $request->input('start_date');
        $end_date                   = $request->input('end_date');
        $status                     = $request->input('status');

        // all timetables
        $allTimetables = $this->timeTable->where([
            'batch'=>$batch,
            'section'=>$section,
            'shift'=>$shift,
            //'academic_year'=>$academicYear,
            'campus'=>$campus_id,
            'institute'=>$institute_id
        ])->get();

        // batch section assigned period id
        $batchSectionPeriodId = $this->getBatchSectionPeriodCategoryId($institute_id, $campus_id, null, $level, $batch, $section, $shift);
        // all class periods
        $allClassPeriods = $this->getAcademicClassPeriods($batchSectionPeriodId, $institute_id, $campus_id, null);
        // all class subject
        $allClassSubjects = $this->classSubject->where(['class_id'=>$batch,'section_id'=>$section])->get();

        $PeriodList     = [];
        $ClassDuration  = [];
        
        foreach($allClassPeriods as $key=>$period){

           
            $period->period_start_hour % 10   ==  $period->period_start_hour?'0':'';
            
            $period->period_start_min % 10    ==  $period->period_start_min?'0':'';
            
            $start_time_format = $period->period_start_meridiem == 0?'AM':'PM';

            $starting_time  = $period->period_start_hour.':'.$period->period_start_min.' '.$start_time_format;

            $period->period_end_hour % 10 ==  $period->period_end_hour?'0':'';
            $period->period_end_min % 10  ==  $period->period_end_min?'0':'';
            $end_time_format = $period->period_end_meridiem  ==  0 ? 'AM' : 'PM';

            $ending_time    = $period->period_end_hour.':'.$period->period_end_min.' '.$end_time_format;

            $PeriodList[$key+1]   = $starting_time.' - '.$ending_time;

            // $v =  strtotime($ending_time) - strtotime($starting_time);
            // echo date("h:i", $v);

            $to_time = strtotime($period->period_end_hour.':'.$period->period_end_min);
            $from_time = strtotime($period->period_start_hour.':'.$period->period_start_min);
            //echo "Time Check For ".round(abs($to_time - $from_time) / 60,2). " minute"."<br/>";

            $ClassDuration[$key+1] = round(abs($to_time - $from_time) / 60,2);
        }

        //$time1 = strtotime('08:5');
        //$time2 = strtotime('07:30');

        // $to_time = strtotime($period->period_end_hour.':'.$period->period_end_min);
        // $from_time = strtotime($period->period_start_hour.':'.$period->period_start_min);
        // echo "Time Check For ".round(abs($to_time - $from_time) / 60,2). " minute";

        // die();
        

        $ClassName = $this->batch->where(['academics_level_id'=>$level, 'campus'=> $campus_id, 'institute'=>$institute_id,'id'=>$batch])->first();

        $SectionName = $this->section->where(['batch_id' => $batch, 'institute'=>$institute_id])->first();


        $topic_info = OnlineClassTopic::where(['academic_level_id'=>$level, 'academic_class_id'=>$batch, 'academic_section_id'=>$section])->get();

        $topicList = [];
        if(isset($topic_info) && !empty($topic_info) && is_array($topic_info)){
            foreach($topic_info as $key=>$topic){
                $topicList[$key+1] = $topic->class_topic;
            }
        }
        else{
            foreach($topic_info as $key=>$topic){
                $topicList[$key+1] = "Topic Not Found";
            }
        }

        $studentList = $this->studentProfileView
            ->where('batch',$batch)
            ->where('section',$section)
            ->where('academic_level',$level)
            ->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')
            ->count();
        
        //Class subject Topic Start
        $level          =   $request->input('level');
        $batch          =   $request->input('batch');
        $section        =   $request->input('section');

        $ClassSubjectTopic = $this->OnlineClassTopic->where(['institute_id'=>$institute_id,'campus_id'=>$campus_id,'academic_level_id'=>$level,'academic_class_id'=>$batch, 'academic_section_id'=>$section])->orderBy('id', 'ASC')->get();

        $topicNameList = [];
        foreach($ClassSubjectTopic as $topicName){
            $topicNameList[$topicName->class_subject_id] = $topicName->class_topic;
            $topicNameList[$topicName->class_subject_id] = $topicName->id;
        }    
        //Class subject Topic End

        //Class History Status Start
        $ClassHistoryStatus = $this->OnlineClassHistory->where(['institute_id'=>$institute_id,'campus_id'=>$campus_id,'academic_level_id'=>$level,'academic_class_id'=>$batch, 'academic_section_id'=>$section])->orderBy('id', 'ASC')->get();

        $HistoryStatusList = [];
        foreach($ClassHistoryStatus as $ClassStatus){
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->conduct_time;
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->teacher_class_status;
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->student_present;
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->student_absent;
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->student_leave;
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->teacher_remarks;
            $HistoryStatusList[$ClassStatus->class_subject_id][] = $ClassStatus->status;  
        }
        //Class History Status End

        echo "<pre>";
        //print_r($PeriodList);
        //print_r($allClassPeriods);
        //print_r($topic_info);
        //print_r($topicList);
        //print_r($ClassSubjectTopic);
        print_r($topicNameList);
        //print_r($HistoryStatusList);
        echo "</pre>";

        //echo "Check-".$HistoryStatusList[195][0];
        //die();

        return view('onlineacademics::pages.ClassHistory',compact('topic_name','academicYears','subjectList','topic_list','empList','allAcademicsLevel','allClassPeriods', 'allClassSubjects', 'allTimetables', 'batchSectionPeriodId','total_Day_Date','PeriodList','ClassName','SectionName','topicList','studentList', 'ClassDuration','topicNameList','HistoryStatusList','shift'))->with('page');

    }

    public function StoreClassTopic(Request $request)
    {
        dd($request->all());
    }

    // get class section assigned period category id
    public function getBatchSectionPeriodCategoryId($institute, $campus, $academicYear, $level, $batch, $section, $shift)
    {
        $batchSectionPeriodProfile =  $this->classSectionPeriodCategory->where([
            // 'academic_year' => $academicYear,
            'institute_id' => $institute,
            'campus_id' => $campus,
            'academic_level' => $level,
            'batch' => $batch,
            'section' => $section,
            'cs_shift' => $shift,
        ])->first();
        // return variable
        return $batchSectionPeriodProfile?$batchSectionPeriodProfile->cs_period_category:0;
    }

    public function getAcademicClassPeriods($categoryId, $institute, $campus, $academicYear)
    {
        // periods
        return $this->classPeriod->where([
            'institute' => $institute,
            'campus' => $campus,
            //'academic_year' => $academicYear,
            'period_category' => $categoryId
        ])->get();
    }

    ///////// Student Module Ajax Request  function /////////
    public function findBatch(Request $request)
    {
        
        $academicLevelId    = $request->input('id');
        $campusId           = $request->input('campus', $this->academicHelper->getCampus());
        $instituteId        = $request->input('institute', $this->academicHelper->getInstitute());

        // response array
        $data = array();
        // all batch
        $allBatch = $this->batch->where(['academics_level_id'=>$academicLevelId, 'campus'=> $campusId, 'institute'=>$instituteId])->orderBy('batch_name','ASC')->get();
        // looping for adding division into the batch name
        foreach ($allBatch as $batch) {
            if ($batch->get_division()) {
                $data[] = array('id' => $batch->id, 'batch_name' => $batch->batch_name . " - " . $batch->get_division()->name);
            } else {
                $data[] = array('id' => $batch->id, 'batch_name' => $batch->batch_name);
            }
        }

        //then sent this data to ajax success
        return $data;
    }

    ///////// Student Module Ajax Request  function /////////
    public function findSection(Request $request)
    {
        // response array
        $data = array();
        // all level
        $sectionList = $this->section->where('batch_id', $request->id)->orderBy('section_name','ASC')->get();
        // looping for adding section
        foreach ($sectionList as $section) {
            $data[] = [
                'id' =>$section->id,
                //'academics_year_id' =>$section->academics_year_id,
                'batch_id' =>$section->batch_id,
                'section_name' =>$section->section_name,
                'intake' =>$section->intake,
                'status' =>$section->status,
                'campus' =>$section->campus,
                'institute' =>$section->institute,
            ];
        }
        //then sent this data to ajax success
        return $data;

    }

    //////////////////////  find class subjects for ajax request //////////////////////
    public function findsubjcet(Request $request)
    {
        // input details
        $class      = $request->input('class_id');
        $section    = $request->input('section_id');
        // response array
        $data = array();
        // all class subject
        $allClassSubject = $this->classSubject->where(['class_id'=>$class, 'section_id'=>$section])->orderBy('sorting_order', 'ASC')->get();
        // active user information
        $userInfo = Auth::user();
        // checking user role
        if($userInfo->hasRole('teacher')){
            // find user employee profile
            $teacherInfoProfile = $userInfo->employee();
            // find class teacher subject list
            $classTeacherSubjects = $this->subjectTeacher->where(['employee_id'=>$teacherInfoProfile->id, 'is_active'=>1])->get();
            // Teacher subject looping for finding subjects of this class-section
            foreach ($classTeacherSubjects as $teacherSubject){
                // find class subject profile anc checking
                if($classSubject = $teacherSubject->classSubject()){
                    // checking class subject details
                    if(($classSubject->class_id==$class) AND ($classSubject->section_id==$section)){
                        $data[] = $this->ClassSubjectReturnPack($classSubject);
                    }
                }
            }
        }else{
            // looping for adding division into the batch name
            foreach ($allClassSubject as $classSubject) {
                $data[] = $this->ClassSubjectReturnPack($classSubject);
            }
        }
        //then sent this data to ajax success
        return $data;
    }

    public function findTeacher(Request $request)
    {
        
        $class_id   = $request->input('class_id');
        $section_id = $request->input('section_id');
        $subject_id = $request->input('subject_id');

        // all class subject teachers
        $subjectTeachers = $this->subjectTeacher->where(['class_subject_id'=>$subject_id,'is_active'=>1])->first();

        $teacher_id = $subjectTeachers->employee_id;

        $data = [];
        $data = EmployeeInformation::where('institute_id', institution_id())->where('campus_id',campus_id())->where('id',$teacher_id)->where('status',1)->get();
        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        return $data;
    }

    public function ajax_teacher_topic(Request $request)
    {
        
        $class_id   = $request->input('class_id');
        $section_id = $request->input('section_id');
        $subject_id = $request->input('subject_id');

        // all class subject teachers
        $subjectTeachers = $this->subjectTeacher->where(['class_subject_id'=>$subject_id,'is_active'=>1])->first();

        $teacher_id = $subjectTeachers->employee_id;

        //$data = [];
        $teacher_data = EmployeeInformation::where('institute_id', institution_id())->where('campus_id',campus_id())->where('id',$teacher_id)->where('status',1)->get();

        //Subject Topic Start 02/07/2020
        $academic_subject_id = DB::table('class_subjects')
        ->where('class_id',$request->input('class_id'))
        ->where('section_id',$request->input('section_id'))
        ->where('id',$request->input('subject_id'))   
        ->value('subject_id');

        // all class subject
        $allClassSubjectTopic = $this->OnlineClassTopic->where(['academic_class_id'=>$class_id, 'academic_section_id'=>$section_id, 'class_subject_id'=>$academic_subject_id, 'class_teacher_id'=>$teacher_id])->orderBy('id', 'ASC')->get();

        $topic_data = [];
        // active user information
        $userInfo = Auth::user();
        // checking user role
        if($userInfo->hasRole('teacher')){
            // find user employee profile
            $teacherInfoProfile = $userInfo->employeeInformation();
            // find class teacher subject list
            $classTeacherSubjects = $this->subjectTeacher->where(['employee_id'=>$teacherInfoProfile->id, 'is_active'=>1])->get();
            // Teacher subject looping for finding subjects of this class-section
            foreach ($classTeacherSubjects as $teacherSubject){
                // find class subject profile anc checking
                if($classSubject = $teacherSubject->classSubject()){
                    // checking class subject details
                    if(($classSubject->class_id==$class) AND ($classSubject->section_id==$section)){
                        $topic_data[] = $this->ClassSubjectTopicReturnPack($classSubject);
                    }
                }
            }
        }else{
            // looping for adding division into the batch name
            foreach ($allClassSubjectTopic as $classSubject) {
                $topic_data[] = $this->ClassSubjectTopicReturnPack($classSubject);
            }
        }
        //then sent this data to ajax success
        //Subject Topic End 02/07/2020

        // echo "<pre>";
        // print_r($data);
        // echo "</pre>";
        $data =[
            'teacher_data'=>$teacher_data,
            'topic_data'=>$topic_data
        ];
        
        return $data;
    }

    public function ClassSchedule(Request $request)
    {
        //dd($request->all());
        $institute_id               = $request->input('institute_id');
        $campus_id                  = $request->input('campus_id');
        $academic_level_id          = $request->input('academic_level_id');
        $academic_class_id          = $request->input('academic_class_id');
        $academic_class             = $request->input('academic_class');
        $academic_section_id        = $request->input('academic_section_id');
        $academic_section           = $request->input('academic_section');
        $academic_shift_id          = $request->input('academic_shift_id');

            $studentList1 = $this->studentProfileView
            ->where('batch',$academic_class_id)
            ->where('section',$academic_section_id)
            ->where('academic_level',$academic_level_id )
            ->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')
            ->get();

        $academic_shift_name        = "";
        if($academic_shift_id == 1){
            $academic_shift_name = "morning";
        }
        else{
            $academic_shift_name = "day";
        }
        $academic_shift             = $academic_shift_name;
        $class_subject_id           = $request->input('class_subject_id');
        $class_subject              = $request->input('class_subject');
        $class_opening_date         = $request->input('class_opening_date');
        $class_opening_day          = $request->input('class_opening_day');
        $class_routine_time         = $request->input('class_routine_time');
        $class_teacher_id           = $request->input('class_teacher_id');
        $class_teacher_name         = $request->input('class_teacher_name');

        $class_topic_name           = $request->input('class_topic_name');
        $class_total_student        = $request->input('class_total_student');
        $class_status               = $request->input('class_status');

        $routineTime                = explode('-',$class_routine_time);
        $class_start_time           = $routineTime[0];
        $class_end_time             = $routineTime[1];


        $ClassScheduledId           = $institute_id.$campus_id.$academic_level_id.$academic_class_id.$academic_section_id.$academic_shift_id.$class_subject_id.$class_teacher_id.date("h:i:s a m/d/Y");


        $academic_level_name = DB::table('academics_level')
        ->where('institute_id',$institute_id)
        ->where('campus_id',$campus_id)
        ->where('id',$academic_level_id)   
        ->value('level_name');

        $ClassSubjectTopic = $this->OnlineClassTopic
        ->where('institute_id',$institute_id)
        ->where('campus_id',$campus_id)
        ->where('academic_level_id',$academic_level_id)
        ->where('academic_class_id',$academic_class_id)
        ->where('academic_section_id',$academic_section_id)
        ->where('class_topic','LIKE','%'.$class_topic_name.'%')
        ->first();

        $class_topic_id = $ClassSubjectTopic->id;

        

        $CheckScheduled = DB::table('online_class_schedule')
        ->where('institute_id',$institute_id)
        ->where('campus_id',$campus_id)
        ->where('academic_level_id',$academic_level_id)
        ->where('academic_class_id',$academic_class_id)
        ->where('academic_section_id',$academic_section_id)
        ->where('academic_shift_id',$academic_shift_id)
        ->where('class_opening_date',$class_opening_date)
        ->where('class_teacher_id',$class_teacher_id)
        ->where('class_subject_id',$class_subject_id)
        ->where('class_topic_name', 'like','%'.$class_topic_name.'%')
        ->first();

        $data=[];
        
        if(isset($CheckScheduled)){
            
            Session::flash('message', 'Class Schedule All Ready Exit');
            //return view('onlineacademics::pages.includes.class-schedule-list');
            $data=[
                'status'=>'Error'
            ];
        }
        else{

            $NewSchedule = new $this->OnlineClassSchedule();

            $NewSchedule->institute_id              = $institute_id;
            $NewSchedule->campus_id                 = $campus_id;
            $NewSchedule->academic_level_id         = $academic_level_id;
            $NewSchedule->academic_level            = $academic_level_name ? $academic_level_name : 'null';
            $NewSchedule->academic_class_id         = $academic_class_id;
            $NewSchedule->academic_class            = $academic_class ? $academic_class : 'null';
            $NewSchedule->academic_section_id       = $academic_section_id;
            $NewSchedule->academic_section          = $academic_section ? $academic_section : 'null';
            $NewSchedule->academic_shift_id         = $academic_shift_id;
            $NewSchedule->academic_shift            = $academic_shift ? $academic_shift : 'null';
            $NewSchedule->class_subject_id          = $class_subject_id ? $class_subject_id : 'null';
            $NewSchedule->class_subject             = $class_subject ? $class_subject : 'null';
            $NewSchedule->class_opening_date        = $class_opening_date ? $class_opening_date : 'null';
            $NewSchedule->class_opening_day         = $class_opening_day ? $class_opening_day : 'null';
            $NewSchedule->class_routine_time        = $class_routine_time ? $class_routine_time : 'null';

           $NewSchedule->creater_id                   = Auth::id() ? Auth::id() : 'null';
              $NewSchedule->class_start_time          = $class_start_time ? $class_start_time : 'null';
            $NewSchedule->class_end_time            = $class_end_time ? $class_end_time : 'null';

            $NewSchedule->class_teacher_id          = $class_teacher_id ? $class_teacher_id : 'null';
            $NewSchedule->class_teacher_name        = $class_teacher_name ? $class_teacher_name : 'null';
            $NewSchedule->class_topic_id            = $class_topic_id ? $class_topic_id : 'null';
            $NewSchedule->class_topic_name          = $class_topic_name ? $class_topic_name : 'null';
            $NewSchedule->class_total_student       = $class_total_student ? $class_total_student : 'null';
            $NewSchedule->class_status              = $class_status ? $class_status : 'null';
            $NewSchedule->class_scheduled_id        = $ClassScheduledId ? $ClassScheduledId : 'null';
            $NewSchedule->save();

            foreach($studentList1 as $student) { 
                $studentbatch_wise = array(
                    'std_id' => $student->std_id,
                    'online_class_id'=>$NewSchedule->id,
                    'attendance_status' =>0,
                    'created_at'=>  date("Y-m-d H:i:s")
                );

                 DB::table('online_attandences')->insert($studentbatch_wise);
            }
               
            Session::flash('message', 'New Class Schedule Created Successfully');
            //return view('onlineacademics::pages.includes.class-schedule-list');
            $data=[
                'subject_id'=>$class_subject_id,
                'status'=>'Success'
            ];
        }

        // $data=[
        //     'status'=>'Success'
        // ];

         return $data;

    }

    public function OnlineScheduleClass(Request $request)
    {

        $class_scheduled_id    = $request->input('class_scheduled_id');
        //return redirect()->route('onlineacademics.onlineacademic.onlineclass');
        //return redirect('/onlineacademics/onlineacademic/onlineclass');

        $data  = [
            'tabActive'=>'onlineclass',
            'class_scheduled_id'=>$class_scheduled_id
        ];

        return $data;
    }

    public function LiveClassScheduled($scheduledId){
        


        $scheduledId = (isset($scheduledId) ? $scheduledId : "");

        $ScheduledData = $this->OnlineClassSchedule->where(['class_scheduled_id' => $scheduledId])->first();

        // all academics levels
        $allAcademicsLevel = $this->academicHelper->getAllAcademicLevel();

        $topic_name = "onlineclass";


        $qry = [
            'institute_id'=>$this->academicHelper->getInstitute(),
            'campus_id'=>$this->academicHelper->getCampus()
        ];
        // all academics year
        $academicYears  = $this->academicsYear->where($qry)->get();
        $subjectList    = $this->subject->get();

        $topic_list     = $this->OnlineClassTopic->where(['institute_id'=>$ScheduledData->institute_id,'campus_id'=>$ScheduledData->campus_id,'academic_level_id'=>$ScheduledData->academic_level_id,'academic_class_id'=>$ScheduledData->academic_class_id,'academic_section_id'=>$ScheduledData->academic_section_id,'class_subject_id'=>$ScheduledData->class_subject_id])->get();


        $studentList = $this->studentProfileView

            ->where('batch',$ScheduledData->academic_class_id)
            ->where('section',$ScheduledData->academic_section_id)
            ->where('academic_level',$ScheduledData->academic_level_id )
            ->orderByRaw('LENGTH(gr_no) asc')->orderBy('gr_no', 'asc')
            ->get();

        $empList = EmployeeInformation::where('institute_id', institution_id())->where('campus_id',campus_id())->where('status',1)->get();

        return view('onlineacademics::pages.onlineclass',compact('topic_name','academicYears','subjectList','topic_list','empList','allAcademicsLevel','ScheduledData','scheduledId','studentList'))->with('page');
    }

     public function onlineclass_condduct(Request $request)
    {
       $scheduledId = $request->scheduleid;
       $teacher_id = $request->teacher_id;

       if($request->class_status == 2){

            $ScheduledData = $this->OnlineClassSchedule->where(['class_scheduled_id' => $scheduledId])->where(['class_teacher_id' => $teacher_id]) ->update([
                   'class_status' => 6,
                   'class_conduct_time'=>date("Y-m-d h:i:sa"),
            ]);

           if($ScheduledData){

         // echo "Hello";
              $ScheduledData = $this->OnlineClassSchedule->where(['class_scheduled_id' => $scheduledId])->first();
             return view('onlineacademics::pages.updateclassstatus',compact('ScheduledData'));
            }
          else{ 
               echo "Do Not Update ";
              }
      
      }
    }

}
