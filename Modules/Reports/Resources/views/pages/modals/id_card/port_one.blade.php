
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

        .fontSide {
            width: {{$tempSetting?($tempType==1?($tempSetting->width.'px'):'350px'):'350px'}};
            height: {{$tempSetting?($tempType==1?($tempSetting->height.'px'):'210px'):'210px'}};
            background-color: {{$tempSetting?($tempType=1?($tempSetting->color):'#a3cde7'):'#a3cde7'}} !important;
            border: 2px solid #3c3c3c;
            padding: 5px;
            float: left;
            margin-left: 50px;
            margin-top: 20px;
        }
        p.schoolName {
            text-align: center;
            text-transform: uppercase;
            font-weight: bold;
            size: {{$tempSetting->title_font}};
            margin-top: 15px;
        }
        p.address {
            text-align: center;
            line-height: 0px;
            font-size: 13px;
            color: {{$tempSetting?($tempType==1?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }
        .profilePic{
            text-align: center;
            margin-top: 10px;
        }
        .proifleImage {
            height: 80px;
            border-radius: 50px;
            width: 80px;
            margin-top: 10px;
        }
        .studentInfo {
            margin-left: 15px;
            margin-top: 20px;
            color: {{$tempSetting?($tempType==1?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }
        p.normaltext {
            font-size: 12px;
            line-height: 6px;
            color: {{$tempSetting?($tempType==1?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }
        p.studentName {
            font-size: 14px;
            text-align: center;
            font-weight: bold;
            color: {{$tempSetting?($tempType==1?($tempSetting->font_color):'#000000'):'#000000'}} !important;
            margin-top: 5px;
        }
        p.principal {
            float: right;
            padding: 20px;
            color: {{$tempSetting?($tempType==1?($tempSetting->font_color):'#000000'):'#000000'}} !important;
        }

        /*back Side design Html Css*/
        .backSide{
            width: {{$tempSetting?($tempType==1?($tempSetting->width.'px'):'350px'):'350px'}};
            height: {{$tempSetting?($tempType==1?($tempSetting->height.'px'):'210px'):'210px'}};
            background-color: {{$tempSetting?($tempType=1?($tempSetting->color):'#a3cde7'):'#a3cde7'}} !important;
            float: left;
            border: 2px solid #3c3c3c;
            padding: 5px;
        }
/*//*/
        p.stduentInfoTitle {
            font-size: 14px;
            font-weight: bold;
            line-height: 20px;
            text-align: center;
            border-bottom: 2px solid #3c3c3c;
            color: {{$tempSetting?($tempType==1?($tempSetting->font_color):'#000000'):'#000000'}} !important;
            margin-top: 15px;
        }
        p.port_one_found{
            margin-top: 45px;
            text-align: center;
            line-height: 0px;
        }
        p.port_one_phone {
            font-size: 12px;
            text-align: center;
            line-height: 0px;

        }
        .schoolLogo {
            text-align: center;
        }
        p.address1{
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            width: 175px;
        }
        .imageOpacity {
            opacity: 0.1;
            height: 80px;
            width: 130px;
        }

        .port_one_signature {
            width: 60px !important;
        }

        .clear_both {
            clear: both;
        }
        .singleStudent {
            padding-bottom: 20px;
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

            @page {
                size: A4; /* DIN A4 standard, Europe */
                margin: 20mm 0 10mm 0;
            }
            body {
                margin:0;
                padding:0;
            }
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
                    <div class="fontSide">
                        <p class="schoolName">{{$instituteInfo->institute_name}}</p>
                        <p class="address">{{$instituteInfo->address2}}</p>





                        <div class="profilePic">
                            @if($studentInfo->singelAttachment('PROFILE_PHOTO'))
                                <img class="proifleImage" src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
                            @else
                                <img class="proifleImage"   src="{{asset('/assets/users/images/user-default.png')}}">
                            @endif
                        </div>
                        <p class="studentName">{{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</p>
                        <div class="studentInfo">
                            <p class="normaltext">ID NO: {{$studentInfo->enroll()->gr_no}}</p>
                            <p class="normaltext">Class: {{$studentEnroll->batch()->batch_name}}</p>
                            <p class="normaltext">Section: {{$studentEnroll->section()->section_name}}</p>
                            <p class="normaltext">Blood Group: {{$studentInfo->blood_group}}</p>
                            <p class="normaltext">Year : {{$academicYear->year_name}}</p>
                        </div>
                        @if(!empty($templateProfile))
                            <div class="principalImage">
                                <img class="port_one_signature" src="{{URL::asset('/assets/id-card/'.$templateProfile->signature)}}">
                                <p>Principal</p>
                            </div>
                        @endif
                    </div>

                    {{--Back Side Design Code --}}

                    <div class="fontSide">
                        <p class="stduentInfoTitle">Student Information</p>
                        <div class="studentInfo">

                            @php $parents = $studentInfo->myGuardians(); $contact=''; @endphp
                            {{--checking--}}
                            @if($parents->count()>0)
                                @foreach($parents as $index=>$parent)
                                    @php $guardian = $parent->guardian();
                                    @endphp
                                    @if($parent->is_emergency == 1)
                                        @php $contact=$guardian->mobile @endphp
                                    @endif
                                    <p class="normaltext">{{$index%2==0?"Father":"Mother"}} : {{$guardian->title." ".$guardian->first_name." ".$guardian->last_name}}</p>
                                @endforeach
                            @endif
                            <p class="normaltext">Date of Birth: {{date('d/M/Y', strtotime($studentInfo->dob))}}</p>
                            <p class="normaltext">Contact : {{$contact}}</p>
                        </div>
                        <div class="schoolLogo">
                            @if($instituteInfo->logo)
                                <img class="imageOpacity" src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
                            @endif
                        </div>
                        <p class="port_one_found">If it is found please return to</p>
                        <p class="address1">{{$instituteInfo->address1}}</p>
                        <p class="port_one_phone">Tel: {{$instituteInfo->phone}} </p>

                    </div>

                </div>
                @if($i%3==0)
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

