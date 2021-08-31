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
            <div class="box-header">
                <h4><i class="fa fa-search"></i> Search Evaluation</h4> 
            </div>
            <div class="box-body table-responsive">
                <form action="{{ url('employee/evaluation/search') }}" method="POST">
                    @csrf

                    <div class="row" style="margin-bottom: 20px">
                        <div class="col-sm-3">
                            <select name="evaluationFor" id="designation-for" class="form-control evaluation-for" required>
                                <option value="">Evaluation For</option>
                                <option value="1">HR/FM - Teaching</option>
                                <option value="2">HR/FM - Non Teaching</option>
                                <option value="3">Students</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="year" id="" class="form-control years">
                                <option value="">Select Year</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="evaluationId" id="" class="form-control evaluations">
                                <option value="">Select</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <button class="btn btn-success">Search</button>
                        </div>
                    </div>
                </form>
                @isset($evaluation)
                <h3>{{ $evaluation->name }} ({{ $evaluation->year }})</h3>
                @endisset
                @if ($evaluationStatus == 2)
                    <h3>Final Evaluation ({{ $year }})</h3>
                @endif
                @if ($evaluationStatus == 1)
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr style="height:300px;">
                            <th>Sl</th>
                            <th>Name & Designation</th>
                            @php
                            $totalParamVal = 0;
                            @endphp
                                @isset ($parameters)
                                @foreach ($parameters as $key => $parameter)
                                @php
                                $totalParamVal += $parameter->pivot->score;
                                @endphp
                                <th class="evaluation-th">
                                    <div class="evaluation-parameter">
                                        {{$parameter->name}} ({{$parameter->pivot->score}})
                                    </div>
                                </th>
                                @endforeach
                                @endisset
                            <th>Total ({{$totalParamVal}})</th>
                            <th>On 100</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($evaluationMarks)    
                        @foreach ($evaluationMarks as $key1 => $evaluationMark)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                @if ($evaluationMark[0]->employeeDesignation)
                                <td>{{$userNames[$key1]}} - {{ $evaluationMark[0]->employeeDesignation->name }}</td>
                                @else
                                <td>{{$userNames[$key1]}}</td>
                                @endif

                                @php
                                    $paramScores = [];
                                    $total = 0;
                                    $on100 = 0;
                                    $i = 0;
                                    foreach ($parameters as $key2 => $parameter) {
                                        $paramScores[$key2+1] = 0;
                                    }
                                    foreach($evaluationMark as $mark){
                                        $i++;
                                        $total += $mark->total;
                                        $on100 += $mark->on100;
                                        $parametersScores = json_decode($mark->parameters_score);
                                        foreach ($parametersScores as $key3 => $paramScore) {
                                            $paramScores[$key3] += $paramScore;
                                        }
                                    }
                                @endphp
                                @foreach ($paramScores as $score)
                                    <td>{{$score/$i}}</td>
                                @endforeach
                                <td>{{$total/$i}}</td>
                                <td>{{$on100/$i}}</td>
                            </tr>
                        @endforeach
                        @endisset
                    </tbody>
                </table>
                @elseif($evaluationStatus == 2)
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr style="height:300px;">
                            <th>Sl</th>
                            <th>Name & Designation</th>
                            @php
                            $totalParamVal = 0;
                            @endphp
                                @foreach ($evaluations as $evaluation)
                                @php
                                $totalParamVal += $evaluation->score;
                                @endphp
                                <th class="evaluation-th">
                                    <div class="evaluation-parameter">
                                        {{$evaluation->name}} ({{$evaluation->score}})
                                    </div>
                                </th>
                                @endforeach
                            <th>Total ({{$totalParamVal}})</th>
                            <th>On 100</th>
                            <th>Remarks (Position)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @isset($evaluationMarks)    
                        @foreach ($evaluationMarks as $key => $evaluationMark)
                            <tr>
                                <td>{{$loop->index+1}}</td>
                                @if ($evaluationMark[0]->employeeDesignation)
                                <td>{{$userNames[$key]}} - {{ $evaluationMark[0]->employeeDesignation->name }}</td>
                                @else
                                <td>{{$userNames[$key]}}</td>
                                @endif
                                @php
                                    $total = 0
                                @endphp
                                @foreach ($evaluations as $evaluation)
                                    @php
                                        $mark = 0;
                                        $i = 0;
                                    @endphp
                                    @foreach ($evaluationMark as $individualMark)
                                        @if ($evaluation->id == $individualMark->evaluation_id)
                                            @php
                                                $i++;
                                                $mark += $individualMark->total;
                                            @endphp
                                        @endif
                                    @endforeach
                                    @php
                                        $i = ($i != 0)?$i:1;
                                        $total += $mark/$i;
                                    @endphp
                                    <td>{{$mark/$i}}</td>
                                @endforeach
                                <td>{{$total}}</td>
                                <td>{{($total / $totalParamVal) * 100 }}</td>
                                <td>{{ $position[$key] }}</td>
                            </tr>
                        @endforeach
                        @endisset
                    </tbody>
                </table>
                @endif
            </div>
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

        $('.evaluation-for').change(function () {
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/employee/evaluation/ajax/search/year') }}",
                type: 'get',
                cache: false,
                data: {
                    '_token': $_token,
                    'designation': $(this).val()
                }, //see the $_token
                datatype: 'application/json',

                success: function (data) {
                    var txt = '<option value="">Select Year</option>';

                    data.forEach(item => {
                        txt += '<option value="'+item+'">'+item+'</option>';
                    });

                    $('.years').empty();
                    $('.years').append(txt);
                },

                error: function (error) {}
            });
        });

        $('.years').change(function () {
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/employee/evaluation/ajax/search/evaluation') }}",
                type: 'get',
                cache: false,
                data: {
                    '_token': $_token,
                    'year': $(this).val(),
                    'designations': $('.evaluation-for').val()
                }, //see the $_token
                datatype: 'application/json',

                success: function (data) {
                    var txt = '<option value="">Select Evaluation</option>';

                    data.forEach(item => {
                        txt += '<option value="'+item.id+'">'+item.name+'</option>';
                    });

                    $('.evaluations').empty();
                    $('.evaluations').append(txt);
                },

                error: function (error) {}
            });
        });
    });
</script>
@stop