<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 5/8/17
 * Time: 12:02 PM
 */
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
                <li><a href="#"> Profit and Loss </a></li>
            </ul>
        </section>
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Profit and Loss</h3>
                            </div>
                            <div class="box-body table-responsive">
                                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                                    <div class="margin-r-5 pull-right"><a class="btn btn-info btn-sm" onclick="excelDownload()">Excel Download</a></div>
                                    <div id="w1" class="grid-view">
                                        @if($accHeads->count()>0)
                                        <table id="myTable" class="table table-striped table-bordered">
                                            {{--<thead>
                                            <tr>
                                                <th></th>
                                                <th></th>
                                            </tr>
                                            </thead>--}}
                                            <tbody>
                                            {{--@php $i=1 @endphp--}}
                                            @foreach($accHeads as $accHead)
                                                <tr style="font-weight: 600" >
                                                    {{--<td>{{ $i++ }}</td>--}}
                                                    <td>{{$accHead->chart_name}}</td>
                                                    <td style="text-align: right">{{ abs($accHead->sumCalc($accHead->id)) }}</td>
                                                </tr>
                                                @if(count($accHead->childs))
                                                    @include('accounting::pages.accReport.manageChild',['childs' => $accHead->childs])
                                                @endif
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr style="font-weight: 600">
                                                @php
                                                    $profitLoss = abs($accHead->sumCalc($incomeHeadId)) - abs($accHead->sumCalc($expenseHeadId));
                                                @endphp
                                                <td style="text-align: right">
                                                    @if($profitLoss >= 0){{'Net Profit'}}
                                                    @else {{'Net Loss'}}
                                                    @endif
                                                </td>
                                                <td style="text-align: right">{{abs($profitLoss)}}</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                        @else

                                            No Record Found

                                        @endif
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
            window.location.href = '{{url('accounting/accreport/accprofitlossexcel/')}}';
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