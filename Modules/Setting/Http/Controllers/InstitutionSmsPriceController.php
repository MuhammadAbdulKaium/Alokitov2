<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\InstitutionSmsPrice;
use Modules\Setting\Entities\Institute;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class InstitutionSmsPriceController extends Controller
{

    private  $academicHelper;
    private  $institutionSmsPrice;


    public function __construct(AcademicHelper $academicHelper, Institute $institute,InstitutionSmsPrice $institutionSmsPrice)
    {
        $this->academicHelper             = $academicHelper;
        $this->institutionSmsPrice   = $institutionSmsPrice;
        $this->institute   = $institute;
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
        //get all loginScreens
        $institutionSmsPriceList=$this->institutionSmsPrice->get();
        return view('setting::institute-sms-price.index',compact('institutes','institutionSmsPriceList'));

    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('setting::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'institute_id' => 'required',
            'sms_price'   => 'required',
        ]);

        if ($validator->passes()) {

            $institutionSmsPriceId = $request->input('institutionSmsPriceProfile_id');
            if (empty($institutionSmsPriceId)) {

                // check duplicate value in the table
                $instituteSmsProfile=$this->institutionSmsPrice->where('institution_id',$request->input('institute_id'))->first();
                if(empty($instituteSmsProfile)) {
                    $institutionSmsPriceObj = new $this->institutionSmsPrice();
                    $institutionSmsPriceObj->institution_id = $request->input('institute_id');
                    $institutionSmsPriceObj->sms_price = $request->input('sms_price');
                    $institutionSmsPriceObj->save();
                    Session::flash('message', 'Institute SMS Price Successfully Created');
                } else {
                    Session::flash('error', 'Institute SMS Price Already Exists');
                }
          } else {
                $institutionSmsPriceProfile = $this->institutionSmsPrice->find($institutionSmsPriceId);
                $institutionSmsPriceProfile->sms_price = $request->input('sms_price');
                $institutionSmsPriceProfile->save();
                Session::flash('message', 'Institute SMS Price Successfully Updated');
            }
        } else {
            Session::flash('error', 'Something wrong please try again');
        }

        return redirect()->back();

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('setting::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($institutionSmsPriceId)
    {
        $institutes=$this->institute->all();
        //get all institute Property
        $institutionSmsPriceList=$this->institutionSmsPrice->all();
        //single property
        $institutionSmsPriceProfile=$this->institutionSmsPrice->find($institutionSmsPriceId);
        return view('setting::institute-sms-price.index',compact('institutes','institutionSmsPriceList','institutionSmsPriceProfile'));

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
    public function delete($institutionSmsPriceId)
    {
        $institutionSmsPriceProfile=$this->institutionSmsPrice->find($institutionSmsPriceId);
        $institutionSmsPriceProfile->delete();
    }
}
