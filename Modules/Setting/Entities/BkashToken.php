<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;

class BkashToken extends Model
{
    protected $table    = 'bkash_token';
    protected $fillable = [
        'id',
        'token',
        'expair_time',
    ];

}
