<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 8/16/17
 * Time: 2:13 PM
 */
//use Modules\Employee\Http\Controllers\MyHelper;
?>
<style>
    #myDataTable {
        height: 300px;
        overflow: auto;
        width: 100%;
    }
    #myDataTable table{
        width:100%;
    }
</style>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h3 class="box-title"><i class="fa fa-plus-square"></i> Employee Attendance Update </h3>
</div>
<form autocomplete="off"  id="shift-create-form" action="{{url('/employee/add-attendance/store')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="id" value="{{$emplAttendanceProfile->id}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="department">Employee Name </label>
                    @php $empName=$emplAttendanceProfile->name(); @endphp
                        <input type="text" value="{{$empName->first_name.' '.$empName->middle_name.' '.$empName->last_name}}" class="form-control" name="empName" id="empName">
                        <div class="help-block"></div>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="startDate">Start Date</label>
                    <input type="text" readonly value="{{$emplAttendanceProfile->in_date}}" class="form-control" name="startDate" id="startDate">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="name">Start Time</label>
                    <div class='input-group date' id="startTime">
                        <input required  value="{{date('g:i A', strtotime($emplAttendanceProfile->in_time))}}" name="startTime" type='text' class="form-control" />
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                    </div>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="endDate">End Date</label>
                    <input type="text" class="form-control" readonly value="{{$emplAttendanceProfile->out_date}}" name="endDate" id="endDate">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="name">End Time</label>
                    <div class='input-group date' id="endTime">
                        <input required name="endTime" value="{{date('g:i A', strtotime($emplAttendanceProfile->out_time))}}"  type='text' class="form-control" />
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--./modal-body-->
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-left" id="create"></i> Save </button>
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
    <!--./modal-footer-->
</form>

<div class="modal" id="attendanceModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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


<style>
    .pad-right{
        padding-right: 20px;
    }
</style>

<script type="text/javascript">

       /// date time picker for add attendnace


        $(document).ready(function(){
            $('#startTime').datetimepicker({
                format: 'LT'
            });

            $('#endTime').datetimepicker({
                format: 'LT'
            });
        });

</script>