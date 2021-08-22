@extends('layouts.master')
<!-- page content -->
@section('content')
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
    <script src="{{URL::asset('js/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function(){

            @yield('page-script')
        });
    </script>

@endsection
