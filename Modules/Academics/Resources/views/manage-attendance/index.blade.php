
@extends('layouts.master')
<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-plus-square"></i> Manage Attendance
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">Student</a></li>
                <li><a href="#">Manage Attendance</a></li>
                <li class="active">Manage Subject</li>
            </ul>
        </section>
        <section class="content">
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif
            <div class="panel panel-default">
                <div class="panel-body">
                    <div>
                        <ul class="nav-tabs margin-bottom nav">
                            {{--checking user role--}}
                            @role(['super-admin','admin'])
                            <li @if($page == "manage") class="active" @endif>
                                <a href="{{url('/academics/manage/attendance/manage')}}">Attendance</a>
                            </li>
                            @endrole

                            {{--daily attendance--}}
                            {{-- <li @if($page == "daily-attendance") class="active" @endif>
                                <a href="{{url('/academics/manage/attendance/daily-attendance')}}">Daily Attendance</a>
                            </li> --}}

                            {{--checking user role--}}
                            @role(['super-admin','admin'])
                            @role(['super-admin'])
                            <li @if($page == "upload") class="active" @endif>
                                <a href="{{url('/academics/manage/attendance/upload')}}">Upload Daily Attendance</a>
                            </li>
                            @endrole
                            <li @if($page == "report") class="active" @endif>
                                <a href="{{url('/academics/manage/attendance/report')}}">Report</a>
                            </li>
                            <li @if($page == "settings") class="active" @endif>
                                <a href="{{url('/academics/manage/attendance/settings')}}">Settings</a>
                            </li>
                            @endrole
                        </ul>
                        <!-- page content div -->
                        @yield('page-content')
                    </div>
                </div>
            </div>
        </section>
    </div>

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
@endsection

@section('scripts')
    @yield('page-script')
@endsection
