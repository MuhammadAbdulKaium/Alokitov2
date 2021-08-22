<?php


use Modules\Setting\Entities\SettingInstProp;
use Modules\Student\Entities\StudentEnrollment;
use Modules\Academics\Entities\Semester;
use Modules\Academics\Entities\AcademicsYear;
use Modules\Setting\Entities\Institute;
use Modules\Setting\Entities\Campus;
use Modules\Setting\Entities\FessSetting;
use Modules\Student\Entities\StudentInformation;
use Modules\Academics\Entities\ClassSubject;





function hello()
{
    return "this is helper function";
}



//Number Formatter Change Function

function numberFormatter($value){
    $getLang= App::getLocale();
    $numberFormatter = new NumberFormatter( $getLang, NumberFormatter::PATTERN_DECIMAL );
    return $numberFormatter->format($value);
}


    function en2bnNumber ($number){
        $search_array= array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0");
        $replace_array = array("১", "২", "৩", "৪", "৫", "৬", "৭", "৮", "৯", "০");
        $bn_number = str_replace($search_array, $replace_array, $number);

        return $bn_number;
    }


// gcd
function alokito_gcd_ratio($a, $b)
{
    $var1=$a;
    $var2=$b;

    for($x=$var2;$x>1;$x--) {
        if(($var1%$x)==0 && ($var2 % $x)==0) {
            $var1 = $var1/$x;
            $var2 = $var2/$x;
        }
    }
    // return
    return "$var1 : $var2";
}


///////////////////////////////  Academic Helper functions area starts here ///////////////////////////////

// sorting timetable using teacher and period id
function sortTimetableForTeacher($teacher, $period, $allTimeTables)
{
    return periodSorter($period, teacherSorter($teacher, $allTimeTables));
}

// sorting timetable using teacher id
function teacherSorter($teacher, $collections)
{
    return $collections->filter(function($timetable) use ($teacher)
    {
        return $timetable->teacher == $teacher;
    });
}


// sorting timetable using day and period id
function sortTimetable($day, $period, $allTimeTables)
{
    return periodSorter($period, daySorter($day, $allTimeTables));
}

// sorting timetable using day id
function daySorter($day, $collections)
{
    return $collections->filter(function($timetable) use ($day)
    {
        return $timetable->day == $day;
    });
}

// sorting timetable using period id
function periodSorter($period, $collections)
{
    return $collections->filter(function($timetable) use ($period)
    {
        return $timetable->period == $period;
    });
}

// batch section filter for waiver list
function batchSorter($batch, $collections)
{
    return $collections->filter(function($waiver) use ($batch)
    {
        return $waiver->batch == $batch;
    });
}


// sorting event using start_date_time and end_date_time
function eventDateSorter($startDateTime,  $collections)
{
    return $collections->filter(function($event) use ($startDateTime)
    {
        if(date('d-m-Y', strtotime($event->start_date_time))==date('d-m-Y', strtotime($startDateTime))) {
            return $event;
        }
    });
}

// section shorting for waiver list
function sectionSorter($section, $collections)
{
    return $collections->filter(function($waiver) use ($section)
    {
        return $waiver->section == $section;
    });
}

// section shorting for waiver list
function findClassSubject($csId)
{
    return ClassSubject::find($csId);
}

// section shorting for waiver list
function findStudent($stdId)
{
    return StudentInformation::find($stdId);
}


function getAcademicSemesters(){
    return Semester::where([
        'academic_year_id'=> session()->get('academic_year'),
        'status'=>1
    ])->get();
}
function getAcademicYears(){
    return AcademicsYear::all();
}

///////////////////////////////  Academic Helper functions area ends here ///////////////////////////////

// institute property setting module

function institute_property($attribute_name){
    $instituteId=session()->get('institute');
    $campus_id=session()->get('campus');

    $attribute_name=strtolower($attribute_name);
    return SettingInstProp::where('institution_id',$instituteId)->where('campus_id',$campus_id)->where('attribute_name',$attribute_name)->first();

}


function getInstituteProfile()
{
    return Institute::find(session()->get('institute'));
}

function getCampusProfile()
{
    return Campus::find(session()->get('campus'));
}

// get all campus list with institute id
function getCampusList($instId)
{
    return Campus::where(['institute_id'=>$instId])->orderBy('name', 'ASC')->get();
}

// batch section Student Count
// institute property setting module

function getNumberOfStudentByBatchSection($batch,$section){
    return StudentEnrollment::where('batch',$batch)->where('section',$section)->get();

}

// get batch name by Id
function getBatchName($batchId){
    $batchProfile=\Modules\Academics\Entities\Batch::where('id',$batchId)->first();
    return $batchProfile->batch_name;
}

// get batch name by Id
function getSectionName($sectionId){
    $sectionProfile=\Modules\Academics\Entities\Section::where('id',$sectionId)->first();
    return $sectionProfile->section_name;
}


