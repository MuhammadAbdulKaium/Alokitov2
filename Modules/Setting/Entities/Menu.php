<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use SoftDeletes;
    protected $table    = 'setting_menus';
    protected $dates = ['deleted_at'];

    protected $fillable = ['name','module_id', 'sub_module_id', 'route', 'icon', 'status'];

    // find module profile using this module id
    public function menuModule()
    {
        return $this->belongsTo('Modules\Setting\Entities\Module', 'module_id', 'id')->first();
    }

    // find menu permission
    public function menuPermissions() {
        return $this->belongsToMany('App\Models\Permission', 'setting_menu_permission', 'menu_id', 'permission_id');
    }

    // checking menu permission
    public function checkPermission($permissionId){
        if($this->menuPermissions()->where('permission_id', $permissionId)->first()){
            return true;
        }
        return false;
    }
}
