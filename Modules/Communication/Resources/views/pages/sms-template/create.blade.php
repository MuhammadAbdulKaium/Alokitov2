@extends('communication::layouts.master')
@section('section-title')
    <link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet" type="text/css"/>
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small> SMS Template</small>        </h1>
        <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/dashboard/default/index">Communication</a></li>
            <li class="active">SMS Template</li>
        </ul>
    </section>

@endsection
<!-- page content -->
@section('page-content')
    {{--<section class="content">--}}
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-plus-square"></i> SMS Template</h3>
        </div>


            <div class="box-body">
                <a href="/communication/sms/template/list" class="btn btn-success pull-right" style="margin-bottom:20px;" role="button">SMS Template List</a>

                <div class="row">
                    <div class="col-sm-6">
                        <form id="sms_template_form"
                              method="post" enctype="multipart/form-data">
                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                            <input type="hidden" name="sms_template_id" @if(!empty($smsTemplateProfile->id)) value="{{$smsTemplateProfile->id}}" @endif>

                            <div class="form-group">
                                <label for="sms">Template Name:</label>
                                <input type="text" name="template_name" id="template_name" @if(!empty($smsTemplateProfile->template_name)) value="{{$smsTemplateProfile->template_name}}" @endif class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="section">Template Type</label>
                                <select id="template_type" class="form-control academicSection" required name="sms_type">
                                    <option value="">--- Select Section ---</option>
                                    <option  @if(!empty($smsTemplateProfile->sms_type) && $smsTemplateProfile->sms_type==1)) selected @endif value="1">Teacher</option>
                                    <option @if(!empty($smsTemplateProfile->sms_type) && $smsTemplateProfile->sms_type==2) selected @endif value="2">Student</option>
                                    <option @if(!empty($smsTemplateProfile->sms_type) && $smsTemplateProfile->sms_type==3) selected @endif value="3">Parents</option>
                                    <option @if(!empty($smsTemplateProfile->sms_type) && $smsTemplateProfile->sms_type==4) selected @endif  value="4">Staff</option>
                                    <option @if(!empty($smsTemplateProfile->sms_type) && $smsTemplateProfile->sms_type==5) selected @endif  value="5">Others</option>

                                </select>
                                <div class="help-block"></div>
                            </div>


                            <div class="form-group">
                                <label for="student">Message :</label>
                                <textarea class="form-control" required name="message" rows="5" id="message">@if(!empty($smsTemplateProfile->message)){{$smsTemplateProfile->message}}@endif</textarea>
                            </div>
                            <span id="remaining" style="float: right">160 characters remaining</span> <br>

                            <button type="button" class="btn btn-success smsTemplateStore">Submit</button>
                        </form>
                    </div>


    </div><!-- /.box-->

    </div>



@endsection

@section('page-script')

{{--<script>--}}

    // sms Template store ajax request
    $(".smsTemplateStore").click(function(e){

                    $.ajax({
                        url: '/communication/sms/template/store',
                        type: 'POST',
                        cache: false,
                        data: $('form#sms_template_form').serialize(),
                        datatype: 'json/application',
                        success: function (result) {

                            if(result=="insert"){
                                 swal("Success!", "SMS Template Successfully Created", "success");
                            }else if(result=="update"){
                             swal("Success!", "SMS Template Successfully Updated", "success");
                         }

                        }
                    });
    });



@endsection

