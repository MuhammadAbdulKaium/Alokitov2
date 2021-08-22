<div class="box box-solid">
    <div class="box-header">
        <h4><i class="fa fa-plus-square"></i> List</h5>
    </div>
    <div class="box-body">
        <table class="table table-bordered text-center">
            <thead>
                <tr>
                    <th>
                        <input class="form-check-input all_check" type="checkbox" value=""> All
                    </th>
                    <th>Day</th>
                    <th>Date</th>
                    @php
                    $rows=8;
                    $cols=10;
                    @endphp
                    @for ($i = 1; $i <= $cols; $i++) <th>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="slots[]" value="{{ $i }}" id="slot">
                            <label class="form-check-label" for="slot">
                                Slot{{$i}}
                            </label>
                        </div>
                        </th>
                    @endfor
                </tr>
            </thead>
            <tbody>
                @foreach ($period as $date)
                    <tr>
                        <td><input type="checkbox" class="checkbox date-checkbox" name="dates[]" value="{{ $date->format('d/m/Y') }}"></td>
                        <td>{{ $date->format('l') }}</td>
                        <td>{{ $date->format('d/m/Y') }}</td>
                        @for ($i = 1; $i <= 10; $i++)
                            @php
                                $schedule = $previousSchedules->where('date', $date->format('Y-m-d'))->firstWhere('slot', $i);
                            @endphp

                            @if ($schedule)
                                @php
                                    $menu = $foodMenus->firstWhere('id', $schedule->menu_id);
                                @endphp
                                <td>
                                    <span>Time: <span class="text-success">{{ Carbon\Carbon::parse($schedule->time)->format('g:i A') }}</span></span><br>
                                    <span class="text-danger">{{ $menu->menu_name }}</span><br>
                                    <span data-toggle="modal" data-target="#totalPositions"
                                        style="cursor:pointer">Total Persons: <span
                                            class="text-primary">{{ $schedule->persons }}</span></span><br>
                                    {{-- <span data-toggle="modal" data-target="#costing" style="cursor:pointer">Costing:
                                        <span class="text-danger">7,500</span></span><br>
                                    <span class="text-warning">R: RN#10090</span> --}}
                                </td>
                            @else
                                <td></td>
                            @endif
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>