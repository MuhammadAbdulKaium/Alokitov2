@extends('admission::layouts.master')

@section('styles')
@endsection
<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-plus-square" aria-hidden="true"></i> Create Admission Letter        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/admission/default/index">Enquiry</a></li>
                <li><a href="/admission/admission-letter/index">Admission Letters</a></li>
                <li class="active">Create Admission Letter</li>
            </ul>    </section>
        <section class="content">
            <div class="row">
                <div class="col-md-10">
                    <div class="box box-solid">
                        <form id="admission-letter-form" action="/admission/admission-letter/create" method="post" role="form">
                            <input type="hidden" name="_csrf" value="S1VuWjVjZjgDHhxsbVMQaQINMSBHDCENEWwnLgEvElUlMVcwV1IyaA==">   <div class="box-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="form-group field-admissionletter-admission_letter_title required">
                                            <label class="control-label" for="admissionletter-admission_letter_title">Letter Title</label>
                                            <input type="text" id="admissionletter-admission_letter_title" class="form-control" name="AdmissionLetter[admission_letter_title]" maxlength="60" aria-required="true">
                                            <p class="help-block help-block-error"></p>
                                        </div>         </div>
                                    <div class="col-sm-12">
                                        <div class="form-group field-admissionletter-admission_letter_content required">
                                            <label class="control-label" for="admissionletter-admission_letter_content">Letter Content</label>
                                            <textarea id="admissionletter-admission_letter_content" name="AdmissionLetter[admission_letter_content]" rows="10" aria-required="true"></textarea>
                                            <p class="help-block help-block-error"></p>
                                        </div>         </div>
                                </div>
                            </div><!-- /.box-body -->
                            <div class="box-footer">
                                <button type="submit" class="btn btn-primary btn-create">Create</button>      <a class="btn btn-default btn-create" href="/admission/admission-letter/index">Cancel</a> </div><!-- /.box-footer-->
                        </form>        </div>
                </div>
                <div class="col-md-2">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h4 class="box-title">
                                Choose content variables        </h4>
                        </div>
                        <div id="adm-content" class="box-body" style="max-height: 710px;overflow:auto">
                            <div class="row">
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{title}">Title</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{date}">Current Date</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{name}">Name</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{applyCourse}">Apply Course</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{applyBatch}">Apply Batch</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{regNo}">Registration No</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{dob}">Date of Birth</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{gender}">Gender</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{email}">Email Id</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{contactno}">Mobile No</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{phoneno}">Phone No</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{address}">Address</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{city}">City</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{state}">State</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{country}">Country</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{pin}">Pincode</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{houseno}">House No</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{instituteName}">Institute Name</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{instituteWebsite}">Institute website</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{instituteAlias}">Institute Short Name</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{instituteEmail}">Institute Email</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{instituteAddress}">Institute Address</button>                </div>
                                <div class="col-sm-4 col-md-12 col-xs-12" style="margin-bottom:4px">
                                    <button type="button" class="btn btn-default btn-sm btn-block" data-rvalue="{instituteContactNo}">Institute contact No</button>                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('page-script')

    CKEDITOR.replace('admissionletter-admission_letter_content', {"height":400,"toolbarGroups":[{"name":"document","groups":["mode","document","doctools"]},{"name":"clipboard","groups":["clipboard","undo"]},{"name":"editing","groups":["find","selection","spellchecker"]},{"name":"forms"},"/",{"name":"basicstyles","groups":["basicstyles","colors","cleanup"]},{"name":"paragraph","groups":["list","indent","blocks","align","bidi"]},{"name":"links"},{"name":"insert"},"/",{"name":"styles"},{"name":"blocks"},{"name":"colors"},{"name":"tools"},{"name":"others"}]});
        dosamigos.ckEditorWidget.registerOnChangeHandler('admissionletter-admission_letter_content');
        jQuery('#admission-letter-form').yiiActiveForm([], []);
        $("[data-rvalue]").click(function() {
        CKEDITOR.instances['admissionletter-admission_letter_content'].insertText($(this).data('rvalue'));
        });
        $('#adm-content').slimScroll({
        height: '665px'
    });



        alert("100");



@endsection
