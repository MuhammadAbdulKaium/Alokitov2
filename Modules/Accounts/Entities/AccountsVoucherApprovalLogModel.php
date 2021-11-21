<?php

namespace Modules\Accounts\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\AccountsHelper;

class AccountsVoucherApprovalLogModel extends Model
{
    use AccountsHelper;
    protected $table= 'accounts_voucher_approval_log';
    protected $guarded = ['id'];
    public $timestamps = false;

    public function scopeModule($query)
    {
        return $query->where('accounts_voucher_approval_log.campus_id', self::getCampusId())->where('accounts_voucher_approval_log.institute_id', self::getInstituteId());

    }
    
}
