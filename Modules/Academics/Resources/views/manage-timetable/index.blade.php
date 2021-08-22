
@extends('layouts.master')

<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-plus-square"></i> Manage Timetable
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/">Academics</a></li>
                <li class="active">Manage Timetable</li>
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
                            <li @if($page == "timetable") class="active" @endif><a href="/academics/timetable/timetable">Timetable</a></li>
                            <li @if($page == "manage") class="active" @endif><a href="/academics/timetable/manage">Manage</a></li>
                            <li @if($page == "settings") class="active" @endif><a href="/academics/timetable/settings">Settings</a></li>
                            <li @if($page == "routine") class="active" @endif><a href="/academics/timetable/routine">Routine</a></li>
                            <li @if($page == "class-teacher-assign") class="active" @endif><a href="/academics/timetable/class-teacher-assign">Class Teacher Assign</a></li>
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
