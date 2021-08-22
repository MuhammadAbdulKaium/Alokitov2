@if($extraPaymentList->count()>0)

    <div class="box-body table-responsive">
        <div >
            <div id="w1" class="grid-view">

                <table id="feesListTable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>#Id</th>
                        <th><a  data-sort="sub_master_alias">Payer Name</a></th>
                        <th><a  data-sort="sub_master_alias">Extra Amount</a></th>
                        <th><a>Action</a></th>
                    </tr>

                    </thead>
                    <tbody>

                    @php

                        $i = 1
                    @endphp
                    @foreach($extraPaymentList as $payment)

                        <tr class="gradeX">
                            <td>{{$i++}}</td>
                    @php $std=$payment->payer(); @endphp
                            <td>{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}</td>
                            <td>{{$payment->extra_amount}}</td>
                            {{--<td> @if ($fees->partial_allowed==1) <span class="btn-orange">Yes<span> @else <span>No</span> @endif</td>--}}
                            {{--<td>{{date('m-d-Y',strtotime($fees->due_date))}}</td>--}}
                            {{--<td>{{$fees->fee_status}}</td>--}}

                            <td>
                                <a href="{{ URL::to('fees/invoice/show', $payment->id) }}" class="btn btn-info btn-xs"><i class="fa fa-eye"></i></a>
                                {{--<a href="" id="batch_edit_{{$invoice->id}}" onclick="modalLoadEdit(this.id)" class="btn btn-primary btn-xs" data-target="#globalModalEdit"  data-toggle="modal" data-placement="top" data-content="update"><i class="fa fa-edit"></i></a>--}}
                                @if($payment->invoice_status=="2")
                                    <a  id="{{$payment->id}}" class="btn btn-danger cancelInvoice btn-xs"  title="" style="font-size:15px;" data-toggle="tooltip" data-placement="bottom" onclick="return confirm('Are you sure, you want to cancel this invoice?');" data-original-title="Cancel Invoice"><i class="fa fa-times-circle-o"></i></a>
                                @endif
                                <a  id="{{$payment->id}}" class="btn btn-danger btn-xs delete_class" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    {{--{{ $invoice->render() }}--}}

                    </tbody>
                </table>
            </div>
            {{--<div class="link" style="float: right"> {{ $feesinvoices->links() }}</div>--}}
            <div class="link" style="float: right">


                {{--                    {!! $feesinvoices->appends(Request::all(['']))->render() !!}--}}
                {!! $extraPaymentList->appends(Request::only([
                    'search'=>'search',
                    'filter'=>'filter',
                    'academic_level'=>'academic_level',
                    'batch'=>'batch',
                    'section'=>'section',
                    ]))->render() !!}

            </div>

            @else
                <div class="row">
                    <div class="col-md-12">
                        <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                            <h5><i class="fa fa-warning"></i></i> No result found. </h5>
                        </div>
                    </div>

                </div>

@endif