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
            Accounting
        </h1>
        <ul class="breadcrumb">
            <li><a href="{{URL::to('home')}}"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="{{URL::to('finance')}}">Finance</a></li>
            <li><a href="{{URL::to('accounting')}}">Accounting</a></li>
        </ul>
    </section>


    <div class="col-md-12">

        <div class="row text-center menu-section">
            <div class="col-md-12 col-md-offset-2">


                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URL::to('accounting/accfyear')}}">
                        <p class="heading-section">Financial Year</p>
                        <p class="desc-section">Create a financial year</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('accounting/acchead')}}">
                        <p class="heading-section">Ledger and Group</p>
                        <p class="desc-section">Create ledger and group head </p>
                    </a>
                </div>


                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URL::to('accounting/accvouchertype')}}">
                        <p class="heading-section">Voucher Type</p>
                        <p class="desc-section">Voucher type create</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URL::to('accounting/accvoucherentry/add')}}">
                        <p class="heading-section">Voucher Entry</p>
                        <p class="desc-section">Voucher entry section <en></en>try</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URL::to('accounting/accvoucherentry')}}">
                        <p class="heading-section">List of Voucher</p>
                        <p class="desc-section">All voucher <en></en>try</p>
                    </a>
                </div>



            </div>


        </div>

    </div>



@stop

{{-- Scripts --}}

