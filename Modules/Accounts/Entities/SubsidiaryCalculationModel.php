<?php

namespace Modules\Accounts\Entities;

use App\BaseModel;
use App\Helpers\AccountsHelper;

class SubsidiaryCalculationModel extends BaseModel
{
    use AccountsHelper;
    protected $table= 'accounts_subsidiary_calculation';
    protected $guarded = ['id','campus_id','institute_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid'];

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeModule($query)
    {
        return $query->where('accounts_subsidiary_calculation.campus_id', self::getCampusId())->where('accounts_subsidiary_calculation.institute_id', self::getInstituteId());

    }

    public function scopeApproved($query)
    {
        return $query->where('accounts_subsidiary_calculation.status', 1);
    }
}
