<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Library\Entities\BookShelf;
use Illuminate\Support\Facades\Session;


class BookShelfController extends Controller
{

    private  $bookShelf;

    public function __construct(BookShelf $bookShelf)
    {
        $this->bookShelf             = $bookShelf;
    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $bookShelfs=$this->bookShelf->orderByDesc('id')->paginate('10');
        return view('library::library-book-shelf.index',compact('bookShelfs'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function store(Request $request)
    {
        $book_shelf_id=$request->input('book_shelf_id');
        if($book_shelf_id>0) {

            $bookShelf = $this->bookShelf->find($book_shelf_id);
            $bookShelf->name = $request->input('name');
            $bookShelf->details = $request->input('details');
            $update = $bookShelf->save();
            if ($update) {
                Session::flash('update', "Book Shelf Update Successfully");
            }


        } else {

            $bookShelf = new $this->bookShelf;
            $bookShelf->name = $request->input('name');
            $bookShelf->details = $request->input('details');
            $insert = $bookShelf->save();
            if ($insert) {
                Session::flash('insert', "Book Shelf Create Successfully");
            }

        }
        return redirect()->back();
    }


    // book shelf delete

    public  function delete($bookShelfId){
        $bookShelfProfile=$this->bookShelf->find($bookShelfId);
        $bookShelfProfile->delete();
    }


    public  function edit($bookShelfId){
        $bookShelfs=$this->bookShelf->orderByDesc('id')->paginate(10);
        $bookShelfsProfile=$this->bookShelf->find($bookShelfId);
        return view('library::library-book-shelf.index',compact('bookShelfs','bookShelfsProfile'));

    }


    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */

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
