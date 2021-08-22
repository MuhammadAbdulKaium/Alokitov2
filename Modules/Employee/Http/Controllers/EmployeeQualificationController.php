<?php

namespace Modules\Employee\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Validator;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Session;
use Modules\Employee\Entities\EmployeeDocument;
use Modules\Employee\Entities\EmployeeInformation;

class EmployeeQualificationController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index($id)
    {
        $employeeInfo = EmployeeInformation::findOrFail($id);
        $qualifications = EmployeeDocument::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'employee_id' => $employeeInfo->id,
            'document_type' => 1,
        ])->get();

        return view('employee::pages.profile.qualification', compact('employeeInfo', 'qualifications'))->with('page', 'qualification');
    }



    public function create($id)
    {
        $employeeInfo = EmployeeInformation::findOrFail($id);
        return view('employee::pages.modals.qualification-create', compact('employeeInfo'));
    }



    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'qualification_attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
        ]);

        if ($validator->passes()){
            $attachmentName = null;
            if($request->hasFile('qualification_attachment')){
                $attachmentName = 'qualification-' . time() . '.' . $request->qualification_attachment->extension();
                $request->qualification_attachment->move(public_path('employee-attachment'), $attachmentName);
            }

            EmployeeDocument::create($request->except('document_type', 'qualification_attachment', 'created_by', 'campus_id', 'institute_id') + [
                'document_type' => 1,
                'qualification_attachment' => $attachmentName,
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            Session::flash('message', 'Employee qualification added successfully.');
            return redirect()->back();
        }else{
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
        $qualification = EmployeeDocument::findOrFail($id);
        return view('employee::pages.modals.qualification-update', compact('qualification'));
    }



    public function update(Request $request, $id)
    {
        $qualification = EmployeeDocument::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'qualification_attachment' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
        ]);

        if ($validator->passes()){
            $attachmentName = $qualification->qualification_attachment;
            if($request->hasFile('qualification_attachment')){
                if ($attachmentName){
                    $file_path = public_path().'/employee-attachment/'.$attachmentName;
                    unlink($file_path);
                }
                $attachmentName = 'qualification-' . time() . '.' . $request->qualification_attachment->extension();
                $request->qualification_attachment->move(public_path('employee-attachment'), $attachmentName);
            }

            $qualification->update($request->except('qualification_attachment', 'updated_by') + [
                'qualification_attachment' => $attachmentName,
                'updated_by' => Auth::id(),
            ]);

            Session::flash('message', 'Employee qualification updated successfully.');
            return redirect()->back();
        }else{
            Session::flash('errorMessage', 'Fill the fields with valid data.');
            return redirect()->back();
        }
    }



    public function destroy($id)
    {
        $qualification = EmployeeDocument::findOrFail($id);
        $attachmentName = $qualification->qualification_attachment;
        if ($attachmentName){
            $file_path = public_path().'/employee-attachment/'.$attachmentName;
            unlink($file_path);
        }
        $qualification->delete();

        Session::flash('message', 'Employee qualification deleted successfully.');
        return redirect()->back();
    }
}
