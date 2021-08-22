<?php

namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;

class AccBank extends Model
{
    protected $table = "acc_bank";
    protected $fillable = [];

    /**
     * child to parent
     * @return mixed
     */
    public function parent() {
        return $this->belongsTo(AccCharts::class, 'chart_parent','id')->first();
    }

    /**
     * parent to childes
     */
    public function childs(){
        return $this->hasMany(AccCharts::class, 'chart_parent','id');
    }
}
