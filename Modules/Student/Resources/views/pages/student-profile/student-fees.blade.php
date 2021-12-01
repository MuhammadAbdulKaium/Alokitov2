@extends('student::pages.student-profile.profile-layout')
@section('styles')
<link rel="stylesheet" href="{{URL::to('css/datatables/dataTables.bootstrap.css')}}">
	<style>
		.table-responsive{

			overflow-x: hidden;
			overflow-y: hidden;
		}
		.full-with-modal{
			width: 80%;
		}
	</style>
@endsection

@section('profile-content')
				<h4> Fees Invoice </h4>
		  @if($generatedFees->count()>0)
		  <div class="table-responsive">
			  <table  id="FeesInvoiceTables" class="table table-striped table-bordered" style="width: 100%">
				  <thead>
				  <tr>
					  <th>Invoice ID</th>
					  <th><a  data-sort="sub_master_code">Month Name</a></th>
					  <th><a  data-sort="sub_master_code">Year</a></th>
					  <th><a  data-sort="sub_master_code">Structure Name</a></th>
					  <th><a  data-sort="sub_master_alias">Fees</a></th>
					  <th><a  data-sort="sub_master_alias">Fine</a></th>
					  <th><a  data-sort="sub_master_alias">Fine Type</a></th>
					  <th><a  data-sort="sub_master_alias">Last Date of Payment</a></th>
					  <th><a  data-sort="sub_master_alias">Status</a></th>
					  {{--<th><a  data-sort="sub_master_alias">Waiver</a></th>--}}
					  <th><a>Action</a></th>
				  </tr>
				  </thead>

				  <tbody>
					  @foreach($generatedFees as $invoice)
						  <tr>
							  <td>{{$invoice->inv_id}}</td>
							  <td>
								  @foreach($month_list as $key=>$month)
									  @if($key==$invoice->month_name)
										{{$month}}
									  @endif
								  @endforeach
							  </td>
							  <td>{{$invoice->year}}</td>
							  <td>{{$invoice->structure_name}}</td>
							  <td>{{$invoice->fees}}</td>
							  <td>{{$invoice->late_fine}}</td>
							  <td>{{$invoice->fine_type}}</td>
							  <td>{{$invoice->payment_last_date}}</td>
							  <td>{{$invoice->status==1?'Paid':($invoice->status==2?'Partially Paid':'Pending')}}</td>
							  <td>
								  <a class="btn btn-primary" href="{{url('student/fees/invoice/'.$invoice->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Invoice</a>
							  </td>
						  </tr>
					  @endforeach
				  </tbody>
			  </table>
		  @endif
			  <h4> Fees Collection </h4>
			  @if($feesCollection->count()>0)
				  <table class="table table-bordered">
					  <thead>
					  <tr>
						  <th>Invoice ID</th>
						  <th>Fees Amount</th>
						  <th>Fine Amount</th>
						  <th>Total Payable</th>
						  <th>Paid Amount</th>
						  <th>Discount</th>
						  <th>Paid Date</th>
						  <th>Total Due</th>
						  <th>Payment Type</th>
						  <th>Status</th>
						  <th>Action</th>
					  </tr>
					  </thead>
					  <tbody>
					  @foreach($feesCollection as $collection)
						  <tr>
							  <td>{{$collection->inv_id}}</td>
							  <td>{{$collection->fees_amount}}</td>
							  <td>{{$collection->fine_amount}}</td>
							  <td>{{$collection->total_payable}}</td>
							  <td>{{$collection->paid_amount}}</td>
							  <td>{{$collection->discount}}</td>
							  <td>{{$collection->pay_date}}</td>
							  <td>{{$collection->total_dues}}</td>
							  <td>{{$collection->payment_type==1?'Manual':($collection->payment_type==2?'Online':'N/A')}}</td>
							  <td>{{$collection->status==1?'Paid':($collection->status==2?'Partially Paid':'Pending')}}</td>
							  <td>
								  <a class="btn btn-primary" href="{{url('student/fees/collection/history/'.$collection->std_id.'/'.$collection->fees_generate_id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">History</a>
								  <a class="btn btn-primary" href="{{url('student/fees/collection/invoice/'.$collection->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Invoice</a>
							  </td>
						  </tr>
					  @endforeach
					  </tbody>
				  </table>
			  @endif
	  <!-- global modal -->
		  <div aria-hidden="true" aria-labelledby="esModalLabel" class="modal" id="globalModal" role="dialog" tabindex="-1">
			  <div class="modal-dialog full-with-modal">
				  <div class="modal-content">
					  <div class="modal-body">
						  <div class="loader">
							  <div class="es-spinner">
								  <i class="fa fa-spinner fa-pulse fa-5x fa-fw">
								  </i>
							  </div>
						  </div>
					  </div>
				  </div>
			  </div>
		  </div>

@endsection

<!-- page script -->
@section('scripts')
	<script type="text/javascript" src="{{URL::to('js/datatables/dataTables.bootstrap.js')}}"></script>
	<script type = "text/javascript">
		$(document).ready(function () {
                $('#FeesInvoiceTable').DataTable();
                $('#AtttendanceInvoiceTable').DataTable();
		});
	</script>
@endsection