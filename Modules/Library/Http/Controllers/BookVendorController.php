<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Library\Entities\BookVendor;
use Illuminate\Support\Facades\Session;

class BookVendorController extends Controller
{

    private  $bookVendor;

    public function __construct(BookVendor $bookVendor)
    {
        $this->bookVendor             = $bookVendor;
    }


    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $bookVendors=$this->bookVendor->orderByDesc('id')->paginate(10);
        return view('library::library-book-vendor.index',compact('bookVendors'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function store(Request $request)
    {
        $bookVendorId=$request->input('book_vendor_id');
        if($bookVendorId>0) {

            $bookVendor= $this->bookVendor->find($bookVendorId);
            $bookVendor->name=$request->input('name');
            $bookVendor->address=$request->input('address');
            $bookVendor->contact_no=$request->input('contact_no');
            $bookVendor->email=$request->input('email');
            $insert=$bookVendor->save();
            if($insert) {
                Session::flash('insert',"Book Vendor Update Successfully");
            }
            return redirect()->back();
        }
        else {
            $bookVendor=new $this->bookVendor;
            $bookVendor->name=$request->input('name');
            $bookVendor->address=$request->input('address');
            $bookVendor->contact_no=$request->input('contact_no');
            $bookVendor->email=$request->input('email');
            $insert=$bookVendor->save();
            if($insert) {
                Session::flash('insert',"Book Vendor Create Successfully");
            }
            return redirect()->back();
        }

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
    public  function edit($bookVendorId){
        $bookVendors=$this->bookVendor->orderByDesc('id')->paginate(10);
        $bookVendorProfile=$this->bookVendor->find($bookVendorId);
        return view('library::library-book-vendor.index',compact('bookVendors','bookVendorProfile'));

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

    public  function delete($bookVendorId){
        $bookVendor=$this->bookVendor->find($bookVendorId);
        $bookVendor->delete();
    }
}
