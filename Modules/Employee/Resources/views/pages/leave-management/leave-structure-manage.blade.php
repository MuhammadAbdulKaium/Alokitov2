
@extends('layouts.master')

@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>Leave Structure</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Human Resource</a></li>
                <li><a href="#">Leave Management</a></li>
                <li class="active">Leave Structure</li>
            </ul>
        </section>
        <section class="content">
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('alert'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif


            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i>  Leave Structure </h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="/employee/manage/leave/structure/create" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square"></i> Add</a>
                        </div>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <div class="box-header">
                    </div>
                    <div class="box-body">
                        <table id="example1" class="table table-striped text-center">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Leave Structure</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Leave Type </th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @foreach($allLeaveStructure as $structure)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$structure->name}}</td>
                                    <td>{{date('d M, Y', strtotime($structure->start_date))}}</td>
                                    <td>{{date('d M, Y', strtotime($structure->end_date))}}</td>
                                    <td>
                                        @if($structure->structureLeaveTypes()->count()>0)
                                            <a href="/employee/manage/leave/structure/venus/edit/{{$structure->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><span class="label label-success">Update Leave Type</span></a>
                                        @else
                                            <a href="/employee/manage/leave/structure/venus/add/{{$structure->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><span class="label label-warning">Assign Leave Type</span></a>
                                        @endif
                                    </td>
                                    <td>
                                        @if($structure->status==1)
                                            @if($structure->structureLeaveTypes()->count()>0)
                                                <a href="/employee/manage/leave/structure/{{$structure->id}}/0" data-confirm="Are you sure you want to disapprove this?"><span class="label label-success">Approved</span></a>
                                            @else
                                                <p class="label label-default">Assign A Leave Type</p>
                                            @endif
                                        @else
                                            <a href="/employee/manage/leave/structure/{{$structure->id}}/1" data-confirm="Are you sure you want to approve this?"><span class="label label-danger">Pending</span></a>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="/employee/manage/leave/structure/edit/{{$structure->id}}" title="Update" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                                        <a href="{{ url('/employee/manage/leave/structure/delete/'.$structure->id) }}" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="get"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content" id="modal-content">
                        <div class="modal-body" id="modal-body">
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

@section('scripts')
    <!-- DataTables -->
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap-datepicker.js') }}"></script>
    <!-- datatable script -->
    <script>
        $(function () {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
        });



        jQuery(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
        });

    </script>
@endsection
