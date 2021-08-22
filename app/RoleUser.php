<?php

namespace App;

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
}
