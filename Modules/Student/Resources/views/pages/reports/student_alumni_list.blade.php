<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Student Alumni List</title>
    <style type="text/css">
        table>thead>tr>th, table>tbody>tr>td{
            text-align: center;
            height: 20px;
        }
    </style>
</head>
<body>

@if($alumniStudents->count()>0)
<table id="example1" class="table table-striped" style="text-align: center">
    <thead>
    <tr>
        {{--<th>User ID</th>--}}
        <th>Std ID</th>
        <th>GR No.</th>
        <th>Name</th>
        <th>Email</th>
        <th>User Name</th>
        <th>Academic Year</th>
        <th>Course Name</th>
        <th>Class</th>
        <th>Section</th>
    </tr>
    </thead>
    <tbody>
    @foreach($alumniStudents as $enrollHistory)
    <tr>
        {{--<td>{{$enroll->user_id}}</td>--}}
        @php $studentProfile=$enrollHistory->enroll()->student();  @endphp
        <td>{{$studentProfile->std_id}}</td>
        <td>{{$enrollHistory->gr_no}}</td>
        <td> {{$studentProfile->first_name." ".$studentProfile->middle_name." ".$studentProfile->last_name}}</td>
        <td>{{$studentProfile->email}}</td>
        <td>{{$studentProfile->user()->username}}</td>
        <td>{{$enrollHistory->academicsYear()->year_name}}</td>
        <td>{{$enrollHistory->level()->level_name}}</td>
        <td>{{$enrollHistory->batch()->batch_name}} @if(isset($enrollHistory->batch()->get_division()->name)) - {{$enrollHistory->batch()->get_division()->name}}@endif</td>
        <td>{{$enrollHistory->section()->section_name}}</td>
    </tr>

    @endforeach
    </tbody>
</table>
@endif

</body>
</html>