<html>

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<!-- Student Information -->
	<style type="text/css">
		.label {font-size: 18px;  padding: 5px; border: 1px solid #000000; border-radius: 3px; font-weight: 700;}
		.text-center {text-align: center !important;}
		.clear{clear: both;}

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

		#customers {
			font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		#customers td, #customers th {
			border: 1px solid #ddd;
			padding: 8px;
		}

		#customers tr:nth-child(even){background-color: #f2f2f2;}

		#customers tr:hover {background-color: #ddd;}

		#customers th {
			padding-top: 12px;
			padding-bottom: 12px;
			text-align: left;
			background-color: #4CAF50;
			color: white;
		}

		body{
			font-size: 11px;
		}

		html{margin:30px}

	</style>
</head>
<body>

<div id="inst" class="text-center clear" style="width: 100%;">
	<div id="inst-photo">
		@if($instituteInfo->logo)
			<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
		@endif
	</div>
	<div id="inst-info">
		<b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
	</div>
</div>

<div id="report-card" class="clear">
	<p class="label text-center text-bold">Enrollment History</p>
	<table id="customers" class="report_card_table table table-bordered table-responsive table-striped text-center">
		<thead>
		<tr>
			<th>#</th>
			<th>Name</th>
			<th>Academic Year</th>
			<th>Level</th>
			<th>Batch</th>
			<th>Section</th>
			<th>Enroll Status</th>
		</tr>
		</thead>
		<tbody>
		{{--enroll list checking--}}
		@if($enrollHistory->count()>0)
			{{--enroll list looping--}}
			@foreach($enrollHistory as $index=>$enroll)
				{{--student porfile--}}
				@php $stdProfile = (object) $enroll->enroll()->student(); @endphp
				<tr>
					<td>{{($index+1)}}</td>
					<td>{{$stdProfile->first_name.' '.$stdProfile->middle_name.' '.$stdProfile->last_name}}</td>
					<td>{{$enroll->academicsYear()->year_name}}</td>
					<td>{{$enroll->level()->level_name}}</td>
					<td>{{$enroll->batch()->batch_name}}</td>
					<td>{{$enroll->section()->section_name}}</td>
					<td>{{$enroll->batch_status}}</td>
				</tr>
			@endforeach
		@else
			<tr>
				<td colspan="7">No Records found.</td>
			</tr>
		@endif
		</tbody>
	</table>
</div>
</body>

</html>
