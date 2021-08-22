<?php

namespace Modules\Finance\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class AccountSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function entrytypeList()
    {
        return view('finance::pages.setting.entrytypelist');
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
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('finance::show');
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
