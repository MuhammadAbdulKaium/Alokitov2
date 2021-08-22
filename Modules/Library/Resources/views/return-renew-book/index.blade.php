

@extends('library::layouts.master')
@section('section-title')
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Return/Renew Book</small>        </h1>
        <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/library/default/index">Library</a></li>
            <li class="active">Return/Renew Book</li>
        </ul>


@endsection
<!-- page content -->
@section('page-content')
        <div class="box box-solid">

            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-eye"></i> View Issuer/Borrower		</h3>
            </div>
            @if(!empty($issueBooks))
            <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered"><thead>
                            <tr><th>#</th><th><a href="/library/library-borrow-transaction/borrower?sort=lbtBookMaster.lbm_id" data-sort="lbtBookMaster.lbm_id">ASN No.</a></th><th><a href="/library/library-borrow-transaction/borrower?sort=lbt_book_master_id" data-sort="lbt_book_master_id">Book Name</a></th><th><a href="/library/library-borrow-transaction/borrower?sort=libraryBooks.lbd_isbn_no" data-sort="libraryBooks.lbd_isbn_no">ISBN No.</a></th><th><a href="/library/library-borrow-transaction/borrower?sort=lbt_holder_id" data-sort="lbt_holder_id">Holder Name</a></th><th><a href="/library/library-borrow-transaction/borrower?sort=lbt_holder_type" data-sort="lbt_holder_type">Holder Type</a></th><th><a href="/library/library-borrow-transaction/borrower?sort=lbt_issue_date" data-sort="lbt_issue_date">Issue Date</a></th><th><a href="/library/library-borrow-transaction/borrower?sort=lbt_due_date" data-sort="lbt_due_date">Due Date</a></th><th>Duration</th><th class="action-column">Action</th></tr><tr id="w1-filters" class="filters"></tr>
                            </thead>
                            <tbody>
        @php  $i=1; @endphp
                            @foreach($issueBooks as $book)
                              <tr>
                                <td>{{$i}}</td>
                                <td>{{$book->asn_no}}</td>
                                <td>{{$book->book()->name}}</td>
                                <td>{{$book->isbn_no}}</td>
                                 @php $std=$book->student(); $employee=$book->employee(); @endphp
                                  <td>
                                      @if($book->holder_type=="1")
                                         @if(!empty($std)) {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}} @endif</td>
                                  @elseif($book->holder_type=="2")
                                     @if(!empty($employee)) {{$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name}} @endif</td>

                                  @endif
                                  <td>
                                      @if($book->holder_type=="1")
                                          Student
                                          @elseif($book->holder_type=="2")
                                          Employee
                                      @endif

                                  </td>
                                <td>{{date('d-m-Y',strtotime($book->issue_date))}}</td>
                                <td>{{date('d-m-Y',strtotime($book->due_date))}}</td>

                                  @php

                                      $startDate = \Carbon\Carbon::parse($book->issue_date);
                                      $endDate   = \Carbon\Carbon::parse($book->due_date);
                                      $dateDiff = $current->diff($endDate)->format("%a");
                                      $fine=($book->daily_fine)*($dateDiff);

                                  @endphp

                                <td>
                                    @if($endDate>=date('Y-m-d'))
                                        <span class="label label-success" style="font-size:12px">{{$dateDiff}} day(s) left</span>

                                    @else
                                        <span class="label label-danger" style="font-size:12px">Overdue {{$dateDiff}} day(s) Fine {{$fine}} Taka</span>

                                  @endif

                                <td><a href="/library/library-borrow-transaction/return-book/{{$book->id}}" title="Return Book" data-pjax="0" data-target="#globalModal" data-toggle="modal"><span class="fa fa-reply-all"></span></a>
                                    <a href="/library/library-borrow-transaction/update/{{$book->id}}" title="Update Book" data-pjax="0" data-target="#globalModal" data-toggle="modal"><span class="glyphicon glyphicon-pencil"></span></a></td>
                            </tr>
                                @endforeach


                            </tbody></table>
                    	</div>
            @else
                <div class="container" style="margin-top: 20px">
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                    </div>
                </div>

            @endif



        </div><!-- /.box-body -->



        </div><!-- /.box-->

@endsection

@section('page-script')




@endsection

