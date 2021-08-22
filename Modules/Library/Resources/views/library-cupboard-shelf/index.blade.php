@extends('library::layouts.master')
@section('section-title')
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Cup Board Shelf </small>        </h1>
        <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/library/default/index">Library</a></li>
            <li class="active">Cup Board Shelf</li>
        </ul>

@endsection
<!-- page content -->
@section('page-content')
    <!-- grading scale -->

    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-plus-square"></i> Create Cup Board Shelf
            </h3>
        </div>

        <form id="cupBoardShelf" action="/library/library-cupboard-shelf/store" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="cup_board_shelf_id" @if(!empty($cupBoardShelfProifle->id)) value="{{$cupBoardShelfProifle->id}}" @endif >
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group field-librarycupboardshelf-lcs_name required">
                                <label class="control-label" for="librarycupboardshelf-lcs_name">Name</label>
                                <input id="librarycupboardshelf-lcs_name" class="form-control" required name="name" maxlength="100" aria-required="true" type="text" @if(!empty($cupBoardShelfProifle->name)) value="{{$cupBoardShelfProifle->name}}" @endif>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group field-librarycupboardshelf-lcs_cupboard_id required">
                                <label class="control-label" for="librarycupboardshelf-lcs_cupboard_id">Cupboard</label>
                                <select id="librarycupboardshelf-lcs_cupboard_id" class="form-control" required name="book_shelf_name" aria-required="true">
                                    <option value="">--- Select Cup Board ---</option>
                                   @if($bookShelfs->count()>0)
                                        @foreach($bookShelfs as $bookShelf)
                                            @if (!empty($cupBoardShelfProifle->book_shelf_id))
                                                @if($bookShelf->id==$cupBoardShelfProifle->book_shelf_id)
                                                <option value="{{ $bookShelf->id }}" selected>{{ $bookShelf->name }}</option>
                                            @else
                                                <option value="{{ $bookShelf->id }}">{{ $bookShelf->name }}</option>
                                            @endif

                                            @else
                                                <option value="{{ $bookShelf->id }}">{{ $bookShelf->name }}</option>
                                            @endif

                                        @endforeach
                                       @endif


                                </select>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group field-librarycupboardshelf-lcs_cupboard_capacity required">
                                <label class="control-label" for="librarycupboardshelf-lcs_cupboard_capacity">Capacity</label>
                                <input id="librarycupboardshelf-lcs_cupboard_capacity" required class="form-control" name="capacity" type="text" @if(!empty($cupBoardShelfProifle->capacity)) value="{{$cupBoardShelfProifle->capacity}}" @endif>
                                <div class="help-block"></div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group field-librarycupboardshelf-lcs_details required">
                                <label class="control-label" for="librarycupboardshelf-lcs_details">Details</label>
                                <textarea id="librarycupboardshelf-lcs_details" required class="form-control" name="details" rows="2">@if(!empty($cupBoardShelfProifle->details)) {{$cupBoardShelfProifle->details}} @endif</textarea>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary btn-create">Create</button>	<a class="btn btn-default btn-create" href="/library/library-cupboard-shelf/index">Cancel</a>
                </div>
                <!-- /.box-footer-->
            </form>
        </div>
        <div class="box box-solid">
            @if($cupBoardShelfs->count()>0)
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> View Cup Board Shelf</h3>
            </div>
            <div class="box-body table-responsive">
                <div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="10000">
                    <div id="w2" class="grid-view">
                        <table class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><a href="/library/library-cupboard-shelf/index?sort=lcs_name" data-sort="lcs_name">Boook Shelf Name</a></th>
                                <th><a href="/library/library-cupboard-shelf/index?sort=lcs_cupboard_id" data-sort="lcs_cupboard_id">Cupboard</a></th>
                                <th><a href="/library/library-cupboard-shelf/index?sort=lcs_cupboard_capacity" data-sort="lcs_cupboard_capacity">Capacity</a></th>
                                <th><a href="/library/library-cupboard-shelf/index?sort=lcs_details" data-sort="lcs_details">Details</a></th>
                                <th class="action-column">&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($cupBoardShelfs as $cupBoardShelf)
                            <tr data-key="1">
                                <td>{{$cupBoardShelf->id}}</td>
                                <td>{{$cupBoardShelf->bookShelf()->name}}</td>
                                <td>{{$cupBoardShelf->name}}</td>
                                <td>{{$cupBoardShelf->capacity}}</td>
                                <td>{{$cupBoardShelf->details}}</td>
                                <td>
                                    <a href="{{URL::to("/library/library-cupboard-shelf/edit",$cupBoardShelf->id)}}"  class="btn btn-primary btn-xs" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <a  id="{{$cupBoardShelf->id}}" class="btn btn-danger btn-xs delete_cupboard_shelf" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><span class="glyphicon glyphicon-trash"></span></a>

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

    // Book Category ajax request
    $('.delete_cupboard_shelf').click(function(){
        var tr = $(this).closest('tr'),
            del_id = $(this).attr('id');

        $.ajax({
            url: "/library/library-cupboard-shelf/delete/"+ del_id,
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

