<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;

class EntriesItem extends Model
{
    // Table name
    protected $table = 'finance_entries_item';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'entries_id',
        'dc',
        'ledger_id',
        'dr_amount',
        'cr_amount',
    ];


    public function ledger(){
        return $this->belongsTo('Modules\Finance\Entities\Ledger', 'ledger_id', 'id')->first();
    }

}
