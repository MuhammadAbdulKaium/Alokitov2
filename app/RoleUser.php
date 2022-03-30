<?php

namespace App;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class RoleUser extends Model
{
    public $timestamps =false;
    protected $table = 'role_user';
    protected $fillable = ['user_id', 'role_id'];


    // find user details
    public function user()
    {
        // getting user info
        return $this->belongsTo('App\User', 'user_id', 'id')->first();
    }
    public function singleRole(){
        return $this->hasOne(Role::class,'id','role_id');
    }
}
