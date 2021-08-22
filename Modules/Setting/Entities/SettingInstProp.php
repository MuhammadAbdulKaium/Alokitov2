<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class SettingInstProp extends Model
{
    use SoftDeletes;
    protected $table    = 'setting_inst_prop';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'attribute_name',
        'attribute_value',
        'font_family_id'
    ];

    public function institute() {
        return $this->belongsTo('Modules\Setting\Entities\Institute', 'institution_id', 'id')->first();
    }

    public function campus() {
        return $this->belongsTo('Modules\Setting\Entities\Campus', 'campus_id', 'id')->first();
    }

    public function font_family() {
        return $this->belongsTo('Modules\Setting\Entities\FontFamily', 'font_family_id', 'id')->first();
    }
}
