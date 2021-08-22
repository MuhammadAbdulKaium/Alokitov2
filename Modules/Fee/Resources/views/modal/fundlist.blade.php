@if(!empty($feeFundList))

    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Fund Name</th>
            <th>Distribution</th>
        </tr>
        </thead>
        <tbody>
        @foreach($feeFundList as $key=>$fund)
        <tr>
            <td>{{$fund}}</td>
            <td><input type="text" name="fund[{{$key}}]" class="form-control"></td>
        </tr>
            @endforeach
        </tbody>
    </table>
@endif