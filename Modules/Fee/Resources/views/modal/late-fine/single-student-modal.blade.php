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
    <form class="form-horizontal" action="{{URL::to('/fee/late-fine/single-student/payment/store')}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        <input type="hidden" name="std_id" value="{{$invoiceProfile->student_id}}">
        <input type="hidden" name="invoice_id" value="{{$invoiceProfile->id}}">
        <div class="form-group">
            <p class="control-label col-sm-4" for="email">Total Fine:</p>
            <p class="col-sm-4">
                {{$lateFineProfile->fine_amount}}
            </p>
        </div>
        {{--total fine amount --}}

        <div class="form-group">
            <p class="control-label col-sm-4" for="email">Previous Paid:</p>
            <p class="col-sm-4">
                {{$totalfeefinePaid}}
            </p>
        </div>


        <div class="form-group">
            <p class="control-label col-sm-4" for="email">Due:</p>
            <div class="col-sm-4 ">
                <p class="waiverAmount">{{$lateFineProfile->fine_amount-$totalfeefinePaid}}</p>
            </div>
        </div>

        {{--<div class="form-group">--}}
            {{--<p class="control-label col-sm-4" for="email">Total Payable:</p>--}}
            {{--<div class="col-sm-4 ">--}}
                {{--<p class="payableAmount"></p>--}}
            {{--</div>--}}
        {{--</div>--}}


        <div class="form-group">
            <p class="control-label col-sm-4" for="email">Paid Amount</p>
            <p class="col-sm-4">
                <input type="text" id="paidAmount"  name="paid_amount" />
            </p>
        </div>


        <div class="form-group">
            <p class="col-sm-offset-4 col-sm-4">
                <button type="submit" formtarget="_blank" class="btn btn-primary">Submit</button>
            </p>
        </div>
    </form>
</div>


<script>

    $("#paidAmount").keyup(function(){
        var paidAmount=parseInt($(this).val());
        var payableAmount=parseInt($('p.payableAmount').text());
        var dueAmount=payableAmount-paidAmount;
        $(".dueAmount").empty();
        $(".dueAmount").append(dueAmount);

    });

</script>
