<style>
    .heading {
        text-align: center;
        margin: 0px;
        padding: 0px;
    }
    p {
        font-size: 12px;
        padding-top: 5px;
    }
    h2, h5 {
        margin: 0px;
        padding: 0px;
        line-height: 0px;
    }
    #studentListTable {
        font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
        border-collapse: collapse;
        width: 100%;
    }

    #studentListTable td, #studentListTable th {
        border: 1px solid #ddd;
        padding: 2px;
        font-size: 10px;
    }

    /*#studentListTable tr:nth-child(even){background-color: #f2f2f2;}*/


    #studentListTable th {
        text-align: left;
        background-color: #8a8a8a;
        color: white;
    }
</style>

<div class="heading">
    <h2>{{$instituteInfo->institute_name}}</h2>
    <p>{{$instituteInfo->address1}}</p>
    <h5>Due Absent Fine </h5>
    <p>Class: <strong>{{$class_name}}</strong>   Section : <strong>{{$section_name}}</strong></p>

</div>
<table id="studentListTable" class="table table-striped table-bordered" style="margin-top: 20px">
    <thead>
    <tr>
        <th>STD ID</th>
        <th>Name</th>
        <th>Roll</th>
        <th>Total Absent</th>
        <th>Fine Rate</th>
        <th>Total Fine</th>
        <th>Paid</th>
        <th>Due</th>
    </tr>
    </thead>
    <tbody>

    @php $totalFineAmount=0; $totalPaidAmount=0; $totalDueAmount=0; @endphp
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
            <td>{{$alreadyPaid}}</td>
            <td>{{$totalAbsentFineAmount-$alreadyPaid}}</td>
        </tr>
        @php
            $totalFineAmount+=$totalAbsentFineAmount;
            $totalPaidAmount+=$alreadyPaid;
            $totalDueAmount+=$totalAbsentFineAmount-$alreadyPaid;
        @endphp
    @endforeach
    <tr>
        <th colspan="5" align="right">Total</th>
        <th>{{$totalFineAmount}}</th>
        <th>{{$totalPaidAmount}}</th>
        <th>{{$totalDueAmount}}</th>

    </tr>
    </tbody>
</table>

<script>

    var oTable = $('#studentListTable').dataTable({
        stateSave: true
    });



</script>