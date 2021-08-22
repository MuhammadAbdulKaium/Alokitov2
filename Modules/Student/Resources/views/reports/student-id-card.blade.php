<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {{--student information--}}
    <style>
        .inst-logo{
            width: 20%;
        }
        .inst-logo img{
            width: 55px;
            height:50px;
        }

        .inst-info{
            width: 79%;
        }

        .inst-name{
            font-size: 16px;
            font-weight: 900;
        }

        .id-card-label {
            border: 1px solid black;
            border-radius: 2px;
            font-size: 15px;
            font-weight: 700;
            margin: 5px 0px;
        }

        .std-info{
            font-size: 13px;
        }

        .std-photo{

        }

        .std-photo img{
            width: 80px;
            height: 90px;
        }


        .clear{
            clear: both;
        }

        .pull-left{
            float: left;
        }

        .pull-right{
            float: right;
        }

        .text-center {
            text-align: center;
        }


        .id-card-row {
            width: 100%;
            margin-bottom: 20px;
        }

        .first-col{
            width: 49%;
        }

        .second-col{
            width: 49%;
        }

        .id-card-wrapper{
            border-radius: 5px;
            margin-bottom: 5px;
            width: 325px;
            height: 190px;
            background-color: gainsboro;
            padding:10px;
        }
    </style>
</head>
<body>
<div class="id-card-row clear">
    @php $studentInfo = $stdProfile->student() @endphp
    @php $studentEnroll = $studentInfo->enroll(); @endphp
    {{--std ID Card--}}
    <div class="id-card-wrapper">
        <div class="id-card-row">
            <div class="inst-logo pull-left">
                @if($instituteInfo->logo)
                    <img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}">
                @endif
            </div>
            <div class="inst-info text-center pull-right">
                <b class="inst-name">{{$instituteInfo->institute_name}}</b> <br/>
                <span style="font-size: 13px">{{$instituteInfo->address1}}</span>
            </div>
        </div>
        <br><br>
        <div class="id-card-row clear">
            <div class="id-card-label text-center clear">Student ID Card</div>
            <div class="col-sm-8 std-info text-left pull-left">
                <table cellspacing="1" cellpadding="1">
                    <tbody>
                    <tr> <th>Name</th> <td>: {{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</td> </tr>
                    <tr> <th>Roll</th> <td>: {{$studentInfo->enroll()->gr_no}}</td> </tr>
                    <tr> <th>Year</th><td>: {{$studentEnroll->academicsYear()->year_name}}</td></tr>
                    <tr> <th>Level</th><td>: {{$studentEnroll->level()->level_name}}</td></tr>
                    <tr> <th>Class </th><td>: {{$studentEnroll->batch()->batch_name}}</td></tr>
                    <tr> <th>Section</th><td>: {{$studentEnroll->section()->section_name}}</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="col-sm-4 std-photo pull-right">
                {{--check std profile photo--}}
                @if($studentInfo->singelAttachment('PROFILE_PHOTO'))
                    <img src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
                @else
                    <img  src="{{public_path().'/assets/users/images/user-default.png'}}">
                @endif
            </div>
        </div>
    </div>
</div>

</body>
</html>
