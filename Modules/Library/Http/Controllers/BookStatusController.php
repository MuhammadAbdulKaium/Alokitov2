<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Library\Entities\BookStatus;

class BookStatusController extends Controller
{


    private  $bookStatus;

    public function __construct(BookStatus $bookStatus)
    {
        $this->bookStatus             = $bookStatus;
    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $bookStatusList=$this->bookStatus->orderByDesc('id')->paginate('10');
        return view('library::library-book-status.index',compact('bookStatusList'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function store(Request $request)
    {
        $book_status_id=$request->input('book_status_id');
        if($book_status_id>0) {

            $bookStatus = $this->bookStatus->find($book_status_id);
            $bookStatus->name = $request->input('name');
            $bookStatus->details = $request->input('details');
            $update = $bookStatus->save();
            if ($update) {
                Session::flash('update', "Book Status Update Successfully");
            }


        } else {

            $bookStatus = new $this->bookStatus;
            $bookStatus->name = $request->input('name');
            $bookStatus->details = $request->input('details');
            $insert = $bookStatus->save();
            if ($insert) {
                Session::flash('insert', "Book Status Create Successfully");
            }

        }
        return redirect()->back();
    }


    // book shelf delete

    public  function delete($bookStatusId){
        $bookStatusProfile=$this->bookStatus->find($bookStatusId);
        $bookStatusProfile->delete();
    }


    public  function edit($bookStatusId){
        $bookStatusList=$this->bookStatus->orderByDesc('id')->paginate(10);
        $bookStatusProfile=$this->bookStatus->find($bookStatusId);
        return view('library::library-book-status.index',compact('bookStatusList','bookStatusProfile'));

    }


    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('library::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */

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
