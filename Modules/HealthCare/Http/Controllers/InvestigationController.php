<?php

namespace Modules\HealthCare\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use PDF;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\HealthCare\Entities\HealthInvestigation;
use Modules\HealthCare\Entities\HealthInvestigationReport;
use Modules\HealthCare\Http\Requests\InvestigationRequest;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\StudentInformation;

class InvestigationController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index()
    {
        $investiagtions = HealthInvestigation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        return view('healthcare::pages.investigation.list-of-investigation', compact('investiagtions'));
    }


    public function create()
    {
        $healthInvestigation = null;
        $investigationTemplate = view('healthcare::pages.investigation.investigation-template')->render();

        return view('healthcare::pages.investigation.manage-investigation', compact('healthInvestigation', 'investigationTemplate'));
    }


    public function store(InvestigationRequest $request)
    {
        DB::beginTransaction();
        try {
            $insertInvestigation = HealthInvestigation::insert([
                'report_type' => $request->reportType,
                'title' => $request->title,
                'sample' => $request->sample,
                'lab_id' => $request->labId,
                'report_pattern' => $request->reportPattern,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            if ($insertInvestigation) {
                DB::commit();
                Session::flash('message', 'Success! New investigation created successfully.');
                return redirect('/healthcare/investigation');
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('message', 'Error creating investigation.');
            return redirect('/healthcare/investigation');
        }
    }


    public function show($id)
    {
        return view('healthcare::show');
    }


    public function edit($id)
    {
        $healthInvestigation = HealthInvestigation::findOrFail($id);

        $investigationTemplate = view('healthcare::pages.investigation.investigation-template')->render();

        return view('healthcare::pages.investigation.manage-investigation', compact('investigationTemplate', 'healthInvestigation'));
    }


    public function update(InvestigationRequest $request, $id)
    {
        $healthInvestigation = HealthInvestigation::findOrFail($id);
        DB::beginTransaction();
        try {
            $updateInvestigation = $healthInvestigation->update([
                'report_type' => $request->reportType,
                'title' => $request->title,
                'sample' => $request->sample,
                'lab_id' => $request->labId,
                'report_pattern' => $request->reportPattern,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateInvestigation) {
                DB::commit();
                Session::flash('message', 'Success! Investigation updated successfully.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating investigation.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $investigation = HealthInvestigation::findOrFail($id);
        $investigationReports = $investigation->investigationReports;

        if (sizeof($investigationReports) > 0) {
            Session::flash('errorMessage', 'Can not delete! Report assigned under this investigation.');
            return redirect()->back();
        } else {
            $investigation->delete();
            Session::flash('message', 'Success! Investigation deleted successfully.');
            return redirect()->back();
        }
    }


    public function investigationReports()
    {
        $investiagtionReports = HealthInvestigationReport::with('prescription', 'investigation')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('healthcare::pages.investigation-reports.investigation-reports', compact('investiagtionReports'));
    }

    public function setReport($id)
    {
        $investigationReport = HealthInvestigationReport::findOrFail($id);
        $reportPattern = json_decode($investigationReport->investigation->report_pattern, 1);

        return view('healthcare::pages.investigation-reports.set-report', compact('investigationReport', 'reportPattern'));
    }

    public function saveReport(Request $request, $id)
    {
        $investigationReport = HealthInvestigationReport::findOrFail($id);
        DB::beginTransaction();
        try {
            $updateInvestigation = $investigationReport->update([
                'result' => json_encode($request->result),
                'status' => 2,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id()
            ]);

            if ($updateInvestigation) {
                DB::commit();
                Session::flash('message', 'Success! Investigation report updated successfully.');
                return redirect('/healthcare/investigation/reports');
            } else {
                Session::flash('errorMessage', 'Error updating investigation report.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating investigation report.');
            return redirect()->back();
        }
    }

    public function viewReport($id)
    {
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $investigationReport = HealthInvestigationReport::findOrFail($id);
        $reportPattern = json_decode($investigationReport->investigation->report_pattern, 1);

        if ($investigationReport->patient_type == 1) {
            $patient = $investigationReport->cadet;
        } else if ($investigationReport->patient_type == 2) {
            $patient = $investigationReport->employee;
        } else {
            $patient = null;
        }
        $patientAge = Carbon::parse($patient->dob)->diff(Carbon::now())->format('%y years, %m months');
        $date = Carbon::parse($investigationReport->created_at)->format('d/m/Y');

        $pdf = PDF::loadView('healthcare::pages.investigation-reports.investigation-report-pdf', compact('institute', 'investigationReport', 'patient', 'patientAge', 'reportPattern', 'date'));
        return $pdf->download('lab-report.pdf');
    }

    public function deliverReport($id)
    {
        $investigationReport = HealthInvestigationReport::findOrFail($id);
        DB::beginTransaction();
        try {
            $updateInvestigation = $investigationReport->update([
                'status' => 3,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);

            if ($updateInvestigation) {
                DB::commit();
                Session::flash('message', 'Success! Status updated successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating investigation report.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating investigation report.');
            return redirect()->back();
        }
    }
}
