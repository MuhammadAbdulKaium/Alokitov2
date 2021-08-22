<?php

namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Accounting\Entities\AccCharts;

class AccSubLedger extends Model
{
    protected $table = "acc_subledger";
    protected $fillable = [];

    /**
     * child to parent
     * @return mixed
     */
    public function parent() {
        return $this->belongsTo(AccCharts::class, 'chart_parent','id')->first();
    }
}
