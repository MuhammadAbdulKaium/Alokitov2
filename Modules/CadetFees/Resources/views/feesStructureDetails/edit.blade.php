<form action="/cadetfees/fees/structure/update/{{$feesStructure->id}}" method="POST">
    <input type="hidden" name="_token" value="<?php echo e(csrf_token()); ?>">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title text-bold">
            <i class="fa fa-info-circle"></i> Fees Structure Update
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="academic_level">Structure Name</label>
                    <input type="text" class="form-control" name="structure_name" id="structure_name" required value="{{$feesStructure->structure_name}}">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-right">Submit</button>
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
    </div>
</form>
