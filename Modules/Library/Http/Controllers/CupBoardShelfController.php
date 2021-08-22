<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Library\Entities\BookShelf;
use Modules\Library\Entities\CupBoardShelf;
use Illuminate\Support\Facades\Session;



class CupBoardShelfController extends Controller
{

    private  $bookShelf;
    private  $cupBoardShelf;

    public function __construct(BookShelf $bookShelf, CupBoardShelf $cupBoardShelf)
    {
        $this->bookShelf                  = $bookShelf;
        $this->cupBoardShelf             = $cupBoardShelf;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
       $bookShelfs= $this->bookShelf->all();
       $cupBoardShelfs= $this->cupBoardShelf->orderByDesc('id')->paginate('10');
        return view('library::library-cupboard-shelf.index',compact('cupBoardShelfs','bookShelfs'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function store( Request $request)
    {
        $cupBoardShelfId = $request->input('cup_board_shelf_id');
        if ($cupBoardShelfId > 0) {

            $cupBoardShelf =$this->cupBoardShelf->find($cupBoardShelfId);
            $cupBoardShelf->name = $request->input('name');
            $cupBoardShelf->book_shelf_id = $request->input('book_shelf_name');
            $cupBoardShelf->capacity = $request->input('capacity');
            $cupBoardShelf->details = $request->input('details');
            $insert = $cupBoardShelf->save();
            if ($insert) {
                Session::flash('insert', "Book Cup Board Shelf Update Successfully");
            }
            return redirect()->back();

        } else {
            $cupBoardShelf = new $this->cupBoardShelf;
            $cupBoardShelf->name = $request->input('name');
            $cupBoardShelf->book_shelf_id = $request->input('book_shelf_name');
            $cupBoardShelf->capacity = $request->input('capacity');
            $cupBoardShelf->details = $request->input('details');
            $insert = $cupBoardShelf->save();
            if ($insert) {
                Session::flash('insert', "Book Cup Board Shelf Create Successfully");
            }
            return redirect()->back();
        }
    }



    //get cupboad shelf by shelf id

     public  function  getCupBoradShelfByBookShelfId($bookShelfId){
        return $this->cupBoardShelf->where('book_shelf_id',$bookShelfId)->get();
     }


    public  function delete($cupBoardShelfId){
        $cupBoardShelfIdProfile=$this->cupBoardShelf->find($cupBoardShelfId);
        $cupBoardShelfIdProfile->delete();
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

    public  function edit($cupBoardShelfId){
        $bookShelfs= $this->bookShelf->all();
        $cupBoardShelfs=$this->cupBoardShelf->orderByDesc('id')->paginate(10);
        $cupBoardShelfProifle=$this->cupBoardShelf->find($cupBoardShelfId);
        return view('library::library-cupboard-shelf.index',compact('bookShelfs','cupBoardShelfs','cupBoardShelfProifle'));

    }

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
