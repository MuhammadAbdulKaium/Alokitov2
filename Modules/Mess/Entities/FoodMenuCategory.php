<?php

namespace Modules\Mess\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodMenuCategory extends Model
{
    use SoftDeletes;

    protected $table = 'cadet_mess_food_menu_categories';
    protected $guarded = [];



    public function menus()
    {
        return $this->hasMany(FoodMenu::class, 'category_id', 'id');
    }
}
