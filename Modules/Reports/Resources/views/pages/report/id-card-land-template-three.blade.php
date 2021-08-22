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


		.id-card-three-land{
			width: {{$tempSetting?($tempType==0?($tempSetting->width.'px'):'350px'):'350px'}};
			height: {{$tempSetting?($tempType==0?($tempSetting->height.'px'):'210px'):'210px'}};
		}

		.inst-info-section-land-3{
			margin: 0px;
			padding: 0px;
			background: {{$tempSetting?($tempType==0?($tempSetting->color):'blue'):'blue'}};
			height: 50px;
		}
		.id-card-heading{
			float: right;
			border-bottom: 2px solid #bab8b8;
			border-top-left-radius: 100px;
			border-bottom-left-radius: 100px;
			background:#E5E9EB;
			width: 100%;
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

		.std-info-section {
			height: 90px;
			width: 100%;
			float: left;
		}

		.std-photo {
			width: 30%;
			float: left;
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
</head>
<body>

<div style="width:100%; margin: 0 auto">
	{{--checking template type--}}
	{{--student list looping--}}
	@foreach($studentList as $index=>$stdInfo)
		@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
		@php $studentEnroll = $studentInfo->enroll(); @endphp
		@if($tempType==0)
			<div class="col-md-10 col-md-offset-1" style="margin-top: 20px">
				<div class="col-sm-6">
					<div class="id-card-three-land id-card-one-width id-card-one-height">
						<div class="row inst-info-section-land-3 id-card-one-color text-center">
							<div class="inst-logo-2-land id-card-one-color">
								@if($instituteInfo->logo)
									<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}">
								@endif
							</div>
							<div class="inst-info-2-land ">
								<b class="inst-name-2-land inst-font-size-land">{{$instituteInfo->institute_name}}</b>
								<p>{{$instituteInfo->address1}}</p>
							</div>
						</div>
						{{--<div class="id-card-heading">--}}
							{{--<p>ID CARD               2012-2013</p>--}}
						{{--</div>--}}
						<div class="row std-info-section">
							<div class="col-sm-4 std-photo std-land-photo-2">
								<img  src="{{public_path().'/assets/default.png'}}">
							</div>
							<div class="col-sm-8 std-land-info-2 std-info-2">
								<table class="table-id-card-three-land">
									<tbody>
									<tr> <th width="30%">Name</th> <th width="5%">:</th> <th width="100%">AKM AMIRUL ISLAM</th> </tr>
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
