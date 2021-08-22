@extends('layouts.master')

@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Investigation Report</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Health Care</a></li>
            <li>SOP Setup</li>
            <li><a href="/healthcare/investigation/reports">Investigation Reports</a></li>
            <li class="active">Set Investigation Report</li>
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

        @php
            $result = null;
            if($investigationReport->result){
                $result = json_decode($investigationReport->result, 1);
            }
        @endphp

        <div class="row">
            <div class="col-sm-12">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-eye"></i> Investigation Report List </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <form action="{{ url('/healthcare/save/report/'.$investigationReport->id) }}" method="post">
                            @csrf
                            
                            @foreach ($reportPattern as $table)
                            @php
                                $i = $loop->index;
                            @endphp
                            <h4><b>{{ $table['title'] }}</b></h4>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Test</th>
                                        <th>Result</th>
                                        <th>Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($table['tests'] as $test)
                                        <tr>
                                            <td>{{ $loop->index+1 }}</td>
                                            <td>{{ $test['testName'] }}</td>
                                            <td>
                                                @if ($investigationReport->status != 3)
                                                    @if ($result)
                                                        <input type="number" name="result[{{ $i }}][]" class="form-control" value="{{ $result[$i][$loop->index] }}" required>    
                                                    @else
                                                        <input type="number" name="result[{{ $i }}][]" class="form-control" value="0" required>    
                                                    @endif
                                                @else
                                                    {{ $result[$i][$loop->index] }}                                                
                                                @endif                                                
                                            </td>
                                            <td>{{ $test['unit'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @endforeach 
                            
                            @if ($investigationReport->status != 3)
                            <button class="btn btn-success" style="float: right">Save</button>
                            @endif
                        </form>                    
                    </div>
                </div>
            </div>
        </div>

        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="loader">
                            <div class="es-spinner">
                                <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                            </div>
                        </div>
                    </div>
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
        $('#investigationTable').DataTable();

        $('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    });
</script>
@stop