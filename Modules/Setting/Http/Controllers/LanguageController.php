<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\Language;
use Session;

class LanguageController extends Controller
{


    private  $language;

    public function __construct(Language $language)
    {
        $this->language             = $language;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $languages= $this->language->all();
        return view('setting::language.index',compact('languages'));
    }


    public  function  languageEdit($language_id){
//        return $language_id;
        $languageProfile= $this->language->find($language_id);
        $languages= $this->language->all();
        return view('setting::language.index',compact('languageProfile','languages'));
    }



    public function selectLanguage($locale){
        if (in_array($locale, \Config::get('app.locales'))) {
            Session::put('locale', $locale);
        }
        return redirect('/');
}

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function store( Request $request)
    {

        $language_id=$request->input('language_id');
        if(empty($language_id)) {
            $language = new $this->language();
            $language->language_name = $request->input('language_name');
            $language->language_slug = $request->input('language_slug');
            $language->save();
            return redirect()->back();
        } else {
            $language = $this->language->find($language_id);
            $language->language_name = $request->input('language_name');
            $language->language_slug = $request->input('language_slug');
            $language->save();
            return redirect()->back();
        }

    }

    public  function  deleteLanguage($language_id){
       $language_profile=$this->language->find($language_id);
       $language_profile->delete();
        return redirect()->back();
    }




    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
//    public function store(Request $request)
//    {
//    }

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
