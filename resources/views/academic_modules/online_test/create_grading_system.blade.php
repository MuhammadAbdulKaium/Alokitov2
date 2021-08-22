@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
<link href="{{ asset('css/multiple-input.css') }}" rel="stylesheet">
@stop


{{-- Content --}}
@section('content')
      <div class="content-wrapper">
            <section class="content-header">
                <h1>
            <i class="fa fa-plus-square"></i> Create |<small>Grading System</small>        </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Academics</a></li>
                    <li class="active">Online Test</li>
                    <li><a href="/onlinetest/test-grading-system/index">Grading System</a></li>
                    <li class="active">Create Grading System</li>
                </ul>
            </section>
            <section class="content">
                <div class="box box-solid">
                    <form id="test-grading-system" action="/onlinetest/test-grading-system/create" method="post">
                        <input type="hidden" name="_csrf" value="RG5JUVRla3g0LQMWNyMvDTUtMCkBXCgNNB0kJiwiIkoLHgEYEB0eDQ==">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-testgradingsystem-tgs_name required">
                                        <label class="control-label" for="testgradingsystem-tgs_name">Grading System Name</label>
                                        <input type="text" id="testgradingsystem-tgs_name" class="form-control" name="TestGradingSystem[tgs_name]" maxlength="55" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div id="test-grading-syatem" class="multiple-input">
                                        <table class="multiple-input-list table table-condensed">
                                            <thead>
                                                <tr>
                                                    <th class="list-cell__tgl_name">Grade Name</th>
                                                    <th class="list-cell__tgl_min_marks">Minimum Marks</th>
                                                    <th class="list-cell__tgl_max_marks">Maximum Marks</th>
                                                    <th class="list-cell__tgl_point">Point</th>
                                                    <th class="list-cell__tgl_level">Level</th>
                                                    <th class="list-cell__button"></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="multiple-input-list__item">
                                                    <td class="list-cell__tgl_name">
                                                        <div class="form-group field-testgradinglevel-0-tgl_name">
                                                            <input type="text" id="testgradinglevel-0-tgl_name" class="form-control" name="TestGradingLevel[0][tgl_name]">
                                                            <div class="help-block help-block-error"></div>
                                                        </div>
                                                    </td>
                                                    <td class="list-cell__tgl_min_marks">
                                                        <div class="form-group field-testgradinglevel-0-tgl_min_marks">
                                                            <input type="text" id="testgradinglevel-0-tgl_min_marks" class="form-control" name="TestGradingLevel[0][tgl_min_marks]">
                                                            <div class="help-block help-block-error"></div>
                                                        </div>
                                                    </td>
                                                    <td class="list-cell__tgl_max_marks">
                                                        <div class="form-group field-testgradinglevel-0-tgl_max_marks">
                                                            <input type="text" id="testgradinglevel-0-tgl_max_marks" class="form-control" name="TestGradingLevel[0][tgl_max_marks]">
                                                            <div class="help-block help-block-error"></div>
                                                        </div>
                                                    </td>
                                                    <td class="list-cell__tgl_point">
                                                        <div class="form-group field-testgradinglevel-0-tgl_point">
                                                            <input type="text" id="testgradinglevel-0-tgl_point" class="form-control" name="TestGradingLevel[0][tgl_point]">
                                                            <div class="help-block help-block-error"></div>
                                                        </div>
                                                    </td>
                                                    <td class="list-cell__tgl_level">
                                                        <div class="form-group field-testgradinglevel-0-tgl_level">
                                                            <div class="radio-list">
                                                                <input type="hidden" name="TestGradingLevel[0][tgl_level]" value="">
                                                                <div id="testgradinglevel-0-tgl_level">
                                                                    <div class="radio">
                                                                        <label>
                                                                            <input type="radio" name="TestGradingLevel[0][tgl_level]" value="1" data-id="testgradinglevel-0-tgl_level"> Pass</label>
                                                                    </div>
                                                                    <div class="radio">
                                                                        <label>
                                                                            <input type="radio" name="TestGradingLevel[0][tgl_level]" value="0" data-id="testgradinglevel-0-tgl_level"> Fail</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="help-block help-block-error"></div>
                                                        </div>
                                                    </td>
                                                    <td class="list-cell__button">
                                                        <div class="btn multiple-input-list__btn js-input-plus btn btn-success"><i class="glyphicon glyphicon-plus"></i> Add More</div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--./box-body-->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Create</button> <a class="btn btn-default btn-create" href="/onlinetest/test-grading-system/index">Cancel</a> </div>
                        <!--./box-footer-->
                    </form>
                </div>
            </section>
        </div>
