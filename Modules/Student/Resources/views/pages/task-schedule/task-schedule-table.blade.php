@php
    $s = 1;
    $dayString = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    $alphabets = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z'];
@endphp
<table class="table table-bordered evaluation-table">
    <thead>
        <tr>
            <th>SL</th>
            <th colspan="2">Event</th>
            <th colspan="2">Time Table</th>
        </tr>
    </thead>
    <tbody>
        {{-- Daily Event Start--}}
        <tr>
            <td colspan="5"><b>Daily Event</b></td>
        </tr>
        @foreach ($dailyEvents as $dailyEvent)
            <tr>
                <td>{{ $s++ }}</td>
                <td colspan="4">{{ $dailyEvent[0]->activityCategory->category_name }}:</td>
            </tr>

            @php
                $diffThu = $dailyEvent->firstWhere('different_thursday', 1);
            @endphp
            <tr>
                <td></td>
                <td colspan="2">Events</td>
                @if ($diffThu)
                    <td>Except Thursday</td>
                    <td>Thursday</td>
                @else
                    <td colspan="2">Times</td>
                @endif
            </tr>
            @foreach ($dailyEvent as $activity)
                @php
                    $times = json_decode($activity->times);
                    $days = '(';
                    $len = count($times->days);
                    $i = 1;
                    foreach ($times->days as $day) {
                        if ($len == $i) {
                            $days .= $dayString[$day].')';
                        }else{
                            $days .= $dayString[$day].', ';
                        }
                        $i++;
                    }
                @endphp
                <tr>
                    <td></td>
                    <td>{{ $alphabets[$loop->index] }}</td>
                    <td>{{ $activity->activity->activity_name }} {{ (sizeof($times->days) < 7)?$days:'' }} {{ ($activity->extra_note)?'('.$activity->extra_note.')':'' }}</td>
                    @if ($activity->different_thursday == 1)
                        <td>{{ $times->startTime }} - {{ $times->endTime }} hrs</td>
                        <td>{{ $times->thuStartTime }} - {{ $times->thuEndTime }} hrs</td>
                    @else
                        <td colspan="2">{{ $times->startTime }} - {{ $times->endTime }} hrs</td>
                    @endif
                </tr>
            @endforeach
        @endforeach
        {{-- Daily Event End--}}

        {{--Weekly Event Start--}}
        <tr>
            <td colspan="5"><b>Weekly Event</b></td>
        </tr>
        @foreach ($weeklyEvents as $weeklyEvent)
            <tr>
                <td>{{ $s++ }}</td>
                <td colspan="4">{{ $weeklyEvent[0]->activityCategory->category_name }}:</td>
            </tr>
            
            <tr>
                <td></td>
                <td colspan="2">Events</td>
                <td colspan="2">Times</td>
            </tr>
            @foreach ($weeklyEvent as $activity)
                @php
                    $times = json_decode($activity->times);
                @endphp
                <tr>
                    <td></td>
                    <td>{{ $alphabets[$loop->index] }}</td>
                    <td>{{ $activity->activity->activity_name }} (Every {{ $dayString[$times->days[0]] }}) {{ ($activity->extra_note)?'('.$activity->extra_note.')':'' }}</td>
                    <td colspan="2">{{ $times->startTime }} - {{ $times->endTime }} hrs</td>
                </tr>
            @endforeach
        @endforeach
        {{--Weekly Event End--}}


        {{--Monthly Event Start--}}
        <tr>
            <td colspan="5"><b>Monthly Event</b></td>
        </tr>
        @foreach ($monthlyEvents as $monthlyEvent)
            <tr>
                <td>{{ $s++ }}</td>
                <td colspan="4">{{ $monthlyEvent[0]->activityCategory->category_name }}:</td>
            </tr>
            
            <tr>
                <td></td>
                <td colspan="2">Events</td>
                <td colspan="2">Times</td>
            </tr>
            @foreach ($monthlyEvent as $activity)
                @php
                    $times = json_decode($activity->times);
                @endphp
                <tr>
                    <td></td>
                    <td>{{ $alphabets[$loop->index] }}</td>
                    <td>{{ $activity->activity->activity_name }} (Every {{ ordinal($times->weekNumber) }} {{ $dayString[$times->days[0]] }}) {{ ($activity->extra_note)?'('.$activity->extra_note.')':'' }}</td>
                    <td colspan="2">{{ $times->startTime }} - {{ $times->endTime }} hrs</td>
                </tr>
            @endforeach
        @endforeach
        {{--Monthly Event End--}}

    </tbody>
</table>