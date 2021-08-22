<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;

class PhysicalRoomAllocation extends Model
{
    protected $fillable = [];

    
    public function physicalRoomStudents()
    {
        return $this->hasMany('Modules\Academics\Entities\PhysicalRoomStudent', 'allocation_id', 'id');
    }

    public function sections()
    {
        $sectionIds = $this->hasMany('Modules\Academics\Entities\PhysicalRoomStudent', 'allocation_id', 'id')->where('student_information_id', null)->pluck('section_id');

        return Section::with('singleBatch')->whereIn('id', $sectionIds)->get();
    }

    public function studentsNo()
    {
        $studentsNo = $this->hasMany('Modules\Academics\Entities\PhysicalRoomStudent', 'allocation_id', 'id')->where('student_information_id', '!=', null)->count();

        return $studentsNo;
    }

    
    public function user()
    {
        return $this->hasOne('App\User', 'id', 'created_by');
    }
}
