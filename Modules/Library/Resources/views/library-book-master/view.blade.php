@extends('library::layouts.master')
@section('section-title')
        <h1>
            <i class="fa fa-plus-square"></i> Add Book        </h1>
        <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/library/default/index">Library</a></li>
            <li><a href="/library/library-book-master/index">Manage Books</a></li>
            <li class="active">Add Book</li>
        </ul>


@endsection
<!-- page content -->
@section('page-content')

        <p class="text-right btn-view-group">
            <a class="btn btn-default btn-view btn-flat" href="/library/library-book-master/index"><i class="fa fa-chevron-circle-left"></i> Back</a>
            <a class="btn btn-info btn-flat btn-view" href="/library/library-book/update-book/{{$bookProfile->id}}"><i class="fa fa-pencil-square-o"></i> Update</a>
            <a class="btn btn-danger btn-flat btn-view" href="/library/library-book-master/delete-book?id=2" data-confirm="Are you sure you want to delete this item?" data-method="post"><i class="fa fa-trash-o"></i> Delete</a>
        </p>
        <div class="box">
            <div class="box-body no-padding table-responsive">
                <table id="w1" class="table table-striped table-bordered detail-view">
                    <tbody>
                    <tr class="odd">
                        <th class="col-sm-3">Book Name</th>
                        <td class="col-sm-3">{{$bookProfile->name}}</td>
                        <th class="col-sm-3">Subtitle</th>
                        <td class="col-sm-3">{{$bookProfile->subtitle}}</td>
                    </tr>
                    <tr class="even">
                        <th class="col-sm-3">Book Category</th>
                        <td class="col-sm-3">{{$bookProfile->category()->name}}</td>
                        <th class="col-sm-3">ISBN No.</th>
                        <td class="col-sm-3">{{$bookProfile->isbn_no}}</td>
                    </tr>
                    <tr class="odd">
                        <th class="col-sm-3">Author</th>
                        <td class="col-sm-3">{{$bookProfile->author}}</td>
                        <th class="col-sm-3">Publisher</th>
                        <td class="col-sm-3">{{$bookProfile->publisher}}</td>
                    </tr>
                    <tr class="even">
                        <th class="col-sm-3">Edition</th>
                        <td class="col-sm-3">{{$bookProfile->edition}}</td>
                        <th class="col-sm-3">Book Vendor</th>
                        <td class="col-sm-3">{{$bookProfile->book_vendor()->name}}</td>
                    </tr>
                    <tr class="odd">
                        <th class="col-sm-3">Cupboard</th>
                        <td class="col-sm-3">{{$bookProfile->bookShelf()->name}}</td>
                        <th class="col-sm-3">Cupboard Self</th>
                        <td class="col-sm-3">{{$bookProfile->bookcup_board_shelf()->name}}</td>
                    </tr>
                    <tr class="even">
                        <th class="col-sm-3">Copy</th>
                        <td class="col-sm-3">{{$bookCount}}</td>
                        <th class="col-sm-3">Book Cost</th>
                        <td class="col-sm-3">{{$bookProfile->book_cost}}</td>
                    </tr>
                    <tr class="odd">
                        <th class="col-sm-3">Remarks</th>
                        <td class="col-sm-3">{{$bookProfile->remark}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box-->
        <div class="box">
            <div class="box-header with-border">
                <h2 class="box-title">
                    <i class="fa fa-files-o"></i>
                    <b> Copies</b>
                </h2>
                <a class="btn btn-success btn-flat pull-right" href="/library/library-book-master/add-more-copy/{{$bookProfile->id}}" data-target="#globalModal" data-toggle="modal"><i class="fa fa-plus-square"></i> Add More</a>
            </div>
            @if($bookStocks->count()>0)
            <div class="box-body no-padding table-responsive with-border">
                <div id="barcode" class="grid-view">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>ASN No.</th>
                            <th>Barcode</th>
                            <th>Status</th>
                            <th class="action-column">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($bookStocks as $bookStock)
                        <tr data-key="11">
                            <td>{{$bookStock->id}}</td>
                            <td>{{$bookStock->asn_no}}</td>
                            <td><span><img src="http://iwatchsystems.com/technical/wp-content/uploads/2011/05/barcod.jpg" style="padding-left:50px;" height="30px" align="center" alt=""><br><span style="padding-left:50px;">{{$bookStock->barcode}}</span></span></td>
                            <td> @if($bookStock->book_status=="1") Avaliable @elseif($bookStock->book_status=="2") Issued @endif</td>
                            <td><a href="/library/library-book-master/delete?id=11" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <ul class="pagination">

                        {{ $bookStocks->links() }}
                     </ul>

                </div>

                @else
                    Not FOund

                @endif
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /.box-->
@endsection

@section('page-script')





@endsection

