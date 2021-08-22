<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 8/13/17
 * Time: 1:28 PM
 */
?>
{{--{{dd($data)}}--}}
<table id="dataTable1" class="table table-striped">
<thead>
<tr>
    <th>id</th>
    <th>Name</th>
    <th>In Date</th>
    <th>In Time</th>
    <th>Out Date</th>
    <th>Out Time</th>
</tr>
</thead>
<tbody>
@foreach($data as $d)
    <tr>
        <td>{{$d->employee_id}}</td>
        <td>{{$d->employee_name}}</td>
        <td>{{date_format(date_create_from_format('d-m-Y', $d->employee_in_date), 'd-m-Y')}}</td>
        <td>{{date_format($d->employee_in_time, 'h:i:s A')}}</td>
        <td>{{date_format(date_create_from_format('d-m-Y', $d->employee_out_date), 'd-m-Y')}}</td>
        <td>{{date_format($d->employee_out_time, 'h:i:s A')}}</td>
    </tr>
@endforeach
</tbody>
</table>
<h3><br>If everything is ok please save your Attendance data.</h3>
<script>
    $(function () {
        $("#dataTable1").DataTable();
    });
</script>