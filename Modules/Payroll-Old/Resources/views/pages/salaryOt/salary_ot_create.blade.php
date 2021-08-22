<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 10/8/17
 * Time: 3:37 PM
 */
?>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Overtime Rules </h3>
</div>
<form id="salaryOt-create-form" action="{{url('payroll/ot-rates/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="approveDate">Approve Date</label>
                    <input class="form-control" type="date" name="approveDate" id="approveDate">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="effectiveDate">Effective Date</label>
                    <input class="form-control" type="date" name="effectiveDate" id="effectiveDate">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="otType">Overtime Type</label>
                    <select class="form-control" name="otType" id="otType">
                        <option value="">Select Overtime Component</option>
                        @foreach($SalaryOtLists as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="otRate">Overtime Rate Per Hour</label>
                    <input class="form-control" type="number" name="otRate" id="otRate">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="otStart">Overtime Start</label>
                    <input class="form-control" type="time" name="otStart" id="otStart">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="otEnd">Overtime End</label>
                    <input class="form-control" type="time" name="otEnd" id="otEnd">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="minOt">Minimum Overtime In a day (Hour)</label>
                    <input class="form-control" type="text" name="minOt" id="minOt">
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="maxOt">Maximum Overtime In a day (Hour)</label>
                    <input class="form-control" type="text" name="maxOt" id="maxOt">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label for="otGrace">Overtime Grace In a day (Minute)</label>
                    <input class="form-control" type="number" name="otGrace" id="otGrace">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10">
                <div class="form-group">
                    <label for="remarks">Remarks</label>
                    <textarea class="form-control" name="remarks" id="remarks"></textarea>
                </div>
            </div>
        </div>
        <!--./modal-body-->
        <div class="modal-footer">
            <button type="submit" class="btn btn-info pull-left" id="create"> Create</button>
            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
        </div>
    </div>
    <!--./modal-footer-->
</form>
<script type="text/javascript">
    $(document).ready(function(){
        // validate signup form on keyup and submit
        var validator = $("#salaryOt-create-form").validate({
            // Specify validation rules
            rules: {
                effectiveDate: {
                    required: true,
                },otType: {
                    required: true,
                },otRate: {
                    required: true,
                },otStart: {
                    required: true,
                },otEnd: {
                    required: true,
                },
            },

            // Specify validation error messages
            messages: {
            },

            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },

            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
                $(element).closest('.form-group').addClass('has-success');
            },

            debug: true,
            success: "valid",
            errorElement: 'span',
            errorClass: 'help-block',

            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>