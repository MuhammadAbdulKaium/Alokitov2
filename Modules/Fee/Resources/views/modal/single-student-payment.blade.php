<link rel="stylesheet" type="text/css" href="{{ Module::asset('fee:css/style.css') }}" />
<style>
    .form-group {
        margin:0px !important;
    }
    .control-label {
        padding-top: 0px !important;
    }
</style>

<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-plus-square"></i>Pay Now</h4>
</div>

{{--modal-body--}}
<div class="modal-body ">
    <form class="form-horizontal onlinePayment" id="singleStudentPaymentForm" action="/fee/student/onlinepayment/request" method="POST">
        <input type="hidden" name="invoice_id" value="{{$invoiceProfile->id}}">
        <input type="hidden" name="actual_amount" value="{{$invoiceProfile->amount}}">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="std_id" value="{{$invoiceProfile->student_id}}">
        <input type="hidden" name="sub_id" value="{{$invoiceProfile->sub_head_id}}">
        @php
            $waiverProfile=$invoiceProfile->isWaiver($invoiceProfile->student_id,$invoiceProfile->head_id,$invoiceProfile->amount) ;
           $waiverTypeName=$invoiceProfile->waiverTypeName($waiverProfile['waiver_type']);

            $actualAmount=$invoiceProfile->amount;
            $totalAmount=$invoiceProfile->amount-$invoiceProfile->paid_amount;
            $payableAmount=$totalAmount-$waiverProfile['waiver'];;

        @endphp

        <input type="hidden" name="waiver_amount" value="{{$waiverProfile['waiver']}}">
        <input type="hidden" name="waiver_type" value="{{$waiverProfile['waiver_type']}}">

        <input type="hidden" name="waiver_type" value="{{$waiverProfile['waiver_type']}}">

        {{--<div class="form-group">--}}
            {{--<p class="control-label col-sm-4" for="email">Fee Head:</p>--}}
            {{--<p class="col-sm-4">--}}
               {{--{{$invoiceProfile->feehead()->name}}--}}
            {{--</p>--}}
        {{--</div>--}}

        {{--<div class="form-group">--}}
            {{--<p class="control-label col-sm-4" for="email">Sub  Head:</p>--}}
            {{--<p class="col-sm-4">--}}
                {{--{{$invoiceProfile->subhead()->name}}--}}
            {{--</p>--}}
        {{--</div>--}}

        <div class="form-group">
            <p class="control-label col-sm-4" for="email">Actual Amount:</p>
            <div class="col-sm-4 ">
               <p class="actualAmount">{{$actualAmount}}</p>
            </div>
        </div>

        <div class="form-group">
            <p class="control-label col-sm-4" for="email">Previous Amount:</p>
            <div class="col-sm-4 ">
                <p class="previousAmount">{{$invoiceProfile->paid_amount}}</p>
            </div>
        </div>

        {{--<div class="form-group">--}}
            {{--<p class="control-label col-sm-4" for="email">Fine Amount:</p>--}}
            {{--<div class="col-sm-4 ">--}}
                {{--<p class="fineAmount">0</p>--}}
            {{--</div>--}}
        {{--</div>--}}

        <div class="form-group">
            <p class="control-label col-sm-4" for="email">Total:</p>
            <div class="col-sm-4 ">
                <p class="totalAmount">{{$totalAmount}}</p>
            </div>
        </div>

        @if(!empty($waiverTypeName))
        <div class="form-group">
            <p class="control-label col-sm-4" for="email">Waiver Type:</p>
            <div class="col-sm-4 ">
                <p class="waiverAmount">{{$waiverTypeName}}</p>
            </div>
        </div>
        @endif
        @if(!empty($waiver))
        <div class="form-group">
            <p class="control-label col-sm-4" for="email">Waiver:</p>
            <div class="col-sm-4 ">
                <p class="waiverAmount">{{$waiver}}</p>
            </div>
        </div>
        @endif

        <div class="form-group">
            <p class="control-label col-sm-4" for="email">Payable Amount:</p>
            <div class="col-sm-4 ">
                <p class="payableAmount">{{$payableAmount}}</p>
            </div>
        </div>


        <div class="form-group">
            <p class="control-label col-sm-4" for="email">Paid Amount</p>
            <p class="col-sm-4">
                <input type="text" id="paidAmount"  name="paid_amount" />
            </p>
        </div>

        <div class="form-group">
            <p class="control-label col-sm-4" for="email">Due Amount</p>
            <p class="col-sm-4 dueAmount">
            </p>
            <input type="hidden" name="due_amount" class="dueAmountValue" value="0">
        </div>

        <input type="hidden" name="payable_amount" value="{{$invoiceProfile->amount-$waiverProfile['waiver']}}">

        {{--<div class="form-group">--}}
            {{--<p class="control-label col-sm-4" for="email">Fine :</p>--}}
            {{--<div class="col-sm-4">--}}
                {{--0--}}
            {{--</div>--}}
        {{--</div>--}}

        {{--<div class="form-group">--}}
            {{--<p class="control-label col-sm-4" for="email">Previous Due:</p>--}}
            {{--<p class="col-sm-4">--}}
               {{--00--}}
            {{--</p>--}}
        {{--</div>--}}

        {{--<div class="form-group">--}}
            {{--<p class="control-label col-sm-4" for="email">Total:</p>--}}
            {{--<p class="col-sm-4">--}}
                {{--{{$invoiceProfile->amount}}--}}
            {{--</p>--}}
        {{--</div>--}}

        {{--<div class="form-group">--}}
            {{--<p class="control-label col-sm-4" for="email">Waiver Type:</p>--}}
            {{--<p class="col-sm-4">--}}
              {{--////d--}}
            {{--</p>--}}
        {{--</div>--}}
        {{--<div class="form-group">--}}
            {{--<p class="control-label col-sm-4" for="email">Wiver Amount:</p>--}}
            {{--<p class="col-sm-4">--}}
                {{--{{$invoiceProfile->amount}}--}}
            {{--</p>--}}
        {{--</div>--}}




        {{--<div class="form-group">--}}
            {{--<p class="control-label col-sm-4" for="email">Due:</p>--}}
            {{--<p class="col-sm-4">--}}
                {{--Due--}}
            {{--</p>--}}
        {{--</div>--}}

        <div class="form-inline col-sm-offset-4 ">
            <div class="form-group">
                <p class="col">
                    {{--<a class="btn btn-primary testButton">Submit</a>--}}
                    <button type="button" class="btn btn-primary submitPayment">Submit</button>
                </p>
            </div>

            <div class="form-group">
                <p class="col">
                    {{--<a class="btn btn-primary testButton">Submit</a>--}}
                    <button type="submit" class="btn btn-info onlinePaymentBtn">Online Payment</button>
                    <img class="fee-loader" alt="Loading..." title="Loading..." src="{{ Module::asset('fee:images/loader/fee-loader.gif') }}" />
                </p>
            </div>
        </div>
    </form>
</div>


<script>

    $("#paidAmount").keyup(function(){
        var paidAmount=parseInt($(this).val());
        var payableAmount=parseInt($('p.payableAmount').text());
        var dueAmount=payableAmount-paidAmount;
        $(".dueAmount").empty();
        $(".dueAmountValue").empty();
        $(".dueAmountValue").val(dueAmount);
        $(".dueAmount").append(dueAmount);

    });


    $('.submitPayment').click(function(e) {
//        alert(1);
        e.preventDefault();
        var paidAmount=parseInt($('#paidAmount').val());
        var payableAmount=parseInt($('p.payableAmount').text());
        if(paidAmount>payableAmount){

            swal("Error!", "You cannot apply an amount greater than payable amount", "warning");

        } else {
            // ajax request
            $.ajax({
                url: '/fee/single-student/payment/store',
                type: 'POST',
                cache: false,
                data: $('form#singleStudentPaymentForm').serialize(),
                datatype: 'json/application',

                beforeSend: function() {
                    {{--alert($('form#Partial_allowForm').serialize());--}}
                },

                success:function(data)
                {
//                  alert(data.invoice_id);
//                  console.log(data);
                    var paidAmount= $('#invoiceListTable tr .tr_'+data.invoice_id).find(".due_amount_"+data.invoice_id).text();
//                  alert(paidAmount);
                    $('#invoiceListTable .tr_'+data.invoice_id).find(".due_amount_"+data.invoice_id).html(data.due_amount);
                    $('#invoiceListTable .tr_'+data.invoice_id).find(".paid_amount_"+data.invoice_id).html(data.paid_amount);
                    $('#globalModal').modal('toggle');
                    swal("Success", "Successfully Paid", "success");
                    window.open('{{URL::to('/fee/single-student/invoice/payment')}}/'+data.transaction_id,'_blank');
                },

                error:function(data)
                {
                    {{--alert(JSON.stringify(data));--}}
                }
            });
        }
    });

    $( document ).ready(function() {
        $('.fee-loader').hide();
        $('.onlinePayment').submit(function(e) {
            $('.onlinePaymentBtn').prop("disabled", true);
            $('.fee-loader').fadeIn();
            var paidAmount=parseInt($('#paidAmount').val());
            var payableAmount=parseInt($('p.payableAmount').text());
            if(paidAmount>payableAmount)
            {
                $('.onlinePaymentBtn').prop("disabled", false);
                $('.fee-loader').hide();
                e.preventDefault();
                swal("Error!", "You cannot apply an amount greater than payable amount", "warning");
            }
        });
    });
</script>