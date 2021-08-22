<?php

namespace Modules\Accounting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AccFeesCollection extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'acc_fees_collection';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];
}
