<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title" id="gsmTitle">Payment Transaction Details</h4>
</div>

{{--@php--}}
{{--$fees=$invoiceInfo->fees();--}}

{{--@endphp--}}


<form  id="InvoicePaymentForm"  class="form-horizontal margin-none" style="margin-top: 20px;" >

    @if(!empty($paymentProfile->invoice_id))
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Fees Name</label>
            @if(!empty($paymentProfile->invoice()->fees_id))
                <div class="col-md-6"><a href="{{ URL::to('fees/invoice/add', $paymentProfile->invoice()->fees()->id) }}">{{$paymentProfile->invoice()->fees()->fee_name}}</a></div>
            @else
                Attendance Fine
            @endif
        </div>
    @endif



    @if(!empty($paymentProfile->invoice_id))
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Type</label>
            <div class="col-md-6">
                <p>
                    @if(!empty($paymentProfile->invoice()->fees_id))
                        <span  class="label label-success">F</span>
                    @else
                        <span  class="label label-primary">A</span>
                    @endif
                </p>
            </div>
        </div>
    @endif


    @if(!empty($paymentProfile->invoice_id))
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Invoice ID</label>
            <div class="col-md-6">
                <p><a href="{{ URL::to('fees/invoice/show', $paymentProfile->invoice_id) }}"> {{$paymentProfile->invoice_id}}</a></p>
            </div>
        </div>
    @endif

    <div class="form-group">
        <label class="col-md-5 control-label" for="firstname">Payer Name</label>
        <div class="col-md-6">
            @if(!empty($paymentProfile->invoice_id))
                @php $std=$paymentProfile->invoice()->payer() @endphp
                {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}
            @else

                @php $std=$paymentProfile->getInvoiceIdByPaymentId($paymentProfile->id); @endphp
                {{$std->first_name.' '.$std->middle_name.' '.$std->last_name}}

            @endif
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-5 control-label" for="firstname">Transaction/Cheque ID/</label>
        <div class="col-md-6">
            {{$paymentProfile->transaction_id}}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-5 control-label" for="firstname">Payment Method.</label>
        <div class="col-md-6">
            {{$paymentProfile->payment_method()->method_name}}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-5 control-label" for="firstname">Payment Date</label>
        <div class="col-md-6">
            {{$paymentProfile->payment_date}}
        </div>
    </div>
    <div class="form-group">
        <label class="col-md-5 control-label" for="firstname">Payment Status</label>
        <div class="col-md-6">
            {{$paymentProfile->payment_status}}
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-5 control-label" for="firstname">Paid Amount</label>
        <div class="col-md-6">
            {{$paymentProfile->payment_amount}}
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-5 control-label" for="firstname">Paid By</label>
        <div class="col-md-6">
            {{$paymentProfile->paid_by}}
        </div>
    </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" id="apymentCloseButton" data-dismiss="modal">Close</button>
    </div>
</form>
