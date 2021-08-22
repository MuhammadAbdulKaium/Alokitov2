<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CadetPerformanceType extends Model
{
    use SoftDeletes;
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $table = 'cadet_performance_type';
    protected $fillable = ['category_type'];

    public function events()
    {
        return $this->hasMany('Modules\Event\Entities\Event');
    }
}
