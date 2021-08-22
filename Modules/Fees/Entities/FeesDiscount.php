<?php

namespace Modules\Fees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeesDiscount extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'fees_discount';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'fees_id',
        'discount_name',
        'discount_percent',
        'status'
    ];
}
