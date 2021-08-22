@extends('layouts.master')
<!-- page content -->
@section('content')
    <link href="{{ asset('css/jquery.timepicker.min.css') }}" rel="stylesheet" type="text/css"/>

    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-plus-square"></i> Reports</h1>
        </section>
        <section class="content">
            @if(Session::has('success'))
                <div class="alert-success alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">
                    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i>{{ Session::get('success') }}</h4>
                </div>
            @elseif(Session::has('warning'))
                <div class="alert-warning alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">
                    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i>{{ Session::get('warning') }}</h4>
                </div>
            @endif
            <div class="panel panel-default no-print">
                <div class="panel-body">
                    <div>
                        <ul class="nav-tabs margin-bottom nav" id="">
                            <li @if($page == "academics") class="active" @endif  id="#">
                                <a href="{{url('/reports/academics')}}">Academics</a>
                            </li>
                            <li @if($page == "attendance") class="active" @endif  id="#">
                                <a href="{{url('/reports/attendance')}}">Attendance</a>
                            </li>
                            <li @if($page == "result") class="active" @endif id="#">
                                <a href="{{url('/reports/result')}}">Result</a>
                            </li>

                            <li @if($page == "fees") class="active" @endif id="#">
                                <a href="{{url('/reports/fees')}}">Fees</a>
                            </li>

                            <li @if($page == "invoice") class="active" @endif id="#">
                                <a href="{{url('/reports/invoice')}}">Invoice</a>
                            </li>
                            <li @if($page == "admission") class="active" @endif id="#">
                                <a href="{{url('/reports/admission')}}">Admission</a>
                            </li>
                            <li @if($page == "id-card") class="active" @endif id="#">
                                <a href="{{url('/reports/id-card')}}">ID Card</a>
                            </li>
                            <li @if($page == "documents") class="active" @endif id="#">
                                <a href="{{url('/reports/documents')}}">Documents</a>
                            </li>
                            <li @if($page == "admit-card") class="active" @endif id="#">
                                <a href="{{url('/reports/admit-card')}}">Admit Card</a>
                            </li>

                            <li @if($page == "sitplan") class="active" @endif id="#">
                                <a href="{{url('/reports/sitplan')}}">Seat Plan</a>
                            </li>
                            <li @if($page == "examatsheet") class="active" @endif id="#">
                                <a href="{{url('/reports/examatsheet')}}">Exam Sheet</a>
                            </li>
                            <li @if($page == "waiver") class="active" @endif id="#">
                                <a href="{{url('/reports/waiver')}}">Waiver</a>
                            </li>

                            <li class="{{$page=='enrollment'?'active':''}}" id="#">
                                <a href="{{url('/reports/enrollment')}}">Enrollment</a>
                            </li>

                            <li class="{{$page=='employee'?'active':''}}" id="#">
                                <a href="{{url('/reports/employee')}}">Employee</a>
                            </li>
                            <li class="{{$page=='college-'?'active':''}}" id="#">
                                <a href="{{url('/reports/college-report')}}">College Report</a>
                            </li>

                            {{--<li id="#">--}}
                                {{--<a href="#">Communication 4</a>--}}
                            {{--</li>--}}
                            {{--<li id="#">--}}
                                {{--<a href="#">Communication 5</a>--}}
                            {{--</li>--}}
                            {{--<li id="#">--}}
                                {{--<a href="#">Communication 6</a>--}}
                            {{--</li>--}}
                        </ul>
                        <!-- page content div -->
                        @yield('page-content')
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- global modal -->
    <div aria-hidden="true" aria-labelledby="esModalLabel" class="modal" id="globalModal" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw">
                            </i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{URL::asset('js/jquery.timepicker.min.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
            @yield('page-script')
        });
    </script>
    {{--page new script--}}
    @yield('page-new-script')
@endsection
