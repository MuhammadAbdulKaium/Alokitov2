<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use SoftDeletes;
    protected $table    = 'setting_modules';
    protected $dates = ['deleted_at'];
    protected $fillable = ['name', 'parent_id','icon', 'route', 'status'];

    // find module for sub-module use only
    public function module()
    {
        return $this->belongsTo('Modules\Setting\Entities\Module','parent_id','id')->first();
    }
    // find sub module for module use only
    public function subModules($status)
    {
        // checking status
        if($status=='all'){
            return $this->hasMany('Modules\Setting\Entities\Module', 'parent_id', 'id')->orderBy('name', 'ASC')->get();
        }else{
            return $this->hasMany('Modules\Setting\Entities\Module', 'parent_id', 'id')->where('status', $status)->orderBy('name', 'ASC')->get();
        }
    }
    // find munu for sub module use only
    public function menus()
    {
        return $this->hasMany('Modules\Setting\Entities\Menu', 'sub_module_id', 'id')->where('status', 1)->orderBy('name', 'ASC')->get();
    }





    // get module institute list
    public function moduleInstitutes() {
        return $this->belongsToMany('Modules\Setting\Entities\Institute', 'setting_institute_modules', 'module_id', 'institute_id');
    }
    // checking module institute
    public function checkInstitute($instituteId){
        if($this->moduleInstitutes()->where('institute_id', $instituteId)->first()){
            return true;
        }
        return false;
    }
}