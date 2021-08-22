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
                        <h1 id="total_institute" class="text-center">0</h1>
                        <p class="text-center">Institute (s)</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-xs-6">
                <a href="#" class="button-wrap">
                    <div class="icon-wrap hidden-xs">
                        <p><i class="fa fa-users"></i></p>
                    </div>
                    <div class="icon-containt-wrap">
                        <h1 id="total_student" class="text-center">0</h1>
                        <p class="text-center">student (s)</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-xs-6">
                <a href="#" class="button-wrap">
                    <div class="icon-wrap hidden-xs">
                        <p><i class="fa fa-users"></i></p>
                    </div>
                    <div class="icon-containt-wrap">
                        <h1 id="total_teacher" class="text-center">0</h1>
                        <p class="text-center">Teacher (s)</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3 col-xs-6">
                <a href="#" class="button-wrap">
                    <div class="icon-wrap hidden-xs">
                        <p><i class="fa fa-users"></i></p>
                    </div>
                    <div class="icon-containt-wrap">
                        <h1 id="teacher_student_ratio" class="text-center">0:0</h1>
                        <p class="text-center">Teacher to Student Ratio</p>
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

                {{--session msg--}}
                @if(Session::has('success'))
                    <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                    </div>
                    @php session()->forget('success'); @endphp
                @elseif(Session::has('warning'))
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h4><i class="fa fa-times" aria-hidden="true"></i> {{ Session::get('warning') }} </h4>
                        @php session()->forget('warning'); @endphp
                    </div>
                @endif

                <div class="atten-bg">
                    <div class="box-title">
                        <h4><i class="fa fa-university"></i>
                            <b>Institute List</b>
                            <span class="pull-right"><a href="{{url('admin/uno/institute/summary')}}">View Summary</a></span>
                        </h4>
                        <input id="admin_id" type="hidden" value="{{Auth::user()->id}}">

                    </div>
                    <div class="theme-style"></div>
                    <br/>
                    <div class="col-md-12">
                        <table id="example1" class="table table-bordered table-responsive table-striped text-center">
                            <thead>
                            <tr class="bg-gray">
                                <th>#</th>
                                <th class="text-primary">Institute Name</th>
                                <th>Male Student (s)</th>
                                <th>Female Student (s)</th>
                                <th class="text-success">Total Student (s)</th>
                                <th class="text-danger">Total Teacher (s)</th>
                                <th>Teacher : Student</th>
                            </tr>
                            </thead>
                            <tbody>
                            {{--institute details--}}
                            @php $totalStdCount = 0; $totalTeacherCount = 0; $totalInstituteCount = 0;@endphp

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
                                            <a class="text-primary" href="/admin/uno/institute/login/campus/{{$campusProfile->id}}" target="_blank">
                                                {{$instituteProfile->institute_name}} ({{$campusProfile->name}})
                                            </a>
                                        </td>
                                        @php $totalStd = $campusProfile->student()->count(); @endphp
                                        <td>{{$campusProfile->student()->where(['gender'=>'Male'])->count()}}</td>
                                        <td>{{$campusProfile->student()->where(['gender'=>'Female'])->count()}}</td>
                                        <td class="text-success">{{$totalStd}}</td>
                                        <td class="text-danger">{{$campusProfile->staff()->count()}}</td>
                                        <td>{{alokito_gcd_ratio($campusProfile->staff()->count(), $totalStd)}}</td>
                                    </tr>

                                    {{--institute details--}}
                                    @php
                                        $totalStdCount += $totalStd;
                                        $totalTeacherCount += $campusProfile->staff()->count();
                                        $totalInstituteCount += 1;
                                    @endphp
                                @endforeach
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

{{--            //$('#teacher_student_ratio').text({{$teacherStudentRatio}});
--}}

{{-- Scripts --}}
@section('scripts')
    <script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
    <script>
        $(document).ready(function () {
            // dataTable
            $("#example1").DataTable();

            @php
                $precision = 3;
                $teacherStudentRatio = alokito_gcd_ratio($totalTeacherCount, $totalStdCount);
            @endphp

            // replace student counting with present and absent
            $('#total_institute').text({{$totalInstituteCount}});
            $('#total_student').text({{$totalStdCount}});
            $('#total_teacher').text({{$totalTeacherCount}});
            $('#teacher_student_ratio').text('{{$teacherStudentRatio}}');

        });
    </script>
@endsection
