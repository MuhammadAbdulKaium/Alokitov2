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

		thead{display: table-header-group;}
		tfoot {display: table-row-group;}
		tr {page-break-inside: avoid;}

		.report_card_table{
			border: 1px solid #dddddd;
			border-collapse: collapse;
		}

	</style>

</head>
<body>

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

{{--Student Infromation--}}
<div class="clear" style="width: 100%;">
	<p class="label text-center row-second">Employee Leave Statement</p>

	@if($allEmployeeList->count()>0)
		<table width="100%" border="1px solid" class="report_card_table text-center" cellpadding="5">
			<thead>
			<tr class="bg-gray-active">
				<th width="10%">#</th>
				<th>Full Name</th>
				<th>Allocated Leave</th>
				<th>Consumed Leave</th>
				<th>Remaining Leave</th>
			</tr>
			</thead>
			<tbody class="text-bold">
			@foreach ($allEmployeeList as $index=>$employee)
				{{--leave counting--}}
				@php
					// initial total leave
					$totalLeave = 0;
					// checking employee leave entitlement
					if($leaveAllocation = $employee->leaveAllocation()){
						// checking leaveAllocation
						if($structure = $leaveAllocation->structure()){
							// checking structure
							if($leaveStructureTypes = $structure->structureLeaveTypes()){
								// find employee total leave count
								$totalLeave = $leaveStructureTypes->sum('leave_days');
							}
						}
					}
					$consumedLeave = $employee->totalLeaveConsumed()->sum('approved_leave_days');
					$availableLeave = ($totalLeave-$consumedLeave);
				@endphp

				<tr class="{{$index%2==0?'bg-gray':'bg-gray-active'}}">
					<td> {{$index+1}} </td>
					<td> {{$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name}} </td>
					<td> {{$totalLeave}} </td>
					<td> {{$consumedLeave}} </td>
					<td> {{$availableLeave}} </td>
				</tr>
			@endforeach
			</tbody>
		</table>
	@else
		<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in">
			<h5 class="text-bold"><i class="fa fa-warning"></i> No records found </h5>
		</div>
	@endif

</div>
</body>
</html>
