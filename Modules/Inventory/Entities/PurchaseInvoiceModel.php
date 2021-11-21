<?php

namespace Modules\Inventory\Entities;

use App\BaseModel;
use App\Helpers\InventoryHelper;

class PurchaseInvoiceModel extends BaseModel
{
    use InventoryHelper;
    protected $table= 'inventory_purchase_invoice_info';
    protected $guarded = ['id','campus_id','institute_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'];

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeModule($query)
    {
        return $query->where('inventory_purchase_invoice_info.campus_id', self::getCampusId())->where('inventory_purchase_invoice_info.institute_id', self::getInstituteId());
    }
}
