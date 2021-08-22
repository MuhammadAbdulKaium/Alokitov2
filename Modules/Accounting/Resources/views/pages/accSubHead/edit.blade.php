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
            <form method="post" action="{{URL::asset('accounting/accsubhead/update')}}">
                {{csrf_field()}}
                <div class="box-body">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="chart_code">Code</label>
                            <input value="{{$data->chart_code}}" type="text" class="form-control" name="chart_code" id="chart_code" placeholder="Enter Code">
                            <input value="{{$data->id}}" type="hidden" class="form-control" name="id" id="id">
                            {{--<span class="help-block">Note : Enter if the ledger account is a bank or a cash account.</span>--}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="chart_name">Name</label>
                            <input value="{{$data->chart_name}}" type="text" class="form-control" name="chart_name" id="chart_name" placeholder="Enter Name">
                            {{--<span class="help-block">Note : Name of your Head.</span>--}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="chart_parent">Parent: </label>
                            {{--<select class="form-control" name="chart_parent" id="chart_parent">--}}
                                {{--@foreach($accHeads as $accHead)
                                    <option value="{{$accHead->id}}">{{$accHead->chart_name}}</option>
                                @endforeach--}}

                                @foreach($accHeads as $accHead)
                                    {{--<option disabled value="{{$accHead->id}}">{{$accHead->chart_name}}</option>--}}
                                    <input class="form-control" type="text" disabled value="{{$accHead->chart_name}}">
                                @endforeach
                            {{--</select>--}}
                            {{--<span class="help-block">Note : Select if the ledger account is a bank or a cash account.</span>--}}
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
            $(location).attr('href', '{{URL::asset('accounting/accsubhead/delete')}}/'+id)
        }
    }
</script>