@extends('reports::layouts.report-layout')
<!-- page content -->
@section('page-content')
	<!-- grading scale -->
	<div class="col-md-12">
		<h4><strong>Result</strong></h4>
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
										<td><strong>Class: </strong></td>
										<td><strong>Section: </strong> 0</td>
										<td><strong>Subjects: </strong> 0</td>
										<td><strong>Student: </strong> 0</td>
									</tr>
									<tr>
										<td><strong>Teacher: </strong></td>
										<td><strong>Admin Staff: </strong></td>
										<td><strong>Class to Student ratio: </strong> 0:0</td>
										<td><strong>Teacher to Student ratio: </strong> 0:0</td>
									</tr>
									</tbody>
								</table>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6">
								<!-- subject chart -->
								<label>Subject # vs class-section</label>
								<div class="chart">
									<canvas id="subjectChart"></canvas>
								</div>
							</div>
							<div class="col-md-6">
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
						<div>
							<h3>Assessment Reports</h3>
						</div>
						<table class="table table-striped table-bordered">
							<tbody>
							<tr>
								<td style="font-size: 20px; color: green;">Report Card</td>
								<td>
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="/academics/manage/assessments/report-card/download/" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-download"></i></a>--}}

									<a href="/academics/manage/assessments/report-card/download/" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
										<button class="btn btn-success">Download</button>
									</a>
								</td>
							</tr>
							</tbody>
						</table>

						<!-- parents section -->
						{{--<div>--}}
							{{--<h4>Another Reports Section</h4>--}}
						{{--</div>--}}
						{{--<table class="table table-striped table-bordered">--}}
							{{--<tbody>--}}
							{{--<tr>--}}
								{{--<td>Another Report</td>--}}
								{{--<td>--}}
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="#"><i class="fa fa-download"></i></a>--}}
									{{--<a href="#" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-download"></i></a>--}}
								{{--</td>--}}
							{{--</tr>--}}
							{{--</tbody>--}}
						{{--</table>--}}
						{{--<table class="table table-striped table-bordered">--}}
							{{--<tbody>--}}
							{{--<tr>--}}
								{{--<td>Parent Login Details Report</td>--}}
								{{--<td>--}}
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="#"><i class="fa fa-download"></i></a>--}}
								{{--</td>--}}
							{{--</tr>--}}
							{{--<tr>--}}
								{{--<td>Parent Info</td>--}}
								{{--<td>--}}
									{{--<a href="#"><i class="fa fa-file-text"></i></a>--}}
									{{--<a href="#"><i class="fa fa-download"></i></a>--}}
								{{--</td>--}}
							{{--</tr>--}}
							{{--<tr>--}}
								{{--<td>Siblings Report</td>--}}
								{{--<td>--}}
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

			{{--@section('page-script')--}}

				{{--// gender chart--}}
				{{--var genderCtx = document.getElementById("genderChart").getContext('2d');--}}
				{{--var genderChart = new Chart(genderCtx, {--}}
				{{--type: 'pie',--}}
				{{--data: {--}}
				{{--labels: ["Male", "Female"],--}}
				{{--datasets: [{--}}
				{{--backgroundColor: ["#F45B5B", "#F7A35C"],--}}
				{{--data: [50, 50]--}}
				{{--}]--}}
				{{--}--}}
				{{--});--}}

				{{--// subject chart--}}
				{{--var subjectCtx = document.getElementById("subjectChart").getContext('2d');--}}
				{{--var subjectChart = new Chart(subjectCtx, {--}}
				{{--type: 'bar',--}}
				{{--data: {--}}
				{{--labels: ["Class One", "Class Two", "Class Three", "Class Four", "Class Five", "Class Six", "Class Seven","Class Eight","Class Nine","Class Ten"],--}}
				{{--datasets: [{--}}
				{{--label: 'Core',--}}
				{{--data: [3, 5, 5, 5, 5, 5, 5, 4, 5, 4],--}}
				{{--backgroundColor: "#2B908F"--}}
				{{--}, {--}}
				{{--label: 'Elective',--}}
				{{--data: [2, 3, 4, 2, 1, 4, 5, 4, 5, 4],--}}
				{{--backgroundColor: "#F7A35C"--}}
				{{--}]--}}
				{{--}--}}
				{{--});--}}


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
			{{--@endsection--}}