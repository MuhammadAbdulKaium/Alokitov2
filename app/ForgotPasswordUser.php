<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ForgotPasswordUser extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'forgot_password_user';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'user_id',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id')->first();
    }





}
