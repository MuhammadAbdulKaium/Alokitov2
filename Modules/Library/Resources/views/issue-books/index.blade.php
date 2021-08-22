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
        <form id="w1" action="/library/library-borrow-transaction/index" method="get">
            <div class="box box-solid">
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group field-librarybookdetailssearch-libbookmasters-lbm_id">
                                <label class="control-label" for="librarybookdetailssearch-libbookmasters-lbm_id">ASN No.</label>
                                <input id="librarybookdetailssearch-libbookmasters-lbm_id" class="form-control" name="LibraryBookDetailsSearch[libBookMasters.lbm_id]" maxlength="255" type="text">

                                <div class="help-block"></div>
                            </div>            </div>
                        <div class="col-sm-4">
                            <div class="form-group field-librarybookdetailssearch-lbd_isbn_no">
                                <label class="control-label" for="librarybookdetailssearch-lbd_isbn_no">ISBN No.</label>
                                <input id="librarybookdetailssearch-lbd_isbn_no" class="form-control" name="LibraryBookDetailsSearch[lbd_isbn_no]" maxlength="35" type="text">

                                <div class="help-block"></div>
                            </div>            </div>
                        <div class="col-sm-4">
                            <div class="form-group field-librarybookdetailssearch-lbd_title">
                                <label class="control-label" for="librarybookdetailssearch-lbd_title">Book Name</label>
                                <input id="librarybookdetailssearch-lbd_title" class="form-control ui-autocomplete-input" name="LibraryBookDetailsSearch[lbd_title]" autocomplete="off" type="text">

                                <div class="help-block"></div>
                            </div>            </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group field-librarybookdetailssearch-lbd_author">
                                <label class="control-label" for="librarybookdetailssearch-lbd_author">Author</label>
                                <input id="librarybookdetailssearch-lbd_author" class="form-control ui-autocomplete-input" name="LibraryBookDetailsSearch[lbd_author]" autocomplete="off" type="text">

                                <div class="help-block"></div>
                            </div>            </div>
                        <div class="col-sm-4">
                            <div class="form-group field-librarybookdetailssearch-lbd_subtitle">
                                <label class="control-label" for="librarybookdetailssearch-lbd_subtitle">Subtitle</label>
                                <input id="librarybookdetailssearch-lbd_subtitle" class="form-control" name="LibraryBookDetailsSearch[lbd_subtitle]" maxlength="100" type="text">

                                <div class="help-block"></div>
                            </div>            </div>
                        <div class="col-sm-4">
                            <div class="form-group field-librarybookdetailssearch-libbookmasters-lbm_status">
                                <label class="control-label" for="librarybookdetailssearch-libbookmasters-lbm_status">Status</label>
                                <select id="librarybookdetailssearch-libbookmasters-lbm_status" class="form-control" name="LibraryBookDetailsSearch[libBookMasters.lbm_status]">
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
            <div class="box-body table-responsive">
                <div id="w2" class="grid-view">
                    <table class="table table-striped table-bordered"><thead>
                        <tr><th>#</th><th><a href="/library/library-borrow-transaction/index?sort=lbd_isbn_no" data-sort="lbd_isbn_no">ISBN No.</a></th><th><a href="/library/library-borrow-transaction/index?sort=lbd_title" data-sort="lbd_title">Book Name</a></th><th><a href="/library/library-borrow-transaction/index?sort=lbd_book_category" data-sort="lbd_book_category">Book Category</a></th><th><a href="/library/library-borrow-transaction/index?sort=lbd_author" data-sort="lbd_author">Author</a></th><th class="text-center">Available</th><th class="text-center">Action</th></tr>
                        </thead>
                        <tbody>
                        <tr data-key="1"><td>1</td><td style="width:150px">1234567890</td><td><a href="/library/library-book-master/view?id=1" target="_blank">Grade1-English</a></td><td>Text Book</td><td></td><td class="text-center">6</td><td class="text-center"><a href="/library/library-borrow-transaction/borrow-book?id=1" title="Issue Book" data-target="#globalModal" data-toggle="modal"><span class="fa fa-caret-square-o-down fa-lg"></span></a></td></tr>
                        </tbody></table>
                </div>	</div><!-- /.box-body -->
        </div><!-- /.box-->


@endsection

@section('page-script')




@endsection

