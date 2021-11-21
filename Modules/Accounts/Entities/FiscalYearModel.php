<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\AccountsHelper;

class FiscalYearModel extends Model
{
    use AccountsHelper;
    protected $table='accounts_fiscal_year';
    protected $fillable = [];

    public function scopeModule($query)
    {
        return $query->where('accounts_fiscal_year.campus_id', self::getCampusId())->where('accounts_fiscal_year.institute_id', self::getInstituteId());

    }
}
