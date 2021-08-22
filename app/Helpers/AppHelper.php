<?php
namespace App\Helpers;

use Modules\CadetFees\Entities\CadetFeesAssign;
use Modules\Setting\Entities\CadetPerformanceActivityPoint;

class AppHelper{

    public static function test($data){
        return $data;
    }

    public static function CadetActivityPoint($activity_id)
    {
        if($activity_id != null || $activity_id > 0)
        {
            $data = CadetPerformanceActivityPoint::where('cadet_performance_activity', $activity_id)->get();
            if($data != null)
            {
                return $data;
            }
        }
    }
    public static function GetFeesAssignAssignCadet($std_id)
    {
        $data = CadetFeesAssign::where('std_id',$std_id)->first();
        if($data != null)
        {
            return $data;
        }
    }

}