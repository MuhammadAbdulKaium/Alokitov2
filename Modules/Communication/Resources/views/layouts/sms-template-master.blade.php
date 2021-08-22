@extends('layouts.master')
<!-- page content -->
@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1><i class="fa fa-plus-square"></i>Sm</h1>
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
        <div class="panel panel-default">
            <div class="panel-body">
                <div>
                    <ul class="nav-tabs margin-bottom nav" id="">
                        <li @if($page == "teacher") class="active" @endif  id="#">
                        <a href="{{url('/communication/sms/template/teacher')}}">Teacher </a>
                        </li>
                        <li @if($page == "student") class="active" @endif  id="#">
                        <a href="{{url('/communication/sms/template/student')}}">Student</a>
                        </li>
                        <li @if($page == "stuff") class="active" @endif  id="#">
                        <a href="{{url('/communication/sms/template/stuff')}}">Staff</a>
                        </li>
                        <li @if($page == "parent") class="active" @endif  id="#">
                        <a href="{{url('/communication/sms/template/parent')}}">Parent</a>
                        </li>

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
<script src="{{URL::asset('js/charts/chart.min.js')}}"></script>
<script src="{{URL::asset('js/tokenInput.js')}}"></script>
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

@endsection
