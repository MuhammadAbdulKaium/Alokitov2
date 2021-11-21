<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\AccountsHelper;

class AccountsConfigurationModel extends Model
{
    use AccountsHelper;
    protected $table = 'accounts_configuration';
    protected $fillable = [];

    public function scopeModule($query)
    {
        return $query->where('accounts_configuration.campus_id', self::getCampusId())->where('accounts_configuration.institute_id', self::getInstituteId());

    }
}
