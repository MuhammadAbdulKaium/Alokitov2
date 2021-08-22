<?php

namespace Modules\RoleManagement\Entities;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'roles';
    protected $fillable = ['name', 'display_name','description', 'status'];
}
