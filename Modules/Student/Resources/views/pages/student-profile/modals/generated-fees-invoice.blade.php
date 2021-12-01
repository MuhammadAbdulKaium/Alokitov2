<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                aria-hidden="true">×</span></button>
</div>
<div class="modal-body">
     <style>
         .clearfix {
             overflow: auto;
         }
         .clearfix::after {
             content: "";
             clear: both;
             display: table;
         }
         .container{
             width: 90%;
             margin: 0 auto;
         }
         img{
             width: 100%;
         }
         .header{
             border-bottom: 1px solid #f1f1f1;
             padding: 10px 0;
         }
         .logo{
             width: 16%;
             float: left;
         }
         .headline{
             float: left;
             padding: 0 20px;
         }
         .info{
             margin: 15px 0;
         }
         .patient-info{
             margin: 15px 0;
             font-size: 17px;
         }
         .patient-info span{
             margin-right: 15px;
         }
         .content{
             margin: 50px 0;
         }
         table#FeesInvoiceTables a {
             color: #fff;
         }
         table {
             border-collapse: collapse;
             width: 100%;
             border: 1px solid #ddd;
         }

         th, td {
             text-align: left;
             padding: 8px;
             border: 1px solid #ddd;
         }

         th {
             background-color: #4CAF50;
             color: white;
         }

         .footer{
             text-align: center;
             border-top: 1px solid #f1f1f1;
             padding: 20px 0;
         }
         img.stamp {
             width: 221px;
             position: absolute;
             top: 9px;
             left: 82px;
         }
         span.invoice-type {
             padding: 8px;
             border-radius: 5px;
             background: #4caf50;
             color: #fff;
         }
         .border {
             border: 1px dotted;
             overflow: hidden;
         }
         h3.invoice-number {
             background: #4caf50;
             padding: 10px;
             text-transform: uppercase;
         }
     </style>
     <div class="container">
         <a class="btn btn-primary" href="{{url('/student/fees/invoice/pdf/'.$generatedFees->id)}}">Print</a>
         <div class="row">
             <div class="row prescription-rows">
                 <div class="col-sm-12" style="text-align: center">
                     <h3 class="invoice-number">Invoice: {{$generatedFees->inv_id}} </h3>
                 </div>
                 <div class="col-sm-1">
                     <img src="{{ asset('/assets/users/images/'.$institute->logo) }}" alt="" style="width: 100%" width="30">
                 </div>
                 <div class="col-sm-6">
                     <h4><b>{{ $institute->institute_name }}</b></h4>
                     <h5>{{ $institute->address2 }}</h5>
                 </div>
                 <div class="col-sm-5" style="text-align: right">
                     <span class="invoice-type">Student Copy</span>
                     <h5>{{$personalInfo->first_name}} {{$personalInfo->last_name}} ({{$personalInfo->email}} ) Roll: {{$personalInfo->gr_no}}</h5>
                     <h5><strong>Level:</strong> {{$personalInfo->level()->level_name}} <strong>Class: </strong>{{$personalInfo->batch()->batch_name}} <strong>Section: </strong>{{$personalInfo->section()->section_name}}</h5>
                 </div>
             </div>
             <table  id="FeesInvoiceTables" class="table table-striped table-bordered" style="width: 100%">
                 <thead>
                 <tr>
                     <th><a  data-sort="sub_master_code">Invoice ID</a></th>
                     <th><a  data-sort="sub_master_code">Month Name</a></th>
                     <th><a  data-sort="sub_master_code">Year</a></th>
                     <th><a  data-sort="sub_master_code">Structure Name</a></th>
                     <th><a  data-sort="sub_master_alias">Fees</a></th>
                     <th><a  data-sort="sub_master_alias">Fine</a></th>
                     <th><a  data-sort="sub_master_alias">Fine Type</a></th>
                     <th><a  data-sort="sub_master_alias">Last Date of Payment</a></th>
                     <th><a  data-sort="sub_master_alias">Status</a></th>
                 </tr>
                 </thead>
                 <tbody>
                 <tr>
                     <td>{{$generatedFees->inv_id}}</td>
                     <td>
                         @foreach($month_list as $key=>$month)
                             @if($key==$generatedFees->month_name)
                                 {{$month}}
                             @endif
                         @endforeach
                     </td>
                     <td>{{$generatedFees->year}}</td>
                     <td>{{$generatedFees->structure_name}}</td>
                     <td>{{$generatedFees->fees}}</td>
                     <td>{{$generatedFees->late_fine}}</td>
                     <td>{{$generatedFees->fine_type==1?'Daily':'Fixed'}}</td>
                     <td>{{$generatedFees->payment_last_date}}</td>
                     <td>{{$generatedFees->status==1?'Paid':($generatedFees->status==2?'Partially Paid':'Pending')}}</td>
                 </tr>
                 </tbody>
             </table>
             <div class="col-md-6">
                 @if($generatedFees->status==1)
                     <img src="{{ asset('/assets/stamp/paid-stamp.png') }}" class="stamp">
                 @elseif($generatedFees->status==2)
                     <img src="{{ asset('/assets/stamp/partially-paid-stamp.png') }}" class="stamp">
                 @else
                     <img src="{{ asset('/assets/stamp/pending-rectangle.png') }}" class="stamp">
                 @endif
             </div>
             <div class="col-md-6">
                 <table class="table table-bordered">
                     <thead>
                     <tr>
                         <th>Head</th>
                         <th>Amount</th>
                     </tr>
                     </thead>
                     <tbody>
                     @foreach($feesHeadDetails as $key=>$head)
                         <tr>
                             <td>
                                 @isset($key)
                                     @foreach($feesHeads as $feesHead)
                                         @if(($feesHead->id == $key))
                                             {{$feesHead->fees_head}}
                                         @endif
                                     @endforeach
                                 @endisset()
                             </td>
                             <td>
                                 {{$head}}
                             </td>
                         </tr>

                     @endforeach
                     </tbody>

                 </table>
             </div>
             <div class="border"></div>
         </div>
         <div class="row">
             <div class="row prescription-rows">
                 <div class="col-sm-12" style="text-align: center">
                     <h3 class="invoice-number">Invoice: {{$generatedFees->inv_id}} </h3>
                 </div>
                 <div class="col-sm-1">
                     <img src="{{ asset('/assets/users/images/'.$institute->logo) }}" alt="" style="width: 100%" width="30">
                 </div>
                 <div class="col-sm-6">
                     <h4><b>{{ $institute->institute_name }}</b></h4>
                     <h5>{{ $institute->address2 }}</h5>
                 </div>
                 <div class="col-sm-5" style="text-align: right">
                     <span class="invoice-type">Student Copy</span>
                     <h5>{{$personalInfo->first_name}} {{$personalInfo->last_name}} ({{$personalInfo->email}} ) Roll: {{$personalInfo->gr_no}}</h5>
                     <h5><strong>Level:</strong> {{$personalInfo->level()->level_name}} <strong>Class: </strong>{{$personalInfo->batch()->batch_name}} <strong>Section: </strong>{{$personalInfo->section()->section_name}}</h5>
                 </div>
             </div>
             <table  id="FeesInvoiceTables" class="table table-striped table-bordered" style="width: 100%">
                 <thead>
                 <tr>
                     <th><a  data-sort="sub_master_code">Invoice ID</a></th>
                     <th><a  data-sort="sub_master_code">Month Name</a></th>
                     <th><a  data-sort="sub_master_code">Year</a></th>
                     <th><a  data-sort="sub_master_code">Structure Name</a></th>
                     <th><a  data-sort="sub_master_alias">Fees</a></th>
                     <th><a  data-sort="sub_master_alias">Fine</a></th>
                     <th><a  data-sort="sub_master_alias">Fine Type</a></th>
                     <th><a  data-sort="sub_master_alias">Last Date of Payment</a></th>
                     <th><a  data-sort="sub_master_alias">Status</a></th>
                 </tr>
                 </thead>
                 <tbody>
                 <tr>
                     <td>{{$generatedFees->inv_id}}</td>
                     <td>
                         @foreach($month_list as $key=>$month)
                             @if($key==$generatedFees->month_name)
                                 {{$month}}
                             @endif
                         @endforeach
                     </td>
                     <td>{{$generatedFees->year}}</td>
                     <td>{{$generatedFees->structure_name}}</td>
                     <td>{{$generatedFees->fees}}</td>
                     <td>{{$generatedFees->late_fine}}</td>
                     <td>{{$generatedFees->fine_type==1?'Daily':'Fixed'}}</td>
                     <td>{{$generatedFees->payment_last_date}}</td>
                     <td>{{$generatedFees->status==1?'Paid':($generatedFees->status==2?'Partially Paid':'Pending')}}</td>
                 </tr>
                 </tbody>
             </table>
             <div class="col-md-6">
                 @if($generatedFees->status==1)
                     <img src="{{ asset('/assets/stamp/paid-stamp.png') }}" class="stamp">
                 @elseif($generatedFees->status==2)
                     <img src="{{ asset('/assets/stamp/partially-paid-stamp.png') }}" class="stamp">
                 @else
                     <img src="{{ asset('/assets/stamp/pending-rectangle.png') }}" class="stamp">
                 @endif
             </div>
             <div class="col-md-6">
                 <table class="table table-bordered">
                     <thead>
                     <tr>
                         <th>Head</th>
                         <th>Amount</th>
                     </tr>
                     </thead>
                     <tbody>
                     @foreach($feesHeadDetails as $key=>$head)
                         <tr>
                             <td>
                                 @isset($key)
                                     @foreach($feesHeads as $feesHead)
                                         @if(($feesHead->id == $key))
                                             {{$feesHead->fees_head}}
                                         @endif
                                     @endforeach
                                 @endisset()
                             </td>
                             <td>
                                 {{$head}}
                             </td>
                         </tr>

                     @endforeach
                     </tbody>

                 </table>
             </div>
             <div class="border"></div>
         </div>
         <div class="row">
             <div class="row prescription-rows">
                 <div class="col-sm-12" style="text-align: center">
                     <h3 class="invoice-number">Invoice: {{$generatedFees->inv_id}} </h3>
                 </div>
                 <div class="col-sm-1">
                     <img src="{{ asset('/assets/users/images/'.$institute->logo) }}" alt="" style="width: 100%" width="30">
                 </div>
                 <div class="col-sm-6">
                     <h4><b>{{ $institute->institute_name }}</b></h4>
                     <h5>{{ $institute->address2 }}</h5>
                 </div>
                 <div class="col-sm-5" style="text-align: right">
                     <span class="invoice-type">Student Copy</span>
                     <h5>{{$personalInfo->first_name}} {{$personalInfo->last_name}} ({{$personalInfo->email}} ) Roll: {{$personalInfo->gr_no}}</h5>
                     <h5><strong>Level:</strong> {{$personalInfo->level()->level_name}} <strong>Class: </strong>{{$personalInfo->batch()->batch_name}} <strong>Section: </strong>{{$personalInfo->section()->section_name}}</h5>
                 </div>
             </div>
             <table  id="FeesInvoiceTables" class="table table-striped table-bordered" style="width: 100%">
                 <thead>
                 <tr>
                     <th><a  data-sort="sub_master_code">Invoice ID</a></th>
                     <th><a  data-sort="sub_master_code">Month Name</a></th>
                     <th><a  data-sort="sub_master_code">Year</a></th>
                     <th><a  data-sort="sub_master_code">Structure Name</a></th>
                     <th><a  data-sort="sub_master_alias">Fees</a></th>
                     <th><a  data-sort="sub_master_alias">Fine</a></th>
                     <th><a  data-sort="sub_master_alias">Fine Type</a></th>
                     <th><a  data-sort="sub_master_alias">Last Date of Payment</a></th>
                     <th><a  data-sort="sub_master_alias">Status</a></th>
                 </tr>
                 </thead>
                 <tbody>
                 <tr>
                     <td>{{$generatedFees->inv_id}}</td>
                     <td>
                         @foreach($month_list as $key=>$month)
                             @if($key==$generatedFees->month_name)
                                 {{$month}}
                             @endif
                         @endforeach
                     </td>
                     <td>{{$generatedFees->year}}</td>
                     <td>{{$generatedFees->structure_name}}</td>
                     <td>{{$generatedFees->fees}}</td>
                     <td>{{$generatedFees->late_fine}}</td>
                     <td>{{$generatedFees->fine_type==1?'Daily':'Fixed'}}</td>
                     <td>{{$generatedFees->payment_last_date}}</td>
                     <td>{{$generatedFees->status==1?'Paid':($generatedFees->status==2?'Partially Paid':'Pending')}}</td>
                 </tr>
                 </tbody>
             </table>
             <div class="col-md-6">
                 @if($generatedFees->status==1)
                     <img src="{{ asset('/assets/stamp/paid-stamp.png') }}" class="stamp">
                 @elseif($generatedFees->status==2)
                     <img src="{{ asset('/assets/stamp/partially-paid-stamp.png') }}" class="stamp">
                 @else
                     <img src="{{ asset('/assets/stamp/pending-rectangle.png') }}" class="stamp">
                 @endif
             </div>
             <div class="col-md-6">
                 <table class="table table-bordered">
                     <thead>
                     <tr>
                         <th>Head</th>
                         <th>Amount</th>
                     </tr>
                     </thead>
                     <tbody>
                     @foreach($feesHeadDetails as $key=>$head)
                         <tr>
                             <td>
                                 @isset($key)
                                     @foreach($feesHeads as $feesHead)
                                         @if(($feesHead->id == $key))
                                             {{$feesHead->fees_head}}
                                         @endif
                                     @endforeach
                                 @endisset()
                             </td>
                             <td>
                                 {{$head}}
                             </td>
                         </tr>

                     @endforeach
                     </tbody>
                 </table>
             </div>
         </div>

     </div>
 </div>
