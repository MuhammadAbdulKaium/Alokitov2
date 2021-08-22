<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class InstituteLanguage extends Model
{
    use SoftDeletes;
    protected $table    = 'institute_language';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'institute_id',
        'language_id'
    ];



    public function language(){
        return $this->belongsTo('Modules\Setting\Entities\Language','language_id','id')->first();
    }

}
