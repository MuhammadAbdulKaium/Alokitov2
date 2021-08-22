<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CCIS</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <meta id="token" name="token" value="{{ csrf_token() }}">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/main-style.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />


    <link rel="shortcut icon" href="{{asset('assets/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('assets/favicon.ico')}}" type="image/x-icon">



    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    @yield('styles')

    <style>
        .language  {
            float: right;
            padding-top: 6px;
        }

        .navbar-static-top{
            background:#efefef !important;
        }



        .example3 .navbar-brand {
            height: 100px;
            border-color: 1px solid #efefef;
        }


        .school-logo {
            margin-top:-50px;

        }

        .school-name {
            font-size: 30px;
            font-weight: 700;
        }

        .header-section {
            display: inline-block;
        }

        /*///  Header font Color*/
        @php $data=institute_property("header-font-color") @endphp
        .school-name {
            @if(!empty($data->attribute_value))
            color:{{ $data->attribute_value }} !important;
        @endif
        }
        /*Header campus COlor Color*/
        @php $data=institute_property("header-font-color") @endphp
        .campus-name {
            @if(!empty($data->attribute_value))
            color:{{ $data->attribute_value }} !important;
        @endif
        }

        /*Header  Color*/
        @php $data=institute_property("Header") @endphp
        .alokito-header {
            @if(!empty($data->attribute_value))
            background:{{ $data->attribute_value }} !important;
        @else {
            background:blue;
        }
        @endif

        }


        /*top header color*/
        @php $data=institute_property("top-header") @endphp
        .top-header {
            @if(!empty($data->attribute_value))
                 background:{{ $data->attribute_value }} !important;
        @else {
            background:#efefef;
        }
        @endif

    }



    </style>


</head>


