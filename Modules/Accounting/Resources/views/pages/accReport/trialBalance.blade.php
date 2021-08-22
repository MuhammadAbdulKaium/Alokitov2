<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 5/9/17
 * Time: 4:14 PM
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
                <li><a href="#">Trial Balance</a></li>
            </ul>
        </section>
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Trial Balance</h3>
                            </div>
                            <div class="box-body table-responsive">
                                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                                    <div class="margin-r-5 pull-right"><a class="btn btn-info btn-sm" onclick="excelDownload()">Excel Download</a></div>
                                    <div id="w1" class="grid-view">
                                        @if($accHeads->count()>0)
                                        <table id="myTable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th style="text-align: center">Account Head</th>
                                                <th style="text-align: center">Dr Total</th>
                                                <th style="text-align: center">Cr Total</th>
                                                <th style="text-align: center">Closing Balance</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @php $totalDr=0; $totalCr=0; @endphp
                                            @foreach($accHeads as $accHead)
                                                <tr style="font-weight: 600" >
                                                    {{--<td>{{ $i++ }}</td>--}}
                                                    <td>{{$accHead->chart_name}}</td>
                                                    <td style="text-align: right">{{ abs($accHead->sumDrCalc($accHead->id)) }}</td>
                                                    <td style="text-align: right">{{ abs($accHead->sumCrCalc($accHead->id)) }}</td>
                                                    <td style="text-align: right">{{ abs($accHead->sumCalc($accHead->id)) }}</td>
                                                </tr>
                                                @if(count($accHead->childs))
                                                    @include('accounting::pages.accReport.manageChildTB',['childs' => $accHead->childs])
                                                @endif
                                                @php $totalDr+=abs($accHead->sumDrCalc($accHead->id)) @endphp
                                                @php $totalCr+=abs($accHead->sumCrCalc($accHead->id)) @endphp
                                            @endforeach
                                            </tbody>
                                            <tfoot>
                                            <tr style="font-weight: 600">
                                                <td  style="text-align: right"> Total</td>
                                                <td  style="text-align: right">
                                                    {{$totalDr}}
                                                    {{--{{--}}
                                                    {{--abs($accHead->sumDrCalc(1)) +--}}
                                                    {{--abs($accHead->sumDrCalc(2)) +--}}
                                                    {{--abs($accHead->sumDrCalc(3)) +--}}
                                                    {{--abs($accHead->sumDrCalc(4))--}}
                                                    {{--}}--}}
                                                </td>
                                                <td  style="text-align: right">
                                              {{$totalCr}}</td>

                                                {{--{{--}}
                                                    {{--abs($accHead->sumCrCalc(1)) +--}}
                                                    {{--abs($accHead->sumCrCalc(2)) +--}}
                                                    {{--abs($accHead->sumCrCalc(3)) +--}}
                                                    {{--abs($accHead->sumCrCalc(4))--}}
                                                    {{--}}--}}
                                                    {{--</td>--}}
                                                <td></td>
                                                <td></td>
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
            window.location.href = '{{url('accounting/accreport/acctrialbalanceexcel/')}}';
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