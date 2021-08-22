<link rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/css/dataTables.checkboxes.css">

<table id="studentListTable" class="table table-striped table-bordered" style="margin-top: 20px">
    <thead>
    <tr>
        <th>STD ID</th>
        <th>Name</th>
        <th>Roll</th>
        <th>View Due</th>
    </tr>

    </thead>
    <tbody>

    @foreach($studentList as $student)

        <tr class="gradeX">
            <td>{{$student->username}}</td>
            <td>{{$student->first_name.' '.$student->middle_name.' '.$student->last_name}}</td>
            <td>{{$student->gr_no}}</td>
            <td><a target="_blank" href="/fee/report-due-amount/student/{{$student->std_id}}" class="btn btn-success">View</a> </td>
        </tr>
    @endforeach

    </tbody>
</table>

<script>

    var oTable = $('#studentListTable').dataTable({
        stateSave: true
    });

</script>