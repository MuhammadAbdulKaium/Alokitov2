<?php

namespace Modules\Inventory\Entities;
use Modules\Inventory\Entities\BaseModel;
use App\Helpers\InventoryHelper;

class StockItemSerialModel extends BaseModel
{
	protected $table= 'inventory_item_serial_info';
    protected $fillable = ['item_id', 'serial_from', 'serial_to'];

    use InventoryHelper;

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeModule($query)
    {
        return $query->where('inventory_item_serial_info.campus_id', self::getCampusId())->where('inventory_item_serial_info.institute_id', self::getInstituteId());

    }
    

    public function scopeValid($query)
    {
        return $query->where('inventory_item_serial_info.valid', 1);
    }

}
