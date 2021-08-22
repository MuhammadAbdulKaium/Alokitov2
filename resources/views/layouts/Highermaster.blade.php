<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>CCIS</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta id="token" name="token" value="{{ csrf_token() }}">
    <link rel="manifest" href="site.webmanifest">

    @if(!empty(getInstituteProfile()))
        <link rel="icon" href="{{url('assets/users/images/'.getInstituteProfile()->logo)}}" type="image/x-icon"/>
    @else
        <link rel="icon" href="{{url('assets/users/images/alokito_software.png')}}" type="image/x-icon"/>
    @endif


    {{--    <link rel="icon" href="{{url('assets/users/images/'.getInstituteProfile()->logo)}}" type="image/x-icon"/>--}}
    {{--<link rel="shortcut icon" href="{{url('assets/users/images/'.getInstituteProfile()->logo)}}" type="image/x-icon"/>--}}


    <link rel="apple-touch-icon" href="">
    <!-- Place favicon.ico in the root directory -->
    <link href="{{ URL::asset('template-2/css/normalize.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('template-2/css/main.css') }}" rel="stylesheet" type="text/css"/>
    {{--    <link href="{{ URL::asset('template-2/css/bootstrap.css') }}" rel="stylesheet" type="text/css"/>--}}
    <link href="{{ URL::asset('template-2/css/style.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('template-2/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('template-2/css/jquery.smartmenus.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">


    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/main-style-new.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet"/>
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
    <link href="{{ asset('css/sweetAlert.css') }}" rel="stylesheet"/>
    <link href="https://cdn.anychart.com/releases/v8/css/anychart-ui.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.anychart.com/releases/v8/fonts/css/anychart-font.min.css" rel="stylesheet" type="text/css">
    @yield('styles')
    <style>
        @php $headingFontFamily=institute_property("heading-font-family") @endphp
        @php $mainFontFamily=institute_property("main-font-family") @endphp


       @if(!empty($headingFontFamily) && !empty($mainFontFamily))
        @import url('{{$headingFontFamily->font_family()->font_link}}');
        @import url('{{$mainFontFamily->font_family()->font_link}}');

        h1 {
        font-family: '{{$headingFontFamily->font_family()->font_name}}', sans-serif !important;
        }

        html, body, p, div, h2, h3, h4, h5, h6, a, ul, ol, li, th, td, dt, dl, dd, b, strong {
            font-family: '{{$mainFontFamily->font_family()->font_name}}', sans-serif !important;
        }
        @endif


        /*///  Header font Color*/
                /*Header  Color*/
        @php $headerBgColor=institute_property("Header") @endphp
        @php $topHeaderBgColor=institute_property("top-header") @endphp

        .school-name {
            font-weight: 600;
            font-size: 25px;
            @if(!empty($headerFontColor->attribute_value))
            color:{{ $headerFontColor->attribute_value }} !important;
        @endif
        }

        .campus-name {
            margin-top: 16px;
            font-size: 18px;
            text-align: center;
            color: #fff;
            padding: 10px;
            @if(!empty($headerBgColor->attribute_value))
background:{{ $headerBgColor->attribute_value }} !important;
            @endif
            border-radius:20px;
        }

        .logo-wrap {
            @if(!empty($topHeaderBgColor->attribute_value))
background:{{ $topHeaderBgColor->attribute_value }} !important;
            @endif
             @if(!empty($headerBgColor->attribute_value))
border-top: 3px solid {{ $headerBgColor->attribute_value }} !important;
        @endif

}
        .main-menu-style {
            @if(!empty($headerBgColor->attribute_value))
             background: {{ $headerBgColor->attribute_value }} !important;

            @else
             backgound:ornage;
        @endif
}

    </style>

