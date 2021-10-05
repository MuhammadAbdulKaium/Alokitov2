<form action="/cadetfees/fees/head/update/{{$feesHead->id}}" method="POST">
    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title text-bold">
            <i class="fa fa-info-circle"></i> Fees Head Update
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="academic_level">Head Name</label>
                    <input type="text" class="form-control" name="head_name" id="head_name" required value="{{$feesHead->fees_head}}">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-right">Submit</button>
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
    </div>
</form>
