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
        <table id="example1" class="table table-striped text-center">
            <thead>
            <tr class="bg-gray">
                <th>id</th>
                <th>user_id</th>
                <th>roll_no</th>
                <th>name</th>
                <th>username</th>
                <th>email</th>
                <th>punch_id</th>
            </tr>
            </thead>
            <tbody>
            @foreach($allEnrollments as $index=>$enroll)
                <tr>
                    <td>{{($enroll->std_id)}}</td>
                    <td>{{($enroll->user_id)}}</td>
                    <td>
                        {{$enroll->gr_no}}
                    </td>
                    <td>
                        {{$enroll->first_name." ".$enroll->middle_name." ".$enroll->last_name}}
                    </td>
                    <td>
                        {{$enroll->username}}
                    </td>
                    <td>
                      {{$enroll->email}}
                    </td>
                    <td>
                       {{$enroll->student()->punch_id}}
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
@endif

</body>
</html>