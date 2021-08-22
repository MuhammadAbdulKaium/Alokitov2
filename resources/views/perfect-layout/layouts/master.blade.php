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
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->
    <link href="{{ URL::asset('template-2/css/normalize.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('template-2/css/main.css') }}" rel="stylesheet" type="text/css"/>
{{--    <link href="{{ URL::asset('template-2/css/bootstrap.css') }}" rel="stylesheet" type="text/css"/>--}}
    <link href="{{ URL::asset('template-2/css/style.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('template-2/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('template-2/css/jquery.smartmenus.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    {{--<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">--}}


    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/main-style-new.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet"/>

    @yield('styles')

</head>
<body>
<div class="wrapper">
    <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->
    <div class="logo-wrap">
        <div class="container-fluid">
            <div class="col-md-6">
{{--                <a href="{{URL::to('/')}}" class=""><img src="http://mlis.edu.bd/images/logo.png" class="brand-logo" alt="Alokito logo"></a>--}}
            </div>

            @include('layouts._header-custom-menu')

        </div><!--Logo wrap container End-->
    </div><!--Logo wrap End-->
    <!-- Add your site or application content here -->

    <!-- Navbar -->
    @include('layouts._header-mega-menu')


    @yield('content')

</div>
    <div class="push"></div>
</div><!--Warper End-->



<footer class="footer">
    <div class="container-fluid">
        <div class="col-md-6">
            <p class="copyright-footer-2">Alokito Software</p>
        </div>
        <div class="col-md-6">
            <p class="copyright-footer">Copyright © 2017 Venus IT. All rights reserved.</p>
        </div>
    </div>
</footer>




<script src="{{URL::asset('js/all.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.validate.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>

<script src="{{URL::asset('template-2/js/vendor/modernizr-3.5.0.min.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('template-2/js/main.js')}}" type="text/javascript"></script>



<script src="{{URL::asset('template-2/js/jquery.smartmenus.js')}}" type="text/javascript"></script>
<script src="{{URL::asset('template-2/js/jquery.smartmenus.bootstrap.js')}}" type="text/javascript"></script>
{{--select 2--}}
{{--<script src="{{ URL::asset('js/select2.full.min.js') }}"></script>--}}
{{--date picker--}}
{{--<script src="{{ URL::asset('js/bootstrap-datepicker.js') }}"></script>--}}

{{--<script src="{{URL::asset('js/jquery.cookie.js')}}" type="text/javascript"></script>--}}
{{--<script src="{{URL::asset('js/bootstrap-waitingfor.min.js')}}" type="text/javascript"></script>--}}
{{--<script src="{{URL::asset('js/datatable.js') }}" type="text/javascript"></script>--}}
{{--<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>--}}



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
