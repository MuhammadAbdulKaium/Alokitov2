<div class="col-md-12">
    {{--student list checking--}}
    @if(!empty($myStdList) AND !empty($attendanceArrayList))
        {{--student list checking--}}
        @if(!empty($academicHolidayList) AND count($academicHolidayList)>0)
            {{--student list checking--}}
            @if(!empty($academicWeeKOffDayList) AND $academicWeeKOffDayList->status=='success')
                {{--$academicWeeKOffDayList--}}
                @php $academicWeeKOffDayList = $academicWeeKOffDayList->week_off_list; @endphp

                <p class="bg-green-active text-center text-bold">Student Summary</p>
                <table class="table table-bordered table-responsive table-striped text-center">
                    <thead>
                    <tr>
                        <th>Total Student</th>
                        <th>Male (s)</th>
                        <th>Female (s)</th>
                    </tr>
                    </thead>

                    <tbody>
                    <tr>
                        <td>{{$myStdList->total}}</td>
                        <td>{{$myStdList->male}}</td>
                        <td>{{$myStdList->female}}</td>
                    </tr>
                    </tbody>
                </table>
                {{--datatable style sheet--}}
                <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
                {{--lebel--}}
                <p class="bg-green-active text-center text-bold">Attendance Summary</p>
                <table id="example1" class="table table-bordered table-responsive table-striped text-center">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Total Present</th>
                        <th>Total Absent</th>
                        <th>Male Present</th>
                        <th>Male Absent</th>
                        <th>Female Present</th>
                        <th>Female Absent</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($attendanceArrayList as $date=>$attendance)
                        {{--checking national holiday list--}}
                        @if(array_key_exists($date, $academicHolidayList)==false)
                            {{--checking week off day--}}
                            @if(array_key_exists($date, $academicWeeKOffDayList)==false)
                                {{--{{dd($academicWeeKOffDayList)}}--}}
                                <tr>
                                    <td>{{date('d M, Y', strtotime($date))}}</td>
                                    <td>{{$attendance['total_present']}} ({{$attendance['total_present_percent']}} %)</td>
                                    <td>{{$attendance['total_absent']}} ({{$attendance['total_absent_percent']}} %)</td>
                                    <td>{{$attendance['male_present']}} ({{$attendance['male_present_percent']}} %)</td>
                                    <td>{{$attendance['male_absent']}} ({{$attendance['male_absent_percent']}} %)</td>
                                    <td>{{$attendance['female_present']}} ({{$attendance['female_present_percent']}} %)</td>
                                    <td>{{$attendance['female_absent']}} ({{$attendance['female_absent_percent']}} %)</td>
                                </tr>
                            @else
                                <tr>
                                    <td>{{date('d M, Y', strtotime($date))}}</td>
                                    <td></td>
                                    <td></td>
                                    <td>{{(string)$academicWeeKOffDayList[$date]}}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td>{{date('d M, Y', strtotime($date))}}</td>
                                <td></td>
                                <td></td>
                                <td>{{(string)$academicHolidayList[$date]}}</td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            @else
                {{--WeekOff day not found msg--}}
                <div class="alert-warning alert-auto-hide alert fade in text-center text-bold" style="opacity: 474.119;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5><i class="fa fa-warning"></i> WeekOff day Setting Not Found for this class section</h5>
                </div>
            @endif
        @else
            {{--National Holiday not found msg--}}
            <div class="alert-warning alert-auto-hide alert fade in text-center text-bold" style="opacity: 474.119;">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                <h5><i class="fa fa-warning"></i> National Holiday Setting Not Found</h5>
            </div>
        @endif
    @else
        {{--No Student Found msg--}}
        <div class="alert-warning alert-auto-hide alert fade in text-center text-bold" style="opacity: 474.119;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h5><i class="fa fa-warning"></i> No Student Found </h5>
        </div>
    @endif
</div>
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<script>
    $(document).ready(function () {
        $("#example2").DataTable();
        $('#example1').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": false,
            "info": true,
            "autoWidth": true
        });
    });
</script>