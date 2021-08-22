@extends('library::layouts.master')
@section('section-title')
        <h1>
            <i class="fa fa-search"></i> Search Book        </h1>
        <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/library/default/index">Library</a></li>
            <li class="active">Search Book</li>
        </ul>

@endsection
<!-- page content -->
@section('page-content')

        <form id="w1" action="/library/library-borrow-transaction/search" method="get">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                                <div class="form-group field-librarybookdetailssearch-libbookmasters-lbm_id">
                                <label class="control-label" for="librarybookdetailssearch-libbookmasters-lbm_id">ASN No.</label>
                                <input id="librarybookdetailssearch-libbookmasters-lbm_id" class="form-control" name="asn_no" maxlength="255" type="text">

                                <div class="help-block"></div>
                            </div>            </div>
                        <div class="col-sm-4">
                            <div class="form-group field-librarybookdetailssearch-lbd_isbn_no">
                                <label class="control-label" for="librarybookdetailssearch-lbd_isbn_no">ISBN No.</label>
                                <input id="librarybookdetailssearch-lbd_isbn_no" class="form-control" name="isbn_no" maxlength="35" type="text">

                                <div class="help-block"></div>
                            </div>            </div>
                        <div class="col-sm-4">
                            <div class="form-group field-librarybookdetailssearch-lbd_title">
                                <label class="control-label" for="librarybookdetailssearch-lbd_title">Book Name</label>
                                <input id="librarybookdetailssearch-lbd_title" class="form-control ui-autocomplete-input" name="book_name" autocomplete="off" type="text">

                                <div class="help-block"></div>
                            </div>            </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group field-librarybookdetailssearch-lbd_author">
                                <label class="control-label" for="librarybookdetailssearch-lbd_author">Author</label>
                                <input id="librarybookdetailssearch-lbd_author" class="form-control ui-autocomplete-input" name="author_name" autocomplete="off" type="text">

                                <div class="help-block"></div>
                            </div>            </div>
                        <div class="col-sm-4">
                            <div class="form-group field-librarybookdetailssearch-lbd_subtitle">
                                <label class="control-label" for="librarybookdetailssearch-lbd_subtitle">Subtitle</label>
                                <input id="librarybookdetailssearch-lbd_subtitle" class="form-control" name="subtitle" maxlength="100" type="text">

                                <div class="help-block"></div>
                            </div>            </div>
                        <div class="col-sm-4">
                            <div class="form-group field-librarybookdetailssearch-libbookmasters-lbm_status">
                                <label class="control-label" for="librarybookdetailssearch-libbookmasters-lbm_status">Status</label>
                                <select id="librarybookdetailssearch-libbookmasters-lbm_status" class="form-control" name="book_status">
                                    <option value="">--- Select Book Status ---</option>
                                    <option value="1">Available</option>
                                    <option value="2">Issued</option>
                                    <option value="3">Renew</option>
                                </select>

                                <div class="help-block"></div>
                            </div>            </div>
                    </div>
                </div><!-- /.box-body -->

                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-create">Search</button>        <a class="btn btn-default btn-create" href="/library/library-borrow-transaction/index">Reset</a>    </div>
            </div>

        </form>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-eye"></i> View Books</h3>
            </div>

            @if(!empty($books))
            <div class="box-body table-responsive">
                <div id="w2" class="grid-view">
                    <table class="table table-striped table-bordered"><thead>
                        <tr><th>#</th><th><a href="/library/library-borrow-transaction/index?sort=lbd_isbn_no" data-sort="lbd_isbn_no">ISBN No.</a></th>
                            <th><a href="/library/library-borrow-transaction/index?sort=lbd_title" data-sort="lbd_title">Book Name</a></th>
                            <th><a href="/library/library-borrow-transaction/index?sort=lbd_book_category" data-sort="lbd_book_category">Book Category</a></th>
                            <th><a href="/library/library-borrow-transaction/index?sort=lbd_author" data-sort="lbd_author">Author</a></th>
                            <th class="text-center">Available</th>
                            <th class="text-center">Action</th></tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($books as $book)
                        <tr data-key="1">
                            <td>{{$i++}}</td>
                            <td>{{$book->isbn_no}}</td>
                            <td><a href="/library/library-book/view/{{$book->id}}" target="_blank">{{$book->name}}</a></td>
                            <td>{{$book->category()->name}}</td>
                            <td>{{$book->author}}</td>
                            <td>{{$book->bookStock()->count()-$book->bookStockIssue()->count()}}</td>
                            <td class="text-center"><a href="/library/library-borrow-transaction/borrow-book/{{$book->id}}" title="Issue Book" data-target="#globalModal" data-toggle="modal"><span class="fa fa-caret-square-o-down fa-lg"></span></a></td>
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

        </div>

@endsection

@section('page-script')




@endsection

