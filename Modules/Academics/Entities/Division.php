<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Http\Controllers\Helpers\AcademicHelper;

class division extends Model
{
    use SoftDeletes;

    protected $table = 'academics_division';
    protected $dates = ['deleted_at'];

    protected $fillable = ['name', 'academic_year', 'campus', 'institute'];

    // public function batches()
    // {
    //     return $this->hasMany('Modules\Academics\Entities\Batch', 'division_id', 'id')->where([
    //         // 'academics_year_id'=>session()->get('academic_year'),
    //         'campus'=> session()->get('campus'),
    //         'institute'=> session()->get('institute')
    //     ])->get();
    // }

    public function subjects()
    {
        return $this->hasMany('Modules\Academics\Entities\Subject', 'division_id', 'id');
    }

    // public function sections()
    // {
    //     return $this->hasMany('Modules\Academics\Entities\Section', 'division_id', 'id');
    // }

    public function batches()
    {
        return $this->belongsToMany('Modules\Academics\Entities\Batch', 'batch_division', 'division_id', 'batch_id');
    }

    public function sections()
    {
        return $this->belongsToMany('Modules\Academics\Entities\Batch', 'division_section', 'division_id', 'ssection_id');
    }

}
