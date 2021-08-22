{{--<div class="box box-solid">--}}
    {{--<div class="et">--}}
        {{--<div class="box-header with-border">--}}
            {{--<h3 class="box-title"><i class="fa fa-search"></i> View Student List</h3>--}}
            {{--<div class="box-tools">--}}
                {{--<a class="btn btn-info btn-sm" href="#"><i class="fa fa-file-excel-o"></i> Excel</a>--}}
            {{--</div>--}}
        {{--</div>--}}
    {{--</div>--}}
    {{--<div class="box-body table-responsive">--}}
        {{--<div class="box-header">--}}
        {{--</div>--}}
        {{--<div class="box-body">--}}
            {{--<table id="fptList" class="table table-striped">--}}
                {{--<thead>--}}
                {{--<tr>--}}
                    {{--<th>#</th>--}}
                    {{--<th>Date.</th>--}}
                    {{--<th>Student ID</th>--}}
                    {{--<th>GR No.</th>--}}
                    {{--<th width="40%">Name</th>--}}
                    {{--<th>Payment Type</th>--}}
                    {{--<th>Payment Method</th>--}}
                    {{--<th>Receipt No</th>--}}
                    {{--<th>Bank</th>--}}
                    {{--<th>Check Date</th>--}}
                    {{--<th>Check NO</th>--}}
                    {{--<th>Amount</th>--}}
                {{--</tr>--}}
                {{--</thead>--}}
                {{--<tbody>--}}
                {{--@php $i = 1; @endphp--}}
                {{--@foreach($fpt_list as $payment)--}}
                    {{--<tr>--}}
                        {{--<td>{{$i++}}</td>--}}
                        {{--<td>{{$payment->payment_date}}</td>--}}
                        {{--<td>{{$payment->invoice()->payer_id}}</td>--}}
                        {{--<td></td>--}}
                        {{--@php $std=$payment->invoice()->payer() @endphp--}}
                        {{--<td>{{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}</td>--}}
                        {{--<td></td>--}}
                        {{--<td>{{$payment->payment_method()->method_name}}</td>--}}
                        {{--<td></td>--}}
                        {{--<td></td>--}}
                        {{--<td></td>--}}
                        {{--<td></td>--}}
                        {{--<td class=" sum">{{$payment->payment_amount}}</td>--}}
                    {{--</tr>--}}
                {{--@endforeach--}}
                {{--</tbody>--}}
                {{--<tfoot id="table-footer">--}}
                {{--<tr>--}}
                    {{--<td style="display: none;" rowspan="1" colspan="1"></td>--}}
                    {{--<td></td>--}}
                    {{--<td style="text-align: right" title="This is the sum of all items in this column, not just the ones on this screen." rowspan="1" colspan="1">Total:</td>--}}
                    {{--<td></td>--}}
                    {{--<td rowspan="1" colspan="1"></td>--}}
                    {{--<td rowspan="1" colspan="1"></td>--}}
                    {{--<td rowspan="1" colspan="1"></td>--}}
                    {{--<td colspan="2" rowspan="1"></td>--}}
                {{--</tr>--}}
                {{--</tfoot>--}}
            {{--</table>--}}
            {{--<div class="feesPayment-pagination" style="float: right"> {{$fpt_list->links()}}</div>--}}
        {{--</div>--}}
    {{--</div>--}}
{{--</div>--}}
