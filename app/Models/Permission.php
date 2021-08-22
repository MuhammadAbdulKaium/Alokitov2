<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Zizaco\Entrust\EntrustPermission;
use Modules\Setting\Entities\Module;

class Permission extends EntrustPermission
{
    protected $table = 'permissions';
    protected $fillable = ['name','module_id','sub_module_id', 'display_name','description','status'];

    // permission sub module
    public function permissionModule()
    {
        return $this->belongsTo('Modules\Setting\Entities\Module', 'module_id', 'id')->first();
    }

    // permission roles
    public function permissionRoles()
    {
        return $this->belongsToMany('App\Models\Role');
    }

    // checking permission role
    public function checkRole($roleId){
        if($this->permissionRoles()->where('role_id', $roleId)->first()){
            return true;
        }
        return false;
    }
}