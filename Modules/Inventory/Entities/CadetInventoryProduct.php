<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\InventoryHelper;

class CadetInventoryProduct extends Model
{

    use InventoryHelper;
    //    protected $fillable = ['product_name'];
    protected $table = 'cadet_stock_products';
    protected $casts = ['store_tagging' => 'array'];

    public function scopeModule($query)
    {
        return $query->where('cadet_stock_products.campus_id', self::getCampusId())->where('cadet_stock_products.institute_id', self::getInstituteId());

    }

    public function stockGroup()
    {
        return $this->belongsTo(StockGroup::class, 'stock_group', 'id');
    }


    public function stockCategory()
    {
        return $this->belongsTo(StockCategory::class, 'category_id', 'id');
    }
}
