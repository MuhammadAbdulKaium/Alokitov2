@extends('layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-eye" aria-hidden="true"></i> View Employee | <small>{{$employeeInfo->title." ".$employeeInfo->first_name." ".$employeeInfo->last_name}}</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/employee">Employee</a></li>
                <li><a href="/employee/manage">Manage Employee</a></li>
                <li class="active">{{$employeeInfo->title." ".$employeeInfo->first_name." ".$employeeInfo->last_name}}s</li>
            </ul>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-3">
                    @include('employee::pages.includes.profile-sidebar')
                </div>
                <div class="col-md-9">
                    @if(Session::has('success'))
                        <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                        </div>
                    @elseif(Session::has('message'))
                        <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> {{ Session::get('message') }} </h4>
                        </div>
                    @elseif(Session::has('warning'))
                        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                        </div>
                    @elseif(Session::has('errorMessage'))
                        <div id="w0-success-0" class="alert-danger alert-auto-hide alert fade in" style="opacity: 423.642;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h4><i class="icon fa fa-check"></i> {{ Session::get('errorMessage') }} </h4>
                        </div>
                    @endif
                    <div class="panel panel-default">
                        <div class="panel-body">
                            <div id="emp-profile">
                                <ul id="w1" class="nav-tabs margin-bottom nav">
                                <li class="@if($page == 'personal')active @endif"><a href="/employee/profile/personal/{{$employeeInfo->id}}">Personal</a></li>
                                <li class="@if($page == 'address')active @endif"><a href="/employee/profile/address/{{$employeeInfo->id}}">Address</a></li>
                                <li class="@if($page == 'guardian')active @endif"><a href="/employee/profile/guardian/{{$employeeInfo->id}}">Family</a></li>
                                <li class="@if($page == 'others')active @endif"><a href="#">Other Info</a></li>
                                <li class="@if($page == 'document')active @endif"><a href="/employee/profile/document/{{$employeeInfo->id}}">Documents</a></li>
                                <li class="@if($page == 'qualification')active @endif"><a href="/employee/profile/qualification/{{$employeeInfo->id}}">Qualification</a></li>
                                <li class="@if($page == 'experience')active @endif"><a href="/employee/profile/experience/{{$employeeInfo->id}}">Experience</a></li>
                                <li class="@if($page == 'shifts')active @endif"><a href="">Shift</a></li>
                                <li class="@if($page == 'leave')active @endif"><a href="#">Leave</a></li>
                                <li class="@if($page == 'salary')active @endif"><a href="#">Salary</a></li>
                                </ul>
                                @yield('profile-content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
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
@endsection

@section('scripts')
    <script type = "text/javascript">
        jQuery(document).ready(function () {

            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
        });
    </script>
    @yield('profile-scripts')
@endsection
