
@extends('layouts.master')


@section('styles')
    <!-- DataTables -->
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>

@endsection

@section('content')

    {{--batch string--}}
    @php $batchString="Class"; @endphp

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>Device Attendance</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/student/">Cadet</a></li>
                <li class="active">Device Attendancet</li>
            </ul>
        </section>
        <section class="content">
            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Search Cadet Device Attendance</h3>
                </div>
                <form autocomplete="off" id="attendanceSearchForm" method="POST">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <input type="hidden" name="user_type" value="s">
                    <div class="box-body">
                        <div class="row">

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="name">Start Date</label>
                                    <input type='text' name="start_date" class="form-control" id='start_date' />
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="end_date">End Date</label>
                                    <input type='text' name="end_date" class="form-control" id='end_date' />
                                </div>
                            </div>

                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="name">Start Date Time</label>
                                    <div class='input-group date' id="start_time">
                                        <input required name="start_time"   type='text' class="form-control" />
                                        <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                       </div>
                                   </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label class="control-label" for="name">End Date Time</label>
                                    <div class='input-group date' id="end_time">
                                        <input required name="end_time"   type='text' class="form-control" />
                                        <span class="input-group-addon">
                                        <span class="glyphicon glyphicon-time"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ./box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info pull-right">Search</button>
                        <button type="reset" class="btn btn-default">Reset</button>
                    </div>
                </form>
            </div>
            {{--std list container--}}
            <div id="attendance-table-container" class="box box-solid">
                @if(Session::has('success'))
                    <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                    </div>
                @elseif(Session::has('alert'))
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
                    </div>
                @elseif(Session::has('warning'))
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                    </div>
                @endif
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
    <!-- DataTables -->
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>

    <script src="{{ URL::asset('js/moment.min.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <!-- datatable script -->

    <script>
        $(document).ready(function(){
            $('#start_date').datetimepicker({  format: 'DD-MM-YYYY' });
            $('#start_time').datetimepicker({  format: 'LT'});
            $('#end_date').datetimepicker({  format: 'DD-MM-YYYY' });
            $('#end_time').datetimepicker({  format: 'LT'});

        // request for section list using batch id
        $('form#attendanceSearchForm').on('submit', function (e) {
            // alert('hello');
            e.preventDefault();

            // checking start and end date
            if($('#start_date').val() && $('#end_date').val()){
                // ajax request
                $.ajax({
                    url: '/employee/employee-attendance/custom',
                    type: 'POST',
                    cache: false,
                    data: $('form#attendanceSearchForm').serialize(),
                    datatype: 'html',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // att-box show
                        $('#att-box').removeClass('hide');
                        $('#attendance-table-container').html('');
                        $('#attendance-table-container').html(data);

                    },
                    error:function(){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            }else{
                // sweet alert
                swal("Warning", 'Please Check all inputs are selected', "Warning");
            }
        });

        });


    </script>

@endsection
