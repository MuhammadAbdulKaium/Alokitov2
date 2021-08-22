<html lang="en"><head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta charset="utf-8">
    <title>Student id view</title>

    <meta name="description" content="overview &amp; stats">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <!-- bootstrap & fontawesome -->
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    {{--<link rel="stylesheet" href="css/custom.css">--}}
    <link rel="stylesheet" href="css/report.css">
    <style>

        @media print {
            .table td, .table th {
                background: none !important;
            }
        }
        .report_school_name{
            font-size:28px !important;
        }
        .report_school_adrs {
            font-size:14px !important;
        }

        .reportTable tr td {
            border:1px solid  #000000 !important;
        }
        .reportTable tr th {
            border:1px solid  #000000 !important;
        }
        .noMargin {
            margin:0px !important;
        }

        .idStyle {
            border: 2px solid #a1a1a1;
            background: #dddddd;
            width: 300px;
            background:#FFFBDC;
        }
        .mainWidth {
            margin: auto;
            width: 864px;
        }
        .overflow {overflow: hidden}
        .singId {position: relative;}
        .imageId {
            height: 315px;
            margin: 5px 10px;
            width: 412px;
        }
        .imageId img {width:100%; height: 100%}
        .floatLeft {float: left}
        .content {
            height: 312px;
            left: 12px;
            position: absolute;
            top: 5px;
            width: 410px;
            z-index: 100;
        }
        .stdImgArea {margin-top: 90px;}
        .stdImg {
            height: 73px;
            width: 79px;
            margin: 0 auto;
            margin-top: -10px;
            margin-left: 60px;
        }
        .stdImg img {width:100%; border-radius: 50%;}
        .singContent {height: 100%;width: 200px; float:left;}
        .contData h4 {
            font-size: 12px;
            font-weight: bold;
            margin: 4px;
            margin-top: 20px;
        }
        .contData table {font-size:11px; margin:0}
        .contData table tbody tr td h4 {margin:3px 0;}
        .contData table tbody tr td {padding: 2px 5px !important;}
        .signatueImage {line-height: 15px;margin-top: 7px;padding-right: 5px;}
        .signatueImage img {width: 40px;}
        .signatueImage p {font-size: 11px;margin: 0;}
        .parmanentAddress {font-size:11px;padding: 5px; margin-top: 20px}
        .notes {}
        .notes h3 {
            border-bottom: 1px solid;
            border-top: 1px solid;
            font-size: 15px;
            margin: auto;
            padding: 5px;
            text-transform: uppercase;
            width: 52px;
        }
        .returnCard {margin-top: 40px;}
        .returnCard p {margin: 0; font-size: 10px}
        .returnCard h4 {margin: 4px 0; font-size: 14px !important; font-weight: bold}
        .returnCard h3 {margin: 4px 0; font-size: 12px !important; font-weight: bold}
        p.cardValidti {
            bottom: 0 !important;
            color: red;
            position: absolute;
            width: 190px;
        }
        .parmanentAddress h4  {
            font-size: 12px !important;
            text-align: center;
            font-weight: bold;
        }
        .table>tbody>tr>td {
            border: none;
        }


    </style>
<body>
<div class="col-md-10 col-md-offset-1">
    <div class="text-center overflow mainWidth">
        <div class="col-md-12 headerContent">
            <div class="row brdrBottom">
                <div class="report_school_name text-center">{{$instituteInfo->institute_name}}</div>
                <div class="report_school_adrs text-center">{{$instituteInfo->address1}}</div>
            </div>
        </div>
        <h4 class="text-center"><u>Student Id List</u></h4>
        @php $count=0; @endphp
        @foreach($studentList as $index=>$stdInfo)
            {{--student details--}}
            @php $studentInfo = findStudent($stdInfo['std_id']) @endphp
            @php $studentEnroll = $studentInfo->enroll(); @endphp
            <div class="singId floatLeft">
                <div class="imageId ">
                    <img src="{{asset('/assets/id-card/afeac.jpg')}}" alt="">
                </div>
                <div class="content">
                    <div class="singContent">
                        <div class="parmanentAddress text-left">
                            <i>
                                <h4>Student Information</h4>
                                {{--parents information--}}
                                @php $parents = $studentInfo->myGuardians(); $gurdianAddess=''; $contact='';@endphp
                                {{--checking--}}
                                <p>
                                    @if($parents->count()>0)
                                        @foreach($parents as $index=>$parent)
                                            @php $guardian = $parent->guardian(); $gurdianAddess=$guardian->home_address; @endphp
                                            <b>{{$index%2==0?"Father":"Mother"}} :</b> {{$guardian->title." ".$guardian->first_name." ".$guardian->last_name}}<br>
                                            @if($parent->is_emergency == 1)
                                                @php $contact=$guardian->mobile @endphp
                                            @endif
                                        @endforeach
                                    @endif
                                    <b>Mobile No:</b>{{$contact}} <br>
                                <b>Date of Birth:</b>{{date('d-m-Y',strtotime($studentInfo->dob))}}
                                </p>




                            </i>

                            <div class="returnCard  text-center">
                                {{--<p>If Found Please Return This Card to</p>--}}
                                <h4>AFEAC</h4>
                                  <h3>  Residential Model School</h3>
                                <p>Parbotipur-Badarganj, Parbatipur, Dinajpur<br>
                                    <b>Mobile No : </b>02-9471487 <br>
                                    Email: info@afeac.net<br>
                                   Website: www.afeac.edu.bd

                                </p>

                                <div class="signatueImage text-center">
                                    <img src="{{URL::to('/assets/signature',$templateProfile->signature)}}" alt="">
                                    <p class="text-center">Head Master</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="singContent" style="margin-left: 9px;">
                        <div class="stdImgArea text-center">
                            <div class="stdImg ">
                                <!-- <img src="http://192.168.1.47/syedahmed/resource/stu_photo/1531371065.jpg" width="70" height="80">  -->
                                @if($studentInfo->singelAttachment('PROFILE_PHOTO'))
                                    <img width="70" height="80" src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
                                @else
                                    <img width="70" height="80" src="{{asset('/assets/users/images/user-default.png')}}" >
                                @endif
                            </div>



                        </div>
                        <div class="contData">
                            <h4 class="text-center">{{$stdInfo->first_name." ".$stdInfo->middle_name." ".$stdInfo->last_name}}</h4>
                            <table class="table text-left">
                                <tbody>
                                <tr>
                                    <td width="40%">Class</td>
                                    <td colspan="3">: {{$studentEnroll->batch()->batch_name}}</td>
                                </tr>
                                <tr>
                                    <td>Section</td>
                                    <td colspan="3">: {{$studentEnroll->section()->section_name}}</td>
                                </tr>
                                {{--<tr class="hide">--}}
                                    {{--<td>Group</td>--}}
                                    {{--<td>--}}
                                    {{--</td>--}}
                                    {{--<td>Roll</td>--}}
                                    {{--<td></td>--}}
                                {{--</tr>--}}
                                <tr>
                                    <td>Roll</td>
                                    <td colspan="3">: {{$stdInfo->gr_no}}</td>
                                </tr>
                                <tr>
                                    <td>Blood Group</td>
                                    <td colspan="3">: {{$studentInfo->blood_group }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @php $count++; @endphp
            @if($count==4)
                @php $count=0; @endphp
                <div style="page-break-after:always;"></div>
            @endif

        @endforeach
    </div>
</div>


</body>
</html>