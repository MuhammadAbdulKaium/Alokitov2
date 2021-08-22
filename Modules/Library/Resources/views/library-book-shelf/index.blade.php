@extends('library::layouts.master')
@section('section-title')
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Book Shelf </small>        </h1>
        <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/library/default/index">Library</a></li>
            <li class="active">Book Shelf</li>
        </ul>

@endsection
<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-plus-square"></i> Create Book Shelf
            </h3>
        </div>


        <form id="bookShelf" action="/library/library-book-shelf/store" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="book_shelf_id" @if(!empty($bookShelfsProfile))  value="{{$bookShelfsProfile->id}}" @endif>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group field-librarycupboardmaster-lcm_name required">
                                <label class="control-label" for="librarycupboardmaster-lcm_name">Name</label>
                                <input id="librarycupboardmaster-lcm_name" required class="form-control" name="name" maxlength="100" aria-required="true" type="text" @if(!empty($bookShelfsProfile))  value="{{$bookShelfsProfile->name}}" @endif>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group field-librarycupboardmaster-lcm_details required">
                                <label class="control-label" for="librarycupboardmaster-lcm_details">Details</label>
                                <textarea id="librarycupboardmaster-lcm_details" required class="form-control" name="details" rows="2">@if(!empty($bookShelfsProfile)) {{$bookShelfsProfile->details}} @endif</textarea>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-create">Create</button>	<a class="btn btn-default btn-create" href="/library/library-cupboard-master/index">Cancel</a>
                </div>
                <!-- /.box-footer-->
            </form>
        </div>
        <div class="box box-solid">
            @if($bookShelfs->count()>0)
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> View Book Shelf</h3>
            </div>
            <div class="box-body table-responsive">
                <div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="10000">
                    <div id="w2" class="grid-view">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><a href="/library/library-cupboard-master/index?sort=lcm_name" data-sort="lcm_name">Name</a></th>
                                <th><a href="/library/library-cupboard-master/index?sort=lcm_details" data-sort="lcm_details">Details</a></th>
                                <th class="action-column">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($bookShelfs as $bookShelf)
                            <tr data-key="1">
                                <td>{{$bookShelf->id}}</td>
                                <td>{{$bookShelf->name}}</td>
                                <td>{{$bookShelf->details}}</td>
                                <td>
                                    <a href="{{URL::to("/library/library-book-shelf/edit",$bookShelf->id)}}"  class="btn btn-primary btn-xs" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <a  id="{{$bookShelf->id}}" class="btn btn-danger btn-xs delete_book_shelf" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><span class="glyphicon glyphicon-trash"></span></a>
                                </td>
                            </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @else

                <div class="container" style="margin-top: 20px">
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642; margin-top: 20px">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                    </div>
                </div>

                @endif
            <!-- /.box-body -->
        </div>



@endsection

@section('page-script')

    // Book Shelf Delete ajax request
    $('.delete_book_shelf').click(function(){
        var tr = $(this).closest('tr'),
            del_id = $(this).attr('id');

        $.ajax({
            url: "/library/library-book-shelf/delete/"+ del_id,
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

