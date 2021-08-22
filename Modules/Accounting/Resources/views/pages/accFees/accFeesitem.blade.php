<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 9/16/17
 * Time: 5:25 PM
 */
$myUrl = '/fees/fees_item_collection/';
?>

@extends('layouts.master')
@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-eye" aria-hidden="true"></i> Accounting
            </h1>
            <ul class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{url('accounting')}}">Accounting</a></li>
                <li><a href="#">Fees Items Collection</a></li>
            </ul>
        </section>

        <section class="content">
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('alert'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">{{--<i class="fa fa-search">--}}</i>Fees Item Collection </h3>
                    {{--<a class="btn btn-success btn-sm pull-right" href="/payroll/salary-structure/add" oncontextmenu="return false;" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        Today's Fees Collection</a>--}}
                </div>
                <div class="box-body table-responsive">
                    <div class="box-header"></div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-8">
                                <form id="salaryStructure-create-form" action="{{url('accounting/accfeesitemcollection')}}" method="POST">
                                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="errorTxt"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>Start Date</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input readonly type="text" name="start_date" id="start_date" placeholder="Start Date" class="form-control pull-right" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label>End Date</label>
                                                <div class="input-group date">
                                                    <div class="input-group-addon">
                                                        <i class="fa fa-calendar"></i>
                                                    </div>
                                                    <input readonly type="text" name="end_date" id="end_date" placeholder="End Date" class="form-control pull-right" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label">&nbsp;</label><br>
                                                <a class="btn btn-danger" class="form-control" id="clearDate"> Clear </a>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="form-group">
                                                <label class="control-label">&nbsp;</label><br>
                                                <a class="btn btn-info" class="form-control" id="requestFees"> Find </a>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="returnedData">

                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
            </div>
        </section>

        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content" id="modal-content">
                    <div class="modal-body" id="modal-body">
                        <div class="loader">
                            <div class="es-spinner">
                                <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <!-- bootstrap datepicker -->
    <script src="{{URL::asset('js/bootstrap-datepicker.js')}}"></script>
    <!-- DataTables -->
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <!-- datatable script -->
    <script>
        $(function () {
            $('#end_date, #start_date').datepicker({
                autoclose: true
            });
        });
        $(document).ready(function (){
            $('#clearDate').click(function (){
                /*$('#start_date').val('')
                $('#end_date').val('')*/
                location.reload();
            });
            $('#requestFees').click(function (){
//                alert(11);

                var start_date = $('#start_date').val().split('/');
                start_date = start_date[2]+'-'+start_date[0]+'-'+start_date[1];
                var end_date = $('#end_date').val().split('/');
                end_date = end_date[2]+'-'+end_date[0]+'-'+end_date[1];

                {{--var fees = encodeURIComponent($('#selFees').val());--}}
                {{--var feesName = $('#selFees option[value="'+fees+'"]').text();--}}
                {{--var token = "{{ csrf_token() }}";--}}
                {{--var dataSet = '_token='+token+'&fees='+fees+'&start_date='+start_date+'&end_date='+end_date;--}}
                {{--var url = '{{$myUrl}}'+fees+'/'+start_date+'/'+end_date;--}}

                var url = '{{$myUrl}}'+start_date+'/'+end_date;
                $.ajax({
                    url: url,
                    type: 'get',
                    beforeSend: function (){
                    }, success: function (data){
                        var grandTotal = data['total_fees_paid'];
//                        console.log(grandTotal);
                        if(grandTotal> 0){

                            var grandTotal = data['total_fees_paid'];
//                            alert(grandTotal);
                            txt = '<br><table id="result">'+
                                '<tr><td colspan="2">' +
                                'Date: <br><strong>' +start_date+' </strong>'+
                                'To <strong>' +end_date+' </strong> <br><br>'+
                                '</td></tr>'+
//                                    '<tr><th>Items</th><th>Amount</th></tr>'+
                                '<tr><td>Total</td><td>'+ grandTotal +'<input type="hidden" name="total" value="'+grandTotal+'"></td></tr>'+
//                                    '<tr><td>Tution Fees</td><td>'+ fees_amount1 +'<input type="hidden" name="fees_amount1" value="'+fees_amount1+'"><input type="hidden" name="fees_id_1" value="'+fees_id_1+'"></td></tr>'+
//                                    '<tr><td>Other Amount</td><td>'+ fees_amount2 +'<input type="hidden" name="fees_amount2" value="'+fees_amount2+'"><input type="hidden" name="fees_id_2" value="'+fees_id_2+'"></td></tr>'+
//                                    '<tr><td>Admission Fees</td><td>'+ fees_amount3 +'<input type="hidden" name="fees_amount3" value="'+fees_amount3+'"><input type="hidden" name="fees_id_3" value="'+fees_id_3+'"></td></tr>'+
//                                    '<tr><td>Certification Fees</td><td>'+ fees_amount4 +'<input type="hidden" name="fees_amount4" value="'+fees_amount4+'"><input type="hidden" name="fees_id_4" value="'+fees_id_4+'"></td></tr>'+
//                                    '<tr><td>Fine Fees</td><td>'+ fees_amount5 +'<input type="hidden" name="fees_amount5" value="'+fees_amount5+'"><input type="hidden" name="fees_id_5" value="'+fees_id_5+'"></td></tr>'+
//                                    '<tr><td>Discount</td><td>'+ discount +'<input type="hidden" name="discount" value="'+discount+'"></td></tr>'+
//                                    '<tr><td>Due Fine Amount</td><td>'+ due_fine +'<input type="hidden" name="due_fine" value="'+due_fine+'"><input type="hidden" name="fees_id_6" value="'+fees_id_6+'"></td></tr>'+
//                                    '<tr><td>Attendance Fine Amount</td><td>'+ fees_amount7 +'<input type="hidden" name="fees_amount7" value="'+fees_amount7+'"><input type="hidden" name="fees_id_7" value="'+fees_id_7+'"></td></tr>'+
                                '</table><br>'+
                            '<div class="row"><div class="col-sm-12"><button type="submit" class="btn btn-info pull-left" id="save"> Save</button></div></div>';
                            $('#returnedData').html(txt);
                            /*$('#returnedData').html(txt
                             /!*for(var i=0; i<=data.length; i++){
                             data[i]
                             }*!/

                             //'<p>Collected fees '+ feesName +': '+ data.amount +'</p>'+
                             //'<input type="hidden" name="feesCode" value="'+data.fees+'">'+
                             //'<br>'+
                             //'<input type="hidden" name="feesAmount" value="'+data.amount+'">'
                             );*/
                        }else{
                            $('#returnedData').html('No Data Found<br>');
                        }
                    }

                });
            });
        });
    </script>
    <style>
        #result table, th, td{
            border: 0px solid black;
        }
        #result {
            width: 500px;
            font-size: 18px;
        }
    </style>
@endsection

