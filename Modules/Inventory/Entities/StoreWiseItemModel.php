<?php

namespace Modules\Inventory\Entities;

use Modules\Inventory\Entities\BaseModel;
use App\Helpers\InventoryHelper;

class StoreWiseItemModel extends BaseModel
{
    use InventoryHelper;
    protected $table= 'inventory_store_wise_item';
    protected $guarded = ['id','campus_id','institute_id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by', 'valid'];

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeModule($query)
    {
        return $query->where('inventory_store_wise_item.campus_id', self::getCampusId())->where('inventory_store_wise_item.institute_id', self::getInstituteId());

    }
    public function scopeValid($query)
    {
        return $query->where('inventory_store_wise_item.valid', 1);
    }

    public function scopeAccess($query, $that)
    {
        return $query->where(function ($q) use ($that) {
        	$q->where('inventory_store_wise_item.campus_id', $that->campus_id)->where('inventory_store_wise_item.institute_id', $that->institute_id);
        	if(count($that->AccessStore)>0){
        		$q->whereIn('inventory_store_wise_item.store_id', $that->AccessStore);
        	}
        });
    }


    
}
