
@extends('admin::layouts.master')

@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>HighAdmin</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Settings</a></li>
                <li><a href="#">Manage Users</a></li>
                <li class="active">HighAdmin</li>
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
                        <h3 class="box-title"><i class="fa fa-search"></i> Users ( HighAdmin ) </h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="{{url('/admin/manage/users/uno/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
                                <i class="fa fa-plus-square"></i> Add HighAdmin
                            </a>
                        </div>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table id="example1" class="table table-bordered table-responsive table-striped text-center">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    {{--<th>Created_at</th>--}}
                                    <th>Institute</th>
                                    <th>Action</th>
                                </tr>
                                </thead>

                                <tbody>
                                @if($unoUsersList->count()>0)
                                    @foreach($unoUsersList as $index=>$unoUser)
                                        @php $userProfile = $unoUser->user();  @endphp
                                        <tr>
                                            <td>{{($index+1)}}</td>
                                            <td>{{$userProfile->name}}</td>
                                            <td>{{$userProfile->email}}</td>
                                            {{--<td>{{$userProfile->created_at->format('d M, Y (l) - h:s:i a')}}</td>--}}
                                            <td>
                                                <a title="Edit" href="{{url('/admin/manage/users/uno/institute/assign/'.$userProfile->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Assign</a>
                                                /
                                                <a title="Edit" href="{{url('/admin/manage/users/uno/institute/list/'.$userProfile->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">View</a>
                                            </td>
                                            {{--action td--}}
                                            <td>
                                            <a title="Edit" href="{{url('/admin/manage/users/uno/edit/'.$userProfile->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a>
                                            <a href="#" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="get">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                            </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr><td colspan="5">No records found</td></tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
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
    <!-- datatable script -->
    <script>

        jQuery(document).ready(function () {

            $("#example2").DataTable();
            $('#example1').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": false,
                "info": true,
                "autoWidth": true
            });

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

        });

    </script>
@endsection
