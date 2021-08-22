<?php

namespace Modules\RoleManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table='users';
    protected $fillable = ['name','email','role_id','username','password','status'];


}