@stop

{{-- Scripts --}}
@section('scripts')
<script src="{{ asset('js/jquery.multipleInput.min.js') }}" type="text/javascript"></script>
   <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#test-grading-syatem').multipleInput({
                "id": "test-grading-syatem",
                "template": "<tr class=\"multiple-input-list__item\"><td class=\"list-cell__tgl_name\"><div class=\"form-group field-testgradinglevel-{multiple_index_test-grading-syatem}-tgl_name\"><input type=\"text\" id=\"testgradinglevel-{multiple_index_test-grading-syatem}-tgl_name\" class=\"form-control\" name=\"TestGradingLevel[{multiple_index_test-grading-syatem}][tgl_name]\">\n<div class=\"help-block help-block-error\"></div></div></td>\n<td class=\"list-cell__tgl_min_marks\"><div class=\"form-group field-testgradinglevel-{multiple_index_test-grading-syatem}-tgl_min_marks\"><input type=\"text\" id=\"testgradinglevel-{multiple_index_test-grading-syatem}-tgl_min_marks\" class=\"form-control\" name=\"TestGradingLevel[{multiple_index_test-grading-syatem}][tgl_min_marks]\">\n<div class=\"help-block help-block-error\"></div></div></td>\n<td class=\"list-cell__tgl_max_marks\"><div class=\"form-group field-testgradinglevel-{multiple_index_test-grading-syatem}-tgl_max_marks\"><input type=\"text\" id=\"testgradinglevel-{multiple_index_test-grading-syatem}-tgl_max_marks\" class=\"form-control\" name=\"TestGradingLevel[{multiple_index_test-grading-syatem}][tgl_max_marks]\">\n<div class=\"help-block help-block-error\"></div></div></td>\n<td class=\"list-cell__tgl_point\"><div class=\"form-group field-testgradinglevel-{multiple_index_test-grading-syatem}-tgl_point\"><input type=\"text\" id=\"testgradinglevel-{multiple_index_test-grading-syatem}-tgl_point\" class=\"form-control\" name=\"TestGradingLevel[{multiple_index_test-grading-syatem}][tgl_point]\">\n<div class=\"help-block help-block-error\"></div></div></td>\n<td class=\"list-cell__tgl_level\"><div class=\"form-group field-testgradinglevel-{multiple_index_test-grading-syatem}-tgl_level\"><div class=\"radio-list\"><input type=\"hidden\" name=\"TestGradingLevel[{multiple_index_test-grading-syatem}][tgl_level]\" value=\"\"><div id=\"testgradinglevel-{multiple_index_test-grading-syatem}-tgl_level\"><div class=\"radio\"><label><input type=\"radio\" name=\"TestGradingLevel[{multiple_index_test-grading-syatem}][tgl_level]\" value=\"1\" data-id=\"testgradinglevel-{multiple_index_test-grading-syatem}-tgl_level\"> Pass</label></div>\n<div class=\"radio\"><label><input type=\"radio\" name=\"TestGradingLevel[{multiple_index_test-grading-syatem}][tgl_level]\" value=\"0\" data-id=\"testgradinglevel-{multiple_index_test-grading-syatem}-tgl_level\"> Fail</label></div></div></div>\n<div class=\"help-block help-block-error\"></div></div></td>\n<td class=\"list-cell__button\"><div class=\"btn multiple-input-list__btn js-input-remove btn btn-danger\"><i class=\"glyphicon glyphicon-remove\"></i> Remove</div></td></tr>",
                "jsTemplates": [],
                "limit": 9223372036854775807,
                "min": 1,
                "attributeOptions": {
                    "enableAjaxValidation": true,
                    "enableClientValidation": false,
                    "validateOnChange": false,
                    "validateOnSubmit": true,
                    "validateOnBlur": false
                },
                "indexPlaceholder": "multiple_index_test-grading-syatem"
            });
            jQuery('#test-grading-system').yiiActiveForm([{
                "id": "testgradingsystem-tgs_name",
                "name": "tgs_name",
                "container": ".field-testgradingsystem-tgs_name",
                "input": "#testgradingsystem-tgs_name",
                "enableAjaxValidation": true,
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Grading System Name cannot be blank."
                    });
                    yii.validation.string(value, messages, {
                        "message": "Grading System Name must be a string.",
                        "max": 55,
                        "tooLong": "Grading System Name should contain at most 55 characters.",
                        "skipOnEmpty": 1
                    });
                }
            }], []);
        });
        </script>
@stop