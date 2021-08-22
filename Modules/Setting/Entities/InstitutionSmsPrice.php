<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class InstitutionSmsPrice extends Model
{
    use SoftDeletes;
    protected $table    = 'institute_sms_price';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'institution_id',
        'sms_price',
    ];


    public function institute() {
        return $this->belongsTo('Modules\Setting\Entities\Institute', 'institution_id', 'id')->first();
    }


}

