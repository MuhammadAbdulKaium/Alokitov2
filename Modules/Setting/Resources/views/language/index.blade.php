@extends('setting::layouts.master')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

@section('section-title')

    <h1>
        <i class="fa fa-th-list"></i> Manage <small>Language</small>
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Setting</a></li>
        <li class="active">Configuration</li>
        <li class="active">Language Name</li>
    </ul>
@endsection

@section('page-content')
    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @endif
    </div>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Language</h3>
        </div>

        <form id="setting-Language-form" name="setting_Language_form" action="{{url('setting/language/store')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="language_id" @if(!empty($languageProfile)) value="{{$languageProfile->id}}" @endif>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">Language Name</label>
                            <input type="text" id="language_name" class="form-control" name="language_name" @if(!empty($languageProfile)) value="{{$languageProfile->language_name}}" @endif maxlength="60" placeholder="Enter Language Name" aria-required="true">
                            <div class="help-block">
                                @if ($errors->has('language_name'))
                                    <strong>{{ $errors->first('language_name') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">Language Slug</label>
                            <input type="text" id="language_slug" class="form-control" name="language_slug" maxlength="60" placeholder="Enter Language Slug" @if(!empty($languageProfile)) value="{{$languageProfile->language_slug}}" @endif aria-required="true">
                            <div class="help-block">
                                @if ($errors->has('language_slug'))
                                    <strong>{{ $errors->first('language_slug') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                @if(empty($languageProfile))
                    <button type="submit" class="btn btn-primary btn-create">Create</button>
                @else
                    <button type="submit" class="btn btn-primary btn-create">Update</button>
                @endif
                <button type="reset" class="btn btn-default btn-create">Reset</button>
            </div>
            <!-- /.box-footer-->
        </form>

    </div>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> View Language List</h3>
        </div>
        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="myTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><a  data-sort="sub_master_name">Language Name</a></th>


                            <th><a>Action</a></th>
                        </tr>

                        </thead>
                        <tbody>

                        @if(isset($languages))
                            @php
                                $i = 1
                            @endphp
                            @foreach($languages as $language)
                                <tr class="gradeX">
                                    <td>{{$i++}}</td>
                                    <td>{{$language->language_name}}</td>
                                    <td>
                                        {{--<a href="{{ route('--}}
                                        {{---currency', $values->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}

                                        <a href="{{ url('setting/language/edit', $language->id) }}" class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></a>
                                        <a href="{{ url('setting/language/delete', $language->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        {{--{{ $data->render() }}--}}

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box-->
@endsection

@section('page-script')


@endsection

