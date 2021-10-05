<?php

namespace Modules\Website\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Website\Entities\WebsiteRule;

class RulesController extends Controller
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
        $rules = WebsiteRule::where('campus_id', '=', $campus)
            ->where('institute_id', '=', $institute)->first();

        return view('website::rules.index', compact('rules', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = Auth::user();
        return view('website::rules.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        WebsiteRule::create($input);
        return redirect('website/rules');
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
        $rules = WebsiteRule::findOrFail($id);

        return view('website::rules.edit', compact('rules', 'user'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $website = WebsiteRule::findOrFail($id);

        $input = $request->all();

        $website->update($input);
        return redirect('website/rules');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $rules = WebsiteRule::findOrFail($id);
        $rules->delete();
        return redirect('website/rules');
    }
}
