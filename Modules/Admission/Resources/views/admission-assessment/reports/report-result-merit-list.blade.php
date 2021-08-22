<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
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
		.report_card_table{
			border: 1px solid #dddddd;
			border-collapse: collapse;
		}
	</style>
</head>
<body>
<div class="container">
	{{--Institute info part--}}
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

	{{--Application info part--}}
	<div class="section clear">
		<p class="label row-second text-center">Exam Information</p>
		<table width="100%" border="1px solid" class="report_card_table text-center" cellpadding="5">
			<tbody>
			<tr class="row-second">
				<th class="text-center"><a>Academic Details</a></th>
				<th class="text-center"><a>Exam Date</a></th>
				<th class="text-center"><a>Enroll Students</a></th>
			</tr>
			<tr>
				<td class="text-center">
					{{$academicInfo->year_name}} / {{$academicInfo->level_name}} ({{$academicInfo->batch_name}})
				</td>
				<td class="text-center">
					{{date('d M, Y', strtotime($examDetails->exam_date))}}
				</td>
				<td>{{$examDetails->merit_list_std_no}}</td>
			</tr>
			</tbody>
		</table>
	</div>

	{{--personal info part--}}
	<div class="section clear">
		<p class="label text-center row-second"> Merit List </p>
		<table width="100%" border="1px solid" class="report_card_table text-center" cellpadding="5">
			<thead>
			<tr class="row-second">
				<th class="text-center">#</th>
				<th class="text-center">Application No</th>
				<th class="text-center">Name</th>
				<th class="text-center">Exam Marks</th>
				<th class="text-center">Exam Result</th>
				<th class="text-center">Merit Position</th>
			</tr>
			</thead>
			<tbody>
			@php $i=1; @endphp
			@foreach($applicantResultSheet as $applicantResult)
				{{--applicant ID--}}
				@php $applicant = $applicantResult->application(); @endphp
				@php $applicantInfo = $applicant->personalInfo(); @endphp

				@php $examDetails = $applicantResult->examDetails(); @endphp
				@php $examGrade = $applicantResult->grade(); @endphp
				{{--table row--}}
				<tr>
					<td class="text-center">{{$i++}}</td>
					<td class="text-center">{{$applicant->application_no}}</td>
					<td class="text-center">
						{{$applicantInfo->std_name}}
					</td>
					<td class="text-center">
						{{$examGrade->applicant_grade." / ".$examDetails->exam_marks}}
					</td>
					<td class="text-center">{{$applicantResult->applicant_exam_result=='0'?'Failed':'Passed'}}</td>
					<td class="text-center">{{$applicantResult->applicant_merit_position}}</td>
				</tr>
				@php $i = ($i++); @endphp
			@endforeach
			</tbody>
		</table>
	</div>
</div>
</body>
</html>
