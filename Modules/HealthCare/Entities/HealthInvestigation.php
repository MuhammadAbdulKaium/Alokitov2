<?php

namespace Modules\HealthCare\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HealthInvestigation extends Model
{
    use SoftDeletes;
    protected $table = 'cadet_health_investigations';
    protected $guarded = [];



    public function investigationReports()
    {
        return $this->hasMany(HealthInvestigationReport::class, 'investigation_id', 'id');
    }
}
