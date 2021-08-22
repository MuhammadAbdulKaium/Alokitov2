@extends('layouts.master')

{{-- Web site Title --}}


@section('styles')

    <link href="{{ asset('css/html5input.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/kv-widgets.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/spectrum.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/spectrum-kv.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet">
@stop
   {{-- <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
    <title>EduSec | Login</title>
    <link href="/css/all-0e5a260dfb0bb1624548888c5dcb9474.css?v=1486729641" rel="stylesheet">
    <link href="/assets/e081f6af/authchoice.css?v=1486961339" rel="stylesheet">--}}
    <style>
        .login-header {
            border-bottom: 1px solid #ddd;
        }
        .login-header h1 {
            font-size: 30px;
            margin: 0;
            text-align: center;
        }
        .login-box, .register-box {
            margin: 7% auto;
        }
        .inst-logo {
            width:80px;
            height:80px;
        }
        .inst-name {
            font-size:20px;
            font-weight:600;
        }

    </style></head>
<body class="login-page">
<div class="wrapper" style="background: #cfd2d9 !important">
    <div class="login-box">


        <div class="login-box-body">
            <p class="text-center">
               {{-- <img class="inst-logo" src="{{ asset("/site/loadimage") }}" alt="">  --}}          <br>
                <span class="inst-name">
                ALOKITO            </span>
            </p>
            <br>
            <form id="login-form" action="admin/dashboard" method="get" role="form">
                <input type="hidden" name="_csrf" value="RzlLM0NSVXAdSCBWcgVjMR9AAGspCz4.AHs.AikZHwceACBgewAFRA==">
                <div class="form-group has-feedback field-loginform-username required">

                    <input type="text" id="loginform-username" class="form-control" name="LoginForm[username]" placeholder="Username" aria-required="true"><span class='glyphicon glyphicon-user form-control-feedback'></span>

                    <p class="help-block help-block-error"></p>
                </div>
                <div class="form-group has-feedback field-loginform-password required">

                    <input type="password" id="loginform-password" class="form-control" name="LoginForm[password]" placeholder="Password" aria-required="true"><span class='glyphicon glyphicon-lock form-control-feedback'></span>

                    <p class="help-block help-block-error"></p>
                </div>
                <div class="row">
                    <div class="col-xs-8">
                        <div class="form-group field-loginform-rememberme">
                            <div class="checkbox">
                                <label for="loginform-rememberme">
                                    <input type="hidden" name="LoginForm[rememberMe]" value="0"><input type="checkbox" id="loginform-rememberme" name="LoginForm[rememberMe]" value="1" checked>
                                    Remember Me
                                </label>
                                <p class="help-block help-block-error"></p>

                            </div>
                        </div>            </div>
                    <div class="col-xs-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat pull-right" name="login-button">Login</button>            </div>
                </div>
            </form>
           <a class="text-center" href="/site/request-password-reset">Forgotten Password?</a>        <br/>
        </div><!-- /.login-box-body -->

    </div><!-- /.login-box -->
</div>
<footer class="main-footer text-center" style="margin-left:0">
    <strong>Copyright &copy; 2017 <a href="http://www.venusitltd.com">Venus IT Ltd.</a>.</strong> All rights reserved.    </footer>
</body>
</html>
