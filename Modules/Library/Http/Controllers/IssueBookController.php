<?php

namespace Modules\Library\Http\Controllers;

use App\Jobs\ResultJob;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Library\Entities\Book;
use Modules\Library\Entities\IssueBook;
use Modules\Library\Entities\BookStock;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class IssueBookController extends Controller
{

    private  $book;
    private  $issueBook;
    private  $bookStock;

    public function __construct(Book $book,IssueBook $issueBook,BookStock $bookStock)
    {
        $this->book            = $book;
        $this->issueBook            = $issueBook;
        $this->bookStock            = $bookStock;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('library::index');
    }


    public function showIssueBookModal($bookId)
    {
        $bookProfile=$this->book->find($bookId);
        $bookStockProfile=$this->bookStock->where('book_id',$bookId)->where('book_status',1)->first();
        return view('library::modals.borrow-book',compact('bookProfile','bookStockProfile'));
    }



    /// bookBorrowTransactionUpdate modal Show
    public function bookBorrowTransactionUpdate($bookIssueId)
    {
        $issueBookProfile=$this->issueBook->find($bookIssueId);
        return view('library::modals.borrow-book',compact('issueBookProfile'));
    }


    // return renew book modal


    public function returnRenewBook($bookIssueId)
    {
        $current=Carbon::now();
        $issueBookProfile=$this->issueBook->find($bookIssueId);
        $bookStockProfile=$this->bookStock->where('book_id',$issueBookProfile->book_id)->first();
        return view('library::modals.return_renew_book',compact('issueBookProfile','bookStockProfile','current'));
    }


    // return renew book update


    public function returnBookWithFine(Request $request,$issueBookId){
        $issueBook=$this->issueBook->find($issueBookId);
        $asn_id=$issueBook->asn_no;
        $issueBook->total_due=$request->total_due;
        $issueBook->total_fine=$request->total_due;
        $issueBook->status=4;
        $issueBook->is_paid=1;
        $issueBook->remarks="Fully Paid";
        $update=$issueBook->save();


        if($update) {
            $statusBook=$this->bookStock->find($asn_id);
            $statusBook->book_status=1;
            $statusUpdate=$statusBook->save();
            Session::flash('update',"Book Return Successfully");
        }
        return redirect()->back();
    }

    public function returnBookManually($bookIssueId)
    {
        $current=Carbon::now();
        $issueBookProfile=$this->issueBook->find($bookIssueId);
        $bookStockProfile=$this->bookStock->where('book_id',$issueBookProfile->book_id)->first();
        return view('library::library-fine-master.index',compact('issueBookProfile','bookStockProfile','current'));
    }

    public function returnBookWithFineManual(Request $request,$issueBookId){
        $issueBook=$this->issueBook->find($issueBookId);
        $asn_id=$issueBook->asn_no;
        $issueBook->total_due=$request->LibraryFineMaster['lfm_amount'];
        $issueBook->total_fine=$request->LibraryFineMaster['lfm_total_amount'];
        $issueBook->remarks=$request->LibraryFineMaster['lfm_remarks'];
        $issueBook->status=4;
        $issueBook->is_paid=1;
        $update=$issueBook->save();
        if($update) {
            $statusBook=$this->bookStock->find($asn_id);
            $statusBook->book_status=1;
            $statusUpdate=$statusBook->save();
            Session::flash('update',"Book Return Successfully");
        }
        return redirect()->route('fineList');
    }
    public function returnRenewBookUpdate(Request $request,$issueBookId)
    {

        $book_status= $request->input('book_status');
        $asn_no= $request->input('asn_no');
        $book_id= $request->input('book_id');
        if($book_status>0){
            $todayDate=date('Y-m-d');
            $issueBook=$this->issueBook->find($issueBookId);
            $issueBook->issue_date=$todayDate;
            //renew for 3
            $issueBook->status=3;
            $issueBook->due_date=date('Y-m-d',strtotime( "+30 day", strtotime( $todayDate ) ));
            $update=$issueBook->save();
            if($update) {

                Session::flash('update',"Book Renew Successfully");
            }
            return redirect()->back();

        } else {
            $issueBook=$this->issueBook->find($issueBookId);
            //return for 4
            $issueBook->status=4;
            $update=$issueBook->save();
            $asn_id=$issueBook->asn_no;
            if($update) {
                $statusBook=$this->bookStock->find($asn_id);
                $statusBook->book_status=1;
                $statusUpdate=$statusBook->save();
//                for Change book status
                $book=$this->book->find($book_id);
                $oldCopy=json_decode(($book->copy));
                // book stock array declear
                $bookStock=array();
                $bookStock['total'] =$oldCopy->total+1;
                $bookStock['total_list'] =$oldCopy->total_list;
                $book->copy=json_encode($bookStock);
                $book->save();

                if($book->save()){
                    $bookStock=$this->bookStock->where('asn_no',$asn_no)->first();
                    $bookStock->book_status=1;
                    $bookStock->save();
                }

                Session::flash('update',"Book Return Successfully");
            }
            return redirect()->back();

        }
    }


    public function storeIssueBook(Request $request)
    {
        //request all
//        dd($request->all());

        $issueBookId=$request->input('issue_book_id');
        if(!empty($issueBookId)){
            $issueBook=$this->issueBook->find($issueBookId);
            $issueBook->issue_date=date('Y-m-d',strtotime($request->input('issue_date')));
            $issueBook->due_date=date('Y-m-d',strtotime($request->input('due_date')));
            $update=$issueBook->save();
            if($update) {

                Session::flash('update',"Book Issue Update Successfully");
            }
            return redirect()->back();

        }else {
            $issueBook=new $this->issueBook;
            $issueBook->book_id=$request->input('book_id');
            $issueBook->isbn_no=$request->input('isbn_no');
            $issueBook->holder_type=$request->input('holder_type');
            $issueBook->holder_id=$request->input('holder_id');
            $issueBook->asn_no=$request->input('asn_no');
            $issueBook->is_paid=0;
            $issueBook->daily_fine=$request->input('daily_fine');
            $issueBook->issue_date=date('Y-m-d',strtotime($request->input('issue_date')));
            $issueBook->due_date=date('Y-m-d',strtotime($request->input('due_date')));
            $issueBook->status=2;
            $insert=$issueBook->save();
            if($insert) {

                $book=$this->book->find($request->input('book_id'));
                $oldCopy=json_decode(($book->copy));
                // book stock array declear
                $bookStock=array();
                $bookStock['total'] =$oldCopy->total-1;
                $bookStock['total_list'] =$oldCopy->total_list;
                $book->copy=json_encode($bookStock);
                $book->save();

                if($book->save()){
                    $bookStock=$this->bookStock->where('asn_no',$issueBook->asn_no)->first();
                    $bookStock->book_status=2;
                    $bookStock->save();
                }


                Session::flash('insert',"Book Issue Successfully");
            }
            return redirect()->back();
        }


    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function showReturnRenewBook()
    {
        $current=Carbon::now();
        $issueBooks=$this->issueBook->whereIn('status',[2,3])->orderByDesc('id')->paginate(10);
        return view('library::return-renew-book.index',compact('issueBooks','current'));
    }

    public function fineList()
    {
        $fineList=$this->issueBook->where('is_paid','=',1)->paginate(10);
        return view('library::library-fine-master.fine',compact('fineList'));

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('library::edit');
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
