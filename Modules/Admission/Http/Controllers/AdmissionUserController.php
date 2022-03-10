<?php

namespace Modules\Admission\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use JWTAuth ;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Modules\Admission\Entities\ApplicantUser;
use Modules\Admission\Entities\ApplicantUser as User;

class AdmissionUserController extends Controller
{
    public $user;
    public function __construct(){
        $this->user=auth('api')->user();

    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('admission::index');
    }



    public function getUserInfo(){
        return ['status'=>true ,'data'=>$this->user];
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('admission::create');
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
        return view('admission::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('admission::edit');
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
