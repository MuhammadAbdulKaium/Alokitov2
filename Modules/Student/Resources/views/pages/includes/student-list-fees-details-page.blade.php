
{{-- @php  print_r($allEnrollments) @endphp --}}
<div class="col-md-12">
    <div class="box box-solid">
        <div class="et">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> View Student List</h3>
            </div>
        </div>
        <div class="card table-responsive">
            <form method="post" id="fees-collection-form">
                @csrf
                @if(isset($searchData))
                    @if($searchData->count()>0)
                        @php $i=1; @endphp
                        <table class="table">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="checkAll"></th>
                                <th>INV</th>
                                <th>Student Number</th>
                                <th>Name</th>
                                <th>Roll</th>
                                <th>Structure</th>
                                <th>Month</th>
                                <th>Fees</th>
                                <th>Delay Fine</th>
                                <th>Fine Type</th>
                                <th>Last Payment Date</th>
                                <th>Paid</th>
                                <th>Previous Due</th>
                                <th>Discount</th>
                                <th>Total Payable</th>
                                <th>Current Pay. Amt</th>
                                <th>Discount</th>
                                <th>Status</th>
                                <th>Pay Date</th>
                                <th>Payment Type</th>
                                <th>Payment Mood</th>
                                <th>Transaction ID</th>
                                <th>Paid By</th>
                                <th>Action</th>
                            </tr>

                            </thead>
                            <tbody>
                            @foreach($searchData as $key=>$data)
                                @php
                                    if(isset($data->total_dues))
                                        {
                                             $previous_due= $feesCollections[$data->std_id]->sum('total_dues');
                                        }
                                @endphp
                                <tr class="@if($data->status==1) bg-success @endif">
                                    <td><input type="checkbox" name="checkbox[]" class="fees-gen-check @if($data->status==1)green @endif" value="{{$data->id}}" @if($data->status==1)disabled @endif></td>
                                    <td>{{$data->inv_id}}</td>
                                    <td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->std_id}}</a></td>
                                    <td><a href="/student/profile/personal/{{$data->std_id}}" target="_blank">{{$data->first_name}} {{$data->last_name}}</a></td>
                                    <td>{{$data->gr_no}}</td>
                                    <td>{{$data->structure_name}}</td>
                                    <td>
                                        @foreach($month_list as $key=>$value)
                                            @if($month_name == $key)
                                                {{$value}}
                                            @endif
                                        @endforeach
                                    </td>
                                        <input type="hidden" name="std_id[]" value="{{$data->std_id}}">
                                        <input type="hidden" id="amount_{{$data->std_id}}"  value="{{$data->total_fees}}" class="form-control">
                                    <td>
                                        <input type="hidden" class="gen-fees"  value="{{$data->fees}}">
                                        <input type="hidden" class="late-fine"  value="{{$data->late_fine}}">
                                        <input type="hidden" class="payment-date"  value="{{$data->payment_last_date}}">
                                        <input type="hidden" class="paid"  value="{{$data->paid_amount}}">
                                        <input type="hidden" class="fine-type"  value="{{$data->fine_type}}">
                                        <input type="hidden" class="discount-value"  value="{{$data->discount}}">
                                        {{$data->fees}}
                                    </td>
                                    <td>
                                        {{$data->late_fine}}
                                    </td>
                                    <td>
                                        {{ 1 == $data->fine_type ? 'Per day' : 'Fixed' }}
                                    </td>
                                    <td>
                                        {{$data->payment_last_date}}
                                    </td>
                                    <td class="total-paid">
                                        {{$data->paid_amount}}
                                    </td>
                                    <td>
                                    @isset($data->total_dues)
                                        {{$previous_due}}
                                            <input type="hidden" class="previous-due" value="{{$previous_due}}">
                                    @endisset
                                    </td>
                                    <td>{{$data->discount}}</td>
                                    <td class="total-payable"></td>
                                    <td><input type="number" class="form-control fees-current-pay" name="current_pay[]" disabled required></td>
                                    <td><input type="number" class="form-control fees-discount" name="discount[]" disabled required></td>
                                    <td>
                                        <input type="hidden" class="status" value="{{$data->status}}">
                                        @foreach($statuses as $key=>$status)
                                            @if($key==$data->status)
                                                {{$status}}
                                            @endif
                                        @endforeach
                                    </td>
                                    <td>{{$data->pay_date}}</td>
                                    <td>@isset($data->payment_type) {{($data->payment_type==1) ? 'Manual':( ($data->payment_type==2)?'Online' : 'N/A')}} @endisset</td>
                                    <td>{{$data->payment_mode}}</td>
                                    <td>{{$data->transaction_id}}</td>
                                    <td>{{$data->paid_by}}</td>
                                    <td>
                                        <button type="submit" class="btn btn-primary payData" @if($data->status==1)disabled @endif>Pay</button>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <button type="submit" class="btn btn-primary payData">Pay</button>
                        @php $i++; @endphp
                    @else
                        <h5 class="text-center"> <b>Sorry!!! No Fees Generated for this class. Please Generate First</b></h5>
                    @endif
                @endif

            </form>
        </div>
    </div>
