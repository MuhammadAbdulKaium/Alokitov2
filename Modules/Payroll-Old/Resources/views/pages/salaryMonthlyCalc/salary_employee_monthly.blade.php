<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 11/20/17
 * Time: 12:09 PM
 */
$i=1;
?>
<table id="example2" class="table table-striped">
    <thead>
    <tr>
        <th>#</th>
        <th>Employee</th>
        <th>Month Year</th>
        <th>Total Salary</th>
        <th>Details</th>
    </tr>
    </thead>
    <tbody>
    @foreach($salaryEmployeeMonthly as $data)
        <tr>
            <td>{{$i++}}</td>
            <td>{{\Modules\Payroll\Entities\EmpSalaryAssign::empName($data->employee_id)}}</td>
            <td>{{date('F', mktime(0, 0, 0, $month, 10))}}, {{$year}}</td>
            <td>{{$data->amount}}</td>
            <td>
                <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#globalModal" onclick="showData({{$data->employee_id}},{{$month}},{{$year}})"><i class="fa fa-eye"></i></a>
            </td>
        </tr>
    @endforeach
    </tbody>
    <tfoot>
    <tr>
        <td></td>
    </tr>
    </tfoot>
</table>

<!-- DataTables -->
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- datatable script -->
<script>
    $(function () {
        $("#example1").DataTable();
        $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>