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
		}

		.text-center{
			text-align: center;
		}

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

		/*th,td {line-height: 20px;}*/
		html{margin:25px 25px}

		#admission_report tr>td:first-child{
			text-align: center;
			/*line-height: 50px;*/
		}

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
		</tr>
		</thead>
		@php $inputs = json_decode($inputs) @endphp
		<tbody>
		<tr>
			<td style="text-align:center">{{$inputs->year_name}}</td>
			<td style="text-align:center">{{$inputs->level_name}}</td>
			<td style="text-align:center">{{$inputs->batch_name}}</td>
		</tr>
		</tbody>
	</table>
	<br/>
	<table id="admission_report">
		<thead>
		<tr>
			<th width="1%">#</th>
			<th width="8%">
				Student Name <br/>
				Father's Name <br/>
				Mother's Name
			</th>
			<th width="4%" class="text-center">
				Admission Date <br/>
				Class Roll
			</th>
			<th width="3%">
				SSC / EQUUI <br/>
				Reg. No <br/>
				Roll. No <br/>
				Academic Year
			</th>
			<th width="8%">
				SSC / EQUUI <br/>
				Passing Year <br/>
				Board Name<br/>
				Passing Institute
			</th>
		</tr>
		</thead>

		<tbody>
		{{--checking applicant --}}
		@if($applicantList->count()>0)
			{{--applicant list looping--}}
			@foreach($applicantList as $index=>$appProfile)
				<tr>
					<td width="1%">{{$index+1}}</td>
					<td>
						{{$appProfile->s_name}} <br/>
						{{$appProfile->f_name}} <br/>
						{{$appProfile->m_name}}
					</td>
					<td class="text-center">
						{{date('d M, Y', strtotime($appProfile->updated_at))}} <br/>
						{{$index+1}}
					</td>
					<td>
						{{$appProfile->exam_reg}}  <br/>
						{{$appProfile->exam_roll}} <br/>
						{{$appProfile->exam_year}}
					</td>
					<td>
						{{$appProfile->exam_year}} <br/>
						{{ucfirst($appProfile->exam_board)}} <br/>
						{{ucfirst(strtolower($appProfile->exam_institute))}} <br/>
					</td>
				</tr>
			@endforeach
		@else
			{{--not found msg--}}
			<tr> <td colspan="5">No Record found.</td> </tr>
		@endif
		</tbody>
	</table>
</div>

</body>
</html>