@extends('reports::layouts.report-layout')
<!-- page content -->
@section('page-content')
	<!-- grading scale -->
	<div class="col-md-12">
		<h4><strong>Employee</strong></h4>
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
								<label>Employee Information</label>
								<table class="table table-bordered text-center">
									<tbody>
									<tr>
										<td><strong>Teacher: </strong></td>
										<td><strong>Admin Staff: </strong></td>
										<td><strong>Class to Student ratio: </strong> </td>
										<td><strong>Teacher to Student ratio: </strong></td>
									</tr>
									</tbody>
								</table>
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
							<h4>Leave Reports</h4>
						</div>
						<table class="table table-striped table-bordered">
							<tbody>
							<tr>
								<td>Leave Report</td>
								<td width="15%" class="text-center">
									<a href="#"><i class="fa fa-file-text"></i></a>
									<a href="/employee/manage/leave/report" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
										<i class="fa fa-download"></i>
									</a>
								</td>
							</tr>

							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection