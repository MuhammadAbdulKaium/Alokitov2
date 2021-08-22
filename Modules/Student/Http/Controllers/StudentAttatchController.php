<?php

namespace Modules\Student\Http\Controllers;

use App\Content;
use File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Student\Entities\StudentAttachment;
use Modules\Student\Entities\StudentInformation;
use Redirect;
use Session;
use Validator;

class StudentAttatchController extends Controller
{

    // student profile document main page
    public function getStudentDocuments($id)
    {
        $personalInfo = StudentInformation::findOrFail($id);
        return view('student::pages.student-profile.student-documents', compact('personalInfo'))->with('page', 'documents');
    }

    // student profile document create
    public function createStudentDocument($stdId)
    {
        return view('student::pages.student-profile.modals.document-add')->with('std_id', $stdId);
    }

    // student profile document store
    public function storeStudentDocument(Request $request)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'image'           => 'required',
            'std_id'          => 'required',
            'doc_type'        => 'required',
            'doc_details'     => 'required',
            'doc_submited_at' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            // photo storing
            $photoFile       = $request->file('image');
            $fileExtension   = $photoFile->getClientOriginalExtension();
            $contentName     = $photoFile->getClientOriginalName();
            $contentFileName = $photoFile->getClientOriginalName();
            $destinationPath = 'assets/users/images/';

            // Start transaction!
            DB::beginTransaction();

            try {

                $uploaded = $photoFile->move($destinationPath, $contentFileName);
                // storing file name to the database
                if ($uploaded) {
                    // user documet
                    $userDocument = new Content();
                    // storing user documetn
                    $userDocument->name      = $contentName;
                    $userDocument->file_name = $contentFileName;
                    $userDocument->path      = $destinationPath;
                    $userDocument->mime      = $fileExtension;
                    $userDocument->save();
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

            try {

                // Validate, then create if valid
                // upload flile to the student attachment database
                $studentAttachment = new StudentAttachment();
                // storing student attachment
                $studentAttachment->std_id          = $request->input('std_id');
                $studentAttachment->doc_id          = $userDocument->id;
                $studentAttachment->doc_type        = $request->input('doc_type');
                $studentAttachment->doc_details     = $request->input('doc_details');
                $studentAttachment->doc_submited_at = date('Y-m-d', strtotime($request->input('doc_submited_at')));
                // save student attachment profile
                $attachmentUploaded = $studentAttachment->save();

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

            // If we reach here, then
            // data is valid and working.
            // Commit the queries!
            DB::commit();

            // session mes
            if ($attachmentUploaded) {
                Session::flash('success', 'Document added successfully');
                // receiving page action
                return redirect()->back();
            } else {
                Session::flash('warning', 'unable to perform the action. please try with correct Information');
                // receiving page action
                return redirect()->back();
            }

        } else {
            Session::flash('warning', 'invalid Information. please try with correct Information');
            // receiving page action
            return redirect()->back()->withErrors($validator)->withInput();
        }
    }

    // student profile document edit
    public function editStudentDocument($id)
    {
        // document profile
        $attachment = StudentAttachment::findOrFail($id);
        // return with attachment profile
        return view('student::pages.student-profile.modals.document-update', compact('attachment'));
    }

    // student profile document edit
    public function updateStudentDocument(Request $request, $id)
    {
        // validating all requested input data
        $validator = Validator::make($request->all(), [
            'doc_type'        => 'required',
            'doc_details'     => 'required',
            'doc_submited_at' => 'required',
        ]);

        // storing requesting input data
        if ($validator->passes()) {

            // Attatchment profile
            $attachment = StudentAttachment::findOrFail($id);
            // update attatchment
            $attachment->doc_type        = $request->input('doc_type');
            $attachment->doc_details     = $request->input('doc_details');
            $attachment->doc_submited_at = date('Y-m-d', strtotime($request->input('doc_submited_at')));
            // save update
            $attachmentUpdated = $attachment->save();

            // checking
            if ($attachmentUpdated) {
                // photo storing
                if ($photoFile = $request->file('image')) {

                    $fileExtension   = $photoFile->getClientOriginalExtension();
                    $contentName     = $photoFile->getClientOriginalName();
                    $contentFileName = $photoFile->getClientOriginalName();
                    $destinationPath = 'assets/users/images/';
                    $uploaded        = $photoFile->move($destinationPath, $contentFileName);

                    // checking
                    if ($uploaded) {
                        $userDocument = $attachment->singleContent();
                        // storing user documetn
                        $userDocument->name      = $contentName;
                        $userDocument->file_name = $contentFileName;
                        $userDocument->path      = $destinationPath;
                        $userDocument->mime      = $fileExtension;
                        // save content
                        $contentupdated = $userDocument->save();

                        // checking
                        if ($contentupdated) {
                            // success msg
                            Session::flash('success', 'Document Updated');
                            // return back
                            return redirect()->back();
                        }
                    } else {
                        // warning msg
                        Session::flash('warning', 'Unable to upload document');
                        // return back
                        return redirect()->back();
                    }
                } else {
                    // success msg
                    Session::flash('success', 'Document Updated');
                    // return back
                    return redirect()->back();
                }
            } else {
                // warning msg
                Session::flash('warning', 'Unable to update attachment');
                // return back
                return redirect()->back();
            }

        } else {
            // warning msg
            Session::flash('warning', 'Invalid Information');
            // return back
            return redirect()->back();
        }
    }

    // student document status change
    public function changStudentDocumentStatus($id, $status)
    {

        // document profile
        $studentAttachment = StudentAttachment::findOrFail($id);
        // update attatchment
        $studentAttachment->doc_status = $status;
        // save update
        $updated = $studentAttachment->save();

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

    // delete document
    public function deleteStudentDocument($id)
    {
        // document profile
        $studentAttachment = StudentAttachment::findOrFail($id);
        // delete student attachment
        $attachmentDeleted = $studentAttachment->delete();

        // checking
        if ($attachmentDeleted) {
            // content profile
            $studentContent = $studentAttachment->singleContent();
            // delete student attatchment content
            $studentContentDeleted = $studentContent->delete();

            // checking
            if ($studentContentDeleted) {
                // success msg
                Session::flash('success', 'Document Deleted');
                // return back
                return redirect()->back();
            } else {
                // warning msg
                Session::flash('warning', 'Uanable to delete attatchment content');
                // return back
                return redirect()->back();
            }
        } else {
            // warning msg
            Session::flash('warning', 'Uanable to delete attatchment');
            // return back
            return redirect()->back();
        }
    }

}
