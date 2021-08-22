<!doctype html>
<html class="no-js" lang="">
<head>
    <meta charset="utf-8">
    <title>403</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
</head>
<body>
<div class="wrapper">
    <section class="section-single">
        <div class="container">
            <div class="fullwidth-post-wrapper col-lg-12">
                <div class="error404 text-center">
                    <h1>
                        <span style="font-size: 250px;"> 403 </span>
                        {{--<span class="text-danger" style="font-size: 20px; color: red;">--}}
                        {{--<i class="fa fa-random" aria-hidden="true"></i> Not Found--}}
                        {{--</span>--}}
                    </h1>
                    <h3>
                        No Permission to Access.<br/>
                        Perhaps you can return back to the site's homepage and see if you can find what you are looking for.
                    </h3>
                    {{--<a class="btn btn-primary" href="{{ url()->previous() }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> BACK</a>--}}
                    <a class="btn btn-primary" href="/"><i class="fa fa-home" aria-hidden="true"></i> HOME</a>
                    {{--<a class="btn btn-default" href="/"> <i class="fa fa-sign-out" aria-hidden="true"></i> Logout</a>--}}
                    <a class="btn btn-primary"  href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>

                </div>
            </div>
        </div>
    </section>
</div>
</body>
</html>
