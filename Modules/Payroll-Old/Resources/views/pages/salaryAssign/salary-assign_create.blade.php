<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 9/21/17
 * Time: 3:10 PM
 */?>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Assign Employee Salary </h3>
</div>
<form id="salaryComponent-create-form" action="{{url('/payroll/emp-salary-assign/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="errorTxt"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label class="control-label" for="empList">Employee : </label>
                    <select id="employee" class="form-control" name="employee" >
                        <option value="">Select Employee</option>
                        @foreach($emp as $data)
                            <option value="{{$data->id}}">{{$data->title}} {{$data->first_name}} {{$data->middle_name}} {{$data->last_name}} {{$data->alias}} </option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="control-label" for="salaryStructure">Salary Structure : </label>
                    <select class="form-control" id="salaryStructure" name="salaryStructure">
                        <option value="">Select</option>
                        @foreach($salaryStructure as $data)
                            <option value="{{$data->id}}">{{$data->name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="control-label" for="salary">Salary :</label>
                    <input id="salary" class="form-control" name="salary" type="number">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <label class="control-label" for="structureType">Structure Type : </label>
                    <select class="form-control" id="structureType" name="structureType">
                        <option value="">Select</option>
                        <option value="B">Basic</option>
                        <option value="G">Gross</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-2">
                <br>
                <a class="btn btn-success" onclick="funcCalc()">Calculate</a>
            </div>
        </div>
        <div class="row" id="salary-segregation"></div>
        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <label class="control-label" for="effectiveDate">Effective Date : </label>
                    <input type="date" class="form-control" name="effectiveDate" id="effectiveDate">
                </div>
            </div>
        </div>
        <!--./modal-body-->
        <div class="modal-footer">
            <button type="submit" class="btn btn-info pull-left" id="create"></i> Create</button>
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
    function funcCalc() {
        var employee = $('#employee').val();
        var salaryStructure = $('#salaryStructure').val();
        var salary = $('#salary').val();
        var structureType = $('#structureType').val();
        var token = "{{ csrf_token() }}";
        var dataSet = '_token=' + token + '&salaryStructure=' + salaryStructure + '&salary=' + salary + '&structureType=' + structureType;
        if (employee == '' ) {
            alert('Please select an Emoloyee');
        }else if (salaryStructure == '' ) {
            alert('Salary Structure Cannot be empty');
        }else if (salary == '' ) {
            alert('Salary Cannot be empty');
        }else if (structureType == '' ) {
            alert('Structure Type Cannot be empty');
        }else{
            $.ajax({
                url: "{{ url('payroll/emp-salary-assign/salary-segregation')}}",
                type: 'post',
                data: dataSet,
                beforeSend: function () {
                }, success: function (data) {
                    $('#salary-segregation').html(data);
                }
            });
        }
    }
    $(document).ready(function(){
        // validate signup form on keyup and submit
        var validator = $("#salaryComponent-create-form").validate({
            // Specify validation rules
            rules: {
                employee: {
                    required: true,
                }, salaryStructure: {
                    required: true,
                }, salary: {
                    required: true,
                }, structureType: {
                    required: true,
                }, effectiveDate: {
                    required: true,
                },
            },

            // Specify validation error messages
            messages: {
                //name:"Shift Name is required.",
                //startTime:"Shift Start Time is required.",
                //endTime:"Shift End Time is required.",
            },

            //errorLabelContainer: '.errorTxt',


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
                if($('#salary-segregation').text() == ''){
                    alert('Press calculate button please.');
                }else{
                    form.submit();
                }
            }
        });
    });
</script>
