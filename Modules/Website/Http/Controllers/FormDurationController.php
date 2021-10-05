<?php

namespace Modules\Website\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Website\Entities\WebsiteFormDuration;

class FormDurationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $campus = session()->get('campus');
        $institute = session()->get('institute');

        $user = Auth::user();
        $date = WebsiteFormDuration::where('campus_id', '=', $campus)
            ->where('institute_id', '=', $institute)->first();

        return view('website::form.index', compact('date', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = Auth::user();
        return view('website::form.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

//        dd($input);

        WebsiteFormDuration::create($input);
        return redirect('website/form');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('website::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $date = WebsiteFormDuration::findOrFail($id);

        return view('website::form.edit', compact('date', 'user'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $duration = WebsiteFormDuration::findOrFail($id);
        $input = $request->all();
        $duration->update($input);
        return redirect('website/form');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $duration = WebsiteFormDuration::findOrFail($id);
        $duration->delete();
        return redirect('website/form');
    }
}
