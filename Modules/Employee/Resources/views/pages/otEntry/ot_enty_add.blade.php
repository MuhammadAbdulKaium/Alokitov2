<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 10/24/17
 * Time: 1:28 PM
 */
?>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Employee Overtime </h3>
</div>
<form id="overTime-create-form" action="{{url('employee/employee-over-time-entry/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="empList">Employee List</label>
                    <select class="form-control" name="empList" id="empList">
                        <option value="">Select Employee</option>
                        @foreach($empInfo as $data)
                            <option value="{{$data->employee_id}}">{{$data->employee_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label for="approveDate">Approve Date</label>
                    <input class="form-control" type="date" name="approveDate" id="approveDate">
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label for="otHour">Ot Hours</label>
                    <input class="form-control" type="Number" name="otHour" id="otHour">
                </div>
            </div>
            {{--<div class="col-md-4">--}}
                {{--<label class="control-label" for="structureType">Select Month: </label><br>--}}
                {{--<a class="btn btn-info" onclick="chooseMonth(-1)" title="Previous Month">{{$s='&nbsp;&nbsp;&nbsp;'}}  <i class="fa fa-angle-left"></i>{{$s}}</a>--}}
                {{--<a class="btn btn-info" onclick="chooseMonth(0)" title="Current Month">{{$s}}<i class="fa fa-dot-circle-o"></i>{{$s}}</a>--}}
                {{--<a class="btn btn-info" onclick="chooseMonth(1)" title="Next Month">{{$s}}<i class="fa fa-angle-right"></i>{{$s}}</a>--}}
            {{--</div>--}}
        </div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label" for="effectiveMonth">Effective Month : </label>
                    <select class="form-control" id="effectiveMonth" name="effectiveMonth">
                        <option value="">Select Month</option>
                        @for($i=1;$i<=12;$i++)
                            <option value="{{$i}}">{{date('F',strtotime(date('Y-').$i.date('-d')))}}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label" for="effectiveYear">Effective Year: </label>
                    <select class="form-control" id="effectiveYear" name="effectiveYear">
                        <option value="">Select Year</option>
                        @for($i=2016;$i<=date('Y')+1;$i++)
                            <option value="{{$i}}">{{$i}}</option>
                        @endfor
                    </select>
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
<script>
//    function chooseMonth(i) {
//        var d = new Date();
//        d.setMonth(d.getMonth()+i);
//        var m = d.getMonth() + 1;
//        var y = d.getFullYear();
//        $('#effectiveMonth').val(m);
//        $('#effectiveYear').val(y);
//    }
    function clearData() {
        $('#employee').val('');
        $('#amount').val('');
        $('#structureType').val('');
        $('#effectiveMonth').val('');
        $('#effectiveYear').val('');
    }
</script>
<script type="text/javascript">
    $(document).ready(function(){
        var validator = $("#overTime-create-form").validate({
            // Specify validation rules
            rules: {
                empList: {
                    required: true,
                },approveDate: {
                    required: true,
                },otHour: {
                    required: true,
                },effectiveMonth: {
                    required: true,
                },effectiveYear: {
                    required: true,
                }
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