@extends('layouts.master')
@section('content')
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
    @foreach ($division->batches() as $batch)
    <div class="col-sm-4">


                        <table class="table table-bordered table-hover">
                            <tr>
                                <td>Class</td>
                                <td>{{$batch->batch_name}}</td>
                            </tr>
                            <tr>
                                <td>Section</td>
                                <td>{{$batch->section()->count()}}</td>
                                <td><a href="{{url('academics/section')}}">Add Section</a></td>
                            </tr>
                            <tr>
                                <td>Subjects</td>
                                <td></td>
                                <td><a href="{{url('academics/subject')}}">Add Subjects</a></td>
                            </tr>
                            <tr>
                                <td>Students</td>
                                <td></td>
                                <td><a href="{{url('student')}}">Add Students</a></td>
                            </tr>
                        </table>
                    </div>

        @endforeach
        </div>
    </div>
</div>
    @endforeach
</div>
<div class="wrapper">
        <div class="row">
            <div class="panel panel-primary" style="margin:5px;">
                <div class="panel-heading">
                    <h3 class="panel-title" >

                        <a data-toggle="collapse"  id="collapse-plus-minus" onclick="plus_minus_control_on_colapse(this.id)" href="#collapse1" class="fa fa-plus">No Division</a>
                    </h3>
                </div>
                <div  id="collapse1" class="panel-body noPadding panel-collapse collapse">
                    @foreach ($batchesWithoutDivision as $batch)
                        <div class="col-sm-4">


                            <table class="table table-bordered table-hover">
                                <tr>
                                    <td>Class</td>
                                    <td>{{$batch->batch_name}}</td>
                                </tr>
                                <tr>
                                    <td>Section</td>
                                    <td>{{$batch->section()->count()}}</td>
                                    <td><a href="{{url('academics/section')}}">Add Section</a></td>
                                </tr>
                                <tr>
                                    <td>Subjects</td>
                                    <td></td>
                                    <td><a href="{{url('academics/subject')}}">Add Subjects</a></td>
                                </tr>
                                <tr>
                                    <td>Students</td>
                                    <td></td>
                                    <td><a href="{{url('student')}}">Add Students</a></td>
                                </tr>
                            </table>
                        </div>

                    @endforeach
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