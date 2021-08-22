<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    // Table name
    protected $table = 'user_institution_campus';
    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];
    //The attributes that are mass assignable.
    protected $fillable = ['user_id', 'institute_id','campus_id'];


    // find campus details
    public function campus()
    {
        return $this->belongsTo('Modules\Setting\Entities\Campus', 'campus_id', 'id')->first();
    }

    // find campus details
    public function institute()
    {
        return $this->belongsTo('Modules\Setting\Entities\Institute', 'institute_id', 'id')->first();
    }

    // find user details
    public function user()
    {
        // getting user info
        return $this->belongsTo('App\User', 'user_id', 'id')->first();
    }

    public function scopeModule($query, $user_id=0)
    {
        return $query->where(function($q)use($user_id){
            $q->where('user_institution_campus.campus_id', session()->get('campus'))->where('user_institution_campus.institute_id', session()->get('institute'));
            if(!empty($user_id)){
                $q->where('user_institution_campus.user_id', $user_id);
            }
        }); 
    }

}