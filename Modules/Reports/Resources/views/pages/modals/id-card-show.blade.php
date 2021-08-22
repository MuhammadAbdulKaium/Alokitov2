
{{--template setting--}}
@php
	$tempSetting = null;
	$tempType = null;
@endphp

{{--checking template profle--}}
@if($templateProfile)
	@php
		$tempSetting = json_decode($templateProfile->setting);
		$tempType =  $templateProfile->temp_type;
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
			background: url("{{asset('/assets/id-card/'.$templateProfile->bg_image)}}");
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



		/*Id Card Design 3 LandScape*/
			.inst-info-section-land-3{
			margin: 0px;
			padding: 0px;
			{{--background: {{$tempSetting?($tempType==0?($tempSetting->color):'blue'):'blue'}};--}}
			height: 50px;
		}
		.id-card-heading{
			float: right;
			border-bottom: 2px solid #bab8b8;
			border-top-left-radius: 100px;
			border-bottom-left-radius: 100px;
			background:#E5E9EB;
			width: 90%;
			height: 26px;
			text-align: center;
			font-weight: bold;
			margin-top: -4px;
		}
		.id-card-heading p {
			margin-top: 5px;
		}
		.inst-info-2-land{
			font-size: 11px;
			color: #FFF;
			float: right;
			text-align: center;
			padding: 4px;
		}

		.inst-logo-2-land{
			position: absolute;
			z-index: 99;
			background: {{$tempSetting?($tempType==0?($tempSetting->color):'blue'):'blue'}};
			width: 20%;
			float: left;
			height: 70px;
			border-bottom-left-radius: 10px;
			border-bottom-right-radius: 10px;
		}
		.inst-logo-2-land img {
			width: 70px;
			height: 60px;
			padding: 6px;
		}

		.inst-name-2-land {
			font-size: {{$tempSetting?($tempType==0?($tempSetting->title_font.'px'):'20px'):'20px'}};
			font-weight: 700;
			text-transform: uppercase;
		}
		.inst-name-2-land p {
			font-size:12px;
		}
		.table-id-card-three-land {
			font-size: 10px;
		}
		.std-land-photo-2 img {
			margin-top: 6px !important;
			margin-left: 32px !important;
			width: 80px !important;
			height: 90px;
			border: 2px solid #bab8b8;
			position: absolute;
			z-index: 99;
			border-radius: 20%;
		}
		.std-land-info-2 {
			margin-top: 4px !important;
			padding-left: 10px !important;
		}
		.id-card-land-3-sing {
			background: #E5E9EB;
			height: 40px;
			margin: 0px;
			padding: 0px;
		}
		.id-card-land-3-sing p {
			float: right;
			font-size: 12px;
			margin-top: 16px;
			text-transform: uppercase;
			padding-right: 20px;
			font-weight: bold;
		}
		.id-card-land-3-slow {
			background: {{$tempSetting?($tempType==0?($tempSetting->color):'blue'):'blue'}};
			margin: 0px;
			padding: 0px;
			color: #FFF;
			text-align: center;
			text-transform: uppercase;
		}

		/*end id card 2 design */






	</style>

	<div class="row">
		<p class="bg-blue-active text-bold text-center" style="padding: 5px">Class Section Student ID Card @if($studentList->count()>0)<span style="padding: 5px; cursor: pointer" id="download-class-section-std-id-card" class="pull-right label label-success text-bold">Download ID Card</span>@endif</p>

		<div class="col-md-12" style="margin: 0 auto">
			{{--checking template type--}}
			{{--student list looping--}}
			@foreach($studentList as $stdInfo)
				{{--student details--}}
				@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
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
										@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
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
						<div class="row" style="border-top: 2px solid red; margin-top: 20px;">
							<div class="col-md-10 col-md-offset-1" style="margin-top: 20px">
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
													@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
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

				@elseif($templateProfile->temp_id==3)
						<div class="row" style="border-top: 2px solid red; margin-top: 20px;">
							<div class="col-md-10 col-md-offset-1" style="margin-top: 20px">
								<div class="col-sm-6">
									<p class="label label-success text-center">Front Side</p>
									<div class="id-card-three-land id-card-one-width id-card-one-height">
										<div class="row inst-info-section-land-3 id-card-one-color text-center">
											<div class="inst-logo-2-land id-card-one-color">
												@if($instituteInfo->logo)
													<img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
												@endif
											</div>
											<div class="inst-info-2-land ">
												<b class="inst-name-2-land inst-font-size-land">{{$instituteInfo->institute_name}}</b>
												<p>{{$instituteInfo->address1}}</p>
											</div>
										</div>
										<div class="id-card-heading">
											<p>ID CARD               2012-2013</p>
										</div>
										<div class="row std-info-section">
											<div class="col-sm-4 std-photo std-land-photo-2">
												<img  src="{{asset('/assets/default.png')}}">
											</div>
											<div class="col-sm-8 std-land-info-2 std-info-2 text-left">
												<table class="table-id-card-three-land">
													<tbody>
													<tr> <th width="30%">Name</th> <th width="5%">:</th> <th width="100%">Shakib Hossain</th> </tr>
													<tr> <th>ID NO:</th> <th>:</th> <td>01</td> </tr>
													<tr> <th>Class</th> <th>:</th> <td>Class Six</td> </tr>
													<tr> <th>Section</th> <th>:</th> <td>A</td> </tr>
													<tr> <th>Blood Group</th> <th>:</th> <td>A+ (positive)</td> </tr>
													</tbody>
												</table>
											</div>
										</div>
										<div class="row id-card-land-3-sing">
											<p>Principal</p>
										</div>
										<div class="row id-card-land-3-slow id-card-one-color">
											Slow GUn Here
										</div>

										{{--<div class="row id-card-land-3-slogan">--}}
										{{--<p style="text-align: center">Your SlowGUn Her</p>--}}
										{{--</div>--}}
									</div>
								</div>
								<div class="col-sm-5">
									<p class="label label-success text-center">Back Side</p>
									<div class="id-card-one id-card-one-color">
										<div class="row">
											<div class="col-md-12">
												<p class="text-center text-bold">{{$instituteInfo->address1}}</p>
												<hr/>
											</div>
											<div class="col-md-12">
												<p class="std-address std-address-first"><strong>Father's Name :</strong> Shakib Hossain</p>
												<p class="std-address"><strong>Mother's Name :</strong> Fathema Begum</p>
												<p class="std-address"><strong>Birth Date :</strong> 31-12-1991</p>
												<p>
													<strong>Emergency Contact :</strong><br/>
													<span class="std-address">01717375219 (home)</span><br/>
													<span class="std-address">01717375219 (office)</span>
												</p>
											</div>
										</div>
									</div>
								</div>
							</div>

						</div>

					@elseif($templateProfile->temp_id==4)

						<div class="row" style="border-top: 2px solid red; margin-top: 20px;">
								<div class="col-md-10 col-md-offset-1" style="margin-top: 20px">
								<div class="col-sm-6">
									<p class="label label-success text-center">Front Side</p>
									<div class="id-card-four-land id-card-one-width id-card-one-height">
										<div class="row inst-info-section-land-4 id-card-one-color text-center id-card-font-color-land">
											<p class="inst-name-4-land inst-font-size-land">{{$instituteInfo->institute_name}}</p>
											<div class="inst-logo-4-land">
												@if($instituteInfo->logo)
													<img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
												@endif
											</div>
										</div>
										<div class="row std-info-section-4">
											<div class="col-sm-8 std-land-info-4 std-info-4 text-left">
												<table class="table-id-card-three-land">
													<tbody>
													<tr> <th width="30%">Name</th> <th width="5%">:</th> <th width="100%">Shakib Hossain</th> </tr>
													<tr> <th>ID NO:</th> <th>:</th> <td>01</td> </tr>
													<tr> <th>Class</th> <th>:</th> <td>Class Six</td> </tr>
													<tr> <th>Section</th> <th>:</th> <td>A</td> </tr>
													<tr> <th>Blood Group</th> <th>:</th> <td>A+ (positive)</td> </tr>
													</tbody>
												</table>
												<p class="principal-sing">Principal</p>
											</div>
											<div class="col-sm-4 std-photo std-land-photo-4">
												<img  src="{{asset('/assets/default.png')}}">
											</div>

										</div>
										<div class="row id-card-land-4-sing id-card-font-color-land">
											<p>Webbence School Street , Your City, Your State, Zip Code Email: School@gmail.com	, Web: www.school.com</p>
										</div>
										{{--<div class="row id-card-land-3-slogan">--}}
										{{--<p style="text-align: center">Your SlowGUn Her</p>--}}
										{{--</div>--}}
									</div>
								</div>
								<div class="col-sm-5">
									<p class="label label-success text-center">Back Side</p>
									<div class="id-card-one id-card-one-color id-card-font-color-land">
										<div class="row">
											<div class="col-md-12">
												<p class="text-center text-bold">{{$instituteInfo->address1}}</p>
												<hr/>
											</div>
											<div class="col-md-12">
												<p class="std-address std-address-first"><strong>Father's Name :</strong> Shakib Hossain</p>
												<p class="std-address"><strong>Mother's Name :</strong> Fathema Begum</p>
												<p class="std-address"><strong>Birth Date :</strong> 31-12-1991</p>
												<p>
													<strong>Emergency Contact :</strong><br/>
													<span class="std-address">01717375219 (home)</span><br/>
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
					@if($templateProfile->temp_id==1)
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
										@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
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
						@elseif($templateProfile->temp_id==2)
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
											<b class="inst-name-portrait">{{$instituteInfo->institute_name}} GGG</b> <br/>
											{{--{{$instituteInfo->address1}}--}}
										</div>
									</div>

									{{--<p class="id-card-label">Student ID Card</p>--}}
									<div class="row std-info-section">
										<div class="col-sm-12 std-photo-portrait" style="margin: 0 auto">
											{{--sort std profile--}}
											@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
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
				@endif
			@endforeach
		</div>
	</div>


	<script>
        $(document).ready(function () {
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

