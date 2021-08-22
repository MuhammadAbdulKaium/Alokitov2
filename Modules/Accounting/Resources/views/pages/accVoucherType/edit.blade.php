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
            <h4 class="modal-title">Edit Bank</h4>
        </div>
        <div class="modal-body">
            @foreach($datas as $data) @endforeach
            <form method="post" action="{{URL::asset('accounting/accvouchertype/update')}}">
                {{csrf_field()}}
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="voucher_code">Voucher code</label>
                            <input value="{{$data->voucher_code}}" type="text" class="form-control" name="voucher_code" id="voucher_code" placeholder="Enter Voucher Code">
                            <input value="{{$data->id}}" type="hidden" class="form-control" name="id" id="voucher_code" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="voucher_name">Voucher Name</label>
                            <input value="{{$data->voucher_name}}" type="text" class="form-control" name="voucher_name" id="voucher_name" placeholder="Enter Voucher Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="voucher_type">Voucher Type</label>
                            <select class="form-control" name="voucher_type" id="voucher_type">
                                <option value="">Select one</option>
                                <option @if($data->voucher_type_id == 1) {{'selected'}} @endif value="1">CONTRA</option>
                                <option @if($data->voucher_type_id == 2) {{'selected'}} @endif value="2">CREDIT NOTE</option>
                                <option @if($data->voucher_type_id == 3) {{'selected'}} @endif value="3">DEBIT NOTE</option>
                                <option @if($data->voucher_type_id == 4) {{'selected'}} @endif value="4">DELIVERY NOTE</option>
                                <option @if($data->voucher_type_id == 5) {{'selected'}} @endif value="5">JOURNAL</option>
                                <option @if($data->voucher_type_id == 6) {{'selected'}} @endif value="6">MEMORANDUM</option>
                                <option @if($data->voucher_type_id == 7) {{'selected'}} @endif value="7">PAYMENT</option>
                                <option @if($data->voucher_type_id == 8) {{'selected'}} @endif value="8">PHYSICAL STOCK</option>
                                <option @if($data->voucher_type_id == 9) {{'selected'}} @endif value="9">PURCHASE</option>
                                <option @if($data->voucher_type_id == 10) {{'selected'}} @endif value="10">PURCHASE ORDER</option>
                                <option @if($data->voucher_type_id == 11) {{'selected'}} @endif value="11">RECEIPT</option>
                                <option @if($data->voucher_type_id == 12) {{'selected'}} @endif value="12">RECEIPT NOTE</option>
                                <option @if($data->voucher_type_id == 13) {{'selected'}} @endif value="13">REJECTIONS IN</option>
                                <option @if($data->voucher_type_id == 14) {{'selected'}} @endif value="14">REJECTIONS OUT</option>
                                <option @if($data->voucher_type_id == 15) {{'selected'}} @endif value="15">REVERSING JOURNAL</option>
                                <option @if($data->voucher_type_id == 16) {{'selected'}} @endif value="16">SALES</option>
                                <option @if($data->voucher_type_id == 17) {{'selected'}} @endif value="17">SALES ORDER</option>
                                <option @if($data->voucher_type_id == 18) {{'selected'}} @endif value="18">STOCK JOURNAL</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank_parent">Default Ledger</label>
                            @foreach($accHeads as $accHead)
                            <input value="{{$accHead->chart_name}}" class="form-control" disabled>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="notes">Notes</label>
                            <textarea class="form-control" cols="30" name="notes" id="notes">{{$data->notes}}</textarea>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a onclick="dlt_head({{$data->id}})" type="submit" class="btn btn-danger pull-right">Delete</a>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    function dlt_head(id){
        if(confirm('Are you sure, You want to delete')){
            $(location).attr('href', '{{URL::asset('accounting/accvouchertype/delete')}}/'+id)
        }
    }
</script>