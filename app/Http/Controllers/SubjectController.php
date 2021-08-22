<?php

namespace App\Http\Controllers;

use App\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $pageTitle = "Subject Informations";
        $subject_alias = Input::get('subject_alias');
        $title = Input::get('subject_code');

        $data = Currency::where('status','!=','cancel')->where('code', 'LIKE', '%'.$code.'%')->where('title', 'LIKE', '%'.$title.'%')->orderBy('id', 'DESC')->get();
        return view('admin::currency.index', ['data' => $data, 'pageTitle'=> $pageTitle]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\SubjectRequest $request)
    {
        $input = $request->all();

        $subject_alias = Input::get('subject_alias');
        $subject_alias_upper_case = strtoupper($subject_alias);
        $input['subject_alias'] = $subject_alias_upper_case;

        $title = Input::get('subject_code');
        $title_upper_case = ucwords($title);
        $input['subject_code'] = $title_upper_case;

        $title = Input::get('subject_alias');
        $title_upper_case = ucwords($title);
        $input['subject_alias'] = $title_upper_case;

        /* Transaction Start Here */
        DB::beginTransaction();
        try {
            Subject::create($input);
            DB::commit();
            Session::flash('message', 'Successfully added!');
        } catch (\Exception $e) {
            //If there are any exceptions, rollback the transaction`
            DB::rollback();
            //Session::flash('danger', $e->getMessage());
            Session::flash('error', "Invalid Request. Please Try Again");
        }

        return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {//print_r($id);exit;
        $pageTitle = 'View Currency Informations';
        $data = Currency::where('id',$id)->first();

        return view('admin::currency.view', ['data' => $data, 'pageTitle'=> $pageTitle]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $pageTitle = 'Update Currency Informations';
        $data = Currency::where('id',$id)->first();
        return view('admin::currency.update', ['data' => $data, 'pageTitle'=> $pageTitle]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\CurrencyRequest $request, $id)
    {
        $model = Currency::where('id',$id)->first();
        $input = $request->all();

        $code = Input::get('code');
        $code_upper_case = strtoupper($code);
        $input['code'] = $code_upper_case;

        $title = Input::get('title');
        $title_upper_case = ucwords($title);
        $input['title'] = $title_upper_case;

        DB::beginTransaction();
        try {
            $model->update($input);
            DB::commit();
            Session::flash('message', "Successfully Updated");
        }
        catch ( Exception $e ){
            //If there are any exceptions, rollback the transaction
            DB::rollback();
            Session::flash('error', "Invalid Request. Please Try Again");
        }
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $model = Currency::findOrFail($id);

        DB::beginTransaction();
        try {
            if($model->status =='active'){
                $model->status = 'cancel';
            }else{
                $model->status = 'active';
            }
            $model->save();
            DB::commit();
            Session::flash('message', "Successfully Deleted.");

        } catch(\Exception $e) {
            DB::rollback();
            Session::flash('error', "Invalid Request. Please Try Again");
        }
        return redirect()->back();
    }


}
