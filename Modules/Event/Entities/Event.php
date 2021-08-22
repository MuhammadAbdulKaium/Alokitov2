<?php

namespace Modules\Event\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use SoftDeletes;
    protected $primaryKey='id';
    protected $table = 'cadet_events';

    protected $fillable = ['event_name','category_id','sub_category_id',
        'activity_id','status','remarks','employee_id','created_by',
        'updated_by','deleted_by','campus','institute'];

    public function category()
    {
        return $this->belongsTo('Modules\Setting\Entities\CadetPerformanceType', 'category_id', 'id');
    }

    public function sub_category()
    {
        return $this->belongsTo('Modules\Setting\Entities\CadetPerformanceCategory', 'sub_category_id', 'id');
    }
    public function activity()
    {
        return $this->belongsTo('Modules\Setting\Entities\CadetPerformanceActivity', 'activity_id', 'id');
    }
    public function emplooyes()
    {
        return $this->hasMany('Modules\Employee\Entities\EmployeeInformation', 'employee_id', 'id');
    }

    public function teams()
    {
        return $this->hasMany('Modules\Event\Entities\EventTeam', 'event_id', 'id');
    }
}
