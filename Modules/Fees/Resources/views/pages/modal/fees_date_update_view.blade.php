<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title" id="gsmTitle">Fees Due Date Update</h4>
</div>

{{--@php--}}
    {{--$fees=$invoiceInfo->fees();--}}

{{--@endphp--}}

    <form  id="feesDueDateUpdate" action="/fees/due_date/update/" method="post"  class="form-horizontal margin-none" style="margin-top: 20px;" >
        <input type="hidden" name="_token" value="{{csrf_token()}}">
      <input type="hidden" name="fees_id" value="{{$feesProfile->id}}">
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Fees Name</label>
            <div class="col-md-6"><a href="{{ URL::to('fees/invoice/add', $feesProfile->id) }}">{{$feesProfile->fee_name}}</a></div>
        </div>

        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Fees Type:</label>
            <div class="col-md-6">
              {{$feesProfile->fees_type()->fee_type_name}}
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Fees Description:</label>
            <div class="col-md-6">
                {{$feesProfile->description}}
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Partial Allow:</label>
            <div class="col-md-6">
               @if($feesProfile->partial_allowed==0)
                   No
               @elseif($feesProfile->partial_allowed==0)
                    Yes
                   @endif
            </div>
        </div>
        <div class="form-group">
            <label class="col-md-5 control-label" for="firstname">Fees Due Date:</label>
            <div class="col-md-3">
                <input name="due_date" required @if(!empty($feesProfile->due_date)) value="{{date('m/d/Y',strtotime($feesProfile->due_date))}}" @endif id="dueDate" class="form-control" placeholder="Paid On" type="text">
            </div>
        </div>

        <div class="form-group">
            <div class="col-md-12 col-md-offset-4">
                <button type="submit" class="btn btn-primary" id="UpdateDate">Update</button>
            </div>
        </div>


    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" id="apymentCloseButton" data-dismiss="modal">Close</button>
    </div>
</form>

<script>

    $(document).ready(function(){
            $('#dueDate').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd/mm/yy"});
    })


</script>