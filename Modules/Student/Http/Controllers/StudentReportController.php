<?php

namespace Modules\Student\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use JasperPHP\JasperPHP;


class StudentReportController extends Controller
{
    /*
     * indexReport function
     * function is to create the report with necessary input
     */
    public function indexReport()
    {
        $report_name = 'User-Report'; //Please Do not use any space while creating $report_name
        $ext = "pdf"; // TODO This field value should come from user's choise like pdf/excel
        $driver = 'json'; //This could be changed to mysql
        $data = $this->createDataSource();
        if(isset($data)){
            $json_file_location = $this->createJsonFile($report_name, $data);
            if(isset($json_file_location)){
                $jasper_file_location = "/home/iftekhar/Project/ems/Modules/Student/Resources/reports/demoreport.jasper";
                $this->createReport($report_name, $json_file_location, $jasper_file_location, $ext, $driver);
            }
        }
        else{
            //TODO Redirect to Report General Error Page
        }

    }
    /*
     * createDatasource is a function to create custom datasource as needed for the report
     * Reuturn format of data should be json encoded.
     */
    public function createDataSource(){
        return json_encode(User::all());
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
        $file_location = public_path() . '/report/json/'.time().'_'.$report_name.'.json';
        $file = fopen($file_location, 'w');
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
    public function createReport($report_name, $json_file_location, $jasper_file_location, $ext, $driver)
    {
        $output_file = public_path() . '/report/'.time().'_'.$report_name;
        $jasper = new JasperPHP;
        $result = $jasper->process(
            $jasper_file_location,
            $output_file,
            array($ext),
            array(),
            array(
                'driver' => $driver,
                'json_query' => '""',
                'data_file' => $json_file_location
            )
        )->execute();

        //sleep(9);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename='.time().'_'.$report_name.'.'.$ext);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Content-Length: ' . filesize($output_file.'.'.$ext));
        flush();
        readfile($output_file.'.'.$ext);
        unlink($output_file.'.'.$ext);
    }
}
