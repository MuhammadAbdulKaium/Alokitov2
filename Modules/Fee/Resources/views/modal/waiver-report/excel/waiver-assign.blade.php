    <table>
        <thead>
        <tr>
            <th># NO</th>
            <th>STD ID</th>
            <th>Name</th>
            <th>Roll</th>
            <th>Class</th>
            <th>Section</th>
            <th>Fee Head</th>
            <th>Waiver Type</th>
            <th>Waiver Amount/Percent</th>
        </tr>
        </thead>
        <tbody>
        @php $i=1; @endphp
        @foreach($waiverAssignList as $waiver)
            <tr>
                <td>{{$i++}}</td>
                @php $studentProfile=$waiver->studentProfile(); @endphp
                <td>{{$studentProfile->username}}</td>
                <td>{{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}}</td>
                <td>{{$studentProfile->gr_no}}</td>
                <td>
                    @if ($waiver->batch()->get_division())
                        {{$waiver->batch()->batch_name.' '.$waiver->batch()->get_division()->name}}
                    @else
                        {{$waiver->batch()->batch_name}}
                    @endif
                </td>
                <td>{{$waiver->section()->section_name}}</td>
                <td>{{$waiver->feehead()->name}}</td>
                <td>{{$waiver->waiver_type()->name}}</td>
                <td>{{$waiver->amount}}
                    @if($waiver->amount_percentage==1) %
                    @else
                        ৳
                    @endif
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
