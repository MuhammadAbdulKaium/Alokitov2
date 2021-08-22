@extends('layouts.master')
@section('content')
    <style>
        .box-solid {
            padding: 10px;
        }
             /************** TABLES ***************/

         .table-top {
             vertical-align: top;
         }

        .width-50 {
            width: 40%;
        }

        table {
            width: 100%;
        }

        th {
            border-bottom: 2px solid #555;
            text-align: left;
            font-size: 16px;
        }

        th a {
            display: block;
            padding: 2px 4px;
            text-decoration: none;
        }

        th a.asc:after {
            content: ' â‡£';
        }

        th a.desc:after {
            content: ' â‡¡';
        }

        table tr td {
            padding: 4px;
            text-align: left;
        }

        table.stripped tr td {
            border-bottom:1px solid #DDDDDD;
            border-top:1px solid #DDDDDD;
        }

        table.stripped tr:hover {
            background-color: #FFFF99;
        }

        table.stripped .tr-ledger {

        }

        table.stripped .tr-group {
            font-weight: bold;
        }

        table.extra tr td {
            padding: 6px;
        }

        table.stripped .tr-root-group {
            background-color: #F3F3F3;
            color: #754719;
        }
    </style>
    <div class="content-wrapper">
        <section class="content-header">
            @yield('section-title')
        </section>
        <section class="content">
            @if(Session::has('insert'))
                <div class="alert-success alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">
                    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i>{{ Session::get('insert') }}</h4>
                </div>
            @elseif(Session::has('update'))
                <div class="alert-success alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">
                    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i>{{ Session::get('update') }}</h4>
                </div>

            @elseif(Session::has('warning'))
                <div class="alert-warning alert-auto-hide alert fade in" id="w0-success-0" style="opacity: 423.642;">
                    <button aria-hidden="true" class="close" data-dismiss="alert" type="button">×</button>
                    <h4><i class="icon fa fa-check"></i>{{ Session::get('warning') }}</h4>
                </div>
        @endif


        @yield('page-content')

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
{{--    <script src="{{URL::asset('js/bootstrap-datetimepicker.min.js')}}"></script>--}}
  @yield('page-script')

@endsection
