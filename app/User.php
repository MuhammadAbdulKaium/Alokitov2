<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Carbon\Carbon;
use Modules\Student\Entities\StudentInformation;
use Auth;
use Entrust;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait; // entrust

    //The attributes that are mass assignable.
    protected $fillable = [
        'name', 'email', 'username', 'password',
    ];

    // The attributes that should be hidden for arrays.
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $table='users';




    public function userInfo()
    {
        return $this->hasMany('App\UserInfo', 'user_id', 'id');
    }


    ////////////////////////  User Role-Permission Task //////////////////////////

    // user  roles
    public function roles()
    {
        return $this->belongsToMany('App\Models\Role');
    }

    // user single  role
    public function role()
    {
        return $this->belongsToMany('App\Models\Role')->first();
    }
    public function singleroleUser(){
        return $this->hasOne(RoleUser::class);
    }

    // user permissions
    public function permissions() {
        // find user permission list
        return $this->hasMany('App\Models\UserPermission', 'user_id', 'id');
    }

    // checking user permission
    public function hasPermission($ePermission){
        // permission list
        $permissionList = $this->permissions()->get(['permission_id', 'valid_up_to']);
        // permission list checking
        if($permissionList->count()>0){
            // permission list looping
            foreach ($permissionList as $singlePermission){
                // permission profile
                $permissionProfile = $singlePermission->permission()->first(['name', 'status', 'is_user']);
                //checking permission profile status and user assign
                if($permissionProfile->status==0 || $permissionProfile->is_user==0) continue;
                // checking permission name
                if(($permissionProfile->name==$ePermission) AND (Carbon::parse(Carbon::now())->lt($singlePermission->valid_up_to))) return true;
            }
        }
        // return
        return false;
    }

    //  checking user permission with array list
    public function hasAnyPermission($permissions){
        // checking permission is array or not
        if(is_array($permissions)){
            foreach ($permissions as $permission) {
                // checking user single permission
                if($this->hasPermission($permission)) return true;
            }
        }else{
            // checking user single permission
            if($this->hasPermission($permissions)) return true;
        }
        // return
        return false;
    }

    ////////////////////////  User Role-Permission Task //////////////////////////

    // user student
    public function student()
    {
        return $this->hasMany('Modules\Student\Entities\StudentInformation', 'user_id', 'id')->first();
    }

    // user parent
    public function parent()
    {
        return $this->hasMany('Modules\Student\Entities\StudentGuardian', 'user_id', 'id')->first();
    }

    // user employee
    public function employee()
    {
        return $this->hasOne('Modules\Employee\Entities\EmployeeInformation', 'user_id', 'id')->first();
    }


    // returns single address of the user with address type
    public function singleAddress($type)
    {
        // getting single address
        return $this->hasOne('App\Address', 'user_id', 'id')->where('type', $type)->first();
    }

    // returns all address of the user
    public function allAddress()
    {
        // getting user all addresses from the user db table
        return $this->hasMany('App\Address', 'user_id', 'id')->get();
    }

    // find student profile
    public function findStudent($stdId, $campus, $institute)
    {
        return StudentInformation::where(['id'=>$stdId, 'campus'=>$campus, 'institute'=>$institute])->first();
    }

    public function scopeModule($query)
    {
        return $query->where('users.id', Auth::user()->id); 
        // for Role base access
        /*if(Entrust::hasRole(['super-admin','admin'])){
            return;
        }else{
            return $query->where('users.id', Auth::user()->id);  
        }*/
    }

}
