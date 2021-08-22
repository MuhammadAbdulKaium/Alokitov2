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
			text-align: center;
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
			margin-left: 275px;
		}

		.std-photo-portrait img{
			width: 80px;
			height: 90px;
			margin-left: 70px;
		}

		.std-address-first{ margin-top: 10px}

		.std-address{
			font-size: 13px;
			line-height: 20px;
		}

		.std-address-portrait{
			font-size: 12px;
			line-height: 20px;
		}

		.text-center {
			text-align: center;
		}

		.text-left{
			text-align: left;
		}

		.col-sm-2 {
			width: 16.66666667%;
			float: left;
		}

		.col-sm-4 {
			width: 30%;
			float: left;
		}

		.col-sm-6 {
			width: 50%;
			float: left;
		}

		.col-sm-8 {
			width: 65%;
			float: left;
		}

		.col-sm-10 {
			width: 83.33333333%;
			float: right;
		}

		.col-sm-12 {
			width: 100%;
			float: left;
		}

		.row{
			width: 100%;
			clear: both;
		}


		p {
			margin: 0 0 10px;
		}

		table {
			border-spacing: 0;
			border-collapse: collapse;
			background-color: transparent;
			width: 100%;
			font-size: 13px;
			text-align: left;
			font-weight: 700;
		}

		b, strong {
			font-size: 13px;
			font-weight: bold;
		}

		hr{margin: 5px 0;}
		html{margin:0}





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
			background: url("{{public_path().'/assets/users/images/'.$instituteInfo->logo}}");
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
</head>
<body>

<div style="width:100%; margin: 0 auto">
	{{--checking template type--}}
	{{--student list looping--}}
	@foreach($studentList as $index=>$stdInfo)
		{{--student details--}}
		@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
		@php $studentEnroll = $studentInfo->enroll(); @endphp
			<div class="row" style="margin: 25px 0; padding: 15px;" >
				<div class="col-sm-6">
					<div class="id-card-one">
						<div class="row inst-info-section text-center" style="clear: both">
							<div class="col-sm-2 inst-logo">
								@if($instituteInfo->logo)
									{{--for web server--}}
									{{--<img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">--}}

									{{--for local server--}}
									<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}">
								@endif
							</div>
							<div class="col-sm-10 inst-info">
								<b class="inst-name">{{$instituteInfo->institute_name}}</b> <br/>
								{{$instituteInfo->address1}}
							</div>
						</div>
						<br/><br/><br/>
						<p class="id-card-label">Student ID Card</p>
						<div class="row std-info-section">
							<div class="col-sm-8 std-info text-left">
								<table width="100%">
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
									{{--for web server--}}
									{{--<img src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">--}}
									{{--for local server--}}
									<img src="{{public_path().'/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name}}">
								@else
									{{--for web server--}}
									{{--<img  src="{{asset('/assets/users/images/user-default.png')}}">--}}

									{{--for local server--}}
									<img  src="{{public_path().'/assets/users/images/user-default.png'}}">
								@endif
							</div>
						</div>
					</div>

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
			{{--checking student loop count--}}
			{{--@if($index%2==0)--}}
			<div style="page-break-after:always;"></div>
			{{--@endif--}}

	@endforeach
</div>

</body>

</html>
