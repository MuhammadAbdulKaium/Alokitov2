<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title" id="gsmTitle">Buy SMS Update From</h4>
</div>

<div class="modal-body">

<form action="{{URL::to('/communication/sms/sms_credit/update')}}" method="post" class="form-horizontal">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="sms_credit_id" value="{{$smsCreditProfile->id}}">
    <input type="hidden" name="status" value="{{$smsCreditProfile->status}}">
    <div class="form-group">
        <label class="control-label col-sm-4" for="id">Institution Id :	:</label>
        <div class="col-sm-6">
            <input type="text" name="institution_id" value="{{$smsCreditProfile->institution_id }}" class="form-control" id="email" readonly >
        </div>
    </div>
    <div class="form-group">
        <label class="control-label col-sm-4" for="institution">  Name of the institution :</label>
        <div class="col-sm-6">
            <input type="text" class="form-control" readonly  value="{{$smsCreditProfile->institute()->institute_name}}" id="institution">
        </div>
    </div>


    <div class="form-group">
        <label class="control-label col-sm-4" for="pwd">SMS amount :</label>
        <div class="col-sm-6">
            <input type="number" name="sms_amount" value="{{$smsCreditProfile->sms_amount }}" class="form-control" id="pwd" placeholder="SMS amount">
        </div>
    </div>

    <div class="form-group">
        <label class="control-label col-sm-4" for="pwd">Comment :</label>
        <div class="col-sm-6">
            <textarea class="form-control" name="comment" rows="5" id="comment">{{$smsCreditProfile->comment }}</textarea>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
    </div>
</form>
</div>