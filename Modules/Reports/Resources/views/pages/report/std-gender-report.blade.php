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
        .col-md-12 {
            width: 100%;
        }


        /*.table-bordered {*/
        /*border: 1px solid #ddd;*/
        /*}*/

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

        .h3, h3 {
            font-size: 24px;
        }

        .table-bordered {
            border: 1px solid #ddd;
        }
        .table-responsive {
            min-height: .01%;
            overflow-x: auto;
        }

        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
            padding: 5px;
            line-height: 1.42857143;
            /*vertical-align: top;*/
            border: 1px solid #ddd;
        }

        /*.table {*/
        /*width: 100%;*/
        /*max-width: 100%;*/
        /*margin-bottom: 20px;*/
        /*}*/
        /*table {*/
        /*background-color: transparent;*/
        /*}*/
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


        /*thead{display: table-header-group;}*/
        /*tfoot {display: table-row-group;}*/
        /*tr {page-break-inside: avoid;}*/
        th,td {line-height: 20px;}
        html{margin-top:30px}

    </style>
</head>
<body>
<div class="main-page-wrapper">
    <div class="container">
        @if($studentList->count()>0 AND $instituteInfo)
            <div class="row">
                <!--MAIN CONTENT-->
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row attendance-report">
                        <div class="panel panel-info attendance-report-info">
                            <div class="attendance-top">
                                <div class="col-md-2 col-sm-3 col-xs-12 attendance-top-logo pull-left">
                                    {{--<img src="images/logos/logo.png" alt="image" width="150">--}}
                                    @if($instituteInfo->logo)
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
                            <div class="row section-seven">
                                <div class="col-md-12 col-xs-12 attendance-details">
                                    <div class="table-overflow table-responsive">
                                        <h3 class="atten-std-info atten-std-details font16">{{$reportDetails->class_name.' ('.$reportDetails->section_name.')'}} Gender Report</h3>

                                        <table class="table table-bordered table-hover">
                                            <thead>
                                            <tr>
                                                <th class="details-center">#</th>
                                                <th class="details-center">Roll</th>
                                                <th class="details-center">Name</th>
                                                <th class="details-center">Gender</th>
                                            </tr>
                                            </thead>

                                            <tbody>
                                            @foreach($studentList as $index=>$std)
                                                <tr>
                                                    <td>{{($index+1)}}</td>
                                                    <td>{{$std->gr_no}}</td>
                                                    <td>{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}</td>
                                                    <td>{{$std->gender}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div><!--//table-overflow-->
                                </div><!--//attendance-details-->
                            </div><!--//row section-seven-->
                        </div><!--//attendance-report-info-->
                    </div><!--//attendance-report-->
                </div> <!-- /.col-12- -->
                <!--//MAIN CONTENT-->
            </div>
        @else
            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <p class="text-center">No Student found</p>
                </div>
            </div>
        @endif
    </div>
</div> <!-- /.main-page-wrapper -->
</body>
</html>
