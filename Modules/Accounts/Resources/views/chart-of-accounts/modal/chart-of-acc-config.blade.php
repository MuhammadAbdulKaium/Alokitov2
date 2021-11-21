<form action="{{url('/accounts/chart-of-accounts-config-update')}}" method="POST">
    @csrf
    <input type="hidden" name="id" value="{{(!empty($coaConfig))?$coaConfig->id:0}}">

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title text-bold">
            <i class="fa fa-info-circle"></i> Chart of Accounts config
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <label for="">Code Type : </label>
                <input type="radio" id="Auto" value="Auto" name="code_type" <?php if($code_type=='Auto') echo 'checked'; ?>>
                <label for="Auto">Auto</label>
                <input type="radio" id="Manual" value="Manual" name="code_type" <?php if($code_type=='Manual') echo 'checked'; ?>>
                <label for="Manual">Manual</label>
            </div>            
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-right">Update</button>
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
    </div>
</form>

