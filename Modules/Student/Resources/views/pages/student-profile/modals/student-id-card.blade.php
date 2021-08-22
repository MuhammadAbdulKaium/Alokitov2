@php
    $tempSetting = $templateProfile?json_decode($templateProfile->setting):[];
	$tempType =  $templateProfile?$templateProfile->temp_type:[];
@endphp


<style>

    .id-card-one{
        /*border: 1px solid red;*/
        color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}};;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 5px;
        width: {{$tempSetting?($tempType==0?($tempSetting->width.'px'):'350px'):'350px'}};
        height: {{$tempSetting?($tempType==0?($tempSetting->height.'px'):'210px'):'210px'}};
        background-color: {{$tempSetting?($tempType==0?($tempSetting->color):'#a3cde7'):'#a3cde7'}};
        -webkit-box-shadow: 3px 0px 162px -17px rgba(194,186,194,0.76);
        -moz-box-shadow: 3px 0px 162px -17px rgba(194,186,194,0.76);
        box-shadow: 3px 0px 162px -17px rgba(194,186,194,0.76);
    }

    .id-card-two{
        /*border: 1px solid red;*/
        color: {{$tempSetting?($tempType==1?($tempSetting->font_color):'#000000'):'#000000'}};
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 5px;
        width: {{$tempSetting?($tempType==1?($tempSetting->width.'px'):'250px'):'250px'}};
        height: {{$tempSetting?($tempType==1?($tempSetting->height.'px'):'350px'):'350px'}};
        background-color: {{$tempSetting?($tempType==1?($tempSetting->color):'#a3cde7'):'#a3cde7'}};
        -webkit-box-shadow: 3px 0px 162px -17px rgba(194,186,194,0.76);
        -moz-box-shadow: 3px 0px 162px -17px rgba(194,186,194,0.76);
        box-shadow: 3px 0px 162px -17px rgba(194,186,194,0.76);
    }

    .inst-logo img{
        width: 55px;
        height:50px;
    }

    .inst-logo-portrait img{
        width: 90px;
        height: 75px;
    }

    .inst-info{

    }

    .inst-name{
        font-size: {{$tempSetting?($tempType==0?($tempSetting->title_font.'px'):'20px'):'20px'}};
        font-weight: 700;
    }

    .inst-name-portrait{
        font-size: {{$tempSetting?($tempType==0?($tempSetting->title_font.'px'):'17px'):'17px'}};
        font-weight: 700;
    }

    .id-card-label {
        border: 1px solid black;
        border-radius: 2px;
        font-size: 15px;
        font-weight: 700;
        margin-top: 5px;
        text-align: center;
    }

    .std-info{
        font-size: 13px;
        padding:0 0 0 15px;
    }

    .std-photo{
        padding:0 0 0 0;
    }

    .std-photo img{
        width: 80px;
        height: 90px;
        margin-left: 25px;
    }

    .std-photo-portrait img{
        width: 80px;
        height: 90px;
        margin-left: 70px;
    }

    .std-address-first{ margin-top: 10px}

    .std-address{
        font-size: 13px;
        line-height: 10px;
    }

    .std-address-portrait{
        font-size: 12px;
        line-height: 8px;
    }

    hr{
        margin-top: 5px;
        margin-bottom: 5px;
    }



    /*id card 2 design section */

    .inst-logo-2 {
        width: 25%;
        float: left;
        height: 60px;
    }

    .inst-logo-2 img {
        width: 70px;
        height: 60px;
    }

    .inst-info-2 {
        width: 68%;
        float: left;
        margin-left: 10px;
        border-bottom: 1px solid blue;
    }

    .std-photo-2 img {
        margin-top: 15px !important;
        margin-left: 10px !important;
        width:80px !important;
        height:90px;
        border: 2px solid blue;
    }
    .std-info-2 {
        margin-top: 10px !important;
        padding-left: 10px !important;
    }
    .principal p {
        float: right;
        margin-right: 20px;
        font-size: 14px;
        font-weight: bold;
        color: blue;
    }

    #opacity-image{
        height: 200px;
        width: 200px;
        display: block;
        position: relative;

    }
    #opacity-image:after{
        background: url("{{asset('/assets/users/images/'.$instituteInfo->logo)}}");
        background-repeat: no-repeat;
        background-size: 100% auto;
        height: 224px;
        width: 170px;
        opacity: 0.1;
        top: 35px;
        left: 120px;
        position: absolute;
        z-index: 0;
        content: "";
    }

    .square{
        margin: 5px;
        float: left;
        width: 200px;
        height: 200px;
    }

    /*end id card 2 design */


