<?php

namespace Modules\Academics\Entities;

use Modules\Fees\Entities\StudentPyamentView;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{

    use SoftDeletes;

    protected $table = 'batch';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'academics_year_id', 'academics_level_id', 'division_id', 'batch_name', 'batch_alias', 'start_date', 'end_date', 'campus', 'institute'
    ];

    //    public function academicsYear()
    //    {
    //        // getting user
    //        $academicsYear = $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academics_year_id', 'id')->first();
    //
    //         var_dump(($academicsYear));
    //        // checking
    //        if ($academicsYear) {
    //            // return user info
    //            return $academicsYear;
    //        } else {
    //            // return false
    //            return false;
    //        }
    //    }
    public function academicsYear()
    {
        $academicsYear = $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academics_year_id', 'id')->first();
        // var_dump($academicsYear);
        // checking
        if ($academicsYear) {
            // return user info
            return $academicsYear;
        } else {
            // return false
            return false;
        }
    }
    public function division()
    {
        // getting user
        return $this->belongsTo('Modules\Academics\Entities\Division', 'division_id', 'id')->first();
    }

    /**
     * @return array
     */
    public function academicsLevel()
    {
        $academicsLevel = $this->belongsTo('Modules\Academics\Entities\AcademicsLevel', 'academics_level_id', 'id')->first();
        // var_dump($academicsYear);
        // checking
        if ($academicsLevel) {
            // return user info
            return $academicsLevel;
        } else {
            // return false
            return false;
        }
    }

    public function singleLevel()
    {
        return $this->belongsTo('Modules\Academics\Entities\AcademicsLevel', 'academics_level_id', 'id');
    }

    public function academicsLevelAll()
    {
        // getting user info
        $academicsLevelAll = $this->belongsTo('Modules\Academics\Entities\AcademicsYear', 'academics_year_id', 'id')->get();
        // checking
        if ($academicsLevelAll) {
            // return user info
            return $academicsLevelAll;
        } else {
            // return false
            return false;
        }
    }

    public function section()
    {
        $section = $this->hasMany('Modules\Academics\Entities\Section', 'batch_id', 'id')->get();
        // var_dump($academicsYear);
        // checking
        if ($section) {
            // return user info
            return $section;
        } else {
            // return false
            return false;
        }
    }

    public function get_division()
    {
        return $this->hasOne('Modules\Academics\Entities\Division', 'id', 'division_id')->first();
    }


    public function payableAmount()
    {
        return StudentPyamentView::where(['batch' => $this->id])->get()->sum('payable_amount');
    }

    public function paidAmount()
    {
        return StudentPyamentView::where(['batch' => $this->id])->get()->sum('payed_amount');
    }

    public function discountAmount()
    {
        return StudentPyamentView::where(['batch' => $this->id])->get()->sum('discount');
    }



    public static function getBatchNameById($id)
    {
        $batchProfile = Batch::find($id);
        return $batchProfile->batch_name;
    }

    public function divisions()
    {
        return $this->belongsToMany('Modules\Academics\Entities\Division', 'batch_division', 'batch_id', 'division_id');
    }



    public function grade()
    {
        return $this->belongsToMany(Grade::class, 'academics_class_grade_scales', 'batch_id', 'scale_id');
    }
}
