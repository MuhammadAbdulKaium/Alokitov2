<?php

namespace Modules\Academics\Http\Controllers\Reports;

use App\User;
use Illuminate\Routing\Controller;
use JasperPHP\JasperPHP;
use Modules\Academics\Entities\Batch;
use Modules\Academics\Entities\Section;
use Modules\Setting\Entities\Institute;
use Modules\Student\Entities\StudentInformation;
use Modules\Student\Entities\StudentProfileView;
use App\Http\Controllers\Helpers\AcademicHelper;

class GenderReportController extends Controller
{

    private $studentProfileView;
    private $studentInformation;
    private $institute;
    private $academicHelper;

    public function __construct(Institute $institute, StudentInformation $studentInformation, StudentProfileView $studentProfileView,  AcademicHelper $academicHelper)
    {
        $this->studentProfileView = $studentProfileView;
        $this->studentInformation = $studentInformation;
        $this->institute          = $institute;
        $this->academicHelper          = $academicHelper;
    }

    /*
     * indexReport function
     * function is to create the report with necessary input
     */
    public function indexReport($reportDetails)
    {
        if ($reportDetails->downloadToken) {
            $downLoadToken = $reportDetails->downloadToken;
        } else {
            $downLoadToken = 1;
        }

        // report_type
        if ($reportDetails->report_type == "gender") {
            $report_name     = 'Student-Gender-Report'; //Please Do not use any space while creating $report_name
            $report_location = "/Modules/Academics/Resources/reports/gender-report.jrxml";
        } elseif ($reportDetails->report_type == "birthday") {
            $report_name     = 'Student-Birthday-Report'; //Please Do not use any space while creating $report_name
            $report_location = "/Modules/Academics/Resources/reports/birthday-report.jrxml";
        } else {
            $report_name     = 'Student-Contact-Report'; //Please Do not use any space while creating $report_name
            $report_location = "/Modules/Academics/Resources/reports/contact-report.jrxml";
        }

        // doc_type
        if ($reportDetails->doc_type == "pdf") {
            $ext = 'pdf'; // TODO This field value should come from user's choise like pdf/excel
        } else {
            $ext = 'xlsx'; // TODO This field value should come from user's choise like pdf/excel
        }

        $driver     = 'json'; //This could be changed to mysql
        $data       = $this->createDataSource($reportDetails);
        $parameters = $this->createParameter();
        if (isset($data)) {
            $json_file_location = $this->createJsonFile($report_name, $data);
            if (isset($json_file_location)) {
                $jasper_file_location = env('HOME_PROJECT') . $report_location;

                setcookie(
                    "downloadToken",
                    $downLoadToken,
                    time() + (30 * 60),
                    // expires January 1, 2038
                    "/", // your path
                    null, // your domain
                    false, // Use true over HTTPS
                    false// Set true for $AUTH_COOKIE_NAME
                );

                return $this->createReport($report_name, $json_file_location, $jasper_file_location, $ext, $driver, $parameters);
            }
        } else {
            //TODO Redirect to Report General Error Page
            return false;
        }

    }

