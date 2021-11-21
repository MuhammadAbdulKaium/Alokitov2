@php
    $months = ['', 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    $startMonth = 6;
    $endMonth = 5;
    $i = $startMonth;
@endphp

<form action="{{url('/accounts/budget-allocation/store-budget')}}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title text-bold">
            <i class="fa fa-plus-square"></i> Add Budget
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-2">
                <label for="">Fiscal Year*</label>
            </div>
            <div class="col-sm-4">
                <input type="text" id="year" class="form-control hasDatepicker"
                       name="year" maxlength="10" placeholder="Fiscal Year" aria-required="true"
                       size="10" required>
            </div>
            <div class="col-sm-2">
                <label for="">Posting Ledger</label>
            </div>
            <div class="col-sm-4">
                <select name="ledgerId" id="select-ledger" class="form-control" required>
                    <option value="">-- Select --</option>
                    @foreach($ledgerParents as $ledgerParent)
                        @php
                            $selectedLedgers = $ledgers->where('parent_id', $ledgerParent->id);
                        @endphp
                        <optgroup label="{{$ledgerParent->account_name}}">
                            @foreach($selectedLedgers as $ledger)
                                <option value="{{$ledger->id}}">[{{$ledger->account_code}}] {{$ledger->account_name}}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 20px">
            <div class="col-sm-2">
                <label for="">Budget Amount*</label>
            </div>
            <div class="col-sm-4">
                <input type="number" name="amount" class="form-control budget-field" required>
            </div>
        </div>

        <div class="row" style="margin-top: 10px">
            @while(true)
                @php
                    if ($i == 0){
                        $i = ($i+1) % 12;
                        continue;
                    }
                @endphp

                <div class="col-sm-2" style="margin-top: 20px">
                    <label for="">{{$months[$i]}}</label>
                </div>
                <div class="col-sm-4" style="margin-top: 20px">
                    <input type="number" name="months[{{$i}}]" class="form-control month-field">
                </div>

                @php
                    if ($i == $endMonth){
                        break;
                    }
                    $i = ($i+1) % 13;
                @endphp
            @endwhile
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-right">Submit</button>
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
    </div>
</form>

<script>
    $(document).ready(function (){
        $('#select-ledger').select2();

        $('#year').datepicker({
            format: "yyyy",
            minViewMode: 2,
            autoclose : true
        }).on('hide',function(){
            var date=$('#year').val();
            if(date){
                $('#year').val(date + "-" + (parseInt(date) + parseInt(1)));
            }
        });

        $(document).on('keyup', '.budget-field', function (){
            var amount = $(this).val() / 12;
            $('.month-field').val(amount);
        });

        $(document).on('keyup', '.month-field', function (){
            var totalAmount = 0;

            $('.month-field').each((index, item) => {
                if ($(item).val()){
                    var value = parseInt($(item).val());
                } else {
                    var value = 0;
                }
                totalAmount += value;
            });

            $('.budget-field').val(totalAmount);
        });
    });
</script>