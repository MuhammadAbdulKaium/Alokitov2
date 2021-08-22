<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class BkashTransaction extends Model
{
    protected $table="bkash_transaction";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'marcent_invoice_num',
        'payment_id_num',
        'transaction_status',
        'transaction_id',
        'payment_id',
    ];

}
