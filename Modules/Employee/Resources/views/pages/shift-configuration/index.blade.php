@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Shift</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="#">Human Resource</a></li>
            <li><a href="#">Employee Management</a></li>
            <li class="active">Shift</li>
        </ul>
    </section>

    <section class="content">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-plus-square"></i>
                    {{ ($shiftConfiguration)?'Update Shift '.$shiftConfiguration->name:'Add New Shift' }}
                </h3>
                @if ($shiftConfiguration)
                <div class="box-tools">
                    <a class="btn btn-success" href="{{url('/employee/shift-configuration')}}" <i class="fa fa-plus-square"></i> Add</a></div>
                @endif 
            </div>
            <div class="box-body table-responsive">
                <div class="box-body">
                    <form action="{{ ($shiftConfiguration)?url('/employee/shift-configuration/update/'.$shiftConfiguration->id):url('/employee/shift-configuration/add') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="form-group" style="margin-top: 15px">
                                <div class="col-sm-1">
                                    <label for="">Shift Name:</label>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" class="form-control" name="name" value="{{ ($shiftConfiguration)?$shiftConfiguration->name:'' }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1">
                                    <label for="">Start Time:</label>
                                </div>
                                <div class="col-sm-3">
                                    <input type="time" class="form-control" name="start_time" value="{{ ($shiftConfiguration)?$shiftConfiguration->start_time:'' }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-1">
                                    <label for="">End Time:</label>
                                </div>
                                <div class="col-sm-3">
                                    <input type="time" class="form-control" name="end_time" value="{{ ($shiftConfiguration)?$shiftConfiguration->end_time:'' }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="row" style="margin-top: 20px">
                            <div class="col-sm-2">
                                <input class="nextDayCheck" type="checkbox" name="nextDayCheck" value="1" {{ ($shiftConfiguration)?($shiftConfiguration->next_day_exit)?'checked':'':'' }}> Exit on Next Day
                            </div>
                            <div class="col-sm-3">
                                <input class="lastMinuteCheck" type="checkbox" name="lastMinuteCheck" {{ ($shiftConfiguration)?($shiftConfiguration->minutes_before)?'checked':'':'' }}> Allow <input type="number"
                                   class="lastMinute" name="lastMinute" value="{{ ($shiftConfiguration)?$shiftConfiguration->minutes_before:'' }}" style="width: 50px" disabled> Minutes before next shift
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <button class="btn btn-success" style="float: right">
                                    {{ ($shiftConfiguration)?'Update':'Add' }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /.box-body -->
            </div>
        </div>

        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i> Shift List </h3>
            </div>
            <div class="box-body table-responsive">
                <div class="box-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Shift Name</th>
                                <th scope="col">Start Time</th>
                                <th scope="col">End Time</th>
                                <th scope="col">Next Day Exit</th>
                                <th scope="col">Allowed Time before the shift</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shiftConfigurations as $shiftConfiguration)
                            <tr>
                                <th scope="row">{{ $loop->index+1 }}</th>
                                <td>{{ $shiftConfiguration->name }}</td>
                                <td>{{ Carbon\Carbon::parse($shiftConfiguration->start_time)->format('h : i A') }}</td>
                                <td>{{ Carbon\Carbon::parse($shiftConfiguration->end_time)->format('h : i A') }}</td>
                                <td>
                                    @if ($shiftConfiguration->next_day_exit)
                                        On
                                    @else
                                        Off
                                    @endif
                                </td>
                                <td>{{ $shiftConfiguration->minutes_before }} {{ ($shiftConfiguration->minutes_before)?'minutes':'' }}</td>
                                <td>
                                    <a href="{{ url('/employee/shift-configuration/'.$shiftConfiguration->id) }}" class="btn btn-info btn-xs"><i class="fa fa-pencil-square-o"
                                        aria-hidden="true"></i></a>
                                    <a href="{{ url('/employee/shift-configuration/delete/'.$shiftConfiguration->id) }}"
                                        class="btn btn-danger btn-xs"
                                        onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                        data-content="delete"><i class="fa fa-trash-o"></i></a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.box-body -->
            </div>
        </div>
    </section>

    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="modal-content">
                <div class="modal-body" id="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('scripts')
<script>
    $(document).ready(function () {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        function lastMinuteCheck(){
            if ($('.lastMinuteCheck').is(':checked')) {
                $('.lastMinute').removeAttr('disabled');
                $('.nextDayCheck').prop('checked', true);
                $('.lastMinute').attr('required', true);
            }else{
                $('.lastMinute').attr('disabled', 'disabled');
                $('.lastMinute').attr('required', false);
            }
        }

        $('.lastMinuteCheck').click(function () {
            lastMinuteCheck();
        });

        // $('.nextDayCheck').click(function () {
        //     if ($(this).is(':not(:checked)')) {
        //         $('.lastMinuteCheck').prop('checked', false);
        //     }
        //     lastMinuteCheck();
        // });
        });
    
</script>
@endsection