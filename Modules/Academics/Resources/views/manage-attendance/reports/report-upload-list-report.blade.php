<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<style type="text/css">
		@if($requestType=='pdf')

		.label {font-size: 15px;  padding: 5px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
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

		thead{display: table-header-group;}
		tfoot {display: table-row-group;}
		tr {page-break-inside: avoid;}

		.report_card_table{
			border: 1px solid #dddddd;
			border-collapse: collapse;
		}

		@else

		table>thead>tr>th, table>tbody>tr>td{
			text-align: center;
			height: 22px;
		}

		@endif
	</style>

</head>
<body>

@if($requestType=='pdf')
	<div id="inst" class="text-center clear " style="width: 100%;">
		<div id="inst-photo">
			@if($instituteInfo->logo)
				<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
			@endif
		</div>
		<div id="inst-info">
			<b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
		</div>
	</div>
@endif

@if($requestType=='pdf')
	{{--Student Infromation--}}
	<div class="clear" style="width: 100%;">
		<p class="label text-center row-second">Upload Report</p>
		@endif

		@if(!empty($attendanceArrayList))
			<table width="100%" border="1px solid" class="report_card_table text-center" cellpadding="5">
				<thead>
				@if($requestType=='pdf')
					<tr class="bg-gray-active">
						<th width="10%">GR. NO</th>
						<th>Full Name</th>
						{{--<th width="20%">Class (Section)</th>--}}
						<th>Attendance Date</th>
						<th>Entry Time</th>
						<th>Out Time</th>
						<th>Attendance Type</th>
					</tr>
				@else
					<tr class="bg-gray-active">
						<th width="15%">GR. NO</th>
						<th width="30%">Full Name</th>
						{{--<th width="25%">Class (Section)</th>--}}
						<th width="15%">Attendance Date</th>
						<th width="20%">Entry Time</th>
						<th width="20%">Out Time</th>
						<th width="15%">Attendance Type</th>
					</tr>
				@endif
				</thead>
				<tbody class="text-bold">
				@php $i=1; @endphp
				@foreach ($attendanceArrayList as $key=>$attendance)
					{{--checking report type--}}
					@if($reportType=='ALL')
						{{--ALL type statements--}}
					@elseif ($reportType=='PRESENT')
						@if($attendance['att_type'] == 'ABSENT') @continue @endif
					@elseif ($reportType=='LATE_PRESENT')
						@if($attendance['att_type'] == 'PRESENT' || $attendance['att_type'] == 'ABSENT') @continue @endif
					@elseif ($reportType=='ABSENT')
						@if($attendance['att_type'] != 'ABSENT') @continue @endif
					@endif
					{{--student profile--}}
					@php $stdProfile = $attendance['std_profile'] @endphp
					<tr class="{{$i%2==0?'bg-gray':'bg-gray-active'}}">
						<td> {{$stdProfile->gr_no}} </td>
						<td> {{$stdProfile->name}} </td>
						{{--<td> {{$stdProfile->enroll}} </td>--}}
						<td> {{$attendance['att_date']}} </td>
						<td class="{{$attendance['att_type']=='ABSENT'?'text-danger':''}}">
							{{--checking--}}
							@if($attendance['att_type']!='ABSENT') {{$attendance['entry_date_time']}} @else - @endif
						</td>
						<td class="{{$attendance['att_type']=='ABSENT'?'text-danger':''}}">
							{{--checking--}}
							@if($attendance['att_type']!='ABSENT') {{$attendance['out_date_time']}} @else - @endif
						</td>
						<td class="{{$attendance['att_type']=='ABSENT'?'text-danger':''}}"> {{$attendance['att_type']}} </td>
					</tr>
					@php $i+=1; @endphp
				@endforeach
				</tbody>
			</table>
		@else
			<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in">
				<h5 class="text-bold"><i class="fa fa-warning"></i> No records found </h5>
			</div>
		@endif

		@if($requestType=='pdf')
	</div>
@endif
</body>
</html>
