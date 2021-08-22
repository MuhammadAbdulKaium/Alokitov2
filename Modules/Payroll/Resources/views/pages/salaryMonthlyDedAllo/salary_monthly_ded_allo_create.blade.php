<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 10/2/17
 * Time: 12:13 PM
 */
?>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Assign Employee Monthly Deduction and Allowance </h3>
</div>
<form id="salaryMonthlyDedAllo-create-form" action="{{url('/payroll/emp-salary-dedallo/store')}}" method="POST">
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
                    <label class="control-label" for="employee">Employee : </label>
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
                    <label class="control-label" for="structureType">Structure Type : </label>
                    <select class="form-control" id="structureType" name="structureType">
                        <option value="">Select</option>
                        <option value="B">Basic</option>
                        <option value="G">Gross</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row" id="salary-segregation">
            <div class="col-md-12">
                <h3>Add
                    <a class="btn btn-success" onclick="addData('allo')"><i class="fa fa-plus-square"></i> Allowance</a>
                    <a class="btn btn-success" onclick="addData('ded')"><i class="fa fa-plus-square"></i> Deduction</a>
                </h3>
            </div>
        </div>
        <div class="row"><div class="col-sm-12" id="addRemoveBoxAllo"></div></div>
        <div class="row"><div class="col-sm-12" id="addRemoveBoxDed"></div></div>
        <input type="hidden" id="maxValI" name="maxValI" value="0">
        <input type="hidden" id="maxValII" name="maxValII" value="1000">
        <br>
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
            {{--<div class="col-md-4">--}}
            {{--<label class="control-label" for="structureType">Select Month: </label><br>--}}
            {{--<a class="btn btn-info" onclick="chooseMonth(-1)" title="Previous Month">{{$s='&nbsp;&nbsp;&nbsp;'}}  <i class="fa fa-angle-left"></i>{{$s}}</a>--}}
            {{--<a class="btn btn-info" onclick="chooseMonth(0)" title="Current Month">{{$s}}<i class="fa fa-dot-circle-o"></i>{{$s}}</a>--}}
            {{--<a class="btn btn-info" onclick="chooseMonth(1)" title="Next Month">{{$s}}<i class="fa fa-angle-right"></i>{{$s}}</a>--}}
            {{--</div>--}}
        </div>
        <!--./modal-body-->
        <div class="modal-footer">
            <button type="submit" class="btn btn-info pull-left" id="create"></i> Create</button>
            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            <button onclick="clearData()" class="btn btn-danger pull-right" type="button">clear</button>
        </div>
        <!--./modal-footer-->
    </div>
</form>

