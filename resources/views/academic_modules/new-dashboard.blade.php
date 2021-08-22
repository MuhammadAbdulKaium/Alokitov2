@extends('layouts.master')

{{-- Content --}}

@section('styles')

    <style>

    .dashboard-panel {
        text-align:  center;
        padding:  10px;
        font-size: 20px;
        box-shadow: 0 0 10px #48b04f;
        /*border-radius: 5px;*/
    }
        .dashboard-panel i {
            font-size: 35px;
        }

    .dashboard-panel a:hover {
        color:#fff !important;
    }
    .dashboard-panel:hover {
        background: #48b04f;
        transition: all .4s ease;
        -webkit-transition: all .4s ease;
        color:#fff !important;
    }

        .heading-modules {
            background: #48b04f;
            color: #FFF;
            font-size: 20px;
        }


    .label-head {
        font-size: 20px;
    }

    .info-box-number {
        font-size: 20px;
    }

    </style>


@endsection

@section('content')

    <section class="breadcrumb-bg">
        <div class="container-fluid">
            <div class="col-md-6">
                <h5 class="text-color-white"><i class="fa fa-bullhorn icon-margin"></i> Message of the day:</h5>
            </div>
        </div>
    </section>

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
            border-radius: 10px;
            padding: 2px;
            font-size: 16px;
            font-weight: 600;
        }
        .ds-menus:hover {
            background: #6EFA9D !important;
            color: #fff !important;
            font-size: 16px;
            font-weight: 600;
        }
        .padding5px {
            padding: 5px;
        }
        .ds-menu-icon {
            padding: 5px;
        }

    </style>

    <div class="col-md-12">

        <div class="row text-center menu-section">

            <div class=" col-md-2 padding5px ">
                <a href="{{URL::to('academics')}}">
                <div class="counter ds-menus">
                  <img class="ds-menu-icon" src="{{URL::to('assets/menu/graduation-cap.png')}}">
                    <p class="count-text ">Academics</p>
                </div>
                </a>
            </div>

            <div class=" col-md-2 padding5px ">
                <a href="{{URL::to('academics/manage/attendance/manage')}}">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/attendance.png')}}">
                        <p class="count-text ">Attendance</p>
                    </div>
                </a>
            </div>


            <div class=" col-md-2 padding5px ">
                <a href="{{URL::to('communication/sms/group')}}">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/sms.png')}}">
                        <p class="count-text ">SMS</p>
                    </div>
                </a>
            </div>

            <div class=" col-md-2 padding5px ">
                <a href="{{URL::to('fees/menu')}}">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/fees.png')}}">
                        <p class="count-text ">Fees</p>
                    </div>
                </a>
            </div>


            <div class=" col-md-2 padding5px ">
                <a href="{{URL::to('communication/notice/')}}">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/notice.png')}}">
                        <p class="count-text ">Notice</p>
                    </div>
                </a>
            </div>


            <div class=" col-md-2 padding5px ">
                <a href="{{URl::to('hr-payroll')}}">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/human-resource.png')}}">
                        <p class="count-text ">HR & Payroll</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="row text-center menu-section">

            <div class=" col-md-2 padding5px ">
                <a href="{{URl::to('student/manage')}}">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/student.png')}}">
                        <p class="count-text ">Student Management</p>
                    </div>
                </a>
            </div>

            <div class=" col-md-2 padding5px ">
                <a href="#">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/online-admission.png')}}">
                        <p class="count-text ">Online Admission</p>
                    </div>
                </a>
            </div>

            <div class=" col-md-2 padding5px ">
                <a href="{{URL::to('/finance')}}">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/finance.png')}}">
                        <p class="count-text ">Finance</p>
                    </div>
                </a>
            </div>

            <div class=" col-md-2 padding5px ">
                <a href="#">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/communication.png')}}">
                        <p class="count-text ">Communication</p>
                    </div>
                </a>
            </div>


            <div class=" col-md-2 padding5px ">
                <a href="#">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/report.png')}}">
                        <p class="count-text ">Report and Printing</p>
                    </div>
                </a>
            </div>


            <div class=" col-md-2 padding5px ">
                <a href="#">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/library.png')}}">
                        <p class="count-text ">Library</p>
                    </div>
                </a>
            </div>
        </div>

        <div class="row text-center menu-section">

            <div class=" col-md-2 padding5px ">
                <a href="#">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/hostel.png')}}">
                        <p class="count-text ">Hostel</p>
                    </div>
                </a>
            </div>

            <div class=" col-md-2 padding5px ">
                <a href="#">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/transport.png')}}">
                        <p class="count-text ">Transport</p>
                    </div>
                </a>
            </div>



            <div class=" col-md-2 padding5px ">
                <a href="#">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/inventory.png')}}">
                        <p class="count-text ">Inventory</p>
                    </div>
                </a>
            </div>

            <div class=" col-md-2 padding5px ">
                <a href="#">
                    <div class="counter ds-menus">
                        <img class="ds-menu-icon" src="{{URL::to('assets/menu/setting.png')}}">
                        <p class="count-text ">Setting</p>
                    </div>
                </a>
            </div>

        </div>

    </div>






    <div class="modal" id="globalModal" tabindex="-1" role="dialog"  aria-labelledby="esModalLabel" aria-hidden="true">
        <div id="modal-dialog" class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                        </div>
                    </div>
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
