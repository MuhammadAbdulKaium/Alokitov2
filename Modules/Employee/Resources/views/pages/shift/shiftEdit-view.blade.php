<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 7/30/17
 * Time: 4:27 PM
 */
?>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Update Shift</h3>
</div>
<form id="shift-create-form" action="{{url('/employee/shift/update', [$shift->id])}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="errorTxt"></div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="shiftName">Shift Name</label>
                    <input id="shiftName" value="{{$shift->shiftName}}" class="form-control" name="shiftName" maxlength="50" type="text">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="firstHoliday">First Holiday </label>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label pad-right" >
                            <input class="form-check-input for-count firstHoliday" type="checkbox" @if($shift->firstHoliday == 1){{'checked'}} @endif @if(!empty($shift->firstHoliday) & $shift->firstHoliday != 1) {{'disabled'}} @endif name="firstHoliday" value="1"> Saturday
                        </label>
                        <label class="form-check-label pad-right">
                            <input class="form-check-input for-count firstHoliday" type="checkbox" @if($shift->firstHoliday == 2){{'checked'}} @endif @if(!empty($shift->firstHoliday) & $shift->firstHoliday != 2) {{'disabled'}} @endif name="firstHoliday" value="2"> Sunday
                        </label>
                        <label class="form-check-label pad-right">
                            <input class="form-check-input for-count firstHoliday" type="checkbox" @if($shift->firstHoliday == 3){{'checked'}} @endif @if(!empty($shift->firstHoliday) & $shift->firstHoliday != 3) {{'disabled'}} @endif name="firstHoliday" value="3"> Monday
                        </label>
                        <label class="form-check-label pad-right">
                            <input class="form-check-input for-count firstHoliday" type="checkbox" @if($shift->firstHoliday == 4){{'checked'}} @endif @if(!empty($shift->firstHoliday) & $shift->firstHoliday != 4) {{'disabled'}} @endif name="firstHoliday" value="4"> Tuesday
                        </label>
                        <label class="form-check-label pad-right">
                            <input class="form-check-input for-count firstHoliday" type="checkbox" @if($shift->firstHoliday == 5){{'checked'}} @endif @if(!empty($shift->firstHoliday) & $shift->firstHoliday != 5) {{'disabled'}} @endif name="firstHoliday" value="5"> Wednesday
                        </label>
                        <label class="form-check-label pad-right">
                            <input class="form-check-input for-count firstHoliday" type="checkbox" @if($shift->firstHoliday == 6){{'checked'}} @endif @if(!empty($shift->firstHoliday) & $shift->firstHoliday != 6) {{'disabled'}} @endif name="firstHoliday" value="6"> Thursday
                        </label>
                        <label class="form-check-label pad-right">
                            <input class="form-check-input for-count firstHoliday" type="checkbox" @if($shift->firstHoliday == 7){{'checked'}} @endif @if(!empty($shift->firstHoliday) & $shift->firstHoliday != 7) {{'disabled'}} @endif name="firstHoliday" value="7"> Friday
                        </label>
                    </div>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="secondHoliday">Second Holiday </label>
                    <div class="form-check form-check-inline">
                        <label class="form-check-label pad-right" >
                            <input class="form-check-input for-count secondHoliday" @if($shift->secondHoliday == 1){{'checked'}} @endif @if(!empty($shift->secondHoliday) & $shift->secondHoliday != 1) {{'disabled'}} @endif type="checkbox" name="secondHoliday"  value="1"> Saturday
                        </label>
                        <label class="form-check-label pad-right">
                            <input class="form-check-input for-count secondHoliday" @if($shift->secondHoliday == 2){{'checked'}} @endif @if(!empty($shift->secondHoliday) & $shift->secondHoliday != 2) {{'disabled'}} @endif type="checkbox" name="secondHoliday"  value="2"> Sunday
                        </label>
                        <label class="form-check-label pad-right">
                            <input class="form-check-input for-count secondHoliday" @if($shift->secondHoliday == 3){{'checked'}} @endif @if(!empty($shift->secondHoliday) & $shift->secondHoliday != 3) {{'disabled'}} @endif type="checkbox" name="secondHoliday"  value="3"> Monday
                        </label>
                        <label class="form-check-label pad-right">
                            <input class="form-check-input for-count secondHoliday" @if($shift->secondHoliday == 4){{'checked'}} @endif @if(!empty($shift->secondHoliday) & $shift->secondHoliday != 4) {{'disabled'}} @endif type="checkbox" name="secondHoliday"  value="4"> Tuesday
                        </label>
                        <label class="form-check-label pad-right">
                            <input class="form-check-input for-count secondHoliday" @if($shift->secondHoliday == 5){{'checked'}} @endif @if(!empty($shift->secondHoliday) & $shift->secondHoliday != 5) {{'disabled'}} @endif type="checkbox" name="secondHoliday"  value="5"> Wednesday
                        </label>
                        <label class="form-check-label pad-right">
                            <input class="form-check-input for-count secondHoliday" @if($shift->secondHoliday == 6){{'checked'}} @endif @if(!empty($shift->secondHoliday) & $shift->secondHoliday != 6) {{'disabled'}} @endif type="checkbox" name="secondHoliday"  value="6"> Thursday
                        </label>
                        <label class="form-check-label pad-right">
                            <input class="form-check-input for-count secondHoliday" @if($shift->secondHoliday == 7){{'checked'}} @endif @if(!empty($shift->secondHoliday) & $shift->secondHoliday != 7) {{'disabled'}} @endif type="checkbox" name="secondHoliday"   value="7"> Friday
                        </label>
                    </div>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="shiftStartTime">Shift Start Time</label>
                    <input id="shiftStartTime" value="{{$shift->shiftStartTime}}"  class="form-control" name="shiftStartTime" type="time">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="shiftEndTime">Shift End Time</label>
                    <input id="shiftEndTime" value="{{$shift->shiftEndTime}}" class="form-control" name="shiftEndTime" type="time">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="lateInTime">Late In Time</label>
                    <input id="lateInTime" value="{{$shift->lateInTime}}" class="form-control" name="lateInTime" type="time">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="absentInTime">Absent In Time</label>
                    <input id="absentInTime" value="{{$shift->absentInTime}}" class="form-control" name="absentInTime" type="time">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="lunchStartTime">Lunch Start Time</label>
                    <input id="lunchStartTime" value="{{$shift->lunchStartTime}}" class="form-control" name="lunchStartTime" type="time">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="lunchEndTime">Lunch End Time</label>
                    <input id="lunchEndTime" value="{{$shift->lunchEndTime}}" class="form-control" name="lunchEndTime" type="time">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="overTimeStart"> Over Time Start</label>
                    <input id="overTimeStart" value="{{$shift->overTimeStart}}" class="form-control" name="overTimeStart" type="time">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="overTimeEnd"> Over Time End</label>
                    <input id="overTimeEnd" value="{{$shift->overTimeEnd}}" class="form-control" name="overTimeEnd" type="time">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="extraOverTimeStart"> Extra Over Time Start</label>
                    <input id="extraOverTimeStart" value="{{$shift->extraOverTimeStart}}" class="form-control" name="extraOverTimeStart" type="time">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="extraOverTimeEnd"> Extra Over Time End</label>
                    <input id="extraOverTimeEnd" value="{{$shift->extraOverTimeEnd}}" class="form-control" name="extraOverTimeEnd" type="time">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="earlyOutTime"> Early Out Time</label>
                    <input id="earlyOutTime" value="{{$shift->earlyOutTime}}" class="form-control" name="earlyOutTime" type="time">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="lastOutTime"> Last Out Time</label>
                    <input id="lastOutTime" value="{{$shift->lastOutTime}}" class="form-control" name="lastOutTime" type="time">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="lateDayAllow"> Late Day Allow</label>
                    <input id="lateDayAllow" value="{{$shift->lateDayAllow}}" class="form-control" name="lateDayAllow" type="number">
                    <div class="help-block">Days</div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="outTimeGrace"> Over Time Grace</label>
                    <input id="outTimeGrace" value="{{$shift->outTimeGrace}}" class="form-control" name="outTimeGrace" type="number">
                    <div class="help-block">Minutes</div>
                </div>
            </div>
        </div>
    </div>
    <!--./modal-body-->
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-left" id="update"></i> Update </button>
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
    <!--./modal-footer-->
