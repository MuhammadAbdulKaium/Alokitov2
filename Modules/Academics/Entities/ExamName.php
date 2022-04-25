<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\House\Entities\House;
use Modules\House\Entities\RoomStudent;

class ExamName extends Model
{
    // Table name
    protected $table = 'cadet_exam_name';

    protected $fillable = [
        'exam_name',
        'exam_category_id',
        'term_id',
        'campus',
        'institute',
    ];
    protected $casts = [
        'classes' => 'array',
    ];
    private function ordinal($number)
    {
        $ends = array('th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th');
        if ((($number % 100) >= 11) && (($number % 100) <= 13))
            return $number . 'th';
        else
            return $number . $ends[$number % 10];
    }

    public function ExamCategory()
    {
        return $this->belongsTo(ExamCategory::class, 'exam_category_id', 'id');
    }

    public function Term()
    {
        return $this->hasOne(Semester::class, 'id', 'term_id');
    }

    public function houseWisePosition($academicYearId, $semesterId)
    {
        $positions = [];
        $marks = [];
        $houses = House::where([
            'campus_id' => session()->get('campus'),
            'institute_id' => session()->get('institute'),
        ])->get();

        foreach ($houses as $key => $house) {
            $studentIds = RoomStudent::where([
                'campus_id' => session()->get('campus'),
                'institute_id' => session()->get('institute'),
                'house_id' => $house->id
            ])->pluck('student_id');

            $totalMark = ExamMark::where([
                'campus_id' => session()->get('campus'),
                'institute_id' => session()->get('institute'),
                'academic_year_id' => $academicYearId,
                'semester_id' => $semesterId,
                'exam_id' => $this->id,
            ])->whereIn('student_id', $studentIds)->sum('total_mark');

            $marks[$house->id] = $totalMark;
        }

        arsort($marks);
        $i = 1;
        $j = sizeof($marks);

        foreach ($marks as $key => $mark) {
            $positions[$key] = ['position' => $this->ordinal($i++), 'weightage' => $j--];
        }

        return $positions;
    }
}
