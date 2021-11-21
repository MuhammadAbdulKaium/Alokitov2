<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\InventoryHelper;

class AccountsVoucherConfigModel extends Model
{
    use InventoryHelper;
    protected $fillable = [];
    protected $table='accounts_voucher_config';

    public function scopeModule($query)
    {
        return $query->where('accounts_voucher_config.campus_id', self::getCampusId())->where('accounts_voucher_config.institute_id', self::getInstituteId());

    }
}
