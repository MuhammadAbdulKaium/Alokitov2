<?php

namespace Modules\Finance\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Wildside\Userstamps\Userstamps;
use Modules\Finance\Entities\FinancialAccount;

class Group extends Model
{
    use Userstamps;
//    use SoftDeletes;

    // Table name
    protected $table = 'finance_group';

    // The attribute that should be used for softdelete.
//    protected $dates = ['deleted_at'];

    // The attributes that should be guarded for arrays.
    // protected $guarded = [];

    // The attributes that should be hidden for arrays.
    // protected $hidden = [];

    //The attributes that are mass assignable.
    protected $fillable = [
        'id',
        'name',
        'parent_id',
    ];


    public function parent()
    {
        return $this->belongsTo('Modules\Finance\Entities\Group','parent_id')->where('parent_id',NULL);
    }

    public function children()
    {
        return $this->hasMany('Modules\Finance\Entities\Group','parent_id');
    }




    // asset gorup id
    public function getGroupId($code){
        // account obje
        $accountObj= new FinancialAccount;
        $gorupProfile=Group::where('code',$code)->where('account_id',$accountObj->getActiveAccount())->first();
        return  $gorupProfile->id;
    }





    public function generateSelectTree($datas, $parent = 0, $limit=0){
        if($limit > 1000) return ''; // Make sure not to have an endless recursion
//        $tree = '<ul>';
        $tree='';
        for($i=0, $ni=count($datas); $i < $ni; $i++){
            if($datas[$i]['parent_id'] == $parent){
                $tree .= '<option value="' . $datas[$i]['id'] .'">';
                $tree .= str_repeat("&nbsp;", $parent);
                $tree .= $datas[$i]['name'];
                $tree .= '</option>';
                $tree .= $this->generateSelectTree($datas, $datas[$i]['id'], $limit++);
            }
        }
//        $tree .= '</ul>';
        return $tree;
    }



}
