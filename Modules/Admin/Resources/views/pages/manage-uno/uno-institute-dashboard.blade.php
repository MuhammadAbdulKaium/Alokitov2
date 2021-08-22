@extends('layouts.master')
{{--@extends('admin::layouts.master')--}}

@section('styles')

@endsection

{{-- Content --}}
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-plus-square"></i> Institute Attendance
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="#">UNO</a></li>
                <li class="active">Institute Attendance</li>
            </ul>
        </section>
        <section class="content">
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif


            <div class="panel panel-default">
                <div class="panel-body">
                    <ul class="nav nav-tabs">
                        <li class="my-tab active"><a data-toggle="tab" href="#today_attendance">Today's Attendance</a></li>
                        <li class="my-tab"><a data-toggle="tab" href="#previous_attendance">Previous Attendance</a></li>
                    </ul>
                    {{--<hr/>--}}
                    <br/>
                    <div class="tab-content">
                        {{--today_attendance--}}
                        <div id="today_attendance" class="tab-pane fade in active">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="container-fluid">
                                        <div class="col-md-4">
                                            <div class="box-title">
                                                <h4><span class="fa fa-users"></span> Total Attendance</h4>
                                            </div>
                                            <div class="theme-style"></div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="chart">
                                                        <canvas id="totalAttendanceChart"></canvas>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <table class="table table-bordered text-center table-responsive table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Total Student</th>
                                                            <th>Present</th>
                                                            <th>Absent </th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            @if($attendanceInfo['status']=='success')
                                                                <td>{{$attendanceInfo['total_std']}}</td>
                                                                <td>{{$attendanceInfo['total_present_std']}} ({{$attendanceInfo['total_present_percentage']}} %)</td>
                                                                <td>{{$attendanceInfo['total_absent_std']}} ({{$attendanceInfo['total_absent_percentage']}} %)</td>
                                                            @else
                                                                <td>{{$attendanceInfo['std_count']}}</td>
                                                                <td>0 (0.00 %)</td>
                                                                <td>0 (0.00 %)</td>
                                                            @endif
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="box-title">
                                                <h4><span class="fa fa-users"></span> Male Attendance</h4>
                                            </div>
                                            <div class="theme-style"></div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="chart">
                                                        <canvas id="maleAttendanceChart"></canvas>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <table class="table table-bordered text-center table-responsive table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th> Male Total</th>
                                                            <th> Male Present</th>
                                                            <th> Male Absent</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            @if($attendanceInfo['status']=='success')
                                                                <td>{{$attendanceInfo['total_male_std']}}</td>
                                                                <td>{{$attendanceInfo['male_std_present']}} ({{$attendanceInfo['male_present_percentage']}} %)</td>
                                                                <td>{{$attendanceInfo['male_std_absent']}} ({{$attendanceInfo['male_absent_percentage']}} %)</td>
                                                            @else
                                                                <td>{{$attendanceInfo['std_count']}}</td>
                                                                <td>0 (0.00 %)</td>
                                                                <td>0 (0.00 %)</td>
                                                            @endif
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="box-title">
                                                <h4><span class="fa fa-users"></span> Female Attendance</h4>
                                            </div>
                                            <div class="theme-style"></div>
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <div class="chart">
                                                        <canvas id="femaleAttendanceChart"></canvas>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <table class="table table-bordered text-center table-responsive table-striped">
                                                        <thead>
                                                        <tr>
                                                            <th>Female Total</th>
                                                            <th>Female Present</th>
                                                            <th>Female Absent</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr>
                                                            @if($attendanceInfo['status']=='success')
                                                                <td>{{$attendanceInfo['total_female_std']}}</td>
                                                                <td>{{$attendanceInfo['female_std_present']}} ({{$attendanceInfo['female_present_percentage']}} %)</td>
                                                                <td>{{$attendanceInfo['female_std_absent']}} ({{$attendanceInfo['female_absent_percentage']}} %)</td>
                                                            @else
                                                                <td>{{$attendanceInfo['std_count']}}</td>
                                                                <td>0 (0.00 %)</td>
                                                                <td>0 (0.00 %)</td>
                                                            @endif
                                                        </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--role permission--}}
                        <div id="previous_attendance" class="tab-pane fade in">
                            <div class="row">
                                <div class="col-md-12">
                                    <form id="uno_institute_attendance_form" class="text-center">
                                        {{--token--}}
                                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                                        <div class="row">
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label class="control-label" for="academic_level">Academic Level</label>
                                                    <select id="academic_level" class="form-control academicLevel" name="academic_level" required>
                                                        <option value="" selected disabled>--- Select Level ---</option>
                                                        @if($allAcademicsLevel->count()>0)
                                                            @foreach($allAcademicsLevel as $level)
                                                                <option value="{{$level->id}}">{{$level->level_name}}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label class="control-label" for="batch">Batch</label>
                                                    <select id="batch" class="form-control academicBatch" name="batch" required>
                                                        <option value="" selected disabled>--- Select Batch ---</option>
                                                    </select>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label class="control-label" for="section">Section</label>
                                                    <select id="section" class="form-control academicSection" name="section" required>
                                                        <option value="" selected disabled>--- Select Section ---</option>
                                                    </select>
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label class="control-label" for="datepicker">From Date</label>
                                                    <input readonly class="form-control text-center datepicker" id="from_date" name="from_date" type="text">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="form-group">
                                                    <label class="control-label" for="datepicker">To Date</label>
                                                    <input readonly class="form-control text-center datepicker" id="to_date" name="to_date" type="text">
                                                    <div class="help-block"></div>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <label class="control-label" for="">Action</label> <br/>
                                                <button type="submit"  style="min-width: 180px;" class="btn btn-success text-bold">Submit</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- data table container --}}
                            <div class="row" id="uno_inst_attendance_list_container"> </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

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
    <script src="{{URL::asset('template-2/js/chart.min.js')}}"></script>
    <script>
        $(document).ready(function(){

            // total attendance chart
            var totalAttendanceChartCtx = document.getElementById("totalAttendanceChart").getContext('2d');
            var totalAttendanceChart = new Chart(totalAttendanceChartCtx, {
                type: 'pie',
                data: {
                    labels: ["Total Present", "Total Absent"],
                    datasets: [{
                        backgroundColor: ["#119117", "#dd0404"],
                        @if($attendanceInfo['status']=='success')
                        data: [{{$attendanceInfo['total_present_percentage']}}, {{$attendanceInfo['total_absent_percentage']}}],
                        @else
                        data: [0, 100],
                        @endif
                    }]
                }
            });
            // male attendance chart
            var maleAttendanceChartCtx = document.getElementById("maleAttendanceChart").getContext('2d');
            var maleAttendanceChart = new Chart(maleAttendanceChartCtx, {
                type: 'pie',
                data: {
                    labels: ["Male Present", "Male Absent"],
                    datasets: [{
                        backgroundColor: ["#119117", "#dd0404"],
                        @if($attendanceInfo['status']=='success')
                        data: [{{$attendanceInfo['male_present_percentage']}}, {{$attendanceInfo['male_absent_percentage']}}],
                        @else
                        data: [0, 100],
                        @endif
                    }]
                }
            });
            // total attendance chart
            var femaleAttendanceChartCtx = document.getElementById("femaleAttendanceChart").getContext('2d');
            var femaleAttendanceChart = new Chart(femaleAttendanceChartCtx, {
                type: 'pie',
                data: {
                    labels: ["Female Present", "Female Absent"],
                    datasets: [{
                        backgroundColor: ["#119117", "#dd0404"],
                        @if($attendanceInfo['status']=='success')
                        data: [{{$attendanceInfo['female_present_percentage']}}, {{$attendanceInfo['female_absent_percentage']}}],
                        @else
                        data: [0, 100],
                        @endif
                    }]
                }
            });

            //Date picker
            $('.datepicker').datepicker({
                autoclose: true
            });

            // request for batch list using level id
            jQuery(document).on('change','.academicLevel',function(){
                // get academic level id
                var level_id = $(this).val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "/academics/find/batch",
                    type: 'GET',
                    cache: false,
                    data: {'id': level_id }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // console.log(level_id);
                    },
                    success:function(data){
                        op+='<option value="" selected disabled>--- Select Batch ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                        }
                        // refresh attendance container row
                        $('#uno_inst_attendance_list_container').html('');
                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append(op);
                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');
                        // set value to the academic secton
                        $('.academicSession option:first').prop('selected',true);
                    },
                    error:function(){
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            });


            // request for section list using batch id
            jQuery(document).on('change','.academicBatch',function(){
                // get academic level id
                var batch_id = $(this).val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "/academics/find/section",
                    type: 'GET',
                    cache: false,
                    data: {'id': batch_id }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        //
                    },

                    success:function(data){
                        op+='<option value="" selected disabled>--- Select Section ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                        }
                        // refresh attendance container row
                        $('#uno_inst_attendance_list_container').html('');
                        // set value to the academic batch
                        $('.academicSection').html("");
                        $('.academicSection').append(op);
                    },
                    error:function(){
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });
            });


            // request for section list using batch and section id
            jQuery(document).on('change','.academicSection',function(){
                // get academic level id
                $('#uno_inst_attendance_list_container').html('');
            });

            // request for section list using batch and section id
            $('form#uno_institute_attendance_form').on('submit', function (e) {
                e.preventDefault();

                // class section details
                var class_id = $("#batch").val();
                var section_id = $("#section").val();
                var startDate = $('#from_date').val().replace('-','/');
                var endDate = $('#to_date').val().replace('-','/');
                // uno_inst_attendance_list_container
                var atd_list_container = $('#uno_inst_attendance_list_container');

                // checking
                if(class_id && section_id && startDate && endDate){
                    // checking date
                    if(startDate < endDate){
                        // ajax request
                        $.ajax({
                            url: '/admin/uno/find/student-previous-days-attendance-list',
                            type: 'POST',
                            cache: false,
                            data: $('form#uno_institute_attendance_form').serialize(),
                            datatype: 'application/json',

                            beforeSend: function() {
                                // show waiting dialog
                                waitingDialog.show('Loading...');
                            },

                            success:function(data){
                                // show waiting dialog
                                waitingDialog.hide();
                                // uno_inst_attendance_list_container
                                atd_list_container.html('');
                                // checking
                                if(data.status=='success'){
                                    atd_list_container.html(data.content);
                                }else {
                                    // sweet alert success
                                    swal("Warning", data.msg, "warning");
                                }
                            },

                            error:function(){
                                // show waiting dialog
                                waitingDialog.hide();
                                // uno_inst_attendance_list_container
                                atd_list_container.html('');
                                // sweet alert
                                swal("Error", 'Unable to load data form server', "error");
                            }
                        });
                    }else{
                        // uno_inst_attendance_list_container
                        atd_list_container.html('');
                        // sweet alert
                        swal("Warning", 'Invalid Date format', "warning");
                    }
                }else{
                    // uno_inst_attendance_list_container
                    atd_list_container.html('');
                    // sweet alert
                    swal("Warning", 'Please double check all inputs are selected.', "warning");
                }
            });


        });
    </script>
@endsection

