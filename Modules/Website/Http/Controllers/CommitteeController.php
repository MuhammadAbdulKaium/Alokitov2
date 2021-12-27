<?php

namespace Modules\Website\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Website\Entities\WebsiteCommittee;
use Validator;


class CommitteeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */

    public function  speech(){
        $campus = session()->get('campus');
        $institute = session()->get('institute');

        $user = Auth::user();
        $committees = WebsiteCommittee::where('campus_id', '=', $campus)
            ->where('institute_id', '=', $institute)->get();

        return view('website::committee.speech.index', compact('committees', 'user'));
    }
    public function index()
    {
        $campus = session()->get('campus');
        $institute = session()->get('institute');

        $user = Auth::user();
        $committees = WebsiteCommittee::where('campus_id', '=', $campus)
            ->where('institute_id', '=', $institute)->get();

        return view('website::committee.index', compact('committees', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = Auth::user();
        return view('website::committee.create', compact('user'));
    }
    public function speechCreate()
    {
        $user = Auth::user();
        return view('website::committee.speech.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
            $campus = session()->get('campus');
            $institute = session()->get('institute');
        if($request->type!=null){
            $data=WebsiteCommittee::where('campus_id', '=', $campus)
                ->where('institute_id', '=', $institute)->where('type','=',$request->type)->first();
         if($data)
         {

             if($request->type==1){
                 return redirect()->back()->with('warning','Principle is already assigned !');
             }else
             {
                 return redirect()->back()->with('warning','Chairman is already assigned !');
             }

         }
        }

        $input = $request->all();

        if($file = $request->file('image')){
            $current_timestamp = Carbon::now()->timestamp;
            $name = $input['campus_id'] . $input['institute_id'] . $current_timestamp . "." . $file->getClientOriginalExtension();
            $file->move('images', $name);
            $input['image'] = $name;
        }

        WebsiteCommittee::create($input);
      return redirect()->back();

    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $values = WebsiteCommittee::findOrFail($id);

        return view('website::committee.show', compact('values', 'user'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $committees = WebsiteCommittee::findOrFail($id);

        return view('website::committee.edit', compact('committees', 'user'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $website = WebsiteCommittee::findOrFail($id);

        $input = $request->all();

        if($file = $request->file('image')){
            $current_timestamp = Carbon::now()->timestamp;
            $name = $input['campus_id'] . $input['institute_id'] . $current_timestamp . "." . $file->getClientOriginalExtension();
            $file->move('images', $name);
            $input['image'] = $name;
        }

        $website->update($input);
        return redirect('website/committee');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $committee = WebsiteCommittee::findOrFail($id);

        $committee->delete();
        return redirect('website/committee');
    }

}
