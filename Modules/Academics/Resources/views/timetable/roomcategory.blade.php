@extends('layouts.master')

<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Room Category</small>
            </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Academics</a></li>
                <li><a href="#">Timetable</a></li>
                <li class="active">Room Category</li>
            </ul>
        </section>
        @if(Session::has('message'))
            <div class="alert alert-success alert-dismissible alert-auto-hide">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ Session::get('message') }} </h4>
            </div>
        @elseif(Session::has('warning'))
            <div class="alert alert-warning alert-dismissible alert-auto-hide">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
            </div>

        @elseif(Session::has('success'))
            <div class="alert alert-success alert-auto-hide">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
            </div>
        @endif
        <section class="content">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-pencil-square"></i> Create Room Category | Class Room</h3>
                </div>

                <form id="room-category-form" action="{{url('academics/roomcategory/store')}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="form-group field-roomcategory-rc_name required">
                            <label class="control-label" for="roomcategory-rc_name">Category</label>
                            <input type="text" id="roomcategory-rc_name" required class="form-control" name="roomcategoryname" value="" maxlength="100" aria-required="true">
                            <input type="hidden" id="roomcategory-id" required class="form-control" name="roomcategory-id" value="" maxlength="100" aria-required="true">

                            <div class="help-block"></div>
                        </div></div><!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" id="submitButton" class="btn btn-info btn-create">Create</button>    	<a class="btn btn-default btn-create" href="/academics/roomcategory">Cancel</a>    </div><!-- /.box-footer-->

                </form>
            </div>

            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> View Room Categories List</h3>
                </div>
                <div class="box-body table-responsive">

                    <div id="w1" class="grid-view">

                        <table id="myTable" class="table table-striped table-bordered display" width="100%">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th><a data-sort="sub_master_name">Category</a></th>
                                    <th><a data-sort="sub_master_name">Action</a></th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($roomCategoryList))
                                    <?php $count =1;?>
                                    @foreach($roomCategoryList as $roomcategory)
                                        <tr>
                                            <td>{{$count}}</td>
                                            <td>{{$roomcategory->categoryname}}</td>
                                            <td>
                                                <a class="btn btn-primary btn-xs" href="javascript:updateRoomCategory('{{$roomcategory->id}}','{{$roomcategory->categoryname}}')" title="Update" aria-label="Update" data-pjax="0"><span class="fa fa-edit"></span></a>
                                                <a class="btn btn-danger btn-xs" href="/academics/roomcategory/delete/{{$roomcategory->id}}" title="Delete" aria-label="Delete"  data-confirm="Are you sure you want to delete this item?" data-method="get"><span class="fa fa-trash-o"></span>

                                                </a>
                                            </td>
                                            <?php $count++;?>
                                        </tr>
                                    @endforeach

                                @endif
                                </tbody>
                        </table>
                    </div>
                </div>
            </div>    <!-- /.box-body -->
            <!-- /.box-->
        </section>
    </div>
@endsection

@section('scripts')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>
    <script type="text/javascript">
        $(document).ready(function(){

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

            $('#myTable').dataTable();
        });
        function updateRoomCategory(id, name) {
            $('#roomcategory-id').val(id);
            $('#roomcategory-rc_name').val(name);
            $('#roomcategory-rc_name').focus();
            $('#submitButton').text('Update');
        }
    </script>
@endsection
