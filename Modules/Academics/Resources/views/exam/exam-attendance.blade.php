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
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                                                                                          style="text-decoration:none" data-dismiss="alert"
                                                                                          aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Academics Exam Attendance </h3>
            </div>
            <div class="box-body table-responsive">
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
                        <select name="termId" id="" class="form-control select-term" required>
                            <option value="">Term / Semester*</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="examNameId" id="" class="form-control select-exam-name">
                            <option value="">Exam Name*</option>
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
                    <div class="col-sm-1">
                        <select name="markParameterId" id="" class="form-control select-mark-parameter">
                            <option value="">Criteria*</option>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-sm btn-primary search-attendance-sheet-button"><i class="fa fa-search"></i></button>
                        <button class="btn btn-sm btn-primary view-attendance-sheet-button"><i class="fa fa-eye"></i></button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-6">
                        <h5 class="attendance-table-heading"></h5>
                        <table class="table table-bordered attendance-table">
                            
                        </table>
                        <div class="attendance-save-btn-holder">

                        </div>
                    </div>
                    <div class="col-sm-3"></div>
                </div>
            </div>
        </div>
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
            markParameterId: "",
        }

        var datasToSaveAttendance = null;

        function setSearchFormDatas() {
            searchFormDatas.academicYearId = $('.select-academic-year').val();
            searchFormDatas.semesterId = $('.select-term').val();
            searchFormDatas.examId = $('.select-exam-name').val();
            searchFormDatas.batchId = $('.select-class').val();
            searchFormDatas.sectionId = $('.select-form').val();
            searchFormDatas.subjectId = $('.select-subject').val();
            searchFormDatas.markParameterId = $('.select-mark-parameter').val();
        }

        $('.select-academic-year').change(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/exam/search-semester') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'yearId': $(this).val()
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">Term / Semester*</option>';
                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.name+'</option>';
                    });

                    $('.select-term').empty();
                    $('.select-term').append(txt);
                    $('.select-exam-name').empty();
                    $('.select-exam-name').append('<option value="">Exam Name*</option>');
                    $('.select-class').empty();
                    $('.select-class').append('<option value="">Select Class*</option>');
                    $('.select-form').empty();
                    $('.select-form').append('<option value="">Select Form*</option>');
                    $('.select-subject').empty();
                    $('.select-subject').append('<option value="">Select Subject*</option>');
                    $('.select-mark-parameter').empty();
                    $('.select-mark-parameter').append('<option value="">Criteria*</option>');
                }
            });
            // Ajax Request End
        });

        $('.select-term').change(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/exam/search-exam') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'termId': $(this).val()
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">Exam Name*</option>';
                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.exam_name+'</option>';
                    });

                    $('.select-exam-name').empty();
                    $('.select-exam-name').append(txt);
                    $('.select-class').empty();
                    $('.select-class').append('<option value="">Select Class*</option>');
                    $('.select-form').empty();
                    $('.select-form').append('<option value="">Select Form*</option>');
                    $('.select-subject').empty();
                    $('.select-subject').append('<option value="">Select Subject*</option>');
                    $('.select-mark-parameter').empty();
                    $('.select-mark-parameter').append('<option value="">Criteria*</option>');
                }
            });
            // Ajax Request End
        });

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
                }
            });
            // Ajax Request End
        });

        $('.select-form').change(function () {
            setSearchFormDatas();

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
                }
            });
            // Ajax Request End
        });

        $('.select-subject').change(function () {
            setSearchFormDatas();

            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/exam/search-mark-parameters-from/exam-schedules') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'academicYearId': searchFormDatas.academicYearId,
                    'semesterId': searchFormDatas.semesterId,
                    'examId': searchFormDatas.examId,
                    'batchId': searchFormDatas.batchId,
                    'subjectId': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">Criteria*</option>';
                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.name+'</option>';
                    });

                    $('.select-mark-parameter').empty();
                    $('.select-mark-parameter').append(txt);
                }
            });
            // Ajax Request End
        });

        function tConvert (time) {
            // Check correct time format and split into components
            time = time.toString ().match (/^([01]\d|2[0-3])(:)([0-5]\d)(:[0-5]\d)?$/) || [time];

            if (time.length > 1) { // If time format correct
                time = time.slice (1);  // Remove full string match value
                time[5] = +time[0] < 12 ? 'AM' : 'PM'; // Set AM/PM
                time[0] = +time[0] % 12 || 12; // Adjust hours
            }
            return time.join (''); // return adjusted time or original string
        }

        function showAttendanceSheet(type) {
            var allFieldHasValue = true;

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
                        'criteriaId': datasToSaveAttendance.markParameterId,
                        'batchId': datasToSaveAttendance.batchId,
                        'sectionId': datasToSaveAttendance.sectionId
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        var table = '';
                        var studentTr = '';
                        var sl = 1;
                        var previousAttendance = (data[0])?JSON.parse(data[0].attendance):null;

                        var examStartsIn = (data[2].startTime)?tConvert(data[2].startTime):'00:00';
                        var examEndsIn = (data[2].endTime)?tConvert(data[2].endTime):'00:00';
                        $('.attendance-table-heading').text('Exam Date: '+data[2].date+' | Starts In: '+examStartsIn+' | Ends In: '+examEndsIn+'')

                        if (data[1].length > 0) {
                            if (type == 'search') {
                                data[1].forEach(function (ele) {
                                    var checked = '';
                                    if (previousAttendance) {
                                        checked = (previousAttendance[ele.std_id])?(previousAttendance[ele.std_id] == 1)?'checked':'':'';
                                    }

                                    studentTr += '<tr><td>'+sl+++'</td><td>'+ele.first_name+' '+ele.last_name+
                                    '</td><td>'+ele.gr_no+'</td><td><input type="checkbox" value="'+ele.std_id+
                                    '" class="attendance-select" '+checked+'></td></tr>';
                                });

                                table = '<thead><tr><th>SL</th><th>Cadet Names</th><th>Roll No</th><th><input type="checkbox" class="attendance-all-select"></th></tr></thead><tbody>'+studentTr+'</tbody>';

                                $('.attendance-table').empty();
                                $('.attendance-table').append(table);
                                $('.attendance-save-btn-holder').empty();
                                if (previousAttendance) {
                                    $('.attendance-save-btn-holder').append('<button class="btn btn-success attendance-save-btn" style="float: right;">Update</button>');
                                }else{
                                    $('.attendance-save-btn-holder').append('<button class="btn btn-success attendance-save-btn" style="float: right;">Save</button>');
                                }
                            }else if(type == 'view'){
                                if (previousAttendance) {
                                    data[1].forEach(function (ele) {
                                        var attendance = (previousAttendance[ele.std_id] == 1)?'<span class="text-success">Present</span>':'<span class="text-danger">Absent</span>';

                                        studentTr += '<tr><td>'+sl+++'</td><td>'+ele.first_name+' '+ele.last_name+
                                        '</td><td>'+ele.gr_no+'</td><td>'+attendance+'</td></tr>';
                                    });

                                    table = '<thead><tr><th>SL</th><th>Cadet Names</th><th>Roll No</th><th>Attendance</th></tr></thead><tbody>'+studentTr+'</tbody>';

                                    $('.attendance-table').empty();
                                    $('.attendance-save-btn-holder').empty();
                                    $('.attendance-table').append(table);
                                    $('.attendance-save-btn-holder').append('<button class="btn btn-success attendance-print-btn" style="float: right;"><i class="fa fa-print"></i> Print</button>');
                                }else{
                                    $('.attendance-table').empty();
                                    $('.attendance-save-btn-holder').empty();
                                    $('.attendance-table').append('<div class="text-danger" style="text-align: center">No Attendance Found! Please take attendance first.</div>');
                                }
                            }                            
                        }else{
                            $('.attendance-table').empty();
                            $('.attendance-save-btn-holder').empty();
                            $('.attendance-table').append('<div class="text-danger" style="text-align: center">No Students Found!</div>');
                        }                        
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

            allCheckBoxes.each((index, data) => {
                if ($(data).is(':checked')) {
                    attendance[$(data).val()] = 1;
                }else{
                    attendance[$(data).val()] = 0;
                }
            });

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
            
                beforeSend: function () {},
            
                success: function (result) {
                    $('.attendance-save-btn').text('Update');
                    swal({
                        title: "Success!",
                        text: result,
                        icon: "success",
                    }, function () {
                        location.reload();
                    });
                }
            });
            // Ajax Request End
        });

        $(document).on('click', '.attendance-all-select', function () {
            if ($(this).is(':checked')) {
                $('.attendance-select').prop('checked', true);
            }else{
                $('.attendance-select').prop('checked', false);
            }
        });
    });
</script>
@stop