// get fees fine by invoice id
function  get_fees_day_amount($due_date)
{
    $instituteId=session()->get('institute');
    $campusId=session()->get('campus');

    $date = \Carbon\Carbon::parse($due_date);
    $now = \Carbon\Carbon::now();
    $diff = $date->diffInDays($now) - 1;
    $day_fine_amount = 0;
    // day_fine_count here
    if ($due_date < date('Y-m-d')) {
        $feeSetting = FessSetting::where('ins_id',$instituteId)->where('campus_id',$campusId)->where('setting_type','1')->first();
        if (!empty($feeSetting))
            $day_fine_amount = $feeSetting->value * $diff;
    }
    return $day_fine_amount;

}





function getAttendanceFinePreviousMonth($invoice_id){

    $invoiceProfile=\Modules\Fees\Entities\FeesInvoice::find($invoice_id);
    if(!empty($invoiceProfile)) {
        $payer_id = $invoiceProfile->payer_id;
        $created_date = $invoiceProfile->created_at;
        $date = \Carbon\Carbon::now();

        // session
        $campus = session()->get('campus');
        $institute = session()->get('institute');
        $academic_year = session()->get('academic_year');

        $studentAttendanceFineAmount =\Modules\Student\Entities\StudentAttendanceFine::
        where('academic_year', $academic_year)
            ->where('ins_id', $institute)
            ->where('campus_id', $campus)
            ->where('std_id', $payer_id)
            ->where('is_read', 0)
            ->whereMonth('date', $date->subMonth()->format('m'))
            ->sum('fine_amount');

        if (!empty($studentAttendanceFineAmount)) {
            return $studentAttendanceFineAmount;
        } else {
            return 0;
        }
    }
    else {
        return 0;
    }

}


    // subject grade calculation
     function subjectGradeCalculation($subjectMarksPercentage, $gradeScaleDetails)
    {
        // subject total marks in percentage
        $percentage = (int)$subjectMarksPercentage;
        $maxPoints = floatval(0.00);
        // looping
        foreach ($gradeScaleDetails as $grade){
            // object conversion
            $grade = (object)$grade;

            // max point checking
            if(($grade->points) > $maxPoints){
                $maxPoints = ($maxPoints+($grade->points));
            }
        }
        // looping
        foreach ($gradeScaleDetails as $grade){
            // object conversion
            $grade = (object)$grade;
            // max mark
            $maxMark = floatval($grade->max_per);
            // min mark
            $minMark = floatval($grade->min_per);
            // grade mark (letter grade)
            $gradeMark  = ['grade'=>$grade->name, 'point'=>$grade->points, 'max_point'=>$maxPoints];
            // grade checking
            if($percentage>=$minMark && $percentage<=$maxMark){
                return $gradeMark;
            }

        }
        return  $gradeMark  = ['grade'=>'N/A', 'point'=>'0.0', 'max_point'=>'0.0'];
    }

    // subject grade calculation
     function gradeReverseCalculation($gradePoint, $gradeScaleDetails)
     {
         // subject total marks in percentage
         $gradePoint = floatval($gradePoint);
         $maxPoints = floatval(0.00);
         // looping
         foreach ($gradeScaleDetails as $grade) {
             // max point checking
             if ($grade->points > $maxPoints) {
                 $maxPoints = ($maxPoints + $grade->points);
             }
         }
         // looping
         foreach ($gradeScaleDetails as $grade) {
             // max mark
             $gradeScalePoint = floatval($grade->points);
             // grade mark (letter grade)
             $gradeMark = (object)['grade' => $grade->name, 'point' => $grade->points, 'max_point' => $maxPoints];
             // grade checking
             if ($gradePoint >= $gradeScalePoint) {
                 return $gradeMark;
             }

         }
    }

        // image unlink path

          function UnlinkImage($filepath,$fileName)
        {
            $old_image = $filepath.$fileName;
            if (file_exists($old_image)) {
                @unlink($old_image);
            }
        }


        // accounting all helper fucnton

/**
 * This function returns the ledger or group name with code if present
 */
function recurse($arr, $level = 0)
{
    # we have a numerically-indexed array. go through each item:
    foreach ($arr as $n) {
        # print out the item ID and the item name
        echo '<option value="' . $n['id'] . '">'
            . str_repeat("-", $level)
            . $n['name']
            . '</option>'
            . PHP_EOL;
        # if item['children'] is set, we have a nested data structure, so
        # call recurse on it.
        if (isset($n['children'])) {
            # we have children: RECURSE!!
            recurse($n['children'], $level + 1);
        }
    }
}


function institution_id(){
    return session()->get('institute');
}
function campus_id(){
    return session()->get('campus');
}

function academic_year(){
    return session()->get('academic_year');
}

function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}