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
            <i class="fa fa-th-list"></i> Manage | <small>Task Schedule</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/student/default/index">Cadets</a></li>
            <li><a href="">SOP Setup</a></li>
            <li class="active">Task Schedule</li>
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
            <div class="col-sm-4">
                <div class="box box-solid">
                    <form method="POST" action="{{url('/student/create/task/schedule/date')}}">
                        @csrf

                        <div class="box-header">
                            <h4><i class="fa fa-plus-square"></i> Create Schedule Date</h5>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <input type="text" name="name" class="form-control" placeholder="Name" required>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" id="fromDate" class="form-control hasDatepicker from-date" name="fromDate" maxlength="10"
                                    placeholder="From Date" aria-required="true" size="10" required>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" id="expectedDate" class="form-control hasDatepicker expected-date" name="expectedDate" maxlength="10"
                                    placeholder="Expected Date" aria-required="true" size="10" required>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-success btn-create">Create</button>
                        </div>
                    </form>
                </div>

                <div class="box box-solid">
                    <div class="box-header">
                        <h4><i class="fa fa-search"></i> Schedule Date List</h5>
                    </div>

                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="taskScheduleDateTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Schedule Name</th>
                                    <th>Start Date</th>
                                    <th>Expected Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taskScheduleDates as $taskScheduleDate)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$taskScheduleDate->name}}</td>
                                        <td>{{Carbon\Carbon::parse($taskScheduleDate->start_date)->format('d/m/Y')}}</td>
                                        <td>{{Carbon\Carbon::parse($taskScheduleDate->expected_date)->format('d/m/Y')}}</td>
                                        <td>
                                            <a href="{{ url('/student/edit/task/schedule/date/'.$taskScheduleDate->id) }}"
                                                class="btn btn-primary btn-xs" title="Edit" data-target="#globalModal"
                                                data-toggle="modal" data-modal-size="modal-md">
                                                <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                                            </a>
                                            <a href="{{ url('/student/delete/task/schedule/date/'.$taskScheduleDate->id) }}"
                                                class="btn btn-danger btn-xs"
                                                onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                                data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="box box-solid">
                    <form method="POST" action="{{url('/student/create/task/schedule')}}" id="task-schedule-form">
                        @csrf

                        <input type="hidden" name="taskScheduleId" value="{{ ($taskSchedule)?$taskSchedule->id:'' }}">

                        <div class="box-header">
                            <h4><i class="fa fa-plus-square"></i> Create Task Schedule</h5>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <select name="taskScheduleDateId" id="" class="form-control" required>
                                        <option value="">--Task Schedule Date--</option>
                                        @foreach ($taskScheduleDates as $taskScheduleDate)
                                            <option value="{{ $taskScheduleDate->id }}" {{ ($taskSchedule)?($taskSchedule->taskScheduleDate->id == $taskScheduleDate->id)?'selected':'':'' }}>{{ $taskScheduleDate->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <select name="activityCategoryId" id="" class="form-control select-activity-category" required>
                                        <option value="">--Activity Category--</option>
                                        @foreach ($activityCategories as $activityCategory)
                                            <option value="{{ $activityCategory->id }}" {{ ($taskSchedule)?($taskSchedule->activityCategory->id == $activityCategory->id)?'selected':'':'' }}>{{ $activityCategory->category_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <select name="activityId" id="" class="form-control select-activity" required>
                                        <option value="">--Activity--</option>
                                        @if ($taskSchedule)
                                            @foreach ($taskSchedule->activityCategory->studentActivityDirectoryActivities as $activity)
                                                <option value="{{$activity->id}}" {{ ($taskSchedule)?($taskSchedule->activity->id == $activity->id)?'selected':'':'' }}>{{$activity->activity_name}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-sm-3">
                                    <select name="eventType" id="" class="form-control event-type" required>
                                        <option value="">--Event Type--</option>
                                        <option value="1" {{ ($taskSchedule)?($taskSchedule->event_type == 1)?'selected':'':'' }}>Daily Event</option>
                                        <option value="2" {{ ($taskSchedule)?($taskSchedule->event_type == 2)?'selected':'':'' }}>Weekly Event</option>
                                        <option value="3" {{ ($taskSchedule)?($taskSchedule->event_type == 3)?'selected':'':'' }}>Monthly Event</option>
                                    </select>
                                </div>
                            </div>
                            @php
                                $times = null;
                                if ($taskSchedule) {
                                    $times = json_decode($taskSchedule->times);
                                }
                            @endphp
                            <div class="row" style="margin-top: 15px">
                                <div class="col-sm-3 diffThuCheckHolder" style="display: {{ ($taskSchedule)?($taskSchedule->event_type == 1)?'block':'none':'none' }}">
                                    <input type="checkbox" name="differentThu" value="1" class="diffThuCheck" {{ ($taskSchedule)?($taskSchedule->different_thursday == 1)?'checked':'':'' }}> Different Thursday
                                </div>
                                <div class="col-sm-3 weekNumberHolder" style="display: {{ ($taskSchedule)?($taskSchedule->event_type == 3)?'block':'none':'none' }}">
                                    <select name="weekNumber" id="" class="form-control">
                                        <option value="">--Select Week--</option>
                                        <option value="1" {{ ($times && $taskSchedule->event_type == 3)?($times->weekNumber == 1)?'selected':'':'' }}>1st</option>
                                        <option value="2" {{ ($times && $taskSchedule->event_type == 3)?($times->weekNumber == 2)?'selected':'':'' }}>2nd</option>
                                        <option value="3" {{ ($times && $taskSchedule->event_type == 3)?($times->weekNumber == 3)?'selected':'':'' }}>3rd</option>
                                        <option value="4" {{ ($times && $taskSchedule->event_type == 3)?($times->weekNumber == 4)?'selected':'':'' }}>4th</option>
                                    </select>
                                </div>
                                <div class="col-sm-9">
                                    <input type="checkbox" value="6" name="days[]" {{ ($times)?(in_array(6, $times->days))?'checked':'':'' }}> Saturday 
                                    <input type="checkbox" value="0" name="days[]" {{ ($times)?(in_array(0, $times->days))?'checked':'':'' }}> Sunday 
                                    <input type="checkbox" value="1" name="days[]" {{ ($times)?(in_array(1, $times->days))?'checked':'':'' }}> Monday 
                                    <input type="checkbox" value="2" name="days[]" {{ ($times)?(in_array(2, $times->days))?'checked':'':'' }}> Tuesday 
                                    <input type="checkbox" value="3" name="days[]" {{ ($times)?(in_array(3, $times->days))?'checked':'':'' }}> Wednesday 
                                    <input type="checkbox" value="4" name="days[]" {{ ($times)?(in_array(4, $times->days))?'checked':'':'' }}> Thursday 
                                    <input type="checkbox" value="5" name="days[]" {{ ($times)?(in_array(5, $times->days))?'checked':'':'' }}> Friday 
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px">
                                <div class="col-sm-2">
                                    <label for="">Start Time: </label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="time" name="startTime" class="form-control" value="{{ ($times)?$times->startTime:'' }}" required>
                                </div>
                                <div class="col-sm-2">
                                    <label for="">End Time: </label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="time" name="endTime" class="form-control" value="{{ ($times)?$times->endTime:'' }}" required>
                                </div>
                                <div class="col-sm-4">
                                    <input type="text" name="extraNote" class="form-control" value="{{ ($taskSchedule)?$taskSchedule->extra_note:'' }}" placeholder="Extra Note">
                                </div>
                            </div>
                            <div class="row thu-times" style="margin-top: 15px; display: {{ ($taskSchedule)?($taskSchedule->different_thursday == 1)?'block':'none':'none' }}">
                                <div class="col-sm-12" style="margin-bottom: 15px">
                                    <label for="">--For Thursday--</label>
                                </div>
                                <div class="col-sm-2">
                                    <label for="">Start Time: </label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="time" name="thuStartTime" class="form-control" value="{{ ($times && $taskSchedule->different_thursday == 1)?($times->thuStartTime):'' }}">
                                </div>
                                <div class="col-sm-2">
                                    <label for="">End Time: </label>
                                </div>
                                <div class="col-sm-2">
                                    <input type="time" name="thuEndTime" class="form-control" value="{{ ($times && $taskSchedule->different_thursday == 1)?($times->thuEndTime):'' }}">
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-success btn-create">{{ ($taskSchedule)?'Update':'Create' }}</button>
                            @if ($taskSchedule)
                                <a class="btn btn-primary" href="{{ url('/student/task/schedule') }}">Add New</a>
                            @endif
                        </div>
                    </form>
                </div>

                <div class="box box-solid">
                    <div class="box-header">
                        <h4><i class="fa fa-search"></i> Schedule List</h5>
                    </div>

                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered table-hover" id="taskScheduleTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Schedule Date</th>
                                    <th>Activity Category</th>
                                    <th>Activity</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($taskSchedules as $taskSchedule)
                                    <tr>
                                        <td>{{$loop->index+1}}</td>
                                        <td>{{$taskSchedule->taskScheduleDate->name}}</td>
                                        <td>{{$taskSchedule->activityCategory->category_name}}</td>
                                        <td>{{$taskSchedule->activity->activity_name}}</td>
                                        <td>
                                            @if ($taskSchedule->event_type == 1)
                                                Daily Event
                                            @elseif ($taskSchedule->event_type == 2)
                                                Weekly Event
                                            @elseif ($taskSchedule->event_type == 3)
                                                Monthly Event
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ url('/student/task/schedule/'.$taskSchedule->id) }}"
                                                class="btn btn-primary btn-xs"><i class="fa fa-pencil-square-o" 
                                                aria-hidden="true"></i></a>
                                            <a href="{{ url('/student/delete/task/schedule/'.$taskSchedule->id) }}"
                                                class="btn btn-danger btn-xs"
                                                onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                                data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
        $('#taskScheduleDateTable').DataTable();
        $('#taskScheduleTable').DataTable();

        $('#fromDate').datepicker();
        $('#expectedDate').datepicker();

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        $('.select2').select2();

        $(document).on('change', '.select-activity-category', function () {
            var selectActivity = $(this).parent().parent().find('.select-activity');

            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/student/get/activites/from/activity-cateogry') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'categoryId': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (datas) {
                    var txt = '<option value="">--Activity--</option>';

                    datas.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.activity_name+'</option>';
                    });

                    selectActivity.html(txt);
                }
            });
            // Ajax Request End
        });

        $(document).on('click', '.diffThuCheck', function () {
            var thuTimes = $(this).parent().parent().parent().find('.thu-times');
            if ($(this).is(':checked')) {
                thuTimes.css('display', 'block');
            }else{
                thuTimes.css('display', 'none');
            }
        });

        $(document).on('change', '.event-type', function () {
            var thisParent = $(this).parent().parent().parent();
            var days = thisParent.find('input[name="days[]"]');

            thisParent.find('.diffThuCheckHolder').css('display', 'none');
            thisParent.find('.thu-times').css('display', 'none');
            thisParent.find('.weekNumberHolder').css('display', 'none');

            days.prop('checked', false);
            

            if ($(this).val() == 1) {
                thisParent.find('.diffThuCheckHolder').css('display', 'block');
                days.prop('checked', true);
            } else if($(this).val() == 2){

            } else if($(this).val() == 3){
                thisParent.find('.weekNumberHolder').css('display', 'block');
            }
        });

        $('form#task-schedule-form').one('submit', function (e) {
            e.preventDefault();
            var days = $(this).find('input[name="days[]"]');
            var diffThuCheck = $(this).find('.diffThuCheck');
            var thuStartTime = $(this).find('input[name="thuStartTime"]').val();
            var thuEndTime = $(this).find('input[name="thuEndTime"]').val();
            var eventType = $(this).find('.event-type').val();
            var weekNumber = $(this).find('input[name="weekNumber"]').val();
            var dayCheck = false;
            var multipleDay = false;
            var i = 0;

            days.each((index, value) => {
                if ($(value).is(':checked')) {
                    dayCheck = true;
                }
                if ($(value).is(':checked') && i++ > 0) {
                    multipleDay = true;
                }
            });

            if (!dayCheck) {
                swal('Error!', 'Must select at least one day.', 'error');
            } else if (diffThuCheck.is(':checked') && (!thuStartTime || !thuEndTime)){
                swal('Error!', 'Please fill up the missing times.', 'error');
            } else if (eventType == 3 && !weekNumber){
                swal('Error!', 'Please select week number.', 'error');
            }else if (eventType != 1 && multipleDay) {
                swal('Error!', 'Please select only one day.', 'error');
            } else {
                $(this).submit();
            }
        });
    });
</script>
@stop