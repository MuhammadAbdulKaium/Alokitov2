<?php

namespace Modules\Mess\Entities;

use Illuminate\Database\Eloquent\Model;

class MessTableSeat extends Model
{
    protected $table = 'cadet_mess_table_seats';
    protected $guarded = [];




    public function table()
    {
        return $this->belongsTo(MessTable::class, 'mess_table_id', 'id');
    }
}
