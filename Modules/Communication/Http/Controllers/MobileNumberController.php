<?php

namespace Modules\Communication\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Communication\Entities\MobileNumber;
use App\Http\Controllers\Helpers\AcademicHelper;
use Modules\Communication\Entities\Group;

class MobileNumberController extends Controller
{


    private $mobileNumber;
    private $academicHelper;
    private $group;

    public function  __construct(MobileNumber $mobileNumber, Group $group, AcademicHelper $academicHelper)
    {

        $this->group= $group;
        $this->mobileNumber= $mobileNumber;
        $this->academicHelper= $academicHelper;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function addGroupModal()
    {
        return view('communication::pages.modal.sms.group_number');
    }



    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function storeMobileNumberGroup(Request $request)
    {
       $group_name=$request->input('group_name');
       $phoneNumber=$request->input('phone_number');

       // get institution and campus id

        $institution_id= $this->academicHelper->getInstitute();
        $campus_id= $this->academicHelper->getCampus();

        // save group name
             $groupObj= $this->group;
            $groupObj->institution_id=$institution_id;
            $groupObj->group_name= $group_name;
            $groupObj->institution_id= $institution_id;
            $groupObj->campus_id= $campus_id;
             $result=$groupObj->save();


             // save all phone number by use group name
       for($i=0; $i<count($phoneNumber); $i++){

           $numberGroupObj= new $this->mobileNumber;
           $numberGroupObj->group_name_id= $groupObj->id;
           $numberGroupObj->institution_id= $institution_id;
           $numberGroupObj->campus_id= $campus_id;
           $numberGroupObj->mobile_number= $phoneNumber[$i];
           $numberGroupObj->save();

       }

       return redirect()->back();

    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function groupView($groupId)
    {
        $institution_id= $this->academicHelper->getInstitute();
        $campus_id= $this->academicHelper->getCampus();
        // get group profile
        $groupProfile=$this->group->find($groupId);
        $groupNumberList=$this->mobileNumber->where('group_name_id',$groupId)->where('institution_id', $institution_id)->where('campus_id', $campus_id)->get();
        return view('communication::pages.modal.sms.group_view', compact('groupNumberList','groupProfile'));
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('communication::show');
    }

    public function groupCopyAllNumber($groupId){

        $institution_id= $this->academicHelper->getInstitute();
        $campus_id= $this->academicHelper->getCampus();

        $groupNumberList=$this->mobileNumber->select('mobile_number')->where('group_name_id',$groupId)->where('institution_id', $institution_id)->where('campus_id', $campus_id)->get();
        if($groupNumberList->count()>0)
        {
            return $groupNumberList;
        }    else {
            return "error";
        }

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function groupNumberEditModal($groupId)
    {
        $institution_id= $this->academicHelper->getInstitute();
        $campus_id= $this->academicHelper->getCampus();
        // get group profile
        $groupProfile=$this->group->find($groupId);
        $groupNumberList=$this->mobileNumber->where('group_name_id',$groupId)->where('institution_id', $institution_id)->where('campus_id', $campus_id)->get();
        return view('communication::pages.modal.sms.group_number_edit', compact('groupNumberList','groupProfile'));

    }


    public function updateMobileNumberGroup(Request $request){

//        return $request->all();

        $allOldNumberId=[];
        $group_id=$request->input('group_id');
        $group_name=$request->input('group_name');
        $phoneNumber=$request->input('phone_number');
        $allOldNumberId=$request->input('all_old_number_id');
        $old_number_ids=$request->input('old_number_id');
        $oldNumber=$request->input('old_number');
//        return $old_number_ids[0];

        // check old number list
        if(!empty($old_number_ids)){
            $removeNumberList=array_diff($allOldNumberId,$old_number_ids);
            $removeValueList=array_values($removeNumberList);
            if(count($removeValueList)>0){
                // save all phone number by use group name
                for($i=0; $i<count($removeValueList); $i++){
                    $numberGroupObjProfile=  $this->mobileNumber->find($removeValueList[$i]);
                    $numberGroupObjProfile->delete();
                }
            }
        } else {
            // all old number list delete
            for($i=0; $i<count($allOldNumberId); $i++){
                $numberGroupObjProfile=  $this->mobileNumber->find($allOldNumberId[$i]);
                $numberGroupObjProfile->delete();
            }
        }


        // get institution and campus id

        $institution_id= $this->academicHelper->getInstitute();
        $campus_id= $this->academicHelper->getCampus();

        // save group name
        $groupObj= $this->group->find($group_id);
        $groupObj->institution_id=$institution_id;
        $groupObj->group_name= $group_name;
        $groupObj->institution_id= $institution_id;
        $groupObj->campus_id= $campus_id;
        $result=$groupObj->save();


        if(count($phoneNumber)>0){
            // save all phone number by use group name
            for($i=0; $i<count($phoneNumber); $i++){
                if(!empty($phoneNumber[$i])){

                    $numberGroupObj= new $this->mobileNumber;
                    $numberGroupObj->group_name_id= $group_id;
                    $numberGroupObj->institution_id= $institution_id;
                    $numberGroupObj->campus_id= $campus_id;
                    $numberGroupObj->mobile_number= $phoneNumber[$i];
                    $numberGroupObj->save();
                }
            }
        }

        // mobile number are should be update query


        if(count($old_number_ids)>0){
            // save all phone number by use group name
            for($i=0; $i<count($old_number_ids); $i++){
                if (array_key_exists($i,$old_number_ids)) {
                    $numberGroupObjProfile= $this->mobileNumber->find($old_number_ids[$i]);
                    $numberGroupObjProfile->group_name_id= $group_id;
                    $numberGroupObjProfile->institution_id= $institution_id;
                    $numberGroupObjProfile->campus_id= $campus_id;
                    $numberGroupObjProfile->mobile_number= $oldNumber[$i];
                    $numberGroupObjProfile->save();
                }
            }
//            return $array;
        }


        return redirect()->back();


    }


    public function groupDelete($groupId){
//        return "ddd";
        $groupNumberProfile=$this->mobileNumber->where('group_name_id', $groupId)->first();
        if(!empty($groupNumberProfile)){
            $this->mobileNumber->where('group_name_id',$groupId)->delete();
            $this->group->find($groupId)->delete();
            return 'success';
        } else {
                return "error";
        }
    }









    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
