@extends('layouts.master')

@section('styles')
    <style>
        body {
            padding-right: 0 !important;
        }
    </style>
@endsection

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Exam |<small>Mark Entry</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Academics</a></li>
            <li>SOP Setup</li>
            <li>Exam</li> 
            <li class="active">Exam Mark Entry</li>
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
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Exam Mark Entry </h3>
            </div>
            <div class="box-body table-responsive">
                <form id="std_manage_search_form" method="GET" action="{{ url('/academics/exam/student/search') }}" target="_blank">
                    @csrf

                    <input type="hidden" name="type" class="select-type">
                    <input type="hidden" name="can_save" value="{{ (in_array("academics/exam/save/student/marks" ,$pageAccessData))?true:false }}">

                    <div class="row"  style="margin-bottom: 50px">
                        <div class="col-sm-1">
                            <select name="yearId" id="" class="form-control select-year" required>
                                <option value="">-Year-</option>
                                @foreach ($academicYears as $academicYear)
                                    @isset($selectedAcademicYear)
                                        <option value="{{$academicYear->id}}" {{($academicYear->id == $selectedAcademicYear->id)?'selected':''}}>{{$academicYear->year_name}}</option>
                                    @else
                                        <option value="{{$academicYear->id}}">{{$academicYear->year_name}}</option>
                                    @endisset
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="termId" id="" class="form-control select-term" required>
                                <option value="">Term / Semester*</option>
                                @foreach ($semesters as $semester)
                                    @isset($selectedSemester)
                                        <option value="{{$semester->id}}" {{($semester->id == $selectedSemester->id)?'selected':''}}>{{$semester->name}}</option>
                                    @else
                                        <option value="{{$semester->id}}">{{$semester->name}}</option>
                                    @endisset
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="examId" id="" class="form-control select-exam" required>
                                <option value="">Select Exam*</option>
                                @isset($examNames)
                                @foreach ($examNames as $examName)
                                @isset($exam)
                                    <option value="{{$examName->id}}" {{($examName->id == $exam->id)?'selected':''}}>{{$examName->exam_name}}</option>
                                @else
                                    <option value="{{$examName->id}}">{{$examName->exam_name}}</option>
                                @endisset
                                @endforeach
                                @endisset    
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="batchId" id="" class="form-control select-class" required>
                                <option value="">Select Class*</option>
                                @isset($batches)
                                @foreach ($batches as $batch)
                                @isset($selectedBatch)
                                    <option value="{{$batch->id}}" {{($batch->id == $selectedBatch->id)?'selected':''}}>{{$batch->batch_name}}</option>
                                @else
                                    <option value="{{$batch->id}}">{{$batch->batch_name}}</option>
                                @endisset
                                @endforeach       
                                @endisset
                                
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <select name="sectionId" id="" class="form-control select-form" required>
                                <option value="">Select Form*</option>
                                @isset($allSection)
                                    @foreach ($allSection as $section)
                                        <option value="{{$section->id}}" {{($section->id == $selectedSection->id)?'selected':''}}>{{$section->section_name}}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-sm-2">
                            <select name="subjectId" id="" class="form-control select-subject" required>
                                <option value="">Select Subject*</option>
                                @isset($allSubject)
                                    @foreach ($allSubject as $subject)
                                        @isset($selectedSubject)
                                        <option value="{{$subject->id}}" {{($selectedSubject->id == $subject->id)?'selected':''}}>{{$subject->subject_name}}</option>
                                        @else
                                        <option value="{{$subject->id}}">{{$subject->subject_name}}</option>
                                        @endisset
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-sm-2">
                            @if (in_array(4550 ,$pageAccessData))
                            <button class="btn btn-sm btn-primary search-btn" type="button"><i class="fa fa-search"></i></button>
                            @endif
                            @if (in_array(4600 ,$pageAccessData))
                            <button class="btn btn-sm btn-primary view-btn" type="button"><i class="fa fa-eye"></i></button>
                            @endif
                            @if (in_array(4650 ,$pageAccessData))
                            <button class="btn btn-sm btn-primary print-btn" type="button"><i class="fa fa-print"></i></button>
                            @endif
                            <button class="print-submit-btn" type="submit" style="display: none"></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" id="std_list_container_row">

            </div>
        </div>
    </section>
