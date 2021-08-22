<?php

namespace Modules\Admission\Http\Controllers;

use File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Admission\Entities\ApplicantDocument;
use Modules\Admission\Entities\ApplicantUser;
use Redirect;
use Session;

class ApplicantDocumentController extends Controller
{

    private $applicant;
    private $information;

    // constructor
    public function __construct(ApplicantUser $applicant, ApplicantDocument $document)
    {
        $this->applicant = $applicant;
        $this->document  = $document;
    }

    // create
    public function addDocument($id)
    {
        // document profile
        $documentProfile = null;
        // applicant profile
        $applicantProfile = $this->applicant->find($id);
        // return view with variable
        return view('admission::application.modals.document', compact('applicantProfile', 'documentProfile'));
    }

    // edit document
    public function editDocument($docId)
    {
        // document profile
        $documentProfile = $this->document->find($docId);
        // applicant profile
        $applicantProfile = $documentProfile->applicant();
        // return view with variable
        return view('admission::application.modals.document', compact('applicantProfile', 'documentProfile'));
    }

    // store document
    public function storeDocument(Request $request)
    {
        // applicant Id
        $applicantId = $request->input('applicant_id');
        $documentId  = $request->input('document_id');
        $docType     = $request->input('doc_type');
        $docDetails  = $request->input('doc_details');
        // checking category file of this applicant
        if (!$this->document->where(['applicant_id' => $applicantId, 'doc_type' => $docType])->first() || $documentId > 0) {
            // file checking
            if ($photoFile = $request->file('document')) {
                // document details
                $fileExtension   = $photoFile->getClientOriginalExtension();
                $contentName     = $photoFile->getClientOriginalName();
                $contentFileName = $photoFile->getClientOriginalName();
                $destinationPath = 'assets/admission/documents/applicant-' . $applicantId . "/";
                // checking  $documentId
                if ($documentId > 0) {
                    $applicantDocument = $this->document->find($documentId);
                    // checking doc_type
                    if ($applicantDocument->doc_type == $docType) {
                        // delete old photo
                        File::delete($applicantDocument->doc_path . $applicantDocument->doc_name);
                    } else if(!$this->document->where(['applicant_id' => $applicantId, 'doc_type' => $docType])->first()) {
                        // delete old photo
                        File::delete($applicantDocument->doc_path . $applicantDocument->doc_name);
                    } else {
                        Session::flash('warning', $docType . ' is already exists');
                        // receiving page action
                        return redirect()->back();
                    }
                } else {
                    $applicantDocument = new $this->document();
                }
                // uploading file
                if ($uploaded = $photoFile->move($destinationPath, $contentFileName)) {
                    // storing file name to the database
                    // storing user document
                    $applicantDocument->applicant_id = $applicantId;
                    $applicantDocument->doc_name     = $contentFileName;
                    $applicantDocument->doc_type     = $docType;
                    $applicantDocument->doc_path     = $destinationPath;
                    $applicantDocument->doc_mime     = $fileExtension;
                    $applicantDocument->doc_details  = $docDetails;
                    // save document
                    $applicantDocumentSubmitted = $applicantDocument->save();
                    // checking
                    if ($applicantDocumentSubmitted) {
                        Session::flash('success', 'Document Submitted');
                        // receiving page action
                        return redirect()->back();
                    }
                } else {
                    Session::flash('warning', 'Unable to upload document');
                    // receiving page action
                    return redirect()->back();
                }
            } else {
                Session::flash('warning', 'Document Not Found');
                // receiving page action
                return redirect()->back();
            }
        } else {
            Session::flash('warning', $docType . ' is already exists');
            // receiving page action
            return redirect()->back();
        }
    }

    // edit profile photo
    public function editPhoto($id)
    {
        // applicant profile
        $applicantProfile = $this->applicant->find($id);
        // return with variable
        return view('admission::application.modals.applicant-photo', compact('applicantProfile'));
    }

    // update photo
    public function updatePhoto(Request $request)
    {
        // applicant id
        $applicantId = $request->input('applicant_id');
        $documentId  = $request->input('document_id');
        // file checking
        if ($photoFile = $request->file('image')) {
            // checking $documentId
            if ($documentId > 0) {
                $applicantDocument = $this->document->find($documentId);
                // delete old photo
                File::delete($applicantDocument->doc_path);
            } else {
                $applicantDocument = new $this->document();
            }
            // document details
            $fileExtension   = $photoFile->getClientOriginalExtension();
            $contentName     = $photoFile->getClientOriginalName();
            $contentFileName = $photoFile->getClientOriginalName();
            $destinationPath = 'assets/admission/images/'.$contentFileName;
            // uploading file
            if ($uploaded = $photoFile->move($destinationPath, $contentFileName)) {
                // storing user document
                $applicantDocument->applicant_id = $applicantId;
                $applicantDocument->doc_name     = $contentFileName;
                $applicantDocument->doc_type     = "PROFILE_PHOTO";
                $applicantDocument->doc_path     = $destinationPath;
                $applicantDocument->doc_mime     = $fileExtension;
                // save document
                $applicantDocumentSubmitted = $applicantDocument->save();
                // checking
                if ($applicantDocumentSubmitted) {
                    Session::flash('success', 'Document Added Successfully');
                    // receiving page action
                    return redirect()->back();
                }
            } else {
                Session::flash('warning', 'Unable to upload document');
                // receiving page action
                return redirect()->back();
            }
        } else {
            Session::flash('warning', 'Photo Not Found');
            // receiving page action
            return redirect()->back();
        }

    }

    // delete document
    public function deleteDocument($id)
    {
        // document profile
        $applicantDocument = $this->document->find($id);
        // old document path
        $oldDocumentPath = $applicantDocument->doc_path;
        // delete document profile
        $applicantDocumentDeleted = $applicantDocument->delete();
        // checking
        if ($applicantDocumentDeleted) {
            // delete old document file
            File::delete($oldDocumentPath);
            // success msg
            Session::flash('success', 'Document Deleted');
            return redirect()->back();
        } else {
            Session::flash('warning', 'Unable to perform action');
            return redirect()->back();
        }
    }
}
