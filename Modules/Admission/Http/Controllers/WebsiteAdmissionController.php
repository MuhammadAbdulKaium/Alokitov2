<?php

namespace Modules\Admission\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WebsiteAdmissionController extends Controller
{
   public function examSetting(Request $request){
       return ['status'=>'success','data'=>"boo"];
   }
}
