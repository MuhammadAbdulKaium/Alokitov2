<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Student Deactive List</title>
    <style type="text/css">
        table>thead>tr>th, table>tbody>tr>td{
            text-align: center;
            height: 20px;
        }
    </style>
</head>
<body>

@if($deactiveStudents->count()>0)
<table id="example1" class="table table-striped" style="text-align: center">
    <thead>
    <tr>
        {{--<th>User ID</th>--}}
        <th>Roll NO.</th>
        <th>Name</th>
        <th>Email</th>
        <th>Academic Year</th>
        <th>Course Name</th>
        <th>Class</th>
        <th>Section</th>
        <th>Status</th>
    </tr>
    </thead>
    <tbody>
    @foreach($deactiveStudents as $student)
    <tr>
        {{--<td>{{$enroll->user_id}}</td>--}}
        <td>{{$student->gr_no}}</td>
        <td>{{$student->first_name." ".$student->middle_name." ".$student->last_name}}</td>
        <td>{{$student->email}}</td>
        <td>{{Modules\Academics\Entities\AcademicsYear::getAcademicYearById($student->academic_year)}}</td>
        <td>{{Modules\Academics\Entities\AcademicsLevel::getAcademicLevelById($student->academic_level)}}</td>
        <td>{{Modules\Academics\Entities\Batch::getBatchNameById($student->batch)}}</td>
        <td>{{Modules\Academics\Entities\Section::getSectionNameById($student->section)}}</td>
        <td><span class="label label-danger">Deactive</span></td>
    </tr>

    @endforeach
    </tbody>
</table>
@endif

</body>
</html>