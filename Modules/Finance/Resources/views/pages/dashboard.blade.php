@extends('finance::layouts.master')
@section('section-title')
    <h1>
        <i class="fa fa-search"></i>Finance
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/library/default/index">Finacne</a></li>
        <li class="active">Manage Account</li>
    </ul>
@endsection
<!-- page content -->
@section('page-content')
    <div class="box box-body">
            <div class="alert alert-success" role="alert">
                Account "school001" activated.</div>



            <style type="text/css">
                .text-medium{ font-size: 30px; font-weight: 600; }
                li.innerTB{ padding-top: 10px !important ; padding-bottom: 10px !important ; }
            </style>


            <div class="row">
                <div class="col-md-4">
                    <div class="widget widget-body-white widget-heading-simple  padding-none">
                        <div class="widget-body padding-none">
                            <h4 class="heading innerAll text-primary" style="padding-bottom:5px;">Account Details</h4>
                            <div class="bg-gray innerAll" style="padding-top:0px;">
                                <div class="">
                                    <ul class="list-unstyled strong innerAll" style="padding-top:0px; padding-left: 0px;">
                                        <li class="border-bottom innerTB half">Account Name : school001</li>
                                        <li class="border-bottom innerTB half">Financial Year : 31-12-2018 - 31-12-2019</li>
                                        <li class="border-bottom innerTB half">Contact Person : Chanduail School </li>
                                        <li class="border-bottom innerTB half">Email : chanduail@gmail.com</li>
                                        <li class="innerTB half">
                                            Account Status :
                                            Unlocked                                                        </li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-3">
                    <div class="well text-center margin-none">
                        <p class="strong">Assets</p>
                        <strong class="text-medium text-primary">Dr 700.00</strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="well text-center margin-none">
                        <p class="strong">Liabilities and Owners Equity</p>
                        <strong class="text-medium text-primary">0.00</strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="well text-center margin-none">
                        <p class="strong">Income</p>
                        <strong class="text-medium text-primary">Cr 700.00</strong>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="well text-center margin-none">
                        <p class="strong">Expense</p>
                        <strong class="text-medium text-primary">0.00</strong>
                    </div>
                </div>

            </div>

    </div>
@endsection
@section('page-script')
@endsection