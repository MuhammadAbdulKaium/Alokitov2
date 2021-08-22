<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Employee List</title>
    <style type="text/css">
        table>thead>tr>th, table>tbody>tr>td{
            text-align: center;
            height: 20px;
        }
    </style>
</head>
<body>

@if($allEmployee->count()>0)
    <table id="example1" class="table table-striped" style="text-align: center">
        <thead>
        <tr>
            <th>#</th>
            <th>Employee No</th>
            <th>Name</th>
            <th>Email/Login Id</th>
            <th>Department</th>
            <th>Designation</th>
            <th>Category</th>
        </tr>
        </thead>
        <tbody>
        @php $i=1; @endphp
            @foreach($allEmployee as $employee)
                <tr>
                    <td>{{$i++}}</td>
                    <td>{{$employee->id}}</td>
                    <td>{{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}}</td>
                    <td>{{$employee->email}}</td>
                    <td>{{$employee->department()->name}}</td>
                    <td>{{$employee->designation()->name}}</td>
                    <td>@if($employee->category==1) Teaching @else Non-Teaching @endif</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

</body>
</html>