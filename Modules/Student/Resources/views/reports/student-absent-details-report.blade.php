<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
		.label {font-size: 15px; padding: 5px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
		.row-first{background-color: #b0bc9e;}
		.row-second{background-color: #e5edda;}
		.row-third{background-color: #5a6c75;}
		.text-center {text-align: center; font-size: 12px}
		.clear{clear: both;}

		#std-info {
			float:left;
			width: 79%;
		}
		#std-photo {
			float:left;
			width: 20%;
			margin-left: 10px;
		}
		#std-photo img{
			width: 100%;
			height: 100%;
		}

		#inst-photo{
			float:left;
			width: 15%;
		}
		#inst-info{
			float:left;
			width: 85%;
		}

		#inst{
			padding-bottom: 20px;
			width: 100%;
		}

		body{
			font-size: 11px;
		}
		.report_card_table{
			border: 1px solid #dddddd;
			border-collapse: collapse;
		}
	</style>
</head>

<body>
<div id="inst" class="text-center clear" style="width: 100%;">
	<div id="inst-photo">
		@if($instituteInfo->logo)
{{--			<img src="{{URL::asset('assets/users/images/'.$instituteInfo->logo)}}"  style="width:80px;height:80px">--}}
			<img src="{{public_path().'assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
		@endif
	</div>
	<div id="inst-info">
		<b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
	</div>
</div>