</form>

<style>
    .pad-right{
        padding-right: 20px;
    }
</style>

<script type="text/javascript">
    $('.firstHoliday').click(function () {
        if($('.firstHoliday:checked').length == 1){
            $('.firstHoliday').prop('disabled', true);
            $('.firstHoliday:checked').prop('disabled', false);
        }else{
            $('.firstHoliday').prop('disabled', false);
        }
        firstSecHoliday('f');
    });
    $('.secondHoliday').click(function () {
        if($('.secondHoliday:checked').length == 1){
            $('.secondHoliday').prop('disabled', true);
            $('.secondHoliday:checked').prop('disabled', false);
        }else{
            $('.secondHoliday').prop('disabled', false);
        }
        firstSecHoliday('s');
    });

    function firstSecHoliday(i) {
        if($('.firstHoliday:checked').length != '' && $('.secondHoliday:checked').length != '' && $('.firstHoliday:checked').val() == $('.secondHoliday:checked').val()){
            alert("First Holiday and Second Holiday cannot be Same");
            if(i == 'f'){
                $('.firstHoliday').prop('disabled', false);
                $('.firstHoliday').removeAttr('checked');
            }else if(i == 's'){
                $('.secondHoliday').prop('disabled', false);
                $('.secondHoliday').removeAttr('checked');
            }
        }
    }

    $(document).ready(function(){
        // validate signup form on keyup and submit
        var validator = $("#shift-create-form").validate({
            // Specify validation rules
            rules: {
                shiftName: {
                    required: true,
                    minlength: 1,
                    maxlength: 50,
                },
                shiftStartTime: {
                    required: true,
                },
                shiftEndTime: {
                    required: true,
                },
                lastOutTime: {
                    required: true,
                },
            },

            // Specify validation error messages
            messages: {
                //name:"Shift Name is required.",
                //startTime:"Shift Start Time is required.",
                //endTime:"Shift End Time is required.",
            },

            //errorLabelContainer: '.errorTxt',


            highlight: function(element) {
                $(element).closest('.form-group').addClass('has-error');
            },

            unhighlight: function(element) {
                $(element).closest('.form-group').removeClass('has-error');
                $(element).closest('.form-group').addClass('has-success');
            },

            debug: true,
            success: "valid",
            errorElement: 'span',
            errorClass: 'help-block',

            errorPlacement: function(error, element) {
                if (element.parent('.input-group').length) {
                    error.insertAfter(element.parent());
                } else {
                    error.insertAfter(element);
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
        });
    });
</script>
