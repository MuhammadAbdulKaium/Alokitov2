<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserPermission extends Model
{
    use SoftDeletes;
    // table
    protected $table = 'user_permissions';
    // The attribute that should be used for soft delete.
    protected $dates = ['deleted_at'];
    // fillable fields
    protected $fillable = ['user_id', 'permission_id', 'valid_up_to'];

    // find user details
    public function user()
    {
        // getting user info
        return $this->belongsTo('App\User', 'user_id', 'id')->first();
    }

    // find user details
    public function permission()
    {
        // getting user info
        return $this->belongsTo('App\Models\Permission', 'permission_id', 'id');
    }
}
