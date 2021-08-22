<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Schedule Table</title>
    <style>
        table, td, th {
        border: 1px solid black;
        text-align: center;
        }

        table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <table>
        <caption><b>Food Menu Schedules (slot 1 - 5)</b></caption>
        <thead>
            <tr>
                <th>Day</th>
                <th>Date</th>
                @for ($i = 1; $i <= 5; $i++) 
                    <th>Slot{{$i}}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach ($period as $date)
                <tr>
                    <td>{{ $date->format('l') }}</td>
                    <td>{{ $date->format('d/m/Y') }}</td>
                    @for ($i = 1; $i <= 5; $i++)
                        @php
                            $schedule = $previousSchedules->where('date', $date->format('Y-m-d'))->firstWhere('slot', $i);
                        @endphp

                        @if ($schedule)
                            @php
                                $menu = $foodMenus->firstWhere('id', $schedule->menu_id);
                            @endphp
                            <td>
                                <span>Time: {{ Carbon\Carbon::parse($schedule->time)->format('g:i A') }}</span><br>
                                <span>{{ $menu->menu_name }}</span><br>
                                <span>Total Persons: {{ $schedule->persons }}</span><br>
                            </td>
                        @else
                            <td></td>
                        @endif
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>

    <table>
        <caption><b>Food Menu Schedules (slot 6 - 10)</b></caption>
        <thead>
            <tr>
                <th>Day</th>
                <th>Date</th>
                @for ($i = 6; $i <= 10; $i++) 
                    <th>Slot{{$i}}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach ($period as $date)
                <tr>
                    <td>{{ $date->format('l') }}</td>
                    <td>{{ $date->format('d/m/Y') }}</td>
                    @for ($i = 6; $i <= 10; $i++)
                        @php
                            $schedule = $previousSchedules->where('date', $date->format('Y-m-d'))->firstWhere('slot', $i);
                        @endphp

                        @if ($schedule)
                            @php
                                $menu = $foodMenus->firstWhere('id', $schedule->menu_id);
                            @endphp
                            <td>
                                <span>Time: {{ Carbon\Carbon::parse($schedule->time)->format('g:i A') }}</span><br>
                                <span>{{ $menu->menu_name }}</span><br>
                                <span>Total Persons: {{ $schedule->persons }}</span><br>
                            </td>
                        @else
                            <td></td>
                        @endif
                    @endfor
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>