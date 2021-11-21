<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Update Accounts Configuration</h4>
</div>
<div class="modal-body">
    <form action="/accounts/accounts-configuration/update/{{$label_name}}" method="POST">
        @csrf
        <input type="hidden" name="code_type" value="{{$code_type}}">
        <div class="row">
            <div class="col-md-12">
                @foreach($accountInfo as $v)
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">{{$v->particular_name}} <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <?php if(!in_array($v->particular, $auto_permission)){ ?>
                        <input type="hidden" name="id[]" value="{{$v->id}}">
                        <select name="particular_id[]" class="form-control select-account">
                            <option value="">Select Accounts</option>
                            @foreach($nature as $na)
                               {{chartOfAccounts($na->id, $accounts, 0, $v,$code_type)}}
                            @endforeach
                            
                        </select>
                        <?php }else{ ?> 
                            <b>Will be auto as per invoice</b>
                        <?php } ?>                       
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-success pull-right">Update</button>
            </div>
        </div>
    </form>
</div>
@php
    function chartOfAccounts($accountId, $accounts, $margin, $particular, $code_type){
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
        if($particular->particular_id==$account->id && $particular->particular_code==$account_code){
            echo '<option value="'.$account->id.'" selected="selected">'.$space.'['.$account_code.'] '.$account->account_name.'</option>';
        }
        else{
            echo '<option value="'.$account->id.'">'.$space.'['.$account_code.'] '.$account->account_name.'</option>';
        }

        $childs = $accounts->where('parent_id', $accountId);
        $margin += 5;
        foreach ($childs as $child){
            chartOfAccounts($child->id, $accounts, $margin, $particular, $code_type);
        }
    }
@endphp

<script>
    $(document).ready(function (){
        $('.select-account').select2();
    });
</script>

