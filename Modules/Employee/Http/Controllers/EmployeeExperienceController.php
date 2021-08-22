<?php

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Entities\EmployeeDocument;
use Modules\Employee\Entities\EmployeeInformation;
use Validator;
use Session;


class EmployeeExperienceController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index($id)
    {
        $employeeInfo = EmployeeInformation::findOrFail($id);
        $experiences = EmployeeDocument::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'employee_id' => $employeeInfo->id,
            'document_type' => 2,
        ])->get();

        return view('employee::pages.profile.experience', compact('employeeInfo', 'experiences'))->with('page', 'experience');
    }


    public function create($id)
    {
        $employeeInfo = EmployeeInformation::findOrFail($id);

        return view('employee::pages.modals.experience-create', compact('employeeInfo'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'experience_attachment' => 'image|mimes:jpeg,png,jpg,gif,svg|max:200',
        ]);

        if ($validator->passes()){
            $attachmentName = null;
            if($request->hasFile('experience_attachment')){
                $attachmentName = 'experience-' . time() . '.' . $request->experience_attachment->extension();
                $request->experience_attachment->move(public_path('employee-attachment'), $attachmentName);
            }

            EmployeeDocument::create($request->except('document_type', 'experience_from_date', 'experience_to_date', 'experience_attachment', 'created_by', 'campus_id', 'institute_id') + [
                    'document_type' => 2,
                    'experience_from_date' => Carbon::parse($request->experience_from_date)->format('Y-m-d'),
                    'experience_to_date' => Carbon::parse($request->experience_to_date)->format('Y-m-d'),
                    'experience_attachment' => $attachmentName,
                    'created_by' => Auth::id(),
                    'campus_id' => $this->academicHelper->getCampus(),
                    'institute_id' => $this->academicHelper->getInstitute(),
                ]);

            Session::flash('message', 'Employee qualification added successfully.');
            return redirect()->back();
        } else {
            Session::flash('errorMessage', 'Fill the fields with valid data.');
            return redirect()->back();
        }
    }


    public function show($id)
    {
        return view('employee::show');
    }


    public function edit($id)
    {
        $experience = EmployeeDocument::findOrFail($id);
        return view('employee::pages.modals.experience-update', compact('experience'));
    }


    public function update(Request $request, $id)
    {
        $experience = EmployeeDocument::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'experience_attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
        ]);

        if ($validator->passes()){
            $attachmentName = $experience->experience_attachment;
            if($request->hasFile('experience_attachment')){
                if ($attachmentName){
                    $file_path = public_path().'/employee-attachment/'.$attachmentName;
                    unlink($file_path);
                }
                $attachmentName = 'experience-' . time() . '.' . $request->experience_attachment->extension();
                $request->experience_attachment->move(public_path('employee-attachment'), $attachmentName);
            }

            $experience->update($request->except('qualification_attachment', 'updated_by') + [
                    'experience_attachment' => $attachmentName,
                    'updated_by' => Auth::id(),
                ]);

            Session::flash('message', 'Employee experience updated successfully.');
            return redirect()->back();
        }else{
            Session::flash('errorMessage', 'Fill the fields with valid data.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $experience = EmployeeDocument::findOrFail($id);
        $attachmentName = $experience->experience_attachment;
        if ($attachmentName){
            $file_path = public_path().'/employee-attachment/'.$attachmentName;
            unlink($file_path);
        }
        $experience->delete();

        Session::flash('message', 'Employee experience deleted successfully.');
        return redirect()->back();
    }
}
