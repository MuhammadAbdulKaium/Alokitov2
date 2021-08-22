@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
<link href="{{ asset('css/jquery-ui.min.css') }}" rel="stylesheet">
@stop


{{-- Content --}}
@section('content')
         <div class="content-wrapper">
            <section class="content-header">
                <h1>
            <i class="fa fa-plus-square"></i> Create Assignment        </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Academics</a></li>
                    <li><a href="/academics/assignment/index">Assignment</a></li>
                    <li class="active">Create Assignment</li>
                </ul>
            </section>
            <section class="content">
                <div class="box box-solid">
                    <form id="w0" action="/academics/assignment/create" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="_csrf" value="N1AxNER4V3pAElRWLzI6I10aZmUPNj0cUBZuQAUBEDB0PgZDdhAZLA==">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group field-assignment-assignment_name required">
                                        <label class="control-label" for="assignment-assignment_name">Name</label>
                                        <input type="text" id="assignment-assignment_name" class="form-control" name="Assignment[assignment_name]" maxlength="100" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group field-assignment-assignment_desc required">
                                        <label class="control-label" for="assignment-assignment_desc">Description</label>
                                        <textarea id="assignment-assignment_desc" class="form-control" name="Assignment[assignment_desc]" aria-required="true"></textarea>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group field-assignment-assignment_academic_year_id required">
                                        <label class="control-label" for="assignment-assignment_academic_year_id">Academic Year</label>
                                        <select id="assignment-assignment_academic_year_id" class="form-control" name="Assignment[assignment_academic_year_id]" onchange="$.get( &quot;/dependent/get-academic-courses&quot;, { yid : $(this).val() })
                    .done(function(data) {
                        $( &quot;#assignment-assignment_course_id&quot;).html(data);
                });" aria-required="true">
                                            <option value="">--- Select Academic Year ---</option>
                                            <option value="1" selected>2016-17</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group field-assignment-assignment_course_id required">
                                        <label class="control-label" for="assignment-assignment_course_id">Course</label>
                                        <select id="assignment-assignment_course_id" class="form-control" name="Assignment[assignment_course_id]" onchange="
                    $.get( &quot;/dependent/course-batch&quot;, { cid: $(this).val(), yid :  $( &quot;#assignment-assignment_academic_year_id&quot; ).val()} )
                        .done(function( data ) {
                            $( &quot;#assignment-assignment_batch_id&quot; ).html( data );
                        }
                    );
                " aria-required="true">
                                            <option value="">--- Select Course ---</option>
                                            <option value="1">Preschool</option>
                                            <option value="2">Primary</option>
                                            <option value="3">Secondary</option>
                                            <option value="4">Computer Fundamentals</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group field-assignment-assignment_batch_id required">
                                        <label class="control-label" for="assignment-assignment_batch_id">Batch</label>
                                        <select id="assignment-assignment_batch_id" class="form-control" name="Assignment[assignment_batch_id]" onchange="
                    $.getJSON( &quot;/dependent/get-section-subject&quot;, { id: $(this).val() } )
                        .done(function( data ) {
                            $( &quot;#assignment-assignment_section_id&quot; ).html( data.sectionData );
                            $( &quot;#assignment-assignment_subject_id&quot; ).html( data.subjectData );
                        }
                    );
                " aria-required="true">
                                            <option value="">--- Select Batch ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group field-assignment-assignment_section_id required">
                                        <label class="control-label" for="assignment-assignment_section_id">Section</label>
                                        <select id="assignment-assignment_section_id" class="form-control" name="Assignment[assignment_section_id]" aria-required="true">
                                            <option value="">--- Select Section ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group field-assignment-assignment_subject_id required">
                                        <label class="control-label" for="assignment-assignment_subject_id">Subject</label>
                                        <select id="assignment-assignment_subject_id" class="form-control" name="Assignment[assignment_subject_id]" aria-required="true">
                                            <option value="">--- Select Subject ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group field-assignment-assignment_allocate_date required">
                                        <label class="control-label" for="assignment-assignment_allocate_date">Allocate Date</label>
                                        <input type="text" id="assignment-assignment_allocate_date" class="form-control" name="Assignment[assignment_allocate_date]" placeholder="Select Allocation Date">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group field-assignment-assignment_submit_date required">
                                        <label class="control-label" for="assignment-assignment_submit_date">Due Date</label>
                                        <input type="text" id="assignment-assignment_submit_date" class="form-control" name="Assignment[assignment_submit_date]" placeholder="Select Due Date">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group field-assignment-file required">
                                        <label class="control-label" for="assignment-file">Assignment File</label>
                                        <input type="hidden" name="Assignment[file]" value="">
                                        <input type="file" id="assignment-file" name="Assignment[file]" aria-required="true">
                                        <div class="hint-block">NOTE : Upload only jpg, gif, png, pdf, txt, jpeg, doc, docx files and size must be less than 1MB.</div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Create</button> <a class="btn btn-default btn-create" href="/academics/assignment/index">Cancel</a></div>
                    </form>
                </div>
            </section>
        </div>
@stop

{{-- Scripts --}}

@section('scripts')
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">jQuery(document).ready(function () {
jQuery('#assignment-assignment_allocate_date').datepicker({"changeMonth":true,"changeYear":true,"autoSize":true,"onClose":function( selectedDate ) {
                        $( "#assignment-assignment_submit_date" ).datepicker( "option", "minDate", selectedDate );},"dateFormat":"dd-mm-yy"});
jQuery('#assignment-assignment_submit_date').datepicker({"changeMonth":true,"changeYear":true,"autoSize":true,"onClose":function( selectedDate ) {
                        $( "#assignment-assignment_allocate_date" ).datepicker( "option", "maxDate", selectedDate );},"dateFormat":"dd-mm-yy"});
jQuery('#w0').yiiActiveForm([{"id":"assignment-assignment_name","name":"assignment_name","container":".field-assignment-assignment_name","input":"#assignment-assignment_name","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Name cannot be blank."});yii.validation.string(value, messages, {"message":"Name must be a string.","max":100,"tooLong":"Name should contain at most 100 characters.","skipOnEmpty":1});}},{"id":"assignment-assignment_desc","name":"assignment_desc","container":".field-assignment-assignment_desc","input":"#assignment-assignment_desc","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Description cannot be blank."});yii.validation.string(value, messages, {"message":"Description must be a string.","max":255,"tooLong":"Description should contain at most 255 characters.","skipOnEmpty":1});}},{"id":"assignment-assignment_academic_year_id","name":"assignment_academic_year_id","container":".field-assignment-assignment_academic_year_id","input":"#assignment-assignment_academic_year_id","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Academic Year cannot be blank."});yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Academic Year must be an integer.","skipOnEmpty":1});}},{"id":"assignment-assignment_course_id","name":"assignment_course_id","container":".field-assignment-assignment_course_id","input":"#assignment-assignment_course_id","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Course cannot be blank."});yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Course must be an integer.","skipOnEmpty":1});}},{"id":"assignment-assignment_batch_id","name":"assignment_batch_id","container":".field-assignment-assignment_batch_id","input":"#assignment-assignment_batch_id","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Batch cannot be blank."});yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Batch must be an integer.","skipOnEmpty":1});}},{"id":"assignment-assignment_section_id","name":"assignment_section_id","container":".field-assignment-assignment_section_id","input":"#assignment-assignment_section_id","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Section cannot be blank."});yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Section must be an integer.","skipOnEmpty":1});}},{"id":"assignment-assignment_subject_id","name":"assignment_subject_id","container":".field-assignment-assignment_subject_id","input":"#assignment-assignment_subject_id","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Subject cannot be blank."});yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Subject must be an integer.","skipOnEmpty":1});}},{"id":"assignment-assignment_allocate_date","name":"assignment_allocate_date","container":".field-assignment-assignment_allocate_date","input":"#assignment-assignment_allocate_date","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Allocate Date cannot be blank."});}},{"id":"assignment-assignment_submit_date","name":"assignment_submit_date","container":".field-assignment-assignment_submit_date","input":"#assignment-assignment_submit_date","validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Due Date cannot be blank."});}},{"id":"assignment-file","name":"file","container":".field-assignment-file","input":"#assignment-file","validate":function (attribute, value, messages, deferred, $form) {yii.validation.file(attribute, messages, {"message":"File upload failed.","skipOnEmpty":true,"mimeTypes":[],"wrongMimeType":"Only files with these MIME types are allowed: .","extensions":["jpg","gif","png","pdf","txt","jpeg","doc","docx"],"wrongExtension":"Only files with these extensions are allowed: jpg, gif, png, pdf, txt, jpeg, doc, docx.","maxSize":1048576,"tooBig":"The file \"{file}\" is too big. Its size cannot exceed 1 MiB.","maxFiles":1,"tooMany":"You can upload at most 1 file."});yii.validation.required(value, messages, {"message":"File cannot be blank."});}}], []);
});</script> 
@stop