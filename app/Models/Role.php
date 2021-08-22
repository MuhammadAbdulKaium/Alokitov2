<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    protected $table = 'roles';
    protected $fillable = ['name', 'display_name','description', 'status'];

    // role permissions
    public function rolePermissions()
    {
        return $this->belongsToMany('App\Models\Permission');
    }
    // role users
    public function roleUsers()
    {
        return $this->belongsToMany('App\user');
    }
}