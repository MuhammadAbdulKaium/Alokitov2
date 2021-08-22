<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	{{--student information--}}
	<style>
		/*.inst-logo{*/
		/*width: 35%;*/
		/*}*/
		.inst-logo img{
			width: 65px;
			height:70px;
			margin-top: 10px;
		}

		.inst-info{
			width: 100%;
			margin-top: -10px;
		}

		.inst-name{
			font-size: 23px;
			font-weight: 700;
		}

		.id-card-label {
			border: 1px solid black;
			border-radius: 2px;
			font-size: 13px;
			font-weight: 700;
			text-align: center;
			display: inline-block;
			padding: 3px 30px;
		}

		.std-info{
			font-size: 11px;
			width: 79%;
		}

		.std-photo{
			width: 20%;
		}

		.std-photo img{
			width: 100px;
			height:100px;
			/*margin-left: 0px;*/
			margin-top: -80px;
			float: right;
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

		.text-right {
			text-align: right;
		}


		.id-card-row {
			width: 100%;
		}

		.first-col{
			width: 100%;
		}

		.routine{
			border: 1px solid #000000;
			border-collapse: collapse;
			line-height: 15px;
		}


		.id-card-wrapper{
			border: 4px solid black;
			border-radius: 1px;
			/*margin-bottom: 20px;*/
			height: auto;
			/*background-color: gainsboro;*/
			padding:20px;
		}
		.heading {
			margin-top:-30px;
			text-align: center;
			width: 100%;
		}

		.page {
			overflow: hidden;
			page-break-after: always;
		}

		.singnature{
			height: 20px;
			width: 75px;
			margin-top: -10px !important;
			margin-bottom: -10px;
		}

		html{margin:0px; padding: 0px}
	</style>
</head>
<body>
@if($studentList->count()>0 AND !empty($examRoutine) AND count($examRoutine)>0)
	{{--loop counter--}}
	@php $loopCounter = 1; @endphp
	{{--student list looping--}}
	@foreach($studentList as $index=>$student)
		{{--student information--}}
		@php
			$studentInfo = $student->student();
			// find student addition subject list
			$myAdditionalSubList = array_key_exists($studentInfo->id, $additionalArrayList)?$additionalArrayList[$studentInfo->id]:[]
		@endphp

		<div class="first-col" style=" margin-top:{{$loopCounter>1?'20px':'5px'}}; height: 410px;">
			<div class="id-card-wrapper">
				<div class="id-card-row" style="width: 100%">
					<div class="inst-logo pull-left">
						@if($instituteInfo->logo)
							<img class="pull-right" src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
						@endif
					</div>
					<div class="inst-info text-center">
						<b class="inst-name">{{$instituteInfo->institute_name}}</b> <br/>
						<span style="font-size: 12px">{{$instituteInfo->address1}}</span>
					</div>
				</div>
				<br>
				<br>
				<div class="heading">
					<p class="id-card-label text-center">ADMIT CARD</p>
				</div>
				<div class="id-card-row">
					<div class="col-sm-8 std-info text-left pull-left">
						<table style="font-size: 12px; line-height: 20px" width="100%" border="1px solid black" class="text-left routine">
							{{--<table style="font-size: 15px" width="100%"  cellspacing="1" cellpadding="1">--}}
							{{--<table style="font-size: 14px" width="100%"  cellspacing="1" cellpadding="1" border="1px solid black">--}}
							<tbody>
							<tr>
								<th width="15%">Student Name</th>
								<th width="1%">: </th>
								<td> <b style="font-size: 15px">&nbsp;{{$student->first_name." ".$student->middle_name." ".$student->last_name}}</b></td>
								<th width="6%">Class</th>
								<th width="1%">:</th>
								@php
									// batch profile
									$batch = $student->batch();
									// checking batch division
									if ($batch->get_division()) {
										$batchName = $batch->batch_name . " - " . $batch->get_division()->name;
									} else {
										$batchName = $batch->batch_name;
									}
								@endphp
								<td>&nbsp; &nbsp; {{$batchName}} - ({{$student->section()->section_name}})</td>
								<th width="6%">Roll</th>
								<th width="1%">:</th>
								<td> &nbsp; &nbsp; <strong>{{$student->gr_no}}</strong></td>
							</tr>
							</tbody>
						</table>
					</div>
					<div class="col-sm-4 std-photo pull-right">
						{{--check std profile photo--}}
						@if($studentInfo->singelAttachment('PROFILE_PHOTO'))
							<img src="{{asset('/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}">
						@else
							<img  src="{{asset('/assets/users/images/user-default.png')}}">
						@endif
					</div>
					<br/>
					<br/>
				</div>
				<div style="margin-top: -20px; font-size: 14px; width: 100%; text-align: center;">
					<h3 class=" text-center clear">{{$semesterProfile->name}}</h3>
				</div>
				<div class="id-card-row clear" style="margin-top: -10px;">
					<table style="font-size: 11px" width="100%" border="1px solid black" class="text-center routine">
						<thead>
						<tr>
							<th>Subject name</th>
							<th>Subject Code</th>
							<th>Exam Date</th>
							<th>Exam Start Time</th>
							<th>Exam End Time</th>
						</tr>
						</thead>
						<tbody>
						@for($i=1; $i<=count($examRoutine); $i++)

							{{--find first subject--}}
							@php
								$firstSubject = (object)$examRoutine[$i];
								$subId = $firstSubject->sub_id;
								$subType = $firstSubject->sub_type;
							@endphp
							{{--checking subject type--}}
							@if($subType==1 || in_array($subId, $myAdditionalSubList))
								<tr>
									{{--subject details--}}
									<td>{{$firstSubject->sub_name}}</td>
									<td>{{$firstSubject->sub_code}}</td>
									<td>{{date('d M, Y',strtotime($firstSubject->date))}}</td>
									<td>{{$firstSubject->start_time}}</td>
									<td>{{$firstSubject->end_time}}</td>
								</tr>
							@endif
						@endfor
						</tbody>
					</table>
				</div>
				<br><br><br>
				<div class="heading">
					<div class="col-md-6 pull-left text-center">
						{{--checking auth sign--}}
						{{--@if($reportCardSetting AND $reportCardSetting->auth_sign!=null AND !empty($reportCardSetting->auth_sign))--}}
						{{--<br/>--}}
						{{--@endif--}}
						<br/>Convener
					</div>
					<div class="col-md-6 pull-right text-center">
						{{--checking auth sign--}}
						@if($reportCardSetting AND $reportCardSetting->auth_sign!=null AND !empty($reportCardSetting->auth_sign))
							<img class="singnature" src="{{public_path().'/assets/users/images/'.$reportCardSetting->auth_sign}}"><br/>
						@endif
						<br/>Principal
					</div>
				</div>
				<br>
			</div>
		</div>

		{{--checking loop counter--}}
		@if($loopCounter==3)
			<div class="page"> </div>
			{{--loop counter--}}
			@php $loopCounter = 1; @endphp
		@else
			{{--loop counter--}}
			@php $loopCounter += 1; @endphp
		@endif
	@endforeach
@else
	<p class="text-center">No resource found</p>
@endif

</body>
</html>
