<h4 class="bg-green-active text-center">Batch, Section, Repeater, Transfer</h4>

<table class="table table-bordered text-center table-striped">
    <thead>
    <tr>
        <th>Total Student</th>
        <th>Male Student</th>
        <th>Female Student</th>
        <th>Upbritti Male Student</th>
        <th>Upbritti Female Student</th>
        <th>Scholarship total Student</th>
        <th>Scholarship female Student</th>
        <th>Repeater total Student</th>
        <th>Repeater female Student</th>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td>{{$responseData['std_total']}}</td>
        <td>{{$responseData['std_male']}}</td>
        <td>{{$responseData['std_female']}}</td>
        <td>{{$responseData['upobritti_male']}}</td>
        <td>{{$responseData['upobritti_female']}}</td>
        <td>{{$responseData['scholarship_total']}}</td>
        <td>{{$responseData['scholarship_female']}}</td>
        <td>{{$responseData['repeater_total']}}</td>
        <td>{{$responseData['repeater_female']}}</td>
    </tr>
    </tbody>
</table>