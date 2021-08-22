@extends('admin::layouts.master')

{{-- Web site Title --}}

@section('styles')
    {{--datatable style sheet--}}
    <link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@stop

{{-- Content --}}
@section('content')
    <section class="4-big-button">
        <div class="container-fluid">
            <div class="col-md-3 col-xs-6">
                <a href="#">
                    <div class="icon-wrap hidden-xs">
                        <p><i class="fa fa-university"></i></p>
                    </div>
                    <div class="icon-containt-wrap">
                        <h1 class="text-center">{{$instituteList?$instituteList->count():0}}</h1>
                        <p class="text-center">Total Institute (s)</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-xs-6">
                <a href="#">
                    <div class="icon-wrap hidden-xs">
                        <p><i class="fa fa-users"></i></p>
                    </div>
                    <div class="icon-containt-wrap">
                        <h1 id="total_std" class="text-center">0</h1>
                        <p class="text-center">Total Student (s)</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-xs-6">
                <a href="#" class="button-wrap">
                    <div class="icon-wrap hidden-xs">
                        <p><i class="fa fa-users"></i></p>
                    </div>
                    <div class="icon-containt-wrap">
                        <h1 id="total_present" class="text-center">0</h1>
                        <p class="text-center">Present (%)</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-xs-6">
                <a href="#">
                    <div class="icon-wrap hidden-xs">
                        <p><i class="fa fa-users"></i></p>
                    </div>
                    <div class="icon-containt-wrap">
                        <h1 id="total_absent" class="text-center">0</h1>
                        <p class="text-center">Absent (%)</p>
                    </div>
                </a>
            </div>
        </div>
    </section>
    {{--clearfix--}}
    <div class="clearfix"></div>
    <section>
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="atten-bg">
                    <div class="box-title">
                        <h4><i class="fa fa-university"></i> <b>Institute List</b> </h4>
                    </div>
                    <div class="theme-style"></div>
                    <br/>
                    <div class="col-md-12">
                        <table id="example1" class="table table-bordered table-responsive table-striped text-center">
                            <thead>
                            <tr class="bg-gray">
                                <th>#</th>
                                <th class="text-default">Institute Name</th>
                                <th class="text-primary">Total Student</th>
                                <th class="text-success">Total Present</th>
                                <th class="text-danger">Total Absent</th>
                                <th>Male Present</th>
                                <th>Female Present</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{--institute attendance details--}}
                            @php $totalStdCount = 0; $totalPresentCount = 0; $totalAbsentCount = 0; $percentage = 0;@endphp

                            @if($instituteList AND $instituteList->count()>0)
                                {{--institute and campus--}}
                                @foreach($instituteList as $index=>$singleInstitute)
                                    @php
                                        $campusProfile = $singleInstitute->campus();
                                        $instituteProfile = $singleInstitute->institute();
                                    @endphp
                                    <tr>
                                        <td>{{($index+1)}}</td>
                                        <td>
                                            <a href="/admin/uno/institute/login/campus/{{$campusProfile->id}}" target="_blank">
                                                {{$instituteProfile->institute_name}} ({{$campusProfile->name}})
                                            </a>
                                        </td>
                                        {{--find institute attendance details--}}
                                        @php $attendanceList = (object)$instituteAttendanceArrayList[$singleInstitute->campus_id];@endphp
                                        {{--checking institute attendance details--}}
                                        @if($attendanceList->status=='success')
                                            @php
                                                $totalStudent = $attendanceList->total_std;
                                                $presentStudent = $attendanceList->total_present_std;
                                                $absentStudent = $attendanceList->total_absent_std;
                                            @endphp
                                            <td class="text-primary">{{$totalStudent}}</td>
                                            <td class="text-success">{{$presentStudent}} ({{$attendanceList->total_present_percentage}}%)</td>
                                            <td class="text-danger">{{$absentStudent}} ({{$attendanceList->total_absent_percentage}}%)</td>
                                            <td>{{$attendanceList->male_present_percentage}}%</td>
                                            <td>{{$attendanceList->female_present_percentage}}%</td>
                                            {{--total counting--}}
                                            @php $totalStdCount += $totalStudent; $totalPresentCount += $presentStudent; $totalAbsentCount += $absentStudent;@endphp
                                        @else
                                            <td>{{$attendanceList->std_count}}</td>
                                            <td colspan="5">-</td>
                                            {{--total counting--}}
                                            @php $totalStdCount += $attendanceList->std_count; @endphp
                                        @endif
                                    </tr>
                                @endforeach

                                {{--total student perentage calculation--}}
                                @php
                                    $percentage = ($totalPresentCount/$totalStdCount)*100;
                                @endphp
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal" id="globalModal" tabindex="-1" role="dialog"  aria-labelledby="esModalLabel" aria-hidden="true">
        <div id="modal-dialog" class="modal-dialog">
            <div class="modal-content" >
                <div class="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Scripts --}}
@section('scripts')
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            // dataTable
            $("#example1").DataTable();

            @php $precision = 3; @endphp

            // replace student counting with present and absent
            $('#total_std').text({{$totalStdCount}});
            $('#total_present').text({{(substr(number_format(($percentage), $precision + 1, '.', ''), 0, -1))}});
            $('#total_absent').text({{(substr(number_format((100-$percentage), $precision + 1, '.', ''), 0, -1))}});

        });
    </script>
@endsection
