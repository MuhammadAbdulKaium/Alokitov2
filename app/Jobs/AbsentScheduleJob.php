<?php

namespace App\Jobs;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;
use Modules\Academics\Entities\AttendanceUpload;
use Modules\Student\Entities\StudentProfileView;
use Modules\Academics\Entities\AttendanceUploadAbsent;
use Modules\Student\Entities\StudentInformation;

class AbsentScheduleJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

              Log::info("Absent Schedule Job Started...");
//                try {
                   // loop presentStudent Class Section
                    $presentList=$this->getTodayClassSectionAttendanceList();
                   Log::info('sss'.$presentList);
                    foreach ($presentList as $present) {
                        //all student ids
                        $allStudentArray = StudentProfileView::where('section', $present->section)
                            ->where('batch', $present->batch)
                            ->where('institute', $present->institute)
                            ->where('campus', $present->campus)
                            ->pluck('std_id')->toArray();
                        Log::info('AllStudent' . print_r($allStudentArray));
                        $presentStudentArray = AttendanceUpload::where('batch', $present->batch)
                            ->where('institute', $present->institute)
                            ->where('campus', $present->campus)
                            ->pluck('std_id')->toArray();
                        Log::info('PresentSMS' . print_r($presentStudentArray));
                        $absentStudentList = array_diff($allStudentArray, $presentStudentArray);

                        foreach ($absentStudentList as $key => $value) {
                            $daystart= date('Y-m-d').' 00:00:00';
                            $dayend= date('Y-m-d').' 23:59:59';
                            $studentAttendanceProfile=AttendanceUploadAbsent::where('std_id',$value)
                                                ->whereBetween('entry_date_time', [$daystart, $dayend])
                                                ->first();


                            if(empty($studentAttendanceProfile)){
                                // insert student absent data in abset table
                                $studentPrepareData=$this->prepareStudenteAbsentData($value);
                                AttendanceUploadAbsent::create($studentPrepareData);
                            }

                            Log::info("Stueent IDs..." . $value);
                          }

                    }


////                } catch (\Exception $exception) {
////                    Log::info("Abset Sms Device Ended With Error...");
////                }
    }


    public function getTodayClassSectionAttendanceList(){
         $daystart= date('Y-m-d').' 00:00:00';
         $dayend= date('Y-m-d').' 23:59:59';
         return $prestStudentList=AttendanceUpload::
        whereBetween('entry_date_time', [$daystart, $dayend])->where('is_device',1)->distinct('section')->get();
    }

    public function prepareStudenteAbsentData($studentId){
        $std_id = $studentId;
        $student = StudentInformation::find($std_id);
        if(empty($student)) return null;
        $student_enrolement = $student->enroll();
        if(empty($student_enrolement)) return null;
        $data = array(
            'std_id' => $std_id,
            'std_gr_no' => $student_enrolement->gr_no,
            'academic_year' => $student_enrolement->academic_year,
            'level' => $student_enrolement->academic_level,
            'batch' => $student_enrolement->batch,
            'section' => $student_enrolement->section,
            'institute' => $student->institute,
            'campus' => $student->campus,
            'entry_date_time' => date("Y-m-d H:i:s"),
        );
        return $data;
    }




}
