@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Cadet Academics |<small>Exam Attendance</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Academics</a></li>
            <li>SOP Setup</li>
            <li>Exam</li>
            <li class="active">Exam Attendance</li>
        </ul>
    </section>
    <section class="content">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> 
                <a href="#" class="close" style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  
                <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" 
                aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  
                <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" 
                aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Academics Exam Attendance </h3>
            </div>
            <div class="box-body table-responsive">
                <form method="POST" action="{{ url('academics/exam/print/attendance/sheet') }}" target="_blank">
                    @csrf
                    <div class="row"  style="margin-bottom: 50px">
                        <div class="col-sm-1">
                            <select name="academicYearId" id="" class="form-control select-academic-year">
                                <option value="">-Year-</option>
                                @foreach($academicYears as $academicYear)
                                    <option value="{{$academicYear->id}}">{{$academicYear->year_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="semesterId" id="" class="form-control select-term" required>
                                <option value="">Term / Semester*</option>
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="examId" id="" class="form-control select-exam-name">
                                <option value="">Exam Name*</option>
                                @foreach ($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->exam_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="batchId" id="" class="form-control select-class">
                                <option value="">Select Class*</option>
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <select name="sectionId" id="" class="form-control select-form" required>
                                <option value="">Select Form*</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="subjectId" id="" class="form-control select-subject">
                                <option value="">Select Subject*</option>
                            </select>
                        </div>
                        <div class="col-sm-2">
                            @if (in_array(4300 ,$pageAccessData))
                            <button class="btn btn-sm btn-primary search-attendance-sheet-button" type="button"><i class="fa fa-search"></i></button>
                            @endif
                            @if (in_array(4350 ,$pageAccessData))
                            <button class="btn btn-sm btn-primary view-attendance-sheet-button" type="button"><i class="fa fa-eye"></i></button>
                            @endif
                            @if (in_array("academics/exam/print/attendance/sheet" ,$pageAccessData))
                            <button class="btn btn-sm btn-primary"><i class="fa fa-print"></i></button>
                            @endif
                        </div>
                    </div>
                </form>

                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8 attendance-table-holder">
                        <h5 class="attendance-table-heading"></h5>
                        <table class="table table-bordered attendance-table">
                            
                        </table>
                        <div class="attendance-save-btn-holder">

                        </div>
                    </div>
                    <div class="col-sm-2"></div>
                </div>
            </div>
        </div>

        <input type="hidden" class="can_save" value="{{ (in_array("academics/exam/save-students-attendance" ,$pageAccessData))?true:false }}">
    </section>
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('.date').datepicker();

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        var searchFormDatas = {
            academicYearId: "",
            semesterId: "",
            examId: "",
            batchId: "",
            sectionId: "",
            subjectId: "",
        }
        var datasToSaveAttendance = null;
        var schedule = null;

        function setSearchFormDatas() {
            searchFormDatas.academicYearId = $('.select-academic-year').val();
            searchFormDatas.semesterId = $('.select-term').val();
            searchFormDatas.examId = $('.select-exam-name').val();
            searchFormDatas.batchId = $('.select-class').val();
            searchFormDatas.sectionId = $('.select-form').val();
            searchFormDatas.subjectId = $('.select-subject').val();
        }

        $('.select-exam-name').change(function (){
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/exam/search-classes/from-exam') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'examNameId': $(this).val(),
                }, //see the _token
                datatype: 'application/json',

                beforeSend: function () {},

                success: function (data) {
                    var classText = '<option value="">Select Class*</option>';

                    data.forEach(element => {
                        classText += '<option value="'+element.id+'">'+element.batch_name+'</option>';
                    });

                    $('.select-class').empty();
                    $('.select-class').append(classText);
                    $('.select-form').empty();
                    $('.select-form').append('<option value="">Select Form*</option>');
                    $('.select-subject').empty();
                    $('.select-subject').append('<option value="">Select Subject*</option>');
                    $('.select-mark-parameter').empty();
                    $('.select-mark-parameter').append('<option value="">Criteria*</option>');
                },

                error: function (error) {
                    console.log(error);
                    waitingDialog.hide();
                }
            });
            // Ajax Request End
        });

        $('.select-class').change(function (){
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/exam/search-forms') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'batch': $(this).val()
                }, //see the _token
                datatype: 'application/json',

                beforeSend: function () {},

                success: function (data) {
                    var txt = '<option value="">Select Form*</option>';
                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.section_name+'</option>';
                    });

                    $('.select-form').empty();
                    $('.select-form').append(txt);
                    $('.select-subject').empty();
                    $('.select-subject').append('<option value="">Select Subject*</option>');
                    $('.select-mark-parameter').empty();
                    $('.select-mark-parameter').append('<option value="">Criteria*</option>');
                },

                error: function (error) {
                    console.log(error);
                    waitingDialog.hide();
                }
            });
            // Ajax Request End
        });

        $('.select-form').change(function () {
            setSearchFormDatas();

            if (searchFormDatas.academicYearId && searchFormDatas.semesterId) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/academics/exam/search-subjects-from/exam-schedules') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'academicYearId': searchFormDatas.academicYearId,
                        'semesterId': searchFormDatas.semesterId,
                        'examId': searchFormDatas.examId,
                        'batchId': searchFormDatas.batchId
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        var txt = '<option value="">Select Subject*</option>';
                        data.forEach(element => {
                            txt += '<option value="'+element.id+'">'+element.subject_name+'</option>';
                        });

                        $('.select-subject').empty();
                        $('.select-subject').append(txt);
                        $('.select-mark-parameter').empty();
                        $('.select-mark-parameter').append('<option value="">Criteria*</option>');
                    },

                    error: function (error) {
                        console.log(error);
                        waitingDialog.hide();
                    }
                });
                // Ajax Request End
            } else {
                $(this).val("");
                swal('Error!', "Please select Year and Term first.", 'error');
            }
        });

        function showAttendanceSheet(type) {
            var allFieldHasValue = true;
            console.log(searchFormDatas);

            for (const property in searchFormDatas) {
                if (searchFormDatas[property] == "") {
                    allFieldHasValue = false;
                }
            }

            if (allFieldHasValue) {
                datasToSaveAttendance = searchFormDatas;

                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/academics/exam/search-students/for-attendance') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'academicYearId': datasToSaveAttendance.academicYearId,
                        'semesterId': datasToSaveAttendance.semesterId,
                        'examId': datasToSaveAttendance.examId,
                        'subjectId': datasToSaveAttendance.subjectId,
                        'batchId': datasToSaveAttendance.batchId,
                        'sectionId': datasToSaveAttendance.sectionId,
                        'type': type,
                        'canSave': $('.can_save').val()
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },
                
                    success: function (data) {
                        console.log(data);
                        waitingDialog.hide();
                        $('.attendance-table-holder').html(data[0]);     
                        schedule = data[1];                  
                    },

                    error: function (error) {
                        console.log(error);
                        waitingDialog.hide();
                    }
                });
                // Ajax Request End
            }else{
                swal("Error!", "Please fill up all the fields first!", "error");
            }
        }

        $('.search-attendance-sheet-button').click(function () {
            setSearchFormDatas();
            showAttendanceSheet('search');
        });

        $('.view-attendance-sheet-button').click(function () {
            setSearchFormDatas();
            showAttendanceSheet('view');
        });

        $(document).on('click', '.attendance-save-btn', function () {
            var table = $(this).parent().prev();
            var allCheckBoxes = table.find('.attendance-select');
            var attendance = {};
            var currentDateTime = new Date();
            var scheduleDateTime = new Date(schedule.date+" "+schedule.startTime);

            allCheckBoxes.each((index, data) => {
                if (!attendance[$(data).data('criteria-id')]) {
                    attendance[$(data).data('criteria-id')] = {};
                }
                if ($(data).is(':checked')) {
                    attendance[$(data).data('criteria-id')][$(data).val()] = 1;
                }else{
                    attendance[$(data).data('criteria-id')][$(data).val()] = 0;
                }
            });

            // if (currentDateTime >= scheduleDateTime) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/academics/exam/save-students-attendance') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'academicYearId': datasToSaveAttendance.academicYearId,
                        'semesterId': datasToSaveAttendance.semesterId,
                        'examId': datasToSaveAttendance.examId,
                        'subjectId': datasToSaveAttendance.subjectId,
                        'criteriaId': datasToSaveAttendance.markParameterId,
                        'batchId': datasToSaveAttendance.batchId,
                        'sectionId': datasToSaveAttendance.sectionId,
                        'attendance': attendance,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },
                
                    success: function (result) {
                        waitingDialog.hide();
                        $('.attendance-save-btn').text('Update');
                        swal("Success!", result, "success");
                    },

                    error: function (error) {
                        console.log(error);
                        waitingDialog.hide();
                    }
                });
                // Ajax Request End
            // } else {
                // swal("Error!", "Exam date & time hasn't arrived yet.", "warning");
            // }
        });

        $(document).on('click', '.attendance-all-select', function () {
            var criteriaId = $(this).data('criteria-id');
            if ($(this).is(':checked')) {
                $('.attendance-select[data-criteria-id="'+criteriaId+'"]').prop('checked', true);
            }else{
                $('.attendance-select[data-criteria-id="'+criteriaId+'"]').prop('checked', false);
            }
        });
    });
</script>
@stop