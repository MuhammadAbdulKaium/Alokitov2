<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 9/14/17
 * Time: 12:07 PM
 */
?>
@extends('layouts.master')
@section('content')
    <script src="http://www.chartjs.org/dist/2.7.0/Chart.bundle.js"></script>
    <script src="http://www.chartjs.org/samples/latest/utils.js"></script>
    <style>
        canvas {
            -moz-user-select: none;
            -webkit-user-select: none;
            -ms-user-select: none;
        }
    </style>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-eye" aria-hidden="true"></i> Accounting
            </h1>
            <ul class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{url('/accounting')}}">Accounting</a></li>
            </ul>
        </section>
        <section class="content">
            {{--//////////////////////////////////////////////////////////////////////////////--}}
            <div class="callout callout-info show msg-of-day" style="background: #2c3e50 !important;">
                <h4><i class="fa fa-bullhorn"></i> Message of The Day</h4>
                <marquee onmouseout="this.setAttribute('scrollamount', 6, 0);" onmouseover="this.setAttribute('scrollamount', 0, 0);" scrollamount="6" behavior="scroll" direction="left">Life is an adventure in forgiveness.</marquee>
            </div>

            <div class="row">
                {{--<div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua bg-midblack">
                        <div class="inner">
                            <h3>Financial</h3>
                            <p>Year</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{url('accounting/accfyear')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>--}}
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>Ledger</h3>
                            <p>Group Add</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{url('accounting/acchead')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>Bank</h3>
                            <p>Add</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="{{url('accounting/accbank')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                {{--<div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>Voucher</h3>
                            <p>Type</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="{{url('accounting/accvouchertype')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>--}}
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-aqua bg-midblack">
                        <div class="inner">
                            <h3>Voucher</h3>
                            <p>Entry</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="{{url('accounting/accvoucherentry/add')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-green">
                        <div class="inner">
                            <h3>Fees</h3>
                            <p>Collection</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="{{url('accounting/accfeescollection')}}" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                {{--<div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-yellow">
                        <div class="inner">
                            <h3>General</h3>
                            <p>Settings</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-3 col-xs-6">
                    <!-- small box -->
                    <div class="small-box bg-red">
                        <div class="inner">
                            <h3>65</h3>
                            <p>Social Learning</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
                    </div>
                </div>--}}
                <!-- ./col -->
            </div>
{{--//////////////////////////////////////////////////////////////////////////////--}}
            {{--'accHeadsData','assetData','liabilityData','incData','expData'--}}
            <div class="row">
                <div class="col-md-6">
                    <div class="canvas-holder">
                        <canvas id="asset-area" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="canvas-holder">
                        <canvas id="liability-area" />
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="canvas-holder">
                        <canvas id="inc-area" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="canvas-holder">
                        <canvas id="exp-area" />
                    </div>
                </div>
            </div>
            {{--//////////////////////////////////////////////////////////////////////////////--}}

        </section>
    </div>

    <script>
        /*////////////////////////////////////////////////////////*/
        var assetData = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        @foreach ($assetData as $data)
                            @if(!empty($data['totalAmt']))
                            {{$data['totalAmt']}},
                        @endif
                        @endforeach
                    ],
                    backgroundColor: [
                        window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,window.chartColors.red, window.chartColors.orange,
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    @foreach ($assetData as $data)
                        @if(!empty($data['totalAmt']))
                        "{{ucfirst($data['name'].': '.$data['totalAmt'])}}",
                    @endif
                    @endforeach
                ]
            },
            options: {
                responsive: true,
                legend: { position: 'left', },
                title: { display: true, text: 'Asset: {{$accHeadsData[0]['totalAmt']}}' },
                animation: { animateScale: true, animateRotate: true}
            }
        };

        var liabilityData = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        @foreach ($liabilityData as $data)
                        @if(!empty($data['totalAmt']))
                        {{$data['totalAmt']}},
                        @endif
                        @endforeach
                    ],
                    backgroundColor: [
                        window.chartColors.red, window.chartColors.orange, window.chartColors.yellow,window.chartColors.red, window.chartColors.orange, window.chartColors.yellow,window.chartColors.red, window.chartColors.orange, window.chartColors.yellow,window.chartColors.red, window.chartColors.orange, window.chartColors.yellow,window.chartColors.red, window.chartColors.orange, window.chartColors.yellow,window.chartColors.red, window.chartColors.orange, window.chartColors.yellow,window.chartColors.red, window.chartColors.orange, window.chartColors.yellow,window.chartColors.red, window.chartColors.orange, window.chartColors.yellow,window.chartColors.red, window.chartColors.orange, window.chartColors.yellow,window.chartColors.red, window.chartColors.orange, window.chartColors.yellow,window.chartColors.red, window.chartColors.orange, window.chartColors.yellow,window.chartColors.red, window.chartColors.orange, window.chartColors.yellow,
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    @foreach ($liabilityData as $data)
                            @if(!empty($data['totalAmt']))
                            "{{ucfirst($data['name']).': '.$data['totalAmt']}}",
                            @endif
                    @endforeach
                ]
            },
            options: {
                responsive: true,
                legend: { position: 'left', },
                title: { display: true, text: 'Liability: {{$accHeadsData[1]['totalAmt']}}' },
                animation: { animateScale: true, animateRotate: true}
            }
        };

        var incData = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        @foreach ($incData as $data)
                            @if(!empty($data['totalAmt']))
                                {{$data['totalAmt']}},
                            @endif
                        @endforeach
                    ],
                    backgroundColor: [
                        window.chartColors.green, window.chartColors.blue, window.chartColors.blue,window.chartColors.green, window.chartColors.blue, window.chartColors.blue,window.chartColors.green, window.chartColors.blue, window.chartColors.blue,window.chartColors.green, window.chartColors.blue, window.chartColors.blue,window.chartColors.green, window.chartColors.blue, window.chartColors.blue,window.chartColors.green, window.chartColors.blue, window.chartColors.blue,window.chartColors.green, window.chartColors.blue, window.chartColors.blue,window.chartColors.green, window.chartColors.blue, window.chartColors.blue,window.chartColors.green, window.chartColors.blue, window.chartColors.blue,
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    @foreach ($incData as $data)
                            @if(!empty($data['totalAmt']))
                            "{{ucfirst($data['name']).': '.$data['totalAmt']}}",
                            @endif
                    @endforeach
                ]
            },
            options: {
                responsive: true,
                legend: { position: 'left', },
                title: { display: true, text: 'Income: {{$accHeadsData[2]['totalAmt']}}' },
                animation: { animateScale: true, animateRotate: true}
            }
        };

        var expData = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        @foreach ($expData as $data)
                            @if(!empty($data['totalAmt']))
                            {{$data['totalAmt']}},
                            @endif
                        @endforeach
                    ],
                    backgroundColor: [
                        window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,window.chartColors.red, window.chartColors.blue,
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    @foreach ($expData as $data)
                        @if(!empty($data['totalAmt']))
                        "{{ucfirst($data['name']).': '.$data['totalAmt']}}",
                        @endif
                    @endforeach
                ]
            },
            options: {
                responsive: true,
                legend: { position: 'left', },
                title: { display: true, text: 'Expense: {{$accHeadsData[3]['totalAmt']}}' },
                animation: { animateScale: true, animateRotate: true}
            }
        };

        window.onload = function() {
            var asset = document.getElementById("asset-area").getContext("2d");
            window.myPie = new Chart(asset, assetData);

            var liability = document.getElementById("liability-area").getContext("2d");
            window.myPie = new Chart(liability, liabilityData);

            var inc = document.getElementById("inc-area").getContext("2d");
            window.myPie = new Chart(inc, incData);

            var exp = document.getElementById("exp-area").getContext("2d");
            window.myPie = new Chart(exp, expData);

        };
    </script>
@endsection