<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{{--template setting--}}
	@php $tempSetting = null; $tempType = null; @endphp
	{{--checking template profle--}}
	@if($templateProfile)
		@php
			$tempSetting = json_decode($templateProfile->setting);
			$tempType =  $templateProfile->temp_type;
		@endphp
	@endif
	<style>

		/*body {*/
		/*font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;*/
		/*color: #333;*/
		/*background-color: #fff;*/
		/*}*/

		div {
			display: block;
		}

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


		.inst-logo img{
			width: 55px;
			height:50px;
		}

		.inst-logo-portrait img{
			width: 90px;
			height: 75px;
		}


		.inst-name{
			font-size: {{$tempSetting?($tempType==0?($tempSetting->title_font.'px'):'20px'):'20px'}};
			font-weight: 700;
			text-align: center;
		}



		.std-photo {
			width:32% ;
			height: auto;
		}

		.std-photo img{
			width:80px !important;
			height:90px;
			border: 2px solid blue;
		}

		p {
			margin: 0 0 10px;
			font-size: 13px;
		}

		table {
			border-spacing: 0;
			border-collapse: collapse;
			background-color: transparent;
			width: 100%;
			font-size: 13px;
			text-align: left;
		}

		b, strong {
			font-size: 13px;
		}

		hr{margin: 5px 0;}
		html{margin:0}



		.std-address-first{ margin-top: 10px}

		.std-address{
			font-size: 13px;
			line-height: 20px;
		}


		.text-center {
			text-align: center;
		}

		.text-left{
			text-align: left;
		}






		/*id card 2 design section */

		.inst-logo-2 {
			width: 35%;
			float: left;
			height: 60px;
		}

		.inst-logo-2 img {
			width: 70px;
			height: 60px;
		}

		.inst-info-2 {
			float: left;
			width: 100%;
			margin-left: 70px;
			height: auto;
			padding-left: 10px;
			padding-right: 10px;
			text-align: center;
			border-bottom: 1px solid blue;
		}



		.principal p {
			float: right;
			margin-right: 20px;
			font-size: 14px;
			font-weight: bold;
			color: blue;
		}

		{{--#opacity-image{--}}
			{{--height: 200px;--}}
			{{--width: 200px;--}}
			{{--display: block;--}}
			{{--position: relative;--}}

		{{--}--}}
		{{--#opacity-image:after{--}}
			{{--background: url("{{public_path().'/assets/users/images/'.$instituteInfo->logo}}");--}}
			{{--background-repeat: no-repeat;--}}
			{{--background-size: 100% auto;--}}
			{{--height: 224px;--}}
			{{--width: 170px;--}}
			{{--opacity: 0.1;--}}
			{{--top: 35px;--}}
			{{--left: 120px;--}}
			{{--position: absolute;--}}
			{{--z-index: 0;--}}
			{{--content: "";--}}
		{{--}--}}

		/*.square{*/
		/*margin: 5px;*/
		/*float: left;*/
		/*width: 200px;*/
		/*height: 200px;*/
		/*}*/

		.std-info-section {
			width: 100%;
			height: auto;
			float: left;
			clear: both;
			margin-top: 80px;
		}

		.std-info {
			float: left;
			width: 100%;
			margin-left: 100px;
			margin-top: -100px;
			height: auto;
			text-align: center;
		}

		.inst-info-section {
			width: 100%;
			float: left;
			height: auto;
		}

		/*#opacity-image{*/
		/*height: 100px;*/
		/*width: 100px;*/
		/*display: block;*/
		/*position: relative;*/

		/*}*/
		.opacity-image{
			opacity: 0.1;
			z-index: -1;
		}

		.opacity-image img {
			height: 170px;
			width: 180px;
			margin-top: 10px;
			margin-left: 130px;
		}


		/*end id card 2 design */



	</style>
</head>
<body>

<div style="width:100%; margin: 0 auto">
	{{--checking template type--}}
	{{--student list looping--}}
	@foreach($studentList as $index=>$stdInfo)
		@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
		@php $studentEnroll = $studentInfo->enroll(); @endphp
		@if($tempType==0)
			<div class="id-card-one" style="margin-top: 50px; margin-left: 100px">
				<div class="opacity-image">
					<img src="{{public_path().'/assets/id-card/'.$templateProfile->bg_image}}">
				</div>
				<div class="section-idcard" style="margin-top: -300px">
					<div class="inst-info-section">
						<div class="inst-logo-2">
							@if($instituteInfo->logo)
								<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}">
							@endif
						</div>
						<div class="inst-info-2">
							<b class="inst-name">{{$instituteInfo->institute_name}}</b> <br/>
							<p>{{$instituteInfo->address1}}</p>
						</div>
					</div>
					<div class="std-info-section">
						<div class="std-photo">

							{{--sort std profile--}}
							@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
							{{--check std profile photo--}}
							@if($studentInfo->singelAttachment('PROFILE_PHOTO'))
								{{--for web server--}}
								{{--<img src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">--}}
								{{--for local server--}}
{{--								<img src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">--}}
							@else
								{{--for web server--}}
								{{--<img  src="{{asset('/assets/users/images/user-default.png')}}">--}}

								{{--for local server--}}
								<img  src="{{public_path().'/assets/users/images/user-default.png'}}">
							@endif

						</div>
						<div class="std-info">
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

			<div class="id-card-one" style="margin-top: 50px; margin-left: 100px">
				<div class="row">
					<div class="col-sm-12">
						<p class="text-center text-bold">{{$instituteInfo->address1}}</p>
						<hr/>
					</div>
					<div class="col-sm-12">
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



			<div style="page-break-after:always;"></div>
		@endif
	@endforeach

	{{--@php $top=50; @endphp--}}
	{{--@for($i=0; $i<10; $i++)--}}
	{{--<div class="square">--}}
	{{--<div class="opacity-image">--}}
	{{--<img src="http://www.mountcarmel.edu.in/wp-content/uploads/2015/03/mount-carmel-logo.png">--}}
	{{--</div>--}}

	{{--</div>--}}

	{{--<div style="page-break-after:always;"></div>--}}
	{{--@endfor--}}

</div>

</body>

</html>
