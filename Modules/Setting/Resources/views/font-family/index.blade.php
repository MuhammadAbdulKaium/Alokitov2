@extends('setting::layouts.master')
<link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css"/>

@section('section-title')

    <h1>
        <i class="fa fa-th-list"></i> Manage <small>Font Family</small>
    </h1>
    <ul class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Setting</a></li>
        <li class="active">Configuration</li>
        <li class="active">Font Family Name</li>
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
            <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Font Family</h3>
        </div>

        <form id="setting-fontFamily-form" name="setting_fontFamily_form" action="{{url('setting/font-family/store')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="font_family_id" @if(!empty($fontFamilyProfile)) value="{{$fontFamilyProfile->id}}" @endif>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">Font Family Name</label>
                            <input type="text" id="fontFamily_name" class="form-control" name="font_name" @if(!empty($fontFamilyProfile)) value="{{$fontFamilyProfile->font_name}}" @endif maxlength="255" placeholder="Enter Font Family Name" aria-required="true">
                            <div class="help-block">
                                @if ($errors->has('font_name'))
                                    <strong>{{ $errors->first('font_name') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">Font Family Link</label>
                            <input type="text" id="font_link" class="form-control" name="font_link" maxlength="255" placeholder="Enter Font Family Link" @if(!empty($fontFamilyProfile)) value="{{$fontFamilyProfile->font_link}}" @endif aria-required="true">
                            <div class="help-block">
                                @if ($errors->has('font_link'))
                                    <strong>{{ $errors->first('font_link') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>


                    <div class="col-sm-3">
                        <div class="form-group field-subjectmaster-sub_master_name required">
                            <label class="control-label" for="name">CSS Code</label>
                            <input type="text" id="font_css_code" class="form-control" name="font_css_code" maxlength="255" placeholder="Enter Font Family CSS Code" @if(!empty($fontFamilyProfile)) value="{{$fontFamilyProfile->font_css_code}}" @endif aria-required="true">
                            <div class="help-block">
                                @if ($errors->has('font_css_code'))
                                    <strong>{{ $errors->first('font_css_code') }}</strong>
                                @endif
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                @if(empty($fontFamilyProfile))
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
            <h3 class="box-title"><i class="fa fa-search"></i> View Font Family List</h3>
        </div>
        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="myTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><a  data-sort="sub_master_name">Font Family Name</a></th>
                            <th><a  data-sort="sub_master_name">Font Family Link</a></th>
                            <th><a  data-sort="sub_master_name">Font CSS Code </a></th>


                            <th><a>Action</a></th>
                        </tr>

                        </thead>
                        <tbody>

                        @if(!empty($fontFamilys))
                            @php
                                $i = 1
                            @endphp
                            @foreach($fontFamilys as $fontFamily)
                                <tr class="gradeX">
                                    <td>{{$fontFamily->id}}</td>
                                    <td>{{$fontFamily->font_name}}</td>
                                    <td>{{$fontFamily->font_link}}</td>
                                    <td>{{$fontFamily->font_css_code}}</td>
                                    <td>
                                        {{--<a href="{{ route('--}}
                                        {{---currency', $values->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}

                                        <a href="{{ url('setting/font-family/edit', $fontFamily->id) }}" class="btn btn-primary btn-xs" ><i class="fa fa-edit"></i></a>
                                        <a href="{{ url('setting/font-family/delete', $fontFamily->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
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

