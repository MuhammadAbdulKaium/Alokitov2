<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Finance\Entities\EntriesType;

class EntriesTypeController extends Controller
{

    private $entriesType;
    /**
     * Display a listing of the resource.
     * @return Response
     */

    public function  __construct(EntriesType $entriesType)
    {
        $this->entriesType=$entriesType;
    }


    public function addEntryType()
    {
        return view('finance::pages.setting.addentrytype');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function storeEntryType(Request $request)
    {

        if(!empty($request->entriestype_id)) {
            $entriesTypeProfile = $this->entriesType->find($request->entriestype_id);
            $entriesTypeProfile->label = $request->label;
            $entriesTypeProfile->name = $request->name;
            $entriesTypeProfile->desc = $request->desc;
            $entriesTypeProfile->numbering = $request->numbering;
            $entriesTypeProfile->prefix = $request->prefix;
            $entriesTypeProfile->suffix = $request->suffix;
            $result = $entriesTypeProfile->save();
            Session::flash('message', 'Entries Type Updated Successfully');

        } else {
            $entriesTypeObj = new $this->entriesType;
            $entriesTypeObj->label = $request->label;
            $entriesTypeObj->name = $request->name;
            $entriesTypeObj->desc = $request->desc;
            $entriesTypeObj->numbering = $request->numbering;
            $entriesTypeObj->prefix = $request->prefix;
            $entriesTypeObj->suffix = $request->suffix;
            $result = $entriesTypeObj->save();
            Session::flash('message', 'Entries Type Created Successfully');
        }


        return redirect()->back();


    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function entrytypeList()
    {
        $entriesTypes=$this->entriesType->all();

        return view('finance::pages.setting.entrytypelist',compact('entriesTypes'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('finance::edit');
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
