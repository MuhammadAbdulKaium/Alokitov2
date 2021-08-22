<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <style>
        .label {
            border: 1px solid black;
            border-radius: 2px;
            font-size: 25px;
            font-weight: 700;
            margin: 5px 0px;
            padding: 10px;
        }
        .clearfix{clear: both;}
        .clear{clear: both;}
        .pull-left{float: left;}
        .stprofile-left{float: left;}
        .stprofile-right{float: right;}
        .pull-right{float: right;}
        .text-center {text-align: center;}
        .img-thumbnail {
            display: inline-block;
            height: 200px;
            padding: 4px;
            line-height: 1.42857143;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            -webkit-transition: all .2s ease-in-out;
            -o-transition: all .2s ease-in-out;
            transition: all .2s ease-in-out;
            width: 205px;
        }
        .col-md-3 {
            width: 25%;
        }
        .col-md-9 {
            width: 68%;
        }

        .table {
            width: 100%;
            max-width: 100%;
            margin-bottom: 20px;
        }

        table {
            background-color: transparent;
            display: table;
            border-collapse: separate;
            border-spacing: 2px;
            border-color: grey;
        }
        table {
            border-spacing: 0;
            border-collapse: collapse;
        }

        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 2px;
            line-height: 1.42857143;
            /*vertical-align: top;*/
            /*border-top: 1px solid #ddd;*/
        }

        *{margin:0px; padding:0px;}
        body {
            font-family: 'Open Sans', sans-serif;
            font-weight: normal;
            color:#6f6f6f;
            font-size: 14px;
            position: relative;
        }
        ul li{list-style: none;}
        /*school Attendance Report*/
        .attendance-report .attendance-top-title{ text-align: center; }
        .attendance-report .attendance-top-title .attendance-title{ font-size: 36px; line-height: 50px; display: block;}
        .attendance-report .panel-info{padding-right: 15px; padding-left: 15px;}
        .attendance-top-logo img{margin-top: 10px; margin-bottom: 30px;}
        .attendance-top-title .attendance-address{line-height: 28px;}
        .attendance-report .attendance-details .table > tbody > tr > td{text-align: center;}
        .attendance-report .attendance-top-logo{padding: 0;}
        .details-center{text-align: center;}
        .atten-std-info{
            background-color:#d9edf7;
            text-align: center;
            padding-bottom: 6px;
            padding-top: 6px;
            border-bottom: 1px solid #dfdfdf;
            border-top: 1px solid #dfdfdf;
        }
        .atten-std-details{margin-bottom: 0; margin-top: 20px;}
        .page-main-title{
            background: #fcfcfc;
            padding-bottom: 15px;
            padding-top: 15px;
            box-shadow: 0px 0px 2px 0.1px;
            border: 0;
        }
        .std-title{text-align: center; display: block; padding-bottom: 2px; margin: 0;}
        /*//school Attendance Report*/
        /*student profile*/
        .attendance-report-info .area-panel{margin-top: 20px;}
        .stprofile-title h3{padding: 0; margin: 0; text-align: center; display: block;}
        .stprofile-title{margin-bottom: 34px; border-bottom: 1px solid #00a946; padding: 4px 0;}
        .stprofile-right .tb-ftbold>tbody tr td:first-child{color: #000; font-weight: 600;}
        .stprofile-right .tb-ftbold>tbody tr td:nth-child(4){color: #000; font-weight: 600;}
        .area-panel .stext-left h3{text-align: left; text-transform: uppercase; padding: 15px 0;}
        .area-panel .margin0{margin-bottom: 0;}
        .stprofile-left-inner h3{font-size: 20px; margin: 0; padding-top: 8px;}
        .stprofile-left-inner p{
            font-size: 16px;
            line-height: 24px;
            letter-spacing: 1px;
            color: #e98b59;
            padding: 20px 0;
        }


        .stprofile-left .emprofession{background:#009fd1; padding: 0 15px; padding-top: 1px; color: #fff; }
        .stprofile-left .emprofession h3{text-transform: uppercase;font-size: 20px;color: #fff;padding: 0;margin-bottom: 5px;}
        .stprofile-left .emprofession p{padding-bottom: 15px;}
        .text-uper h3,
        .emeducation-panel h3,
        .employer-info{text-transform: uppercase;}
        .stprofile-left .em-contact ul li{margin: 0; padding: 0; color: #fff; font-size: 16px;}
        .stprofile-left-bg{background: #212832; margin-top: -10px; display: block; padding-left: 15px; padding-right: 15px;}
        .em-contact,.em-follow h3{margin: 0; font-size: 20px; padding-top: 15px;}
        .emcontent-bg h3{color: #fff;}
        ul.emfollow-text{margin-top: 10px;}
        ul.emfollow-text b{line-height: 25px;}
        ul.emfollow-text li:last-child{padding-bottom: 35px;}
        ul.emfollow-text li{color: #c6c6c6;}
        .stprofile-left .em-contact strong{font-weight: 600; color: #009fd1;font-size: 16px;line-height: 26px;margin: 0;padding: 0;padding-top: 15px;}
        .stprofile-left .em-contact ul li p{line-height: 25px;}
        .emfollow-text{}
        .stprofile-right{padding-right: 15px;}
        .emeducation-panel h3{border-bottom: 1px solid #dfdfdf;}
        .emeducation-right h4{font-size: 16px; text-transform: uppercase;}

    </style>
</head>
<body>
<div class="main-page-wrapper">
    <div class="container">
        @if($employeeProfile AND $instituteInfo)
            {{--student enrollment--}}
            @php
                $present = $employeeProfile->user()->singleAddress("EMPLOYEE_PRESENT_ADDRESS");
                $permanent = $employeeProfile->user()->singleAddress("EMPLOYEE_PERMANENT_ADDRESS");
            @endphp
            <div class="row">
                <!--MAIN CONTENT-->
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row attendance-report">
                        <div class="panel panel-info attendance-report-info">
                            <div class="attendance-top">
                                <div class="col-md-2 col-sm-3 col-xs-12 attendance-top-logo pull-left">
                                    @if($instituteInfo->logo)
                                        <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}" width="100px" height="100px">
                                    @endif
                                </div>
                                <div class="col-md-10 col-sm-9 col-xs-12 attendance-top-title text-centert">
                                    <h1 class="attendance-title">{{$instituteInfo->institute_name}}</h1>
                                    <h3 class="attendance-address font16"><strong>{{$instituteInfo->address1}}</strong></h3>
                                    <p>
                                        <b>Phone: </b>{{$instituteInfo->phone}},
                                        <b>Email: </b>{{$instituteInfo->email}},
                                        <b> Website:</b> {{$instituteInfo->website}}
                                    </p>
                                </div>
                                <div class="clearfix"></div>
                                <div class="page-main-title">
                                    <h3 class="std-title label">Employee Profile</h3>
                                </div><!--//table-overflow-->
                                <div class="clearfix"></div>
                                <div class="row area-panel">
                                    <div class="col-md-3 col-sm-4 col-xs-12 stprofile-left">
                                        <div class="stprofile-img">
                                            @if($employeeProfile->singelAttachment("PROFILE_PHOTO"))
                                                <img class="img-thumbnail" src="{{asset('/assets/users/images/'.$employeeProfile->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image">
                                            @else
                                                <img class="img-thumbnail" src="{{asset('/assets/users/images/user-default.png')}}" alt="No Image">
                                            @endif
                                        </div>
                                        <div class="emprofession text-uper">
                                            <h3>Designation</h3>
                                            <p>{{$employeeProfile->designation()->name}}</p>
                                        </div>
                                        <div class="stprofile-left-bg">
                                            <div class="em-contact text-uper emcontent-bg">
                                                <h3>Contact</h3>
                                                <ul>
                                                    <li><strong class="content-empadd0">Phone</strong><br/><p>{{$employeeProfile->phone}}</p></li>
                                                    <li><strong>Email</strong><br/>
                                                        <p>{{$employeeProfile->email}}</p>
                                                        {{--<p>kazi.shariful.islam@alokitosoftware.com</p>--}}
                                                    </li>
                                                    <li><strong>Web</strong><br/><p>-</p></li>
                                                    <li><strong>Address</strong><br/><p>{{$present?$present->address:'-'}}</p></li>
                                                </ul>
                                            </div>
                                            {{--<div class="clearfix"></div>--}}
                                            {{--<div class="em-follow text-uper emcontent-bg">--}}
                                            {{--<h3>Follow me</h3>--}}
                                            {{--<ul class="emfollow-text">--}}
                                            {{--<li><b>Facebook</b></br>facebook.com/username</li>--}}
                                            {{--<li><b>Twitter</b></br>twitter.com/username</li>--}}
                                            {{--<li><b>Linkedin</b></br>linkedin.com/username</li>--}}
                                            {{--</ul>--}}
                                            {{--</div>--}}
                                        </div>
                                    </div>
                                    <div class="col-md-9 col-sm-8 stprofile-right">
                                        <div class="emprofile-title ">
                                            <h3 class="employer-info">Employee Information</h3>
                                            <hr/>
                                            <div class="stprofile-inner table-responsive">
                                                <table class="table tb-ftbold">
                                                    <tbody>
                                                    <tr>
                                                        <td width="15%">Name</td>
                                                        <td width="5%">:</td>
                                                        <td colspan="4">{{$employeeProfile->title." ". $employeeProfile->first_name." ".$employeeProfile->middle_name." ".$employeeProfile->last_name}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="15%">Gender</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">{{$employeeProfile->gender}}</td>
                                                        <td width="15%">Religion</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">
                                                            @php
                                                                switch($employeeProfile->religion) {
                                                                   case '1': echo "Islam"; break;
                                                                   case '2': echo "Hinduism"; break;
                                                                   case '3': echo "Christianity"; break;
                                                                   case '4': echo "Buddhism"; break;
                                                                   case '5': echo "Others"; break;
                                                                 }
                                                            @endphp
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td width="15%">Category</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">
                                                            @php
                                                                switch($employeeProfile->category) {
                                                                   case '1': echo "Teaching"; break;
                                                                   case '0': echo "Non-Teaching";  break;
                                                                }
                                                            @endphp
                                                        </td>
                                                        <td width="15%">Department</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">{{$employeeProfile->department()->name}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="15%">Blood Group</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">{{$employeeProfile->blood_group}}</td>
                                                        <td width="15%">Marital Status</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">{{$employeeProfile->marital_status}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="15%">Nationality</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">{{$employeeProfile->nationality()?$employeeProfile->nationality()->nationality:'-'}}</td>
                                                        <td width="15%">Birthday</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">{{ date('F d, Y', strtotime($employeeProfile->dob)) }}</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div><!--//table-overflow-->
                                        </div><!--//emprofile-title-->
                                        <div class="clearfix"></div>
                                        <div class="emprofile-title ">
                                            <h3 class="employer-info">Permanent Address</h3>
                                            <hr/>
                                            <div class="stprofile-inner table-responsive">
                                                <table class="table tb-ftbold">
                                                    <tbody>
                                                    <tr>
                                                        <td width="15%">Address</td>
                                                        <td width="5%">:</td>
                                                        <td colspan="4">@if($permanent){{$permanent->address}}@else - @endif</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="15%">House No</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">@if($permanent){{$permanent->house}}@else - @endif</td>
                                                        <td width="15%">City</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">@if($permanent){{$permanent->city()->name}}@else - @endif</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="15%">State</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">@if($permanent){{$permanent->state()->name}}@else - @endif</td>
                                                        <td width="15%">Country</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">@if($permanent){{$permanent->country()->name}}@else - @endif</td>
                                                    </tr>
                                                    <tr>
                                                        <td width="15%">Zip Code</td>
                                                        <td width="5%">:</td>
                                                        <td width="30%">@if($permanent){{$permanent->zip}}@else - @endif</td>
                                                    </tr>
                                                    </tbody>
                                                </table>
                                            </div><!--//table-overflow-->
                                        </div><!--//emprofile-title-->
                                    </div><!--//stprofile-right-->
                                </div><!--//attendance-top-->
                            </div>
                        </div><!--//attendance-report-->
                    </div> <!-- /.col-12- -->
                    <!--//MAIN CONTENT-->
                </div>
                @else

                @endif
            </div>
    </div> <!-- /.main-page-wrapper -->
</div> <!-- /.main-page-wrapper -->
</body>
</html>
