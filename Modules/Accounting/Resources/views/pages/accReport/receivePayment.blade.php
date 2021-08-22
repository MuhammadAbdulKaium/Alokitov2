<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 5/11/17
 * Time: 4:45 PM
 */
use Modules\Accounting\Entities\AccCharts;
?>
@extends('layouts.master')
<!-- bootstrap datepicker -->
<link rel="stylesheet" href="{{URL::asset('css/datepicker3.css')}}">
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-eye" aria-hidden="true"></i> Accounting Report
            </h1>
            <ul class="breadcrumb">
                <li><a href="{{url('/home')}}"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{url('/finance')}}"><i class="fa fa-home"></i>Finance</a></li>
                <li><a href="{{url('/accounting')}}">Accounting</a></li>
                <li><a href="{{url('/accounting/accreport')}}">Reports</a></li>
                <li><a href="#">Receipt and Payment Account</a></li>
            </ul>
        </section>
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Receipt and Payment Account</h3>
                            </div>

                            <div class="box-body table-responsive">
                                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                                    <div class="margin-r-5 pull-right"><a class="btn btn-info btn-sm" onclick="excelDownload()">Excel Download</a></div>

                                    <div id="w1" class="grid-view">
                                        <table id="myTable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center; width: 50%">Receipts</th>
                                                <th style="text-align: center; width: 50%">Payment</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php /*echo '<pre>';print_r($receiveTrns); echo '<pre>';*/?><!--
                                            --><?php /*echo '<pre>';print_r($paymentTrns); echo '<pre>';*/?>
                                            <tr>
                                                <td>
                                                    <table class="table table-bordered">
                                                        @php
                                                        $receive = 0;
                                                        $payment = 0;
                                                        $receiveCount = count($receiveTrns);
                                                        $paymentCount = count($paymentTrns) + 1;
                                                        $count = $paymentCount - $receiveCount;

                                                        @endphp
                                                        @foreach($receiveTrns as $receiveTrn)
                                                            <tr>
                                                                <td style="text-align: left; width: 80%">
                                                                    @php
                                                                        $a = AccCharts::where('id',$receiveTrn->acc_charts_id)->get(['chart_name']);
                                                                        $receive = $receive + $receiveTrn->amount;
                                                                    @endphp
                                                                    @if(count($a)>0)
                                                                    {{$a[0]->chart_name}}
                                                                    @endif
                                                                </td>
                                                                <td style="text-align: right; width: 20%">{{$receiveTrn->amount}}</td>
                                                            </tr>
                                                        @endforeach
                                                        @if($count >0)
                                                            @for($i=1;$i<=abs($count);$i++)
                                                            <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                                            @endfor
                                                        @endif
                                                        <tr>
                                                            <td style="text-align: left; width: 80%">Total</td>
                                                            <td style="text-align: right; width: 20%">{{$receive}}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table class="table table-bordered">
                                                        @foreach($paymentTrns as $paymentTrn)
                                                            <tr>
                                                                <td style="text-align: left; width: 80%">
                                                                    @php
                                                                        $a = AccCharts::where('id',$paymentTrn->acc_charts_id)->get(['chart_name']);
                                                                        $payment = $payment + $paymentTrn->amount;
                                                                    @endphp
                                                                    {{$a[0]->chart_name}}</td>
                                                                <td style="text-align: right; width: 20%">{{$paymentTrn->amount}}</td>
                                                            </tr>
                                                        @endforeach
                                                            @if($count < 0)
                                                                @for($i=1;$i<=abs($count);$i++)
                                                                    <tr><td>&nbsp;</td><td>&nbsp;</td></tr>
                                                                @endfor
                                                            @endif
                                                        <tr>
                                                            <td>Closing Balance</td>
                                                            <td  style="text-align: right; width: 20%">{{$receive - $payment}} </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="text-align: left; width: 80%">Total</td>
                                                            <td style="text-align: right; width: 20%">{{$receive}}</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                            </tbody>
                                        </table>





                                        {{--<table id="myTable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Account Head</th>
                                                <th style="text-align: center">Dr Total</th>
                                                <th style="text-align: center">Cr Total</th>
                                                <th style="text-align: center">Closing Balance</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $i=1 @endphp
                                            @foreach($accHeads as $accHead)
                                                <tr style="font-weight: 600" >
                                                    <td>{{ $i++ }}</td>
                                                    <td>{{$accHead->chart_name}}</td>
                                                    <td style="text-align: right">{{ abs($accHead->sumDrCalc($accHead->id)) }}</td>
                                                    <td style="text-align: right">{{ abs($accHead->sumCrCalc($accHead->id)) }}</td>
                                                    <td style="text-align: right">
                                                        {{ abs($accHead->sumCalc($accHead->id)) }}
                                                    </td>
                                                </tr>
                                                @if(count($accHead->childs))
                                                    @include('accounting::pages.accReport.manageChildTB',['childs' => $accHead->childs])
                                                @endif
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr style="font-weight: 600">
                                                <td  style="text-align: right"> Total</td>
                                                <td  style="text-align: right">
                                                    {{
                                                    abs($accHead->sumDrCalc(1)) +
                                                    abs($accHead->sumDrCalc(2)) +
                                                    abs($accHead->sumDrCalc(3)) +
                                                    abs($accHead->sumDrCalc(4))
                                                    }}
                                                </td>
                                                <td  style="text-align: right">{{
                                                    abs($accHead->sumCrCalc(1)) +
                                                    abs($accHead->sumCrCalc(2)) +
                                                    abs($accHead->sumCrCalc(3)) +
                                                    abs($accHead->sumCrCalc(4))
                                                    }}</td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            </tfoot>
                                        </table>--}}
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        {{--data will sit here--}}
    </div>

    <script type = "text/javascript">
        function excelDownload() {
            window.location.href = '{{url('accounting/accreport/accreceivepaymentexcel/')}}';
        }
    </script>

    <!-- jQuery 2.2.3 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <!-- <script src="../../plugins/jQuery/jquery-2.2.3.min.js"></script> -->
    <!-- Bootstrap 3.3.6 -->
    <script src="{{URL::asset('js/bootstrap.min.js')}}"></script>
    <!-- Select2 -->
    <script src="{{URL::asset('js/select2.full.min.js')}}"></script>
    <!-- InputMask -->
    <script src="{{URL::asset('js/jquery.inputmask.js')}}"></script>
    <script src="{{URL::asset('js/jquery.inputmask.date.extensions.js')}}"></script>

    <!-- bootstrap datepicker -->
    <script src="{{URL::asset('js/bootstrap-datepicker.js')}}"></script>

    <script>
        $(function () {
            //Date picker
            $('#datepicker').datepicker({
                autoclose: true
            });
        });

    </script>
@endsection