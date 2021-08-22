<h4 class="bg-green-active text-center">Batch, Section, Dropout, Promotion</h4>

<table class="table table-bordered text-center table-striped">
    <thead>
    <tr>
        <th>Total Student</th>
        <th>Female Student</th>
        <th>Promotion Total Student</th>
        <th>Promotion Female Student</th>
        <th>Fail total Student</th>
        <th>Fail female Student</th>
        <th>Dropout total Student</th>
        <th>Dropout female Student</th>
    </tr>
    </thead>

    <tbody>
    <tr>
        <td>{{$responseData['std_total']}}</td>
        <td>{{$responseData['std_female']}}</td>

        <td>{{$responseData['promotion_total']}}</td>
        <td>{{$responseData['promotion_female']}}</td>


        <td>{{$responseData['repeater_total']}}</td>
        <td>{{$responseData['repeater_female']}}</td>

        <td>{{$responseData['dropout_total']}}</td>
        <td>{{$responseData['dropout_female']}}</td>

    </tr>
    </tbody>
</table>