<?php

namespace Modules\Setting\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Setting\Entities\FontFamily;
use Illuminate\Support\Facades\Session;

class FontFamilyController extends Controller
{

    private  $fontFamily;

    public function  __construct(FontFamily $fontFamily)
    {
        $this->fontFamily= $fontFamily;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $fontFamilys=$this->fontFamily->orderByDesc('id')->get();
        return view('setting::font-family.index', compact('fontFamilys'));
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
        $fontFamilyId= $request->input('font_family_id');
        if(!empty($fontFamilyId)){
            $fontFamilyProfile=$this->fontFamily->find($fontFamilyId);
            $fontFamilyProfile->font_name=$request->input('font_name');
            $fontFamilyProfile->font_link=$request->input('font_link');
            $fontFamilyProfile->font_css_code=$request->input('font_css_code');
            $fontFamilyProfile->save();
            Session::flash('message', 'Font Family Successfully Updated');

        } else {

            $fontFamilyObj= new $this->fontFamily;
            $fontFamilyObj->font_name=$request->input('font_name');
            $fontFamilyObj->font_link=$request->input('font_link');
            $fontFamilyObj->font_css_code=$request->input('font_css_code');
            $fontFamilyObj->save();
            Session::flash('message', 'Font Family Successfully Created');
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
    public function edit($id)
    {
        $fontFamilyProfile=$this->fontFamily->find($id);
        $fontFamilys=$this->fontFamily->orderByDesc('id')->get();
        return view('setting::font-family.index', compact('fontFamilyProfile','fontFamilys'));
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
    public function delete($id)
    {
        $fontFamilyProfile=$this->fontFamily->find($id);
        $fontFamilyProfile->delete();
        return  redirect()->back();
    }
}
