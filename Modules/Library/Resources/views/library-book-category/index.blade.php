@extends('library::layouts.master')
@section('section-title')
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Book Category</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/library/default/index">Library</a></li>
            <li class="active">Book Category</li>
        </ul>

@endsection
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-plus-square"></i> Create Book Category
                    </h3>
                </div>

                <form id="bookCategory" action="/library/library-book-category/store" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="book_category_id" @if(!empty($bookCategoryProfile)) value="{{$bookCategoryProfile->id}}" @endif>
                    <div class="box-body">
                        <div class="form-group field-librarybookcategory-bc_name required">
                            <label class="control-label" for="librarybookcategory-bc_name">Book Category</label>
                            <input id="librarybookcategory-bc_name" class="form-control" name="name" maxlength="35" placeholder="Enter Book Category" aria-required="true" type="text" @if(!empty($bookCategoryProfile)) value="{{$bookCategoryProfile->name}}" @endif>
                            <p class="help-block help-block-error"></p>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-primary">Create</button>	<a class="btn btn-default " href="/library/library-book-category/index">Cancel</a>
                    </div>
                </form>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-search"></i> View Book Category
                    </h3>
                </div>
                @if($bookCategorys->count()>0)
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="10000">
                        <div id="w2" class="grid-view">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a href="/library/library-book-category/index?sort=bc_name" data-sort="bc_name">Book Category</a></th>
                                    <th class="action-column">&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($bookCategorys as $bookCategory)

                                <tr data-key="3">
                                    <td>{{$bookCategory->id}}</td>
                                    <td>{{$bookCategory->name}}</td>
                                    <td>
                                        <a href="{{URL::to("/library/library-book-category/edit",$bookCategory->id)}}"  class="btn btn-primary btn-xs" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>
                                        <a  id="{{$bookCategory->id}}" class="btn btn-danger btn-xs delete_book" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><span class="glyphicon glyphicon-trash"></span></a>
                                    </td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="paginate" style="float: right">
                        {{ $bookCategorys->links() }}
                    </div>


                @else
                    <div class="container" style="margin-top: 20px">
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                    </div>
                    </div>

                    @endif

                <!-- /.box-body -->

@endsection

 @section('page-script')

    // Book Category ajax request
    $('.delete_book').click(function(){
        var tr = $(this).closest('tr'),
            del_id = $(this).attr('id');

        $.ajax({
            url: "/library/library-book-category/delete/"+ del_id,
            type: 'GET',
            cache: false,
            success:function(result){
                tr.fadeOut(1000, function(){
                    $(this).remove();
                });
            }
        });
    });



@endsection

