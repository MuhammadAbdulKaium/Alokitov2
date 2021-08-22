<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 9/11/17
 * Time: 6:17 PM
 */
?>
<div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content">
        <div class="modal-body">
            <div class="modal-header">
                <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
                <h3 class="box-title"><i class="fa fa-edit"></i> Edit Salary Structure </h3>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="errorTxt"></div>
                </div>
            </div>

            <form id="salaryStructure-create-form" action="{{url('/payroll/salary-structure/update')}}" method="POST">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="errorTxt"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="control-label" for="salaryStructureName">Salary Structure Name: </label>
                                <input id="salaryStructureName" value="{{$SalaryStructures->name}}" class="form-control" name="salaryStructureName" maxlength="50" type="text">
                                <input  value="{{$SalaryStructuresBasic[0]->structure_id}}" class="form-control" name="id" type="hidden">
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="control-label" for="details">Details: </label>
                                <textarea class="form-control"  name="details" id="details" rows="3">{{$SalaryStructures->details}}</textarea>
                                <div class="help-block"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <label class="control-label" >Salary Component: </label>
                        </div>
                        <div class="col-sm-5">
                            <label class="control-label" >Percent / Amount</label>
                        </div>
                        <div class="col-sm-2">
                            <label class="control-label" >&nbsp;</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <div class="form-group">
                                <select class="form-control" name="salComp_1" id="salComp_1">
                                    <option value="" selected>Select Basic Component</option>
                                    @foreach($BasicSalComp as $data)
                                        <option value="{{$data->id}}" @if($SalaryStructuresBasic[0]->name == $data->name) {{"selected"}} @endif>{{$data->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group"  style="display:none;">
                                <input id="salaryAmtVal_1" value="100"  class="form-control" name="salaryAmtVal_1" maxlength="3" type="text">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group" style="display:none;">
                                <select class="form-control" name="percent_1" id="percent_1">
                                    <option value="">Amount</option>
                                    <option selected value="P">Percent(%)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <br><strong>Allowance</strong>
                    <button type="button" class="btn btn-xs btn-info" onclick="addData('allo')"> + </button>
                    <div id="addRemoveBoxAllo">
                        @php $amountAlo = $amountDed = 0 ; $i=2; $ii=1000; @endphp
                        @foreach($SalaryStructuresAlo as $data)
                            {{--<div class="row">
                                <div class="col-sm-5">{{$data->name}}</div>
                                <div class="col-sm-3">{{$data->amount}}@if($data->percent == 'P'){{' %'}}@endif</div>
                                <div class="col-sm-2"></div>
                            </div>--}}
                            <div class="row" id="addMore_{{$i}}">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <select class="form-control" name="salComp_{{$i}}" id="salComp_{{$i}}">
                                            <option>Select Allowance</option>
                                            @foreach($alloSalComp as $d)
                                                <option value="{{$d->id}}" @if($d->name == $data->name) {{'selected'}} @endif>{{$d->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input id="salaryAmtVal_{{$i}}" value="{{$data->amount}}" onkeyup="totalPercent()" class="form-control" name="salaryAmtVal_{{$i}}" type="number">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select class="form-control" onchange="totalPercent()" name="percent_{{$i}}" id="percent_{{$i}}">
                                            <option @if($data->percent=='P'){{'selected'}}  @php echo $amountAlo = $amountAlo + $data->amount @endphp @endif value="P">Percent(%) </option>
                                            <option @if($data->percent==''){{'selected'}} @endif >Amount</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <a class="form-control btn btn-info"  onclick="removeData({{$i}}); totalPercent()"> - </a>
                                </div>
                            </div>
                            @php $i++ @endphp
                        @endforeach
                    </div>
                    <br><strong>Deduction</strong>
                    <button type="button" class="btn btn-xs btn-info" onclick="addData('ded')"> + </button>
                    <div id="addRemoveBoxDed">
                        @foreach($SalaryStructuresDed as $data)
                            <div class="row" id="addMore_{{$i}}">
                                <div class="col-sm-5">
                                    <div class="form-group">
                                        <select class="form-control" name="salComp_{{$i}}" id="salComp_{{$i}}">
                                            <option>Select Allowance</option>
                                            @foreach($dedSalComp as $d)
                                                <option value="{{$d->id}}" @if($d->name == $data->name) {{'selected'}} @endif>{{$d->name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input id="salaryAmtVal_{{$i}}" value="{{$data->amount}}" onkeyup="totalPercent()" class="form-control" name="salaryAmtVal_{{$i}}" maxlength="3" type="number">
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <select class="form-control" onchange="totalPercent()" name="percent_{{$i}}" id="percent_{{$i}}">
                                            <option @if($data->percent=='P'){{'selected'}} @php $amountDed = $amountDed - $data->amount @endphp @endif value="P">Percent(%)</option>
                                            <option @if($data->percent==''){{'selected'}} @endif >Amount</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <a class="form-control btn btn-info"  onclick="removeData({{$i}}); totalPercent()"> - </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="row">
                        <div class="col-sm-5">
                            <label class="control-label" ></label>
                        </div>
                        <div class="col-sm-5">
                            Total Percent:
                            <label class="control-label" id="totalPercent">{{$amountAlo-$amountDed}}</label>
                            <input type="hidden" id="maxValI" name="maxValI" value="{{$i}}">
                            <input type="hidden" id="maxValII" name="maxValII" value="1000">
                            <div class="col-sm-2">
                                <label class="control-label" ></label>
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
        </div>
    </div>
</div>

<script type="text/javascript">
    function addData(type){
        if(type == 'allo'){
            var iHolder = $('#maxValI');
            var box = $('#addRemoveBoxAllo');
        }else if(type == 'ded'){
            var iHolder = $('#maxValII');
            var box = $('#addRemoveBoxDed');
        }
        var i = iHolder.val(); i++;
        var lastPart =  '    <div class="col-sm-3">'+
            '        <div class="form-group">'+
            '            <input id="salaryAmtVal_'+i+'"  onkeyup="totalPercent()" class="form-control" name="salaryAmtVal_'+i+'" type="number">'+
            '        </div>'+
            '    </div>'+
            '    <div class="col-sm-2">'+
            '        <div class="form-group">'+
            '            <select class="form-control" onchange="totalPercent()" name="percent_'+i+'" id="percent_'+i+'">'+
            '                <option value="P">Percent(%)</option>'+
            '                <option value="">Amount</option>'+
            '            </select>'+
            '        </div>'+
            '    </div>'+
            '    <div class="col-sm-1">'+
            '        <a class="form-control btn btn-info"  onclick="removeData('+i+');totalPercent()"> - </a>'+
            '    </div>'+
            '</div>';

        if(type == 'allo'){
            $('#addRemoveBoxAllo').append(
                '<div class="row" id="addMore_'+i+'">'+
                '    <div class="col-sm-5">'+
                '        <div class="form-group">'+
                '            <select class="form-control" name="salComp_'+i+'" id="salComp_'+i+'">'+
                '                <option>Select Allowance</option>'+
                '                @foreach($alloSalComp as $data)'+
                '                    <option value="{{$data->id}}">{{$data->name}}</option>'+
                '                @endforeach'+
                '            </select>'+
                '        </div>'+
                '    </div>'+lastPart);
        }else if(type == 'ded'){
            $('#addRemoveBoxDed').append(
                '<div class="row" id="addMore_'+i+'">'+
                '    <div class="col-sm-5">'+
                '        <div class="form-group">'+
                '            <select class="form-control" name="salComp_'+i+'" id="salComp_'+i+'">'+
                '                <option>Select Deduction</option>'+
                '                @foreach($dedSalComp as $data)'+
                '                    <option value="{{$data->id}}">{{$data->name}}</option>'+
                '                @endforeach'+
                '            </select>'+
                '        </div>'+
                '    </div>'+lastPart
            );
        }
        iHolder.val(i);
    }
    function removeData(i){
        $('#addMore_'+i).remove();
    }
    function totalPercent() {
        var i_ = $('#maxValI').val();
        var ii_ = $('#maxValII').val();
        var totalPercent = 0;
        for(var i=2; i<=i_; i++){
            if($('#percent_'+i).val() =='P'){
                var par = $('#salaryAmtVal_'+i).val() != null ? parseInt($('#salaryAmtVal_'+i).val()) : 0;
                totalPercent = totalPercent +  parseInt(par);
            }
        }
        for(var ii=1000; ii<=ii_; ii++){
            if($('#percent_'+ii).val() =='P'){
                var par = $('#salaryAmtVal_'+ii).val() != null ? parseInt($('#salaryAmtVal_'+ii).val()) : 0;
                totalPercent = totalPercent -  parseInt(par);
            }
        }
        $('#totalPercent').html(totalPercent);
    }

    $(document).ready(function(){
        // validate signup form on keyup and submit
        var validator = $("#salaryStructure-create-form").validate({
            // Specify validation rules
            rules: {
                salaryStructureName:{
                    required: true,
                    minlength: 3,
                    maxlength: 100,
                },
                salComp_1: {
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
                form.submit();
            }
        });
    });
</script>