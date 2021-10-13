<?php

namespace Modules\CadetFees\Entities;

use Illuminate\Database\Eloquent\Model;

class FeesStructureDetails extends Model
{
    protected $fillable = ['head_amount'];
    protected $table = 'fees_structure_details';
}
