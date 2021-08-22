@extends('layouts.master')

@section('content')
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage  |<small>Fine List</small>
            </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/library/default/index">Library</a></li>
                <li class="active">Fine</li>
            </ul>
        </section>
        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
            @if(Session::has('message'))


                <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @endif
        </div>
        <section class="content">
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title"><i class="fa fa-search"></i> View Fine List</h3>
                </div>
                <div class="box-body table-responsive">

                    <div id="w1" class="grid-view">

                        <table id="myTable" class="table table-striped table-bordered">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th><a  data-sort="sub_master_type">Holder Type</a></th>
                                <th><a  data-sort="sub_master_name">Holder Name</a></th>
                                <th><a  data-sort="sub_master_alias">Total Fine</a></th>
                                <th><a  data-sort="sub_master_alias">Paid</a></th>
                                <th><a>Action</a></th>
                            </tr>

                            </thead>
                            <tbody>

                            @if(isset($fineList))
                                @php
                                    $i = 1
                                @endphp
                                @foreach($fineList as $values)
                                    <tr class="gradeX">
                                        <td>{{$i++}}</td>
                                        <td>
                                            @if($values->holder_type =="1")
                                                Student
                                            @elseif ($values->holder_type =="2")
                                                Teacher
                                            @endif
                                        </td>
                                        <td>
                                            @if($values->holder_type =="1")
                                                {{$values->student()->first_name.' '.$values->student()->middle_name.' '.$values->student()->last_name}}
                                            @elseif ($values->holder_type =="2")
                                                {{$values->employee()->first_name.' '.$values->employee()->middle_name.' '.$values->employee()->last_name}}
                                            @endif
                                        </td>
                                        <td>{{$values->total_fine}}</td>
                                        <td>{{$values->total_due}}</td>
                                        <td>
                                            <a href="/library/library-borrow-transaction/return-book-with-fine-show/{{$values->id}}" class="btn btn-primary btn-xs">Edit</a>
                                            <a href="{{ url('academics/delete-academic-year', $values->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            {{ $fineList->render() }}

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </section>
        </div>
    <div id="slideToTop"><i class="fa fa-chevron-up"></i></div>


@endsection
<!--
TO load view of each row
 -->
@section('scripts')
    <script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>

    <script type="text/javascript">

        $(document).ready(function(){
            //alert();
            $('#myTable').DataTable();
        });

    </script>

@endsection
