@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Exam |<small>Marks</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Academics</a></li>
            <li>SOP Setup</li>
            <li>Exam</li> 
            <li class="active">Exam Marks</li>
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
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Exam Marks </h3>
            </div>
            <div class="box-body table-responsive">
                <form action="{{url('/academics/exam/set/marks')}}" method="get">
                    @csrf

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
                                @isset($semesters)
                                @foreach ($semesters as $semester)
                                @isset($selectedSemester)
                                    <option value="{{$semester->id}}" {{($semester->id == $selectedSemester->id)?'selected':''}}>{{$semester->name}}</option>
                                @else
                                    <option value="{{$semester->id}}">{{$semester->name}}</option>
                                @endisset
                            @endforeach
                                @endisset
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
                            <select name="subjectId" id="" class="form-control select-subject">
                                <option value="">All Subjects</option>
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
                            <button class="btn btn-sm btn-primary"><i class="fa fa-search"></i></button>
                        </div>
                    </div>
                </form>

                @isset($subjects)
                <table class="table table-bordered" id="marksTable">
                    <thead>
                        <tr>
                            <th scope="col">Subject</th>
                            <th scope="col">Full Marks</th>
                            <th scope="col">Conversion</th>
                            @foreach ($examMarkParameters as $examMarkParameter)
                                <th scope="col">{{$examMarkParameter->name}}</th>
                            @endforeach
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    @foreach ($subjects as $subject)
                    @php
                        $previousMark = null;
                        foreach ($subjectMarks as $key => $subjectMark) {
                            if ($subject->id == $subjectMark->subject_id) {
                                $previousMark = $subjectMark;
                            }
                        }
                        $paramFullMarks = null;
                        $paramPassMarks = null;
                        if ($previousMark) {
                            $previousParamMarks = json_decode($previousMark->marks);
                            $paramFullMarks = $previousParamMarks->fullMarks;
                            $paramPassMarks = $previousParamMarks->passMarks;
                        }
                    @endphp
                    <form>
                        <tbody>
                            <input type="hidden" value="{{$subject->id}}" class="subject-id">
                            <tr>
                                <td rowspan="5" style="vertical-align: middle">
                                    <div class="text-center">{{$subject->subject_name}}</div>
                                    <div class="text-center">Code: {{$subject->subject_code}}</div>
                                    <div class="text-center">Alias: {{$subject->subject_alias}}</div>
                                </td>
                                <td></td>
                                <td></td>
                                @foreach ($examMarkParameters as $examMarkParameter)
                                    @php
                                        $previousParamMark = null;
                                        if ($paramFullMarks) {
                                            foreach ($paramFullMarks as $key => $paramMark) {
                                                if ($key == $examMarkParameter->id) {
                                                    $previousParamMark = $paramMark;
                                                }
                                            }
                                        }
                                    @endphp
                                    <td scope="col"><input type="checkbox" {{($previousParamMark)?'checked':''}} class="parameter-check" data-param-id="{{$examMarkParameter->id}}" value="{{$examMarkParameter->id}}"> {{$examMarkParameter->name}}</td>
                                @endforeach
                                <td rowspan="5" style="vertical-align: middle"><button type="button" class="btn btn-success marks-save-btn">{{($previousMark)?'Update':'Save'}}</button></td>
                            </tr>
                            <tr>
                                <td><input type="number" value="{{($previousMark)?$previousMark->full_marks:''}}" class="form-control full-mark"></td>
                                <td><input type="number" value="{{($previousMark)?$previousMark->full_mark_conversion:''}}" class="form-control full-mark-conversion"></td>
                                @foreach ($examMarkParameters as $examMarkParameter)
                                    @php
                                    $previousParamMark = null;
                                        if ($paramFullMarks) {
                                            foreach ($paramFullMarks as $key => $paramMark) {
                                                if ($key == $examMarkParameter->id) {
                                                    $previousParamMark = $paramMark;
                                                }
                                            }
                                        }
                                    @endphp
                                    <td><input type="number" value="{{($previousParamMark)?$previousParamMark:''}}" class="form-control parameter-field param-field-full-mark" data-param-id="{{$examMarkParameter->id}}" {{($previousParamMark)?'':'disabled'}}></td>
                                @endforeach
                            </tr>
                            <tr>
                                <td colspan="12"><b>Pass Marks: </b></td>
                            </tr>
                            <tr>
                                <td><input type="number" value="{{($previousMark)?$previousMark->pass_marks:''}}" class="form-control pass-mark"></td>
                                <td><input type="number" value="{{($previousMark)?$previousMark->pass_mark_conversion:''}}" class="form-control pass-mark-conversion"></td>
                                @foreach ($examMarkParameters as $examMarkParameter)
                                    @php
                                        $previousParamMark = null;
                                        if ($paramPassMarks) {
                                            foreach ($paramPassMarks as $key => $paramMark) {
                                                if ($key == $examMarkParameter->id) {
                                                    $previousParamMark = $paramMark;
                                                }
                                            }
                                        }
                                    @endphp
                                    <td><input type="number" value="{{($previousParamMark)?$previousParamMark:''}}" class="form-control parameter-field param-field-pass-mark" data-param-id="{{$examMarkParameter->id}}" {{($previousParamMark)?'':'disabled'}}></td>
                                @endforeach
                            </tr>
                            <tr>
                                <td colspan="5">
                                    <span><input type="checkbox" class="effective-for" {{($previousMark)?($previousMark->effective_for == 1)?'checked':'':''}}> Effective For result calculation</span>
                                    <span style="margin-left: 20px"><input type="radio" name="type" value="1" {{($previousMark)?($previousMark->status == 1)?'checked':'':''}}> Individual</span>
                                    <span style="margin-left: 20px"><input type="radio" name="type" value="2" {{($previousMark)?($previousMark->status == 2)?'checked':'':''}}> Aggregated</span>
                                </td>
                            </tr>
                        </tbody>
                    </form>
                    @endforeach
                </table>
                @endisset
            </div>
        </div>
    </section>
