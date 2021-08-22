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
						  @if($studentFeesInvoiceList->count()>0)
					      <div class="table-responsive">
							  <table  id="FeesInvoiceTables" class="table table-striped table-bordered" style="width: 100%">
								  <thead>
								  <tr>
									  <th>Invoice ID</th>
									  <th><a  data-sort="sub_master_code">Fee Name</a></th>
									  <th><a  data-sort="sub_master_code">Fees Type</a></th>
									  <th><a  data-sort="sub_master_alias">Fees</a></th>
									  <th><a  data-sort="sub_master_alias">Discount</a></th>
									  <th><a  data-sort="sub_master_alias">Due Fine</a></th>
									  <th><a  data-sort="sub_master_alias">Total Amount</a></th>
									  <th><a  data-sort="sub_master_alias">Paid Amount</a></th>
									  <th><a  data-sort="sub_master_alias">Status</a></th>
									  {{--<th><a  data-sort="sub_master_alias">Waiver</a></th>--}}
									  <th><a>Action</a></th>
								  </tr>

								  </thead>
								  <tbody>
								  @php

									  $i = 1;$getAttendFine=0; $sumTotalAmount=0; $getDueFine=0; $sumTotalPaymentAmount=0; $sumSubTotal=0;$sumTotalDiscount=0; $sumTotalDueFine=0;
								  @endphp
								  @foreach($studentFeesInvoiceList as $invoice)

									  {{-- attendance and due fine amount--}}

									  @if($invoice->due_fine_amount())
										  @php $due_fine_amount=$invoice->due_fine_amount()->fine_amount; @endphp
									  @else
										  @php $due_fine_amount=0;
										  @endphp
									  @endif
									  @php   $std=$invoice->payer(); @endphp

									  <tr class="gradeX">
											  <td>{{$invoice->id}}</td>
											  @php
												  $fees=$invoice->fees();

											  @endphp
											  <td>{{$fees->fee_name}}</td>
											  <td>
												  <span  class="label label-success">F</span>
											  </td>


											  @php $subtotal=0; $totalAmount=0; $totalDiscount=0; @endphp
											  @foreach($fees->feesItems() as $amount)
												  @php $subtotal += $amount->rate*$amount->qty;@endphp

											  @endforeach



											  {{--Due Fine Amount--}}
											  @php
												  $dueFinePaid=$invoice->invoice_payment_summary();
                                                  $var_dueFine=0;
                                                  if($dueFinePaid){
                                                      $var_dueFine = json_decode($dueFinePaid->summary);
                                                  }
											  @endphp

											  @if($invoice->invoice_status=="1")
												  @if(!empty($var_dueFine))
													  @php $getDueFine=$var_dueFine->due_fine->amount; @endphp
												  @endif
											  @else
												  @if(!empty($invoice->findReduction()))
													  @php $getDueFine=$invoice->findReduction()->due_fine; @endphp
												  @else
													  @php $getDueFine=get_fees_day_amount($invoice->fees()->due_date) @endphp
												  @endif
											  @endif

											  @if($discount = $invoice->fees()->discount())
												  @php $discountPercent=$discount->discount_percent;
                                                    $totalDiscount=(($subtotal*$discountPercent)/100);
                                                    $totalAmount=$subtotal-$totalDiscount
												  @endphp
											  @else
												  @php
													  $totalAmount=$subtotal;
												  @endphp

											  @endif


											  {{--waiver Check --}}
											  @if($invoice->waiver_type=="1")
												  @php $totalWaiver=(($totalAmount*$invoice->waiver_fees)/100);
                                                 $totalAmount=$totalAmount-$totalWaiver
												  @endphp
											  @elseif($invoice->waiver_type=="2")
												  @php $totalWaiver=$invoice->waiver_fees;
                                                 $totalAmount=$totalAmount-$totalWaiver
												  @endphp

											  @endif


											  @if($discount = $invoice->fees()->discount())
												  @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
											  @endif


											  @if(!empty($invoice->waiver_fees))
												  @php $totalDiscount=$totalDiscount+$totalWaiver @endphp
											  @endif




											  {{--<td>{{$subtotal+$getAttendFine+$getDueFine-$totalDiscount}}</td>--}}

											  <td>{{$subtotal}}</td>

										  	@php $sumSubTotal+=$subtotal @endphp

											  <td>{{$totalDiscount}} </td>
										  @php $sumTotalDiscount+=$totalDiscount @endphp

											  <td>{{$getDueFine}}</td>

										  @php $sumTotalDueFine+=$getDueFine @endphp
										  @php $sumTotalAmount+=$subtotal+$getDueFine-$totalDiscount @endphp

											  <td>{{$subtotal+$getDueFine-$totalDiscount}}</td>

											  <td>
												  @if ($invoice->invoice_status=="2")
													  {{$invoice->totalPayment()}}
												  @elseif ($invoice->invoice_status=="1")
{{--													  {{$invoice->totalPayment()+$getDueFine}}--}}
													  @php $sumTotalPaymentAmount+=$subtotal+$getDueFine-$totalDiscount @endphp
													  {{$subtotal+$getDueFine-$totalDiscount}}
												  @endif

											  </td>

											  <td>

												  @if ($invoice->invoice_status=="2")
													  <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-danger">Un-Paid</span>
												  @elseif ($invoice->invoice_status=="1")
													  <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-primary">Paid</span>
												  @elseif ($invoice->invoice_status=="4")
													  <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-danger">Cancel</span>
												  @elseif ($invoice->invoice_status=="3")
													  <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-success">Partial Payment</span>
												  @endif

												  <span id="cancelInvoiceStatus{{$invoice->id}}" class="label label-danger" style="display: none">Cancel</span>
											  </td>
											  {{--<td>--}}


												  {{--@if(!empty($invoice->payer()->student_waiver()) && ($invoice->payer()->student_waiver()->end_date>date('Y-m-d')) && ($invoice->wf_status=='1'))--}}
													  {{--<a  class="label label-primary"   href="/fees/invoice/add-waiver-modal/{{$invoice->id}}/" title="Add Waiver" data-pjax="0" data-target="#globalModal" data-toggle="modal"  class="btn btn-success btn-xs wf_status" >Available</a>--}}
												  {{--@elseif(!empty($invoice->payer()->student_waiver()) && ($invoice->wf_status=='2'))--}}
													  {{--<span class="label  label-default ">Applied</span>--}}
												  {{--@endif</td>--}}
											  <td>
												  @php
													  $getUrl=Request::fullUrl();
                                                      $currentUrl=str_replace('/','+',$getUrl);
                                                      $currentUrl=str_replace('?','>>',$currentUrl);
                                                      $currentUrl=str_replace('%','-',$currentUrl);
												  @endphp

												  {{--                            {{$currentUrl}}--}}
												  {{-- {{urlencode(strtolower(url()->current()))}}
           --}}


												  <a href="/fees/invoice/show/{{$invoice->id}}/{{$currentUrl}}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
												  {{--<a href="" id="batch_edit_{{$invoice->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>--}}
											  </td>
									  </tr>


									  @endforeach

								  <tfoot style="background: limegreen; color: #FFF">
								  <tr>
									  <td colspan="3">Total</td>
									  <td>{{$sumSubTotal}}</td>
									  <td>{{$sumTotalDiscount}}</td>
									  <td>{{$sumTotalDueFine}}</td>
									  <td>{{$sumTotalAmount}}</td>
									  <td colspan="3">{{$sumTotalPaymentAmount}}</td>
								  </tr>
								  </tfoot>


								  </tbody>
							  </table>
							  @else

					         <div class="alert bg-warning text-warning">
					            <i class="fa fa-warning"></i> No record found.        
					         </div>
							  @endif
				 </div>
						  <hr>


	{{--===============================Attendacne Invocie =================--}}

						  <h4> Attendance Invoice </h4>
						  @if($studentAttendanceInvoiceList->count()>0)
							  <div class="table-responsive">

								  <table  id="AtttendanceInvoiceTables" class="table table-striped table-bordered" style="width: 100%">
									  <thead>
									  <tr>
										  <th>Invoice ID</th>
										  <th><a  data-sort="sub_master_code">Fee Name</a></th>
										  <th><a  data-sort="sub_master_code">Fees Type</a></th>
										  <th><a  data-sort="sub_master_alias">Fees</a></th>
										  <th><a  data-sort="sub_master_alias">Discount</a></th>
										  {{--<th><a  data-sort="sub_master_alias">Due Fine</a></th>--}}
										  <th><a  data-sort="sub_master_alias">Total Amount</a></th>
										  <th><a  data-sort="sub_master_alias">Paid Amount</a></th>
										  <th><a  data-sort="sub_master_alias">Status</a></th>
										  <th><a>Action</a></th>
									  </tr>

									  </thead>
									  <tbody>
									  @php

										  $i = 1;$getAttendFine=0; $getDueFine=0; $totalInvoiceAmount=0; $paidAttendanceFine=0;
									  @endphp
									  @foreach($studentAttendanceInvoiceList as $invoice)

										  {{-- attendance and due fine amount--}}

										  @if($invoice->due_fine_amount())
											  @php $due_fine_amount=$invoice->due_fine_amount()->fine_amount; @endphp
										  @else
											  @php $due_fine_amount=0;
                                         $std=$invoice->payer()
											  @endphp
										  @endif

										  <tr class="gradeX">
											  <td>{{$invoice->id}}</td>
											  <td>Attendance Fine</td>
											  <td>
												  <span  class="label label-primary">A</span>
											  </td>
											  <td>{{$invoice->invoice_amount}}</td>
											  @php $totalInvoiceAmount+=$invoice->invoice_amount @endphp
											  {{--<td>0</td>--}}
											  <td>0</td>
											  <td> 													  {{$invoice->invoice_amount}}
											  </td>
											  <td>
												  @if ($invoice->invoice_status=="1")
													  {{$invoice->invoice_amount}}
													  @php $paidAttendanceFine+=$invoice->invoice_amount @endphp
												  @else
													  0
												  @endif
											  </td>
											  <td>

												  @if ($invoice->invoice_status=="2")
													  <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-danger">Un-Paid</span>
												  @elseif ($invoice->invoice_status=="1")
													  <span id="unPainInvoiceStatus{{$invoice->id}}"  class="label label-primary">Paid</span>
												  @elseif ($invoice->invoice_status=="4")
													  <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-danger">Cancel</span>
												  @elseif ($invoice->invoice_status=="3")
													  <span id="unPainInvoiceStatus{{$invoice->id}}" class="label label-success">Partial Payment</span>
												  @endif

												  <span id="cancelInvoiceStatus{{$invoice->id}}" class="label label-danger" style="display: none">Cancel</span>
											  </td>
											  <td>
												  @php
													  $getUrl=Request::fullUrl();
                                                      $currentUrl=str_replace('/','+',$getUrl);
                                                      $currentUrl=str_replace('?','>>',$currentUrl);
                                                      $currentUrl=str_replace('%','-',$currentUrl);
												  @endphp

												  {{--                            {{$currentUrl}}--}}
												  {{-- {{urlencode(strtolower(url()->current()))}}
           --}}


												  <a href="/fees/invoice/show/{{$invoice->id}}/{{$currentUrl}}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
											  </td>


									  @endforeach


									  <tfoot style="background: limegreen; color: #FFF">
									  <tr>
										  <td colspan="3">Total</td>
										  <td>{{$totalInvoiceAmount}}</td>
										  {{--<td>0</td>--}}
										  <td>0</td>
										  <td>{{$totalInvoiceAmount}}</td>
										  <td colspan="3">{{$paidAttendanceFine}}</td>
									  </tr>
									  </tfoot>
									  </tbody>
								  </table>
								  @else

									  <div class="alert bg-warning text-warning">
										  <i class="fa fa-warning"></i> No record found.
									  </div>
								  @endif
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