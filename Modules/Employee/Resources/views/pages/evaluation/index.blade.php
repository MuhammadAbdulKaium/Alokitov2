@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
<link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
@stop


{{-- Content --}}
@section('content')

<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Evaluation Setup</small>
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
        <div class="row">
            <div class="col-sm-3">
                <div class="box box-solid">
                    <form action="{{url('/employee/create/evaluation-parameter')}}" method="POST">
                        @csrf

                        <div class="box-header">
                            <h4><i class="fa fa-plus-square"></i> Evaluation Parameter</h5>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <input class="form-control" type="text" placeholder="Evaluation Parameter" name="name" required>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-success btn-create" type="submit">Create</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-5">
                <form action="{{ url('/employee/create/evaluation') }}" method="POST">
                    @csrf

                    <div class="box box-solid">
                        <div class="box-header">
                            <h4><i class="fa fa-plus-square"></i> Evaluation</h5>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <input class="form-control evaluation-field" type="text" placeholder="Evaluation Name" name="name" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="form-group">
                                        <input class="form-control evaluation-field" type="number" placeholder="Score" name="score" required>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <input type="text" id="onlyYear" class="form-control hasDatepicker from-date evaluation-field" name="year" maxlength="10"
                                    placeholder="Effective For" aria-required="true" size="10" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    Evaluation By:
                                </div>
                                <div class="col-sm-8">
                                    <select name="evaluationBy[]" id="designation-by" multiple class="form-control evaluation-field" required>
                                        <option value="hrfm">HR/FM</option>
                                        <option value="cadets">Cadets</option>
                                        @foreach ($designations as $designation)
                                            <option value="{{$designation->id}}">{{$designation->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row" style="margin-top: 15px">
                                <div class="col-sm-4">
                                    Evaluation For:
                                </div>
                                <div class="col-sm-8">
                                    <select name="evaluationFor" id="designation-for" class="form-control evaluation-field" required>
                                        <option value="1">HR/FM - Teaching</option>
                                        <option value="2">HR/FM - Non Teaching</option>
                                        <option value="3">Cadets</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-success btn-create">Create</button>
                            <button class="btn btn-default btn-create evaluation-reset-button">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-sm-4">
                <form action="{{url('/employee/setup/evaluation-parameter')}}" method="post">
                    @csrf

                    <div class="box box-solid">
                        <div class="box-header">
                            <h4><i class="fa fa-plus-square"></i> Evaluation Parameter Assign</h5>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="">Evaluation</label>
                                <select class="form-control" name="evaluation" id="" required>
                                    <option value="">-- Select --</option>
                                    @foreach ($evaluations as $evaluation)
                                        @if ($myEvaluation)
                                        <option value="{{$evaluation->id}}" {{($myEvaluation->id == $evaluation->id)?'selected':''}}>{{$evaluation->name}}</option>
                                        @else
                                        <option value="{{$evaluation->id}}">{{$evaluation->name}}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-success btn-create">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-3">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Evaluation Parameter List</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="evaluationParameterList" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Evaluation</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($evaluationParameters as $evaluationParameter)
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$evaluationParameter->name}}</td>
                                    <td>
                                        <a class="btn btn-primary btn-xs"
                                                href="{{url('/employee/edit/evaluation-parameter/'.$evaluationParameter->id)}}"
                                                data-target="#globalModal" data-toggle="modal"
                                                data-modal-size="modal-sm"><i class="fa fa-edit"></i></a>
                                        <a href="{{ url('/employee/delete/evaluation-parameter/'.$evaluationParameter->id) }}"
                                            class="btn btn-danger btn-xs"
                                            onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                            data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Evaluation Name List</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <table id="evaluationNameList" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Evaluation Name</th>
                                    <th>Score</th>
                                    <th>Effective For</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $totalEvaluationScore = 0;
                                @endphp
                                @foreach ($evaluations as $evaluation)  
                                @php
                                    $totalEvaluationScore += $evaluation->score;
                                @endphp  
                                <tr>
                                    <td>{{$loop->index+1}}</td>
                                    <td>{{$evaluation->name}}</td>
                                    <td>{{$evaluation->score}}</td>
                                    <td>{{$evaluation->year}}</td>
                                    <td>
                                        <a class="btn btn-success btn-xs"
                                                href="{{url('/employee/assign-view/evaluation-parameter/'.$evaluation->id)}}"
                                                data-target="#globalModal" data-toggle="modal"
                                                data-modal-size="modal-md">A</a>
                                        <a class="btn btn-primary btn-xs"
                                                href="{{url('/employee/edit/evaluation/'.$evaluation->id)}}"
                                                data-target="#globalModal" data-toggle="modal"
                                                data-modal-size="modal-md"><i class="fa fa-edit"></i></a>
                                        <a href="{{url('employee/delete/evaluation/'.$evaluation->id)}}"
                                            class="btn btn-danger btn-xs"
                                            onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                            data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Manage Evaluations 
                        </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <form action="{{url('employee/setup/update/evaluation-parameter')}}" method="POST">
                            @csrf

                            <input type="hidden" name="evaluationId" value="{{($myEvaluation)?$myEvaluation->id:''}}">

                            <table id="evaluationParameterWithEvaluationName" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Evaluation Parameter</th>
                                        <th>Evaluation Name</th>
                                        <th>Score</th>
                                        <th>Effective For</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $totalScore = 0;
                                    @endphp
                                    @if ($myEvaluation)    
                                        @foreach ($myEvaluation->parameters as $parameter)    
                                            <tr>
                                                <td>{{$loop->index+1}}</td>
                                                <td>{{$parameter->name}}
                                                    <input type="hidden" name="parameters[]" value="{{$parameter->id}}">
                                                </td>
                                                <td>{{$myEvaluation->name}}</td>
                                                <td>
                                                    <input type="number" class="form-control" name="scores[]" value="{{$parameter->pivot->score}}">
                                                </td>
                                                <td>{{$myEvaluation->year}}</td>
                                            </tr>
                                            @php
                                                $totalScore += $parameter->pivot->score;
                                            @endphp
                                        @endforeach
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right text-bold">Total</td>
                                        <td>{{$totalScore}}</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                            <button class="btn btn-success">Save</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content" id="modal-content">
            <div class="modal-body" id="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@stop

{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('#evaluationParameterList').DataTable();
        $('#evaluationNameList').DataTable();
        $('#evaluationParameterWithEvaluationName').DataTable();

        $('#designation-by').select2();
        $('#evaluation-parameter').select2();

        $('#onlyYear').datepicker({
            format: "yyyy",
            viewMode: "years",
            minViewMode: "years"
        });

        $('.evaluation-reset-button').click(function(){
            $('.evaluation-field').val('');
            $('.evaluation-field').val(null).trigger('change');
        });

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
</script>
@stop