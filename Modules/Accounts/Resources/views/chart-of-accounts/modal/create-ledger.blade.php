
<form action="{{url('/accounts/chart-of-accounts/store')}}" method="POST">
    @csrf

    <input type="hidden" name="account_type" value="ledger">
    <input type="hidden" name="code_type" value="{{$code_type}}">

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title text-bold">
            <i class="fa fa-info-circle"></i> Create Ledger
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="col-md-4 control-label required">Parent Group</label>
                    <div class="col-md-8 p-b-15">
                        <select name="account" class="form-control" id="select-account" required>
                            @php
                                function chartOfAccounts($accountId, $accounts, $margin, $code_type){
                                    $account = $accounts[$accountId];
                                    if($code_type=='Manual'){
                                        $account_code = $account->manual_account_code;
                                    }else{
                                        $account_code = $account->account_code;
                                    }
                                    $space = '';
                                    for ($i=0; $i<$margin; $i++){
                                        $space .= '&nbsp;';
                                    }
                                    echo '<option value="'.$account->id.'">'.$space.'['.$account_code.'] '.$account->account_name.'</option>';

                                    $childs = $accounts->where('parent_id', $accountId);

                                    foreach ($childs as $child){
                                        $margin += 5;
                                        chartOfAccounts($child->id, $accounts, $margin, $code_type);
                                        $margin -= $margin;
                                    }
                                }
                            @endphp
                            @foreach($nature as $na)
                                {{chartOfAccounts($na->id, $accounts, 0,$code_type)}}
                            @endforeach
                            
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-md-4 control-label required">Ledger Name</label>
                    <div class="col-md-8 p-b-15">
                        <input type="text" name="account_name" class="form-control" required placeholder="Enter ledger name">
                    </div>
                </div>

                @if($code_type=='Manual')
                <div class="form-group">
                    <label class="col-md-4 control-label required">Manual Code</label>
                    <div class="col-md-8 p-b-15">
                        <input type="text" name="manual_account_code" class="form-control" required placeholder="Enter manual code">            
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
    $(document).ready(function (){
        $('#select-account').select2();
    });
</script>