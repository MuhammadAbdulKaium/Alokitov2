<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\InstituteLanguage;
use Modules\Setting\Entities\Language;

class InstituteLanguageController extends Controller
{


    private  $instituteLanguage;
    private  $language;

    public function __construct(InstituteLanguage $instituteLanguage, Language $language)
    {
        $this->instituteLanguage      = $instituteLanguage;
        $this->language               = $language;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $instituteLanguages= $this->instituteLanguage->all();
        $languages= $this->language->all();
        return view('setting::language.institute_language',compact('languages','instituteLanguages'));
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
        public function store( Request $request)
    {

        $instituteLanguage=new $this->instituteLanguage();
        $instituteLanguage->institute_id=$request->input('institute_name');
        $instituteLanguage->language_id=$request->input('language_name');
        $instituteLanguage->save();
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
    public function edit()
    {
        return view('setting::edit');
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
