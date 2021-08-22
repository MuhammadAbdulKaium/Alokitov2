<?php

namespace Modules\Library\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    // Table name
    protected $table = 'book';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'name',
        'book_category_id',
        'book_type',
        'subtitle',
        'isbn_no',
        'author',
        'book_shelf_id',
        'cup_board_shelf_id',
        'edition',
        'publisher',
        'book_cost',
        'book_vendor_id',
        'copy',
        'remark'
    ];


    public function category(){
        return $this->hasOne('Modules\Library\Entities\BookCategory','id','book_category_id')->first();
    }

    public function bookShelf(){
        return $this->hasOne('Modules\Library\Entities\BookShelf','id','book_shelf_id')->first();
    }

    public function bookcup_board_shelf(){
        return $this->hasOne('Modules\Library\Entities\CupBoardShelf','id','cup_board_shelf_id')->first();
    }

    public function book_vendor(){
        return $this->hasOne('Modules\Library\Entities\BookVendor','id','book_vendor_id')->first();
    }

    public function bookStock(){
        return $this->hasMany('Modules\Library\Entities\BookStock','book_id','id')->get();
    }

    public function bookStockIssue(){
        return $this->hasMany('Modules\Library\Entities\BookStock','book_id','id')->where('book_status',2)->get();
    }

}
