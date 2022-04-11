<?php

namespace Modules\Academics\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;

class ExamFinalController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('academics::exam.exam-final-result');
    }
    public function termEndExamResults()
    {
        return view('academics::exam.exam-final-result-details');

    }
    public function termEndExamResultDetailsPdf()
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('academics::exam.exam-final-result-details-pdf')->setPaper('a4', 'landscape');
        return $pdf->stream();

    }
    public function termEndExamResultPdf()
    {
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('academics::exam.exam-final-result-pdf')->setPaper('a4', 'landscape');
        return $pdf->stream();

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('academics::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('academics::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('academics::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
