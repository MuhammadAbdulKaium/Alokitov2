@extends('layouts.master')

{{-- Web site Title --}}

<!-- @section('styles')
<link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet">
@stop -->


{{-- Content --}}
@section('content')
       <div class="content-wrapper">
            <section class="content-header">
                <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Question Category</small>        </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Academics</a></li>
                    <li class="active">Online Test</li>
                    <li class="active">Question Category</li>
                </ul>
            </section>
            <section class="content">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Question Category </h3>
                    </div>
                    <form id="question-category" action="/onlinetest/question-category/create" method="post">
                        <input type="hidden" name="_csrf" value="dklLdEhOd28GCgEzKwgzGgcKMgwddzQaBjomAzAJPl05OQM9DDYCGg==">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group field-questioncategory-qc_name required">
                                        <label class="control-label" for="questioncategory-qc_name">Question Category</label>
                                        <input type="text" id="questioncategory-qc_name" class="form-control" name="QuestionCategory[qc_name]" maxlength="255" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group field-questioncategory-qc_course_id">
                                        <label class="control-label" for="questioncategory-qc_course_id">Course</label>
                                        <select id="questioncategory-qc_course_id" class="form-control" name="QuestionCategory[qc_course_id]">
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
                                <div class="col-sm-12">
                                    <div class="form-group field-questioncategory-qc_intro">
                                        <label class="control-label" for="questioncategory-qc_intro">Description</label>
                                        <textarea id="questioncategory-qc_intro" class="form-control" name="QuestionCategory[qc_intro]" rows="3"></textarea>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Create</button>
                            <button type="reset" class="btn btn-default btn-create">Reset</button>
                        </div>
                        <!-- /.box-footer-->
                    </form>
                </div>
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> View Question Category List</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                            <div id="w2" class="grid-view">
                                <div class="summary">Showing <b>1-2</b> of <b>2</b> items.</div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><a href="/onlinetest/question-category/index?sort=qc_name" data-sort="qc_name">Question Category</a></th>
                                            <th><a href="/onlinetest/question-category/index?sort=qc_course_id" data-sort="qc_course_id">Course</a></th>
                                            <th><a href="/onlinetest/question-category/index?sort=qc_intro" data-sort="qc_intro">Description</a></th>
                                            <th class="action-column">&nbsp;</th>
                                        </tr>
                                        <tr id="w2-filters" class="filters">
                                            <td>&nbsp;</td>
                                            <td>
                                                <input type="text" class="form-control" name="QuestionCategorySearch[qc_name]">
                                            </td>
                                            <td>
                                                <select class="form-control" name="QuestionCategorySearch[qc_course_id]">
                                                    <option value=""></option>
                                                    <option value="1">Preschool</option>
                                                    <option value="2">Primary</option>
                                                    <option value="3">Secondary</option>
                                                    <option value="4">Computer Fundamentals</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="QuestionCategorySearch[qc_intro]">
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-key="2">
                                            <td>1</td>
                                            <td>True-False</td>
                                            <td>Secondary</td>
                                            <td></td>
                                            <td><a href="/onlinetest/question-category/view?id=2" data-target="#globalModal" data-toggle="modal" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-category/update?id=2" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-category/delete?id=2" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="1">
                                            <td>2</td>
                                            <td>Multiple Choice</td>
                                            <td>Secondary</td>
                                            <td></td>
                                            <td><a href="/onlinetest/question-category/view?id=1" data-target="#globalModal" data-toggle="modal" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-category/update?id=1" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-category/delete?id=1" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box-->
            </section>
        </div>
@stop

{{-- Scripts --}}

@section('scripts')
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('#question-category').yiiActiveForm([{
        "id": "questioncategory-qc_name",
        "name": "qc_name",
        "container": ".field-questioncategory-qc_name",
        "input": "#questioncategory-qc_name",
        "validate": function(attribute, value, messages, deferred, $form) {
            yii.validation.required(value, messages, {
                "message": "Question Category cannot be blank."
            });
            yii.validation.string(value, messages, {
                "message": "Question Category must be a string.",
                "max": 255,
                "tooLong": "Question Category should contain at most 255 characters.",
                "skipOnEmpty": 1
            });
        }
    }, {
        "id": "questioncategory-qc_course_id",
        "name": "qc_course_id",
        "container": ".field-questioncategory-qc_course_id",
        "input": "#questioncategory-qc_course_id",
        "validate": function(attribute, value, messages, deferred, $form) {
            yii.validation.number(value, messages, {
                "pattern": /^\s*[+-]?\d+\s*$/,
                "message": "Course must be an integer.",
                "skipOnEmpty": 1
            });
        }
    }, {
        "id": "questioncategory-qc_intro",
        "name": "qc_intro",
        "container": ".field-questioncategory-qc_intro",
        "input": "#questioncategory-qc_intro",
        "validate": function(attribute, value, messages, deferred, $form) {
            yii.validation.string(value, messages, {
                "message": "Description must be a string.",
                "skipOnEmpty": 1
            });
        }
    }], []);
    jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
        $(this).slideUp('slow', function() {
            $(this).remove();
        });
    });
    jQuery('#w2').yiiGridView({
        "filterUrl": "\/onlinetest\/question-category\/index",
        "filterSelector": "#w2-filters input, #w2-filters select, select[name=\u0027per-page\u0027]"
    });
    jQuery(document).pjax("#p0 a", {
        "push": true,
        "replace": false,
        "timeout": "10000",
        "scrollTo": false,
        "container": "#p0"
    });
    jQuery(document).on("submit", "#p0 form[data-pjax]", function(event) {
        jQuery.pjax.submit(event, {
            "push": true,
            "replace": false,
            "timeout": "10000",
            "scrollTo": false,
            "container": "#p0"
        });
    });
});
</script>

@stop