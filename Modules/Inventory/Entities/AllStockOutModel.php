<?php

namespace Modules\Inventory\Entities;

use Modules\Inventory\Entities\BaseModel;
use App\Helpers\InventoryHelper;


class AllStockOutModel extends BaseModel
{
    use InventoryHelper;
    protected $table= 'inventory_all_stock_item_out';
    protected $guarded = ['id','campus_id','institute_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid'];

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeModule($query)
    {
        return $query->where('inventory_all_stock_item_out.campus_id', self::getCampusId())->where('inventory_all_stock_item_out.institute_id', self::getInstituteId());

    }
    public function scopeValid($query)
    {
        return $query->where('inventory_all_stock_item_out.valid', 1);
    }
}
