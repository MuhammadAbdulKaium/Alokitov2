<?php

namespace Modules\Accounts\Entities;

use App\BaseModel;
use App\Helpers\AccountsHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BudgetDetails extends BaseModel
{
    use AccountsHelper;

    protected $table = 'accounts_budget_details';
    protected $guarded = [];

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeModule($query)
    {
        return $query->where('accounts_budget_details.campus_id', self::getCampusId())->where('accounts_budget_details.institute_id', self::getInstituteId());

    }
}
