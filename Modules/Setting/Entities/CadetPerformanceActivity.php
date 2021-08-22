<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CadetPerformanceActivity extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $table = 'cadet_performance_activity';
    protected $fillable = ['cadet_category_id', 'activity_name'];

    public function selectCategory()
    {
        $category = $this->belongsTo('Modules\Setting\Entities\CadetPerformanceCategory', 'cadet_category_id', 'id')->first();
        // checking
        if ($category) {
            // return institute info
            return $category;
        } else {
            // return false
            return false;
        }
    }
    public function getValue(){
        return $this->hasMany(CadetPerformanceActivityPoint::class);
    }

    public function activityPoint()
    {
        // getting student attachment from student attachment db table
        $point = $this->hasMany('Modules\Setting\Entities\CadetPerformanceActivityPoint', 'cadet_performance_activity', 'id');
        if ($point) {
            // return institute info
            return $point;
        } else {
            // return false
            return false;
        }
    }

    public function event()
    {
        return $this->hasMany('Modules\Event\Entities\Event');
    }


}
