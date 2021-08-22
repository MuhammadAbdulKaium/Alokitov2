@extends('layouts.master')
@section('content')
    <style type="text/css">
        .table > tbody > tr>th, .table > tbody > tr>td>a{
            color:  #000000 !important;
        }
    </style>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage |<small>Academics</small></h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Academics</a></li>
                <li class="active">Manage Academics</li>
            </ul>
        </section>
        @foreach($divisions as $division)
            <div class="row">
                <div class="panel panel-primary" style="margin:5px;">
                    <div class="panel-heading">
                        <h3 class="panel-title" >
                            <a data-toggle="collapse"  id="collapse-plus-minus{{$division->id}}" onclick="plus_minus_control_on_colapse(this.id)" href="#collapse1{{$division->id}}" class="fa fa-plus">{{$division->name}} </a>
                        </h3>
                    </div>
                    <div  id="collapse1{{$division->id}}" class="panel-body noPadding panel-collapse collapse">
                        @php $i=0; @endphp
                        @foreach ($division->batches as $batch)
                            <div class="col-sm-4">
                                <table class="table @if($i % 2 == 0) bg-green @else bg-orange @endif">
                                    <tr>
                                        <th>Class</th>
                                        <th>{{$batch->batch_name}}</th>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <th>Section</th>
                                        <td>
                                            <a href="{{url('academics/manage/section/show/'.$batch->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lgs">{{$batch->section()->count()}}</a>
                                        </td>
                                        <td>
                                            <a href="{{url('academics/manage/section/create/'.$batch->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lgs">Add Section</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Subjects</th>
                                        <td></td>
                                        <td>
                                            <a href="{{url('academics/manage/subject/'.$batch->id)}}">Add Subject</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            @php $i=$i+1; @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="wrapper">
        <div class="row" style="margin-bottom: 50px;">
            <div class="panel panel-primary" style="margin:5px;">
                <div class="panel-heading">
                    <h3 class="panel-title" >
                        <a data-toggle="collapse"  id="collapse-plus-minus" onclick="plus_minus_control_on_colapse(this.id)" href="#collapse1" class="fa fa-plus">No Division</a>
                    </h3>
                </div>
                <div  id="collapse1" class="panel-body noPadding panel-collapse collapse">
                    @php $i=0; @endphp
                    @foreach ($batchesWithoutDivision as $batch)
                        <div class="col-sm-4">
                            <table class="table @if($i % 2 == 0) bg-green @else bg-orange @endif" style="border-radius: 4px;">
                                <tr>
                                    <th>Class</th>
                                    <th>{{$batch->batch_name}}</th>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th>Section</th>
                                    <td>{{$batch->section()->count()}}</td>
                                    <td>
                                        <a href="{{url('academics/section')}}">Add Section</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Subjects</th>
                                    <td></td>
                                    <td>
                                        <a href="{{url('academics/manage/subject/'.$batch->id)}}">Add Subject</a>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        @php $i=$i+1; @endphp
                    @endforeach
                </div>
            </div>
        </div>

    </div>
    <!-- global modal -->
    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
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
@endsection
@section('scripts')

    <script type="text/javascript">
        function plus_minus_control_on_colapse(id) {
            if($("#"+id).hasClass('fa fa-plus'))
            {
                $("#"+id).removeClass("fa fa-plus");
                $("#"+id).addClass("fa fa-minus");
            }
            else if($("#"+id).hasClass('fa fa-minus'))
            {
                $("#"+id).removeClass("fa fa-minus");
                $("#"+id).addClass("fa fa-plus");
            }
        }

    </script>
@endsection
