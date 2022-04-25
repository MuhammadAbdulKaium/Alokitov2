<?php

namespace Modules\Academics\Http\Controllers;

use Modules\Academics\Entities\Subject;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Academics\Entities\SubjectGroup;
use Modules\Academics\Entities\SubjectGroupAssign;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Academics\Entities\Division;

class SubjectController extends Controller
{

    private $subject;
    private $subjectGroup;
    private $subjectGroupAssign;
    private $academicHelper;

    // constructor
    public function __construct(Subject $subject, SubjectGroup $subjectGroup, SubjectGroupAssign $subjectGroupAssign, AcademicHelper $academicHelper)
    {
        $this->subject = $subject;
        $this->subjectGroup = $subjectGroup;
        $this->subjectGroupAssign = $subjectGroupAssign;
        $this->academicHelper = $academicHelper;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $pageTitle = "Subject Information";
        $insertOrEdit = 'insert'; //To identify insert

        $divisionList = Division::where([
            // 'academic_year'=>$this->academicHelper->getAcademicYear(),
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute()
        ])->get();

        return view('academics::subject.index', ['data' => $this->getAll(), 'divisionList' => $divisionList, 'pageTitle' => $pageTitle, 'insertOrEdit' => $insertOrEdit]);

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('academics::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        // {"subject_name":"asdf","":"asdfasd","":"asdfa"}

        $subjectProfile = new $this->subject();
        // input details
        $subjectProfile->division_id = $request->input('division');
        $subjectProfile->subject_name = $request->input('subject_name');
        $subjectProfile->subject_code = $request->input('subject_code');
        $subjectProfile->subject_alias = $request->input('subject_alias');
        $subjectProfile->campus = $this->academicHelper->getCampus();
        $subjectProfile->institute = $this->academicHelper->getInstitute();
        //$subjectProfile->academic_year = $this->academicHelper->getAcademicYear();
        // save subject profile
        $subjectProfileSaved = $subjectProfile->save();
        // checking
        if($subjectProfileSaved) {
            Session::flash('message', 'Success!Data has been saved successfully.');
            return redirect()->back();
        } else {
            Session::flash('message', 'Failed!Data has not been saved successfully.');
            return redirect()->back();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        // return view('academics::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        //   return view('academics::edit');

        $data = new Subject();
        $editdata = $data->where('id', $id)->get();

        $insertOrEdit = 'edit';

        return view('academics::subject.index', ['data' => $this->getAll(), 'editdata' => $editdata, 'insertOrEdit' => $insertOrEdit]);
    }

    public function edit_perform()
    {

    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $subject = Subject::find($id);
        $updated = $subject->update($request->all());
        if($updated) {
            Session::flash('message', 'Success!Data has been updated successfully.');
            return redirect()->back();
        } else {
            Session::flash('message', 'Failed!Data has not been updated successfully.');
            return redirect()->back();
        }
    }



    public function getAll()
    {
        return $subjectList = $this->subject->where([
            'campus'=>$this->academicHelper->getCampus(),
            'institute'=>$this->academicHelper->getInstitute(),
            // 'academic_year' => $this->academicHelper->getAcademicYear(),
        ])->with('division')->get();
    }

    public  function delete($id)
    {
        $subjectProfile = $this->subject->find($id)->delete();
        if($subjectProfile){
            Session::flash('message', 'Success!Data has been deleted successfully.');
            return redirect()->back();
        } else{
            Session::flash('message', 'Failed!Data has not been deleted successfully.');
            return redirect()->back();
        }
    }
    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {

    }



    ////////////////////// subject Group starts here ///////////////////////

    public function getSubjectGroup()
    {
        $subjectGroupList = $this->subjectGroup->where([
            'campus'=>$this->academicHelper->getCampus(), 'institute'=>$this->academicHelper->getInstitute(),
        ])->orderBy('type', 'ASC')->get();
        // return view with variables
        return view('academics::subject.subject-group',compact('subjectGroupList'));
    }

    public function createSubjectGroup()
    {
        $subjectGroupProfile = null;
        return view('academics::subject.modals.subject-group', compact('subjectGroupProfile'));
    }

    public function editSubjectGroup($subGroupId)
    {
        $subjectGroupProfile = $this->subjectGroup->find($subGroupId);
        return view('academics::subject.modals.subject-group', compact('subjectGroupProfile'));
    }

    public function deleteSubjectGroup($subGroupId)
    {
        $subjectGroupProfile = $this->subjectGroup->find($subGroupId)->delete();
        if($subjectGroupProfile){
            Session::flash('success', 'Subject Group Deleted Successfully.');
            return redirect()->back();
        }else{
            Session::flash('warning', 'Unable to perform the action');
            return redirect()->back();
        }
    }

    public function storeSubjectGroup(Request $request)
    {
        // subject group id
        $subjectGroupId = $request->input('sub_group_id');
        // campus and institute information
        $campus = $this->academicHelper->getCampus();
        $institute = $this->academicHelper->getInstitute();
        // checking subjectGroupId
        if($subjectGroupId>0){
            // find subject group profile
            $subjectGroupProfile = $this->subjectGroup->find($subjectGroupId);
            // checking subject group profile
            if(!$subjectGroupProfile){
                Session::flash('warning', 'No Subject Group Found.');
                return redirect()->back();
            }
        }else{
            $subjectGroupProfile = new $this->subjectGroup();
        }
        // input details
        $subjectGroupProfile->name = $request->input('sub_group_name');
        $subjectGroupProfile->type = $request->input('sub_group_type');
        //$subjectGroupProfile->list = $request->input('sub_group_list');
        $subjectGroupProfile->campus = $campus;
        $subjectGroupProfile->institute = $institute;
        // save and checking
        if($subjectGroupProfile->save()){
            Session::flash('success', 'Subject Group Submitted Successfully.');
            return redirect()->back();
        }else{
            Session::flash('warning', 'Unable to perform the action');
            return redirect()->back();
        }
    }


    public function findCampusSubject(Request $request)
    {
        //get search term
        $searchTerm = $request->input('term');
        // find my subjects
        $mySubjects = $this->subject->where('subject_name', 'like', "%" . $searchTerm . "%")
            ->orwhere('subject_code', 'like', "%" . $searchTerm . "%")
            ->orwhere('subject_alias', 'like', "%" . $searchTerm . "%")
            ->get();

        // filter with institute and campus
        $mySubjects = $this->campusSorting($this->academicHelper->getCampus(), $this->instituteSorting($this->academicHelper->getInstitute(), $mySubjects));

        // checking
        if ($mySubjects->count()>0) {
            $data = array();
            foreach ($mySubjects as $subject) {
                $data[] = array('id' => $subject->id, 'name' => $subject->subject_name . " (" . $subject->subject_code.")");
            }
            // return
            return json_encode($data);
        }
    }

    public function assignSubject($subGroupId)
    {
        // find subject group
        $subGroupProfile = $this->subjectGroup->find($subGroupId);
        // return subject group profile
        return view('academics::subject.modals.group-subject-assign', compact('subGroupProfile'));
    }

    public function storeAssignedSubject(Request $request)
    {
        // request details
        $subId = $request->input('sub_id');
        $subGroupId = $request->input('sub_group_id');
        // assign subject group
        // if($subGroupProfile = $this->subjectGroupAssign->where(['sub_id'=>$subId])->withTrashed()->first()){
        //     $subGroupProfile->restore();
        // }else{
            $subGroupProfile = new $this->subjectGroupAssign();
        // }
        // input details
        $subGroupProfile->sub_id = $subId;
        $subGroupProfile->sub_group_id = $subGroupId;
        // checking
        if($subGroupProfile->save()){
            return ['status'=>'success', 'assign_id'=>$subGroupProfile->id, 'msg'=>'Subject Assigned Successfully.'];
        }else{
            return ['status'=>'failed', 'msg'=>'Unable to perform the action.'];
        }
    }

    public function deleteAssignedSubject($subGroupAssignId)
    {
        // find subject group
        $subGroupAssignProfileDeleted = $this->subjectGroupAssign->find($subGroupAssignId)->delete();
        // checking
        if($subGroupAssignProfileDeleted){
            Session::flash('success', 'Subject Removed Successfully.');
            return redirect()->back();
        }else{
            Session::flash('warning', 'Unable to perform the action');
            return redirect()->back();
        }
    }


    // institute sorter
    public function instituteSorting($instituteId, $collections)
    {
        return $collections->filter(function($singleProfile) use ($instituteId)
        {
            return $singleProfile->institute == $instituteId;
        });
    }

    // campus sorter
    public function campusSorting($campusId, $collections)
    {
        return $collections->filter(function($singleProfile) use ($campusId)
        {
            return $singleProfile->campus == $campusId;
        });
    }


}
