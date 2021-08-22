<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Student List</title>
    <style type="text/css">
        table>thead>tr>th, table>tbody>tr>td{
            text-align: center;
            height: 20px;
        }
    </style>
</head>
<body>

@if($allEnrollments->count()>0)
    <table id="example1" class="table table-striped" style="text-align: center">
        <thead>
        <tr>
            <th>GR No.</th>
            <th>Name</th>
            <th>Email</th>
            <th>User Name</th>
            <th>Phone</th>
            <th>Academic Year</th>
            <th>Course Name</th>
            <th>Class</th>
            <th>Section</th>
        </tr>
        </thead>
        <tbody>
        @foreach($allEnrollments as $enroll)
            <tr>
                <td>{{$enroll->gr_no}}</td>
                <td> {{$enroll->first_name." ".$enroll->middle_name." ".$enroll->last_name}}</td>
                <td>{{$enroll->email}}</td>
                <td>{{$enroll->username}}</td>
                <td>{{$enroll->student()->phone}}</td>
                <td>{{$enroll->year()->year_name}}</td>
                <td>{{$enroll->level()->level_name}}</td>
                <td>{{$enroll->batch()->batch_name}} @if(isset($enroll->batch()->get_division()->name)) - {{$enroll->batch()->get_division()->name}}@endif</td>
                <td>{{$enroll->section()->section_name}}</td>
            </tr>

        @endforeach
        </tbody>
    </table>
@endif

</body>
</html>