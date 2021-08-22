<h5><b>House Master:</b> {{$house->houseMaster->first_name}} {{$house->houseMaster->last_name}}
    @if ($house->housePrefect)
    | <b>House Prefect:</b> {{$house->housePrefect->first_name}} {{$house->housePrefect->last_name}}
    @endif
</h5>
<table class="table table-bordered evaluation-table" style="text-align: center">
    <tbody>       
        @for ($i = 1; $i <= $house->no_of_floors; $i++)
            <tr>
                <th>Floor {{$i}}</th>
                @foreach ($house->rooms as $room)
                    @if ($room->floor_no == $i)
                        <td>
                            <b>{{$room->name}}</b>

                            @php
                                $beds = $roomStudents->where('floor_no', $i)->where('room_id', $room->id);
                            @endphp
                            @for ($j = 1; $j <= $room->no_of_beds; $j++)
                                @php
                                    $bed = $beds->firstWhere('bed_no', $j);
                                @endphp
                                <div>Bed {{$j}}: 
                                    @if ($bed)
                                        {{$bed->student->first_name}}
                                        {{$bed->student->last_name}}
                                    @else
                                        <span class="text-danger">Unassigned</span>
                                    @endif
                                </div>
                            @endfor
                        </td>
                    @endif
                @endforeach
            </tr>
        @endfor
    </tbody>
  </table>