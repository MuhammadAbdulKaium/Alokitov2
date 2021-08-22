<?php

namespace Modules\Fees\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeesBatchSection extends Model
{

    use SoftDeletes;

    // Table name
    protected $table = 'fees_class_section';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'fees_id',
        'batch_id',
        'section_id',
        'status',
        'adjust'
    ];

    public function fees(){
        return $this->belongsTo('Modules\Fees\Entities\Fees','fees_id','id')->first();
    }

    public function batch(){
        return $this->belongsTo('Modules\Academics\Entities\Batch','batch_id','id')->first();
    }

    public function section(){
        return $this->belongsTo('Modules\Academics\Entities\Section','section_id','id')->first();
    }




}