</div>

<script>
    $(function () {
        $("#checkAll").click(function(){
            $(".fees-gen-check").click();
        });

        $("input[type=checkbox]").click(function () {
            var parent = $(this).parent().parent();
            var amount = parent.find('.fees-current-pay');
            var discount = parent.find('.fees-discount');
            var discountDBValue = parent.find('.discount-value');
            var fees = parent.find('.gen-fees');
            var generate_id = parent.find('.fees-gen-check');
            var paid = parent.find('.paid');
            var fine = parent.find('.late-fine');
            var fineType = parent.find('.fine-type');
            var paymentDate = parent.find('.payment-date');
            var totalPayable = parent.find('.total-payable');
            var previousDue = parent.find('.previous-due');
            var cfDues = parent.find('.cf-dues');
            var utc = new Date().toJSON().slice(0,10).replace(/-/g,'-');

            const date1 = new Date(paymentDate.val());
            const date2 = new Date(utc);
            const diffTime = Math.abs(date2 - date1);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
            function totalCalculatio()
            {
                var discountValue=discount.val()?parseInt(discount.val()):0;
                var previousDueValue=previousDue.val()?parseInt(previousDue.val()):0;
                var previousDiscountValue=discountDBValue.val()?parseInt(discountDBValue.val()):0;
                var total = null;
                if(previousDue.val()>0)
                {
                    if(date1<date2) {
                        if(fineType.val()==1){
                            total=parseInt(fees.val())-parseInt(paid.val()) +(fine.val() * diffDays) - discountValue-previousDiscountValue;
                        }
                        else{
                            total=parseInt(fees.val())-parseInt(paid.val()) - discountValue-previousDiscountValue;
                        }
                    }
                }
                else{
                    if(date1<date2){
                        if(fineType.val()==1)
                        {
                            if(paid.val()>0){
                                total=parseInt(fees.val()) +(fine.val() * diffDays) - discountValue - parseInt(paid.val())-previousDiscountValue;
                            } else {
                                total=parseInt(fees.val()) +(fine.val() * diffDays) - discountValue-previousDiscountValue;
                            }

                        }
                        else
                        {
                            total=parseInt(fees.val()) +(fine.val()) - discountValue - parseInt(paid.val())-previousDiscountValue;
                        }

                    }
                    else{
                        total=parseInt(fees.val())-discountValue + previousDueValue-previousDiscountValue;
                    }
                }

                totalPayable.text(total);
                console.log(total);
            }
            // For uncheck checkbox
            if (!$(this).prop("checked")) {
                amount.attr('disabled', 'disabled');
                discount.attr('disabled', 'disabled');
                discount.val('');
                totalPayable.text('');
            }
            // for checkbox enable
            else {
                amount.removeAttr('disabled');
                discount.removeAttr('disabled');
                discount.val(0);
                $('.fees-discount').keyup(function (){
                    totalCalculatio();
                })
                totalCalculatio();

                // cfDues.text(cfDue);
                // console.log(fees.val());
                // console.log(fine.val());
                // console.log(amount);
                // console.log(cfDue);
                // console.log(utc);
            }
        });

        // $('form#cad_assign_submit_form').on('submit', function (e) {
    });




    // $(function () {
    //     $("#example2").DataTable();
    //     $('#example1').DataTable({
    //         "paging": false,
    //         "lengthChange": false,
    //         "searching": true,
    //         "ordering": false,
    //         "info": false,
    //         "autoWidth": false
    //     });
    //
    //     // paginating
    //     $('.pagination a').on('click', function (e) {
    //         e.preventDefault();
    //         var url = $(this).attr('href').replace('store', 'find');
    //         loadRolePermissionList(url);
    //         // window.history.pushState("", "", url);
    //         // $(this).removeAttr('href');
    //     });
    //     // loadRole-PermissionList
    //     function loadRolePermissionList(url) {
    //         $.ajax({
    //             url: url,
    //             type: 'POST',
    //             cache: false,
    //             beforeSend: function() {
    //                 // show waiting dialog
    //                 waitingDialog.show('Loading...');
    //             },
    //             success:function(data){
    //                 // hide waiting dialog
    //                 waitingDialog.hide();
    //                 // checking
    //                 if(data.status=='success'){
    //                     var std_list_container_row = $('#std_list_container_row');
    //                     std_list_container_row.html('');
    //                     std_list_container_row.append(data.html);
    //                 }else{
    //                     alert(data.msg)
    //                 }
    //             },
    //             error:function(data){
    //                 waitingDialog.hide();
    //                 alert(JSON.stringify(data));
    //             }
    //         });
    //     }
    // });
</script>