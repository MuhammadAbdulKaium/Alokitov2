<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\InventoryHelper;

class CadetInventoryProduct extends Model
{
//    protected $fillable = ['product_name'];
    protected $table= 'cadet_stock_products';
    protected $casts =['store_tagging' => 'array' ];
    use InventoryHelper;

    public function scopeModule($query)
    {
        return $query->where('cadet_stock_products.campus_id', self::getCampusId())->where('cadet_stock_products.institute_id', self::getInstituteId());

    }
}
