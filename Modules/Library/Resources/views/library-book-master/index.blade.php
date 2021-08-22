@extends('library::layouts.master')
@section('section-title')
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Books </small>        </h1>
        <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/library/default/index">Library</a></li>
            <li class="active">Books</li>
        </ul>

@endsection
<!-- page content -->
@section('page-content')


        <div class="box box-solid">
            <div>
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-search"></i> Books
                    </h3>
                    <a class="btn btn-success pull-right" href="/library/library-book/create"><i class="fa fa-plus-square"></i> Add Book</a>
                </div>
            </div>
            @if(!empty($books))
            <div class="box-body table-responsive">
                <div id="w1" class="grid-view">
                    <div class="summary">Showing <b>1-1</b> of <b>1</b> item.</div>
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><a href="/library/library-book-master/index?sort=lbd_isbn_no" data-sort="lbd_isbn_no">ISBN No.</a></th>
                            <th><a href="/library/library-book-master/index?sort=lbd_title" data-sort="lbd_title">Book Name</a></th>
                            <th><a href="/library/library-book-master/index?sort=lbd_book_category" data-sort="lbd_book_category">Book Category</a></th>
                            <th><a href="/library/library-book-master/index?sort=lbd_cupboard" data-sort="lbd_cupboard">Cupboard</a></th>
                            <th><a href="/library/library-book-master/index?sort=lbd_cupboard_self" data-sort="lbd_cupboard_self">Cupboard Self</a></th>
                            <th><a href="/library/library-book-master/index?sort=lbd_author" data-sort="lbd_author">Author</a></th>
                            <th><a href="/library/library-book-master/index?sort=lbd_book_copy" data-sort="lbd_book_copy">Copy</a></th>
                            <th class="action-column">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($books as $book)
                        <tr data-key="1">
                            <td>{{$i++}}</td>
                            <td>{{$book->isbn_no}}</td>
                            <td>{{$book->name}}</td>
                            <td>{{$book->category()->name}}</td>
                            <td>{{$book->bookShelf()->name}}</td>
                            <td>{{$book->bookcup_board_shelf()->name}}</td>
                            <td>{{$book->author}}</td>
                            <td>{{$book->bookStock()->count()-$book->bookStockIssue()->count()}}</td>
                            <td><a href="/library/library-book/view/{{$book->id}}" title="View" aria-label="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <div class="paginate" style="float: right">
                        {{ $books->links() }}
                    </div>
                </div>
                @else
                    <div class="container" style="margin-top: 20px">
                        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                        </div>
                    </div>

                @endif
            </div>
            <!-- /.box-body -->
        </div>



@endsection

@section('page-script')




@endsection

