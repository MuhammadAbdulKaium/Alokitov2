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
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" 
                style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" 
                style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Exam Marks </h3>
            </div>
            <div class="box-body table-responsive">
                <form action="{{url('/academics/exam/set/marks')}}" method="get">
                    @csrf

                    <div class="row"  style="margin-bottom: 50px">
                        <div class="col-sm-3">
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
                        <div class="col-sm-3">
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
                                <th scope="col"><input type="checkbox" class="all-parameter-check" data-param-id="{{$examMarkParameter->id}}" value="{{$examMarkParameter->id}}"> {{$examMarkParameter->name}}</th>
                            @endforeach
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    {{-- For Master Select Start --}}
                    <tbody style="background: lightgoldenrodyellow">
                        <tr>
                            <td rowspan="3" style="vertical-align: middle">Master Fields</td>
                            <td><input type="number" class="form-control all-full-mark"></td>
                            <td><input type="number" class="form-control all-full-mark-conversion"></td>
                            @foreach ($examMarkParameters as $examMarkParameter)
                                <td><input type="number" class="form-control parameter-field all-field-full-mark" data-param-id="{{$examMarkParameter->id}}" disabled></td>
                            @endforeach
                            <td rowspan="3" style="vertical-align: middle">
                                @if (in_array("academics/exam/set/marks/post" ,$pageAccessData))
                                <button type="button" id="marks-all-save-btn" class="btn btn-primary">Save All</button>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td colspan="12"><b>Pass Marks: </b></td>
                        </tr>
                        <tr>
                            <td><input type="number" class="form-control all-pass-mark"></td>
                            <td><input type="number" class="form-control all-pass-mark-conversion"></td>
                            @foreach ($examMarkParameters as $examMarkParameter)
                                <td><input type="number" class="form-control parameter-field all-field-pass-mark" data-param-id="{{$examMarkParameter->id}}" disabled></td>
                            @endforeach
                        </tr>
                    </tbody>
                    {{-- For Master Select End --}}

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
                            <tbody class="subject-form-panel" data-subject-id="{{ $subject->id }}" data-subject-name="{{ $subject->subject_name }}">
                                <input type="hidden" value="{{$subject->id}}" class="subject-id">
                                <tr>
                                    <td rowspan="4" style="vertical-align: middle">
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
                                    <td rowspan="4" style="vertical-align: middle">
                                        @if (in_array("academics/exam/set/marks/post" ,$pageAccessData))
                                        <button type="button" class="btn btn-success marks-save-btn">{{($previousMark)?'Update':'Save'}}</button>
                                        @endif
                                    </td>
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

        var exam = ({!!$jsExam!!})?{!!$jsExam!!}:null;
        var batch = ({!!$jsBatch!!})?{!!$jsBatch!!}:null;
        var section = ({!!$jsSection!!})?{!!$jsSection!!}:null;

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
                url: "{{ url('/academics/exam/search-subjects') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'batch': $(this).val()
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
                paramFields.val("");
                paramFields.attr('disabled', true);
            }
        });

        $('.all-parameter-check').click(function(){
            var paramId = $(this).data('param-id');
            var paramFields = $('.parameter-field').filter('[data-param-id="'+paramId+'"]');
            var paramCheck = $('.parameter-check').filter('[data-param-id="'+paramId+'"]');

            if($(this).is(':checked')){
                paramFields.attr('disabled', false);
                paramCheck.prop('checked', true);
            }else{
                paramFields.val("");
                paramFields.attr('disabled', true);
                paramCheck.prop('checked', false);
            }
        });

        function allPassMarkConversion(tbody) {
            var fullMark = tbody.find('.all-full-mark').val();
            var fullMarkConversion = tbody.find('.all-full-mark-conversion').val();
            var passMark = tbody.find('.all-pass-mark').val();

            fullMark = (fullMark)?parseFloat(fullMark):null;
            fullMarkConversion = (fullMarkConversion)?parseFloat(fullMarkConversion):null;
            passMark = (passMark)?parseFloat(passMark):null;

            if (fullMark && fullMarkConversion && passMark) {
                var divisor = fullMark/fullMarkConversion;
                tbody.find('.all-pass-mark-conversion').val((passMark/divisor).toFixed(2));
            } else {
                tbody.find('.all-pass-mark-conversion').val("");
            }
        }

        function passMarkConversion(tbody) {
            var fullMark = tbody.find('.full-mark').val();
            var fullMarkConversion = tbody.find('.full-mark-conversion').val();
            var passMark = tbody.find('.pass-mark').val();

            fullMark = (fullMark)?parseFloat(fullMark):null;
            fullMarkConversion = (fullMarkConversion)?parseFloat(fullMarkConversion):null;
            passMark = (passMark)?parseFloat(passMark):null;

            if (fullMark && fullMarkConversion && passMark) {
                var divisor = fullMark/fullMarkConversion;
                tbody.find('.pass-mark-conversion').val((passMark/divisor).toFixed(2));
            } else {
                tbody.find('.pass-mark-conversion').val("");
            }
        }

        function paramPassMarkCalculation(tbody) {
            var fullMark = tbody.find('.full-mark').val();
            var passMark = tbody.find('.pass-mark').val();
            var conversionVal = parseFloat(passMark)/parseFloat(fullMark);

            var paramFullMarkFields = tbody.find('.param-field-full-mark');
            var paramPassMarkFields = tbody.find('.param-field-pass-mark');

            paramFullMarkFields.each((index, fullMarkField) => {
                var paramPassMarkVal = "";
                
                if ($(fullMarkField).val()) {
                    var paramPassMarkVal = parseFloat($(fullMarkField).val())*conversionVal;
                    paramPassMarkVal = paramPassMarkVal.toFixed(2);
                }

                paramPassMarkFields[index].value = paramPassMarkVal;
            });
        }

        function masterParamPassMarkCalculation(tbody) {
            var fullMark = tbody.find('.all-full-mark').val();
            var passMark = tbody.find('.all-pass-mark').val();
            var conversionVal = parseFloat(passMark)/parseFloat(fullMark);

            var paramFullMarkFields = tbody.find('.all-field-full-mark');
            var paramPassMarkFields = tbody.find('.all-field-pass-mark');

            paramFullMarkFields.each((index, fullMarkField) => {
                var paramPassMarkVal = "";
                
                if ($(fullMarkField).val()) {
                    var paramPassMarkVal = parseFloat($(fullMarkField).val())*conversionVal;
                    paramPassMarkVal = paramPassMarkVal.toFixed(2);
                }

                paramPassMarkFields[index].value = paramPassMarkVal;
            });
        }

        $('.all-field-full-mark').on('change paste keyup', function () {
            var tbody = $(this).parent().parent().parent();
            var paramId = $(this).data('param-id');
            var paramFields = $('.param-field-full-mark').filter('[data-param-id="'+paramId+'"]');
            var value = $(this).val();

            paramFields.val(value);
        });
        
        $('.all-field-pass-mark').on('change paste keyup', function () {
            var paramId = $(this).data('param-id');
            var paramFields = $('.param-field-pass-mark').filter('[data-param-id="'+paramId+'"]');
            var value = $(this).val();
            
            paramFields.val(value);
        });
        
        
        $('.all-full-mark').on('change paste keyup', function () {
            var tbody = $(this).parent().parent().parent();
            var paramFields = $('.full-mark');
            var value = $(this).val();

            paramFields.val(value);
            paramFields.keyup();
            allPassMarkConversion(tbody);
            masterParamPassMarkCalculation(tbody);
            $('.param-field-full-mark').keyup();    
        });

        $('.all-full-mark-conversion').on('change paste keyup', function () {
            var tbody = $(this).parent().parent().parent();
            var paramFields = $('.full-mark-conversion');
            var value = $(this).val();

            paramFields.val(value);
            paramFields.keyup();
            allPassMarkConversion(tbody);
        });

        $('.all-pass-mark').on('change paste keyup', function () {
            var tbody = $(this).parent().parent().parent();
            var paramFields = $('.pass-mark');
            var value = $(this).val();

            paramFields.val(value);
            paramFields.keyup();
            allPassMarkConversion(tbody);
            masterParamPassMarkCalculation(tbody);
            $('.param-field-full-mark').keyup();
        });

        $('.full-mark').on('change paste keyup', function () {
            var tbody = $(this).parent().parent().parent();
            passMarkConversion(tbody);
            paramPassMarkCalculation(tbody);
        });
        $('.full-mark-conversion').on('change paste keyup', function () {
            var tbody = $(this).parent().parent().parent();
            passMarkConversion(tbody);
        });
        $('.pass-mark').on('change paste keyup', function () {
            var tbody = $(this).parent().parent().parent();
            passMarkConversion(tbody);
            paramPassMarkCalculation(tbody);
        });

        $('.param-field-full-mark').on('change paste keyup', function () {
            var tbody = $(this).parent().parent().parent();
            paramPassMarkCalculation(tbody);
        });

        $('.all-field-full-mark').on('change paste keyup', function () {
            var tbody = $(this).parent().parent().parent();
            masterParamPassMarkCalculation(tbody);
            $('.param-field-full-mark').keyup();
        });

        $('.all-effective-for').click(function () {
            if($(this).is(':checked')){
                $('.effective-for').prop('checked', true);
            }else{
                $('.effective-for').prop('checked', false);
            }
        });

        $('.all-radio-aggregated').click(function () {
            if($(this).is(':checked')){
                $('.radio-aggregated').prop('checked', true);
            }else{
                $('.radio-aggregated').prop('checked', false);
            }
        });

        $('.all-radio-individual').click(function () {
            if($(this).is(':checked')){
                $('.radio-individual').prop('checked', true);
            }else{
                $('.radio-individual').prop('checked', false);
            }
        });



        // -- Mark save portion --
        $('.marks-save-btn').click(function () {
            var currentButton = $(this);

            // Catching all the datas
            var tbody = currentButton.parent().parent().parent();
            var datas = {
                subjectId: tbody.find('.subject-id').val(),
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
                    fullMarkParamTotal += parseFloat($(value).val());
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
                    passMarkParamTotal += parseFloat($(value).val());
                    datas.marks.passMarks[paramId] = $(value).val();
                }
            });
            // Data catching finish
            

            // Validating datas
            if (datas.fullMark && datas.fullMarkConversion && datas.passMark && datas.passMarkConversion) {
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
                            'examId': datas.examId,
                            'batchId': datas.batchId,
                            'sectionId': datas.sectionId,
                            'fullMark': datas.fullMark,
                            'fullMarkConversion': datas.fullMarkConversion,
                            'passMark': datas.passMark,
                            'passMarkConversion': datas.passMarkConversion,
                            'marks': JSON.stringify(datas.marks)
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
        });

        // All Save at once
        $('#marks-all-save-btn').click(function () {
            var allSubjectPanels = $('.subject-form-panel');
            var subjectWiseDatas = {};

            allSubjectPanels.each((index, panel) => {
                var tbody = $(panel);
                var subjectId = tbody.data("subject-id");
                var subjectName = tbody.data("subject-name");

                // Catching all the datas
                var datas = {
                    subjectId: tbody.find('.subject-id').val(),
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
                };

                var paramFieldFullMarks = tbody.find('.param-field-full-mark');
                var paramFieldPassMarks = tbody.find('.param-field-pass-mark');
                var fullMarkParamTotal = 0;
                var passMarkParamTotal = 0;


                paramFieldFullMarks.each((index, value) => {
                    var paramId = $(value).data('param-id');
                    if (!$(value).is(':disabled')) {
                        if (!$(value).val()) {
                            swal("Error!", "In "+subjectName+": Please set value for all the parameters!", "error");
                            throw 'Value Missing';
                        }
                        fullMarkParamTotal += parseFloat($(value).val());
                        datas.marks.fullMarks[paramId] = $(value).val();
                    }
                });
                paramFieldPassMarks.each((index, value) => {
                    var paramId = $(value).data('param-id');
                    if (!$(value).is(':disabled')) {
                        if (!$(value).val()) {
                            swal("Error!", "In "+subjectName+": Please set value for all the parameters!", "error");
                            throw 'Value Missing';
                        }
                        passMarkParamTotal += parseFloat($(value).val());
                        datas.marks.passMarks[paramId] = $(value).val();
                    }
                });
                // Data catching finish

                // Validating datas
                if (datas.fullMark && datas.fullMarkConversion && datas.passMark && datas.passMarkConversion) {
                    if (fullMarkParamTotal != datas.fullMark) {
                        swal("Error!", "In "+subjectName+": Total mark parameter values is not equal to Total Mark! adjust them.", "error");
                        throw 'Value Need to adjust';
                    } else if(passMarkParamTotal != datas.passMark){
                        swal("Error!", "In "+subjectName+": Total pass mark parameter values is not equal to Total Pass Mark! adjust them.", "error");
                        throw 'Value Need to adjust';
                    }
                }else{
                    swal("Error!", "In "+subjectName+": Please fill up all the required fields first!", "error");
                    throw 'Value Missing';
                }

                subjectWiseDatas[subjectId] = datas;
            });
            
            // Save Data to database
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/exam/set/all/marks/post') }}",
                type: 'POST',
                cache: false,
                data: {
                    '_token': $_token,
                    'data': subjectWiseDatas,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },
            
                success: function (data) {
                    // hide waiting dialog
                    waitingDialog.hide();

                    if (data.length<1) {
                        swal("Success!", "All marks saved successfully!", "success");
                    }else{
                        var errorText = "";
                        for (var key in data) {
                            if (data.hasOwnProperty(key)) {
                                errorText += (" | "+data[key]+" | ");
                            }
                        }
                        
                        swal("Warning!", errorText, "warning");
                    }

                    $('.marks-save-btn').text("Update");
                },
            
                error: function (error) {
                    // hide waiting dialog
                    waitingDialog.hide();
            
                    console.log(error);

                    swal("Error!", "Error saving marks.", "error");
                }
            });
            // Ajax Request End
        });
    });
</script>
@stop