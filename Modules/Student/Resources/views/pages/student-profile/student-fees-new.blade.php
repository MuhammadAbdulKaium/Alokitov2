@extends('student::pages.student-profile.profile-layout')
@section('styles')
	<link rel="stylesheet" href="{{URL::to('css/datatables/dataTables.bootstrap.css')}}">
	<style>
		.table-responsive{

			overflow-x: hidden;
			overflow-y: hidden;
		}
	</style>
@endsection

@section('profile-content')
	<h4> Fees Invoice </h4>
	@if($studentInvoiceList->count()>0)
		<table id="invoiceListTable" class="table table-striped table-bordered" style="margin-top: 20px">
			<thead>
				<tr>
					<th>SL.</th>
					<th>Fee Head</th>
					<th>Sub Head</th>
					<th>Amount</th>
					<th>Waiver</th>
					<th>Paid Amount</th>
					<th>Payable Amount</th>
					<th>Take</th>
					<th>Action</th>
				</tr>
			</thead>

			<tbody>
				@php $totalAmount=0; $totalWaiver=0; $totalPaid=0; $totalPayable=0; $waiver=0; $i=1; $fine=0;$total=0; @endphp
				@foreach($studentInvoiceList as $invoice)
					<tr class="tr_{{$invoice->id}}">
						<td>{{$i++}}</td>
						<td>{{$invoice->feehead()->name}}</td>
						<td>{{$invoice->subhead()->name}}</td>
						<td class="amount">{{$invoice->amount}}</td>
						<td>
							@php $waiverProfile=$invoice->isWaiver($invoice->student_id,$invoice->head_id,$invoice->amount) @endphp
							{{$waiverProfile['waiver']}}
						</td>
						@php $payableAmount=($invoice->amount-$waiverProfile['waiver'])-$invoice->paid_amount @endphp

						<td class="paid_amount_{{$invoice->id}}">{{$invoice->paid_amount}}</td>
						<td class="payableAmount due_amount_{{$invoice->id}}">{{$payableAmount}}</td>
						<td>
							@if($invoice->status=='paid')
								<span class="label label-success">Paid</span>
							@else
								<a  href="/fee/invoice/payment/single/{{$invoice->id}}" class="btn btn-primary" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">Pay</a>
							@endif
						</td>
						<td><a id="{{$invoice->id}}" class="delete_class btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a> </td>
					</tr>

					@php
					$totalAmount+=$invoice->amount;
					$totalWaiver+=$waiverProfile['waiver'];
					$totalPaid+=$invoice->paid_amount;
					$totalPayable+=$payableAmount;
					@endphp
				@endforeach
			</tbody>

			<tfoot>
			<tr>
				<th colspan="3">Total </th>
				<th>{{$totalAmount}}</th>
				<th>{{$totalWaiver}}</th>
				<th>{{$totalPaid}}</th>
				<th>{{$totalPayable}}</th>
			</tr>
			</tfoot>
		</table>
	@else
		<div class="alert bg-warning text-warning">
			<i class="fa fa-warning"></i> No record found.
		</div>
	@endif
@endsection

<!-- page script -->
@section('scripts')
	<script type="text/javascript" src="{{URL::to('js/datatables/dataTables.bootstrap.js')}}"></script>
	<script type = "text/javascript">
		$(document).ready(function () {
			$('#FeesInvoiceTable').DataTable();
			$('#AtttendanceInvoiceTable').DataTable();
				
			// invoice delete ajax request
			$('.delete_class').click(function(e){
				var tr = $(this).closest('tr'),
				del_id = $(this).attr('id');

				swal({
					title: "Are you sure?",
					text: "You want to delete invoice?",
					type: "warning",
					showCancelButton: true,
					confirmButtonColor: '#DD6B55',
					confirmButtonText: 'Yes!',
					cancelButtonText: "No",
					closeOnConfirm: false,
					closeOnCancel: false
				},
				function(isConfirm) {
					if (isConfirm) {
						$.ajax({
							url: "/fee/student/invoice/delete/" + del_id,
							type: 'GET',
							cache: false,
							success: function (result) {
								tr.fadeOut(1000, function () {
									$(this).remove();
								});
								swal("Success!", "Invoice Successfully Deleted", "success");

							}
						});
					} else {
						swal("NO", "Your Fee and Invoice is safe :)", "error");
						e.preventDefault();
					}
				});
			});
		});
	</script>
@endsection