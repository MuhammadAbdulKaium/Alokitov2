@if(!empty($searchInvoice))
    @php $feesinvoices=$allFeesInvoices; $getAttendFine=0; $getDueFine=0;   @endphp
@endif

@if($feesinvoices->count()>0)
    <div class="box-header">
        <div class="pull-right" style="margin-bottom: 10px;">
        {{--<button type="button" id="get_invoice_pdf"  data-key="pdf" class="btn btn-info btn-sm download-report"><i class="fa fa-file-excel-o"></i> PDF</button>--}}
        <button type="button" id="get_invoice_excel" data-key="xlxs"  class="btn btn-info btn-sm download-report"><i class="fa fa-file-excel-o"></i> Excel</button>
        </div>
            <div id="p0">
            <div id="w1" class="grid-view">

                <table id="feesListTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Invoice Id</th>
                        <th><a  data-sort="sub_master_code">Fee Name</a></th>
                        <th><a  data-sort="sub_master_code">Fees Type</a></th>
                        <th><a  data-sort="sub_master_alias">Payer Name</a></th>
                        <th><a  data-sort="sub_master_alias">Fees</a></th>
                        <th><a  data-sort="sub_master_alias">Discount</a></th>
                        <th><a  data-sort="sub_master_alias">Due Fine</a></th>
                        <th><a  data-sort="sub_master_alias">Total Amount</a></th>
                        <th><a  data-sort="sub_master_alias">Paid Amount</a></th>
                        <th><a  data-sort="sub_master_alias">Status</a></th>
                        <th><a  data-sort="sub_master_alias">Waiver</a></th>
                        <th><a>Action</a></th>
                    </tr>

                    </thead>
                    <tbody>

                    @php

                        $i = 1
                    @endphp
                    @foreach($feesinvoices as $invoice)

                        @php $std=$invoice->payer(); @endphp
                        {{-- attendance and due fine amount--}}

                        @if($invoice->due_fine_amount())
                            @php $due_fine_amount=$invoice->due_fine_amount()->fine_amount; @endphp
                        @else
                            @php $due_fine_amount=0;
                            @endphp
                        @endif

                        <tr class="gradeX">
                            @if(!empty($invoice->fees()))
                                <td>{{$invoice->id}}</td>
                                @php
                                    $fees=$invoice->fees();

                                @endphp
                                <td>{{$fees->fee_name}}</td>
                                <td>
                                    <span  class="label label-success">F</span>
                                </td>


                                <td><a href="/student/profile/personal/{{$invoice->payer_id}}">@if(!empty($std)) {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}} @endif</a></td>


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
                                <td>{{$totalDiscount}} </td>
                                <td>{{$getDueFine}}</td>
                                <td>{{$subtotal+$getDueFine-$totalDiscount}}</td>

                                <td>
                                    @if ($invoice->invoice_status=="2")
                                        {{$invoice->totalPayment()}}
                                    @elseif ($invoice->invoice_status=="1")
                                        {{$invoice->totalPayment()+$getDueFine}}
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


                                    @if(!empty($invoice->payer()->student_waiver()) && ($invoice->payer()->student_waiver()->end_date>date('Y-m-d')) && ($invoice->wf_status=='1'))
                                        @if($invoice->invoice_status=="2")
                                            <a  class="label label-primary"   href="/fees/invoice/add-waiver-modal/{{$invoice->id}}/" title="Add Waiver" data-pjax="0" data-target="#globalModal" data-toggle="modal"  class="btn btn-success btn-xs wf_status" >Available</a>
                                        @endif
                                    @elseif(!empty($invoice->payer()->student_waiver()) && ($invoice->wf_status=='2'))
                                        <span class="label  label-default ">Applied</span>
                                    @endif</td>
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
                                    @if($invoice->invoice_status=="2")
                                        <a  id="{{$invoice->id}}" class="btn btn-danger cancelInvoice btn-xs"  title="" style="font-size:15px;" data-toggle="tooltip" data-placement="bottom"  data-original-title="Cancel Invoice"><i class="fa fa-times-circle-o"></i></a>
                                        <a  id="{{$invoice->id}}" class="btn btn-danger btn-xs delete_class"  data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    @endif
                                </td>

                            @else

                                <td>{{$invoice->id}}</td>
                                <td>Attendance Fine</td>
                                <td>
                                    <span  class="label label-primary">A</span>
                                </td>
                                <td><a href="/student/profile/personal/{{$invoice->payer_id}}">@if(!empty($std)) {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}} @endif</a></td>
                                <td>{{$invoice->invoice_amount}}</td>
                                <td></td>
                                <td></td>
                                <td>       @if ($invoice->invoice_status=="1")
                                        {{$invoice->invoice_amount}}

                                    @endif</td>
                                <td>
                                    @if ($invoice->invoice_status=="1")
                                        {{$invoice->invoice_amount}}

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
                                    @if($invoice->invoice_status=="2")
                                        <a  id="{{$invoice->id}}" class="btn btn-danger cancelInvoice btn-xs"  title="" style="font-size:15px;" data-toggle="tooltip" data-placement="bottom"  data-original-title="Cancel Invoice"><i class="fa fa-times-circle-o"></i></a>
                                        <a  id="{{$invoice->id}}" class="btn btn-danger btn-xs delete_class"  data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    @endif
                                </td>

                            @endif

                        </tr>
                    @endforeach
                    {{--{{ $invoice->render() }}--}}

                    </tbody>
                </table>
            </div>
            {{--<div class="link" style="float: right"> {{ $feesinvoices->links() }}</div>--}}
            <div class="link" style="float: right">


                {{--                    {!! $feesinvoices->appends(Request::all(['']))->render() !!}--}}
                {!! $feesinvoices->appends(Request::only([
                    'search'=>'search',
                    'filter'=>'filter',
                    'academic_year'=>'academic_year',
                    'academic_level'=>'academic_level',
                    'batch'=>'batch',
                    'section'=>'section',
                    'start_date'=>'search_start_date',
                    'end_date'=>'search_end_date',
                    'invoice_status'=>'invoice_status',
                    ]))->render() !!}

            </div>

            @else
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                </div>
            @endif

        </div><!-- /.box-body -->


        <script>

            $(".download-report").click(function(){

                var report_type = $(this).attr('data-key');
//                alert(report_type);
                // dynamic html form
                $('<form id="get_invoice_report_form" action="/reports/invoice/export" method="post" style="display:none;"></form>')
                    .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                    .append('<input type="hidden" name="academic_year" value="'+$('#academic_year').val()+'"/>')
                    .append('<input type="hidden" name="academic_year_name" value="'+$("#academic_year :selected").html()+'"/>')
                    .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                    .append('<input type="hidden" name="academic_level_name" value="'+$("#academic_level :selected").html()+'"/>')
                    .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                    .append('<input type="hidden" name="batch_name" value="'+$("#batch :selected").html()+'"/>')
                    .append('<input type="hidden" name="section" value="'+$('#section').val()+'"/>')
                    .append('<input type="hidden" name="section_name" value="'+$("#section :selected").html()+'"/>')
                    .append('<input type="hidden" name="search_start_date" value="'+$('#search_start_date').val()+'"/>')
                    .append('<input type="hidden" name="search_end_date" value="'+$('#search_end_date').val()+'"/>')
                    .append('<input type="hidden" name="invoice_status" value="'+$('#invoice_status').val()+'"/>')
                    .append('<input type="hidden" name="report_type" value="'+report_type+'"/>')
                    // append to body and submit the form
                    .appendTo('body').submit();
                // remove form from the body
                $('#get_invoice_report_form').remove();

            });





        </script>