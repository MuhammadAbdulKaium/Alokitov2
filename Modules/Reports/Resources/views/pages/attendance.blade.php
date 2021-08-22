
@extends('reports::layouts.report-layout')

<!-- page content -->
@section('page-content')
    <!-- grading scale -->
    <div class="col-md-12">
        <h4><strong>Attendance</strong></h4>
        <hr/>
        <ul class="nav nav-pills">
            <li class="active"><a data-toggle="tab" href="#my_statistics">Statistics</a></li>
            <li><a data-toggle="tab" href="#my_reports">Reports</a></li>
        </ul>
        <hr/>
        <div class="tab-content">
            <!-- statistics section -->
            <div id="my_statistics" class="tab-pane fade in active">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Attendance Information -->
                        {{--<label>Attendance Information</label>--}}
                        {{--<table class="table table-bordered table-striped text-center">--}}
                        {{--<tbody>--}}
                        {{--<tr>--}}
                        {{--<td><strong>Class: </strong></td>--}}
                        {{--<td><strong>Section: </strong> 0</td>--}}
                        {{--<td><strong>Student: </strong>{{$attendanceInfo->total_std}}</td>--}}
                        {{--</tr>--}}
                        {{--<tr>--}}
                        {{--<td colspan="2"><strong class="text-success">Present: {{$attendanceInfo->total_present_std}}</strong></td>--}}
                        {{--<td colspan="2"><strong  class="text-danger">Absent: {{$attendanceInfo->total_absent_std}}</strong></td>--}}
                        {{--</tr>--}}
                        {{--</tbody>--}}
                        {{--</table>--}}

                        <div class="row text-center">
                            <div class="col-md-4">
                                <label>Total Student Attendance</label>
                                <div class="chart">
                                    <canvas id="totalAttendanceChart"></canvas>
                                </div>
                                <table class="table table-responsive text-center table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Total</th>
                                        <th>Present (%)</th>
                                        <th>Absent (%)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{$attendanceInfo->total_std}}</td>
                                        <td>
                                            {{$attendanceInfo->total_present_std}}
                                            ({{round($attendanceInfo->total_present_percentage, 2)}} %)
                                        </td>
                                        <td>
                                            {{$attendanceInfo->total_absent_std}}
                                            ({{round($attendanceInfo->total_absent_percentage, 2)}} %)
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <label>Male Student Attendance</label>
                                <div class="chart">
                                    <canvas id="maleAttendanceChart"></canvas>
                                </div>
                                <table class="table table-responsive text-center table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Total Male</th>
                                        <th>Male Present (%)</th>
                                        <th>Male Absent (%)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{$attendanceInfo->total_male_std}}</td>
                                        <td>
                                            {{$attendanceInfo->male_std_present}}
                                            ({{round($attendanceInfo->male_present_percentage, 2)}} %)
                                        </td>
                                        <td>
                                            {{$attendanceInfo->male_std_absent}}
                                            ({{round($attendanceInfo->male_absent_percentage, 2)}} %)
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-4">
                                <label>Female Student Attendance</label>
                                <div class="chart">
                                    <canvas id="femaleAttendanceChart"></canvas>
                                </div>
                                <table class="table table-responsive text-center table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Total Female</th>
                                        <th>Female Present (%)</th>
                                        <th>Female Absent (%)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>{{$attendanceInfo->total_female_std}}</td>
                                        <td>
                                            {{$attendanceInfo->female_std_present}}
                                            ({{round($attendanceInfo->female_present_percentage, 2)}} %)
                                        </td>
                                        <td>
                                            {{$attendanceInfo->female_std_absent}}
                                            ({{round($attendanceInfo->female_absent_percentage, 2)}} %)
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- report section -->
            <div id="my_reports" class="tab-pane fade in">
                <div class="col-md-12">
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr></tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Student Average Attendance Report</td>
                            <td><a href="/reports/attendance/student/average/" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"> Download</a></td>
                        </tr>
                        <tr>
                            <td>Class-Section Average Attendance Report (Jasper report)</td>
                            <td><a href="/reports/attendance/class/section/average"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"> Download</a></td>
                        </tr>
                        <tr>
                            <td>Student Absent Days Report (subject attendance)</td>
                            <td><a href="/reports/attendance/student/absent/days/" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"> Download</a></td>
                        </tr>
                        <tr>
                            <td>
                                Class-Section Monthly Report (New)
                            </td>
                            <td>
                                <a href="{{url('/reports/attendance/class-section/monthly')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
                                    Download
                                </a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="{{URL::asset('js/charts/chart.min.js')}}"></script>
    <script>
        // Total Attendance Chart (pie)
        var totalAttendanceChartCtx = document.getElementById("totalAttendanceChart").getContext('2d');
        var totalAttendanceChartChart = new Chart(totalAttendanceChartCtx, {
            type: 'pie',
            data: {
                labels: ["Present", "Absent"],
                datasets: [{
                    backgroundColor: ["#119117", "#dd0404"],
                    data: [{{round($attendanceInfo->total_present_percentage, 2)}}, {{round($attendanceInfo->total_absent_percentage, 2)}}]
                }]
            }
        });

        // Male Attendance Chart (pie)
        var maleAttendanceChartCtx = document.getElementById("maleAttendanceChart").getContext('2d');
        var maleAttendanceChartChart = new Chart(maleAttendanceChartCtx, {
            type: 'pie',
            data: {
                labels: ["Present", "Absent"],
                datasets: [{
                    backgroundColor: ["#119117", "#dd0404"],
                    data: [{{round($attendanceInfo->male_present_percentage, 2)}}, {{round($attendanceInfo->male_absent_percentage, 2)}}]
                }]
            }
        });
        // Female Attendance Chart (pie)
        var femaleAttendanceChartCtx = document.getElementById("femaleAttendanceChart").getContext('2d');
        var femaleAttendanceChartChart = new Chart(femaleAttendanceChartCtx, {
            type: 'pie',
            data: {
                labels: ["Present", "Absent"],
                datasets: [{
                    backgroundColor: ["#119117", "#dd0404"],
                    data: [{{round($attendanceInfo->female_present_percentage, 2)}}, {{round($attendanceInfo->female_absent_percentage, 2)}}]
                }]
            }
        });

        // Average Attendance chart
        var averageAttendanceCtx = document.getElementById("averageAttendanceChart").getContext('2d');
        var averageAttendanceChart = new Chart(averageAttendanceCtx, {
            type: 'bar',
            data: {
                labels: ["Class One", "Class Two", "Class Three", "Class Four", "Class Five", "Class Six", "Class Seven","Class Eight","Class Nine","Class Ten"],
                datasets: [{
                    label: 'Present',
                    data: [50, 40, 38, 57, 68, 44, 70, 75, 75, 66],
                    backgroundColor: "#3c763d"
                }, {
                    label: 'Absent',
                    data: [45, 35, 30, 77, 48, 34, 50, 55, 55, 46],
                    backgroundColor: "#a94442"
                }]
            }
        });
    </script>
@endsection