</div>

@php
    $jsYear = 0;
    $jsTerm = 0;
    $jsExam = 0;
    $jsBatch = 0;
    $jsSection = 0;
    if(isset($selectedAcademicYear)){
        $jsYear = $selectedAcademicYear;
    }
    if(isset($selectedSemester)){
        $jsTerm = $selectedSemester;
    }
    if(isset($exam)){
        $jsExam = $exam;
    }
    if (isset($selectedBatch)){
        $jsBatch = $selectedBatch;
    }
    if (isset($selectedSection)){
        $jsSection = $selectedSection;
    }
@endphp

@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        var year = ({!!$jsYear!!})?{!!$jsYear!!}:null;
        var term = ({!!$jsTerm!!})?{!!$jsTerm!!}:null;
        var exam = ({!!$jsExam!!})?{!!$jsExam!!}:null;
        var batch = ({!!$jsBatch!!})?{!!$jsBatch!!}:null;
        var section = ({!!$jsSection!!})?{!!$jsSection!!}:null;

        $('.select-year').change(function () {
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
                    $('.select-exam').empty();
                    $('.select-exam').append('<option value="">Select Exam*</option>');
                    $('.select-class').empty();
                    $('.select-class').append('<option value="">Select Class*</option>');
                    $('.select-form').empty();
                    $('.select-form').append('<option value="">Select Form*</option>');
                    $('.select-subject').empty();
                    $('.select-subject').append('<option value="">All Subjects*</option>');
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
                    var txt = '<option value="">Select Exam*</option>';
                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.exam_name+'</option>';
                    });

                    $('.select-exam').empty();
                    $('.select-exam').append(txt);
                    $('.select-class').empty();
                    $('.select-class').append('<option value="">Select Class*</option>');
                    $('.select-form').empty();
                    $('.select-form').append('<option value="">Select Form*</option>');
                    $('.select-subject').empty();
                    $('.select-subject').append('<option value="">All Subjects*</option>');
                }
            });
            // Ajax Request End
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
                    $('.select-subject').append('<option value="">All Subjects*</option>');
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
                    $('.select-subject').append('<option value="">All Subjects*</option>');
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
                url: "{{ url('/academics/exam/search-subjects') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'section': $(this).val()
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">--All Subjects--</option>';
                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.subject_name+'</option>';
                    });

                    $('.select-subject').empty();
                    $('.select-subject').append(txt);
                }
            });
            // Ajax Request End
        });

        $('.parameter-check').click(function () {
            var paramId = $(this).data('param-id');
            var tbody = $(this).parent().parent().parent();
            var paramFields = tbody.find('.parameter-field').filter('[data-param-id="'+paramId+'"]');

            if($(this).is(':checked')){
                paramFields.attr('disabled', false);
            }else{
                paramFields.attr('disabled', true);
            }
        });



        // -- Mark save portion --
        $('.marks-save-btn').click(function () {
            var currentButton = $(this);

            // Catching all the datas
            var tbody = currentButton.parent().parent().parent();
            var datas = {
                subjectId: tbody.find('.subject-id').val(),
                yearId: (year)?year.id:null,
                termId: (term)?term.id:null,
                examId: (exam)?exam.id:null,
                batchId: (batch)?batch.id:null,
                sectionId: (section)?section.id:null,
                fullMark: tbody.find('.full-mark').val(),
                fullMarkConversion: tbody.find('.full-mark-conversion').val(),
                passMark: tbody.find('.pass-mark').val(),
                passMarkConversion: tbody.find('.pass-mark-conversion').val(),
                marks: {
                    fullMarks: {},
                    passMarks: {}
                },
                effectiveFor: (tbody.find('.effective-for').is(":checked"))?1:0,
                type: (tbody.find('input[name="type"]').is(':checked'))?tbody.find('input[name="type"]:checked').val():null,
            };

            var paramFieldFullMarks = tbody.find('.param-field-full-mark');
            var paramFieldPassMarks = tbody.find('.param-field-pass-mark');
            var fullMarkParamTotal = 0;
            var passMarkParamTotal = 0;


            paramFieldFullMarks.each((index, value) => {
                var paramId = $(value).data('param-id');
                if (!$(value).is(':disabled')) {
                    if (!$(value).val()) {
                        swal("Error!", "Please set value for all the parameters!", "error");
                        throw 'Value Missing';
                    }
                    fullMarkParamTotal += parseInt($(value).val());
                    datas.marks.fullMarks[paramId] = $(value).val();
                }
            });
            paramFieldPassMarks.each((index, value) => {
                var paramId = $(value).data('param-id');
                if (!$(value).is(':disabled')) {
                    if (!$(value).val()) {
                        swal("Error!", "Please set value for all the parameters!", "error");
                        throw 'Value Missing';
                    }
                    passMarkParamTotal += parseInt($(value).val());
                    datas.marks.passMarks[paramId] = $(value).val();
                }
            });
            // Data catching finish
            

            // Validating datas
            if (datas.fullMark && datas.fullMarkConversion && datas.passMark && datas.passMarkConversion && datas.type) {
                if (fullMarkParamTotal != datas.fullMark) {
                    swal("Error!", "Total mark parameter values is not equal to Total Mark! adjust them.", "error");
                } else if(passMarkParamTotal != datas.passMark){
                    swal("Error!", "Total pass mark parameter values is not equal to Total Pass Mark! adjust them.", "error");
                }else{
                    $_token = "{{ csrf_token() }}";
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        url: "{{ url('/academics/exam/set/marks/post') }}",
                        type: 'POST',
                        cache: false,
                        data: {
                            '_token': $_token,
                            'subjectId': datas.subjectId,
                            'yearId': datas.yearId,
                            'termId': datas.termId,
                            'examId': datas.examId,
                            'batchId': datas.batchId,
                            'sectionId': datas.sectionId,
                            'fullMark': datas.fullMark,
                            'fullMarkConversion': datas.fullMarkConversion,
                            'passMark': datas.passMark,
                            'passMarkConversion': datas.passMarkConversion,
                            'marks': JSON.stringify(datas.marks),
                            'effectiveFor': datas.effectiveFor,
                            'type': datas.type,
                        }, //see the _token
                        datatype: 'application/json',
                    
                        beforeSend: function () {
                            currentButton.prop('disabled', true);
                        },
                    
                        success: function (data) {
                            if (data == 1) {
                                swal("Success!", "Marks saved successfully!", "success");
                                currentButton.prop('disabled', false);
                                currentButton.text("Update");
                            }else{
                                swal("Error!", data, "error");
                                currentButton.prop('disabled', false);
                            }
                        },

                        error: function (error) {
                            console.log(error);
                            swal("Error!", "Error saving marks.", "error");
                            currentButton.prop('disabled', false);
                        }
                    });

                }
            }else{
                swal("Error!", "Please fill up all the required fields first!", "error");
            }
        })
    });
</script>
@stop