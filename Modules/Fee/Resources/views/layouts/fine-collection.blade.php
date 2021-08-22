@extends('layouts.master')

@section('styles')
    <link rel="stylesheet" type="text/css" href="https://www.jqueryscript.net/demo/jQuery-Based-Bootstrap-Popover-Enhancement-Plugin-Bootstrap-Popover-X/bootstrap-popover-x.css">
@endsection
<!-- page content -->
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-plus-square"></i>Fine Collection</h1>

            {{--<ul class="breadcrumb">--}}
                {{--<li><a href="{{URL::to('home')}}"><i class="fa fa-home"></i>Home</a></li>--}}
                {{--<li><a href="{{URL::to('finance')}}">Finance</a></li>--}}
                {{--<li><a href="{{URL::to('/fees')}}">Fees</a></li>--}}
            {{--</ul>--}}
        </section>
        <section class="content">
            {{--@if(Session::has('success'))--}}
            {{--<div class="alert-success alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">--}}
            {{--<button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>--}}
            {{--<h4><i class="icon fa fa-check"></i>{{ Session::get('success') }}</h4>--}}
            {{--</div>--}}
            {{--@elseif(Session::has('warning'))--}}
            {{--<div class="alert-warning alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">--}}
            {{--<button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>--}}
            {{--<h4><i class="icon fa fa-check"></i>{{ Session::get('warning') }}</h4>--}}
            {{--</div>--}}
            {{--@endif--}}
            <div class="panel panel-default">
                <div class="panel-body">
                    <div>
                        <ul class="nav-tabs margin-bottom nav" id="">
                            <li @if($page == "late-fine") class="active" @endif  id="#">
                                <a href="{{url('/fee/fine-collection/late-fine')}}">Late Fine </a>
                            </li>

                            <li @if($page == "absent-fine") class="active" @endif  id="#">
                                <a href="{{url('/fee/fine-collection/absent-fine')}}">Absent Fine</a>
                            </li>
                        </ul>
                        <!-- page content div -->
                        @yield('page-content')
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- global modal -->
    <div aria-hidden="true" aria-labelledby="esModalLabel" class="modal" id="globalModal" role="dialog" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw">
                            </i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script src="{{URL::asset('js/charts/chart.min.js')}}"></script>
    <script src="{{URL::asset('js/tokenInput.js')}}"></script>
    <script src="https://www.jqueryscript.net/demo/jQuery-Based-Bootstrap-Popover-Enhancement-Plugin-Bootstrap-Popover-X/bootstrap-popover-x.js"></script>
    <script>
        $(document).ready(function () {
            $.ajaxSetup(
                {
                    headers:
                        {
                            'X-CSRF-Token': $('input[name="_token"]').val()
                        }
                });
        })
    </script>
    @yield('page-script')

@endsection
