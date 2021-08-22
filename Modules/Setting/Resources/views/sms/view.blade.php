@extends('setting::layouts.master')

@section('section-title')
    <h1>
        <i class="fa fa-th-list"></i> Sms  Institute |<small> Getaway List</small></h1>
    <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
        <li><a href="/academics/default/index">Sms</a></li>
        <li class="active">Institute</li>
    </ul>
@endsection

@section('page-content')
    @if($errors->any())
        <div class="alert alert-danger">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif
    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide dism " style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @endif
    </div>
    <div class="box box-solid">
        <div>
            <div class="box-header with-border">
                <h3 class="box-title"><i class="fa fa-search"></i>View SMS Getaway List</h3>
                <div class="box-tools">
                    <a class="btn btn-success btn-sm" href="{{url('setting/sms/setting/getway')}}">Add SMS Get Way</a></div>
            </div>
        </div>

        @if($smsGetways->count()>0)
        <div class="box-body table-responsive">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                <div id="w1" class="grid-view">

                    <table id="myTable" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th><a  data-sort="sub_master_code">API Path</a></th>
                            <th><a  data-sort="sub_master_alias">Remark</a></th>
                            <th><a  data-sort="sub_master_alias">Upto Date</a></th>
                            <th><a>Action</a></th>
                        </tr>

                        </thead>
                        <tbody>

                        @if(isset($smsGetways))
                            @php

                                $i = 1
                            @endphp
                            @foreach($smsGetways as $smsGetway)

                                <tr class="gradeX">
                                    <td>{{$i++}}</td>
                                    <td>{{$smsGetway->api_path}}</td>
                                    <td>{{$smsGetway->remark}}</td>
                                    <td>{{$smsGetway->activated_upto}}</td>
                                    <td>
                                        {{--<a href="{{ route('--}}
                                        {{---currency', $values->id) }}" class="btn btn-info btn-xs" data-toggle="modal" data-target="#etsbModal" data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}
                                        {{--<a href="{{ url('setting/institute-view', $institute->id) }}" class="btn btn-primary btn-xs"   data-placement="top" data-content="view"><i class="fa fa-eye"></i></a>--}}
                                        <a href="{{ url('setting/sms/getway/edit', $smsGetway->id) }}" id="institute_getway_{{$smsGetway->id}}" class="btn btn-success btn-xs" ><i class="fa fa-edit"></i></a>
                                        <a href="{{ url('setting/sms/getway/delete', $smsGetway->id) }}" class="btn btn-danger btn-xs" onclick="return confirm('Are you sure to Delete?')" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        @endif
                        {{--{{ $data->render() }}--}}

                        </tbody>
                    </table>
                </div>
            </div>
        </div><!-- /.box-body -->
            @endif
    </div><!-- /.box-->



@endsection

@section('page-script')
    $('#myTable').DataTable();

    jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
    $(this).slideUp('slow', function() {
    $(this).remove();
    });
    });

    jQuery('#start_date').datepicker({"changeMonth":true,"changeYear":true,"defaultValue":null,"defaultDate":null,"dateFormat":"dd-mm-yy"});
    jQuery('#end_date').datepicker({"changeMonth":true,"changeYear":true,"defaultValue":null,"defaultDate":null,"dateFormat":"dd-mm-yy"});


@endsection

