<?php

namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;

class AccVoucherEntry extends Model
{
    protected $table = "acc_tran";
    protected $fillable = [];
    /**
     * @return mixed
     */
    public function parentVoucherType() {
        return $this->belongsTo(AccVoucherType::class, 'acc_voucher_type_id','id')->first();
    }

    /**
     * @return mixed
     */
    public function parentAccCharts() {
        return $this->belongsTo(AccCharts::class, 'acc_charts_id','id')->first();
    }
}
