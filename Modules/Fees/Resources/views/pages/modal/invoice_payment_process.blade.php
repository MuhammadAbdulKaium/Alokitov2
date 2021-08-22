<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title" id="gsmTitle">Payment Transaction Details</h4>
</div>
<style>
    label.col-md-5.control-label {
        font-size: 14px;
    }
</style>
{{----}}

<div class="container">
<div class="col-md-12">

    <form  id="InvoicePaymentForm" action="/fees/invoice/payment/student/process/store" method="post"  class="form-horizontal margin-none" style="margin-top: 20px;">
        <div class="col-md-5">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" id="partial_allowed" name="partial_allowed" value="">
        <input type="hidden" id="student_id" name="student_id" value="{{$student_id}}">
        <input type="hidden" id="invoices" name="invoices" value="{{json_encode($invoiceArrayList)}}">
        {{--<input type="hidden" name="total_extra_amount"  @if(!empty($stdExtraAmount)) value="{{$stdExtraAmount}}" @else value="0" @endif>--}}
        <div class="separator bottom"></div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Due Amount</label>
            <div class="col-md-6">

                    <span style="display: block; width: 100%; height: 34px; padding: 6px 12px; font-size: 14px; color: #555555; background-color: #eee;">{{$totalAmount}}</span>
                    <input type="hidden" class="due_amount" name="due_amount" value="{{$totalAmount}}">


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
                            <option value="{{$paymentMethod->id}}">{{$paymentMethod->method_name}}</option>
                    @endforeach

                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Transaction Id/Cheque No.</label>
            <div class="col-md-6">
                <input name="transaction_id"   id="transaction_id"  class="form-control" placeholder="Transaction Id/Cheque No." type="text">                                <p id="transaction_id_error" class="has-error help-block" style="display: none;">Please enter transaction id/cheque no.</p>
                <p id="correctTransIdConfirm" class="text-warning hidden" style="font-size:12px;">Please ensure that correct transaction ID is entered. If any entry is incorrect then auto update of payment status will stop working.</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Payment Date</label>
            <div class="col-md-6">
                <input name="payment_date" required   id="PaymentDate" class="form-control" placeholder="Paid On" type="text">                                <p id="paid_on_error" class="has-error help-block" style="display: none;">Please enter paid on date.</p>
                <p id="late_charges_error" class="has-error help-block" style="display: none;">Due date is passed away so user must pay all remaining payment(+ late charges) in a single transaction.</p>
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label"  for="firstname">Paid By</label>
            <div class="col-md-6">
                <select required name="paid_by" id="paid_by" class="form-control">
                    <option  value="student">Student</option>
                    <option  value="parents">Parent</option>
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

    {{--<div class="col-md-4 col-md-offset-2" style="padding:20px"> <button id="updateButton" class="btn btn-primary" type="submit" data-payable-amount="3509">Add</button>--}}
    {{--</div>--}}

        <div class="col-md-4 full-right">

            <div class="form-horizontal">
                <div class="panel panel-default">
                    <div class="panel-body">Student Extra Amount Panel</div>
                </div>

                <div class="form-group">
                    <label class="col-md-5 control-label" for="firstname">Extra Amount</label>
                    <div class="col-md-6">
                        <input name="total_extra_amount"  readonly class="form-control total_extra_amount" value="{{$stdExtraAmount}}" type="number">
                        {{--value="{{$stdExtraAmount}}"--}}
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-5 control-label" for="use_advance_amount">Use Advance Amount</label>
                    <div class="col-md-6">
                        <input name="use_advance_amount" type="checkbox" value="1">
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

    //payment date
    $('#PaymentDate').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});

//    // request for invoice Payment from
//    $('form#InvoicePaymentForm').on('submit', function (e) {
//        e.preventDefault();
//
//        var partial_allow=$('#partial_allowed').val();
//        var fees_total_amount=parseInt($('.due_amount').val());
//        var paymentamount=parseInt($("#payment_amount").val());
//        if(partial_allow==1) {
//
//                // ajax request
//                invoice_payment_ajax_req();
//        }
//        else {
//            if (paymentamount >=fees_total_amount) {
//                //ajax request
//                invoice_payment_ajax_req();
//            }
//            else {
//                $("#Payment_amount_message").show();
//            }
//        }
//
//    });

    {{--//function invoice payment ajax req--}}
    {{--function invoice_payment_ajax_req(){--}}
        {{--@if(!empty($paymentInfo))--}}
        {{--var url= '/fees/invoice/payment/update';--}}
        {{--@else--}}
        {{--var url= '/fees/invoice/payment/student/process/store';--}}
        {{--@endif--}}

{{--//       ajax request code--}}
        {{--$.ajax({--}}

            {{--url: url,--}}
            {{--type: 'POST',--}}
            {{--cache: false,--}}
            {{--data: $('form#InvoicePaymentForm').serialize(),--}}
            {{--datatype: 'json/application',--}}

            {{--beforeSend: function() {--}}
{{--//                 alert($('form#InvoicePaymentForm').serialize());--}}
            {{--},--}}

            {{--success:function(data){--}}
{{--//                alert("Invoice Payment Added SuccessFully");--}}
                 {{--window.location.href="";--}}

            {{--},--}}

            {{--error:function(data){--}}
                {{--alert('error');--}}
            {{--}--}}
        {{--});--}}
    {{--}--}}

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

