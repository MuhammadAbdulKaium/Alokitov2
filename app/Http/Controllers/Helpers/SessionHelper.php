<?php

namespace App\Http\Controllers\Helpers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use  Modules\Academics\Entities\AcademicsYear;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Routing\Route;
use Modules\Setting\Entities\Institute;

class SessionHelper extends Controller
{

    public function __construct(AcademicsYear $academicsYear)
    {
        // statements
    }

    public static function hello()
    {
        return "this is session helper class";
    }

    //['academic_year' =>3,'institute'=>1,'campus'=>1,'grading_scale'=>1]
    public function setSession($sessionInfo)
    {
        // session setting
        session($sessionInfo);
    }


    public static function getInstituteModules(){
        return Institute::find(session()->get('grading_scale'))->instituteModules()->get();
    }

    public static function setMenuHeadActive($urlcategory, $dbcategory)
    {
        if(strtolower($urlcategory) == strtolower($dbcategory)){
            return 'active';
        }else{
            return "";
        }


        // $cpath = "$_SERVER[REQUEST_URI]";

        // if (strpos($cpath, '/') !== false) {
        //     $val = explode('/',$cpath);
        //     if( $path == $val[$key] ){
        //         return 'active';
        //     }else{
        //         return "";
        //     }
        // }
    }

}
