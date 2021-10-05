<?php

namespace Modules\Website\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use App\Http\Controllers\Helpers\AcademicHelper;
use Illuminate\Support\Facades\Auth;
use Modules\Website\Entities\WebsiteInformation;
use Session;
use Validator;

class InformationController extends Controller
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
        $informations = WebsiteInformation::where('campus_id', '=', $campus)
            ->where('institute_id', '=', $institute)->first();

        return view('website::information.index', compact('informations', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = Auth::user();
        return view('website::information.create', compact('user'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        if($file = $request->file('school_logo')){
            $current_timestamp = Carbon::now()->timestamp;
            $name = $input['campus_id'] . $input['institute_id'] . $current_timestamp . "." . $file->getClientOriginalExtension();
            $file->move('images', $name);
            $input['school_logo'] = $name;
        }
        WebsiteInformation::create($input);
        return redirect('website/information');
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
        $informations = WebsiteInformation::findOrFail($id);

        return view('website::information.edit', compact('informations', 'user'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $website = WebsiteInformation::findOrFail($id);

        $input = $request->all();

        if($file = $request->file('school_logo')){
            $current_timestamp = Carbon::now()->timestamp;
            $name = $input['campus_id'] . $input['institute_id'] . $current_timestamp . "." . $file->getClientOriginalExtension();
            $file->move('images', $name);
            $input['school_logo'] = $name;
        }

        $website->update($input);
        return redirect('website/information');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
