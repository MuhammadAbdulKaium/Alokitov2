<?php

namespace Modules\Inventory\Entities;

use Illuminate\Database\Eloquent\Model;

class InventoryStoreCategory extends Model
{
    protected $fillable = ['store_category_name'];
    protected $table = 'inventory_store_category';
}
