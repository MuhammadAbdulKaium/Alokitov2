<?php

namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;

class AccVoucherType extends Model
{
    protected $table = "acc_voucher_type";
    protected $fillable = [];

    public function parent() {
        return $this->belongsTo(AccCharts::class, 'acc_charts_id','id')->first();
    }

}
