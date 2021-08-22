<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title" id="gsmTitle">Payment Transaction Details</h4>
</div>

@php
    $fees=$invoiceInfo->fees();

@endphp

<div class="container">
<div class="col-md-12">

    <form  id="InvoicePaymentForm" class="form-horizontal margin-none" style="margin-top: 20px;" method="post" >
        <div class="col-md-5">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="fees_id" value="{{$invoiceInfo->fees_id}}">
        <input type="hidden" name="invoice_id" value="{{$invoiceInfo->id}}">
        <input type="hidden" name="payer_id" value="{{$invoiceInfo->payer_id}}">
        <input type="hidden" name="payment_id" value="@if(!empty($paymentInfo)) {{$paymentInfo->id}} @endif">
        <input type="hidden" id="partial_allowed" name="partial_allowed" value="{{$fees->partial_allowed}}">
        {{--<input type="hidden" name="total_extra_amount"  @if(!empty($stdExtraAmount)) value="{{$stdExtraAmount}}" @else value="0" @endif>--}}
        <div class="separator bottom"></div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Partial Allow</label>
            <div class="col-md-6">@if($fees->partial_allowed==1) Yes @else No @endif</div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Due Amount</label>
            <div class="col-md-6">
                @php $subtotal=0; $totalPaymentAmount=0; $totalAmount=0; $totalDiscount=0; @endphp
                @foreach($fees->feesItems() as $amount)
                    @php $subtotal += $amount->rate*$amount->qty;@endphp

                @endforeach

                @if($paymentList)
                    @foreach($paymentList as $payment)
                        @php $totalPaymentAmount += $payment->payment_amount;@endphp
                    @endforeach
                @else
                @endif

                @if($discount = $invoiceInfo->fees()->discount())
                    @php $discountPercent=$discount->discount_percent;
                      $totalDiscount=(($subtotal*$discountPercent)/100);

                    @endphp
                @else
                    @php
                        $totalAmount=$subtotal;
                    @endphp

                @endif


                {{--waiver Check --}}
                @if($invoiceInfo->waiver_type=="1")
                    @php $totalWaiver=(($subtotal*$invoiceInfo->waiver_fees)/100);
                    @endphp
                @elseif($invoiceInfo->waiver_type=="2")
                    @php $totalWaiver=$invoiceInfo->waiver_fees;

                    @endphp

                @endif



                {{--/// discount calculate--}}
                @if($discount = $invoiceInfo->fees()->discount())
                    @php $totalDiscount=(($subtotal*$discountPercent)/100);@endphp
                @endif

                {{--calculate waiver--}}
                @if(!empty($invoiceInfo->waiver_fees))
                    @php $totalDiscount=$totalDiscount+$totalWaiver @endphp
                @endif

                @php $attendance_fine=getAttendanceFinePreviousMonth($invoiceInfo->id);
                                          $totalAmount=$subtotal-$totalDiscount;
                @endphp


                @if($totalPaymentAmount>$totalAmount)
                    <span style="display: block; width: 100%; height: 34px; padding: 6px 12px; font-size: 14px; color: #555555; background-color: #eee;">0</span>
                    <input type="hidden" name="due_amount" value="0">
                @else
                    <span style="display: block; width: 100%; height: 34px; padding: 6px 12px; font-size: 14px; color: #555555; background-color: #eee;">{{$totalAmount-$totalPaymentAmount+$day_fine_amount+$attendance_fine}}</span>
                    <input type="hidden" class="due_amount" name="due_amount" value="{{$totalAmount-$totalPaymentAmount+$day_fine_amount+$attendance_fine}}">

                @endif
                <input type="hidden" name="fine_amount" value="{{$day_fine_amount}}">
                <input type="hidden" name="attendance_fine" value="{{$attendance_fine}}">

                <input type="hidden" id="Feessubtotal" name="total_amount" value="{{$totalAmount+$day_fine_amount+$attendance_fine}}">

                {{--@else--}}
                {{--<input type="hidden" id="Feessubtotal" name="total_amount" value="{{$subtotal}}">--}}
                {{--<span style="display: block; width: 100%; height: 34px; padding: 6px 12px; font-size: 14px; color: #555555; background-color: #eee;">--}}

                {{--@if($totalPaymentAmount>$subtotal)--}}
                {{--0--}}
                {{--<input type="hidden" class="due_amount" name="due_amount" value="0">--}}

                {{--@else--}}
                {{--{{$subtotal-$totalPaymentAmount}}--}}
                {{--<input type="hidden" class="due_amount" name="due_amount" value="{{$subtotal-$totalPaymentAmount}}">--}}

                {{--@endif--}}

                {{--</span>--}}


            </div>
        </div>

        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Payment Amount</label>
            <div class="col-md-6">
                <input name="payment_amount"  @if(!empty($paymentInfo->payment_amount)) value="{{$paymentInfo->payment_amount}}" @else value="" @endif id="payment_amount" class="form-control" placeholder="Payment Amount in BDT" step="any" maxlength="13" type="number">                                <p id="paid_amount_error" class="has-error help-block" style="display: none;">Please enter valid payment amount.</p>
                <p id="Payment_amount_message" class="has-error help-block" style="display: none;">Please Pay Your Total Fees or More Amount</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Payment Through</label>
            <div class="col-md-6">
                <select name="payment_method" id="paid_through"  required class="form-control">
                    @foreach($paymentMethodList as $paymentMethod)
                        @if(!empty($paymentInfo->payment_method_id))
                            <option @if($paymentMethod->id==$paymentInfo->payment_method_id) selected="selected" @endif value="{{$paymentMethod->id}}">{{$paymentMethod->method_name}}</option>
                        @else
                            <option value="{{$paymentMethod->id}}">{{$paymentMethod->method_name}}</option>
                        @endif
                    @endforeach

                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Transaction Id/Cheque No.</label>
            <div class="col-md-6">
                <input name="transaction_id"   id="transaction_id"  @if(!empty($paymentInfo->transaction_id)) value="{{$paymentInfo->transaction_id}}" @endif class="form-control" placeholder="Transaction Id/Cheque No." type="text">                                <p id="transaction_id_error" class="has-error help-block" style="display: none;">Please enter transaction id/cheque no.</p>
                <p id="correctTransIdConfirm" class="text-warning hidden" style="font-size:12px;">Please ensure that correct transaction ID is entered. If any entry is incorrect then auto update of payment status will stop working.</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Payment Date</label>
            <div class="col-md-6">
                <input name="payment_date" required @if(!empty($paymentInfo->payment_date)) value="{{$paymentInfo->payment_date}}" @endif id="PaymentDate" class="form-control" placeholder="Paid On" type="text">                                <p id="paid_on_error" class="has-error help-block" style="display: none;">Please enter paid on date.</p>
                <p id="late_charges_error" class="has-error help-block" style="display: none;">Due date is passed away so user must pay all remaining payment(+ late charges) in a single transaction.</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label"  for="firstname">Paid By</label>
            <div class="col-md-6">
                <select required name="paid_by" id="paid_by" class="form-control">
                    <option  @if(!empty($paymentInfo->paid_by)=="student") selected="selected" @endif value="student">Student</option>
                    <option  @if(!empty($paymentInfo->paid_by)=="parents") selected="selected" @endif value="parents">Parent</option>
                </select>
            </div>
        </div>

        @if($feesModule)
            <div class="form-group">
                <label class="col-md-5 control-label" for="firstname">Send Automatic SMS</label>
                <div class="col-md-6">
                    <input name="automatic_sms" type="checkbox" value="1">

                </div>
            </div>
        @endif

        {{--<div class="form-group">--}}
            {{--<label class="col-md-5 control-label" for="firstname"></label>--}}
            {{--<div class="col-md-6">--}}
                {{--<input type="hidden" name="data[FeesPayment][send_notification_emails]" id="send_notification_emails_" value="0"><input type="checkbox" name="data[FeesPayment][send_notification_emails]" class="form-control1" id="send_notification_emails" value="1">--}}
                {{--<label for="send_notification_emails">Send Notification Emails</label>--}}
            {{--</div>--}}
        {{--</div>--}}
  </div>
        <div class="col-md-4 full-right">

    <div class="form-horizontal">
    <div class="panel panel-default">
        <div class="panel-body">Student Extra Amount Panel</div>
    </div>

    <div class="form-group">
        <label class="col-md-5 control-label" for="firstname">Extra Amount</label>
        <div class="col-md-6">
            <input name="total_extra_amount"  class="form-control total_extra_amount" type="text" value="{{$stdExtraAmount}}">
        </div>
    </div>
    <div class="rom" style="display: none">
        My name is Romesh
    </div>

    <div class="form-group">
        <label class="col-md-5 control-label" for="firstname">Add Amount</label>
        <div class="col-md-4">
            <input name="add_extra_amount"  class="form-control get_extra_amount" type="text" value="">
        </div>
        <div class="col-md-3">
        <button type="button" id="add-extra-amount"  class="btn btn-default pull-right">Add</button>
        </div>
    </div>
    </div>
      </div>
    <div class="col-md-6 col-md-offset-2" style="padding:20px"> <button id="updateButton" class="btn btn-primary" data-payable-amount="3509">Add</button>
    </div>

     </form>
</div>

</div>
</div>




<script>

    $('#paid_through').on('change', function() {
        // Does some stuff and logs the event to the console
       var payment_method=$(this).val();
       if(payment_method=="2") {
           $('#transaction_id').attr("required", "true");
       } else {
           $('#transaction_id').removeAttr('required');
       }

    });

    {{--//payment date--}}
    $('#PaymentDate').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});

    // request for invoice Payment from
    $('form#InvoicePaymentForm').on('submit', function (e) {
        e.preventDefault();

        var partial_allow=$('#partial_allowed').val();
        var fees_total_amount=parseInt($('.due_amount').val());
        var paymentamount=parseInt($("#payment_amount").val());
        if(partial_allow==1) {

                // ajax request
                invoice_payment_ajax_req();
        }
        else {
            if (paymentamount >=fees_total_amount) {
                //ajax request
                invoice_payment_ajax_req();
            }
            else {
                $("#Payment_amount_message").show();
            }
        }

    });

    //function invoice payment ajax req
    function invoice_payment_ajax_req(){
        @if(!empty($paymentInfo))
        var url= '/fees/invoice/payment/update';
        @else
        var url= '/fees/invoice/payment/student/store';
        @endif

//       ajax request code
        $.ajax({

            url: url,
            type: 'POST',
            cache: false,
            data: $('form#InvoicePaymentForm').serialize(),
            datatype: 'json/application',

            beforeSend: function() {
//                 alert($('form#InvoicePaymentForm').serialize());
            },

            success:function(data){
//                alert("Invoice Payment Added SuccessFully");
                 window.location.href="";

            },

            error:function(data){
                alert('error');
            }
        });
    }

    $('#add-extra-amount').click(function(){

        var totalExtraAmount=$('.total_extra_amount').val();
        var dueAmount=$('.due_amount').val();
        var addExtraAmount=$('.get_extra_amount').val();
//        alert(dueAmount);
//        alert(addExtraAmount);

        if(addExtraAmount>dueAmount){
            alert("You can only get due amout in your extra amount");
//            alert("Only Add"+dueAmount+"Tk" );
        } else {

        if(totalExtraAmount>=dueAmount){

         alert(addExtraAmount);
            $('#payment_amount').val(addExtraAmount);
        } else {
            alert(dueAmount);
        }
        }
    });





</script>

