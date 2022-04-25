@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Class</small></h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Academics</a></li>
                <li class="active">Course Management</li>
                <li class="active">Class</li>
            </ul>
        </section>
        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
            @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide dism " style="text-align: center">
                    <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}
                </p>
            @endif
            @if(Session::has('error'))
                <p class="alert alert-danger alert-auto-hide dism " style="text-align: center">
                    <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('error') }}
                </p>
            @endif
        </div>
        <section class="content">
            <div class="box box-solid">
                <div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i>View Classes List</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="{{url('academics/add-batch')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Add</a>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <div id="w1" class="grid-view">
                            <table id="myTable" class="table table-striped table-bordered text-center">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    {{--<th><a  data-sort="sub_master_name">Academic Year</a></th>--}}
                                    <th><a  data-sort="sub_master_code">Academic Level Name</a></th>
                                    <th><a  data-sort="sub_master_alias">Class Name</a></th>
                                    <th><a  data-sort="sub_master_alias">Class Alias</a></th>
                                    <th><a  data-sort="sub_master_alias">Group</a></th>
                                    {{-- <th><a  data-sort="sub_master_alias">Start Date</a></th>
                                    <th><a  data-sort="sub_master_alias">End Date</a></th> --}}
                                    <th><a  data-sort="sub_master_alias">Status</a></th>
                                    <th><a>Action</a></th>
                                </tr>

                                </thead>
                                <tbody>

                                @if(isset($batches))
                                    @php

                                        $i = 1
                                    @endphp
                                    @foreach($batches as $values)

                                        <tr class="gradeX">
                                            <td>{{$i++}}</td>
                                            {{--<td>{{$values->academicsYear()->year_name}}</td>--}}
                                            <td>@if ($values->academicsLevel()){{$values->academicsLevel()->level_name}}@endif</td>
                                            <td>{{$values->batch_name}}</td>
                                            <td>{{$values->batch_alias}}</td>
                                            {{-- <td>{{isset($values->division_id)?$values->division()->name:'-'}}</td> --}}
                                            <td>
                                                @foreach ($values->divisions as $division)
                                                    <div class="badge badge-info">{{ $division->name }}</div>
                                                @endforeach    
                                            </td>
                                            {{-- <td>{{date('m-d-Y',strtotime($values->start_date))}}</td>
                                            <td>{{date('m-d-Y',strtotime($values->end_date))}}</td> --}}
                                            <td>
                                                <a href="{{ url('academics/batch-status-change', $values->id) }}" class="btn btn-xs" onclick="return confirm('Are you sure to Change Status?')" data-placement="top" data-content="delete">
                                                    @if($values->status==1) <i class="fa fa-check" style="color:#0FFC45;" ></i>@endif
                                                    @if($values->status==0) <i class="fa fa-times" style="color:#F75432;"></i>@endif
                                                </a>
                                            </td>
                                            <td>
                                                <a href="" class="btn btn-primary btn-xs" id="batch_view_{{$values->id}}" onclick="modalLoad(this.id)" data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="view">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="" id="batch_edit_{{$values->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModal"  data-toggle="modal" data-placement="top" data-content="update">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <a href="{{ url('academics/delete-batch', $values->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
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
        </section>
    </div>

    <div class="modal"  id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')


    <script type = "text/javascript">
        function modalLoad(rowId) {

            var data = rowId.split('_'); //To get the row id

            //   alert(data);
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                url: "{{ url('academics/view-batch') }}" + '/' + data[2],
                type: 'GET',
                cache: false,
                data: {'_token': $_token}, //see the $_token
                datatype: 'html',

                beforeSend: function () {
                },

                success: function (data) {

                    // alert(data.length);
//                    $('.modal-content').html(data);
                    if (data.length > 0) {
                        // remove modal body
                        $('.modal-body').remove();
                        // add modal content
                        $('.modal-content').html(data);
                    } else {
                        // add modal content
                        $('.modal-content').html('info');
                    }
                }
            });

        }
        function modalLoadEdit(rowId) {

            var data = rowId.split('_'); //To get the row id

            //alert(data);
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {'X-CSRF-Token': $('meta[name=_token]').attr('content')},
                url: "{{ url('academics/edit-batch-view') }}" + '/' + data[2],
                type: 'GET',
                cache: false,
                data: {'_token': $_token}, //see the $_token
                datatype: 'html',

                beforeSend: function () {
                },

                success: function (data) {

                    // alert(data.length);
//                    $('.modal-content').html(data);
                    if (data.length > 0) {
                        // remove modal body
                        $('.modal-body').remove();
                        // add modal content
                        $('.modal-content').html(data);
                    } else {
                        // add modal content
                        $('.modal-content').html('info');
                    }
                }
            });

        }




    </script>


    <script type="text/javascript">

        $(document).ready(function(){
            $('#myTable').DataTable();
        });

        jQuery(document).ready(function() {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });
        });


        jQuery('#start_date').datepicker({"changeMonth":true,"changeYear":true,"defaultValue":null,"defaultDate":null,"dateFormat":"dd-mm-yy"});
        jQuery('#end_date').datepicker({"changeMonth":true,"changeYear":true,"defaultValue":null,"defaultDate":null,"dateFormat":"dd-mm-yy"});

    </script>
@endsection

    