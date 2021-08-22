@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
<link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
<style>
    .evaluation-th {
        position: relative;
    }

    .evaluation-parameter {
        /* transform-origin: left; */
        width: 200px;
        transform: translate(-50%) rotate(-90deg);
        position: absolute;
        left: 50%;
        top: 50%;
    }
</style>
@stop


{{-- Content --}}
@section('content')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Evaluation</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/">Human Resource</a></li>
            <li>SOP Setup</li>
            <li class="active">Evaluation Setup</li>
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
            @php
                $totalParamVal = 0;
            @endphp
            @if ($myEvaluation)    
                <div class="box-header">
                    <h4><i class="fa fa-plus-square"></i> Evaluation</h5>
                        <ul class="nav nav-tabs">
                            @foreach ($evaluations as $evaluation)
                            <li class="nav-item {{ ($myEvaluation->id == $evaluation->id)?'active':'' }}">
                                <a href="{{url('employee/evaluation/view/'.$evaluation->id)}}">{{$evaluation->name}}</a>
                            </li>
                            @endforeach
                        </ul>
                </div>
                <form action="{{ url('/employee/evaluation/score/distribution') }}" method="post">
                    @csrf

                    <input type="hidden" name="evaluationId" value="{{ $myEvaluation->id }}">

                    <div class="box-body table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th colspan="16">{{$myEvaluation->name}}: {{ $myEvaluation->year }}</th>
                                </tr>
                                <tr style="height:300px;">
                                    <th>Sl</th>
                                    <th>Photo</th>
                                    <th>Emp Identity</th>
                                    @foreach ($myEvaluation->parameters as $parameter)
                                    @php
                                    $totalParamVal += $parameter->pivot->score;
                                    @endphp
                                    <th class="evaluation-th">
                                        <div class="evaluation-parameter">
                                            {{$parameter->name}} ({{$parameter->pivot->score}})
                                        </div>
                                    </th>
                                    @endforeach
                                    <th>Total ({{$totalParamVal}})</th>
                                    <th>On 100</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($employees)
                                @foreach ($employees as $employee)
                                @php
                                $existingEvaluation = null;
                                $marks = null;
                                foreach($existingEvaluationMarks as $existingEvaluationMark){
                                if($employee->user_id == $existingEvaluationMark->score_for){
                                $marks = json_decode($existingEvaluationMark->parameters_score);
                                $existingEvaluation = $existingEvaluationMark;
                                }
                                }
                                @endphp
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>
                                        @if ($employee->singelAttachment("PROFILE_PHOTO"))
                                        <img style="width: 50px"
                                            src="{{URL::asset('assets/users/images/'.$employee->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}"
                                            alt="">
                                        @else
                                        <img style="width: 50px"
                                            src="{{URL::asset('assets/users/images/user-default.png')}}" alt="">
                                        @endif
                                    </td>
                                    <td>
                                        Emp ID: {{ $employee->id }}<br>
                                        Emp Name: <b>{{$employee->first_name}} {{$employee->last_name}}</b><br>
                                        Department: {{ $employee->department()->name }}<br>
                                        Designation: {{ $employee->designation()->name }}<br>
                                    </td>
                                    @foreach ($myEvaluation->parameters as $parameter)
                                    @php
                                    $score = null;
                                    if($marks){
                                    foreach($marks as $key => $mark){
                                    if($parameter->id == $key){
                                    $score = $mark;
                                    }
                                    }
                                    }
                                    @endphp
                                    <td><input name="es-{{ $parameter->id }}[]" value="{{ ($score)?$score:'' }}"
                                            type="number" min="0"
                                            max="{{($parameter->pivot->score)?$parameter->pivot->score:'0'}}"
                                            class="form-control score-field"></td>
                                    @endforeach
                                    <td class="total-score">{{ ($existingEvaluation)?$existingEvaluation->total:'0' }}</td>
                                    <td class="score-on-100">{{ ($existingEvaluation)?$existingEvaluation->on100:'0' }}</td>
                                    <td>
                                        <textarea name="eRemarks[]" id="" cols="30" rows="1"
                                            class="form-control">{{ ($existingEvaluation)?$existingEvaluation->remarks:'' }}</textarea>
                                    </td>
                                </tr>
                                @endforeach
                                @endif

                                @if ($students)
                                @foreach ($students as $student)
                                @php
                                $existingEvaluation = null;
                                $marks = null;
                                foreach($existingEvaluationMarks as $existingEvaluationMark){
                                if($student->user_id == $existingEvaluationMark->score_for){
                                $marks = json_decode($existingEvaluationMark->parameters_score);
                                $existingEvaluation = $existingEvaluationMark;
                                }
                                }
                                @endphp
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>
                                        @if($student->singelAttachment("PROFILE_PHOTO"))
                                            <img src="{{URL::asset('assets/users/images/'.$student->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px">
                                        @else
                                            <img src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px">
                                        @endif
                                    </td>
                                    <td>
                                        Std ID: {{$student->std_id}}<br>
                                        Std Name: {{$student->first_name}} {{$student->last_name}}<br>
                                        Class: {{ $student->batch()->batch_name }}<br>
                                        Section: {{ $student->section()->section_name }}<br>
                                    </td>
                                    @foreach ($myEvaluation->parameters as $parameter)
                                    @php
                                    $score = null;
                                    if($marks){
                                    foreach($marks as $key => $mark){
                                    if($parameter->id == $key){
                                    $score = $mark;
                                    }
                                    }
                                    }
                                    @endphp
                                    <td><input name="ss-{{ $parameter->id }}[]" value="{{ ($score)?$score:'' }}"
                                            type="number" min="0"
                                            max="{{($parameter->pivot->score)?$parameter->pivot->score:'0'}}"
                                            class="form-control score-field"></td>
                                    @endforeach
                                    <td class="total-score">{{ ($existingEvaluation)?$existingEvaluation->total:'0' }}</td>
                                    <td class="score-on-100">{{ ($existingEvaluation)?$existingEvaluation->on100:'0' }}</td>
                                    <td>
                                        <textarea name="sRemarks[]" id="" cols="30" rows="1"
                                            class="form-control">{{ ($existingEvaluation)?$existingEvaluation->remarks:'' }}</textarea>
                                    </td>
                                </tr>
                                @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <div class="box-footer text-right">
                        <button class="btn btn-success">Save</button>
                    </div>
                </form>
            @else
                <div class="box-body">
                    <h5 class="text-danger" style="text-align: center">Nothing for you to Evaluate</h5>
                </div>
            @endif
        </div>
    </section>
</div>

@stop



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });

        var totalParamVal = {!!json_encode($totalParamVal) !!};

        function calculateTotal(parentId) {
            var scoreFields = parentId.find('.score-field');
            var total = 0;
            scoreFields.each(function (index) {
                if ($(this).val()) {
                    total += parseInt($(this).val());
                }
            });
            parentId.find('.total-score').text(total);
            parentId.find('.score-on-100').text(parseInt((total / totalParamVal) * 100));
        }

        $('.score-field').keyup(function () {
            var parentId = $(this).parent().parent();
            calculateTotal(parentId);
        });
        $(".score-field").bind('keyup mouseup', function () {
            var parentId = $(this).parent().parent();
            calculateTotal(parentId);            
        });
    });
</script>
@stop