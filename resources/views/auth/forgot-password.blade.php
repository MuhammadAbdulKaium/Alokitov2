<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>CCIS</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="manifest" href="site.webmanifest">
    <link rel="apple-touch-icon" href="icon.png">
    <!-- Place favicon.ico in the root directory -->

    <link href="{{ URL::asset('template-2/css/normalize.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('template-2/css/bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('template-2/css/style.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('template-2/css/font-awesome.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800" rel="stylesheet">

    <style>

        .login-warp{
            @if(!empty($loginScreenProfile))
                background:url({{ URL::to('/assets/login_screen/',$loginScreenProfile->login_image) }}) no-repeat center center fixed;
            @else
                 background:url({{ URL::asset('template-2/img/student-school-photography-boston-st-charles-26.jpg') }}) no-repeat center center fixed;
        @endif
/*min-height:990px;
            background-size:cover;*/
        }

        {{--body{--}}
        {{--background:url({{ URL::asset('template-2/img/student-school-photography-boston-st-charles-26.jpg') }}) no-repeat center center fixed;--}}
        {{---moz-background-size: cover;--}}
        {{---webkit-background-size: cover;--}}
        {{---o-background-size: cover;--}}
        {{--background-size: cover;"--}}
        {{--}--}}
        .login-button {
            margin-top: 0px !important;
        }

    </style>

</head>
<body>
<div class="wrapper">
    <!--[if lte IE 9]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
    <![endif]-->

    <!-- Add your site or application content here -->
    <section class="login-warp desktop-login">
        <div class="container-fluid">
            <div class="login-bg">
{{--                <img src=" {{ URL::asset('assets/users/images/alokito_software.jpg') }}" class="img-login">--}}

                @if(Session::has('success'))
                    <div class="alert alert-success" style="margin-bottom: -50px">
                        <strong>Success! </strong> {{Session::get('success')}}
                    </div>
                @endif

                @if(Session::has('wrong'))
                    <div class="alert alert-warning">
                        <strong>Warning! </strong> {{Session::get('wrong')}}
                    </div>
                @endif

                <form  method="POST" action="{{ URL::to('forgot-password/check') }}" style="margin-top: 50px">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="inputEmail">Email Address</label>

                        <input type="email" name="email"  value="{{ old('email') }}"  class="form-control" placeholder="Enter email" required autofocus id="login-username">
                        @if ($errors->has('email'))
                            <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary login-button">Reset Password</button>
                    <a style="margin-top: 10px !important;" href="{{URL::to('login')}}" class="btn btn-primary login-button">Sign In</a>
                </form>
            </div>
        </div>
    </section>
    <!-- media login section-->




    {{--<div class="push"></div>--}}
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


</body>
</html>
