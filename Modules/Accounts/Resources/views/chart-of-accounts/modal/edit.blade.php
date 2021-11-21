<form action="{{url('/accounts/chart-of-accounts/update/'.$account->id)}}" method="POST">
    @csrf
    <input type="hidden" name="code_type" value="{{$code_type}}">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title text-bold">
            <i class="fa fa-info-circle"></i> Edit {{$account->account_name}}
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-md-4 control-label required">Name</label>
                    <div class="col-md-8 p-b-15">
                        <input type="text" name="account_name" class="form-control" value="{{$account->account_name}}" required>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label required">Type</label>
                    <div class="col-md-8 p-b-15">
                        <select name="type" class="form-control" id="" required>
                            <option value="group" {{($account->account_type == 'group')?'selected':''}}>Group</option>
                            <option value="ledger" {{($account->account_type == 'ledger')?'selected':''}}>Ledger</option>
                        </select>
                    </div>
                </div>
                @if($code_type=='Manual')
                <div class="form-group">
                    <label class="col-md-4 control-label required">Manual Code</label>
                    <div class="col-md-8 p-b-15">
                        <input type="text" name="manual_account_code" class="form-control" required placeholder="Enter manual code" value="{{$account->manual_account_code}}">            
                    </div>
                </div>
                @endif


            </div>
        </div>
        
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-right">Submit</button>
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
    </div>
</form>

<script>
</script>