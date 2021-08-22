@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
<link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
@stop


{{-- Content --}}
@section('content')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> View | <small>Task Schedule</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/student/default/index">Cadets</a></li>
            <li><a href="">SOP Setup</a></li>
            <li class="active">View Task Schedule</li>
        </ul>
    </section>
    <section class="content">
        <div id="p0">
            @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                    style="text-decoration:none" data-dismiss="alert"
                    aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @elseif(Session::has('alert'))
                <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
            @elseif(Session::has('errorMessage'))
                <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
            @endif
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid">
                    <div class="box-header">
                        <h4><i class="fa fa-search"></i> Search Task Schedule</h5>
                    </div>

                    <div class="box-body table-responsive">
                        <div class="row" style="margin-bottom: 15px">
                            <div class="col-sm-3">
                                <select name="" id="" class="form-control select-schedule-date">
                                    <option value="">--Shedule Date--</option>
                                    @foreach ($taskScheduleDates as $taskScheduleDate)
                                        <option value="{{ $taskScheduleDate->id }}">{{ $taskScheduleDate->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <button class="btn btn-success search-schedule-table-btn">Search</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12 schedule-table-holder">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
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
<script>
    $(document).ready(function () {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });

    $('.search-schedule-table-btn').click(function () {
            var taskScheduleDateId = $('.select-schedule-date').val();

            if (taskScheduleDateId) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/student/search/task/schedule/table') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'taskScheduleDateId': taskScheduleDateId,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        console.log(data);
                        $('.schedule-table-holder').empty();
                        $('.schedule-table-holder').append(data);
                    }
                });
                // Ajax Request End
            }else{
                swal('Error', 'Please Fill Up the required fields first!', 'error');
            }
        });
</script>
@stop