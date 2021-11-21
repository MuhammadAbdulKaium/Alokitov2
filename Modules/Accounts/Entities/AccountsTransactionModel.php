<?php

namespace Modules\Accounts\Entities;
use App\BaseModel;
use App\Helpers\AccountsHelper;
class AccountsTransactionModel extends BaseModel
{
    use AccountsHelper;
    protected $table= 'accounts_transaction';
    protected $guarded = ['id','campus_id','institute_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid'];

    public static function boot()
    {
        parent::adminBoot();
    }

    public function details(){
        return $this->hasMany('Modules\Accounts\Entities\SubsidiaryCalculationModel','transaction_id','id');
    }

    public function scopeModule($query)
    {
        return $query->where('accounts_transaction.campus_id', self::getCampusId())->where('accounts_transaction.institute_id', self::getInstituteId());

    }

    public function scopeApproved($query)
    {
        return $query->where('accounts_transaction.status', 1);
    }
}
