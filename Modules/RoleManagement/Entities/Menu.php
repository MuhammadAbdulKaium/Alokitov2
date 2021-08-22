<?php

namespace Modules\RoleManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table='setting_menus';
    protected $fillable = ['menu_name','icon','route','status','menu_type','comment'];
}
