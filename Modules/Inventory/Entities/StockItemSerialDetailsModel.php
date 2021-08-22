<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\InventoryHelper;

class StockItemSerialDetailsModel extends Model
{
	protected $table= 'inventory_item_serial_details';
    protected $guarded = ['id'];
    use InventoryHelper;

    public function scopeModule($query)
    {
        return $query->where('inventory_item_serial_details.campus_id', self::getCampusId())->where('inventory_item_serial_details.institute_id', self::getInstituteId());

    }
    public function scopeValid($query)
    {
        return $query->where('inventory_item_serial_details.valid', 1);
    }

}
