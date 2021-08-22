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
            <form method="post" action="{{URL::asset('accounting/accbank/update')}}">
                {{csrf_field()}}
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank_code">Bank Code</label>
                            <input value="{{$data->bank_code}}" type="text" class="form-control" name="bank_code" id="bank_code" placeholder="Enter Bank Code">
                            <input value="{{$data->id}}" type="hidden" class="form-control" name="id" id="id">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank_name">Bank Name</label>
                            <input type="text" value="{{$data->bank_name}}" class="form-control" name="bank_name" id="bank_name" placeholder="Enter Bank Name">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank_acc_no">Bank Acc No</label>
                            <input value="{{$data->bank_acc_no}}" type="text" class="form-control" name="bank_acc_no" id="bank_acc_no" placeholder="Enter Bank Acc No">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank_acc_name">Bank Acc Name</label>
                            <input type="text"  value="{{$data->bank_acc_name}}" class="form-control" name="bank_acc_name" id="bank_acc_name" placeholder="Enter Bank Acc Name">
                        </div>
                    </div>


                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="bank_parent">Parent </label>
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
            $(location).attr('href', '{{URL::asset('accounting/accbank/delete')}}/'+id)
        }
    }
</script>