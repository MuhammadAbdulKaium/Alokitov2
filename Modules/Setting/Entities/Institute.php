<?php

namespace Modules\Setting\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wildside\Userstamps\Userstamps;

class Institute extends Model
{
    use Userstamps;
    use SoftDeletes;

    protected $table = 'setting_institute';
    protected $primaryKey = 'id';
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'institute_name','bn_institute_name',' institute_alias','address1','address2','bn_address','phone','email','website','logo', 'eiin_code', 'center_code','institute_code','upazila_code','zilla_code','subdomain'
    ];

    // get institute modules list
    public function instituteModules() {
        return $this->belongsToMany('Modules\Setting\Entities\Module', 'setting_institute_modules', 'institute_id', 'module_id')->orderBy('name', 'ASC');
    }

    // campus list
    public function campus()
    {
        return $this->hasMany('Modules\Setting\Entities\Campus', 'institute_id', 'id')->orderBy('name', 'ASC')->get();
    }

    // student list
    public function student()
    {
        return $this->hasMany('Modules\Student\Entities\StudentInformation', 'institute', 'id')->get();
    }

    public function staff()
    {
        return $this->hasMany('Modules\Employee\Entities\EmployeeInformation', 'institute_id', 'id')->get();
    }
}
