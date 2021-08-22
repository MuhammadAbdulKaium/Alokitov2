@extends('layouts.master')

{{-- Content --}}

@section('styles')

    <style>

        .wrapper {
            background: #fff !important;
        }

    </style>


@endsection

@section('content')

    {{--<section class="breadcrumb-bg">--}}
    {{--<div class="container-fluid">--}}
    {{--<div class="col-md-6">--}}
    {{--<ul class="breadcrumb">--}}
    {{--<li><a href="#">Home</a></li>--}}
    {{--<li><a href="#">Student</a></li>--}}
    {{--<li class="active">Accessories</li>--}}
    {{--</ul>--}}
    {{--</div>--}}
    {{--<div class="col-md-6">--}}
    {{--<h5 class="text-color-white"><i class="fa fa-bullhorn icon-margin"></i> Message of the day:</h5>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</section><!--breadcrumb and todayes news End-->--}}
    {{--<div class="clearfix"></div>--}}


    {{--<div class="col-md-3 col-xs-6">--}}

        {{--<a href="#" class="btn btn-success btn-lg" role="button"><span class="glyphicon glyphicon-user"></span> <br>Users</a>--}}
    {{--</div>--}}

    <style>
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
            Finance
        </h1>
        <ul class="breadcrumb">
            <li><a href="{{URL::to('home')}}"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="{{URL::to('finance')}}">Finance</a></li>
        </ul>
    </section>


    <div class="col-md-12">

        <div class="row text-center menu-section">
        <div class="col-md-12 col-md-offset-2">


            <div class=" col-md-4 ds-menus ">
                <a class="ds-sub-menu" href="{{URl::to('fees/menu')}}">
                    <p class="heading-section">Fees</p>
                    <p class="desc-section">Fees Control</p>
                </a>
            </div>

            <div class=" col-md-4 ds-menus ">
                <a class="ds-sub-menu" href="{{URl::to('accounting')}}">
                    <p class="heading-section">Accounting</p>
                    <p class="desc-section">Accounting Configuration </p>
                </a>
            </div>


            <div class=" col-md-4 ds-menus ">
                <a class="ds-sub-menu" href="{{URl::to('accounting/accreport')}}">
                    <p class="heading-section">Accounting Report</p>
                    <p class="desc-section">View All Accounting Report</p>
                </a>
            </div>



        </div>


        </div>

    </div>


@stop

{{-- Scripts --}}


@section('scripts')
    <script src="{{URL::asset('template-2/js/chart.min.js')}}"></script>
    <script>
            // This use for DEMO page tab component.
            $('.menu .item').tab();
            // Add minus icon for collapse element which is open by default
            $(".collapse.in").each(function(){
                $(this).siblings(".panel-heading").find(".glyphicon").addClass("glyphicon-minus").removeClass("glyphicon-plus");
            });

            // Toggle plus minus icon on show hide of collapse element
            $(".collapse").on('show.bs.collapse', function(){
                $(this).parent().find(".glyphicon").removeClass("glyphicon-plus").addClass("glyphicon-minus");
            }).on('hide.bs.collapse', function(){
                $(this).parent().find(".glyphicon").removeClass("glyphicon-minus").addClass("glyphicon-plus");
            });
        });
    </script>

@stop
