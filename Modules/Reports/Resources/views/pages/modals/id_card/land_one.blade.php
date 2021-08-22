
{{--template setting--}}
@php
    $tempSetting = null;
    $tempType = null;
@endphp

{{--checking template profle--}}
@if($templateProfile)
    @php
        $tempSetting = $templateProfile?(json_decode($templateProfile->setting)):null;
        $tempType =  $templateProfile?$templateProfile->temp_type:null;
    @endphp

    <style>

        .land_one_FrontSide {
            width: {{$tempSetting?($tempType==0?($tempSetting->width.'px'):'350px'):'350px'}};
            height: {{$tempSetting?($tempType==0?($tempSetting->height.'px'):'210px'):'210px'}};
            background-color: {{$tempSetting?($tempType==0?($tempSetting->color):'#a3cde7'):'#a3cde7'}} !important;
            border: 2px solid #3c3c3c;
            padding: 5px;
            float: left;
        }
        .land_one_school_seciton {
            width: 100%;
            float: left;
            border-bottom: 2px solid #efefef;
            padding-bottom: 10px;

        }

        .land_one_school_logo {
            width: 70px;
            text-align: center;
            float: left;
            width: 20%;
        }

        .land_one_logo {
            width: 70px;
            height: 55px;
        }
        .land_one_school_info{
            float: left;
            text-align: center;
            width: 80%;
            line-height: 15px;
            color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }

        p.land_one_schoolName {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            font-size: {{$tempSetting?($tempType==0?($tempSetting->title_font.'px'):'20px'):'20px'}} !important;
            color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }
        p.land_one_address {
            text-align: center;
            line-height: 0px;
            font-size: 13px;
            color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }
        .land_one_profilePic{
            text-align: center;
            margin-top: 10px;
            width: 20%;
            float: left;
            color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }
        .land_one_proifleImage {
            height: 60px;
            width: 63px;
            border: 2px solid #efefef;
            color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }

        .land_one_studentInfo_and_image {
            margin-top: 10px;
        }
        .land_one_studentInfo {
            width: 74%;
            float: left;
            margin-left: 16px;
            color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }
        p.land_one_normaltext {
            font-size: 12px;
            line-height: 6px;
            color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }

        p.principal {
            float: right;
            padding: 10px;
            font-size: 12px;
            color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }

        /*back Side design Html Css*/
        .land_one_backSide {
            float: left;
            width: {{$tempSetting?($tempType==0?($tempSetting->width.'px'):'350px'):'350px'}} !important;
            height: {{$tempSetting?($tempType==0?($tempSetting->height.'px'):'210px'):'210px'}} !important;
            background-color: {{$tempSetting?($tempType==0?($tempSetting->color):'#a3cde7'):'#a3cde7'}} !important;
            border: 2px solid #3c3c3c;
            padding: 5px;
            margin-left: 10px;
        }

        p.land_one_stduentInfoTitle {
            font-size: 14px;
            font-weight: bold;
            line-height: 20px;
            text-align: center;
            border-bottom: 2px solid #3c3c3c;
            color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }
        p.found{
            margin-top: 45px;
            text-align: center;
            border-top: 2px solid #ccc;
            line-height: 25px;
        }
        p.phone {
            font-size: 12px;
            text-align: center;
        }
        .land_one_schoolLogo {
            text-align: center;
        }
        .imageOpacity {
            opacity: 0.1;
            height: 100px;
            width: 150px;
        }

        .clear_both {
            clear: both;
        }
        .singleStudent {
            padding-bottom: 10px;
        }

        /*End Landsacape one Section */

        @media print
        {
            .no-print, .no-print *
            {
                display: none !important;
            }
            .wrapper {
                background: none !important;
            }
            .printPreview {
                display: none !important;
            }
        }

        .header {
            position: fixed;
            top: 0;
        }
        .printPreview {
            float: right;
        }


    </style>

    <div class="row">
        <button type="button" class="btn btn-success printPreview">Print</button>
        <div class="col-md-12" style="margin: 0 auto">
            {{--checking template type--}}
            {{--student list looping--}}
                {{--student details--}}
            @php $i=1; @endphp
                @foreach($studentList as $stdInfo)
                    <div class="row singleStudent">
                    {{--student details--}}
                    @php $studentInfo = findStudent($stdInfo['std_id']) @endphp
                    @php $studentEnroll = $studentInfo->enroll(); @endphp
                    <div class="land_one_FrontSide">
                        <div class="land_one_school_seciton">
                            <div class="land_one_school_logo">
                                @if($instituteInfo->logo)
                                    <img class="land_one_logo" src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
                                    @endif
                            </div>
                            <div class="land_one_school_info">
                                <p class="land_one_schoolName">{{$instituteInfo->institute_name}}</p>
                                <p class="land_one_address">{{$instituteInfo->address2}}</p>
                            </div>
                        </div>
                        <div class="clear_both"></div>
                        <div class="land_one_studentInfo_and_image">

                            <div class="land_one_profilePic">

                                @if($studentInfo->singelAttachment('PROFILE_PHOTO'))
                                    <img class="land_one_proifleImage" src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
                                @else
                                    <img class="land_one_proifleImage"   src="{{asset('/assets/users/images/user-default.png')}}">
                                @endif
                            </div>
                            <div class="land_one_studentInfo">
                                <p class="land_one_normaltext">Name: {{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</p>
                                <p class="land_one_normaltext">ID NO: {{$studentInfo->enroll()->gr_no}}</p>
                                <p class="land_one_normaltext">Class: {{$studentEnroll->batch()->batch_name}}</p>
                                <p class="land_one_normaltext">Section: {{$studentEnroll->section()->section_name}}</p>
                                <p class="land_one_normaltext">Blood Group: {{$studentInfo->blood_group}}</p>
                            </div>
                        </div>
                        <p class="principal">Principal</p>
                    </div>

                    {{--Back Side Design Code--}}

                    <div class="land_one_backSide">
                        <p class="land_one_stduentInfoTitle">Student Information </p>
                        <div class="land_one_studentInfo">

                            @php $parents = $studentInfo->myGuardians(); @endphp
                            {{--checking--}}
                            @if($parents->count()>0)
                                @foreach($parents as $index=>$parent)
                                    @php $guardian = $parent->guardian(); @endphp
                                    <p class="land_one_normaltext">{{$index%2==0?"Father's Name":"Mother's Name"}} : {{$guardian->title." ".$guardian->first_name." ".$guardian->last_name}}</p>
                                @endforeach
                            @endif
                            <p class="land_one_normaltext">Date of Birth: {{date('d/M/Y', strtotime($studentInfo->dob))}}</p>
                            <p class="land_one_normaltext">Emergency Contact : {{$studentInfo->phone}} (home)</p>
                        </div>
                        <div class="land_one_schoolLogo">
                            @if($instituteInfo->logo)
                                <img class="imageOpacity" src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
                            @endif
                        </div>

                    </div>
                    </div>
                    @if($i%4==0)
                    <div style="page-break-before: always;"></div>
                        @endif
            @php $i++ @endphp
            @endforeach
        </div>
    </div>


    <script>
        $(document).ready(function () {

            $('.printPreview').click(function () {
                window.print();
            })
            $('#download-class-section-std-id-card').click(function () {
                // dynamic form
                $('<form id="std_id_card_download_form" action="/reports/student/id-card/download" method="POST"></form>')
                    .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                    .append('<input type="hidden" name="academic_year" value="'+$("#academic_year").val()+'"/>')
                    .append('<input type="hidden" name="academic_level" value="'+$("#academic_level").val()+'"/>')
                    .append('<input type="hidden" name="batch" value="'+$("#batch").val()+'"/>')
                    .append('<input type="hidden" name="section" value="'+$("#section").val()+'"/>')
                    .append('<input type="hidden" name="font_size" value="{{$fontSize}}"/>')
                    .append('<input type="hidden" name="width" value="{{$width}}"/>')
                    .append('<input type="hidden" name="height" value="{{$height}}"/>')
                    .append('<input type="hidden" name="margin_bottom" value="{{$margin_bottom}}"/>')
                    .append('<input type="hidden" name="request_type" value="pdf"/>').appendTo('body').submit();
                // remove form from the body
                $('#std_id_card_download_form').remove();
            });
        });
    </script>
@else

@endif

