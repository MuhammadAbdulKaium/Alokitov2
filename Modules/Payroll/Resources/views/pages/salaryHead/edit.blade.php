<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Edit Salary Head </h3>
</div>
<form id="salaryComponent-create-form" action="{{url('/payroll/salary/update')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="instute_id" value="{{$head->instute_id}}">
    <input type="hidden" name="id" value="{{$head->id}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="errorTxt"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="custom_name">Custom Name </label>
                    <input id="custom_name" class="form-control" name="custom_name" type="text" value="{{$head->custom_name}}">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="head_name">Head Name </label>
                    <input id="head_name" class="form-control" name="head_name" type="text" value="{{$head->head_name}}">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="type">Type </label>
                    <select class="form-control" id="type" name="type">
                        <option value="">Select</option>
                        @foreach($data[0] as $id => $value)
                            @if($id == $head->type)
                                <option value="{{$id}}" selected>{{$value}}</option>
                            @else
                                <option value="{{$id}}">{{$value}}</option>
                            @endif
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="calculation">Calculation </label>
                    <select class="form-control" id="calculation" name="calculation">
                        <option value="">Select</option>
                        @foreach($data[1] as $id => $value)
                            @if($id == $head->calculation)
                                <option value="{{$id}}" selected>{{$value}}</option>
                            @else
                                <option value="{{$id}}">{{$value}}</option>
                            @endif

                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="placement">Placement </label>
                    <select class="form-control" id="placement" name="placement">
                        @foreach($data[2] as $id => $value)
                            @if($id == $head->placement)
                                <option value="{{$id}}" selected>{{$value}}</option>
                            @else
                                <option value="{{$id}}">{{$value}}</option>
                            @endif
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <!--./modal-body-->
        <div class="modal-footer">
            <button type="submit" class="btn btn-info pull-left" id="create"> Update</button>
            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
        </div>
        <!--./modal-footer-->
    </div>
</form>

<style>
    .pad-right{
        padding-right: 20px;
    }
</style>

<script type="text/javascript">

</script>
