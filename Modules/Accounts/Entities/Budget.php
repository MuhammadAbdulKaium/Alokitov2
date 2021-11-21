<?php

namespace Modules\Accounts\Entities;

use App\BaseModel;
use App\Helpers\AccountsHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Budget extends BaseModel
{
    use AccountsHelper;

    protected $table = 'accounts_budget';
    protected $guarded = [];

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeModule($query)
    {
        return $query->where('accounts_budget.campus_id', self::getCampusId())->where('accounts_budget.institute_id', self::getInstituteId());

    }



    public function account()
    {
        return $this->belongsTo('Modules\Accounts\Entities\ChartOfAccount', 'account_id', 'id');
    }

    public function details()
    {
        return $this->hasMany('Modules\Accounts\Entities\BudgetDetails', 'budget_summary_id', 'id');
    }
}
