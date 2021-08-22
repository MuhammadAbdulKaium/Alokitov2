<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 7/30/17
 * Time: 4:15 PM
 */
use Modules\Employee\Http\Controllers\MyHelper;
?>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-eye"></i> View Shift | <small>{{$shift->shiftName}}</small></h4>
</div>
<div class="modal-body">
    <div class="box-body table-responsive">
        <table id="w0" class="table table-striped table-bordered detail-view">
            <tbody>
            <tr>
                <th style="width:150px">Shift Name</th>
                <td>{{$shift->shiftName}}</td>
            </tr>
            <tr>
                <th style="width:150px">Shift Time</th>
                <td>{{date("g:i a", strtotime($shift->shiftStartTime))}} - {{date("g:i a", strtotime($shift->shiftEndTime))}}</td>
            </tr>
            <tr>
                <th>First Holiday</th>
                <td>{{MyHelper::dayName($shift->firstHoliday)}}</td>
            </tr>
            <tr>
                <th>Second Holiday</th>
                <td>{{MyHelper::dayName($shift->secondHoliday)}}</td>
            </tr>
            <tr>
                <th>Late In Time</th>
                <td><?php if(!empty($shift->lateInTime)) echo date("g:i a", strtotime($shift->lateInTime)) ?></td>
            </tr>
            <tr>
                <th>Absent In Time</th>
                <td><?php if(!empty($shift->absentInTime)) echo date("g:i a", strtotime($shift->absentInTime)) ?></td>
            </tr>
            <tr>
                <th>Lunch Time</th>
                <td><?php if(!empty($shift->lunchStartTime)) echo date("g:i a", strtotime($shift->lunchStartTime)) ?> - <?php if(!empty($shift->lunchEndTime)) echo date("g:i a", strtotime($shift->lunchEndTime)) ?></td>
            </tr>
            <tr>
                <th>Over Time </th>
                <td><?php if(!empty($shift->overTimeStart)) echo date("g:i a", strtotime($shift->overTimeStart)) ?> - <?php if(!empty($shift->overTimeEnd)) echo date("g:i a", strtotime($shift->overTimeEnd)) ?></td>
            </tr>
            <tr>
                <th>Extra Over Time</th>
                <td><?php if(!empty($shift->extraOverTimeStart)) echo date("g:i a", strtotime($shift->extraOverTimeStart)) ?> - <?php if(!empty($shift->extraOverTimeEnd)) echo date("g:i a", strtotime($shift->extraOverTimeEnd)) ?></td>
            </tr>
            <tr>
                <th>Early Out Time</th>
                <td></td>
            </tr>
            <tr>
                <th>Last Out Time</th>
                <td></td>
            </tr>
            <tr>
                <th>Late Day Allow</th>
                <td></td>
            </tr>
            <tr>
                <th>Out Time Grace</th>
                <td></td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
