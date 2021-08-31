@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Student Academics |<small>Exam Schedules</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Academics</a></li>
            <li>SOP Setup</li>
            <li>Exam</li>
            <li class="active">Exam Schedule</li>
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
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Academics Exam Schedules </h3>
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
                        <select name="batchIds[]" id="" multiple class="form-control select-class">
                            <option value="">--Class--</option>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <button class="btn btn-sm btn-primary search-schedule-button"><i class="fa fa-search"></i></button>
                        <button class="btn btn-sm btn-primary view-schedule-button"><i class="fa fa-eye"></i></button>
                    </div>
                </div>

                <table class="table table-bordered" id="marksTable">
                    
                </table>
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

        $('.select-class').select2({
            placeholder: "--Select Class--"
        });

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

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
                    $('.select-class').val(null).trigger('change');
                    $('.select-class').empty();
                    $('.select-class').append('<option value="">Select Class*</option>');
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
                    $('.select-class').val(null).trigger('change');
                    $('.select-class').empty();
                    $('.select-class').append('<option value="">Select Class*</option>');
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
                    var classText = '<option value="">--All Classes--</option>';

                    data.forEach(element => {
                        classText += '<option value="'+element.id+'">'+element.batch_name+'</option>';
                    });

                    $('.select-class').empty();
                    $('.select-class').val(null).trigger('change');
                    $('.select-class').append(classText);
                }
            });
            // Ajax Request End
        });

        // Dependant Selection Fields ajax request end


        // Datas for post schedule
        var scheduleFormData = {
            academicYearId: null,
            semesterId: null,
            examId: null
        };


        // Search Schedule Table part

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

        // Table Head making function
        function makeScheduleTHead(type, classes){
            var tClasses = '';

            classes.forEach(element => {
                tClasses += '<th>'+element.batch_name+'</th>';
            });

            if (type == 'search') {
                return '<thead><tr><th>SL</th><th>Subject</th>'+tClasses+'<th>Action</th></tr></thead>';
            }else if(type == 'view'){
                return '<thead><tr><th>SL</th><th>Subject</th>'+tClasses+'</tr></thead>';
            }
        }

        // Table Data making function
        function makeScheduleTData(type, classes, allSubjectMarks, markParameters, previousSchedules, subjectId){
            var tData = '';

            classes.forEach(batch => {
                var selectedsubjectMark = null;
                var selectedPreviousSchedule = null;

                for (const property in allSubjectMarks) {
                    var subjectMark = allSubjectMarks[property];
                    if (subjectMark.batch_id == batch.id) {
                        selectedsubjectMark = subjectMark;
                    }
                }

                previousSchedules.forEach(element => {
                    if (element.subject_id == subjectId && element.batch_id == batch.id) {
                        selectedPreviousSchedule = JSON.parse(element.schedules);
                    }
                });

                var parameterFields = '';

                if (selectedsubjectMark) {
                    var subjectMarks = JSON.parse(selectedsubjectMark.marks);
                    var parameterKeys = Object.keys(subjectMarks.fullMarks);

                    parameterKeys.forEach(element =>{
                        markParameters.forEach(markParameter => {
                            if (markParameter.id == element){
                                if (type == 'search') {
                                    var previousScheduleDate = (selectedPreviousSchedule)?(selectedPreviousSchedule[element].date)?selectedPreviousSchedule[element].date:'':'';
                                    var previousScheduleStartTime = (selectedPreviousSchedule)?(selectedPreviousSchedule[element].startTime)?selectedPreviousSchedule[element].startTime:'':'';
                                    var previousScheduleEndTime = (selectedPreviousSchedule)?(selectedPreviousSchedule[element].endTime)?selectedPreviousSchedule[element].endTime:'':'';

                                    parameterFields += '<div class="row"><div class="col-sm-2">'+markParameter.name+
                                    ': </div><div class="col-sm-10"><input type="text" class="parameter-date" data-parameter-id="'+markParameter.id+
                                    '" value="'+previousScheduleDate+'" placeholder="Date"> <input type="time" class="parameter-start-time" data-parameter-id="'+markParameter.id+
                                    '" value="'+previousScheduleStartTime+'"> - <input type="time" class="parameter-end-time" data-parameter-id="'+markParameter.id+
                                    '" value="'+previousScheduleEndTime+'"></div></div>';
                                }else if(type == 'view'){
                                    var previousScheduleDate = (selectedPreviousSchedule)?(selectedPreviousSchedule[element].date)?selectedPreviousSchedule[element].date:'dd/mm/yyyy':'dd/mm/yyyy';
                                    var previousScheduleStartTime = (selectedPreviousSchedule)?(selectedPreviousSchedule[element].startTime)?selectedPreviousSchedule[element].startTime:'00:00':'00:00';
                                    var previousScheduleEndTime = (selectedPreviousSchedule)?(selectedPreviousSchedule[element].endTime)?selectedPreviousSchedule[element].endTime:'00:00':'00:00';

                                    parameterFields += '<div class="row"><div class="col-sm-2">'+markParameter.name+
                                    ': </div><div class="col-sm-10">'+previousScheduleDate+' :- '+tConvert(previousScheduleStartTime)+' - '+tConvert(previousScheduleEndTime)+'</div></div>';
                                }
                            }
                        });
                    });
                }

                var batchClass = (parameterFields != '')?'batch-td':'';
                
                tData += '<td class="'+batchClass+'" data-batch-id="'+batch.id+'">'+parameterFields+'</td>';
            });

            return tData;
        }

        function checkPreviousData(previousShedules, subjectId){
            var flag = false;

            previousShedules.forEach(element => {
                if (element.subject_id == subjectId) {
                    flag = true;
                }
            });

            return flag;
        }

        // Table making function
        function makeScheduleTable(type, datas){
            var tRow = '';
            var sl = 1;

            if (type == 'search') {
                for (const property in datas.subjectMarks) {
                    var subjectMarks = datas.subjectMarks[property];
                    var buttonName = (checkPreviousData(datas.previousSchedules, subjectMarks[0].subject.id))?'Update':'Save';

                    tRow += '<form><tr data-subject-id="'+subjectMarks[0].subject.id+'"><td>'+sl+++'</td><td style="text-align: center"><div>'+subjectMarks[0].subject.subject_name+'</div><div>Code: '+
                        subjectMarks[0].subject.subject_code+'</div><div>Alias: '+subjectMarks[0].subject.subject_alias+'</div><div>Group: '+
                            subjectMarks[0].subject.division.name+'</div></td>'+makeScheduleTData(type, datas.classes, subjectMarks, datas.markParameters, datas.previousSchedules, subjectMarks[0].subject.id)+
                            '<td style="vertical-align: middle"><button class="btn btn-success save-button">'+buttonName+'</button></td></tr></form>';
                }
            }else if(type == 'view'){
                for (const property in datas.subjectMarks) {
                    var subjectMarks = datas.subjectMarks[property];

                    tRow += '<form><tr data-subject-id="'+subjectMarks[0].subject.id+'"><td>'+sl+++'</td><td style="text-align: center"><div>'+subjectMarks[0].subject.subject_name+'</div><div>Code: '+
                        subjectMarks[0].subject.subject_code+'</div><div>Alias: '+subjectMarks[0].subject.subject_alias+'</div><div>Group: '+
                            subjectMarks[0].subject.division.name+'</div></td>'+makeScheduleTData(type, datas.classes, subjectMarks, datas.markParameters, datas.previousSchedules, subjectMarks[0].subject.id)+'</tr></form>';
                }
            }            

            return makeScheduleTHead(type, datas.classes)+tRow;
        };

        function searchSchedules(type) {
            var datas = {
                yearId: $('.select-academic-year').val(),
                termId: $('.select-term').val(),
                examNameId: $('.select-exam-name').val(),
                subjectId: $('.select-subject').val(),
                classId: $('.select-class').val()
            };

            scheduleFormData.academicYearId = datas.yearId;
            scheduleFormData.semesterId = datas.termId;
            scheduleFormData.examId = datas.examNameId;

            if (datas.yearId && datas.termId && datas.examNameId){
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/academics/exam/search-schedule') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'yearId': datas.yearId,
                        'termId': datas.termId,
                        'examId': datas.examNameId,
                        'subjectId': datas.subjectId,
                        'classIds': datas.classId,
                        // 'fromDate': datas.fromDate,
                        // 'toDate': datas.toDate,
                    }, //see the _token
                    datatype: 'application/json',

                    beforeSend: function () {},

                    success: function (datas) {
                        var table = makeScheduleTable(type, datas);

                        $('#marksTable').append('<thead><tr><th></th><th></th></tr></thead><tbody><tr><td></td><td></td></tr></tbody>');
                        $('#marksTable').DataTable().destroy();

                        $('#marksTable').empty();
                        $('#marksTable').append(table);

                        $('#marksTable').DataTable();
                        $('.parameter-date').datepicker();
                    }
                });
                // Ajax Request End
            }else{
                swal("Error!", "Please Fill up all the required fields first!", "error");
            }
        }

        // Search Schedules
        $('.search-schedule-button').click(function (){
            searchSchedules('search');
        });

        // View Schedules
        $('.view-schedule-button').click(function () {
            searchSchedules('view');
        });


        // Save Exam Schedules

        // Sending datas to controller
        function saveExamSchedules(datas) {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/exam/save-schedule') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'yearId': datas.academicYearId,
                    'semesterId': datas.semesterId,
                    'examId': datas.examId,
                    'subjectId': datas.subjectId,
                    'batchIds': datas.batchIds,
                    'schedules': datas.schedules,
                    'fromDate': datas.fromDate,
                    'toDate': datas.toDate,
                }, //see the _token
                datatype: 'application/json',

                beforeSend: function () {},

                success: function (result) {
                    swal("Success!", result, "success");
                    $('.save-button').text('Update');
                }
            });
            // Ajax Request End
        }

        // Generating datas
        $(document).on('click', '.save-button', function () {
            var tableTr = $(this).parent().parent();

            var datas = scheduleFormData;
            datas.subjectId= tableTr.data('subject-id');
            datas.batchIds= [];
            datas.schedules= {};

            var allBatchTd = tableTr.find('.batch-td');

            // Set Batch ids
            allBatchTd.each(function (index, batch) {
                // Batch Id array making
                $batchId = $(batch).data('batch-id');
                datas.batchIds.push($batchId);

                // All Schedules object making to insert in database
                datas.schedules[$batchId] = {};
                var dates = $(batch).find('.parameter-date');
                var startTimes = $(batch).find('.parameter-start-time');
                var endTimes = $(batch).find('.parameter-end-time');

                dates.each(function (i, date) {
                    datas.schedules[$batchId][$(date).data('parameter-id')] = {};
                });
                dates.each(function (i, date) {
                    datas.schedules[$batchId][$(date).data('parameter-id')].date = $(date).val();
                });
                startTimes.each(function (i, startTime) {
                    datas.schedules[$batchId][$(startTime).data('parameter-id')].startTime = $(startTime).val();
                });
                endTimes.each(function (i, endTime) {
                    datas.schedules[$batchId][$(endTime).data('parameter-id')].endTime = $(endTime).val();
                });
            });

            saveExamSchedules(datas);
        });
    });
</script>
@stop