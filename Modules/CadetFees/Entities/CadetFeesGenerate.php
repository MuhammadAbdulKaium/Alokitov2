<?php

namespace Modules\CadetFees\Entities;

use Illuminate\Database\Eloquent\Model;

class CadetFeesGenerate extends Model
{
    protected $fillable = ['status'];
    protected $table='cadet_fees_generate';
}
