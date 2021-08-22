<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<style type="text/css">
		.label {font-size: 15px; background-color: #3C8DBC; color: #FFffff; font-weight: 700;}
		.text-center {text-align: center;}

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
			font-size: 12px;
		}

		table {
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
		}

		td, th {
			border: 1px solid #dddddd;
			text-align: left;
			padding: 8px;
		}

		/*tr:nth-child(even) {*/
		/*background-color: #dddddd;*/
		/*}*/
	</style>
</head>
<body>
<div id="inst" class="text-center" style="width: 100%;">
	<div id="inst-photo">
		@if($instituteInfo->logo)
			<img src="{{public_path().'/assets/users/images/'.$instituteInfo->logo}}"  style="width:80px;height:80px">
		@endif
	</div>
	<div id="inst-info">
		<b style="font-size: 30px">{{$instituteInfo->institute_name}}</b><br/>{{'Address: '.$instituteInfo->address1}}<br/>{{'Phone: '.$instituteInfo->phone. ', E-mail: '.$instituteInfo->email.', Website: '.$instituteInfo->website}}
	</div>
</div>

{{--Event List Information--}}
<div style="width: 100%; clear: both">
	{{--<hr>--}}
	<h1 class="text-center" style="font-size: 18px; margin: 20px; border: 1px solid black; border-radius: 5px">Event List</h1>
	@if(count($allEventList)>0)
		<p class="text-center" style="font-size: 15px">
			Showing result From <b>{{date('d-M-Y', strtotime($startDate))}}</b> to <b>{{date('d-M-Y', strtotime($endDate))}}</b>
		</p>

		{{--date, month finding--}}
		@php
			// from_date details
			$fromYear = date('Y',strtotime($startDate));
			$fromMonth = date('m',strtotime($startDate));
			$fromDate = date('d',strtotime($startDate));
			// to_date details
			$toYear = date('Y',strtotime($endDate));
			$toMonth = date('m',strtotime($endDate));
			$toDate = date('d',strtotime($endDate));
		@endphp

		{{--date, month and year checking--}}
		@if($fromYear==$toYear AND $fromMonth==$toMonth)
			{{--event list for same year and same month--}}

			{{--input dates are in a month--}}
			@for($i=$fromDate; $i<=$toDate; $i++)
				{{--date formatting--}}
				@php $toDayDateTime = date('Y-m-'.$i.' 00:00:00', strtotime($startDate)); @endphp
				{{--filtering eventList--}}
				@php $eventList = eventDateSorter($toDayDateTime, $allEventList); @endphp
				{{--event list table--}}
				@if($eventList->count()>0)
					<h5>Event List: {{$i.'-'.date('M-Y', strtotime($startDate))}}</h5>
					@include('communication::pages.event.include.event-list')
				@endif
			@endfor
		@elseif($fromYear==$toYear AND $fromMonth<$toMonth)
			{{--event list for same year and different month--}}

			{{--month looping--}}
			@for($m=$fromMonth; $m<=$toMonth; $m++)
				{{--current month date range finding--}}
				@php $monthFirstDate = date('01', strtotime($fromYear.'-'.$m.'-01')); @endphp
				@php $monthLastDate = date('t', strtotime($fromYear.'-'.$m.'-01')); @endphp
				{{--date range reset--}}
				@php
					if($fromMonth==$m){$monthFirstDate = $fromDate;}
					if($toMonth==$m){$monthLastDate = $toDate;}
				@endphp

				{{--current month date looping--}}
				@for($d=$monthFirstDate; $d<=$monthLastDate; $d++)
					{{--today's dtate formatting--}}
					@php $toDayDateTime =  $fromYear."-".$m."-".$d.' 00:00:00' @endphp
					{{--event list finding with today's date--}}
					@php $eventList = eventDateSorter($toDayDateTime, $allEventList); @endphp
					{{--eventlist table--}}
					@if($eventList->count()>0)
						<h4>Event List: {{date('d-M-Y', strtotime($toDayDateTime))}}</h4>
						@include('communication::pages.event.include.event-list')
					@endif
				@endfor
			@endfor
		@elseif($fromYear<$toYear)
			{{--event list for different year--}}

			{{--year looping--}}
			@for($y=$fromYear; $y<=$toYear; $y++)
				@php $yearMonths = 12; @endphp
				{{--month looping--}}
				@for($m=1; $m<=$yearMonths; $m++)
					{{--current month date range finding--}}
					@php $monthFirstDate = date('01', strtotime($y.'-'.$m.'-01')); @endphp
					@php $monthLastDate = date('t', strtotime($y.'-'.$m.'-01')); @endphp
					{{--date, month range reset--}}
					@php
						if($toYear==$y){$yearMonths = $toMonth;}
						if($toYear==$y AND $toMonth==$m){$monthLastDate = $toDate;}
						if($fromYear==$y AND $fromMonth==$m){$monthFirstDate = $fromDate;}
					@endphp
					{{--current month date looping--}}
					@for($d=$monthFirstDate; $d<=$monthLastDate; $d++)
						{{--today's dtate formatting--}}
						@php $toDayDateTime =  $y."-".$m."-".$d.' 00:00:00' @endphp
						{{--event list finding with today's date--}}
						@php $eventList = eventDateSorter($toDayDateTime, $allEventList); @endphp
						{{--eventlist table--}}
						@if($eventList->count()>0)
							<h4>Event List: {{date('d-M-Y', strtotime($toDayDateTime))}}</h4>
							@include('communication::pages.event.include.event-list')
						@endif
					@endfor
				@endfor
			@endfor
		@else
			{{--Incorrect Date format--}}
			<div class="row text-center">
				<h4>Incorrect Date format</h4>
			</div>
		@endif
	@else
		{{--No records found--}}
		<div class="row text-center">
			<h4>No records found</h4>
		</div>
	@endif
</div>
</body>
</html>
