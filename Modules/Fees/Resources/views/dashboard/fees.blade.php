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
            Fees
        </h1>
        <ul class="breadcrumb">
            <li><a href="{{URL::to('home')}}"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="{{URL::to('finance')}}">Finance</a></li>
            <li><a href="{{URL::to('/fees')}}">Fees</a></li>
        </ul>
    </section>


    <div class="col-md-12">

        <div class="row text-center menu-section">
            <div class="col-md-12 col-md-offset-2">


                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('fees/addfees')}}">
                        <p class="heading-section">Add Fees</p>
                        <p class="desc-section">Create student fees</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('accounting/accreport/accdailybook')}}">
                        <p class="heading-section">Fees List</p>
                        <p class="desc-section">View all fees list</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('fees/feesmanage')}}">
                        <p class="heading-section">Manage Fees</p>
                        <p class="desc-section">Fees manage and payer add </p>
                    </a>
                </div>


                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('fees/invoice')}}">
                        <p class="heading-section">Fees Invoice</p>
                        <p class="desc-section">View all invoice</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('fees/paymenttransaction')}}">
                        <p class="heading-section">Payment Transaction</p>
                        <p class="desc-section">View all payment transaction</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('fees/feestemplate')}}">
                        <p class="heading-section">Fees Template List</p>
                        <p class="desc-section">View fees template and manage</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('fees/feetype')}}">
                        <p class="heading-section">Fees Type</p>
                        <p class="desc-section">Create fees type </p>
                    </a>
                </div>


                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('fees/items')}}">
                        <p class="heading-section">Fees Head</p>
                        <p class="desc-section">Create fees head and view</p>
                    </a>
                </div>

                <div class=" col-md-4 ds-menus ">
                    <a class="ds-sub-menu" href="{{URl::to('fees/advance_payment')}}">
                        <p class="heading-section">Advance Payment</p>
                        <p class="desc-section">View all advance payment and add advance payment</p>
                    </a>
                </div>



            </div>


        </div>

    </div>



@stop

{{-- Scripts --}}

