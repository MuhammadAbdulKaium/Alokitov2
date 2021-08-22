
@extends('reports::layouts.report-layout')

<!-- page content -->
@section('page-content')
	<!-- grading scale -->
	<div class="col-md-12">
		<h4><strong>Fees</strong></h4>
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
											{{--@php--}}
												{{--$total_paidAmount=0;--}}
												{{--$total_Inprogress=0;--}}
												{{--$total_Discount=0;--}}
												{{--$total_Unpaid=0;--}}
												{{--$total_Cancel=0;--}}
											{{--@endphp--}}
									{{--@foreach($feesInfo as $fees)--}}
										{{--@php--}}
											{{--$total_paidAmount+=$fees['paid_amount'];--}}
                                            {{--$total_Inprogress+=$fees['inprogress'];--}}
                                            {{--$total_Discount+=$fees['totalDiscount'];--}}
                                            {{--$total_Unpaid+=$fees['unpaid'];--}}
                                            {{--$total_Cancel+=$fees['cancel'];--}}

										{{--@endphp--}}
									{{--@endforeach--}}



									{{--<label>Fees Information</label>--}}
									{{--<table class="table table-bordered text-center">--}}
										{{--<tbody>--}}
										{{--<tr>--}}
											{{--<td><strong>Paid: </strong>{{$total_paidAmount}}</td>--}}
											{{--<td><strong>In Progress: </strong>{{$total_Inprogress}} </td>--}}
											{{--<td><strong>Discount: </strong> {{$total_Discount}}</td>--}}
										{{--</tr>--}}
										{{--<tr>--}}
											{{--<td><strong>Tax: </strong></td>--}}
											{{--<td><strong>Un-Paid: </strong>{{$total_Unpaid}}</td>--}}
											{{--<td><strong>Cancel: </strong>{{$total_Cancel}} </td>--}}
										{{--</tr>--}}
										{{--</tbody>--}}
									{{--</table>--}}
								</div>
							</div>

							<div class="row">
								<div class="col-md-12">
									<!-- subject chart -->
									<label>Amount # vs Fees</label>
									<div class="chart">
										<canvas id="feesChart"></canvas>
									</div>
								</div>
								<div class="col-md-12">
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
				<div class="col-md-12">
					<table class="table table-bordered table-striped">
						<thead>
						<tr></tr>
						</thead>
						<tbody>
						<tr>
							<td>Fees Invoice Report</td>
							<td><a href="/reports/fees/student/invoice/" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"> Download</a></td>
						</tr>

						<tr>
							<td>Fees Waiver  and Discount Reports</td>
							<td><a href="/reports/fees/waiver/" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"> Download</a></td>
						</tr>

						<tr>
							<td>Waiver Reports</td>
							<td><a href="/reports/waiver/modal" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"> Download</a></td>
						</tr>

						<tr>
							<td>Fees Due Fine Reports</td>
							<td><a href="/reports/fees/fine/report" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"> Download</a></td>
						</tr>

						<tr>
							<td>Fees Details Report</td>
							<td><a href="/reports/fees/details/report" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"> Download</a></td>
						</tr>
						{{--<tr>--}}
							{{--<td>Class-Section Average Attendance Report</td>--}}
							{{--<td><a href="/reports/attendance/class/section/average"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"> Download</td>--}}
						{{--</tr>--}}
						{{--<tr>--}}
							{{--<td>Student Absent Days Report</td>--}}
							{{--<td><a href="/reports/attendance/student/absent/days/" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"> Download</a></td>--}}
						{{--</tr>--}}
						{{--<tr>--}}
							{{--<td>Attendance Raw Data Report</td>--}}
							{{--<td><a href="#" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"> Download</a></td>--}}
						{{--</tr>--}}
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
{{--	{{dd($feesInfo) }}--}}




@endsection
