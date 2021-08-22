<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    {{--student information--}}
    <style>
        @page {
            margin-bottom:10px;
            border: 1px solid blue;
        }

        .testimonial-section {
            margin-left:160px;
            width:800px;
        }

        .std-photo img{
            width: 80px;
            height: 90px;
        }


        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }


    </style>
</head>
<body>
@if(!empty($studentList))
    <div class="row testimonial-section">

        {{--<button type="button" class="btn btn-success pull-right" id="download-class-section-std-id-card">Download</button>--}}
        {{--array type studentList--}}
        @php $myStudentList = $studentList; $i=1; @endphp
        {{--looping--}}
        @foreach($myStudentList as $key=>$studentProfileGuardian)
            <div class="col-md-8 col-md-offset-2" style="height: 500px; border: 2px solid #3c3c3c">
                {{--first div--}}
                <div class="row">
                    @if(!empty($studentProfileGuardian))
                        {{--get single std--}}
                        {{--std ID Card--}}
                        <div id="printablediv" style="border: 2px solid #3c3c3c; padding: 20px">
                            <div class="row" style="font-size: 16px">
                                <div class="col-sm-4" style="width: 33%; float: left">ক্রমিক নং: {{en2bnNumber($i++)}}<br>কেন্দ্র কোড:{{en2bnNumber($instituteInfo->center_code)}}<br>জেলা কোড: {{en2bnNumber($instituteInfo->zilla_code)}}</div>

                                <div class="col-sm-4 text-center" style="width: 33%; float: left">বিদ্যালয়ের কোড: {{en2bnNumber($instituteInfo->institute_code)}} <br>উপজেলা কোড:{{en2bnNumber($instituteInfo->upazila_code)}} </div>

                                <div class="col-sm-4 text-right" style="width: 33%; float: right">EIIN:{{en2bnNumber($instituteInfo->eiin_code)}} <br>

                                    @php  $bangladate = new EasyBanglaDate\Types\BnDateTime(date('Y-m-d'));
                                    @endphp

                                    তারিখ:{{$bangladate->getDateTime()->format('j-m-Y')}}</div>
                            </div>
                            <div class="row text-center">
                                <span>	<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}" style="height: 80px; width: 120px"></span>
                            </div>

                            <div class="row text-center">
                                <h3 style="font-weight: 600">প্রশংসা পত্র</h3>





                                <p style="text-align: justify; font-size: 16px; padding: 20px; line-height: 28px;">
                                    এই মর্মে প্রত্যায়ন করা যাইতেছে যে, @if(!empty($myStudentList[$key]['profile'][0]['bn_fullname'])) {{$myStudentList[$key]['profile'][0]['bn_fullname']}}  @else ..................................@endif,

                                    পিতা: @if(!empty($myStudentList[$key]['guardian'][0]['bn_fullname'])) {{$myStudentList[$key]['guardian'][0]['bn_fullname']}}  @else ..................................@endif,
                                    মাতা: @if(!empty($myStudentList[$key]['guardian'][1]['bn_fullname'])) {{$myStudentList[$key]['guardian'][1]['bn_fullname']}} @else ..................................@endif,
                                    গ্রাম: @if(!empty($myStudentList[$key]['address'][0]['bn_village'])) {{$myStudentList[$key]['address'][0]['bn_village']}} @else ..................................@endif,
                                    ডাকঘর:@if(!empty($myStudentList[$key]['address'][0]['bn_postoffice'])) {{$myStudentList[$key]['address'][0]['bn_postoffice']}} @else ..................................@endif,
                                    উপজেলা:@if(!empty($myStudentList[$key]['address'][0]['bn_zilla'])) {{$myStudentList[$key]['address'][0]['bn_upzilla']}} @else ..................................@endif,
                                    জেলা: @if(!empty($myStudentList[$key]['address'][0]['bn_zilla']))  {{$myStudentList[$key]['address'][0]['bn_zilla']}}  @else ..................................@endif,
                                    কেন্দ্র-........................, রোল........................ মাধ্যমিক ও উচ্চ মাধ্যমিত শিক্ষা বোর্ড, ঢাকা কর্তৃক গৃহিত ............ সনের মাধ্যমিক স্কুল সাটিফিকেট পরীক্ষায় কালাপাহাড়িয়া ইউনিয়ন উচ্চ মাধ্যমিক বিদ্যালয়ের নিয়মিত/অনিয়মিত পরীক্ষার্থী হিসাবে বিজ্ঞান/মানবিক/ব্যবসায় শিক্ষা
                                    জি, পি, এ @if(!empty($myStudentList[$key]['testimonial_result'][0]['gpa'])) {{$myStudentList[$key]['testimonial_result'][0]['gpa']}}@else ..................................@endif
										(কথায়) @if(!empty($myStudentList[$key]['testimonial_result'][0]['gpa_details']))  {{$myStudentList[$key]['testimonial_result'][0]['gpa_details']}}  @else ..................................@endif   পাইয়া উর্ত্তীর্ণ হইয়াছে।   তাহার
                                    রেজিষ্ট্রেশন নম্বর @if(!empty($myStudentList[$key]['testimonial_result'][0]['reg_no']))  {{$myStudentList[$key]['testimonial_result'][0]['reg_no']}}  @else ..................................@endif
                                    সন @if(!empty($myStudentList[$key]['testimonial_result'][0]['year']))  {{$myStudentList[$key]['testimonial_result'][0]['year']}}  @else ..................................@endif
                                    ভর্তি রেজিষ্টার অনুযায়ী তাহার জন্ম



                                    @if(!empty($myStudentList[$key]['dob'][0]))
                                        @php
                                            $bongabda = new EasyBanglaDate\Types\BnDateTime($myStudentList[$key]['dob'][0]);

                                            $date=$bongabda->getDateTime()->format('j-m-Y');
                                            $dataDetials=$bongabda->getDateTime()->format('jS, F Y');
                                        @endphp
                                    @endif

                                    তারিখ @if(!empty($myStudentList[$key]['dob'][0]))  {{$date}}  @else ..................................@endif

										(কথায়)  @if(!empty($myStudentList[$key]['dob'][0]))  {{$dataDetials}}  @else ..................................@endif।
                                    আমার জনামতে {{$instituteInfo->bn_institute_name}}  অধ্যয়নকালে সে রাষ্ট্র বিরোধী বা আইন শৃঙ্খলার পরপিন্থী কোন কাজে জড়িত ছিল না।   সে চরিত্রবান।   তাহার জীবনের সর্বাঙ্গীন উন্নতি কামনা করি।
                                </p>
                            </div>
                            <div class="row" style="width: 100%; font-size: 16px; margin-top: 20px">
                                <div class="col-sm-4 text-center" style="float: left; width: 50%">-------------------------------------<br>লেখক <br></div>
                                <div class="col-sm-4 text-center"  style="float: right; width: 50%">--------------------------------------<br>প্রধান শিক্ষকের স্বাক্ষর <br></div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    </div>
@else
    <p class="text-center">No resource found</p>
@endif
</body>
</html>
