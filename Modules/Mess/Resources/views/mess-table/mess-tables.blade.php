@forelse ($messTables as $messTable)
<table class="table table-bordered">
    <tbody>
        <tr>
            <th>Table: {{ $messTable->table_name }}</th>
            <th><a href="{{url('/mess/table/history/'.$messTable->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">History</a></th>
        </tr>
        <tr>
            <td>
                <div style="text-align: right">
                    <a class="text-success" href="{{url('mess/edit/table/'.$messTable->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-pencil"></i></a>
                    <a href="{{ url('/mess/delete/table/'.$messTable->id) }}"
                        class="text-danger" style="margin-left: 5px"
                        onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                        data-content="delete"><i class="fa fa-trash"></i></a>
                </div>
                <div>Table Name: {{ $messTable->table_name }}</div>
                <div>Total Seats: {{ $messTable->total_seats }}</div>
                <div>Concern HR: {{ $messTable->employee->first_name }} {{ $messTable->employee->last_name }} ({{ $messTable->employee->id }})</div>
                @php
                    $filledChairs = sizeof($messTableSeats->where('mess_table_id', $messTable->id));
                @endphp
                <div>Empty Chairs: {{ $messTable->total_seats - $filledChairs }}</div>
            </td>
            <td>
                <div class="mess-table">
                    {{-- First High Chairs --}}
                    <div class="mess-table-column">
                        <div class="mess-table-no-seat"></div>
                        @for ($i = $messTable->total_high_seats/2; $i > 0; $i--)
                            @php
                                $previousSeat = $messTableSeats->where('mess_table_id', $messTable->id)->firstWhere('seat_no', $i);
                                if ($previousSeat) {
                                    if ($previousSeat->person_type == 1) {
                                        $person = $students->firstWhere('std_id', $previousSeat->person_id);
                                        $personTxt = "Student: ".$person->first_name." ".$person->last_name." (ID: ".$person->std_id.")";
                                    }elseif ($previousSeat->person_type == 2) {
                                        $person = $employees->firstWhere('id', $previousSeat->person_id);
                                        $personTxt = "Hr/Fm: ".$person->first_name." ".$person->last_name." (ID: ".$person->id.")";
                                    }else{
                                        $personTxt = null;
                                    }
                                }else{
                                    $personTxt = null;
                                }
                            @endphp
                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $i)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $i }}">{{ $i }}</div>
                        @endfor
                        <div class="mess-table-no-seat"></div>
                    </div>

                    {{-- Normal Chairs --}}
                    @for ($i = ($messTable->total_high_seats/2)+1, $j = $messTable->total_seats; $i <= $messTable->total_seats/2; $i++, $j--)
                        <div class="mess-table-column">
                            @php
                                $previousSeat = $messTableSeats->where('mess_table_id', $messTable->id)->firstWhere('seat_no', $i);
                                if ($previousSeat) {
                                    if ($previousSeat->person_type == 1) {
                                        $person = $students->firstWhere('std_id', $previousSeat->person_id);
                                        $personTxt = "Student: ".$person->first_name." ".$person->last_name." (ID: ".$person->std_id.")";
                                    }elseif ($previousSeat->person_type == 2) {
                                        $person = $employees->firstWhere('id', $previousSeat->person_id);
                                        $personTxt = "Hr/Fm: ".$person->first_name." ".$person->last_name." (ID: ".$person->id.")";
                                    }else{
                                        $personTxt = null;
                                    }
                                }else{
                                    $personTxt = null;
                                }
                            @endphp
                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $i)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $i }}">{{ $i }}</div>
                            @for ($k = 0; $k< ($messTable->total_high_seats/2); $k++)
                                <div class="mess-table-no-seat"></div>
                            @endfor
                            @php
                                $previousSeat = $messTableSeats->where('mess_table_id', $messTable->id)->firstWhere('seat_no', $j);
                                if ($previousSeat) {
                                    if ($previousSeat->person_type == 1) {
                                        $person = $students->firstWhere('std_id', $previousSeat->person_id);
                                        $personTxt = "Student: ".$person->first_name." ".$person->last_name." (ID: ".$person->std_id.")";
                                    }elseif ($previousSeat->person_type == 2) {
                                        $person = $employees->firstWhere('id', $previousSeat->person_id);
                                        $personTxt = "Hr/Fm: ".$person->first_name." ".$person->last_name." (ID: ".$person->id.")";
                                    }else{
                                        $personTxt = null;
                                    }
                                }else{
                                    $personTxt = null;
                                }
                            @endphp
                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $j)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $j }}">{{ $j }}</div>
                        </div>                    
                    @endfor

                    {{-- Last High Chairs --}}
                    <div class="mess-table-column">
                        <div class="mess-table-no-seat"></div>
                        @for ($k = 0; $k < ($messTable->total_high_seats/2); $k++)
                            @php
                                $previousSeat = $messTableSeats->where('mess_table_id', $messTable->id)->firstWhere('seat_no', $i);
                                if ($previousSeat) {
                                    if ($previousSeat->person_type == 1) {
                                        $person = $students->firstWhere('std_id', $previousSeat->person_id);
                                        $personTxt = "Student: ".$person->first_name." ".$person->last_name." (ID: ".$person->std_id.")";
                                    }elseif ($previousSeat->person_type == 2) {
                                        $person = $employees->firstWhere('id', $previousSeat->person_id);
                                        $personTxt = "Hr/Fm: ".$person->first_name." ".$person->last_name." (ID: ".$person->id.")";
                                    }else{
                                        $personTxt = null;
                                    }
                                }else{
                                    $personTxt = null;
                                }
                            @endphp
                            <div class="mess-table-seat {{ ($previousSeat)?'text-success':'text-danger' }} {{ ($personSeatNo == $i)?'searched-seat':'' }}" data-toggle="tooltip" data-html="true" title="{{ ($personTxt)?$personTxt:'Empty' }}" data-table-id="{{ $messTable->id }}" data-seat-no="{{ $i }}">{{ $i++ }}</div>
                        @endfor
                        <div class="mess-table-no-seat"></div>
                    </div>
                </div>
            </td>
        </tr>
    </tbody>
</table>
@empty
    <div class="text-danger" style="text-align: center">No Table found!</div>
@endforelse