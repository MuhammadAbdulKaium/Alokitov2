<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;
use Modules\Library\Entities\BookCategory;


class BookCategoryController extends Controller
{
    private  $bookCategory;

    public function __construct(BookCategory $bookCategory)
    {
        $this->bookCategory             = $bookCategory;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        // get all Book category
            $bookCategorys=$this->bookCategory->orderByDesc('id')->paginate(3);
            return view('library::library-book-category.index',compact('bookCategorys'));
    }





    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function store(Request $request)
    {
        $book_category_id=$request->input('book_category_id');
        if(empty($book_category_id)) {
            $bookCategory = new $this->bookCategory;
            $bookCategory->name = $request->input('name');
            $insert = $bookCategory->save();
            if ($insert) {
                Session::flash('insert', "Book Category Create Successfully");
            }
        } else {

            $bookCategory = $this->bookCategory->find($book_category_id);
            $bookCategory->name = $request->input('name');
            $update = $bookCategory->save();
            if ($update) {
                Session::flash('update', "Book Category Update Successfully");
            }

        }
        return redirect()->back();
    }



    // delete Library book Category

    public  function delete($bookCategoryId){
        $bookCategoryProfile=$this->bookCategory->find($bookCategoryId);
        $bookCategoryProfile->delete();
    }



    // book category edit view
    public  function edit($bookCategoryId){
        $bookCategorys=$this->bookCategory->orderByDesc('id')->paginate(3);
        $bookCategoryProfile=$this->bookCategory->find($bookCategoryId);
        return view('library::library-book-category.index',compact('bookCategoryProfile','bookCategorys'));

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
