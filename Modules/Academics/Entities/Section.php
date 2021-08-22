<?php

namespace Modules\Academics\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    protected $table      = 'section';
    protected $primaryKey = 'id';
    protected $dates      = ['deleted_at'];
    protected $fillable   = [
        'academics_year_id', 'batch_id', 'section_name', 'intake', 'campus', 'institute'
    ];

    public function academicsYear()
    {
        // getting user
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

    /**
     * @return array|mixed
     */
    public function batchName()
    {
        $batchName = $this->belongsTo('Modules\Academics\Entities\Batch', 'batch_id', 'id')->first();
        // var_dump($academicsYear);
        // checking
        if ($batchName) {
            // return user info
            return $batchName;
        } else {
            // return false
            return false;
        }
    }

    /**
     * @return array|mixed
     */
    public function batch()
    {
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'batch_id', 'id')->first();
    }

    public function singleBatch()
    {
        return $this->belongsTo('Modules\Academics\Entities\Batch', 'batch_id', 'id');
    }

    public function section()
    {
        $section = $this->hasOne('Modules\Academics\Entities\Section', 'batch_id', 'id')->first();
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


    public static function getSectionNameById($id)
    {
        $sectionProfile = Section::find($id);
        return $sectionProfile->section_name;
    }

    public function division()
    {
        return $this->belongsTo('Modules\Academics\Entities\Division', 'division_id', 'id');
    }


    public function divisions()
    {
        return $this->belongsToMany('Modules\Academics\Entities\Division', 'division_section', 'section_id', 'division_id');
    }
}
