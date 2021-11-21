<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\AccountsHelper;

class ChartOfAccountsConfigModel extends Model
{
    use AccountsHelper;
    protected $table = 'accounts_chart_of_acc_code_config';
    protected $fillable = ['code_type','institute_id','campus_id'];

    public function scopeModule($query)
    {
        return $query->where('accounts_chart_of_acc_code_config.campus_id', self::getCampusId())->where('accounts_chart_of_acc_code_config.institute_id', self::getInstituteId());

    }
}
