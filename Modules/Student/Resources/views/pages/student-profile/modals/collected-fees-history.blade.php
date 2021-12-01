<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Fees Collection History<br>
    </h4>
</div>
<div class="modal-body">
     <div class="container">
         <div class="row">
             <table  id="FeesInvoiceTables" class="table table-striped table-bordered" style="width: 100%">
                 <thead>
                 <tr>
                     <th><a  data-sort="sub_master_code">Invoice ID</a></th>
                     <th><a  data-sort="sub_master_code">Month Name</a></th>
{{--                     <th><a  data-sort="sub_master_code">Structure Name</a></th>--}}
                     <th><a  data-sort="sub_master_alias">Fees Amount</a></th>
                     <th><a  data-sort="sub_master_alias">Fine Amount</a></th>
                     <th><a  data-sort="sub_master_alias">Total Payable</a></th>
                     <th><a  data-sort="sub_master_alias">Paid Amount</a></th>
                     <th><a  data-sort="sub_master_alias">Discount</a></th>
                     <th><a  data-sort="sub_master_alias">Due Amount</a></th>
                     <th><a  data-sort="sub_master_alias">Date of Payment</a></th>
                     <th><a  data-sort="sub_master_alias">Payment Type</a></th>
                     <th><a  data-sort="sub_master_alias">Status</a></th>
                 </tr>
                 </thead>
                 <tbody>
                 @foreach($feeCollections as $feeCollection)
                 <tr>
                     <td>{{$feeCollection->inv_id}}</td>
                     <td>
                         @foreach($month_list as $key=>$month)
                             @if($key==$feeCollection->month_name)
                                 {{$month}}
                             @endif
                         @endforeach
                     </td>
{{--                     <td>{{$feeCollection->structure_name}}</td>--}}
                     <td>{{$feeCollection->fees_amount}}</td>
                     <td>{{$feeCollection->fine_amount}}</td>
                     <td>{{$feeCollection->total_payable}}</td>
                     <td>{{$feeCollection->paid_amount}}</td>
                     <td>{{$feeCollection->discount}}</td>
                     <td>{{$feeCollection->total_dues}}</td>
                     <td>{{$feeCollection->pay_date}}</td>
                     <td>{{$feeCollection->payment_type==1?'Manual':($feeCollection->payment_type==2?'Online':'N/A')}}</td>
                     <td>{{$feeCollection->status==1?'Paid':($feeCollection->status==2?'Partially Paid':'Pending')}}</td>
                 </tr>
                 @endforeach
                 </tbody>
             </table>
             <div class="border"></div>
         </div>

     </div>
 </div>
<div class="modal-footer">
    <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
</div>
