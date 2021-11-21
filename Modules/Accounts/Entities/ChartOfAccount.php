<?php

namespace Modules\Accounts\Entities;

use App\DeCentralizeBaseModel;
use App\Helpers\AccountsHelper;

class ChartOfAccount extends DeCentralizeBaseModel
{
    use AccountsHelper;

    protected $table = 'accounts_chart_of_accounts';
    protected $guarded = [];

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeModule($query)
    {
        return $query->where('accounts_chart_of_accounts.campus_id', self::getCampusId())->where('accounts_chart_of_accounts.institute_id', self::getInstituteId());

    }
}
