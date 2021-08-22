<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class LoginScreen extends Model
{
    use SoftDeletes;
    protected $table    = 'setting_login_screen';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        'institution_id',
        'domain_name',
        'login_image'
    ];


    public function institute() {
        return $this->belongsTo('Modules\Setting\Entities\Institute', 'institution_id', 'id')->first();
    }



}
