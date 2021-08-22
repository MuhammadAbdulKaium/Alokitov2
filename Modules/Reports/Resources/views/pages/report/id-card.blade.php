<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{{--student information--}}
	<style>
		@page {
			margin-bottom: -{{$margin_bottom}}px;
			border: 1px solid blue;
		}
		.inst-logo{
			width: 20%;
		}
		.inst-logo img{
			width: 55px;
			height:50px;
		}

		.inst-info{
			width: 79%;
		}

		.inst-name{
			font-size: {{$fontSize}}px;
			font-weight: 900;
		}

		.id-card-label {
			border: 1px solid black;
			border-radius: 2px;
			font-size: 15px;
			font-weight: 700;
			margin-top: 25px;
		}

		.std-info{
			font-size: 13px;
		}

		.std-photo{

		}

		.std-photo img{
			width: 80px;
			height: 90px;
		}


		.clear{
			clear: both;
		}

		.pull-left{
			float: left;
		}

		.pull-right{
			float: right;
		}

		.text-center {
			text-align: center;
		}


		.id-card-row {
			width: 100%;
			margin-bottom: 20px;
		}

		.first-col{
			width: 49%;
		}

		.second-col{
			width: 49%;
		}

		.id-card-wrapper{
			border-radius: 5px;
			margin-bottom: 5px;
			width: {{$width}}px;
			height: {{$height}}px;
			padding: 10px;
			background-color: gainsboro;
		}
		.row-margin {
			margin-top: 10px;
		}
	</style>
</head>
<body>
@if($studentList->count()>0)
	{{--array type studentList--}}
	@php $myStudentList = $studentList->toArray(); @endphp
	{{--row counter--}}
	@php $rowCounter = 0; @endphp
	{{--looping--}}
	@for($i=0; $i<count($myStudentList); $i=$i+2)
		<div class="id-card-row row-margin clear">
			{{--row counter--}}
			@php $rowCounter +=1; @endphp
			{{--first div--}}
			@if($i< count($myStudentList))
				{{--get single std--}}
				@php $stdInfo = $myStudentList[$i]; @endphp
				@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
				@php $studentEnroll = $studentInfo->enroll(); @endphp
				{{--std ID Card--}}
				<div class="first-col pull-left">
					<div class="id-card-wrapper">
						<div class="id-card-row">
							<div class="inst-logo pull-left">
								@if($instituteInfo->logo)
									<img src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
								@endif
							</div>
							<div class="inst-info text-center pull-right">
								<b class="inst-name">{{$instituteInfo->institute_name}}</b> <br/>
								<span style="font-size: 13px">{{$instituteInfo->address1}}</span>
							</div>
						</div>
						<br><br>
						<p class="id-card-label text-center">Student ID Card</p>
						<div class="id-card-row">
							<div class="col-sm-8 std-info text-left pull-left">
								<table cellspacing="1" cellpadding="1">
									<tbody>
									<tr> <th>Name</th> <td>: {{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</td> </tr>
									<tr> <th>Gr NO</th> <td>: {{$studentInfo->enroll()->gr_no}}</td> </tr>
									<tr> <th>Year</th><td>: {{$studentEnroll->academicsYear()->year_name}}</td></tr>
									<tr> <th>Level</th><td>: {{$studentEnroll->level()->level_name}}</td></tr>
									<tr> <th>Class </th><td>: {{$studentEnroll->batch()->batch_name}}</td></tr>
									<tr> <th>Section</th><td>: {{$studentEnroll->section()->section_name}}</td></tr>
									</tbody>
								</table>
							</div>
							<div class="col-sm-4 std-photo pull-right">
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
			@endif
			{{--second div--}}
			@if($i+1<count($myStudentList))
				@php $stdInfo = $myStudentList[$i+1]; @endphp
				@php $studentInfo = findStudent($stdInfo['std_id']) @endphp
				@php $studentEnroll = $studentInfo->enroll(); @endphp
				{{--std ID Card--}}
				<div class="second-col pull-right">
					<div class="id-card-wrapper">
						<div class="id-card-row">
							<div class="inst-logo pull-left">
								@if($instituteInfo->logo)
									<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}">
								@endif
							</div>
							<div class="inst-info text-center pull-right">
								<b class="inst-name">{{$instituteInfo->institute_name}}</b> <br/>
								<span style="font-size: 13px">{{$instituteInfo->address1}}</span>
							</div>
						</div>
						<br><br>
						<div class="id-card-row">
							<p class="id-card-label text-center">Student ID Card</p>
							<div class="col-sm-8 std-info text-left pull-left">
								<table cellspacing="1" cellpadding="1">
									<tbody>
									<tr> <th>Name</th> <td>: {{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</td> </tr>
									<tr> <th>Gr NO</th> <td>: {{$studentInfo->enroll()->gr_no}}</td> </tr>
									<tr> <th>Year</th><td>: {{$studentEnroll->academicsYear()->year_name}}</td></tr>
									<tr> <th>Level</th><td>: {{$studentEnroll->level()->level_name}}</td></tr>
									<tr> <th>Class </th><td>: {{$studentEnroll->batch()->batch_name}}</td></tr>
									<tr> <th>Section</th><td>: {{$studentEnroll->section()->section_name}}</td></tr>
									</tbody>
								</table>
							</div>
							<div class="col-sm-4 std-photo pull-right">
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
			@endif
		</div>

		@if($rowCounter==4)
			{{--row counter--}}
			@php $rowCounter=0; @endphp

			<div style="page-break-after:always;"></div>
		@endif
	@endfor
@else
	<p class="text-center">No resource found</p>
@endif
</body>
</html>
