@extends('layouts.master')

@section('content')
    @if(Session::has('message'))
        <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
            style="text-decoration:none" data-dismiss="alert"
            aria-label="close">&times;</a>{{ Session::get('message') }}</p>
    @elseif(Session::has('alert'))
        <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
    @elseif(Session::has('errorMessage'))
        <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
    @endif
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>Events</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="">Events</a></li>
                <li>SOP Setup</li>
                <li class="active">Set Up Events</li>
            </ul>
        </section>

        <section class="content">
            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> View Event List</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="{{url('/event/event/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Add</a>
                        </div>
                    </div>
                </div>

                <div class="box-body table-responsive">

                    <div id="w1" class="grid-view">
                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><a  data-sort="sub_master_name">Event Name</a></th>
                                <th><a  data-sort="sub_master_code">Category</a></th>
                                <th><a  data-sort="sub_master_alias">Sub Category</a></th>
                                <th><a  data-sort="sub_master_alias">Activity</a></th>
                                <th><a  data-sort="sub_master_alias">Remarks</a></th>
                                <th><a  data-sort="sub_master_alias">Status</a></th>
                                <th><a  data-sort="sub_master_alias">Judge</a></th>

                                <th><a>Action</a></th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$event->event_name}}</td>
                                        <td>{{$event->category->performance_type}}</td>
                                        <td>{{$event->sub_category->category_name}}</td>
                                        <td>{{$event->activity->activity_name}}</td>
                                        <td>{{$event->remarks}}</td>
                                        @if($event->status==1)
                                            <td>{{'Open'}}</td>
                                        @else
                                            <td>{{'Close'}}</td>
                                        @endif
                                        <td>
                                            @php
                                                $employee_ids=json_decode($event->employee_id,true);
                                            @endphp
                                            @foreach($employee_ids as $em)
                                                @php
                                                    $employee = $employees->find($em);
                                                @endphp
                                                <span class="badge">
                                                    {{$employee->first_name}} {{$employee->last_name}}
                                                </span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a class="btn btn-success btn-xs"
                                               href="{{url('/event/assign/date/'.$event->id)}}"
                                               data-target="#globalModal" data-toggle="modal"
                                               data-modal-size="modal-lg">A</a>
                                            @if ($event->status)
                                                <a class="btn btn-info btn-xs"
                                                href="{{url('/event/edit/team/'.$event->id)}}"
                                                data-target="#globalModal" data-toggle="modal"
                                                data-modal-size="modal-lg">T</a>
                                            @endif
                                            <a class="btn btn-primary btn-xs"
                                               href="{{url('/event/edit/'.$event->id)}}"
                                               data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i
                                                        class="fa fa-edit"></i></a>
                                            <a href="{{ url('/event/delete/'.$event->id) }}"
                                               class="btn btn-danger btn-xs"
                                               onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                               data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->

            <!-- /.box-->


            <!-- global modal -->
            <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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
        </section>
    </div>

@endsection


{{--Scripts--}}

@section('scripts')
    <script src="{{ asset('js/multiselect.min.js') }}"></script>
    <script src="{{ asset('js/datatable.js') }}" type="text/javascript"></script>
    <script>
        $(document).ready(function(){
            $('#myTable').DataTable();

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });
        });
    </script>
@endsection