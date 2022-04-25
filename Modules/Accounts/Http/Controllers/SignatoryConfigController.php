<?php

namespace Modules\Accounts\Http\Controllers;

use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Accounts\Entities\SignatoryConfig;
use Modules\Employee\Entities\EmployeeInformation;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sign;
use PhpParser\Node\Stmt\TryCatch;

class SignatoryConfigController extends Controller
{

    private $academicHelper;

    public function __construct(AcademicHelper $academicHelper)
    {
        $this->academicHelper = $academicHelper;
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function page($report_name)
    {
        $reportName = $report_name;

        $numberOfForms = SignatoryConfig::where([
            ['reportName', $report_name],
            ['campus_id', $this->academicHelper->getCampus()],
            ['institute_id', $this->academicHelper->getInstitute()]
        ])->count();
        if ($numberOfForms > 0) {
            $exists = true;
        } else {
            $exists = false;
        }

        return view('accounts::signatory-config.modal.signatory-config-modal', compact('reportName', 'exists', 'numberOfForms'));
    }

    // create signatory-config-form
    public function createForm(Request $request)
    {

        $reportName = $request->reportName;
        // print_r($reportName);
        $employeInformations = EmployeeInformation::with('singleUser')->get();
        // $signatoryConfig= SignatoryConfig::all();
        $getSignatoryConfig = SignatoryConfig::with('employeeInfo.singleDepartment', 'employeeInfo.singleDesignation')->where([
            ['reportName', $reportName],
            ['campus_id', $this->academicHelper->getCampus()],
            ['institute_id', $this->academicHelper->getInstitute()]
        ]);
        $signatoryConfig = $getSignatoryConfig->get();

        $start = $getSignatoryConfig->count();
        $numberOfForms = (int)$request->totalForm - $start;
        return view('accounts::signatory-config.signatory-config-form', compact('start', 'numberOfForms', 'employeInformations', 'reportName', 'signatoryConfig'));
    }

    // get employee designation
    public function getdesignation(Request $request)
    {
        $hrId = $request->hrId;
        $employeeInformation = EmployeeInformation::with('singleDesignation', 'singleDepartment')->where('id', $hrId)->first();
        return $employeeInformation;
    }

    // insert Signatory 
    public function insertSignatory(Request $request)
    {
        $request->validate([
            'label.*' => 'required',
            'empolyee_id.*' => 'required',
            'attatch.*' => 'image|mimes:jpg,png'
        ]);

        DB::beginTransaction();
        try {
            for ($i = 0; $i <= array_key_last($request->empolyee_id); $i++) {

                if (isset($request->empolyee_id[$i])) {
                    if ($request->empolyee_id[$i] != null) {
                        $data = new SignatoryConfig();

                        $cheackEmployee = SignatoryConfig::where([
                            ['reportName', $request->reportName],
                            ['campus_id', $this->academicHelper->getCampus()],
                            ['institute_id', $this->academicHelper->getInstitute()],
                            ['empolyee_id', $request->empolyee_id[$i]]
                        ])->first();
                        if ($cheackEmployee) {
                            // update signature config
                            if (isset($request->file('attatch')[$i])) {
                                if ($cheackEmployee->attatch) {
                                    if (base_path('public/assets/signatory/') . $cheackEmployee->attatch) {

                                        unlink(base_path('public/assets/signatory/') . $cheackEmployee->attatch);
                                    }
                                }
                                 $image = $request->file('attatch')[$i];
                                $name = 'attatch-' . $request->empolyee_id[$i] . '-' . time() . '.' . $image->getClientOriginalExtension();
                                $image->move(public_path() . '/assets/signatory/', $name);
                                $cheackEmployee->update([
                                    'attatch' => $name
                                ]);
                            }
                            $cheackEmployee->update([
                                'reportName' => $request->reportName,
                                'label' => $request->label[$i],
                                'empolyee_id' => $request->empolyee_id[$i],
                            ]);
                            // echo "ache";
                        } else {
                            // insert signature config
                            if (isset($request->file('attatch')[$i])) {
                                // signatory image make start

                                $image = $request->file('attatch')[$i];
                                $name = 'attatch-' . $request->empolyee_id[$i] . '-' . time() . '.' . $image->getClientOriginalExtension();
                                $image->move(public_path() . '/assets/signatory/', $name);
                                // signatory image make End
                                $data->campus_id = $this->academicHelper->getCampus();
                                $data->institute_id = $this->academicHelper->getInstitute();
                                $data->reportName = $request->reportName;
                                $data->label = $request->label[$i];
                                $data->empolyee_id = $request->empolyee_id[$i];
                                $data->attatch = $name;
                                $data->save();
                                // echo "Image Ache";
                            }
                            // echo "Image Nai";
                            $data->campus_id = $this->academicHelper->getCampus();
                            $data->institute_id = $this->academicHelper->getInstitute();
                            $data->reportName = $request->reportName;
                            $data->label = $request->label[$i];
                            $data->empolyee_id = $request->empolyee_id[$i];
                            $data->save();
                        }
                    }
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return back();
    }

    /**
     * deleteSignatory
     * @param int Request
     */
    public function deleteSignatory($id)
    {
        $signatoryDelete = SignatoryConfig::where('id', $id)->first();
        $reportName = $signatoryDelete->reportName;
        if ($signatoryDelete->attatch) {
            if (base_path('public/assets/signatory/') . $signatoryDelete->attatch) {

                unlink(base_path('public/assets/signatory/') . $signatoryDelete->attatch);
            }
        }
        $signatoryDelete->delete();
        return back();
    }
}