    /*
     * createDatasource is a function to create custom datasource as needed for the report
     * Reuturn format of data should be json encoded.
     */
    public function createDataSource($reportDetails)
    {
        $class   = $reportDetails->class;
        $section = $reportDetails->section;

        if ($class > 0 and $section > 0) {
            $studentProfileViewList = $this->studentProfileView->orderBy('batch', 'ASC')->orderBy('section', 'ASC')->where(['academic_year' => $this->getAcademicYearId(), 'batch' => $class, 'section' => $section])->get();
        } else {
            $studentProfileViewList = $this->studentProfileView->orderBy('batch', 'ASC')->orderBy('section', 'ASC')->where(['academic_year' => $this->getAcademicYearId()])->get();
        }

        if ($studentProfileViewList) {
            // array for response
            $response = array();
            // looping
            foreach ($studentProfileViewList as $studentProfile) {

                $row                 = array();
                $row['first_name']   = $studentProfile->first_name;
                $row['middle_name']  = $studentProfile->middle_name;
                $row['last_name']    = $studentProfile->last_name;
                $row['batch_name']   = $studentProfile->batch()->batch_name;
                $row['section_name'] = $studentProfile->section()->section_name;

                // checking
                if ($reportDetails->report_type == "contact") {
                    $stdProfile = $studentProfile->student();
                    $row['email'] = $stdProfile->email;
                    $row['phone'] = $stdProfile->phone;
                    // std user profile
                    $userProfile = $studentProfile->user();
                    // present address
                    if ($present = $userProfile->singleAddress("STUDENT_PRESENT_ADDRESS")) {
                        $row['preaddress'] = $present->address . ", House # " . $present->house . ", " . $present->city()->name . ", " . $present->state()->name . ", " . $present->country()->name . ", " . $present->zip;
                    } else {
                        $row['preaddress'] = 'not availbale';
                    }
                    // permanent address
                    if ($permanent = $userProfile->singleAddress("STUDENT_PERMANENT_ADDRESS")) {
                        $row['peraddress'] = $permanent->address . ", House # " . $permanent->house . ", " . $permanent->city()->name . ", " . $permanent->state()->name . ", " . $permanent->country()->name . ", " . $permanent->zip;
                    } else {
                        $row['peraddress'] = 'not availbale';
                    }
                } elseif ($reportDetails->report_type == "birthday") {
                    $row['dob'] = $studentProfile->student()->dob;
                } else {
                    $row['gender'] = $studentProfile->student()->gender;
                }

                array_push($response, $row);
            }

            return json_encode($response);
        } else {
            return null;
        }

    }

    public function createParameter()
    {
        //The school name and logo should be sent by parameter
        //$instituteInfo = $this->institute->where('id', 1)->first(['id', 'institute_name', 'address1', 'email', 'phone', 'website', 'logo']);
        $instituteInfo = $this->getInstituteProfile();
        // return institute info as array
        return array(
            'name'    => $instituteInfo->institute_name,
            'address' => $instituteInfo->address1,
            'email'   => $instituteInfo->email,
            'phone'   => $instituteInfo->phone,
            'website' => $instituteInfo->website,
            'logo'    => public_path() . "/assets/users/images/" . $instituteInfo->logo,
        );
    }

    /*
     * createJsonFile is a function to create custom json file as needed for the report
     * Reuturn format of this function should be the file path
     * TODO in some cases the generated file should be stored in the database system
     *
     * @param $report_name
     * @param $data
     * @return string
     */

    public function createJsonFile($report_name, $data)
    {
        $file_location = public_path() . '/report/json/' . time() . '_' . $report_name . '.json';
        $file          = fopen($file_location, 'w');
        fwrite($file, $data);
        fclose($file);
        return $file_location;
    }

    /**
     * createReport function
     * takes the following required parameters to create the report
     * and make it downloadable to users
     *
     * @param $report_name
     * @param $json_file_location
     * @param $jasper_file_location
     * @param $ext
     * @param $driver
     */
    public function createReport($report_name, $json_file_location, $jasper_file_location, $ext, $driver, &$parameters)
    {
        $output_file = public_path() . '/report/' . time() . '_' . $report_name;
        $jasper      = new JasperPHP;
        $result      = $jasper->process(
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
//
//        var_dump($result);
//        exit();

        //sleep(9);
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

    /////////////////////  student attendance report /////////////////////

    public function searchAttendanceReport($id)
    {
        return view('student::pages.student-profile.modals.attendance-report')->with('id', $id);
    }


    /////////////  get institute information from session    /////////////
    public function getAcademicYearId()
    {
        return $this->academicHelper->getAcademicYear();
    }

    public function getInstituteId()
    {
        return $this->academicHelper->getInstitute();
    }
    public function getInstituteProfile()
    {
        return $this->academicHelper->getInstituteProfile();
    }

    public function getInstituteCampusId()
    {
        return $this->academicHelper->getCampus();
    }

    public function getGradeScaleTypeId()
    {
        return $this->academicHelper->getGradingScale();
    }

    public function getAcademicSemesterId()
    {
        return 1;
    }

}
