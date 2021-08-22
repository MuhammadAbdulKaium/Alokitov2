<?php

namespace Modules\Mess\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodMenu extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_mess_food_menus';
    protected $guarded = [];




    public function category()
    {
        return $this->belongsTo(FoodMenuCategory::class, 'category_id', 'id');
    }
}
