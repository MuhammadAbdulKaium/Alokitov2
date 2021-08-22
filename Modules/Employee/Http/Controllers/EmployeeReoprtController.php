<?php

namespace Modules\Employee\Http\Controllers;

use Illuminate\Routing\Controller;
use JasperPHP\JasperPHP;
use Modules\Employee\Entities\EmployeeInformation;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\App;

class EmployeeReoprtController extends Controller
{


    private $academicHelper;
    private $employeeInformation;
    // constructor
    public function __construct(AcademicHelper  $academicHelper, EmployeeInformation $employeeInformation) {
        $this->academicHelper        = $academicHelper;
        $this->employeeInformation   = $employeeInformation;
    }


    public function index($id)
    {
        // instituteId and campus details
        $campusId = $this->academicHelper->getCampus();
        $instituteId = $this->academicHelper->getInstitute();
        // find employee profile
        $employeeProfile = $this->employeeInformation->where(['id'=>$id, 'campus_id'=>$campusId, 'institute_id'=>$instituteId])->first();
        // checking
        if($employeeProfile){
            // institute profile
            $instituteInfo = $this->academicHelper->getInstituteProfile();
            // share all variables with the view
            view()->share(compact('employeeProfile', 'instituteInfo'));
            $html = view('employee::reports.employee-profile');
            $pdf = App::make('snappy.pdf.wrapper');
            $pdf->loadHTML($html);
            return $pdf->inline($id.'_employee_profile.pdf');
        }else{
            // 404 errors redirection
            abort(404);
        }
    }

    //function is to create the report with necessary input
    public function indexReport($id)
    {
        // report name without space
        $report_name = 'Employee-Report';
        // report type like as -- pdf/excel
        $ext = "pdf";
        // driver
        $driver = 'json';
        // data source
        $data = $this->createDataSource($id);
        // parameters
        $parameters = $this->createParameter($id);
        // checking
        if (isset($data)) {
            // create json file
            $json_file_location = $this->createJsonFile($report_name, $data);
            // checking
            if (isset($json_file_location)) {
                $jasper_file_location = env('HOME_PROJECT') . "/Modules/Employee/Resources/views/reports/employeeProfile.jasper";
                $this->createReport($report_name, $json_file_location, $jasper_file_location, $ext, $driver, $parameters);
            }
        } else {
            //TODO Redirect to Report General Error Page
        }

    }

    // Parameters for Report and its Reuturn format of data should be json encoded.
    public function createParameter($id)
    {
        // array for response
        $response = array();

        // studentProfile
        $employeeProfile = EmployeeInformation::findOrFail($id);

        // storing student profile details
        $response['name']   = $employeeProfile->middle_name;
        $response['emp_id'] = $employeeProfile->id;
        $response['email']  = $employeeProfile->email;
        $response['phone']  = $employeeProfile->phone;
        $response['status'] = "null";

        $response['alias']       = $employeeProfile->alias;
        $response['dob']         = $employeeProfile->dob;
        $response['doj']         = $employeeProfile->doj;
        $response['b_group']     = $employeeProfile->blood_group;
        $response['b_place']     = $employeeProfile->birth_place;
        $response['religion']    = $employeeProfile->religion;
        $response['category']    = $employeeProfile->category;
        $response['designation'] = $employeeProfile->designation()->name;
        $response['department']  = $employeeProfile->department()->name;
        $response['m_status']    = $employeeProfile->marital_status;
        $response['t_exp']       = $employeeProfile->experience_year . "Years " . $employeeProfile->experience_month . "Months";

        // studnet address
        $allAddress = $employeeProfile->user()->allAddress();
        // looping
        foreach ($allAddress as $address) {
            // present address
            if ($address->type == "EMPLOYEE_PRESENT_ADDRESS") {
                // present array
                $response['pre_address'] = $address->address;
                $response['pre_city']    = $address->city()->name;
                $response['pre_state']   = $address->state()->name;
                $response['pre_country'] = $address->country()->name;
                $response['pre_zip']     = $address->zip;
                $response['pre_house']   = $address->house;
                $response['pre_phone']   = $address->phone;
            }
            if ($address->type == "EMPLOYEE_PERMANENT_ADDRESS") {
                // permanent array
                $response['per_address'] = $address->address;
                $response['per_city']    = $address->city()->name;
                $response['per_state']   = $address->state()->name;
                $response['per_country'] = $address->country()->name;
                $response['per_zip']     = $address->zip;
                $response['per_house']   = $address->house;
                $response['per_phone']   = $address->phone;
            }
        }

        // return response
        return $response;

    }

    // DataSource for Report and its Reuturn format of data should be json encoded.
    public function createDataSource($id)
    {
        // array for response
        $response = array();

        // studentProfile
        $studentProfile = EmployeeInformation::findOrFail($id);

        $allguardians = array();
        foreach ($studentProfile->allGuardian() as $guardian) {
            $response['name']          = $guardian->first_name;
            $response['email']         = $guardian->email;
            $response['mobile']        = $guardian->mobile;
            $response['phone']         = $guardian->phone;
            $response['qualification'] = $guardian->qualification;
            $response['occupation']    = $guardian->occupation;
            $response['e_status']      = $guardian->marital_status;
            $response['o_address']     = $guardian->office_address;
            $response['h_address']     = $guardian->home_address;
            $response['relation']      = $guardian->relation;

            array_push($allguardians, $response);
        }

        return json_encode($allguardians);
    }

    // createJsonFile function and Reuturn format of this function should be the file path
    public function createJsonFile($report_name, $data)
    {
        // file location
        $file_location = public_path() . '/report/json/employee/' . time() . '_' . $report_name . '.json';
        // open file
        $file = fopen($file_location, 'w');
        // write file
        fwrite($file, $data);
        // close file
        fclose($file);
        // return file location
        return $file_location;
    }

    // createReport function and make it downloadable to users
    public function createReport($report_name, $json_file_location, $jasper_file_location, $ext, $driver, &$parameters)
    {
        // report location
        $output_file = public_path() . '/report/reports-employee/' . time() . '_' . $report_name;
        // new jesper
        $jasper = new JasperPHP;
        // porcess jesper
        $result = $jasper->process(
            $jasper_file_location,
            $output_file,
            array($ext),
            $parameters,
            array(
                'driver'     => $driver,
                'json_query' => '""',
                'data_file'  => $json_file_location,
            )
        )->execute();

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . time() . '_' . $report_name . '.' . $ext);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output_file . '.' . $ext));
        flush();
        readfile($output_file . '.' . $ext);
        unlink($output_file . '.' . $ext);
    }
}
