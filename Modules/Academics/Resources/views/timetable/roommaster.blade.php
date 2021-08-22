@extends('layouts.master')

@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>Room</small>        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="">Academics</a></li>
                <li><a href="">Timetable</a></li>
                <li class="active">Room Master</li>
            </ul>    </section>
        @if(Session::has('message'))
            <div class="alert-success alert-auto-hide alert fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ Session::get('message') }} </h4>
            </div>
        @elseif(Session::has('warning'))
            <div class="alert-warning alert-auto-hide alert fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
            </div>

        @elseif(Session::has('success'))
            <div class="alert-success alert-auto-hide alert fade in">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
            </div>
        @endif
        <section class="content">


            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Room</h3>
                </div>

                <form id="room-master-form" action="/academics/roommaster/create" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group field-roommaster-rm_name required">
                                    <label class="control-label" for="roommaster-rm_name">Name</label>
                                    <input type="text" id="roommaster-rm_name" class="form-control" name="name" maxlength="255" aria-required="true">
                                    <input type="hidden" id="room_id" class="form-control" name="room_id"/>
                                    <div class="help-block"></div>
                                </div>		</div>
                            <div class="col-sm-6">
                                <div class="form-group field-roommaster-rm_category required">
                                    <label class="control-label" for="roommaster-rm_category">Category</label>
                                    <select id="category_id" class="form-control" name="category_id" aria-required="true">
                                        <option value="">--- Select Room Category ---</option>
                                        @if(isset($roomCategoryList))
                                            @foreach($roomCategoryList as $category)
                                                <option value="{{$category->id}}">{{$category->categoryname}}</option>
                                            @endforeach
                                        @endif
                                    </select>

                                    <div class="help-block"></div>
                                </div>		</div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group field-roommaster-rm_seat_capacity">
                                    <label class="control-label" for="roommaster-rm_seat_capacity">Seat Capacity</label>
                                    <input type="number" id="seat_capacity" class="form-control" name="seat_capacity">

                                    <div class="help-block"></div>
                                </div>		</div>
                            <div class="col-sm-6">
                                <div class="form-group field-roommaster-rm_location">
                                    <label class="control-label" for="roommaster-rm_location">Location</label>
                                    <input type="text" id="location" class="form-control" name="location" maxlength="255">

                                    <div class="help-block"></div>
                                </div>		</div>
                        </div>

                    </div><!-- /.box-body -->

                    <div class="box-footer">
                        <button type="submit" id="submitButton" class="btn btn-primary btn-create">Create</button>
                        <a href="/academics/roommaster"  class="btn btn-default btn-create">Reset</a>
                    </div><!-- /.box-footer-->
                </form>
            </div>
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> View Room List</h3>
                </div>
                <div class="box-body table-responsive">

                        <div id="w1" class="grid-view">
                            <table id="myTable1" class="table table-striped table-bordered display" width="100%">
                                <thead>
                                    <th>#</th>
                                    <th><a  data-sort="rm_name">Name</a></th>
                                    <th><a  data-sort="rm_category">Category</a></th>
                                    <th><a  data-sort="rm_seat_capacity">Seat Capacity</a></th>
                                    <th><a  data-sort="rm_location">Location</a></th>
                                    <th><a  data-sort="rm_location">Actions</a></th>
                                  </thead>
                                <tbody>
                                @if(isset($roomList))
                                    <?php $count = 1 ;?>
                                    @foreach($roomList as $room)
                                <tr data-key="{{$count}}">
                                    <td>{{$count}}</td>
                                    <td>{{$room->name}}</td>
                                    <td>{{$room->roomCategory()->categoryname}}</td>
                                    <td>{{$room->seat_capacity}}</td>
                                    <td>{{$room->location}}</td>
                                    <td>
                                        <a class="btn btn-primary btn-xs" href="javascript:updateRoom('{{$room->id}}','{{$room->name}}','{{$room->category_id}}','{{$room->seat_capacity}}','{{$room->location}}')" title="Update" aria-label="Update" data-pjax="0"><span class="fa fa-edit"></span></a>
                                        <a class="btn btn-danger btn-xs" href="/academics/roommaster/delete/{{$room->id}}" title="Delete" aria-label="Delete"  data-confirm="Are you sure you want to delete this item?" data-method="get"><span class="fa fa-trash-o"></span>

                                        </a>
                                    </td>
                                </tr>
                                <?php $count = $count + 1 ;?>
                                    @endforeach
                                 @endif
                                </tbody>
                            </table>
                        </div>
                </div><!-- /.box-body -->
            </div><!-- /.box-->
        </section>
    </div>

@endsection

@section('scripts')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

        <script type="text/javascript">
            $(document).ready(function(){
                $('#myTable1').dataTable();
                var validator = $("#room-master-form").validate({
                    // Specify validation rules
                    rules: {
                        name: {
                            required: true,
                            minlength: 1,
                            maxlength: 20,
                            lettersonlys: true,
                        },
                        category_id: {
                            required: true,
                        }

                    },

                    // Specify validation error messages
                    messages: {
                    },

                    submitHandler: function(form) {
                        form.submit();
                    }
                });

                jQuery('.alert-auto-hide').fadeTo(3500, 500, function () {
                    $(this).slideUp('slow', function () {
                        $(this).remove();
                    });
                });

            });

            function updateRoom(id, name,category_id,seat_capacity,location) {
                $('#room_id').val(id);
                $('#roommaster-rm_name').val(name);
                $('#roommaster-rm_name').focus();
                $('#seat_capacity').val(seat_capacity);
                $('#location').val(location);
                $("#category_id").val(category_id).change();
                $('#submitButton').text('Update');
            }
        </script>
@endsection

