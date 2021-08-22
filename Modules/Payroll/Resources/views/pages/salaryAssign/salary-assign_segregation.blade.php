<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 9/23/17
 * Time: 6:40 PM
 */
$totalPercent = $totalPercentDeduction = $totAlallo = $totalDeduction =0;
foreach($SalaryStructureDetails as $data) {
    if(!empty($data->percent))                       $totalPercent = $totalPercent + intval($data->amount);
    if(!empty($data->percent) && $data->type == 'D') $totalPercentDeduction = $totalPercentDeduction + intval($data->amount);
    if(empty($data->percent) && $data->type == 'A')  $totAlallo = $totAlallo + intval($data->amount);
    if(empty($data->percent) && $data->type == 'D')  $totalDeduction = $totalDeduction + intval($data->amount);
}
?>
    <div class="col-md-12">
    <div class="row">
        <div class="col-md-12">
            <h3>{{$SalaryStructure->name}}
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
        @php $totalAmt = 0; $t=0;@endphp
        @foreach($SalaryStructureDetails as $data)
            <div class="row">
                <div class="col-sm-2"></div>
                <div class="col-sm-2">
                    <label class="control-label" for="empList">{{$data->name}}</label>
                </div>
                <div class="col-sm-2"  style="text-align: right">
                    @if($data->type == "D"){{'-'}}@endif{{$data->amount}}@if(!empty($data->percent)){{'%'}}@endif</label>
                </div>
                <div class="col-sm-3"  style="text-align: right">
                    <?php
                    if($formData['structureType'] == 'B'){
                        if(!empty($data->percent)) $amt = $formData['salary'] * $data->amount * .01;
                        else  $amt = $data->amount;

                        if($data->type == "D") $amount = $amt * (-1);
                        else $amount = $amt;

                        $totalAmt = $totalAmt +  $amount;
                    }elseif($formData['structureType'] == 'G'){
                        if(!empty($data->percent)) $amt = (($formData['salary'] + $totalDeduction - $totAlallo) / ($totalPercent))  * $data->amount;
                        else  $amt = $data->amount;

                        if($data->type == "D") $amount = $amt * (-1);
                        else $amount = $amt;

                        $totalAmt = $totalAmt +  $amount;
                    }
                    ?>
                    {{money_format('%i', $amount)}}
                    @if($t==0)
                        <input name="basic" type="hidden" value="{{money_format('%i', $amount)}}">
                    @endif
                </div>
            </div>
            @php $t++; @endphp
        @endforeach
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-2"><label >Gross: </label></div>
            <div class="col-sm-2"></div>
            <div class="col-sm-3">
                <strong class="pull-right">{{money_format('%i', $totalAmt)}}</strong>
            </div>
        </div>
        <div id="extraAlloDed" style="display: none">
            <div class="row">
                <div class="col-md-3"></div>
                <div class="col-md-4"><label>Extra Allowance and Deduction</label></div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-2"></div>
            <div class="col-md-8">
                @for($i=1;$i<=10;$i++) ---------- @endfor
            </div>
        </div>
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-2"><label >Grand Total: </label></div>
            <div class="col-sm-2"></div>
            <div class="col-sm-3">
                <strong class="pull-right" id="grandTotal">{{money_format('%i', $totalAmt)}}</strong>
            </div>
        </div>
</div>

<script>
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

    function salaryAmtVal(i) {
        var salaryAmtVal = $('#salaryAmtVal_'+i).val();
        var salComp = $('#salComp_'+i+' option:selected').text();
        var percent = $('#percent_'+i).val() == 'P' ? '%' : '';
        var sign = $('#alloDedSign').val();

        $('#alloDed_'+i+' #component').html(salComp);
        $('#alloDed_'+i+' #salaryAmtVal').html(salaryAmtVal+percent);

        if(percent == '%') {
            var gTotal = '{{$totalAmt}}';
            var percent = parseFloat(salaryAmtVal) * 0.01 * parseInt(sign);
            gTotal = parseFloat(gTotal) * percent;
        } else {
            var  gTotal= parseFloat(salaryAmtVal) * parseInt(sign);
        }

        $('#alloDed_'+i+' #amount').html(gTotal);
        var gTotal = parseFloat(gTotal) + parseFloat('{{$totalAmt}}');

        $('#grandTotal').html(gTotal.toFixed(2));
    }
</script>