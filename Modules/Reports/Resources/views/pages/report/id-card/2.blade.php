<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student ID Card</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
</head>
<body>

<style>

    @media print {
        .temp_one_font_side{
            border: 2px solid #3498db !important;
        }
        .tem_id_header {
            background: #3498db !important;
        }


        .temp_one_font_side{
            border: 2px solid #3498db !important;
        }
        .temp_one_backSide {
            border: 2px solid #3498db !important;
        }
        .tem_id_header {
            background: #3498db !important;
        }

        .tem_one_note p {
            border-bottom: 1px solid #3498db !important;
            border-top: 1px solid #3498db !important;
        }
        .tem_one_footer {
            background: #3498db !important;
        }

        .temp_one_id-card-heading {
            background: #3498db !important;
        }

        .temp_one_id-card-heading p{
            color: #fff !important;
        }

    }

    .temp_one_font_side{
        width: 250px;
        height: 350px;
        border: 2px solid #3498db;
        float: left;
        margin-top: 10px;
        margin-left: 15px;
    }
    .temp_one_backSide {
        float: left;
        width: 250px;
        height: 350px;
        border: 2px solid #3498db;
        margin-left: 30px;
        margin-top: 10px;
    }
    .tem_id_header {
        background: #3498db;
        height: 40px;
        text-align: center;
    }
    .school-logo {
        margin-top: 10px;
        height: 60px;
    }
    .temp_one_institute_name {
        margin-top: 32px;
        text-align: center;
        font-size: 16px;
        font-weight: bold;
        padding: 0px;
        line-height: 2px;
    }
    .temp_one_address_name {
        font-size: 12px;
        line-height: 10px;
        text-align: center;
    }
    .temp_one_id-card-heading {
        width: 70%;
        margin: 0 auto;
        background: blue;
        color: #fff;
        font-weight: 700;
        text-align: center;
        font-size: 12px;
    }

    .temp_one_student_name {
        text-align: center;
        font-size: 12px;
        font-weight: bold;
        margin-top: 5px;
    }
    .table {
        font-size: 12px;
        width: 90%;
        margin: 0 auto;
    }
    .table>tbody>tr>td {
        padding: 1px !important;
    }
    .temp_one_principal_section {
        float: right;
        margin-right: 15px;
        height: 46px;
    }
    .temp_one_principal {
        font-size: 12px;
    }
    .tem_one_footer {
        background: #3498db;
        height: 5px;
        clear: both;
    }
    .tem_one_per {
        font-size: 20px;
        font-style: italic;
        padding: 5px;
        text-align: center;
    }
    .father_info p {
        font-size: 12px;
        font-style: italic;
        padding-left: 5px;
        margin: 0px;
    }
    .tem_one_note {
        width: 40%;
        text-align: center;
        margin: 0 auto; }
    .tem_one_note p {
        border-bottom: 1px solid #3498db;
        border-top: 1px solid #3498db;
        font-size: 20px;
    }
    .tem_one_retun_address {
        text-align: center;
        line-height: 12px;
        font-size: 12px;
    }
    .temp_one_back_institute_name {
        font-size: 14px;
        font-weight: 600;
    }
    .temp_one_valid {
        margin-top: 40px;
        font-size: 12px;
        text-align: center;
    }
    .temp_one_all_content {
        height: 341px;
    }
    .box-footer {
        border: none !important;
    }
    .studentIDCard {
        padding-left: 40px;
    }

</style>
<div class="row">
    @php $count=0; @endphp
    @foreach($studentList as $index=>$stdInfo)
        {{--student details--}}
        @php $studentInfo = findStudent($stdInfo['std_id']) @endphp
        @php $studentEnroll = $studentInfo->enroll(); @endphp
        <div class="col-md-6 studentIDCard">
            <div class="temp_one_font_side">
                <div class="temp_one_all_content">
                    <div class="tem_id_header">
                        <img class="school-logo" src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
                    </div>
                    <p class="temp_one_institute_name">{{$instituteInfo->institute_name}}</p>
                    <p class="temp_one_address_name">{{$instituteInfo->address1}}</p>
                    <div class="temp_one_id-card-heading">
                        <p>STUDENT'S IDENTITY CARD</p>
                    </div>

                    <div class="temp_one_profile text-center">
                        @if($studentInfo->singelAttachment('PROFILE_PHOTO'))
                            <img class="temp_one_profile_pic" style="height:60px;width: 70px" src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
                        @else
                            <img  src="{{asset('/assets/users/images/user-default.png')}}" style="height:60px;">
                        @endif
                    </div>
                    <p class="temp_one_student_name">{{$stdInfo->first_name." ".$stdInfo->middle_name." ".$stdInfo->last_name}}</p>
                    <table class="table table-bordered">
                        <tr>
                            <td>Class</td>
                            <td>{{$studentEnroll->batch()->batch_name}}</td>
                        </tr>
                        <tr>
                            <td>ID</td>
                            <td>{{$stdInfo->username}}</td>
                        </tr>
                        <tr>
                            <td>Roll</td>
                            <td>{{$stdInfo->gr_no}}</td>
                        </tr>
                    </table>
                    <div class="temp_one_principal_section">
                        <img class="temp_one_principal_sig" style="height: 30px" width="45px" src="{{URL::to('/assets/signature',$templateProfile->signature)}}">
                        <p class="temp_one_principal">Principal</p>
                    </div>
                </div>
                <div class="tem_one_footer">

                </div>

            </div>

            <div class="temp_one_backSide">
                <div class="temp_one_all_content">
                    <p class="tem_one_per">Permanent Address</p>
                    <div class="father_info">
                        {{--parents information--}}
                        @php $parents = $studentInfo->myGuardians(); $gurdianAddess=''; @endphp
                        {{--checking--}}
                        @if($parents->count()>0)
                            @foreach($parents as $index=>$parent)
                                @php $guardian = $parent->guardian(); $gurdianAddess=$guardian->home_address; @endphp
                                <p><strong>{{$index%2==0?"Father's Name":"Mother's Name"}} :</strong> {{$guardian->title." ".$guardian->first_name." ".$guardian->last_name}}</p>
                            @endforeach
                        @endif
                        @if(!empty($stdInfo->studentAddress()))
                            <p><strong>Address:</strong> {{$stdInfo->studentAddress()->address}}</p>
                        @endif

                        <p><strong>Mobile No:{{$studentInfo->phone}}</strong> </p>
                    </div>
                    <div class="tem_one_note">
                        <p>NOTE</p>
                    </div>
                    <div class="tem_one_retun_address">
                        <p>If Found Please Return This Card to</p>
                        <p class="temp_one_back_institute_name">{{$instituteInfo->institute_name}}</p>
                        <p>{{$instituteInfo->address1}} </p>
                        <p><strong>Mobile No:</strong>{{$instituteInfo->phone}} </p>
                        <p>or the Nearest Police Station </p>
                    </div>

                    <p class="temp_one_valid">This card is valid till {{$templateProfile->idcard_valid}}</p>

                </div>
                <div class="tem_one_footer">

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

</body>
</html>