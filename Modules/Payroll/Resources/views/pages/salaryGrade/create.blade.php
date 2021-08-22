<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Salary Grade </h3>
</div>
<form id="salaryComponent-create-form" action="{{url('/payroll/salary/grade/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="institute_id" value="{{session()->get('institute')}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="errorTxt"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="custom_name">Grade Name </label>
                    <input id="grade_name" class="form-control" name="grade_name" type="text">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="head_name">Parent Grade </label>
                    <select class="form-control" id="parent_grade_id" name="parent_grade_id">
                        <option value="">Select</option>
                        @foreach($gradeList as $id => $value)
                            <option value="{{$value->id}}">{{$value->grade_name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="type">Amount </label>
                    <input id="amount" class="form-control" name="amount" type="text">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="type">Percentage </label>
                    <input id="percentage" class="form-control" name="percentage" type="text">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="calculation">based </label>
                    <select class="form-control" id="based" name="based">
                        <option value="">Select</option>
                        @foreach($data as $id => $value)
                            <option value="{{$id}}">{{$value}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <!--./modal-body-->
        <div class="modal-footer">
            <button type="submit" class="btn btn-info pull-left" id="create"> Create</button>
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
