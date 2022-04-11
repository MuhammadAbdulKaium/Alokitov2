@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Exam |<small>Category & Exam</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Academics</a></li>
            <li class="active">Course Management</li>
            <li class="active">Subject</li>
        </ul>
    </section>
    <section class="content">
        <div id="p0">
            @if(Session::has('message'))
                <p class="alert alert-success alert-auto-hide" style="text-align: center"> 
                <a href="#" class="close" style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
            @elseif(Session::has('alert'))
                <p class="alert alert-warning alert-auto-hide" style="text-align: center">  
                <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" 
                aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
            @elseif(Session::has('errorMessage'))
                <p class="alert alert-danger alert-auto-hide" style="text-align: center">  
                <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" 
                aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
            @endif
        </div>
        <div class="row">
            @if (in_array(3000 ,$pageAccessData))
            <div class="col-sm-4">
                @if (in_array("academics/exam-category/store" ,$pageAccessData))
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus-square"></i> Add Exam Category </h3>
                    </div>
                    <div class="box-body">
                        <form action="/academics/exam-category/store" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-sm-5">
                                    <label for="">Category Name</label>
                                    <input type="text" class="form-control" name="exam_category_name" required>
                                    @error('exam_category_name')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <label for="">Alias</label>
                                    <input type="text" class="form-control" name="alias" required>
                                    @error('alias')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-sm-3">
                                    <button class="btn btn-success" style="margin-top: 23px">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Exam Category List </h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered" id="categoryTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th scope="col">Category Name</th>
                                    <th scope="col">Alias</th>
                                    <th scope="col">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($category as $cat)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{$cat->exam_category_name}}</td>
                                    <td>{{$cat->alias}}</td>
                                    <td>
                                        @if (in_array("academics/exam-category.edit" ,$pageAccessData))
                                        <a href="/academics/edit/exam-category/exam/{{$cat->id}}" data-target="#globalModal" 
                                            class="btn btn-primary btn-xs" data-toggle="modal" data-modal-size="modal-sm" 
                                            tabindex="-1"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if (in_array("academics/exam-category.delete" ,$pageAccessData))
                                        <a href="/academics/delete/exam/category/{{$cat->id}}" class="btn btn-danger btn-xs"
                                            onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                            data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>    
            @endif
            @if (in_array(3200 ,$pageAccessData))
            <div class="col-sm-8">
                @if (in_array("academics/exam-name/store" ,$pageAccessData))
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus-square"></i> Add Exam </h3>
                    </div>
                    <div class="box-body">
                        <form action="/academics/exam-name/store" method="post">
                            @csrf

                            <div class="row">
                                <div class="col-sm-4">
                                    <label for="">Exam Name</label>
                                    <input type="text" class="form-control" name="exam_name">
                                </div>
                                <div class="col-sm-4">
                                    <label for="">Exam Category</label>
                                    <select class="form-control" name="exam_category_id">
                                        <option value="">Select Category</option>
                                        @foreach($category as $cat)
                                            <option value="{{$cat->id}}">{{$cat->exam_category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-2" style="margin-top: 20px">
                                    <input type="checkbox" name="effective_on"> Effective
                                </div>
                                <div class="col-sm-2">
                                    <button class="btn btn-success" style="margin-top: 23px">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Exam List </h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered" id="categoryTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th scope="col">Exam Name</th>
                                    <th scope="col">Exam Category</th>
                                    <th scope="col">Effective?</th>
                                    <th scope="col">Assigned To</th>
                                    <th scope="col" width="12%">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($examName as $name)
                                <tr>
                                    <td>{{ $loop->index+1 }}</td>
                                    <td>{{$name->exam_name}}</td>
                                    <td>{{$name->ExamCategory->exam_category_name}}</td>
                                    <td>{{$name->effective_on=='1'?'Yes':'No'}}</td>
                                    <td>
                                        @if (is_array($name->classes) || is_object($name->classes))
                                            @foreach($name->classes as $cl)
                                                @foreach($batches as $batch)
                                                    @if($batch->id == $cl)
                                                        <span class="badge badge-info">
                                                            {{$batch->batch_name}}
                                                        </span>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if (in_array("academics/exam.assign" ,$pageAccessData))
                                        <a class="btn btn-success btn-xs"
                                        href="{{url('academics/exam/name/assign/view/'.$name->id)}}"
                                        data-target="#globalModal" data-toggle="modal"
                                        data-modal-size="modal-md">A</a>
                                        @endif
                                        @if (in_array("academics/exam.edit" ,$pageAccessData))
                                        <a class="btn btn-primary btn-xs"
                                        href="/academics/edit/exam/name/{{$name->id}}"
                                        data-target="#globalModal" data-toggle="modal"
                                        data-modal-size="modal-md"><i class="fa fa-edit"></i></a>
                                        @endif
                                        @if (in_array("academics/exam.delete" ,$pageAccessData))
                                        <a href="/academics/delete/exam/{{$name->id}}" class="btn btn-danger btn-xs"
                                            onclick="return confirm('Are you sure to Delete?')" data-placement="top"
                                            data-content="delete"><i class="fa fa-trash-o"></i></a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
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
        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
    })
</script>
@stop