<script>
    //    function chooseMonth(i) {//i=i+5;
    //        var d = new Date();
    //        d.setMonth(d.getMonth()+i);
    //        var m = d.getMonth();
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
        $('#addRemoveBoxAllo').html('');
        $('#addRemoveBoxDed').html('');
        $('#maxValI').val('0');
        $('#maxValII').val('1000');
    }

    function addData(type){
        if(type == 'allo'){
            var iHolder = $('#maxValI');
        }else if(type == 'ded'){
            var iHolder = $('#maxValII');
        }
        $('#extraAlloDed').show(100);
        var i = iHolder.val(); i++;
        type = ''+type+'';
        var lastPart =
            '    <div class="col-sm-3">'+
            '        <div class="form-group">'+
            '            <input id="salaryAmtVal_'+i+'"  onkeyup="salaryAmtVal('+i+')" class="form-control" name="salaryAmtVal_'+i+'" type="number">'+
            '        </div>'+
            '    </div>'+
            '    <div class="col-sm-2">'+
            '        <div class="form-group">'+
            '            <select class="form-control" onchange="salaryAmtVal('+i+')" name="percent_'+i+'" id="percent_'+i+'">'+
            '                <option value="P">Percent(%)</option>'+
            '                <option selected value="">Amount</option>'+
            '            </select>'+
            '        </div>'+
            '    </div>'+
            '    <div class="col-sm-1">'+
            '        <a class="form-control btn btn-info"  onclick="removeData('+i+')"> - </a>'+
            '    </div>'+
            '</div>';
        if(type == 'allo'){
            $('#addRemoveBoxAllo').append(
                '<div class="row" id="addMore_'+i+'">'+
                '    <div class="col-sm-1">'+
                '        Allowance'+
                '    </div>'+
                '    <div class="col-sm-5">'+
                '        <div class="form-group">'+
                '            <input type="hidden" id="alloDedSign" value="1">'+
                '            <select class="form-control" onchange="salaryAmtVal('+i+')" name="salComp_'+i+'" id="salComp_'+i+'">'+
                '                <option>Select Allowance</option>'+
                '                @foreach($alloSalComp as $data)'+
                '                    <option value="{{$data->id}}">{{$data->name}}</option>'+
                '                @endforeach'+
                '            </select>'+
                '        </div>'+
                '    </div>'+lastPart
            );
        }else if(type == 'ded'){
            $('#addRemoveBoxDed').append(
                '<div class="row" id="addMore_'+i+'">'+
                '    <div class="col-sm-1">'+
                '        Deduction'+
                '    </div>'+
                '    <div class="col-sm-5">'+
                '        <div class="form-group">'+
                '            <input type="hidden" id="alloDedSign" value="-1">'+
                '            <select class="form-control" onchange="salaryAmtVal('+i+')" name="salComp_'+i+'" id="salComp_'+i+'">'+
                '                <option>Select Deduction</option>'+
                '                @foreach($dedSalComp as $data)'+
                '                    <option value="{{$data->id}}">{{$data->name}}</option>'+
                '                @endforeach'+
                '            </select>'+
                '        </div>'+
                '    </div>'+lastPart
            );
        }

        $('#extraAlloDed').append(
            '<div class="row" id="alloDed_'+i+'">'+
            '<div class="col-sm-2"></div>'+
            '<div class="col-sm-2"><label id="component"></label></div>'+
            '<div class="col-sm-2" style="text-align: right" id="salaryAmtVal"></div>'+
            '<div class="col-sm-3" style="text-align: right" id="amount"></div>'+
            '</div>'
        );
        iHolder.val(i);
    }

    function removeData(i){
        var salaryAmtVal = $('#salaryAmtVal_'+i).val();
        var total = $('#grandTotal').text();

        if(i<1000){
            var gTotal = parseFloat(total) - parseFloat(salaryAmtVal);
        }else if(i>1000){
            var gTotal = parseFloat(total) + parseFloat(salaryAmtVal);
        }
        $('#grandTotal').html(gTotal.toFixed(2));
        $('#addMore_'+i).remove();
        $('#alloDed_'+i).remove();

        var iHolder = $('#maxValI').val(); var j=0;
        for(var i=1; i<=iHolder; i++){ if($('#addMore_'+iHolder).text() == ''){ j++; } }

        var iHolder = $('#maxValII').val(); var k=0;
        for(var i=1000; i<=iHolder; i++){ if($('#addMore_'+iHolder).text() == ''){ k++; } }

        if(j>0 && k>0){ $('#extraAlloDed').hide(100); }
    }


    $(document).ready(function(){
        // validate signup form on keyup and submit
        var validator = $("#salaryMonthlyDedAllo-create-form").validate({
            // Specify validation rules
            rules: {
                employee: {required: true,
                },structureType: { required: true,
                },maxValI: { required: true,
                },maxValII: { required: true,
                },effectiveMonth: { required: true,
                },effectiveYear: { required: true, },
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
                if($('#addRemoveBoxAllo').children().length != 0 || $('#addRemoveBoxDed').children().length != 0){
                    form.submit();
                }else{
                    alert('Enter minimum one Deduction or Allowance');
                }
            }
        });
    });
</script>