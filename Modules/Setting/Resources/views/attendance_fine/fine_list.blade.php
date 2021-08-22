@extends('setting::layouts.master')

@section('section-title')

    <h1>
        <i class="fa fa-th-list"></i> Attendance  Setting |<small>  List</small></h1>
    <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Setting</a></li>
        <li class="active">Attendance Fine Setting</li>
    </ul>
    @endsection

    @section('page-content')
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @endif
    </div>
    <div class="box box-solid">
        <div>
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i>View Attendance Fine Setting List</h3>
                <div class="box-tools">
                    <a class="btn btn-success btn-sm" href="{{url('setting/attendance/create')}}">Add Attendance Fine Setting</a></div>
            </div>
        </div>
        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="myTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><a  data-sort="sub_master_code">Institute Name</a></th>
                            <th><a  data-sort="sub_master_alias">Campus Name</a></th>
                            <th><a  data-sort="sub_master_alias">Amount</a></th>
                            <th><a  data-sort="sub_master_alias">Setting Type</a></th>
                            <th><a  data-sort="sub_master_alias">From-Entry Time</a></th>
                            <th><a  data-sort="sub_master_alias">To-Entry Time</a></th>
                            <th><a  data-sort="sub_master_alias">Sorting Order</a></th>
                            <th><a>Action</a></th>
                        </tr>

                        </thead>
                        <tbody>

                        @if(!empty($attendanceFines))
                            @php

                                $i = 1
                            @endphp
                            @foreach($attendanceFines as $fine)

                                <tr class="gradeX">
                                    <td>{{$i++}}</td>
                                    <td>{{$fine->institute()->institute_name}}</td>
                                    <td>{{$fine->campus()->name}}</td>
                                    <td>{{$fine->amount}}</td>
                                    <td>
                                        @if($fine->setting_type=="ABSENT")
                                            ABSENT
                                        @elseif($fine->setting_type=="LATE_PRESENT_1")
                                            LATE_PRESENT ONE
                                        @elseif($fine->setting_type=="LATE_PRESENT_2")
                                            LATE_PRESENT TWO
                                        @endif

                                    </td>
                                    <td>{{$fine->form_entry_time}}</td>
                                    <td>{{$fine->to_entry_time}}</td>
                                    <td>{{$fine->sorting_order}}</td>

                                    <td>
                                        <a href="/setting/attendance/edit/{{$fine->id}}"  class="btn btn-primary btn-xs"   data-content="Edit"><i class="fa fa-pencil"></i></a>
                                        <a  id="{{$fine->id}}" class="btn btn-danger btn-xs delete_class"  data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        </tbody>
                    </table>

                </div>
            </div>
        </div><!-- /.box-body -->
    </div><!-- /.box-->
@endsection

@section('page-script')

    // fees setting delete ajax request
    $('.delete_class').click(function(){
        var x = confirm("Are you sure you want to delete?");
        if(x) {
            var tr = $(this).closest('tr'),
                del_id = $(this).attr('id');

            $.ajax({
                url: "/setting/attendance/delete/" + del_id,
                type: 'GET',
                cache: false,
                success: function (result) {
                    tr.fadeOut(1000, function () {
                        $(this).remove();
                    });
                }
            });

        }
    });









@endsection

