@extends('reports::layouts.report-layout')
<!-- page content -->
@section('page-content')
	<!-- grading scale -->
	<div class="col-md-12">
		<h4><strong>Academics</strong></h4>
		<hr/>
		<ul class="nav nav-pills">
			<li class="active"><a data-toggle="tab" href="#my_statistics">Statistics</a></li>
			<li><a data-toggle="tab" href="#my_reports">Reports</a></li>
		</ul>
		<hr/>
		<div class="tab-content">
			<!-- statistics section -->
			<div id="my_statistics" class="tab-pane fade in active">
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<div class="row">
							<div class="col-md-12">
								<!-- Institute Information -->
								<label>Institute Information</label>
								<table class="table table-bordered text-center">
									<tbody>
									<tr>
										<td><strong>Class: </strong>{{$academicInfo->batch}}</td>
										<td><strong>Section: </strong>{{$academicInfo->section}}</td>
										<td><strong>Subjects: </strong> {{$academicInfo->subject}}</td>
										<td><strong>Student: </strong> {{$academicInfo->student}}</td>
									</tr>
									<tr>
										<td><strong>Teacher: </strong>{{$academicInfo->teacher}}</td>
										<td><strong>Admin Staff: </strong>{{$academicInfo->staff}}</td>
										<td><strong>Class to Student ratio: </strong> {{alokito_gcd_ratio($academicInfo->batch, $academicInfo->student)}}</td>
										<td><strong>Teacher to Student ratio: </strong> {{alokito_gcd_ratio($academicInfo->teacher, $academicInfo->student)}}</td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>

						<div class="row">
							<div class="col-md-8">
								<!-- subject chart -->
								<label>Subject # vs class-section</label>
								<div class="chart">
									<canvas id="subjectChart"></canvas>
								</div>
							</div>
							<div class="col-md-4">
								<!-- gender chart -->
								<label>Student # Gender</label>
								<div class="chart">
									<canvas id="genderChart"></canvas>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<!-- report section -->
			<div id="my_reports" class="tab-pane fade in">
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						{{--teacher section --}}
						<div>
							<h4>Academic Reports</h4>
						</div>
						<table class="table table-striped table-bordered">
							<tbody>
							<tr>
								<td>Class, Section, Repeater, Transfer</td>
								<td width="15%" class="text-center">
									<a href="#"><i class="fa fa-file-text"></i></a>
									<a href="{{url('/reports/academics/batch-section-repeater-and-transfer')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">
										<i class="fa fa-download"></i>
									</a>
								</td>
							</tr>
							<tr>
								<td>Class, Section, Dropout, Promotion</td>
								<td width="15%" class="text-center">
									<a href="#"><i class="fa fa-file-text"></i></a>
									<a href="{{url('/reports/academics/batch-section-dropout-and-promotion')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">
										<i class="fa fa-download"></i>
									</a>
								</td>
							</tr>
							</tbody>
						</table>
						{{--teacher section --}}
						<div>
							<h4>Student Reports</h4>
						</div>
						<table class="table table-striped table-bordered">
							<tbody>
							{{--<tr>--}}
								{{--<td>Student Login Details Report</td>--}}
								{{--<td width="15%" class="text-center">--}}
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="#"><i class="fa fa-download"></i></a>--}}
								{{--</td>--}}
							{{--</tr>--}}
							{{--<tr>--}}
								{{--<td>Incomplete Contact Details</td>--}}
								{{--<td width="15%" class="text-center">--}}
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="#"><i class="fa fa-download"></i></a>--}}
								{{--</td>--}}
							{{--</tr>--}}
							<tr>
								<td>Student Class Section Report</td>
								<td width="15%" class="text-center">
									<a><i class="fa fa-file-text"></i></a>
									<a href="/reports/academics/class/section/student/" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-download"></i></a>
								</td>
							</tr>

							<tr>
								<td>Student Class Subject Report</td>
								<td width="15%" class="text-center">
									<a><i class="fa fa-file-text"></i></a>
									<a href="/reports/academics/student/class/subject/list" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-download"></i></a>
								</td>
							</tr>

							<tr>
								<td>Class Subject Student Report</td>
								<td width="15%" class="text-center">
									<a><i class="fa fa-file-text"></i></a>
									<a href="/reports/academics/class/subject/student/list" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-download"></i></a>
								</td>
							</tr>

							<tr>
								<td>Genders Report</td>
								<td width="15%" class="text-center">
									<a class="progress-a" data-url="gender"><i class="fa fa-file-text"></i></a>
									<a href="/reports/academics/class/section/average/gender" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-download"></i></a>
								</td>
							</tr>
							<tr>
								<td>Religion Report</td>
								<td width="15%" class="text-center">
									<a class="progress-a" data-url="religion"><i class="fa fa-file-text"></i></a>
									<a href="/reports/academics/class/section/average/religion" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-download"></i></a>
								</td>
							</tr>
							<tr>
								<td>Report Card</td>
								<td width="15%" class="text-center">
									<a href="#"><i class="fa fa-file-text"></i></a>
									<a href="/academics/manage/assessments/report-card/download/" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-download"></i></a>
								</td>
							</tr>
							<tr>
								<td>Contact Report</td>
								<td width="15%" class="text-center">
									<a class="progress-a" data-url="contact"><i class="fa fa-file-text"></i></a>
									<a href="/reports/academics/class/section/average/contact" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-download"></i></a>
								</td>
							</tr>
							{{--<tr>--}}
								{{--<td>Activity Report</td>--}}
								{{--<td width="15%" class="text-center">--}}
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="#"><i class="fa fa-download"></i></a>--}}
								{{--</td>--}}
							{{--</tr>--}}
							<tr>
								<td>Birthday Report</td>
								<td width="15%" class="text-center">
									<a class="progress-a" data-url="birthday" ><i class="fa fa-file-text"></i></a>
									<a href="/reports/academics/class/section/average/birthday" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-download"></i></a>
								</td>
							</tr>
							{{--<tr>--}}
								{{--<td>Alumni Report</td>--}}
								{{--<td width="15%" class="text-center">--}}
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="#"><i class="fa fa-download"></i></a>--}}
								{{--</td>--}}
							{{--</tr>--}}
							<tr>
								<td>Event Report</td>
								<td width="15%" class="text-center">
									<a href="#"><i class="fa fa-file-text"></i></a>
									<a href="/reports/event/report" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
										<i class="fa fa-download"></i>
									</a>
								</td>
							</tr>
							</tbody>
						</table>
						 {{--teacher section --}}
						{{--<div>--}}
							{{--<h4>Teacher Reports</h4>--}}
						{{--</div>--}}
						{{--<table class="table table-striped table-bordered">--}}
							{{--<tbody>--}}
							{{--<tr>--}}
								{{--<td>Contact Report</td>--}}
								{{--<td width="15%" class="text-center">--}}
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="#"><i class="fa fa-download"></i></a>--}}
								{{--</td>--}}
							{{--</tr>--}}
							{{--<tr>--}}
								{{--<td>Subjects Allocation Report</td>--}}
								{{--<td width="15%" class="text-center">--}}
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="#"><i class="fa fa-download"></i></a>--}}
								{{--</td>--}}
							{{--</tr>--}}
							{{--</tbody>--}}
						{{--</table>--}}
						<!-- parents section -->
						{{--<div>--}}
							{{--<h4>Parent Reports</h4>--}}
						{{--</div>--}}
						{{--<table class="table table-striped table-bordered">--}}
							{{--<tbody>--}}
							{{--<tr>--}}
								{{--<td>Parent Login Details Report</td>--}}
								{{--<td width="15%" class="text-center">--}}
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="#"><i class="fa fa-download"></i></a>--}}
								{{--</td>--}}
							{{--</tr>--}}
							{{--<tr>--}}
								{{--<td>Parent Info</td>--}}
								{{--<td width="15%" class="text-center">--}}
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="#"><i class="fa fa-download"></i></a>--}}
								{{--</td>--}}
							{{--</tr>--}}
							{{--<tr>--}}
								{{--<td>Siblings Report</td>--}}
								{{--<td width="15%" class="text-center">--}}
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="#"><i class="fa fa-download"></i></a>--}}
								{{--</td>--}}
							{{--</tr>--}}
							{{--</tbody>--}}
						{{--</table>--}}
					</div>
				</div>
			</div>
		</div>
	</div>



