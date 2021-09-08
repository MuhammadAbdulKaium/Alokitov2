<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Alokito ERP Login</title>
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
            <div class="all-cadet-logo">
                <img src=" {{ URL::asset('/template-2/img/all_cadet.png') }}" class="all-cadet-img" height="200" width="200">
            </div>
            <div class="login-bg">
{{--                <img src=" {{ URL::asset('assets/users/images/alokito_software.jpg') }}" class="img-login">--}}
                <form  method="POST" action="{{ route('login') }}" style="margin-top: 40px">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label for="inputEmail">Email or Username</label>

                        <input type="text" name="login"  class="form-control"  value="{{ old('login') }}" placeholder="Enter email" required autofocus id="login-username">
                        @if ($errors->has('login'))
                            <span class="help-block">
                            <strong>{{ $errors->first('login') }}</strong>
                        </span>
                        @endif

                    </div>
                    <div class="form-group">
                        <label for="inputPassword">Password</label>
                        <input type="password"  name="password" class="form-control"  placeholder="Enter password" required id="login-password">
                        @if ($errors->has('password'))
                            <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox"> Remember me </label>
                        <label><a href="{{URL::to('forgot-password')}}">Forgot Password</a> </label>
                    </div>
                    <button type="submit" class="btn btn-default login-button">Sign In</button>
                </form>
            </div>
        </div>
    </section>
    <!-- media login section-->

        <section class="login-warp media-screen-inner">
        <div class="container-fluid">
            <div class="login-bg meida-device-section">
            <img src=" {{ URL::asset('assets/users/images/alokito_software.jpg') }}" class="img-login media-icon" height="40" width="40">
                <form  method="POST" action="{{ route('login') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token()}}">
                    
                        <div class="form-group meida-device-group">
                            <!-- <label for="username" class="cols-sm-2 control-label">Username</label> -->
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon media-addon"><i class="fa fa-user fa" aria-hidden="true"></i></span>
                                    <input type="text" class="form-control input-field-media" value="{{ old('login') }}" name="login" required id="login-username"  placeholder="Enter email"/>
                                    @if ($errors->has('login'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('login') }}</strong>
                                    </span>
                                    @endif

                                </div>
                            </div>
                        </div>

                        <div class="form-group meida-device-group">
                            <!-- <label for="password" class="cols-sm-2 control-label">Password</label> -->
                            <div class="cols-sm-10">
                                <div class="input-group">
                                    <span class="input-group-addon media-addon"><i class="fa fa-lock fa-lg" aria-hidden="true"></i></span>
                                    <input type="password" class="form-control input-field-media" name="password" required id="login-password"  placeholder="Enter password"/>
                                    @if ($errors->has('password'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    <div class="checkbox">
                        <label><input type="checkbox"> Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-default login-button login-media-btn">Sign In</button>
                </form>
                <div class="clearfix"></div>
            </div>
        </div>
         <div class="clearfix"></div>
    </section>
    <!--./media login section-->



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
