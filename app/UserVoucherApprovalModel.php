<?php

namespace App;

use Modules\Inventory\Entities\BaseModel;
use App\Helpers\InventoryHelper;

class UserVoucherApprovalModel extends BaseModel
{
    use InventoryHelper;
    protected $table= 'user_voucher_approval_layer';
    protected $guarded = ['id','campus_id','institute_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid'];

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeModule($query)
    {
        return $query->where('user_voucher_approval_layer.campus_id', self::getCampusId())->where('user_voucher_approval_layer.institute_id', self::getInstituteId());

    }
    public function scopeValid($query)
    {
        return $query->where('user_voucher_approval_layer.valid', 1);
    }
}
