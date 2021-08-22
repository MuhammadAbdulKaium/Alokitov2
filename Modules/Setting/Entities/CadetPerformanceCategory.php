<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Academics\Entities\Semester;
use Modules\Event\Entities\EventMark;
use Modules\House\Entities\House;
use Modules\House\Entities\RoomStudent;
use Modules\Student\Entities\CadetAssesment;

class CadetPerformanceCategory extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $table = 'cadet_performance_category';
    protected $fillable = ['category_name', 'category_type_id', 'is_mandatory', 'flag'];
    
    public function PerformanceType()
    {
        $type = $this->belongsTo('Modules\Setting\Entities\CadetPerformanceType', 'category_type_id', 'id')->first();
        if ($type) {
            return $type;
        } else {
            return false;
        }
    }

    private function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13))
            return $number. 'th';
        else
            return $number. $ends[$number % 10];
    }

    public function houseWisePosition($academicYearId, $semesterId){
        $positions = [];
        $marks = [];
        $semester = Semester::findOrFail($semesterId);
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

            $totalMark = EventMark::where([
                'campus_id' => session()->get('campus'),
                'institute_id' => session()->get('institute'),
                'performance_category_id' => $this->id,
            ])
            ->whereDate('date_time', '>=', $semester->start_date)
            ->whereDate('date_time', '<=', $semester->end_date)
            ->whereNotNull('mark')->whereIn('student_id', $studentIds)->sum('mark');

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
    
    public function event()
    {
        return $this->hasMany('Modules\Event\Entities\Event');
    }
}
