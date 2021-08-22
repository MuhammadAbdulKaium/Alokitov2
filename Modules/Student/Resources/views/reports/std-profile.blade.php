<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {{--<link href="{{ URL::asset('template-2/css/main.css') }}" rel="stylesheet" type="text/css"/>--}}

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
        .pull-right{float: right;}
        .text-center {text-align: center;}
        .img-thumbnail {
            display: inline-block;
            max-width: 100%;
            height: auto;
            padding: 4px;
            line-height: 1.42857143;
            background-color: #fff;
            border: 1px solid #ddd;
            border-radius: 4px;
            -webkit-transition: all .2s ease-in-out;
            -o-transition: all .2s ease-in-out;
            transition: all .2s ease-in-out;
        }
        .col-md-3 {
            width: 24%;
        }
        .col-md-9 {
            width: 74%;
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
        /*table {*/
        /*border-spacing: 0;*/
        /*border-collapse: collapse;*/

        /*}*/



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
        .stprofile-img img{text-align: center; display: block; margin: 0 auto; margin-bottom: 10px; width: 200px; height: 200px;}

    </style>
</head>
<body>
<div class="main-page-wrapper">
    <div class="container">
        @if($studentProfile AND $instituteInfo)
            {{--student enrollment--}}
            @php
                $stdEnroll = $studentProfile->enroll();
                $present = $studentProfile->user()->singleAddress("STUDENT_PRESENT_ADDRESS");
                $permanent = $studentProfile->user()->singleAddress("STUDENT_PERMANENT_ADDRESS");
                $parents = $studentProfile->myGuardians();
            @endphp
            <div class="row">
                <!--MAIN CONTENT-->
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row attendance-report">
                        <div class="panel panel-info attendance-report-info">
                            <div class="attendance-top">
                                <div class="col-md-2 col-sm-3 col-xs-12 attendance-top-logo pull-left">
                                    @if($instituteInfo->logo)
                                        {{--<img src="{{URL::asset('assets/users/images/'.$instituteInfo->logo)}}"  style="width:80px;height:80px">--}}
                                        <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}" width="150">
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
                            </div><!--//attendance-top-->
                            <div class="page-main-title">
                                <h3 class="label text-center">Student Profile</h3>
                            </div><!--//table-overflow-->
                            <div class="clearfix"></div>
                            <div class="row area-panel">
                                <div class="col-md-3 col-sm-4 col-xs-12 stprofile-left pull-leftp pull-left">
                                    <div class="stprofile-img">
                                        {{--<img class="img-thumbnail" src="images/st6.jpg" alt="Profile iamge">--}}
                                        @if($studentProfile->singelAttachment('PROFILE_PHOTO'))
                                            <img class="img-thumbnail" src="{{asset('/assets/users/images/'.$studentProfile->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
                                            {{--<img class="img-thumbnail" src="{{public_path().'/assets/users/images/'.$studentProfile->singelAttachment('PROFILE_PHOTO')->singleContent()->name}}">--}}
                                        @else
                                            <img class="img-thumbnail" src="{{asset('/assets/users/images/user-default.png')}}">
                                            {{--<img class="img-thumbnail" src="{{public_path().'/assets/users/images/user-default.png'}}">--}}
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-9 col-sm-8 stprofile-right pull-right">
                                    <div class="stprofile-title">
                                        <h3 style="font-size: 18px">{{$stdEnroll->batch()->batch_name.' ('.$stdEnroll->section()->section_name.")"}}</h3>
                                    </div>
                                    <div class="stprofile-inner table-responsive">
                                        <table class="table tb-ftbold">
                                            <tbody>
                                            <tr>
                                                <td width="15%">Roll</td>
                                                <td width="5%">:</td>
                                                <td width="30%">{{$stdEnroll->gr_no}}</td>
                                                <td width="15%">Email</td>
                                                <td width="5%">:</td>
                                                <td width="30%">{{$studentProfile->email}}</td>
                                            </tr>
                                            <tr>
                                                <td width="15%">Name</td>
                                                <td width="5%">:</td>
                                                <td width="30%">{{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}}</td>
                                                <td width="15%">Gender</td>
                                                <td width="5%">:</td>
                                                <td width="30%">{{$studentProfile->gender}}</td>
                                            </tr>
                                            <tr>
                                                <td width="15%">Brithday</td>
                                                <td width="5%">:</td>
                                                <td width="30%">{{date('d M, Y', strtotime($studentProfile->dob))}}</td>
                                                <td width="15%">Religion</td>
                                                <td width="5%">:</td>
                                                <td width="30%">{{$studentProfile->religion}}</td>
                                            </tr>
                                            <tr>
                                                <td width="15%">Phone</td>
                                                <td width="5%">:</td>
                                                <td width="30%">{{$studentProfile->phone}}</td>
                                                <td width="15%">Blood</td>
                                                <td width="5%">:</td>
                                                <td width="30%">{{$studentProfile->blood_group}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div><!--//table-overflow-->
                                </div>
                            </div> <!--//area-panel-->
                            <div class="clearfix"></div>
                            <div class="row area-panel">
                                <div class="col-md-12 col-xs-12">
                                    <div class="stprofile-title stparent stext-left margin0">
                                        <h3>Parents</h3>
                                    </div>

                                    @foreach($parents as $index=>$parent)
                                        @php $guardian = $parent->guardian(); @endphp
                                        <div class="row clear" style="margin-top: 10px">
                                            <div class="col-md-3 col-sm-4 col-xs-12 stprofile-left pull-left">
                                                <div class="stprofile-left-inner">
                                                    <h3 class="stparent-title">Guardian {{$index+1}}</h3>
                                                    @if($parent->is_emergency == 1)
                                                        <p>Emergency Contact</p>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="col-md-9 col-sm-8 col-xs-12 stprofile-right pull-right">
                                                @if($index>0) <hr/> @endif
                                                <div class="stprofile-inner table-responsive" style="{{$index>0?'margin-top: 10px':''}}">
                                                    <table class="table tb-ftbold">
                                                        <tbody>
                                                        <tr>
                                                            <td width="15%">Full name</td>
                                                            <td width="5%">:</td>
                                                            <td colspan="3">{{$guardian->title." ".$guardian->first_name." ".$guardian->last_name}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="15%">Email</td>
                                                            <td width="5%">:</td>
                                                            <td colspan="3">{{$guardian->email}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="15%">Cell</td>
                                                            <td width="5%">:</td>
                                                            <td width="30%">{{$guardian->mobile}}</td>
                                                            <td width="15%">Phone</td>
                                                            <td width="5%">:</td>
                                                            <td width="30">{{$guardian->phone}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="15%">Qualification</td>
                                                            <td width="5%">:</td>
                                                            <td width="30%">{{$guardian->qualification}}</td>
                                                            <td width="15%">Occupation</td>
                                                            <td width="5%">:</td>
                                                            <td width="30%">{{$guardian->occupation}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="15%">H. Address</td>
                                                            <td width="5%">:</td>
                                                            <td width="30%" colspan="4">{{$guardian->home_address}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="15%">O. Address</td>
                                                            <td width="5%">:</td>
                                                            <td width="30%" colspan="4">{{$guardian->office_address}}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div><!--//table-overflow-->
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div> <!--//area-panel-->
                            <div class="clearfix"></div>
                            <div class="row area-panel">
                                <div class="col-md-12 col-xs-12">
                                    <div class="stprofile-title stparent stext-left margin0">
                                        <h3>Address</h3>
                                    </div>
                                    @if($present)
                                        <div class="row" style="margin-top: 10px">
                                            <div class="col-md-3 col-sm-4 col-xs-12 stprofile-left pull-left">
                                                <div class="stprofile-left-inner">
                                                    <h3 class="stparent-title">Current Address</h3>
                                                </div>
                                            </div>
                                            <div class="col-md-9 col-sm-8 col-xs-12 stprofile-right pull-right">
                                                <div class="stprofile-inner table-responsive">
                                                    <table class="table tb-ftbold">
                                                        <tbody>
                                                        <tr>
                                                            <td width="15%">Address</td>
                                                            <td width="5%">:</td>
                                                            <td width="30%" colspan="4">{{$present->address}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="15%">State</td>
                                                            <td width="5%">:</td>
                                                            <td width="30%">{{$present->state()?$present->state()->name:''}}</td>
                                                            <td width="15%">City</td>
                                                            <td width="5%">:</td>
                                                            <td width="30%">{{$present->city()?$present->city()->name:''}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="15%">Country</td>
                                                            <td width="5%">:</td>
                                                            <td width="30%">{{$present->country()?$present->country()->name:''}}</td>
                                                            <td width="15%">House No</td>
                                                            <td width="5%">:</td>
                                                            <td width="30">{{$present->house}}</td>
                                                        </tr>
                                                        <tr>
                                                            <td width="15%">Zip Code</td>
                                                            <td width="5%">:</td>
                                                            <td width="30%">{{$present->zip}}</td>
                                                            <td width="15%">Phone</td>
                                                            <td width="5%">:</td>
                                                            <td width="30%">{{$present->phone}}</td>
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div><!--//table-overflow-->
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    @endif

                                    @if($permanent)
                                        <div class="row" style="margin-top: 10px">
                                            <div class="col-md-3 col-sm-4 col-xs-12 stprofile-left pull-left">
                                                <div class="stprofile-left-inner">
                                                    <h3 class="stparent-title">Permanent Address</h3>
                                                </div>
                                            </div>
                                            <div class="col-md-9 col-sm-8 col-xs-12 stprofile-right pull-right">
                                                @if($present)<hr/>@endif
                                                <div class="stprofile-inner table-responsive" style="margin-top: 10px">
                                                    <div class="stprofile-inner table-responsive">
                                                        <table class="table tb-ftbold">
                                                            <tbody>
                                                            <tr>
                                                                <td width="15%">Address</td>
                                                                <td width="5%">:</td>
                                                                <td width="30%" colspan="4">{{$permanent->address}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%">State</td>
                                                                <td width="5%">:</td>
                                                                <td width="30%">{{$permanent->state()?$permanent->state()->name:''}}</td>
                                                                <td width="15%">City</td>
                                                                <td width="5%">:</td>
                                                                <td width="30%">{{$permanent->city()?$permanent->city()->name:''}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%">Country</td>
                                                                <td width="5%">:</td>
                                                                <td width="30%">{{$permanent->country()?$permanent->country()->name:''}}</td>
                                                                <td width="15%">House No</td>
                                                                <td width="5%">:</td>
                                                                <td width="30">{{$permanent->house}}</td>
                                                            </tr>
                                                            <tr>
                                                                <td width="15%">Zip Code</td>
                                                                <td width="5%">:</td>
                                                                <td width="30%">{{$permanent->zip}}</td>
                                                                <td width="15%">Phone</td>
                                                                <td width="5%">:</td>
                                                                <td width="30%">{{$permanent->phone}}</td>
                                                            </tr>
                                                            </tbody>
                                                        </table>
                                                    </div><!--//table-overflow-->
                                                </div><!--//table-overflow-->
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    @endif
                                </div>
                            </div> <!--//area-panel-->
                        </div><!--//attendance-report-info-->
                    </div><!--//attendance-report-->
                </div> <!-- /.col-12- -->
                <!--//MAIN CONTENT-->
            </div>
        @else

        @endif
    </div>
</div> <!-- /.main-page-wrapper -->
</body>
</html>
