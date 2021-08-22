<?php

namespace Modules\Employee\Http\Controllers;

use App\Content;
use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Employee\Entities\EmployeeAttachment;
use Modules\Employee\Entities\EmployeeDocument;
use Modules\Employee\Entities\EmployeeInformation;
use Redirect;
use Session;
use Validator;

class EmployeeAttachmentController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    // Display a listing of the resource.
    public function index()
    {
        return view('employee::index');
    }

    // Show the form for creating a new resource.
    public function create($id)
    {
        // return view
        return view('employee::pages.modals.document-create')->with('emp_id', $id);
    }

    // Store a newly created resource in storage.
    public function store(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'employee_id'          => 'required',
            'document_category'        => 'required',
            'document_details'     => 'required',
            'document_submitted_at' => 'required',
            'document_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
        ]);

        // storing requesting input data
        if ($validator->passes()) {
            $attachmentName = null;
            if($request->hasFile('document_file')){
                $attachmentName = 'document-' . time() . '.' . $request->document_file->extension();
                $request->document_file->move(public_path('employee-attachment'), $attachmentName);
            }

            EmployeeDocument::create($request->except('document_type', 'document_submitted_at', 'document_file', 'created_by', 'campus_id', 'institute_id') + [
                'document_type' => 3,
                'document_file' => $attachmentName,
                'document_submitted_at' => Carbon::parse($request->document_submitted_at),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            Session::flash('message', 'Document uploaded successfully.');
            return redirect()->back();
        } else {
            Session::flash('warning', 'invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // Show the specified resource.
    public function show($id)
    {
        // find employee profile
        $employeeInfo = EmployeeInformation::findOrFail($id);
        $documents = EmployeeDocument::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'employee_id' => $employeeInfo->id,
            'document_type' => 3,
        ])->get();
        // return view
        return view('employee::pages.profile.document', compact('employeeInfo', 'documents'))->with('page', 'document');
    }

    // Show the form for editing the specified resource.
    public function edit($id)
    {
        // find doc profile
        $attachment = EmployeeDocument::findOrFail($id);
        // return view
        return view('employee::pages.modals.document-update', compact('attachment'));
    }

    // Update the specified resource in storage.
    public function update(Request $request, $id)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'document_category'        => 'required',
            'document_details'     => 'required',
            'document_submitted_at' => 'required',
            'document_file' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:200',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            // Attatchment profile
            $attachment = EmployeeDocument::findOrFail($id);
            // update attatchment
            $attachmentName = $attachment->document_file;
            if($request->hasFile('document_file')){
                if ($attachmentName){
                    $file_path = public_path().'/employee-attachment/'.$attachmentName;
                    unlink($file_path);
                }
                $attachmentName = 'document-' . time() . '.' . $request->document_file->extension();
                $request->document_file->move(public_path('employee-attachment'), $attachmentName);
            }

            $attachment->update($request->except('document_file', 'updated_by') + [
                'document_file' => $attachmentName,
                'updated_by' => Auth::id(),
            ]);

            Session::flash('message', 'Employee document updated successfully.');
            return redirect()->back();
        } else {
            // warning msg
            Session::flash('warning', 'Invalid Information');
            // return back
            return redirect()->back();
        }
    }

    // Update the status of specified resource in storage.
    public function status($id, $status)
    {
        // document profile
        $employeeAttachment = EmployeeAttachment::findOrFail($id);
        // update attatchment
        $employeeAttachment->doc_status = $status;
        // save update
        $updated = $employeeAttachment->save();

        // checking
        if ($updated) {
            // success msg
            Session::flash('success', 'Document Status Changed');
            // return back
            return redirect()->back();
        } else {
            // success msg
            Session::flash('success', 'Uanable to Changed Document Status');
            // return back
            return redirect()->back();
        }
    }

    // Remove the specified resource from storage.
    public function destroy($id)
    {
        $employeeDocument = EmployeeDocument::findOrFail($id);

        $attachmentName = $employeeDocument->document_file;
        if ($attachmentName){
            $file_path = public_path().'/employee-attachment/'.$attachmentName;
            unlink($file_path);
        }

        $employeeDocument->delete();

        Session::flash('message', 'Employee document deleted successfully.');
        return redirect()->back();
    }
}
