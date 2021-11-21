<?php

namespace Modules\Inventory\Entities;

use App\DeCentralizeBaseModel;
use App\Helpers\InventoryHelper;

class CustomerModel extends DeCentralizeBaseModel
{

    use InventoryHelper;
    protected $table= 'inventory_customer_info';
    protected $guarded = ['id', 'created_at', 'created_by', 'updated_at', 'updated_by', 'deleted_at', 'deleted_by'];

    public static function boot()
    {
        parent::adminBoot();
    }

    public function scopeModule($query)
    {
        return $query->where('inventory_customer_info.campus_id', self::getCampusId())->where('inventory_customer_info.institute_id', self::getInstituteId());

    }

    
}
