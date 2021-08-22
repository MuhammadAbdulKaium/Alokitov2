<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FessSetting extends Model
{
    protected $table = 'fees_setting';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'ins_id',
        'campus_id',
        'attribute',
        'setting_type',
        'value',
    ];

    public function institute()
    {
        return $this->belongsTo('Modules\Setting\Entities\Institute', 'ins_id', 'id')->first();
    }

    public function campus()
    {
        return $this->belongsTo('Modules\Setting\Entities\Campus', 'campus_id', 'id')->first();
    }
}
