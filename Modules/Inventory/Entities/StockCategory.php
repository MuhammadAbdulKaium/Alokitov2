<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\InventoryHelper;

class StockCategory extends Model
{
    protected $fillable = ['stock_category_name','stock_category_parent_id','has_child'];
    protected $table='cadet_inventory_stock_category';

    use InventoryHelper;

    public function scopeModule($query)
    {
        return $query->where('cadet_inventory_stock_category.campus_id', self::getCampusId())->where('cadet_inventory_stock_category.institute_id', self::getInstituteId());

    }

    
}
