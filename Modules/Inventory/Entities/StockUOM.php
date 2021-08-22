<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\InventoryHelper;

class StockUOM extends Model
{
    protected $fillable = ['symbol_name','formal_name'];
    protected $table='cadet_inventory_uom';

    use InventoryHelper;

    public function scopeModule($query)
    {
        return $query->where('cadet_inventory_uom.campus_id', self::getCampusId())->where('cadet_inventory_uom.institute_id', self::getInstituteId());

    }
}