<body class="layout-top-nav skin-blue-light">
<div class="wrapper">
    <header class="main-header">
        <nav class="navbar navbar-fixed-top">
            <div class="example3">
                <nav class="navbar  navbar-static-top">
                    <div class="container-fluid top-header">
                        <div class="col-md-12 text-center">

                            <img class="school-logo" src="{{URL::asset('assets/users/images/'.getInstituteProfile()->logo)}}" height="90px" style="padding: 5px;" alt="Dispute Bills">
                            <div class="header-section">
                                <h2 class="school-name">{{getInstituteProfile()->institute_name}}</h2>
                                <h4 class="campus-name">{{getCampusProfile()->name}} </h4>
                            </div>
                        </div>

                        <!--/.nav-collapse -->
                    </div>
                    <!--/.container-fluid -->
                </nav>
            </div>
            <div class="container-fluid alokito-header">
                <!--Start Mega menu block-->
                {{--<div class="col-md-5">--}}
                {{--<div class="navbar-header pull-left">--}}
                {{--<a class="navbar-brand text-bold hidden-xs" href="/">--}}
                {{--<i class="fa fa-th"></i> ALOKITO</a> <a class="navbar-brand text-bold visible-xs" href="/">--}}
                {{--<span class="fa fa-th-large fa-lg menu-icon"></span>--}}
                {{--ALOKITO--}}
                {{--</a>--}}
                {{--</div>--}}
                {{--</div>--}}

                @if (!Request::is('/'))
                    {{--header menu permission according to the role--}}
                    @role(['admin','hrms', 'teacher'])

                    @include('layouts._header-mega-menu')

                    {{--new Menu Header--}}
                    <div class="navbar-header pull-left">
                        <a class="navbar-brand text-bold hidden-xs" style="margin-top: 5px;margin-left: 80px;" href="/">{{getInstituteProfile()->institute_name}}</a> </div>


                    {{--<div class="navbar-header pull-left">--}}
                    {{--<a class="navbar-brand text-bold hidden-xs" href="/">ALOKITO</a> <a class="navbar-brand text-bold visible-xs" href="/">ALOKITO</a> </div>--}}

                    @endrole

                <!--./esmenu-->
                    <div class="col-md-5">
                        <div class="navbar-header pull-left">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="language">

                            {{--@if($languages = \Modules\Setting\Entities\Language::all())--}}
                            {{--<div class="dropdown">--}}
                            {{--<button id="SelectLaguage" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">--}}

                            {{--{{Config::get('app.locale')}}--}}

                            {{--<span class="caret"></span></button>--}}
                            {{--<ul class="dropdown-menu">--}}
                            {{--@foreach($languages as $language)--}}
                            {{--<li><a class="select_language" id="{{$language->language_name}}" href="{{URL::to('setting/language/choose/'.$language->language_slug)}}">{{$language->language_name}}</a></li>--}}
                            {{--@endforeach--}}
                            {{--</ul>--}}
                            {{--</div>--}}
                            {{--@endif--}}
                        </div>
                    </div>

                    {{--@if($languages = \Modules\Setting\Entities\Language::all())--}}
                    {{--<div class="dropdown">--}}
                    {{--<button id="SelectLaguage" class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">--}}

                    {{--{{Config::get('app.locale')}}--}}

                    {{--<span class="caret"></span></button>--}}
                    {{--<ul class="dropdown-menu">--}}
                    {{--@foreach($languages as $language)--}}
                    {{--<li><a class="select_language" id="{{$language->language_name}}" href="{{URL::to('setting/language/choose/'.$language->language_slug)}}">{{$language->language_name}}</a></li>--}}
                    {{--@endforeach--}}
                    {{--</ul>--}}
                    {{--</div>--}}
                    {{--@endif--}}
                    {{--</div>--}}

                    {{--</div>--}}
                    @include('layouts._header-custom-menu')
                <!--./navbar-custom-menu-->
                @endif
            </div>



        </nav>



        {{--<!-- School Header Design -->--}}

        {{--<nav class="navbar navbar-expand-lg" style="margin-top: 50px">--}}
            {{--<div class="container">--}}
                {{--<a class="navbar-brand" href="#">--}}
                    {{--<img src="http://www.green.edu.bd/templates/greenuni_new/images/logo_greenuni_new.png" alt="" width="150" height="30">--}}
                {{--</a>--}}
                {{--<div class="collapse navbar-collapse" id="navbarResponsive">--}}
                    {{--<h2>Green University of Bangladesh</h2>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</nav>--}}

        {{--<!-- End School Header Design -->--}}





    </header>

    @yield('content')

    <div id="slideToTop"><i class="fa fa-chevron-up"></i></div>
</div>

<footer class="main-footer">
    <div class="pull-right hidden-xs">
        <div class="pull-right hidden-xs text-bold">
            Powered by <a href="#">AloKITo<sup>&trade;</sup></a>
        </div>
    </div>
    <strong>Copyright &copy; 2017 <a href="http://www.venusitltd.com">Venus IT Ltd</a>.</strong> All rights reserved.
</footer>

<!-- site script -->
<!-- <script src="{{URL::asset('js/jquery.js') }}" type="text/javascript"></script> -->
<!-- <script src="{{URL::asset('js/jquery.validate.js') }}" type="text/javascript"></script> -->
<script src="{{URL::asset('js/all.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.validate.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>

{{--select 2--}}
<script src="{{ URL::asset('js/select2.full.min.js') }}"></script>
{{--date picker--}}
<script src="{{ URL::asset('js/bootstrap-datepicker.js') }}"></script>

<script src="{{URL::asset('js/jquery.cookie.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/bootstrap-waitingfor.min.js')}}" type="text/javascript"></script>
{{--<script src="{{URL::asset('js/datatable.js') }}" type="text/javascript"></script>--}}
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>


@yield('scripts')
<!-- ajax setup -->
<script type="text/javascript">
    jQuery(document).ready(function () {


        // on module click action
        $('#alokito_menu').click(function () {
            $('.tab-pane').css({'display':''});
        });

        // ajax setup for laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>
</body>
</html>
