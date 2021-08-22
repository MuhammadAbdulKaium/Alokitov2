@extends('layouts.master')

@section('styles')
	<link rel="stylesheet" type="text/css" href="https://www.jqueryscript.net/demo/jQuery-Based-Bootstrap-Popover-Enhancement-Plugin-Bootstrap-Popover-X/bootstrap-popover-x.css">
	<link rel="stylesheet" type="text/css" href="{{ Module::asset('fee:css/style.css') }}" />
@endsection

<!-- page content -->
@section('content')

<button class="btn btn-primary hidden-print pull-right" onclick="myFunction();"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
<div class="content-wrapper">
	<section class="content-header">
		<h1><i class="fa fa-plus-square"></i> Online Payment Status</h1>
	</section>

	<section class="content">
		<div class="panel panel-default">
			<div class="panel-body">
				<div>
					<!-- page content div -->
					<link rel="stylesheet" type="text/css" href="http://127.0.0.1:8000/css/datatable.css">
					<link rel="stylesheet" type="text/css" href="http://127.0.0.1:8000/css/datatables/dataTables.bootstrap.css">

					<div class="box-body singleStudentInvoiceList table-responsive">
						@auth
						<div class="container">
							<div class="row justify-content-md-center">
								<div class="col-sm-2 col-md-3 col-lg-3 col-xl-3"></div>
								<div class="col-sm-8 col-md-6 col-lg-6 col-xl-6">
									@if(Session::has('message'))
										<h4 class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</h4>
									@endif
								</div>
								<div class="col-sm-2 col-md-3 col-lg-3 col-xl-3"></div>
							</div>

							<div class="row justify-content-md-center">
								<div class="col-sm-2 col-md-3 col-lg-3 col-xl-3"></div>
								<div class="col-sm-8 col-md-6 col-lg-6 col-xl-6">
									@if(is_array($resultData))
										@foreach($resultData as $key => $value)
											@switch($key)
												@case('epw_txnid')
													<strong>Transaction ID :</strong> {{ $value }}<br />
													@break
											
												@case('mer_txnid')
													<strong>Merchant Transaction ID :</strong> {{ $value }}<br />
													@break
											
												@case('amount')
													<strong>Amount :</strong> {{ $value }} BDT<br />
													@break

												@case('status_code')
													<strong>Status Code :</strong> {{ $value }}<br />
													@break
											
												@case('cardnumber')
													<strong>Card Number :</strong> {{ $value }}<br />
													@break

												@case('payment_type')
													<strong>Payment Type :</strong> {{ $value }}<br />
													@break
											
												@case('date')
													<strong>Payment Date :</strong> {{ $value }}<br />
													@break
											
												@case('date_processed')
													<strong>Payment Process Date :</strong> {{ $value }}<br />
													@break
											
												@case('opt_a')
													{{ $value }}<br />
													@break	

												@case('opt_b')
													{{ $value }}<br />
													@break

												@case('opt_c')
													{{ $value }}<br />
													@break

												@case('opt_d')
													{{ $value }}<br />
													@break

												@default
													
											@endswitch
										@endforeach

										<form id="FormSendtoTrans">
											@foreach(session('store') as $k=>$v)
												<input type="hidden" name="{{ $k }}" value="{{ $v }}" />
											@endforeach
											
											<br />
											<a href="" class="btn btn-primary sendtotrans" target="_blank">Invoice</a>
										</form>

										@foreach(session('store') as $k=>$v)
											@if($k === 'std_id')
												<a href="/student/profile/fees-new/{{ $v }}" class="btn btn-primary sendtoprev">Back Dashboard</a>
											@endif
										@endforeach
									@endif
								</div>
								<div class="col-sm-2 col-md-3 col-lg-3 col-xl-3"></div>
							</div>
						</div>
						@endauth
					</div>
				</div>
			</div>
		</div>
	</section>
</div>
@endsection

@section('scripts')
    @parent
    <script src="{{URL::asset('js/charts/chart.min.js')}}"></script>
    <script src="{{URL::asset('js/tokenInput.js')}}"></script>
    <script src="https://www.jqueryscript.net/demo/jQuery-Based-Bootstrap-Popover-Enhancement-Plugin-Bootstrap-Popover-X/bootstrap-popover-x.js"></script>
    <script>
        $(document).ready(function () {
            $.ajaxSetup(
			{
				headers:
					{
						'X-CSRF-Token': $('input[name="_token"]').val()
					}
			});
				
			$(window).load(function () {
				if( $('.sendtotrans').length ) {
					$.ajax({
						url: '/fee/single-student/payment/store',
						type: 'POST',
						cache: false,
						data: $('form#FormSendtoTrans').serialize(),
						datatype: 'json/application',

						beforeSend: function() {
							{{--alert($('form#Partial_allowForm').serialize());--}}
						},

						success:function(data)
						{
							var paidAmount= $('#invoiceListTable tr .tr_'+data.invoice_id).find(".due_amount_"+data.invoice_id).text();
							$('#invoiceListTable .tr_'+data.invoice_id).find(".due_amount_"+data.invoice_id).html(data.due_amount);
							$('#invoiceListTable .tr_'+data.invoice_id).find(".paid_amount_"+data.invoice_id).html(data.paid_amount);
							$('#globalModal').modal('toggle');
							swal("Success", "Successfully Paid", "success");
							//window.open('{{URL::to('/fee/single-student/invoice/payment')}}/'+data.transaction_id,'_blank');
							$('.sendtotrans').attr('href', '/fee/single-student/invoice/payment/'+data.transaction_id);
						},

						error:function(data)
						{
							{{--alert(JSON.stringify(data));--}}
						}
					});
				}
			});	

			$('.hidden-print').click(function(){
				window.print();
			})
		})
    </script>
    @yield('page-script')
@endsection
