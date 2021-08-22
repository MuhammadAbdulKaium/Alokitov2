@if(count($studentList)>0)

	<style>

		.id-card-one{
			/*border: 1px solid red;*/
			border-radius: 5px;
			margin-bottom: 5px;
			width: 600px;
			height: 500px;
			padding: 10px;
			background-color: gainsboro;
		}

		.inst-logo{

		}
		.inst-logo img{
			width: 55px;
			height:50px;
		}

		.inst-info{

		}


		.id-card-label {
			border: 1px solid black;
			border-radius: 2px;
			font-size: 15px;
			font-weight: 700;
			margin-top: 5px;
		}

		.std-info-section{

		}

		.std-info{
			font-size: 13px;
			padding-right: 0px;
			padding-left: 15px;
		}

		.std-photo{
			padding-right: 0px;
			padding-left: 0px;
		}

		.std-photo img{
			width: 80px;
			height: 90px;
		}

		.row-margin {
			margin-top: 10px;
		}
	</style>


	<div class="row">
		<div class="col-sm-12">
			<p class="bg-blue-active text-bold" style="padding: 5px">Class Section Student Testimonial
				@if(count($studentList)>0)
					<span style="padding: 5px; cursor: pointer" id="download-class-section-std-id-card" class="pull-right label label-success text-bold">Download Testimonial</span>
				@endif
			</p>

			</div>


			{{--<button type="button" class="btn btn-success pull-right" id="download-class-section-std-id-card">Download</button>--}}
			{{--array type studentList--}}
			@php $myStudentList = $studentList; $i=1; @endphp
			{{--looping--}}
			@foreach($myStudentList as $key=>$studentProfileGuardian)
				<div class="col-md-8 col-md-offset-2" style="margin-top: 20px">
					{{--first div--}}
					<div class="row">
						@if(!empty($studentProfileGuardian))
							{{--get single std--}}
							{{--std ID Card--}}
							<div id="printablediv" style="border: 2px solid #3c3c3c; padding: 20px">
								<div class="row" style="font-size: 16px">
									<div class="col-sm-4">ক্রমিক নং: {{en2bnNumber($i++)}}<br>কেন্দ্র কোড:{{en2bnNumber($instituteInfo->center_code)}}<br>জেলা কোড: {{en2bnNumber($instituteInfo->zilla_code)}}</div>

									<div class="col-sm-4 text-center">বিদ্যালয়ের কোড: {{en2bnNumber($instituteInfo->institute_code)}} <br>উপজেলা কোড:{{en2bnNumber($instituteInfo->upazila_code)}} </div>

									<div class="col-sm-4 text-right">EIIN:{{en2bnNumber($instituteInfo->eiin_code)}} <br>

										@php  $bangladate = new EasyBanglaDate\Types\BnDateTime(date('Y-m-d'));
										@endphp

										তারিখ:{{$bangladate->getDateTime()->format('j-m-Y')}}</div>
								</div>
								<div class="row text-center">
									<span><img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}" style="height: 80px; width: 120px"></span>
								</div>

								<div class="row text-center">
									<h3 style="font-weight: 600">প্রশংসা পত্র</h3>





									<p style="text-align: justify; font-size: 16px; padding: 20px; line-height: 28px;">
										এই মর্মে প্রত্যায়ন করা যাইতেছে যে, @if(!empty($myStudentList[$key]['profile'][0]['bn_fullname'])) <b>{{$myStudentList[$key]['profile'][0]['bn_fullname']}} </b> @else ..................................@endif,

										পিতা: @if(!empty($myStudentList[$key]['guardian'][0]['bn_fullname'])) <b>{{$myStudentList[$key]['guardian'][0]['bn_fullname']}} </b> @else ..................................@endif,
										মাতা: @if(!empty($myStudentList[$key]['guardian'][1]['bn_fullname'])) <b>{{$myStudentList[$key]['guardian'][1]['bn_fullname']}} </b>@else ..................................@endif,
										গ্রাম: @if(!empty($myStudentList[$key]['address'][0]['bn_village'])) <b>{{$myStudentList[$key]['address'][0]['bn_village']}} </b>@else ..................................@endif,
										ডাকঘর:@if(!empty($myStudentList[$key]['address'][0]['bn_postoffice'])) <b>{{$myStudentList[$key]['address'][0]['bn_postoffice']}} </b>@else ..................................@endif,
										উপজেলা:@if(!empty($myStudentList[$key]['address'][0]['bn_zilla'])) <b>{{$myStudentList[$key]['address'][0]['bn_upzilla']}} </b>@else ..................................@endif,
										জেলা: @if(!empty($myStudentList[$key]['address'][0]['bn_zilla'])) <b> {{$myStudentList[$key]['address'][0]['bn_zilla']}} </b> @else ..................................@endif,
										কেন্দ্র-........................, রোল........................ মাধ্যমিক ও উচ্চ মাধ্যমিত শিক্ষা বোর্ড, ঢাকা কর্তৃক গৃহিত ............ সনের মাধ্যমিক স্কুল সাটিফিকেট পরীক্ষায় কালাপাহাড়িয়া ইউনিয়ন উচ্চ মাধ্যমিক বিদ্যালয়ের নিয়মিত/অনিয়মিত পরীক্ষার্থী হিসাবে বিজ্ঞান/মানবিক/ব্যবসায় শিক্ষা
										জি, পি, এ @if(!empty($myStudentList[$key]['testimonial_result'][0]['gpa'])) <b> {{$myStudentList[$key]['testimonial_result'][0]['gpa']}} </b> @else ..................................@endif
										(কথায়) @if(!empty($myStudentList[$key]['testimonial_result'][0]['gpa_details'])) <b> {{$myStudentList[$key]['testimonial_result'][0]['gpa_details']}} </b> @else ..................................@endif   পাইয়া উর্ত্তীর্ণ হইয়াছে।   তাহার
										রেজিষ্ট্রেশন নম্বর @if(!empty($myStudentList[$key]['testimonial_result'][0]['reg_no'])) <b> {{$myStudentList[$key]['testimonial_result'][0]['reg_no']}} </b> @else ..................................@endif
										সন @if(!empty($myStudentList[$key]['testimonial_result'][0]['year'])) <b> {{$myStudentList[$key]['testimonial_result'][0]['year']}} </b> @else ..................................@endif
										ভর্তি রেজিষ্টার অনুযায়ী তাহার জন্ম



											@if(!empty($myStudentList[$key]['dob'][0]))
												@php
                                                $bongabda = new EasyBanglaDate\Types\BnDateTime($myStudentList[$key]['dob'][0]);

                                                $date=$bongabda->getDateTime()->format('j-m-Y');
                                                $dataDetials=$bongabda->getDateTime()->format('jS, F Y');
                                                @endphp
											@endif

										তারিখ @if(!empty($myStudentList[$key]['dob'][0])) <b> {{$date}} </b> @else ..................................@endif

										(কথায়)  @if(!empty($myStudentList[$key]['dob'][0])) <b> {{$dataDetials}} </b> @else ..................................@endif।
										আমার জনামতে {{$instituteInfo->bn_institute_name}} অধ্যয়নকালে সে রাষ্ট্র বিরোধী বা আইন শৃঙ্খলার পরপিন্থী কোন কাজে জড়িত ছিল না।   সে চরিত্রবান।   তাহার জীবনের সর্বাঙ্গীন উন্নতি কামনা করি।
									</p>
								</div>
								<div class="row" style="width: 100%; font-size: 16px; margin-top: 20px">
									<div class="col-sm-4 col-md-offset-1 text-center">-------------------------------------<br>লেখক <br></div>
									<div class="col-sm-4 col-md-offset-2 text-center">--------------------------------------<br>প্রধান শিক্ষকের স্বাক্ষর <br></div>
								</div>
							</div>
						@endif
					</div>
				</div>
			@endforeach
		</div>
	</div>
	
	<script>
		$(document).ready(function () {
			$('#download-class-section-std-id-card').click(function () {
				// dynamic form
                $('<form id="std_testimonial_download_form" action="/reports/student/result/testimonal/download" method="POST"></form>')
                    .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                    .append('<input type="hidden" name="academic_year" value="'+$("#academic_year").val()+'"/>')
                    .append('<input type="hidden" name="academic_level" value="'+$("#academic_level").val()+'"/>')
                    .append('<input type="hidden" name="batch" value="'+$("#batch").val()+'"/>')
                    .append('<input type="hidden" name="section" value="'+$("#section").val()+'"/>')
                    .append('<input type="hidden" name="request_type" value="pdf"/>').appendTo('body').submit();
                // remove form from the body
                $('#std_testimonial_download_form').remove();
            });
        });
	</script>
	
@else
	<div class=" col-md-10 col-md-offset-1 text-center alert bg-warning text-warning" style="margin-bottom:0px;">
		<i class="fa fa-warning"></i> No record found.
	</div>
@endif

