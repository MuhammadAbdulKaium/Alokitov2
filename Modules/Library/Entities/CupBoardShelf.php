<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CupBoardShelf extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'cup_board_shelf';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'book_shelf_id',
        'capacity',
        'details',
    ];

    public function bookShelf(){
        return $this->belongsTo('Modules\Library\Entities\BookShelf','book_shelf_id','id')->first();
    }

}
