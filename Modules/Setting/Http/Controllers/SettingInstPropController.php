<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\SettingInstProp;
use Modules\Setting\Entities\Institute;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Setting\Entities\FontFamily;

class SettingInstPropController extends Controller
{

    private  $settingInstProp;
    private  $institute;
    private  $academicHelper;
    private  $fontFamily;


    public function __construct(SettingInstProp $settingInstProp, FontFamily $fontFamily, AcademicHelper $academicHelper,Institute $institute)
    {
        $this->settingInstProp             = $settingInstProp;
        $this->institute                   = $institute;
        $this->academicHelper              = $academicHelper;
        $this->fontFamily                  = $fontFamily;
    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {

        $instituteId=$this->academicHelper->getInstitute();
        $campus_id=$this->academicHelper->getCampus();
        //get all institutes
        $institutes=$this->institute->all();
        //get all institute Property
        $institute_propertys=$this->settingInstProp->get();

        // get font family List
        $fontFmailyList= $this->fontFamily->orderByDesc('id')->get();

        return view('setting::institute_prop.index',compact('institutes','institute_propertys','fontFmailyList'));
    }


    public function store(Request $request)
    {
//        return $request->all();
        $instituteProperty_id=$request->input('property_id');
        if(!empty($instituteProperty_id)) {
            // get institute property Profile
            $instituteProperty = $this->settingInstProp->find($instituteProperty_id);
            $instituteProperty->institution_id = $request->input('institute_name');
            $instituteProperty->campus_id = $request->input('campus_name');
            $instituteProperty->attribute_name = $request->input('attribute_name');
            $instituteProperty->attribute_value = $request->input('attribute_value');
            $instituteProperty->font_family_id = $request->input('font_family_id');
            $update = $instituteProperty->save();
            if ($update) {
                Session::flash('message', 'Success!Institute Property has been Update successfully.');
            }

        } else {

            $instituteProperty = new $this->settingInstProp();
            $instituteProperty->institution_id = $request->input('institute_name');
            $instituteProperty->campus_id = $request->input('campus_name');
            $instituteProperty->attribute_name = $request->input('attribute_name');
            $instituteProperty->attribute_value = $request->input('attribute_value');
            $instituteProperty->font_family_id = $request->input('font_family_id');
            $insert = $instituteProperty->save();

            if ($insert) {
                Session::flash('message', 'Success!Institute Property has been create successfully.');
            }

        }


        return redirect()->back();
    }


    /**
     * Show the specified resource.
     * @return Response
     */
    public function edit($property_id)
    {
        $institutes=$this->institute->all();
        //get all institute Property
        $institute_propertys=$this->settingInstProp->all();
        //single property
          $property=$this->settingInstProp->find($property_id);

        // get font family List
        $fontFmailyList= $this->fontFamily->orderByDesc('id')->get();

        return view('setting::institute_prop.index',compact('institutes','institute_propertys','property','fontFmailyList'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public  function delete($institute_property_id){
        $institute_property_profile=$this->settingInstProp->find($institute_property_id);
        $institute_property_profile->delete();
    }



}