</div>
@endsection



{{-- Scripts --}}
@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function () {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });
        
        $('.select-exam').change(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/exam/search-class') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'examNameId': $(this).val()
                }, //see the _token
                datatype: 'application/json',

                beforeSend: function () {},

                success: function (data) {
                    var txt = '<option value="">Select Class*</option>';
                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.batch_name+'</option>';
                    });

                    $('.select-class').empty();
                    $('.select-class').append(txt);
                    $('.select-form').empty();
                    $('.select-form').append('<option value="">Select Form*</option>');
                    $('.select-subject').empty();
                    $('.select-subject').append('<option value="">Select Subject*</option>');
                }
            });
            // Ajax Request End
        });

        $('.select-class').change(function () {
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
                }
            });
            // Ajax Request End
        });

        $('.select-form').change(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/exam/search-subjects/from-marks') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'yearId': $('.select-year').val(),
                    'termId': $('.select-term').val(),
                    'examId': $('.select-exam').val(),
                    'batchId': $('.select-class').val(),
                    'sectionId': $(this).val()
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    console.log(data);
                    var txt = '<option value="">Select Subject*</option>';
                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.subject_name+'</option>';
                    });

                    $('.select-subject').empty();
                    $('.select-subject').append(txt);
                }
            });
            // Ajax Request End
        });


        function searchStudents() {
            var yearId = $('.select-year').val();
            var termId = $('.select-term').val();
            var examId = $('.select-exam').val();
            var classId = $('.select-class').val();
            var formId = $('.select-form').val();
            var subjectId = $('.select-subject').val();

            if (yearId && termId && examId && classId && formId && subjectId) {
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/academics/exam/student/search/') }}",
                    type: 'GET',
                    cache: false,
                    data: $('form#std_manage_search_form').serialize(),
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        // waitingDialog.show('Loading...');
                    },

                    success:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // checking
                        if(data.status=='success'){
                            var std_list_container_row = $('#std_list_container_row');
                            std_list_container_row.html('');
                            std_list_container_row.append(data.html);
                        }else{
                            alert(data.msg)
                        }
                    },

                    error:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();

                        alert(JSON.stringify(data));
                    }
                });
            } else {
                Swal.fire('Error!', 'Select All the fields first!', 'error');
            }
        }

        $(document).on('submit', 'form#std_list_import_form', function (e) {
            e.preventDefault();
            // ajax request
            $.ajax({
                url: "/academics/exam/save/student/marks",
                type: 'POST',
                cache: false,
                data: $('form#std_list_import_form').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success:function(data){
                    if (data.status==1) {
                        searchStudents();
                    }else{
                        // hide waiting dialog
                        waitingDialog.hide();
                    }
                    Toast.fire({
                        icon: (data.status==1)?'success':'error',
                        title: data.msg
                    });
                },

                error:function(data){
                    waitingDialog.hide();
                    alert(JSON.stringify(data));
                }
            });
        });

        $(document).on('click', '.delete-marks-btn', function () {
            Swal.fire({
                title: "Are you sure?",
                text: "You want to remove selected student\'s marks?",
                icon: "warning",
                showCancelButton: true,
                showConfirmButton: true,
                confirmButtonText: 'Yes',
                cancelButtonText: 'No',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "/academics/exam/delete/student/marks",
                        type: 'POST',
                        cache: false,
                        data: $('form#std_list_import_form').serialize(),
                        datatype: 'application/json',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            if (data.status==1) {
                                searchStudents();
                            }else{
                                // hide waiting dialog
                                waitingDialog.hide();
                            }
                            Toast.fire({
                                icon: (data.status==1)?'success':'error',
                                title: data.msg
                            });
                        },

                        error:function(data){
                            waitingDialog.hide();
                            alert(JSON.stringify(data));
                        }
                    });
                }
            });
        });

        $('.search-btn').click(function () {
            // show waiting dialog
            waitingDialog.show('Loading...');
            $('.select-type').val('search');
            searchStudents();
        });

        $('.view-btn').click(function () {
            // show waiting dialog
            waitingDialog.show('Loading...');
            $('.select-type').val('view');
            searchStudents();
        });

        $('.print-btn').click(function () {
            $('.select-type').val('print');
            $('.print-submit-btn').click();
        });
    });
</script>
@stop