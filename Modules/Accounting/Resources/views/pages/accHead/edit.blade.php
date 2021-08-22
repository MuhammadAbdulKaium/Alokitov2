<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 3/28/17
 * Time: 3:27 PM
 */
use Modules\Accounting\Entities\AccCharts;
?>
<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Ledger and Group</h4>
        </div>
        <div class="modal-body">
            @foreach($datas as $data) @endforeach
            <form method="post" action="{{URL::asset('accounting/acchead/update')}}">
                {{csrf_field()}}
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="chart_code">Code</label>
                            <input  value="{{$data->chart_code}}" type="text" class="form-control" name="chart_code" id="chart_code" placeholder="Enter Code">
                            <input  value="{{$data->id}}" type="hidden" class="form-control" name="id">
                            {{--<span class="help-block">Note : Enter if the ledger account is a bank or a cash account.</span>--}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="chart_name">Name</label>
                            <input  value="{{$data->chart_name}}" type="text" class="form-control" name="chart_name" id="chart_name" placeholder="Enter Name">
                            {{--<span class="help-block">Note : Name of your Head.</span>--}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="chart_type">Type</label>
                            <select class="form-control" name="chart_type" id="chart_type">
                                <option  @if($data->chart_type =='G') {{ 'selected' }} @endif value="G">Group</option>
                                <option  @if($data->chart_type =='L') {{ 'selected' }} @endif value="L">Ledger</option>
                            </select>
                            {{--<span class="help-block">Note : Type of Head.</span>--}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="chart_parent">Parent</label>
                            <select class="form-control" name="chart_parent" id="chart_parent" disabled>
                                @foreach($accHeads as $accHead)
                                    <option value="{{$accHead->id}}">{{ucfirst($accHead->chart_name)}}</option>
                                    {{--@if(count($accHead->childs))
                                        @include('accounting::pages.accHead.manageChildObtion',['childs' => $accHead->childs])
                                    @endif--}}
                                @endforeach
                            </select>
                            {{--<span class="help-block">Note : Select if the ledger account is a bank or a cash account.</span>--}}
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-6  no-padding">
                            <label for="opBalance">Opening Balance</label>
                            <div class="form-group">
                                <div class="col-md-2 no-padding">
                                    <select class="form-control" name="opbalancetype" id="opbalancetype">
                                        <option value="D" >Dr</option>
                                        <option value="C" >Cr</option>
                                    </select>
                                </div>
                                <div class="col-md-10">
                                    <input type="number" class="form-control" name="opbalance" id="opbalance" placeholder="Enter Opening balance">
                                </div>
                                <span class="help-block">Note : Assets / Expenses always have Dr balance and Liabilities / Incomes always have Cr balance.</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>
                                <input @if($data->cash_acc == 1) {{ 'checked' }} @endif  type="checkbox" name="cash_acc" id="cash_acc" >  Bank or cash account
                                <span class="help-block">Note : Select if the ledger account is a bank or a cash account.</span>
                            </label>
                        </div>
                        {{--<div class="form-group">
                            <label>
                                <input @if($data->reconciliation == 1) {{ 'checked' }} @endif  type="checkbox" name="reconciliation" id="reconciliation" >   Reconciliation
                                <span class="help-block">Note : If selected the ledger account can be reconciled from Reports > Reconciliation.</span>
                            </label>
                        </div>--}}
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" cols="30" name="notes" id="notes">{{ $data->notes }}</textarea>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <div class="col-md-7"><button type="submit" class="btn btn-primary">Save</button>
                    <a onclick="dlt_head({{$data->id}})" class="btn btn-danger pull-right">Delete</a></div>
                    <div class="col-md-4">
                        <a class="btn btn-info pull-right"  data-dismiss="modal">Close</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function dlt_head(id){
        if(confirm('Are you sure, You want to delete')){
            $(location).attr('href', '{{URL::asset('accounting/acchead/delete')}}/'+id)
        }
    }
</script>