<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 3/20/17
 * Time: 5:08 PM
 */
use Modules\Accounting\Entities\AccCharts;
?>
@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-eye" aria-hidden="true"></i> Accounting
            </h1>
            <ul class="breadcrumb">
                <li><a href="{{url('/home')}}"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{url('/finance')}}"><i class="fa fa-home"></i>Finance</a></li>
                <li><a href="{{url('accounting')}}">Accounting</a></li>
                <li><a href="#">Add Voucher entry</a></li>
            </ul>
        </section>
        {{--<ol>
            @foreach($accHeads as $accHead)
            <li>{{$accHead->chart_name}}</li>
            @endforeach
        </ol>--}}
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Voucher entry</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('accounting/accvoucherentry')}}">
                                        <i class="fa"></i> List </a>
                                </div>
                            </div>
                            <form id="accVoucherEntry" method="post">
                                <div class="box-body">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="chart_parent">Voucher Type</label>
                                            <input autocomplete="off" onblur="nextVoucherNo()" list="voucherType" type="text" class="form-control" name="voucher_type" id="voucher_type" placeholder="Enter Voucher Type">
                                            <datalist id="voucherType">
                                                @foreach($accVoucherTypes as $accVoucherType)
                                                    <option>{{$accVoucherType->voucher_code}} ----- {{$accVoucherType->voucher_name}}</option>
                                                @endforeach
                                            </datalist>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="chart_code">Voucher No</label>
                                            <input readonly type="text" class="form-control"  name="voucherNo" id="voucherNo" placeholder="Voucher No">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="voucherData">Voucher Data</label>
                                            <input type="text" class="form-control"  name="voucherData" id="voucherData">
                                        </div>

                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes">Narration / Particulars</label>
                                            <textarea class="form-control" cols="30" name="notes" id="notes"></textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <datalist id="headOfAcc">
                                            @foreach($accHeads as $accHead)
                                                <option>{{$accHead->chart_code}} ----- {{$accHead->chart_name}}</option>
                                            @endforeach
                                        </datalist>
                                        <br>
                                        <table width="100%" id="tran_tbl">
                                            <thead>
                                            <tr>
                                                {{--<th width="1%"></th>--}}
                                                <th width="60%">Head of Account</th>
                                                <th width="15%">DR</th>
                                                <th width="15%">CR</th>
                                                <th width="3%"></th>
                                                <th width="2%"></th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                {{--<td><input readonly type="hidden" class="form-control"  value="1" id="sn" name="sn"></td>--}}
                                                <td><input autocomplete="off" list="headOfAcc" type="text" class="form-control" name="chart_name[]" id="chart_name" placeholder="Enter Head of Account"></td>
                                                <td><input style="text-align: right" type="number" onchange="mydr(1)"  onkeyup="mydr(1)" onclick="mydr(1)" class="form-control" name="dr[]" id="dr_1"></td>
                                                <td><input style="text-align: right" type="number" onchange="mycr(1)"  onkeyup="mycr(1)" onclick="mycr(1)" class="form-control" name="cr[]" id="cr_1"></td>
                                                <td><a id="add" onclick="addContent()" class="btn btn-primary">+</a></td>
                                                <td></td>
                                            </tr>
                                            </tbody>
                                            <tfoot>
                                            <tr style="text-align: right">
                                                <td>Total: </td>
                                                <td><div id="totalDr">0</div></td>
                                                <td><div id="totalCr">0</div></td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                        <input type="hidden" name="snCount" id="snCount" value="1">
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" id="submitBtn"  class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.js"></script>
    <script src="{{url('js/bootstrap.min.js')}}"></script>
    <script src="{{url('js/select2.full.min.js')}}"></script>
    <script src="{{url('js/jquery.inputmask.js')}}"></script>
    <script src="{{url('js/jquery.inputmask.date.extensions.js')}}"></script>

    <script>
        $("#voucherData").inputmask("dd-mm-yyyy", {"placeholder": "dd-mm-yyyy"});
        $("[data-mask]").inputmask();

        function addContent() {
            var sn = parseInt( $('#snCount').val()) + 1;
            var HTML = '<tr id = "row_'+sn+'">' +
                '<td><input autocomplete="off" list="headOfAcc" type="text" class="form-control" name="chart_name[]" id="chart_name" placeholder="Enter Head of Account"></td>' +
                '<td><input type="number" style="text-align: right" onchange="mydr('+sn+')" onkeyup="mydr('+sn+')" onclick="mydr('+sn+')"  class="form-control" name="dr[]" id="dr_'+sn+'"></td>' +
                '<td><input type="number" style="text-align: right" onchange="mycr('+sn+')" onkeyup="mycr('+sn+')" onclick="mycr('+sn+')"  class="form-control" name="cr[]" id="cr_'+sn+'"></td>' +
                '<td><a onclick="addContent()" class="btn btn-primary"> + </a></td>' +
                '<td><a onclick="deleteContent('+sn+')" class="btn btn-danger"> - </a></td>' +
                '</tr>';
            $('#tran_tbl').append(HTML);
            $('#snCount').val(sn);
        }
        function deleteContent(id) {
            var rowId = $('#row_'+id);
            rowId.remove();
            totalCalc();
        }
        /*
         1-dr
         2-cr
         */
        function amtDrCr(dc) {
            var dc = (dc == 1) ? 'dr' : 'cr';
            var sn = parseInt( $('#snCount').val());
            var total=0;
            for(var i=1;i<=sn;i++){
                if($('#'+dc+'_'+i).val() == '' || $('#'+dc+'_'+i).val() == null) {
                    var drcr = 0;
                }else{
                    var drcr = parseFloat($('#'+dc+'_' + i).val());
                }
                total = total + drcr;
            }
            return total;
        }

        function totalDr() {
            $('#totalDr').html(amtDrCr(1));
        }
        function totalCr() {
            $('#totalCr').html(amtDrCr(2));
        }
        function mydr(sn) {
            if($('#cr_'+sn).val() != ''){
                $('#dr_'+sn).val('');
            }else if(parseFloat($('#dr_'+sn).val())<0){
                $('#dr_'+sn).val('');
            }
            totalDr();
        }
        function mycr(sn) {
            if($('#dr_'+sn).val() != ''){
                $('#cr_'+sn).val('');
            }else if(parseFloat($('#cr_'+sn).val())<0){
                $('#cr_'+sn).val('');
            }
            totalCr();
        }
        function totalCalc(){
            totalDr();totalCr();
        }
        function nextVoucherNo() {
            var voucher_type = $('#voucher_type').val();
            var token = "{{ csrf_token() }}";
            var dataSet = '_token='+token+'&vType='+voucher_type;
            $.ajax({
                url: "{{ url('accounting/accvoucherentry/vnextno')}}",
                type: 'post',
                data: dataSet,
                success: function (data) {
                    $('#voucherNo').val(data);
                }
            });
        }

        function accVoucherEntry() {
            if(totalCr() != totalDr()){
                alert('Total Cr Dr not same.');
            }
        }


        $(document).ready(function () {
            $('#submitBtn').hover(function () {
                /*if(amtDrCr(1) != amtDrCr(2)){
                 alert('DR amount CR amount not same.');
                 }else if(amtDrCr(1)== '' || amtDrCr(2) == ''){
                 alert('DR amount CR amount empty.');
                 }*/
            });

            $('#accVoucherEntry').submit(function (e) {
                //checking for the voucher entry date are in to the financial year
                var  start = stringToDate('<?php echo $f_date['start']?>', 'yyyy-mm-dd', '-');
                var  end = stringToDate('<?php echo $f_date['end']?>', 'yyyy-mm-dd', '-');
                var  value = stringToDate($('#voucherData').val(), 'dd-mm-yyyy', '-');
                var diff1 = (value - start) / (8.64e7);
                var diff2 = (end - value) / (8.64e7);
                if(diff1 < 0){alert('date is Earlier then financial start date');}
                if(diff2 < 0){alert('date is after then financial end date');}

                var token = "{{ csrf_token() }}";
                var dataSet = '_token='+token+'&'+$(this).serialize();
                if(amtDrCr(1)== '' || amtDrCr(2) == ''){
                    alert('DR amount CR amount empty.');
                }else if(amtDrCr(1) != amtDrCr(2)){
                    alert('DR amount CR amount not same.');
                }else{
                    $.ajax({
                        url: "{{ url('accounting/accvoucherentry')}}",
                        type: 'post',
                        data: dataSet,
                        success: function (data) {
                            if(data == 1){
                                alert('Success');
                                window.location.href = '{{ url('accounting/accvoucherentry/add')}}';
                            }
                        }
                    });
                }
                e.preventDefault();
            });
        });

        $('#voucherData').blur(function (){
            if($(this).val() !=''){
                var  start = stringToDate('<?php echo $f_date['start']?>', 'yyyy-mm-dd', '-');
                var  end = stringToDate('<?php echo $f_date['end']?>', 'yyyy-mm-dd', '-');
                var  value = stringToDate($('#voucherData').val(), 'dd-mm-yyyy', '-');
                var diff1 = (value - start) / (8.64e7);
                var diff2 = (end - value) / (8.64e7);
                if(diff1 < 0){alert('date is Earlier then financial start date');}
                if(diff2 < 0){alert('date is after then financial end date');}
            }
        });

        function stringToDate(_date,_format,_delimiter)
        {
            var formatLowerCase=_format.toLowerCase();
            var formatItems=formatLowerCase.split(_delimiter);
            var dateItems=_date.split(_delimiter);
            var monthIndex=formatItems.indexOf("mm");
            var dayIndex=formatItems.indexOf("dd");
            var yearIndex=formatItems.indexOf("yyyy");
            var month=parseInt(dateItems[monthIndex]);
            month-=1;
            var formatedDate = new Date(dateItems[yearIndex],month,dateItems[dayIndex]);
            return formatedDate;
        }
    </script>
@endsection