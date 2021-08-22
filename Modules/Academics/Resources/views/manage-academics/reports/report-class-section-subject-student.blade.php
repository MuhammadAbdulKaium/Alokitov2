<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<style type="text/css">
		.label {font-size: 15px;  padding: 5px; border: 1px solid #000000; border-radius: 1px; font-weight: 700;}
		.text-center {text-align: center;}
		.text-left {text-align: left;}
		.text-right {text-align: right;}

		#inst-photo{
			float:left;
			width: 15%;
		}

		#inst-info{
			float:left;
			width: 85%;
		}

		.row{ width: 100%; clear: both; }

		body{ font-size: 15px;}

		#customers { font-family: "Trebuchet MS", Arial, Helvetica, sans-serif; border-collapse: collapse; width: 100%; margin: 0 auto;}
		#customers td, #customers th { border: 1px solid #ddd; padding: 2px;}
		#customers tr:nth-child(even){background-color: #f2f2f2;}
		#customers tr:hover {background-color: #ddd;}
		#customers th { padding:5px; background-color: #bfbbb4; color: black; text-align: center }

		#customers span{
			margin-left: 25px;
		}
		/*html{margin:5px}*/
	</style>
</head>
<body>
{{--checking download type--}}
@if($myRequest['download_type']=='pdf')
	<div class="row text-center clear">
		<div id="inst-photo">
			@if($instituteInfo->logo)
				<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px; margin-bottom: 20px;">
			@endif
		</div>
		<div id="inst-info">
			<b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>
			<span style="font-size: 13px; font-weight:500">
			{{$instituteInfo->address1}}<br/>
				{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
		</span>
		</div>
	</div>
@endif

<div class="row clear">
	{{--checking download type--}}
	@if($myRequest['download_type']=='pdf')
		<p class="label text-center">Student List</p>
	@endif
	<table id="customers">
		<thead>
		<tr>
			<th colspan="6">
				<span>Batch: {{$myRequest['batch_name']}} </span>
				<span>Section: {{$myRequest['section_name']}} </span>
				<span>Subject: {{$myRequest['subject_name']}}</span>
			</th>
		</tr>
		{{--checking download type--}}
		@if($myRequest['download_type']=='pdf')
			{{--header for pdf file--}}
			<tr>
				<th width="6%">#</th>
				<th width="6%">Roll</th>
				<th>Name</th>
				<th width="10%">Gender</th>
				<th width="20%">User ID</th>
				<th width="20%">Phone</th>
			</tr>
		@else
			{{--header for excell file--}}
			<tr>
				<th width="5%" class="text-center">#</th>
				<th width="5%" class="text-center">Roll</th>
				<th width="30%" class="text-center">Name</th>
				<th width="10%" class="text-center">Gender</th>
				<th width="20%" class="text-center">User ID</th>
				<th width="20%" class="text-center">Phone</th>
			</tr>
		@endif
		</thead>
		<tbody>
		{{--Checking student List--}}
		@if(count($studentList)>0)
			{{--student Looping--}}
			@foreach($studentList as $index=>$student)
				<tr>
					<td class="text-center">{{($index+1)}}</td>
					<td class="text-center">{{$student['gr_no']}}</td>
					<td> &nbsp; &nbsp; {{ucwords(strtolower($student['name']))}}</td>
					<td class="text-center">{{$student['gender']}}</td>
					<td class="text-center">{{$student['username']}}</td>
					<td class="text-center">{{$student['phone']}}</td>
				</tr>
			@endforeach
		@else
			<tr>
				<td colspan="5">No Records found.</td>
			</tr>
		@endif
		</tbody>
	</table>
</div>



</body>
</html>
