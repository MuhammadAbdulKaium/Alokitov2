<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CCIS</title>

    <!-- Latest compiled and minified JavaScript and CSS
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>
    -->


    <link rel="shortcut icon" href="{{asset('assets/favicon.ico')}}" type="image/x-icon">
    <link rel="icon" href="{{asset('assets/favicon.ico')}}" type="image/x-icon">



    <!-- Javascript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link href="{{ URL::asset('css/login_page.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Owl Stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.css">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->



    <!-- Yeah i know js should not be in header. Its required for demos.-->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>

    <style type="text/css">

        .school {
            background-color: #36e068 !important;
            min-height: 300px
        }

        .school-details h3 {
            font-size: 26px;
            color: #fff;
            font-weight: 700;
            text-align: center;
        }

        .school-details h2 {
            font-size: 20px;
            line-height: 25px;
            color: #fff9f9;
        }
        .school-details p {
            line-height: 23px;
            font-size: 14px;
            color: #fff;
        }

        .school-logo {
            padding: 10px;
        }

        .school-logo .img-responsive {
            margin: 0 auto;
            height: 160px;
            width: 170px;
        }
        .login-side {
            min-height: 765px;
        }

        #login-form {
            margin-top: 25%;
        }

        .footer-section {
            background: #0F0C6B !important;
            min-height: 30px;
        }
        .footer-section .copyright {
            padding: 10px;
            color: #fff;
            text-align: center;
        }

        .item {
            background:orange;
            height: 250px;
        }
        .item img {
            width: 100%;
            height:100%;
        }

        .owl-wrapper-outer {
            border: 2px solid #fff;
            border-radius: 5px;
            margin-top: 10px;
        }
        .owl-theme .owl-controls .owl-page span {
            background: #fff;
        }

        .formplexy {
            padding: 0px !important;
            margin:0px !important;
        }
        .social {
            margin-top: 13%;
        }

        .social a {
            color: #fff;
            font-size: 1.4rem;
            display: inline-block;
            height: 35px;
            width: 35px;
            text-align: center;
            line-height: 35px;
            background-color: #0F0C6B;
            border-radius: 50%;
        }

        .social a + a {
            margin-left: 10px;
        }

        .social a:hover {
            background-color: #4944e9;
            color: #ffffff;
        }

        .social + .copyright-text {
            margin-top: 42px;
            margin-bottom: 0;
        }

        .contact-info h4 { margin-bottom: 20px;  color: #fff; margin-left: 25px;  }
        .single-contact-info { margin-bottom: 15px; text-align: center;}
        .single-contact-info i {
            color: #fff;
            font-size: 1.4rem;
            display: inline-block;
            height: 35px;
            width: 35px;
            text-align: center;
            line-height: 35px;
            background-color: #0F0C6B;
            border-radius: 50%;
        }
        .single-contact-info h3 {
            font-size: 14px;
            color: #fff;
            font-weight: bold;
        }
        .single-contact-info p { font-size: 12px; color: #fff; }

        .software-logo {
            margin-top: 70px;
        }
        .software-logo .img-responsive {
            margin: 0 auto;
        }


        /*// responvice design*/

        @media only screen and (min-device-width : 320px) and (max-device-width : 480px) {

            .software-logo {
                margin-top:10px;
                margin-bottom: -35px;
            }

            .login-side {
                min-height: 480px;
            }

            .flipit {
                display: -webkit-box;
                display: -moz-box;
                display: -ms-flexbox;
                display: -webkit-flex;
                display: flex;
                -webkit-box-orient: vertical;
                -moz-box-orient: vertical;
                -webkit-flex-direction: column;
                -ms-flex-direction: column;
                flex-direction: column;
            }
            .flipit .footer-section {
                -webkit-box-ordinal-group: 3;
                -moz-box-ordinal-group: 3;
                -ms-flex-order: 3;
                -webkit-order: 3;
                order: 3;
            }

            .flipit .school {
                -webkit-box-ordinal-group: 2;
                -moz-box-ordinal-group: 2;
                -ms-flex-order: 2;
                -webkit-order: 2;
                order: 2;
            }

            .flipit .login-side {
                -webkit-box-ordinal-group: 1;
                -moz-box-ordinal-group: 1;
                -ms-flex-order: 1;
                -webkit-order: 1;
                order: 1;
            }



        }

    </style>
</head>
<body>

<div class="container-fluid formplexy flipit">
    <!-- Logo -->
    <!-- Form Base -->
    <div class="form-base col-lg-8 school">

        <!-- Form -->
        <section class="school-details">

            <h3>ALOKITO Login Panel</h3>
            <div class="school-logo">
                <img src="{{asset('assets/login/alokito-software.png')}}" class="img-responsive">
            </div>

            <div class="owl-carousel owl-theme">
                <div class="item"><img class="slider-image" src="{{asset('assets/login/image-one.jpg')}}"/></div>
                <div class="item"><img class="slider-image" src="{{asset('assets/login/image-two.jpg')}}"/></div>
                <div class="item"><img class="slider-image" src="{{asset('assets/login/image-three.jpg')}}"/></div>
                <div class="item"><img class="slider-image" src="{{asset('assets/login/image-four.jpg')}}"/></div>
                <div class="item"><img class="slider-image" src="{{asset('assets/login/image-five.jpg')}}"/></div>
                <div class="item"><img class="slider-image" src="{{asset('assets/login/image-six.jpg')}}"/></div>
                <div class="item"><img class="slider-image" src="{{asset('assets/login/image-seven.jpg')}}"/></div>
                <div class="item"><img class="slider-image" src="{{asset('assets/login/image-eight.jpg')}}"/></div>
                <div class="item"><img class="slider-image" src="{{asset('assets/login/image-nine.jpg')}}"/></div>
                <div class="item"><img class="slider-image" src="{{asset('assets/login/image-ten.jpg')}}"/></div>
                <div class="item"><img class="slider-image" src="{{asset('assets/login/11.jpg')}}"/></div>
                <div class="item"><img class="slider-image" src="{{asset('assets/login/12.jpg')}}"/></div>

            </div>

        </section>
        <div class="row">

            <div class="col-md-6">
                <div class="contact-info">
                    <div class="row">
                        <h4>Contact Info</h4>
                        <!-- START CONTACT INFO DESIGN AREA -->
                        <div class="col-md-4">
                            <div class="single-contact-info">
                                <i class="fa fa-map-marker"></i>
                                <h3>Address</h3>
                                <p>Venus Complex, Kha-199/2-199/4</p>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="single-contact-info">
                                <i class="fa fa-mobile"></i>
                                <h3>Phone</h3>
                                <p>+8801708872244</p>
                            </div>
                        </div>


                        <div class="col-md-4">
                            <div class="single-contact-info">
                                <i class="fa fa-envelope "></i>
                                <h3>Email</h3>
                                <p>alokito@gmail.com</p>
                            </div>
                        </div>


                    </div>

                </div>
            </div>

            <div class="col-md-4 pull-right">

                <div class="menu-social social"><a href="#"><i class="fa fa-facebook"></i></a><a href="#"><i class="fa fa-twitter"></i></a><a href="#"><i class="fa fa-google-plus"></i></a><a href="#"><i class="fa fa-pinterest-p"></i></a><a href="#"><i class="fa fa-linkedin"></i></a></div>

            </div>

        </div>

    </div>

    <div class="form-base col-lg-4 login-side">

        <div class="software-logo">

            <img src="{{asset('assets/login/alokito-software.png')}}" class="img-responsive">
        </div>

        <!-- Form -->
        <section>
            <form class="form-signin" role="form" method="POST" action="{{ route('login') }}" id="login-form">
                {{ csrf_field() }}

                <div class="input-group login-username">
                    <div class="input-group-addon">Username</div>
                    <input type="email" name="email"  value="{{ old('email') }}" placeholder="Enter email" required autofocus id="login-username">
                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="input-group login-password">
                    <div class="input-group-addon">Password</div>
                    <input type="password"  name="password"  placeholder="Enter password" required id="login-password">
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>

                <!-- Checkbox area - Keep me logged in -->

                <div class="input-wrapper">
                    <div class="checkbox-custom">
                        <input type="checkbox" name="login-keep" id="login-keep" class="toggle toggle-round">
                        <label for="login-keep"></label>
                    </div>
                    <label for="login-keep">Keep me logged in</label>
                </div>
                <div class="clearfix"></div>


                <div class="row section-action">
                    <!-- Forgotten Password Trigger -->
                    <div class="col-xs-8">
                        <a class="forgotten-password-trigger custom-color">Forgotten password?</a>
                    </div>
                    <!-- Submit -->
                    <div class="col-xs-4">
                        <button class="btn primary pull-right custom-color-back">Login</button>
                    </div>
                </div>
            </form>
        </section>

        <!-- Forgotten Password -->
        <section class="forgotten-password">
            <form action="donothing" method="post" id="forgotten-password-form">
                <div class="input-group col-sm-7 col-xs-12 login-forgotten-password"><input type="text" placeholder="Email" name="login-forgotten-password" id="login-forgotten-password"><span aria-hidden="true" id="status-forgotten-password" class="status-icon"></span></div>
                <!-- Submit -->
                <button class="btn primary col-sm-4 col-sm-push-1 col-xs-12 custom-color-back">Send</button>
            </form>
            <!-- Forgotten Password Text -->
            <p>We'll send you e-mail with password reset.</p>
            <div class="clearfix"></div>
        </section>

    </div>

    <div class="form-base col-lg-12 footer-section">
        <!-- Copyright -->
        <div class="copyright col-md-10 col-md-offset-1 text-left">
            <div class="copyright">
                <p>Copyright © <a href="https://venusitltd.com" target="_blank">Venus IT Ltd.</a> 2017 All right reserved.</p>
            </div>
        </div>
    </div>

</body>

<script type="text/javascript">
    $(document).ready(function(){
        var owl = $('.owl-carousel');
        owl.owlCarousel({
            items:4,
            loop:true,
            margin:10,
            autoplay:true,
            autoplayTimeout:1000,
            autoplayHoverPause:true
        });
        $('.play').on('click',function(){
            owl.trigger('play.owl.autoplay',[1000])
        })
        $('.stop').on('click',function(){
            owl.trigger('stop.owl.autoplay')
        })
    })
</script>
</html>