@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Cadet Academics |<small>Exam Schedules</small>
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
                            @foreach ($terms as $term)
                                <option value="{{ $term->id }}">{{ $term->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="examNameId" id="" class="form-control select-exam-name">
                            <option value="">Exam Name*</option>
                            @foreach ($exams as $exam)
                                <option value="{{ $exam->id }}">{{ $exam->exam_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <select name="batchIds[]" id="" multiple class="form-control select-class">
                            <option value="">--Class--</option>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        @if (in_array(3750 ,$pageAccessData))
                        <button class="btn btn-sm btn-primary search-schedule-button"><i class="fa fa-search"></i></button>
                        @endif
                        @if (in_array(3800 ,$pageAccessData))
                        <button class="btn btn-sm btn-primary view-schedule-button"><i class="fa fa-eye"></i></button>
                        @endif
                        @if (in_array(3850 ,$pageAccessData))
                        <button class="btn btn-sm btn-primary print-schedule-button"><i class="fa fa-print"></i></button>
                        @endif
                    </div>
                </div>

                <div id="marksTableHolder">

                </div>

                <table class="table table-bordered" id="marksTable">
                    
                </table>
            </div>
        </div>

        <form action="{{ url('/academics/exam/search-schedule') }}" method="GET" target="_blank">
            @csrf

            <input type="hidden" class="hidden_type_field" name="type">
            <input type="hidden" class="hidden_year_id_field" name="yearId">
            <input type="hidden" class="hidden_term_id_field" name="termId">
            <input type="hidden" class="hidden_exam_id_field" name="examId">
            <input type="hidden" class="hidden_subject_id_field" name="subjectId">
            <select name="classIds[]" class="hidden_class_ids_field" style="display: none" multiple></select>
            <input type="submit" class="hidden_print_btn" style="display: none">
        </form>

        <input type="hidden" class="can_save" value="{{ (in_array("academics/exam/save-schedule" ,$pageAccessData))?true:false }}">
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
                    $('.hidden_class_ids_field').html(classText);
                },

                error: function (error) {
                    console.log(error);
                    waitingDialog.hide();
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
                if (type == 'print') {
                    $('.hidden_type_field').val(type);
                    $('.hidden_year_id_field').val(datas.yearId);
                    $('.hidden_term_id_field').val(datas.termId);
                    $('.hidden_exam_id_field').val(datas.examNameId);
                    $('.hidden_subject_id_field').val(datas.subjectId);
                    $('.hidden_class_ids_field').val(datas.classId);

                    $('.hidden_print_btn').click();
                } else {
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
                            'type': type,
                            'canSave': $('.can_save').val()
                            // 'fromDate': datas.fromDate,
                            // 'toDate': datas.toDate,
                        }, //see the _token
                        datatype: 'application/json',

                        beforeSend: function () {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success: function (datas) {
                            waitingDialog.hide();
                            $('#marksTableHolder').html(datas);
                        },

                        error: function (error) {
                            console.log(error);
                            waitingDialog.hide();
                        }
                    });
                    // Ajax Request End
                }
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

        // View Schedules
        $('.print-schedule-button').click(function () {
            searchSchedules('print');
        });


        // Save Exam Schedules

        // Sending datas to controller
        function saveExamSchedules(datas) {
            // console.log(datas);
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
                    'schedules': datas.schedules
                }, //see the _token
                datatype: 'application/json',

                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success: function (result) {
                    waitingDialog.hide();
                    swal("Success!", result, "success");
                },

                error: function (error) {
                    console.log(error);
                    waitingDialog.hide();
                }
            });
            // Ajax Request End
        }

        // Generating datas
        $(document).on('click', '.save-button', function () {
            var tableTr = $(this).parent().parent();

            var flag = true;

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

                datas.schedules[$batchId].fromDate = null;
                datas.schedules[$batchId].toDate = null;

                dates.each(function (i, date) {
                    datas.schedules[$batchId][$(date).data('parameter-id')] = {};
                });
                dates.each(function (i, date) {
                    datas.schedules[$batchId][$(date).data('parameter-id')].date = $(date).val();
                    if(!$(date).val()){
                        flag = false;
                    } else if ($(date).val() < datas.schedules[$batchId].fromDate) {
                        datas.schedules[$batchId].fromDate = $(date).val();
                    } else if ($(date).val() > datas.schedules[$batchId].toDate) {
                        datas.schedules[$batchId].toDate = $(date).val();
                    } else {
                        if (!datas.schedules[$batchId].fromDate) {
                            datas.schedules[$batchId].fromDate = $(this).val();
                        }
                        if (!datas.schedules[$batchId].toDate) {
                            datas.schedules[$batchId].toDate = $(this).val();
                        }
                    }
                });
                startTimes.each(function (i, startTime) {
                    datas.schedules[$batchId][$(startTime).data('parameter-id')].startTime = $(startTime).val();
                    if(!$(startTime).val()){
                        flag = false;
                    }
                });
                endTimes.each(function (i, endTime) {
                    datas.schedules[$batchId][$(endTime).data('parameter-id')].endTime = $(endTime).val();
                    if(!$(endTime).val()){
                        flag = false;
                    }
                });
            });

            if(flag) {
                saveExamSchedules(datas);
                tableTr.find('.save-button').text('Update');
            } else {
                swal('Error!', 'Please choose date & time!', 'error');
            }
        });

        $(document).on('change paste keyup', '.master-parameter-date', function () {
            var classId = $(this).data('class-id');
            $('.parameter-date[data-class-id="'+classId+'"]').val($(this).val());
        });

        $(document).on('change paste keyup', '.master-parameter-start-time', function () {
            var classId = $(this).data('class-id');
            $('.parameter-start-time[data-class-id="'+classId+'"]').val($(this).val());
        });

        $(document).on('change paste keyup', '.master-parameter-end-time', function () {
            var classId = $(this).data('class-id');
            $('.parameter-end-time[data-class-id="'+classId+'"]').val($(this).val());
        });

        function saveAllExamSchedules(datas) {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/exam/save/all/schedules') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'yearId': datas.academicYearId,
                    'semesterId': datas.semesterId,
                    'examId': datas.examId,
                    'subjectIds': datas.subjectIds,
                    'batchIds': datas.batchIds,
                    'schedules': datas.schedules
                }, //see the _token
                datatype: 'application/json',

                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success: function (result) {
                    waitingDialog.hide();
                    $('.save-button').text('Update');
                    swal("Success!", result, "success");
                },

                error: function (error) {
                    console.log(error);
                    waitingDialog.hide();
                }
            });
            // Ajax Request End
        }

        $(document).on('click', '.all-save-btn', function () {
            var flag = true;
            var datas = scheduleFormData;
            datas.subjectIds= [];
            datas.batchIds= [];
            datas.schedules= {};

            var allSubjectTr = $('.subject-tr');
            var allBatchTd = null;

            allSubjectTr.each((si, subject) => {
                // Subject Id array making
                $subjectId = $(subject).data('subject-id');
                datas.subjectIds.push($subjectId);

                allBatchTd = $(subject).find('.batch-td');
                datas.schedules[$subjectId] = {};

                // Set Batch ids 
                allBatchTd.each(function (bi, batch) {
                    $batchId = $(batch).data('batch-id');
                    if (si < 1) {
                        // Batch Id array making
                        datas.batchIds.push($batchId);
                    }

                    // All Schedules object making to insert in database
                    datas.schedules[$subjectId][$batchId] = {};
                    var dates = $(batch).find('.parameter-date');
                    var startTimes = $(batch).find('.parameter-start-time');
                    var endTimes = $(batch).find('.parameter-end-time');

                    datas.schedules[$subjectId][$batchId].fromDate = null;
                    datas.schedules[$subjectId][$batchId].toDate = null;

                    dates.each(function (i, date) {
                        datas.schedules[$subjectId][$batchId][$(date).data('parameter-id')] = {};
                    });
                    dates.each(function (i, date) {
                        datas.schedules[$subjectId][$batchId][$(date).data('parameter-id')].date = $(date).val();
                        if(!$(date).val()){
                            flag = false;
                        } else if ($(date).val() < datas.schedules[$subjectId][$batchId].fromDate) {
                            datas.schedules[$subjectId][$batchId].fromDate = $(date).val();
                        } else if ($(date).val() > datas.schedules[$subjectId][$batchId].toDate) {
                            datas.schedules[$subjectId][$batchId].toDate = $(date).val();
                        } else {
                            if (!datas.schedules[$subjectId][$batchId].fromDate) {
                                datas.schedules[$subjectId][$batchId].fromDate = $(this).val();
                            }
                            if (!datas.schedules[$subjectId][$batchId].toDate) {
                                datas.schedules[$subjectId][$batchId].toDate = $(this).val();
                            }
                        }
                    });
                    startTimes.each(function (i, startTime) {
                        datas.schedules[$subjectId][$batchId][$(startTime).data('parameter-id')].startTime = $(startTime).val();
                        if(!$(startTime).val()){
                            flag = false;
                        }
                    });
                    endTimes.each(function (i, endTime) {
                        datas.schedules[$subjectId][$batchId][$(endTime).data('parameter-id')].endTime = $(endTime).val();
                        if(!$(endTime).val()){
                            flag = false;
                        }
                    });
                });
            });

            if(flag) {
                saveAllExamSchedules(datas);
            } else {
                swal('Error!', 'Blank date & time fields are not allowed!', 'error');
            }
        });
    });
</script>
@stop