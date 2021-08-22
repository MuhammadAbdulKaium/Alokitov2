
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-plus-square"></i> Add Record
    </h4>
</div>
<form id="stu-master-update" action="/cadetfees/paid/fees/manually/{{$feesDetails->id}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="fees_id" value="{{$feesDetails->id}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="title">Payment Date</label>
                    <input type="date" class="form-control" value="{{$feesDetails->payment_last_date}}" readonly>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="title">Fine</label>
                    <input type="number" class="form-control" value="{{$feesDetails->late_fine}}">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="title">Late Date</label>
                    <input type="number" class="form-control" name="different_days" value="{{$different_days}}">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="title">Late fine</label>
                    <input type="number" class="form-control" name="total_late_fine" value="{{$total_late_fine}}">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="title">Total</label>
                    <input type="number" class="form-control" name="total_amount" value="{{$total_amount}}">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success pull-left">Paid</button> <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
    </div>
</form>

<script type="text/javascript">

</script>
