@extends('layouts.master')

@section('content')


    <style>
        .wrapper {
            background: #fff !important;
        }
        .menu-section {
            margin: 0px;
        }
        .ds-menus {
            background: #fff;
            font-size: 16px;
            border-radius: 10px;
            border-left: 20px solid #4aad3f;
            float: left;
            text-align: left;
            padding: 12px;
            margin: 10px;
            border-right: 1px solid #efefef;
            border-bottom: 1px solid #efefef;
            border-top: 1px solid #efefef;
        }
        .ds-menus:hover {
            background: #efefef !important;
            color: #fff;
            font-size: 16px;
        }
        .heading-section {
            margin: 0px;
            font-size: 18px;
            font-weight: bold;
            color: #1BC152;
        }
        .desc-section {
            margin: 0px;
            font-size: 16px;
            color: #1BC152;
        }
        .page-title-header {
            padding-bottom: 10px;
            border-bottom: 1px solid #efefef;
        }


    </style>
    <section class="content-header">
        <h1 class="page-title-header">
            Accounting Report
        </h1>
        <ul class="breadcrumb">
            <li><a href="{{URL::to('home')}}"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="{{URL::to('finance')}}">Finance</a></li>
            <li><a href="{{URL::to('accounting/accreport')}}">Report</a></li>
        </ul>
    </section>


    <div class="col-md-12">

        <div class="row text-center menu-section">
            <div class="col-md-12 col-md-offset-2">


                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('accounting/accreport/accdailybook')}}">
                        <p class="heading-section">Day Book</p>
                        <p class="desc-section">View day book transaction</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('accounting/accreport/accledgerbook')}}">
                        <p class="heading-section">Ledger Book</p>
                        <p class="desc-section">View ledger book transaction </p>
                    </a>
                </div>


                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('accounting/accreport/accreceivepayment')}}">
                        <p class="heading-section">Receiver & Payment</p>
                        <p class="desc-section">View all receive and payment amount</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('accounting/accreport/acctrialbalance')}}">
                        <p class="heading-section">Trail Balance</p>
                        <p class="desc-section">View trail balance sheet <en></en>try</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('accounting/accreport/accbalancesheet')}}">
                        <p class="heading-section">Blance Sheet</p>
                        <p class="desc-section">View Balance sheet<en></en>try</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('accounting/accreport/accprofitloss')}}">
                        <p class="heading-section">Profit and Loss</p>
                        <p class="desc-section">View all profile and loss <en></en>try</p>
                    </a>
                </div>



            </div>


        </div>

    </div>



@stop

{{-- Scripts --}}

