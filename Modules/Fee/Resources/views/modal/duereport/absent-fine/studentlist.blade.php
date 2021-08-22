<link rel="stylesheet" type="text/css" href="https://gyrocode.github.io/jquery-datatables-checkboxes/1.2.6/css/dataTables.checkboxes.css">

<table id="studentListTable" class="table table-striped table-bordered" style="margin-top: 20px">
    <thead>
    <tr>
        <th>STD ID</th>
        <th>Name</th>
        <th>Roll</th>
        <th>Total Absent</th>
        <th>Fine Rate</th>
        <th>Total Fine</th>
        <th>Due</th>
    </tr>
    </thead>
    <tbody>

    @foreach($absentStudentList as $student)

        <tr class="gradeX">
            @php $studentProfile=$student->studentProfile(); @endphp
            <td width="15%">{{$studentProfile->username}}</td>
            <td width="20%">{{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}}</td>
            <td>{{$studentProfile->gr_no}}</td>
            <td>{{$student->total_absent}}</td>
            <td>{{$fineRate->amount}}</td>
            @php $alreadyPaid =0;
            $totalAbsentFineAmount=$fineRate->amount*$student->total_absent;
            $alreadyPaid=$student->totalAbsentAmountPaid($student->std_id)
            @endphp
            <td>{{$totalAbsentFineAmount}}</td>
            <td>{{$totalAbsentFineAmount-$alreadyPaid}}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<script>

    var oTable = $('#studentListTable').dataTable({
        stateSave: true
    });



</script>