</head>
<body>
<div class="wrapper">
    <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->
    <div class="logo-wrap">
        <div class="container-fluid">
            <div class="col-md-2" style="text-align: center">
                <a href="{{(Auth::user()->hasRole('super-admin')==true)?URL::to('/admin '):URL::to('/')}}">

                    {{--institute profile checking--}}
{{--                    @if(!empty(getInstituteProfile()))--}}
{{--                        --}}{{--institute logo--}}
{{--                        <img src="{{url('assets/users/images/'.getInstituteProfile()->logo)}}" style="height: 70px" width="70px" align="center" class="brand-logo" alt="Alokito logo">--}}
{{--                    @else--}}
{{--                        --}}{{--redireting--}}
{{--                        {{URL::to('/ ')}}--}}
{{--                    @endif--}}
                    <img src="{{asset('template-2/img/cadetlogo.jpg')}}" style="height: 100px" width="100px" align="center" class="brand-logo" alt="Alokito logo">
                </a>
            </div>
            @php    $campusName = getCampusProfile(); @endphp
            <div class="col-md-4" style="margin: 20px 0px"><h2 class="school-name">
                    {{--institute profile checking--}}
                    @if(!empty(getInstituteProfile()))
                        {{--institute name--}}
{{--                        {{getInstituteProfile()->institute_name}}--}}
                        Cadet College Central Dashboard for High-Admin

                    @else
                        {{--redireting--}}
{{--                        {{URL::to('/ ')}}--}}
                        Cadet College Central Dashboard for High-Admin
                    @endif
                </h2>
                <span class="label label-success" style="padding: 6px">@isset($campusName->name) {{$campusName->name}} @endisset</span>
{{--                <span class="label label-success" style="padding: 6px">HIgher Admin</span>--}}
            </div>
            <div class="col-md-3" style="margin-bottom: 5px">
                <div style="float:right">
                    <h2 class="school-name">Cadet College SOP</h2>
                    <p><b>Knowledge, Morality, Patriosm</b></p>
                </div>
            </div>
            @include('layouts._header-higher-admin-menu')

        </div><!--Logo wrap container End-->
    </div><!--Logo wrap End-->
    <!-- Add your site or application content here -->

    <!-- Navbar -->


    @include('layouts._header-mega-menu')


    @yield('content')
</div>

<div class="push"></div>
</div><!--Warper End-->


{{--<footer class="footer">--}}
{{--<div class="container-fluid">--}}
{{--<div class="col-md-6">--}}
{{--<p class="copyright-footer-2">Alokito Software</p>--}}
{{--</div>--}}
{{--<div class="col-md-6">--}}
{{--<p class="copyright-footer">Copyright © 2017 Venus IT. All rights reserved.</p>--}}
{{--</div>--}}
{{--</div>--}}
{{--</footer>--}}



<script src="{{URL::asset('js/all.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.validate.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>

<script src="{{URL::asset('template-2/js/vendor/modernizr-3.5.0.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('template-2/js/main.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('template-2/js/jquery.smartmenus.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('template-2/js/jquery.smartmenus.bootstrap.js')}}" type="text/javascript"></script>



<script src="{{ URL::asset('js/select2.full.min.js') }}"></script>
<script src="{{ URL::asset('js/bootstrap-datepicker.js') }}"></script>

<script src="{{URL::asset('js/jquery.cookie.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/bootstrap-waitingfor.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/datatable.js') }}" type="text/javascript"></script>
{{--<script type="text/javascript" src="https://cdn.datatables.net/s/dt/dt-1.10.10,se-1.1.0/datatables.min.js" ></script>--}}

<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="{{URL::asset('js/sweetalert.min.js') }}"></script>
{{--<script src="{{URL::asset('js/pic-chart.js')}}"></script>--}}

{{--<script src="{{URL::asset('js/pie-chartjs.js') }}"></script>--}}

<script src="https://cdn.anychart.com/releases/v8/js/anychart-base.min.js"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-ui.min.js"></script>
<script src="https://cdn.anychart.com/releases/v8/js/anychart-exports.min.js"></script>
<script type="text/javascript" src="{{URL::asset('js/any-chartCustom.js') }}"></script>
@yield('scripts')
<!-- Google Analytics: change UA-XXXXX-Y to be your site's ID. -->
{{--<script>--}}
{{--window.ga=function(){ga.q.push(arguments)};ga.q=[];ga.l=+new Date;--}}
{{--ga('create','UA-XXXXX-Y','auto');ga('send','pageview')--}}
{{--</script>--}}
{{--<script src="https://www.google-analytics.com/analytics.js" async defer></script>--}}


<script>
    $(document).ready(function(){
        // ajax setup for laravel
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    });
</script>

<script>



    $(document).ready(function() {

        $(".dropdown-toggle-language").click(function(ev) {
            $("ul.menu-language").dropdown("toggle");
        });


        $(".dropdown-toggle-campus").click(function(ev) {
            $("ul.menu-campus").dropdown("toggle");
        });


//
//        $("ul.dropdown-menus").focusout(function() {
//            alert('sdafdsf');
//        });



        $('ul.menu-language >li > a:not(a[href="#"])').on('click', function() {
            self.location = $(this).attr('href');
        });

    });
</script>


</body>
</html>
