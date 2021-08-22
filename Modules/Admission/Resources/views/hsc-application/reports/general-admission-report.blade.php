<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<style>

		table {
			width: 100%;
			font-size: 12px;
			border-collapse: collapse;
			border: 1px solid black;
		}

		tr, td, th {
			border: 1px solid #dddddd;
			text-align: left;
			padding: 7px;
			line-height: 5px;
		}
		/*.heading {*/
		/*border: none !important;*/
		/*}*/

		.header { left: 0px; right: 0px; height: 50px; text-align: center;}



		.header-section {
			width: 100%;
			position: relative;
		}
		.header-section .logo {
			width: 20%;
			float: left;
		}

		.header-section .logo img {
			float: left;
		}

		.header-section .text-section {
			width: 70%;
			float: left;
			text-align: center;
		}
		.header-section .text-section p {
			margin-right: 200px;
		}
		p.title {
			font-size: 20px;
			font-weight: bold;
			margin-top: 0px;
		}
		p.address-section {
			font-size: 12px;
			margin-top: -30px;
		}
		.heading{
			margin: 0px;
			padding: 7px;
			font-size: 13px;
			font-weight: bold;
		}


		/*th,td {line-height: 20px;}*/
		/*html{margin:25px 45px}*/

	</style>
</head>
<body>

<div class="header">
	<div class="header-section">
		<div class="logo">
			<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:60px;height:60px; margin-left: 10px">
		</div>
		<div class="text-section">
			<p class="title">{{$instituteInfo->institute_name}}</p><br/>
			<p class="address-section"> {{$instituteInfo->address1}}</p>
			<br/>
			<br/>
			<p class="address-section" style="font-size: 20px; font-weight: 700; text-decoration: underline;">Student Admission Report</p>
		</div>
	</div>
</div>
<div style="clear: both;"></div>
<div class="body">
	<table style="text-align:center">
		<thead>
		<tr>
			<th style="text-align:center">Year</th>
			<th style="text-align:center">Level</th>
			<th style="text-align:center">Class/Batch</th>
			<th style="text-align:center">Type</th>
		</tr>
		</thead>
		@php $inputs = json_decode($inputs) @endphp
		<tbody>
		<tr>
			<td style="text-align:center">{{$inputs->year_name}}</td>
			<td style="text-align:center">{{$inputs->level_name}}</td>
			<td style="text-align:center">{{$inputs->batch_name}}</td>
			<td style="text-align:center">{{$inputs->status_name}}</td>
		</tr>
		</tbody>
	</table>
	<br/>
	<br/>
	<p class="address-section" style="text-align:center; font-size: 20px; font-weight: 700; text-decoration: underline;">Applicant List</p>
	<table>
		<thead>
		<tr>
			<th>#</th>
			<th>Photo</th>
			<th style="text-align:center;">App. ID</th>
			<th>Student Name</th>
			<th>Father Name</th>
			<th style="text-align:center;">Mobile</th>
		</tr>
		</thead>

		<tbody>
		{{--checking applicant --}}
		@if($applicantList->count()>0)
			{{--applicant list looping--}}
			@foreach($applicantList as $index=>$appProfile)
				<tr>
					<td width="1%">{{$index+1}}</td>
					<td width="2%">
						{{--checking std photo--}}
						@if($appProfile->std_photo)
							<img src="{{$appProfile->std_photo}}" width="30px" height="30px">
						@else
							<img src="{{asset('assets/users/images/user-default.png')}}" width="30px" height="30px">
						@endif
					</td>
					<td width="21%" style="text-align:center;">{{$appProfile->a_no}}</td>
					<td width="50%">{{$appProfile->s_name}}</td>
					<td width="50%">{{$appProfile->f_name}}</td>
					<td width="30%" style="text-align:center;">{{$appProfile->s_mobile}}</td>
				</tr>
			@endforeach
		@else
			{{--not found msg--}}
			<tr> <td colspan="6" style="text-align:center;">No Record found.</td> </tr>
		@endif
		</tbody>
	</table>
</div>

</body>
</html>