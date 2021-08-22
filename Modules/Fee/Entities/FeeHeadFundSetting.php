<?php

namespace Modules\Fee\Entities;

use Illuminate\Database\Eloquent\Model;
use Wildside\Userstamps\Userstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
class FeeHeadFundSetting extends Model
{

    use Userstamps;
    use SoftDeletes;

    // Table name
    protected $table = 'fee_headfund_setting';

    // The attribute that should be used for softdelete.
    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'institution_id',
        'campus_id',
        'head_id',
        'fundlist',
        'status',
    ];

    public function feehead(){
        return $this->belongsTo('Modules\Fee\Entities\FeeHead','head_id','id')->first();
    }


}
