<?php

namespace Modules\Website\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Website\Entities\WebsitePublicExamResult;

class PublicExamResultController extends Controller
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
        $results = WebsitePublicExamResult::where('campus_id', '=', $campus)
            ->where('institute_id', '=', $institute)->get();

        return view('website::public-result-result.index', compact('results', 'user'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $user = Auth::user();
        $year = (int)date('Y');
        $years = array();

        for($i=$year -11; $i < $year - 1; $i++)
        {
            $years[] = $i + 1;
        }

        return view('website::public-result-result.create', compact('user', 'years'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        WebsitePublicExamResult::create($input);
        return redirect('website/public_exam');
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
        $result = WebsitePublicExamResult::findOrFail($id);
        $year = (int)date('Y');
        $years = array();

        for($i=$year - 11; $i < $year - 1; $i++)
        {
            $years[] = $i + 1;
        }

        return view('website::public-result-result.edit', compact('result', 'user', 'years'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $result = WebsitePublicExamResult::findOrFail($id);

        $input = $request->all();

        $result->update($input);
        return redirect('website/public_exam');
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy($id)
    {
        $result = WebsitePublicExamResult::findOrFail($id);
        $result->delete();
        return redirect('website/public_exam');
    }
}
