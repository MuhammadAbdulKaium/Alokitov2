<html>
<head>
	{{--meta tag--}}
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	{{--style--}}
	<style>
		.text-center {text-align: center}
		.row {width: 100%; clear: both}
		.pull-left {float:left;}
		.pull-right {float:right;}

		#table {border-collapse: collapse; width: 100%;}
		#table td, #table th { border: 1px solid #000000; padding: 3px;}
		/*#student tr:nth-child(even){background-color: #f2f2f2;}*/
		/*#student tr:hover {background-color: #ddd;}*/
		#table th {
			/*padding-top: 12px;*/
			/*padding-bottom: 12px;*/
			/*text-align: left;*/
			/*background-color: #f2f2f2;*/
			/*color: white;*/
		}

		.inst-logo {width: 20%;}
		.inst-details {width: 80%}
		#title {font-size: 22px; font-weight: 700; margin: 0px 0px 5px 0px; padding: 0px;}
		#address {font-size: 13px; margin: 0px 0px 30px 0px; padding: 0px;}

		body {
			font-family: 'Open Sans', 'Trebuchet MS', Arial, Helvetica, sans-serif;
			font-weight: normal;
			color:#000000;
			font-size: 12px;
			position: relative;
		}
	</style>
</head>
<body>
@if($studentList->count()>0 AND $instituteInfo)
	<div class="row">
		<div class="inst-logo pull-left">
			{{--institute logo--}}
			@if($instituteInfo->logo)
				<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}" width="80" height="80">
			@else
				<img src="{{public_path().'/assets/users/images/user-default.png'}}" width="80" height="80">
			@endif
		</div>
		<div class="inst-details pull-right text-center">
			<p id="title">{{$instituteInfo->institute_name}}</p>
			<p id="address"> {{$instituteInfo->address1}} <br/> {{$instituteInfo->website}}</p>
		</div>
	</div>
	{{--std array list--}}
	@php $stdArrayList = [0=>0, 'M'=>0, 'F'=>0, 1=>0, 2=>0, 3=>0, 4=>0, 5=>0, 6=>0]; @endphp
	<div class="row">
		<table id="table" class="text-center">
			<thead>
			<tr>
				<th colspan="5">{{$reportDetails->class_name.' ('.$reportDetails->section_name.')'}}</th>
			</tr>
			<tr>
				<th>Roll</th>
				<th>Username</th>
				<th>Full Name</th>
				<th>Gender</th>
				<th>Religion</th>
			</tr>
			</thead>
			<tbody>
			@foreach($studentList as $index=>$std)
				<tr>
					<td>{{$std->gr_no}}</td>
					<td>{{$std->username}}</td>
					<td>{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}</td>
					<td>{{$std->gender}}</td>
					<td>
						{{--checking religion--}}
						@if($std->religion==1)
							Islam
							{{--std counter --}}
							@php $stdArrayList[1] +=1; @endphp

						@elseif($std->religion==2)
							Hindu
							{{--std counter --}}
							@php $stdArrayList[2] +=1; @endphp

						@elseif($std->religion==3)
							Christian
							{{--std counter --}}
							@php $stdArrayList[3] +=1; @endphp

						@elseif($std->religion==4)
							Buddhist
							{{--std counter --}}
							@php $stdArrayList[4] +=1; @endphp

						@elseif($std->religion==5)
							Others
							{{--std counter --}}
							@php $stdArrayList[5] +=1; @endphp

						@elseif($std->religion==NULL)
							-
							{{--std counter --}}
							@php $stdArrayList[6] +=1; @endphp

						@endif
					</td>
					{{--std counter --}}
					@php $stdArrayList[0] +=1; @endphp
					{{--checking gender--}}
					@if($std->gender=='Male')
						{{--Gernder counter --}}
						@php $stdArrayList['M'] +=1; @endphp
					@else
						{{--Gernder counter --}}
						@php $stdArrayList['F'] +=1; @endphp
					@endif
				</tr>
			@endforeach
			</tbody>
		</table>
	</div>
	<br/>
	<br/>
	<div class="row">
		<table id="table" class="text-center">
			<thead>
			<tr>
				<th colspan="9">Report Summary</th>
			</tr>
			<tr>
				<th>Total</th>
				<th>Male</th>
				<th>Female</th>
				<th>Islam</th>
				<th>Hindu</th>
				<th>Christian</th>
				<th>Buddhist</th>
				<th>Others</th>
				<th>N/A</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>{{$stdArrayList[0]}}</td>
				<td>{{$stdArrayList['M']}}</td>
				<td>{{$stdArrayList['F']}}</td>
				<td>{{$stdArrayList[1]}}</td>
				<td>{{$stdArrayList[2]}}</td>
				<td>{{$stdArrayList[3]}}</td>
				<td>{{$stdArrayList[4]}}</td>
				<td>{{$stdArrayList[5]}}</td>
				<td>{{$stdArrayList[6]}}</td>
			</tr>
			</tbody>
		</table>
	</div>
@else
	<div class="row"> <p class="text-center">No Student found</p></div>
@endif
</body>
</html>
