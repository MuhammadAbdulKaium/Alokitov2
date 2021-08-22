<!DOCTYPE html>
<html lang="en">
<head>
    <title>ALOKITO - Login</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ URL::asset('css/main-style.css') }}" rel="stylesheet" type="text/css"/>

    <style type="text/css">
        .form-signin
        {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }
        .form-signin .form-signin-heading, .form-signin .checkbox
        {
            margin-bottom: 10px;
        }
        .form-signin .checkbox
        {
            font-weight: normal;
        }
        .form-signin .form-control
        {
            position: relative;
            font-size: 16px;
            height: auto;
            padding: 10px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .form-signin .form-control:focus
        {
            z-index: 2;
        }
        .form-signin input[type="email"]
        {
            margin-bottom: -1px;
            border-radius: 5px;
        }
        .form-signin input[type="password"]
        {
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .account-wall
        {
            background-color: #f7f7f7;
            border-radius: 5px;
            box-shadow: 0 2px 2px rgba(0, 0, 0, 0.3);
            margin-top: 20px;
            padding: 40px;
        }
        .login-title
        {
            color: #d9e7de;
            font-size: 55px;
            font-weight: 700;
            display: block;

        }
        .profile-img
        {
            width: 96px;
            height: 96px;
            margin: 0 auto 10px;
            display: block;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }
        .need-help
        {
            margin-top: 10px;
        }

        body{
            background: #46a8e6 !important;
        }
    </style>
</head>

<body>
<div class="container">
    <div class="row">
        <div class="col-sm-6 col-md-4 col-md-offset-4">
            <h1 class="login-title text-center">ALOKITO</h1>
            <div class="account-wall">
                <img class="profile-img" src="http://demo.edusec.org/site/loadimage" alt="ALOKITO">
                <!-- <h3 class="text-center"><storng>alokito.com</storng></h3> -->
                <form class="form-signin" role="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                        <!-- <label for="email">Email:</label> -->
                        <input type="email" class="form-control" name="email" id="email" value="{{ old('email') }}" placeholder="Enter email" required autofocus>
                        @if ($errors->has('email'))
                            <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                        <!-- <label for="password">Password:</label> -->
                        <input type="password" class="form-control" name="password" id="password" placeholder="Enter password" required>
                        @if ($errors->has('password'))
                            <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="checkbox">
                        <label><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me</label>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>

            </div>
        </div>
    </div>

</div>
<div class="navbar navbar-default navbar-fixed-bottom">
    <div class="container">
        <p class="navbar-text pull-left">© 2015 - 2017
            <a href="http://venusitltd.com" target="_blank"><strong>Venus IT Limited</strong></a>
        </p>

        <a href="#" class="navbar-btn btn-danger btn pull-right">ALOKITO</a>
    </div>
</div>
</body>
</html>