</style>


<!-- modla header -->
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> Student ID Card </h4>
</div>

<div class="modal-body">
    <div class="row">
        {{--student details--}}
        @php $studentInfo = $stdProfile->student() @endphp
        @php $studentEnroll = $studentInfo->enroll(); @endphp
        @if($tempType==0)
            @if($templateProfile->temp_id==1)
                <div class="row" style="margin: 25px 0px; border: 1px dotted black; padding: 15px;" >
                    <div class="col-sm-6">
                        <div class="id-card-one">
                            <div class="row inst-info-section text-center">
                                <div class="col-sm-2 inst-logo">
                                    @if($instituteInfo->logo)
                                        <img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
                                    @endif
                                </div>
                                <div class="col-sm-10 inst-info">
                                    <b class="inst-name">{{$instituteInfo->institute_name}}</b> <br/>
                                    {{$instituteInfo->address1}}
                                </div>
                            </div>
                            <p class="id-card-label">Student ID Card</p>
                            <div class="row std-info-section">
                                <div class="col-sm-8 std-info text-left">
                                    <table>
                                        <tbody>
                                        <tr> <th width="45%">Student Name</th> <th width="5%">:</th> <th width="50%">{{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</th> </tr>
                                        <tr> <th>ID NO:</th> <th>:</th> <td>{{$studentInfo->enroll()->gr_no}}</td> </tr>
                                        <tr> <th>Class</th> <th>:</th> <td>{{$studentEnroll->batch()->batch_name}}</td> </tr>
                                        <tr> <th>Section</th> <th>:</th> <td>{{$studentEnroll->section()->section_name}}</td> </tr>
                                        @if($studentInfo->blood_group)
                                            <tr> <th>Blood Group</th> <th>:</th> <td>{{$studentInfo->blood_group}}</td> </tr>
                                        @endif
                                        </tbody>
                                    </table>
                                </div>
                                <div class="col-sm-4 std-photo">
                                    {{--sort std profile--}}
{{--                                    @php $studentInfo = findStudent($stdProfile->id) @endphp--}}
                                    {{--check std profile photo--}}
                                    @if($studentInfo->singelAttachment('PROFILE_PHOTO'))
                                        <img src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
                                    @else
                                        <img  src="{{asset('/assets/users/images/user-default.png')}}">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="id-card-one">
                            <div class="row">
                                <div class="col-md-12">
                                    <p class="text-center text-bold">{{$instituteInfo->address1}}</p>
                                    <hr/>
                                </div>
                                <div class="col-md-12">
                                    {{--parents information--}}
                                    @php $parents = $studentInfo->myGuardians(); @endphp
                                    {{--checking--}}
                                    @if($parents->count()>0)
                                        @foreach($parents as $index=>$parent)
                                            @php $guardian = $parent->guardian(); @endphp
                                            <p class="std-address {{$index%2==0?"std-address-first":""}}"><strong>{{$index%2==0?"Father's Name":"Mother's Name"}} :</strong> {{$guardian->title." ".$guardian->first_name." ".$guardian->last_name}}</p>
                                        @endforeach
                                    @endif
                                    <p class="std-address"><strong>Birth Date :</strong> {{date('d M, Y', strtotime($studentInfo->dob))}}</p>
                                    <p>
                                        <strong>Emergency Contact :</strong><br/>
                                        <span class="std-address"> {{$studentInfo->phone}} (home)</span><br/>
                                        <span class="std-address">01717375219 (office)</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($templateProfile->temp_id==2)
                <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <div class="col-sm-6">
                            <div class="square" id="opacity-image">
                                <p class="label label-success text-center">Front Side</p>
                                <div class="id-card-one">
                                    <div class="row inst-info-section text-center">
                                        <div class="inst-logo-2">
                                            @if($instituteInfo->logo)
                                                <img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
                                            @endif
                                        </div>
                                        <div class="inst-info-2">
                                            <b class="inst-name">{{$instituteInfo->institute_name}}</b> <br/>
                                            {{$instituteInfo->address1}}
                                        </div>
                                    </div>
                                    <div class="row std-info-section ">

                                        <div class="col-sm-3 std-photo std-photo-2">
                                            {{--sort std profile--}}
                                            @php $studentInfo = findStudent($stdProfile->std_id) @endphp
                                            {{--check std profile photo--}}
                                            @if($studentInfo->singelAttachment('PROFILE_PHOTO'))
                                                {{--for web server--}}
                                                {{--<img src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">--}}
                                                {{--for local server--}}
                                                <img src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
                                            @else
                                                {{--for web server--}}
                                                {{--<img  src="{{asset('/assets/users/images/user-default.png')}}">--}}

                                                {{--for local server--}}
                                                <img  src="{{asset('/assets/users/images/user-default.png')}}">
                                            @endif
                                        </div>
                                        <div class="col-sm-9 std-info std-info-2 text-left">
                                            <table>
                                                <tbody>
                                                <tr> <th width="30%">Name</th> <th width="5%">:</th> <th width="100%">{{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</th> </tr>
                                                <tr> <th>ID NO:</th> <th>:</th> <td>{{$studentInfo->enroll()->gr_no}}</td> </tr>
                                                <tr> <th>Class</th> <th>:</th> <td>{{$studentEnroll->batch()->batch_name}}</td> </tr>
                                                <tr> <th>Section</th> <th>:</th> <td>{{$studentEnroll->section()->section_name}}</td> </tr>
                                                @if($studentInfo->blood_group)
                                                    <tr> <th>Blood Group</th> <th>:</th> <td>{{$studentInfo->blood_group}}</td> </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                            <div class="principal">
                                                <p>Principal</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <p class="label label-success text-center">Back Side</p>
                            <div class="id-card-one">
                                <div class="row">
                                    <div class="col-md-12">
                                        <p class="text-center text-bold">{{$instituteInfo->address1}}</p>
                                        <hr/>
                                    </div>
                                    <div class="col-md-12">
                                        {{--parents information--}}
                                        @php $parents = $studentInfo->myGuardians(); @endphp
                                        {{--checking--}}
                                        @if($parents->count()>0)
                                            @foreach($parents as $index=>$parent)
                                                @php $guardian = $parent->guardian(); @endphp
                                                <p class="std-address {{$index%2==0?"std-address-first":""}}"><strong>{{$index%2==0?"Father's Name":"Mother's Name"}} :</strong> {{$guardian->title." ".$guardian->first_name." ".$guardian->last_name}}</p>
                                            @endforeach
                                        @endif
                                        <p class="std-address"><strong>Birth Date :</strong> {{date('d M, Y', strtotime($studentInfo->dob))}}</p>
                                        <p>
                                            <b>Emergency Contact :</b><br/>
                                            <span class="std-address"> {{$studentInfo->phone}} (home)</span><br/>
                                            <span class="std-address">01717375219 (office)</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif
        @else
            <div class="row" style="margin: 25px 0px; border: 1px dotted black; padding: 15px;" >
                <div class="col-sm-6">
                    <div class="id-card-two">
                        <div class="row inst-info-section text-center">
                            <div class="col-sm-12 inst-logo-portrait">
                                @if($instituteInfo->logo)
                                    <img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
                                @endif
                            </div>
                            <div class="col-sm-12 inst-info">
                                <b class="inst-name-portrait">{{$instituteInfo->institute_name}}</b> <br/>
                                {{--{{$instituteInfo->address1}}--}}
                            </div>
                        </div>

                        {{--<p class="id-card-label">Student ID Card</p>--}}
                        <div class="row std-info-section">
                            <div class="col-sm-12 std-photo-portrait" style="margin: 0 auto">
                                {{--sort std profile--}}
                                @php $studentInfo = findStudent($stdProfile->std_id) @endphp
                                {{--check std profile photo--}}
                                @if($studentInfo->singelAttachment('PROFILE_PHOTO'))
                                    <img src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
                                @else
                                    <img  src="{{asset('/assets/users/images/user-default.png')}}">
                                @endif
                            </div>
                            <div class="col-sm-12 text-left std-info-portrait">
                                <table width="90%" style="margin: 0 auto">
                                    <thead>
                                    <tr><th class="text-center text-bold" colspan="3" style="font-size: 18px">{{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</th></tr>
                                    </thead>
                                    <tbody>
                                    <tr> <th width="40%">ID NO</th> <th>:</th> <td>{{$studentInfo->enroll()->gr_no}}</td> </tr>
                                    <tr> <th>Class</th> <th>:</th> <td>{{$studentEnroll->batch()->batch_name}}</td> </tr>
                                    <tr> <th>Section</th> <th>:</th> <td>{{$studentEnroll->section()->section_name}}</td> </tr>
                                    @if($studentInfo->blood_group)
                                        <tr> <th>Blood Group</th> <th>:</th> <td>{{$studentInfo->blood_group}}</td> </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="id-card-two">
                        <div class="row std-info-portrait">
                            <div class="col-md-12">
                                <p class="text-center text-bold">Student Information</p>
                                <hr/>
                                {{--parents information--}}
                                @php $parents = $studentInfo->myGuardians(); @endphp
                                {{--checking--}}
                                @if($parents->count()>0)
                                    @foreach($parents as $index=>$parent)
                                        @php $guardian = $parent->guardian(); @endphp
                                        <p class="std-address {{$index%2==0?"std-address-first":""}}"><strong>{{$index%2==0?"Father's Name":"Mother's Name"}} :</strong> {{$guardian->title." ".$guardian->first_name." ".$guardian->last_name}}</p>
                                    @endforeach
                                @endif
                                <p class="std-address-portrait"><strong>Birth Date :</strong> {{date('d M, Y', strtotime($studentInfo->dob))}}</p>
                                <p>
                                    <strong>Contact :</strong><br/>
                                    <span class="std-address-portrait">{{$studentInfo->phone}} (home)</span><br/>
                                    <span class="std-address-portrait">01717375219 (office)</span>
                                </p>
                                <br/>
                                <br/>
                                <p class="text-center">
                                    .......................... <br/>Principal
                                </p>

                                {{--<p>--}}
                                {{--<strong>Address:</strong> <br/>--}}
                                {{--<span>Venus Complex, Kha-199/2-199/4, Bir Uttam Rafiqul Islam Ave, Dhaka-1213.</span>--}}
                                {{--</p>--}}
                            </div>
                            <div class="col-md-12">
                                <hr/>
                                <p class="text-center text-bold">{{$instituteInfo->address1}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{--<div class="col-md-8 col-md-offset-2">--}}
            {{--@php $studentInfo = $stdProfile->student() @endphp--}}
            {{--@php $studentEnroll = $studentInfo->enroll(); @endphp--}}
            {{--std ID Card--}}
            {{--<div class="id-card-wrapper">--}}
                {{--<div class="row inst-info-section text-center">--}}
                    {{--<div class="col-sm-2 inst-logo">--}}
                        {{--@if($instituteInfo->logo)--}}
                            {{--<img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">--}}
                        {{--@endif--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-10 inst-info">--}}
                        {{--<b class="inst-name">{{$instituteInfo->institute_name}}</b> <br/>--}}
                        {{--{{$instituteInfo->address1}}--}}
                        {{--{{'Address: '.$instituteInfo->address1}}<br/>--}}
                        {{--{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}--}}
                    {{--</div>--}}
                {{--</div>--}}
                {{--<p class="id-card-label text-center">Student ID Card</p>--}}
                {{--<div class="row std-info-section">--}}
                    {{--<div class="col-sm-8 std-info text-left">--}}
                        {{--<table cellspacing="1" cellpadding="1">--}}
                            {{--<tbody>--}}
                            {{--<tr> <th>Name</th> <td>: {{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</td> </tr>--}}
                            {{--<tr> <th>Roll</th> <td>: {{$studentInfo->enroll()->gr_no}}</td> </tr>--}}
                            {{--<tr> <th>Year</th><td>: {{$studentEnroll->academicsYear()->year_name}}</td></tr>--}}
                            {{--<tr> <th>Level</th><td>: {{$studentEnroll->level()->level_name}}</td></tr>--}}
                            {{--<tr> <th>Class </th><td>: {{$studentEnroll->batch()->batch_name}}</td></tr>--}}
                            {{--<tr> <th>Section</th><td>: {{$studentEnroll->section()->section_name}}</td></tr>--}}
                            {{--</tbody>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                    {{--<div class="col-sm-4 std-photo">--}}
                        {{--sort std profile--}}
                        {{--check std profile photo--}}
                        {{--@if($studentInfo->singelAttachment('PROFILE_PHOTO'))--}}
                            {{--<img src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">--}}
                        {{--@else--}}
                            {{--<img  src="{{asset('/assets/users/images/user-default.png')}}">--}}
                        {{--@endif--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}
    </div>
</div>
<!-- modal footer -->
<div class="modal-footer">
    <button type="submit" id="download_std_id_card" class="btn btn-info pull-right">Download</button>
    <button data-dismiss="modal" class="btn btn-default pull-left" type="button">Close</button>
</div>

<script>
    $(document).ready(function () {
        $('#download_std_id_card').click(function () {
            // dynamic form
            $('<form id="download_std_id_card_form" action="/student/id-card" method="POST" target="_blank"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="std_id" value="{{$studentInfo->id}}"/>')
                .append('<input type="hidden" name="request_type" value="pdf"/>').appendTo('body').submit();
            // remove form from the body
            $('#download_std_id_card_form').remove();
        });
    });
</script>

