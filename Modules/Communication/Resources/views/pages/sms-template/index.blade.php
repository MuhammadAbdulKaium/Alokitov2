@extends('communication::layouts.master')
@section('section-title')
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small> SMS Template</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/dashboard/default/index">Communication</a></li>
            <li class="active">SMS Template List</li>
        </ul>
    </section>
@endsection
<!-- page content -->
@section('page-content')
    {{--
    <section class="content">
    --}}
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-plus-square"></i> SMS Template List </h3>
        </div>


        <div class="box-body">
            <div class="row">


                @if(!empty($smsTemplateList))

                <div class="box-body table-responsive">
                    <a href="/communication/sms/template/" class="btn btn-success pull-right" style="margin-bottom:20px;" role="button">Add SMS Template</a>
                    <div id="p0" data-pjax-container="" data-pjax-push-state="" data-pjax-timeout="10000">
                        <div id="w1" class="grid-view">
                            <table id="feesListTable" class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#ID</th>
                                    <th><a data-sort="sub_master_name">Template Name</a></th>
                                    <th><a data-sort="sub_master_code">SMS Type</a></th>
                                    <th><a data-sort="sub_master_alias">Message</a></th>
                                    <th><a data-sort="sub_master_alias">Created Date</a></th>
                                    <th><a>Action</a></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($smsTemplateList as $smsTemplate)
                                <tr class="gradeX">

                                    <td>{{$smsTemplate->id}}</td>
                                    <td>{{$smsTemplate->template_name}}</td>
                                    <td>
                                        @if($smsTemplate->sms_type=="1")
                                            <span class="label label-primary">Teacher</span>
                                        @elseif($smsTemplate->sms_type=="2")
                                            <span class="label label-success">Student</span>
                                        @elseif($smsTemplate->sms_type=="3")
                                            <span class="label label-info">Parent</span>
                                        @elseif($smsTemplate->sms_type=="4")
                                            <span class="label label-warning">Staff</span>

                                        @elseif($smsTemplate->sms_type=="5")
                                            <span class="label label-warning">Others</span>
                                    @endif

                                    </td>
                                    <td>{{$smsTemplate->message}}</td>
                                    <td>{{date('d-m-Y', strtotime($smsTemplate->created_at))}}</td>
                                    <td>
                                        <a href="{{URL::to('/communication/sms/template/edit',$smsTemplate->id)}}" class="btn btn-success btn-xs"><i class="fa fa-pencil"></i></a>
                                        <a id="{{$smsTemplate->id}}" class="sms_template_delete btn btn-danger btn-xs" data-placement="top" data-content="delete"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="link" style="float: right">
                            {{$smsTemplateList->links()}}
                        </div>
                    </div>
                </div>
                @endif
                <!-- /.box-body -->
            </div>
@endsection
@section('page-script')

    {{--<script>--}}

        // invoice delete ajax request
        $('.sms_template_delete').click(function(e){
            var tr = $(this).closest('tr'),
                del_id = $(this).attr('id');

            swal({
                    title: "Are you sure?",
                    text: "You want to delete sms template",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#DD6B55',
                    confirmButtonText: 'Yes!',
                    cancelButtonText: "No",
                    closeOnConfirm: false,
                    closeOnCancel: false
                },
                function(isConfirm) {

                    if (isConfirm) {

                        $.ajax({
                            url: "/communication/sms/template/delete/" + del_id,
                            type: 'GET',
                            cache: false,
                            success: function (result) {
                                if(result=="success") {
                                    tr.fadeOut(1000, function () {
                                        $(this).remove();
                                    });
                                    swal("Success!", "SMS Template successfully deleted", "success");
                                } else {
                                    swal({
                                        type: 'error',
                                        title: 'Oops...',
                                        text: 'Something went wrong!',
                                    })
                                }
                            }
                        });
                    } else {
                        swal("NO", "Your SMS Template is safe :)", "error");
                        e.preventDefault();
                    }
                });
        });


@endsection