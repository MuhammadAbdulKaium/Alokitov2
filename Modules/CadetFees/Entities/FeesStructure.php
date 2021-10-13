<?php

namespace Modules\CadetFees\Entities;

use Illuminate\Database\Eloquent\Model;

class FeesStructure extends Model
{
    protected $fillable = ['structure_name','total_fees'];
    protected $table = 'fees_structure';
}
