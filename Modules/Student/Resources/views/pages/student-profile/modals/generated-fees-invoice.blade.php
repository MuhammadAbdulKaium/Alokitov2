 <div class="modal-body">
     <h2>#Fees Generate Invoice</h2>
     <div class="col-md-6">
         <table class="table table-bordered table-striped">
             <tr>
                 <th>Name</th>
                 <td>{{$personalInfo->first_name}} {{$personalInfo->first_name}}</td>
             </tr>
             <tr>
                 <th>Student ID</th>
                 <td>{{$personalInfo->email}}</td>
             </tr>
             <tr>
                 <th>Roll</th>
                 <td>{{$personalInfo->gr_no}}</td>
             </tr>
             <tr>
                 <th>Level</th>
                 <td>{{$personalInfo->level()->level_name}}</td>
             </tr>
             <tr>
                 <th>Class</th>
                 <td>{{$personalInfo->batch()->batch_name}}</td>
             </tr>
             <tr>
                 <th>Section</th>
                 <td>{{$personalInfo->section()->section_name}}</td>
             </tr>
         </table>
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
 </div>
