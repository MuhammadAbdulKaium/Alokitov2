<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\InventoryHelper;

class InventoryStore extends Model
{
    protected $fillable = ['store_name','store_address_1','store_address_2','store_phone','store_city'];
    protected $table='cadet_inventory_store';

    use InventoryHelper;

    public function storeCategory()
    {
        return $this->hasMany(InventoryStoreCategory::class,'id','category_id');
    }

    public function scopeModule($query)
    {
        return $query->where('cadet_inventory_store.campus_id', self::getCampusId())->where('cadet_inventory_store.institute_id', self::getInstituteId());

    }

    public function scopeAccess($query, $that)
    {
        return $query->where(function($q)use($that){
            $q->where('cadet_inventory_store.campus_id', $that->campus_id)->where('cadet_inventory_store.institute_id', $that->institute_id);
            if(count($that->AccessStore)>0){
                $q->whereIn('cadet_inventory_store.id', $that->AccessStore);
            }
        });

    }
}
