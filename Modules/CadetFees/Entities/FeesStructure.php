<?php

namespace Modules\CadetFees\Entities;

use Illuminate\Database\Eloquent\Model;

class FeesStructure extends Model
{
    protected $fillable = ['structure_name'];
    protected $table = 'fees_structure';
}
