<?php

namespace Modules\HealthCare\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Modules\Employee\Entities\EmployeeInformation;
use Modules\HealthCare\Entities\HealthDrug;
use Modules\HealthCare\Entities\HealthInvestigation;
use Modules\HealthCare\Entities\HealthInvestigationReport;
use Modules\HealthCare\Entities\HealthPrescription;
use Modules\Inventory\Entities\CadetInventoryProduct;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;
use PDF;

class HealthCareController extends Controller
{
    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }


    public function index()
    {
        $prescriptions = HealthPrescription::with('cadet', 'employee')->where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();

        return view('healthcare::pages.prescription.index', compact('prescriptions'));
    }


    public function create(Request $request)
    {
        $request->validate([
            'userType' => 'required',
            'userId' => 'required'
        ]);

        if ($request->followUpId) {
            $followUpPrescription = HealthPrescription::find($request->followUpId);
            if (!$followUpPrescription) {
                Session::flash('errorMessage', 'Error! No prescription found with this ID.');
                return redirect()->back();
            }
            // User Type 1 = cadet and 2 = HR/FM
            $userType = $followUpPrescription->patient_type;
            if ($userType == 1) {
                $patient = StudentInformation::findOrFail($followUpPrescription->patient_id);
            } else if ($userType == 2) {
                $patient = EmployeeInformation::findOrFail($followUpPrescription->patient_id);
            } else {
                $patient = null;
            }
        } else {
            $followUpPrescription = null;
            // User Type 1 = cadet and 2 = HR/FM
            $userType = $request->userType;
            if ($userType == 1) {
                $patient = StudentInformation::findOrFail($request->userId);
            } else if ($userType == 2) {
                $patient = EmployeeInformation::findOrFail($request->userId);
            } else {
                $patient = null;
            }
        }


        $patientAge = Carbon::parse($patient->dob)->diff(Carbon::now())->format('%y years, %m months');
        $todayDate = Carbon::now();
        $medicalOfficer = Auth::user();
        $institute = Institute::findOrFail($this->academicHelper->getInstitute());
        $investigations = HealthInvestigation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $drugs = CadetInventoryProduct::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'category_id' => 11
        ])->get();

        $prescription = null;

        return view('healthcare::pages.prescription.prescription', compact('userType', 'patient', 'patientAge', 'todayDate', 'medicalOfficer', 'institute', 'investigations', 'prescription', 'followUpPrescription', 'drugs'));
    }


    public function store(Request $request)
    {
        $investigations = json_decode($request->content, 1)['investigations'];
        $treatments = json_decode($request->content, 1)['treatments'];

        DB::beginTransaction();
        try {
            if ($request->followUp) {
                $followUpPrescription = HealthPrescription::findOrFail($request->followUp);
                $followUpPrescription->update([
                    'status' => 3,
                    'created_at' => Carbon::now(),
                    'created_by' => Auth::id(),
                ]);
            }

            // Store Prescription at prescription table
            $prescriptionId = HealthPrescription::insertGetId([
                'patient_type' => $request->patientType,
                'patient_id' => $request->patientId,
                'follow_up' => $request->followUp,
                'content' => $request->content,
                'status' => $request->status,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ]);

            // Store Investigation at investiagation_reports table
            if (sizeof($investigations) > 0) {
                foreach ($investigations as $key => $investigation) {
                    HealthInvestigationReport::insert([
                        'patient_type' => $request->patientType,
                        'patient_id' => $request->patientId,
                        'prescription_id' => $prescriptionId,
                        'investigation_id' => $investigation['id'],
                        'status' => 1,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                }
            }

            // Store Drugs at drugs table
            if (sizeof($treatments) > 0) {
                foreach ($treatments as $key => $treatment) {
                    HealthDrug::insert([
                        'patient_type' => $request->patientType,
                        'patient_id' => $request->patientId,
                        'prescription_id' => $prescriptionId,
                        'product_id' => $treatment['drugId'],
                        'quantity' => $treatment['quantity'],
                        'status' => 1,
                        'created_at' => Carbon::now(),
                        'created_by' => Auth::id(),
                        'campus_id' => $this->academicHelper->getCampus(),
                        'institute_id' => $this->academicHelper->getInstitute(),
                    ]);
                }
            }

            DB::commit();
            Session::flash('message', 'Success! Prescription created successfully.');
            return redirect('/healthcare/prescription');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error creating Prescription.');
            return redirect()->back();
        }
    }


    public function show($id)
    {
        return view('healthcare::show');
    }


    public function edit($id)
    {
        $prescription = HealthPrescription::findOrFail($id);
        $followUpPrescription = null;

        // User Type 1 = cadet and 2 = HR/FM
        $userType = $prescription->patient_type;
        if ($userType == 1) {
            $patient = StudentInformation::findOrFail($prescription->patient_id);
        } else if ($userType == 2) {
            $patient = EmployeeInformation::findOrFail($prescription->patient_id);
        } else {
            $patient = null;
        }
        $patientAge = Carbon::parse($patient->dob)->diff(Carbon::now())->format('%y years, %m months');
        $todayDate = Carbon::parse($prescription->created_at);
        $medicalOfficer = $prescription->createdBy;
        $institute = Institute::findOrFail($prescription->institute_id);
        $investigations = HealthInvestigation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $drugs = CadetInventoryProduct::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'category_id' => 11
        ])->get();

        return view('healthcare::pages.prescription.prescription', compact('userType', 'patient', 'patientAge', 'todayDate', 'medicalOfficer', 'institute', 'investigations', 'prescription', 'followUpPrescription', 'drugs'));
    }


    public function update(Request $request, $id)
    {
        $prescription = HealthPrescription::findOrFail($id);
        $investigations = json_decode($request->content, 1)['investigations'];
        $treatments = json_decode($request->content, 1)['treatments'];

        DB::beginTransaction();
        try {
            $prescription->update([
                'content' => $request->content,
                'status' => $request->status,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);

            // Delete old awaiting investigations
            HealthInvestigationReport::where([
                'prescription_id' => $prescription->id,
                'status' => 1,
            ])->delete();

            // Store Investigation at investiagation_reports table
            if (sizeof($investigations) > 0) {
                foreach ($investigations as $key => $investigation) {
                    $oldInvestigations = HealthInvestigationReport::where([
                        'prescription_id' => $prescription->id,
                        'investigation_id' => $investigation['id'],
                    ])->first();
                    if (!$oldInvestigations) {
                        HealthInvestigationReport::insert([
                            'patient_type' => $request->patientType,
                            'patient_id' => $request->patientId,
                            'prescription_id' => $prescription->id,
                            'investigation_id' => $investigation['id'],
                            'status' => 1,
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }
            }

            // Delete old pending drug reports
            $oldDrug = HealthDrug::where([
                'prescription_id' => $prescription->id,
                'status' => 1,
            ])->delete();

            // Store Drugs at drugs table
            if (sizeof($treatments) > 0) {
                foreach ($treatments as $key => $treatment) {
                    $oldDrug = HealthInvestigationReport::where([
                        'prescription_id' => $prescription->id,
                        'product_id' => $treatment['drugId'],
                    ])->first();
                    if (!$oldDrug) {
                        HealthDrug::insert([
                            'patient_type' => $request->patientType,
                            'patient_id' => $request->patientId,
                            'prescription_id' => $prescription->id,
                            'product_id' => $treatment['drugId'],
                            'quantity' => $treatment['quantity'],
                            'status' => 1,
                            'created_at' => Carbon::now(),
                            'created_by' => Auth::id(),
                            'campus_id' => $this->academicHelper->getCampus(),
                            'institute_id' => $this->academicHelper->getInstitute(),
                        ]);
                    }
                }
            }

            DB::commit();
            Session::flash('message', 'Success! Prescription updated successfully.');
            return redirect('/healthcare/prescription');
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating prescription.');
            return redirect()->back();
        }
    }


    public function destroy($id)
    {
        $prescription = HealthPrescription::findOrFail($id);

        $publishedInvestigation = $prescription->investigations->firstWhere('status', '!=', 1);
        $deliveredDrug = $prescription->drugReports->firstWhere('status', '!=', 1);

        if ($publishedInvestigation) {
            Session::flash('errorMessage', 'Can not delete! Drug delivered under this prescription.');
            return redirect()->back();
        } elseif ($deliveredDrug) {
            Session::flash('errorMessage', 'Can not delete! Investigation report published for this prescription.');
            return redirect()->back();
        } else {
            $prescription->investigations()->delete();
            $prescription->drugReports()->delete();
            $prescription->delete();

            Session::flash('message', 'Success! Prescription deleted successfully.');
            return redirect()->back();
        }
    }

    public function prescriptionStatusChange($id, $status)
    {
        $prescription = HealthPrescription::findOrFail($id);

        DB::beginTransaction();
        try {
            $updateStatus = $prescription->update([
                'status' => $status,
                'updated_at' => Carbon::now(),
                'updated_by' => Auth::id(),
            ]);

            if ($updateStatus) {
                DB::commit();
                Session::flash('message', 'Success! Prescription status changed successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error updating status.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error updating status.');
            return redirect()->back();
        }
    }

    public function printPrescription($id)
    {
        $prescription = HealthPrescription::findOrFail($id);

        $userType = $prescription->patient_type;
        if ($userType == 1) {
            $patient = StudentInformation::findOrFail($prescription->patient_id);
        } else if ($userType == 2) {
            $patient = EmployeeInformation::findOrFail($prescription->patient_id);
        } else {
            $patient = null;
        }
        $patientAge = Carbon::parse($patient->dob)->diff(Carbon::now())->format('%y years, %m months');
        $todayDate = Carbon::parse($prescription->created_at);
        $medicalOfficer = $prescription->createdBy;
        $institute = Institute::findOrFail($prescription->institute_id);
        $investigations = HealthInvestigation::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        $drugs = CadetInventoryProduct::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
            'category_id' => 11
        ])->get();

        $pdf = PDF::loadView('healthcare::pages.prescription.prescription-pdf', compact('userType', 'patient', 'patientAge', 'todayDate', 'medicalOfficer', 'institute', 'investigations', 'prescription', 'drugs'));
        return $pdf->download('prescription.pdf');
    }


    public function usersFromUserType(Request $request)
    {
        if ($request->userType == 1) {
            return StudentInformation::where([
                'campus' => $this->academicHelper->getCampus(),
                'institute' => $this->academicHelper->getInstitute(),
            ])->get();
        } else if ($request->userType == 2) {
            return EmployeeInformation::where([
                'campus_id' => $this->academicHelper->getCampus(),
                'institute_id' => $this->academicHelper->getInstitute(),
            ])->get();
        } else {
            return [];
        }
    }

    public function drugReports()
    {
        $drugReports = HealthDrug::where([
            'campus_id' => $this->academicHelper->getCampus(),
            'institute_id' => $this->academicHelper->getInstitute(),
        ])->get();
        return view('healthcare::pages.drugs.drug-reports', compact('drugReports'));
    }

    public function drugStatusChange($id, $status)
    {
        $drug = HealthDrug::findOrFail($id);
        DB::beginTransaction();
        try {
            $updateStatus = $drug->update([
                'status' => $status,
                'created_at' => Carbon::now(),
                'created_by' => Auth::id(),
            ]);

            if ($updateStatus) {
                DB::commit();
                Session::flash('message', 'Success! Status changed successfully.');
                return redirect()->back();
            } else {
                Session::flash('errorMessage', 'Error Updating Status.');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('errorMessage', 'Error Updating Status.');
            return redirect()->back();
        }
    }
}
