<?php

namespace Modules\Library\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Library\Entities\BookCategory;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\IssueBook;
use Modules\Library\Entities\BookVendor;
use Modules\Library\Entities\BookShelf;
use Modules\Library\Entities\BookStock;
use Illuminate\Support\Facades\Session;

class BookController extends Controller
{

    private  $book;
    private  $bookShelf;
    private  $bookCategory;
    private  $bookVendor;
    private  $issueBook;
    private  $bookStock;

    public function __construct(BookShelf $bookShelf,BookStock $bookStock,BookCategory $bookCategory,BookVendor $bookVendor,Book $book,IssueBook $issueBook)
    {
        $this->bookShelf                  = $bookShelf;
        $this->bookCategory               = $bookCategory;
        $this->bookVendor                 = $bookVendor;
        $this->book                       = $book;
        $this->issueBook                       = $issueBook;
        $this->bookStock                       = $bookStock;
    }



    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        //get book categorys
        $bookCategorys=$this->bookCategory->all();
        $bookShelfs=$this->bookShelf->all();
        $bookVendors=$this->bookVendor->all();
        return view('library::library-book-master.create_update',compact('bookCategorys','bookShelfs','bookVendors'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('library::create_update');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {

        $book_id=$request->input('book_id');

        if(empty($book_id)) {
        // total copy book
        $copy= $request->input('copy');
        $bookStock=array();
        $bookStock['total'] = $copy;
        $bookStock['total_list'] =$copy ;

        $book=new $this->book;
        $book->name=$request->input('name');
        $book->book_category_id=$request->input('book_category');
        $book->book_type=$request->input('book_type');
        $book->subtitle=$request->input('subtitle');
        $book->isbn_no=$request->input('isbn_no');
        $book->author=$request->input('author');
        $book->book_shelf_id=$request->input('book_shelf');
        $book->cup_board_shelf_id=$request->input('cup_board_shelf');
        $book->edition=$request->input('edition');
        $book->publisher=$request->input('publisher');
        $book->book_cost=$request->input('book_cost');
        $book->book_vendor_id=$request->input('book_vendor');
        $book->copy=json_encode($bookStock);
        $book->remark=$request->input('remark');
        $insert=$book->save();

        if($insert) {

        for($i=1;$i<=$copy;$i++){
            $bookStock=new $this->bookStock;
            $bookStock->book_id=$book->id;
            $bookStockCount=$this->bookStock->all();
            if($bookStockCount->count()>0) {
                $bookStock->asn_no = $bookStock->count()+1;
                $bookStock->barcode="BID".$book->id."ASN-".$bookStock->asn_no."BR";
            } else {
                $bookStock->asn_no =$i;
                $bookStock->barcode="BID".$book->id."ASN-".$i."BR";
            }

            $bookStock->save();

        }


            Session::flash('insert',"Book Create Successfully");
        }

        } else {

            $book=$this->book->find($book_id);
            $book->name=$request->input('name');
            $book->book_category_id=$request->input('book_category');
            $book->book_type=$request->input('book_type');
            $book->subtitle=$request->input('subtitle');
            $book->isbn_no=$request->input('isbn_no');
            $book->author=$request->input('author');
            $book->book_shelf_id=$request->input('book_shelf');
            $book->cup_board_shelf_id=$request->input('cup_board_shelf');
            $book->edition=$request->input('edition');
            $book->publisher=$request->input('publisher');
            $book->book_cost=$request->input('book_cost');
            $book->book_vendor_id=$request->input('book_vendor');
            $book->remark=$request->input('remark');
            $update=$book->save();
            if($update){
                Session::flash('update',"Book Update successfully");
            }


        }
        return redirect()->back();
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function showAllBooks()
    {
         $books=$this->book->paginate(10);
        return view('library::library-book-master.index',compact('books'));
    }



    public function showBorrowBookTransaction()
    {
       $books=$this->book->all();
        return view('library::library-borrow-transaction.index',compact('books'));
    }



    /// book search function
    ///
    public  function  bookSearch(Request $request){
//        return $request->all();
        $asn_no=$request->input('asn_no');
        $isbn_no=$request->input('isbn_no');
        $book_name=$request->input('book_name');
        $author_name=$request->input('author_name');
        $subtitle=$request->input('subtitle');
        $book_status=$request->input('book_status');

            $allBoookSearchInputs=array();
            $issueBookInputs=array();

            // check isbn_no
            if ($isbn_no) {
                $allBoookSearchInputs['isbn_no'] = $isbn_no;
            }
            // check book_name
            if ($book_name) {

                $allBoookSearchInputs['name'] = $book_name;
            }
            // check author
            if ($author_name) {

                $allBoookSearchInputs['author'] = $author_name;
            }

            // check subtitle
            if ($subtitle) {

                $allBoookSearchInputs['subtitle'] = $subtitle;
            }

            if ($asn_no) {

                $issueBookInputs['asn_no'] = $asn_no;
            }

            if ($book_status) {

                $issueBookInputs['status'] = $book_status;
            }


            if($book_status=="1"){
                if(empty($asn_no)) {

                    $books=$this->book->where($allBoookSearchInputs)->get();
                    $bookArray=array();
                    foreach ($books as $book){
                         $bookCopy=json_decode(($book->copy));
                       if($bookCopy->total>0) {
                           $bookArray[] =$book->id;
                       }
                    }

                    $bookList=$this->book->whereIn('id',$bookArray)->get();

                     $books=$bookList;
                    return view('library::library-borrow-transaction.index',compact('books'));
                } else {

                    $books=$bookList=array();
                    return view('library::library-borrow-transaction.index',compact('books'));

                }
                return $bookList;
            }
             elseif($book_status="2"){

                 $issueBookList= $this->issueBook->select('book_id')->where($issueBookInputs)->distinct('book_id')->get();
                 // array decelear
                 $bookArray=array();
                 // loop for book id push in array
                 foreach ($issueBookList as $book){
                     $bookArray[]=$book->book_id;
                 }
                 $bookList=$this->book->where($allBoookSearchInputs)->whereIn('id',$bookArray)->get();
                 $books=$bookList;
                 return view('library::library-borrow-transaction.index',compact('books'));


             }
            elseif($book_status="3"){

                $issueBookList= $this->issueBook->select('book_id')->where($issueBookInputs)->distinct('book_id')->get();
                // array decelear
                $bookArray=array();
                // loop for book id push in array
                foreach ($issueBookList as $book){
                    $bookArray[]=$book->book_id;
                }
                $bookList=$this->book->where($allBoookSearchInputs)->whereIn('id',$bookArray)->get();
                $books=$bookList;
                return view('library::library-borrow-transaction.index',compact('books'));


            }


    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function viewBookDetails($bookId)
    {
         $bookProfile=$this->book->find($bookId);
         // book Count
         $bookCount=$this->bookStock->where("book_id",$bookId)->count();
         //book stock Pagination
         $bookStocks=$this->bookStock->where("book_id",$bookId)->paginate(10);
        return view('library::library-book-master.view',compact('bookProfile','bookStocks','bookCount'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function addMoreCopyModal($bookId)
    {
        return view('library::modals.copy_book',compact('bookId'));
    }


    public  function addMoreCopyStore(Request $request)
    {
        $copy = $request->input('copy');
        $book_id = $request->input('book_id');

        $book= $this->book->find($book_id);
        $oldCopy=json_decode(($book->copy));
        // book stock array declear
        $bookStock=array();
        $bookStock['total'] = $copy+$oldCopy->total;
        $bookStock['total_list'] =$oldCopy->total_list.",".$copy ;
        $book->copy=json_encode($bookStock);
        $insert=$book->save();

        if ($insert) {
            for ($i = 1; $i <= $copy; $i++) {
                $bookStock = new $this->bookStock;
                $bookStock->book_id =$book_id;
                $bookStockCount = $this->bookStock->all();
                if ($bookStockCount->count() > 0) {
                    $bookStock->asn_no = $bookStock->count() + 1;
                    $bookStock->barcode = "BID" . $book_id . "ASN-" . $bookStock->asn_no . "BR";
                } else {
                    $bookStock->asn_no = $i;
                    $bookStock->barcode = "BID" . $book_id . "ASN-" . $i . "BR";
                }

                $bookStock->save();

            }
        }
        return redirect()->route('bookList');

    }

    // book update

    public  function editBook($book_id){
        $bookProfile=$this->book->find($book_id);

        //get book categorys
        $bookCategorys=$this->bookCategory->all();
        //book shelfs
        $bookShelfs=$this->bookShelf->all();
        //boookVendor
        $bookVendors=$this->bookVendor->all();

        return view('library::library-book-master.create_update',compact('bookProfile','bookCategorys','bookShelfs','bookVendors'));
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
