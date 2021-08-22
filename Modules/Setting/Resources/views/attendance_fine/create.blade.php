@extends('setting::layouts.master')

@section('section-title')
    <h1>
        <i class="fa fa-th-list"></i> Attendance Fine <small>Setting</small>
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Setting</a></li>
        <li class="active">Configuration</li>
        <li class="active">Attendance Fine</li>
    </ul>
@endsection

@section('page-content')
    <div class="panel panel-default">

        <div class="panel-body">
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>

            @elseif(Session::has('error'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('error') }} </h4>
                </div>

            @endif
            <div class="col-md-5">

                <form action="/setting/attendance/store" method="post" id="attendance_setting">
                    <input type="hidden" @if(!empty($attendanceSettingProfile)) value="{{$attendanceSettingProfile->id}}" @endif name="attendance_setting_id">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">

                    <div class="form-group">
                        <label for="up-to-date">Setting Type </label>
                        <select class="form-control" name="setting_type">
                            <option value="">Select Type</option>
                            <option @if(!empty($attendanceSettingProfile->setting_type) && $attendanceSettingProfile->setting_type=="ABSENT") selected="selected" @endif  value="ABSENT">ABSENT</option>
                            <option @if(!empty($attendanceSettingProfile->setting_type) && $attendanceSettingProfile->setting_type=="LATE_PRESENT_1") selected="selected" @endif  value="LATE_PRESENT_1">LATE PRESENT ONE</option>
                            <option @if(!empty($attendanceSettingProfile->setting_type) && $attendanceSettingProfile->setting_type=="LATE_PRESENT_2") selected="selected" @endif  value="LATE_PRESENT_2">LATE PRESENT TWO</option>
                            <option @if(!empty($attendanceSettingProfile->setting_type) && $attendanceSettingProfile->setting_type=="PRESENT") selected="selected" @endif  value="PRESENT">PRESENT</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="pwd">Amount</label>
                        <input type="text" id="attribute" @if(!empty($attendanceSettingProfile)) value="{{$attendanceSettingProfile->amount}}" @endif required class="form-control" name="amount">
                    </div>

                    <div class="form-group">
                        <label for="pwd"> Form Entry Time</label>
                        <input type="text"   id="form_entry_time" @if(!empty($attendanceSettingProfile)) value="{{$attendanceSettingProfile->form_entry_time}}" @endif required class="form-control date_time_picker " name="form_entry_time">
                    </div>

                    <div class="form-group">
                        <label for="pwd">To Entry Time</label>
                        <input type="text"  id="to_entry_time" required class="form-control date_time_picker" @if(!empty($attendanceSettingProfile)) value="{{$attendanceSettingProfile->to_entry_time}}" @endif name="to_entry_time">
                    </div>

                    <div class="form-group">
                        <label for="pwd">Sorting Order</label>
                        <input type="text" id="sortingOrder" required class="form-control" @if(!empty($attendanceSettingProfile)) value="{{$attendanceSettingProfile->sorting_order}}" @endif name="sorting_order">
                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>

        </div>
    </div>

@endsection




@section('page-script')


    $("#form_entry_time, #to_entry_time").datetimepicker({
        format: 'hh:ii',
        minuteStep: 5,
        autoclose: true,
        minView: 0,
        maxView: 1,
        startView: 1,
    });
    $(this).data('datetimepicker').picker.addClass('timepicker');


@endsection


