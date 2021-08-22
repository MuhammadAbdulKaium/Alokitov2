<?php

namespace Modules\Mess\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Inventory\Entities\StockUOM;

class FoodMenuItem extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_mess_food_menu_items';
    protected $guarded = [];



    public function uom()
    {
        return $this->belongsTo(StockUOM::class, 'uom_id', 'id');
    }
}
