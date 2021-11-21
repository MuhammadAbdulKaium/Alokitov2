<?php

namespace Modules\Accounts\Entities;

use App\BaseModel;
use App\Helpers\AccountsHelper;

class AccountsTransactionDetailsModel extends BaseModel
{
    use AccountsHelper;
    protected $table= 'accounts_transaction_details';
    protected $guarded = ['id','campus_id','institute_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid'];

    public static function boot()
    {
        parent::adminBoot();
    }

    public function transaction(){
        return $this->belongsTo('Modules\Accounts\Entities\AccountsTransactionModel','acc_transaction_id');
    }

    public function subsidiaryCalculations(){
        return $this->hasMany('Modules\Accounts\Entities\SubsidiaryCalculationModel', 'tran_details_id', 'id',);
    }

    public function scopeModule($query)
    {
        return $query->where('accounts_transaction_details.campus_id', self::getCampusId())->where('accounts_transaction_details.institute_id', self::getInstituteId());

    }

    public function scopeApproved($query)
    {
        return $query->where('accounts_transaction_details.status', 1);
    }
}