{{--Student Infromation--}}
<div class="clear" style="width: 100%;">
	<p class="label text-center row-second">Student Information</p>
	<div id="std-info">
		<table width="100%" border="1px solid" class="report_card_table" cellpadding="5">
			<tr>
				<th>Name:</th>
				<td colspan="3"> {{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</td>
			</tr>
			<tr>
				<th>Date of Birth:</th>
				<td>{{ date('d M, Y', strtotime($studentInfo->dob)) }}</td>
				<th>Gender:</th>
				<td>{{$studentInfo->gender}}</td>
			</tr>
			<tr>
				<th>Nationality:</th>
				<td>
					{{$studentInfo->nationality()?$studentInfo->nationality()->nationality:' - '}}
				</td>
				<th>Religion:</th>
				<td>
					@php
						switch($studentInfo->religion)
                        {
                           case '1': echo "Islam"; break;
                           case '2': echo "Hinduism"; break;
                           case '3': echo "Christianity"; break;
                           case '4': echo "Buddhism"; break;
                           case '5': echo "Others"; break;
                        }
					@endphp
				</td>
			</tr>
			<tr>
				<th>Birthplace:</th>
				<td>{{$studentInfo->birth_place}}</td>
				<th>Bloodgroup:</th>
				<td>{{$studentInfo->blood_group}}</td>
			</tr>
			<tr>
				<th>Student Type:</th>
				<td>
					@php
						switch($studentInfo->type) {
						   case '1': echo "Pre Admission"; break;
						   case '2': echo "Regular"; break;
						}
					@endphp
				</td>
				<th>Languages:</th>
				<td>{{$studentInfo->nationality()?$studentInfo->nationality()->nationality:' - '}}</td>
			</tr>
		</table>
	</div>
	<div id="std-photo">
		@if($studentInfo->singelAttachment('PROFILE_PHOTO'))
{{--			<img src="{{URL::asset('assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"  style="width:130px;height:125px">--}}
			<img src="{{public_path().'/assets/users/images/'.$studentInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name}}"  style="width:130px;height:125px">
		@else
{{--			<img  src="{{URL::asset('assets/users/images/user-default.png')}}" style="width:130px;height:125px">--}}
			<img  src="{{public_path().'/assets/users/images/user-default.png'}}" style="width:130px;height:125px">
		@endif
	</div>
</div>

<div class="clear">
	<!-- attendance Report -->
	<p class="label text-center row-second">Student Absent Days Report</p>
@if($attType=='subject')
	<!-- subject wise attendance -->
		<table width="100%" border="1px solid" class="text-center report_card_table">
			<tbody>
			<tr class="row-second">
				<th class="text-center">Date</th>
				@foreach($academicSubjects as $key => $subject)
					<th class="text-center">{{$subject['name']}}</th>
				@endforeach
			</tr>
            <?php $absentCountList = array(); ?>
			@for($i=0; $i<count($studentAttendanceList);$i++)
				<tr>
					<td width="80px" class="text-center">{{date('d M, Y', strtotime($studentAttendanceList[$i]['date']))}}</td>
                    <?php $attendanceList = (array)$studentAttendanceList[$i]['attendance']; ?>
					@for($j =0; $j<count($academicSubjects);$j++)
						<td class="text-center">
							@if(array_key_exists($academicSubjects[$j]['id'], $attendanceList) == true)
								@if($attendanceList[$academicSubjects[$j]['id']] == 0 )
									A
									@if(array_key_exists($academicSubjects[$j]['id'], $absentCountList) == false)
										@php
											$absentCountList[$academicSubjects[$j]['id']]['P'] = 0;
											$absentCountList[$academicSubjects[$j]['id']]['A'] = 0;
										@endphp
									@endif
									@php $absentCountList[$academicSubjects[$j]['id']]['A'] = ($absentCountList[$academicSubjects[$j]['id']]['A']+1); @endphp
								@else
									{{--P--}}
									@if(array_key_exists($academicSubjects[$j]['id'], $absentCountList) == false)
										@php
											$absentCountList[$academicSubjects[$j]['id']]['P'] = 0;
											$absentCountList[$academicSubjects[$j]['id']]['A'] = 0;
										@endphp
									@endif
									@php $absentCountList[$academicSubjects[$j]['id']]['P'] = ($absentCountList[$academicSubjects[$j]['id']]['P']+1); @endphp
								@endif
							@else
								N/A
							@endif
						</td>
					@endfor
				</tr>
			@endfor
			<tr class="row-first">
				<th class="text-center"> Total</th>
				@for($m =0; $m<count($academicSubjects);$m++)
					<th class="text-center">
						@if(array_key_exists($academicSubjects[$m]['id'], $absentCountList) == true)
							A:{{$absentCountList[$academicSubjects[$m]['id']]['A']}}

							{{--P: {{$absentCountList[$academicSubjects[$m]['id']]['P']}}--}}
						@else
							N/A
						@endif
					</th>
				@endfor
			</tr>
			</tbody>
		</table>
@else
	<!-- date wise attendance -->
		<table width="100%" border="1px solid" class="text-center report_card_table">
			<tbody>
			<tr class="row-second">
				<th class="text-center">Date</th>
				<th class="text-center">Attndance</th>
			</tr>
			@php $absentCountList = array(); @endphp
			@for($i=0; $i<count($studentAttendanceList);$i++)
				<tr>
					<td class="text-center">{{$studentAttendanceList[$i]['date']}}</td>
					<td class="text-center">
						@if($studentAttendanceList[$i]['attendance']==1)
							A
							@if(array_key_exists('date', $absentCountList) == false)
								@php $absentCountList['date']['P'] = 0; $absentCountList['date']['A'] = 0; @endphp
							@endif
							@php $absentCountList['date']['A'] = ($absentCountList['date']['A']+1); @endphp
						@else
							{{--P--}}
							@if(array_key_exists('date', $absentCountList) == false)
								@php $absentCountList['date']['P'] = 0; $absentCountList['date']['A'] = 0; @endphp
							@endif
							@php $absentCountList['date']['P'] = ($absentCountList['date']['P']+1); @endphp
						@endif
					</td>
				</tr>
			@endfor
			<tr class="row-first">
				<th class="text-center"> Total</th>
				<th class="text-center">
					@if(array_key_exists('date', $absentCountList) == true)
						A:{{$absentCountList['date']['A']}}
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						P: {{$absentCountList['date']['P']}}
					@else
						N/A
					@endif
				</th>
			</tr>
			</tbody>
		</table>
	@endif
</div>
</body>
</html>