@endsection

@section('page-script')

	// gender chart
	var genderCtx = document.getElementById("genderChart").getContext('2d');
	var genderChart = new Chart(genderCtx, {
	type: 'pie',
	data: {
	labels: ["Male", "Female"],
	datasets: [{
	backgroundColor: ["#F45B5B", "#F7A35C"],
	data: [{{$male}}, {{$female}}]
	}]
	}
	});

	// subject chart
	var labels = @php echo $batchSubjectInfo->label @endphp;
	// subjectChart
	var subjectCtx = document.getElementById("subjectChart").getContext('2d');
	var subjectChart = new Chart(subjectCtx, {
	type: 'bar',
	data: {
	labels: labels,
	datasets: [{
	label: 'Core',
	data: {{$batchSubjectInfo->core_data}},
	backgroundColor: "#2B908F"
	}, {
	label: 'Elective',
	data: {{$batchSubjectInfo->elective_data}},
	backgroundColor: "#F7A35C"
	}]
	}
	});


	{{--// progress bar--}}
	{{--$('.progress-a').click(function () {--}}
		{{--// get currentTime--}}
		{{--var currentTime = $.now()--}}
		{{--// add currentTime with the href--}}
		{{--var my_href = "/reports/academics/student"--}}
		{{--var data_url = $(this).attr('data-url');--}}
		{{--$(this).attr('href', my_href+'/'+data_url+'/'+currentTime);--}}

		{{--// start waitingDialog--}}
		{{--waitingDialog.show("Downloading...");--}}

		{{--// setInterval--}}
		{{--var id =  window.setInterval(function() {--}}
			{{--// get cookie_val--}}
			{{--var cookie_val =  $.cookie("downloadToken");--}}
			{{--// checking--}}
			{{--if(cookie_val == currentTime){--}}
				{{--// removeCookie--}}
				{{--$.removeCookie('downloadToken',null,  { expires: -1, path: '/'});--}}
				{{--console.log("removed" +cookie_val);--}}
				{{--// hide waitingDialog--}}
				{{--waitingDialog.hide();--}}
				{{--// replace href--}}
				{{--$(this).removeAttr('href');--}}

				{{--// stop interval--}}
				{{--stop_interval();--}}
			{{--}--}}
		{{--}, 1000);--}}

		{{--// stop interval function--}}
		{{--function stop_interval(){--}}
			{{--clearInterval(id);--}}
		{{--}--}}
	{{--});--}}
@endsection