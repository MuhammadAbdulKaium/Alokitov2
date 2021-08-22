<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Student\Entities\StudentProfileView;

class PhysicalRoom extends Model
{
    use SoftDeletes;

    protected $guarded = [];



    public function category()
    {
        return $this->belongsTo('Modules\Academics\Entities\PhysicalRoomCategory', 'category_id', 'id');
    }

    public function employee()
    {
        return $this->belongsTo('Modules\Employee\Entities\EmployeeInformation', 'category_id', 'id');
    }

    public function studentsNumber()
    {
        $sections = $this->hasMany('Modules\Academics\Entities\PhysicalRoomStudent', 'physical_room_id', 'id')->get();
    }

    public function allocations()
    {
        return $this->hasMany('Modules\Academics\Entities\PhysicalRoomAllocation', 'physical_room_id', 'id')->latest();
    }

    public function latestAllocation()
    {
        return $this->hasMany('Modules\Academics\Entities\PhysicalRoomAllocation', 'physical_room_id', 'id')->latest()->first();
    }

    public function sections()
    {
        $allocation = $this->hasMany('Modules\Academics\Entities\PhysicalRoomAllocation', 'physical_room_id', 'id')->latest()->first();

        if ($allocation) {
            $physicalRoomStudents = PhysicalRoomStudent::where([
                'allocation_id' => $allocation->id,
                'student_information_id' => null
            ])->pluck('section_id');

            return Section::whereIn('id', $physicalRoomStudents)->get();
        }
    }

    public function students()
    {
        $allocation = $this->hasMany('Modules\Academics\Entities\PhysicalRoomAllocation', 'physical_room_id', 'id')->latest()->first();

        if ($allocation) {
            $physicalRoomStudents = PhysicalRoomStudent::where([
                ['allocation_id', $allocation->id],
                ['student_information_id', '!=', null]
            ])->pluck('student_information_id');

            return StudentProfileView::whereIn('std_id', $physicalRoomStudents)->get();
        }
    }

    public function prefectStudents()
    {
        $allocation = $this->hasMany('Modules\Academics\Entities\PhysicalRoomAllocation', 'physical_room_id', 'id')->latest()->first();

        if ($allocation) {
            $physicalRoomStudents = PhysicalRoomStudent::where([
                ['allocation_id', $allocation->id],
                ['student_information_id', '!=', null],
                ['prefect', '!=', null]
            ])->pluck('student_information_id');

            return StudentProfileView::whereIn('std_id', $physicalRoomStudents)->get();
        }
    }



    public function employees()
    {
        return $this->belongsToMany('Modules\Employee\Entities\EmployeeInformation', 'employee_room', 'room_id', 'employee_id');
    }



    public function activities()
    {
        return $this->hasMany('Modules\Student\Entities\StudentActivityDirectoryActivity', 'room_id', 'id');
    }
}
