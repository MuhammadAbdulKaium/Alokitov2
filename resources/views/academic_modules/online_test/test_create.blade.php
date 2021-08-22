@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/datetimepicker-kv.min.css') }}" rel="stylesheet">
<link href="{{ asset('css/kv-widgets.min.css') }}" rel="stylesheet">
    <script type="text/javascript">
    var datetimepicker_05bf2d9a = {
        "autoclose": true,
        "format": "dd-mm-yyyy hh:ii"
    };
    </script>
@stop


{{-- Content --}}
@section('content')
              <div class="content-wrapper">
            <section class="content-header">
                <h1>
            <i class="fa fa-plus-square"></i> Create |<small>Online Test</small>        </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Academics</a></li>
                    <li class="active">Online Test</li>
                    <li><a href="/onlinetest/online-test-master/index">Manage Online Test</a></li>
                    <li class="active">Create Online Test</li>
                </ul>
            </section>
            <section class="content">
                <div class="box box-solid">
                    <form id="w0" action="/onlinetest/online-test-master/create" method="post">
                        <input type="hidden" name="_csrf" value="WVRrMUkwbF8pFyF2KnYoKigXEkkcCS8qKScGRjF3JW0WJCN4DUgZKg==">
                        <div class="box-body">
                            <p class="text-right">
                                <a class="btn btn-default btn-sm" href="/onlinetest/online-test-master/index"><i class="fa fa-chevron-left"></i> Back</a> </p>
                            <h2 class="page-header edusec-page-header-1 text-blue edusec-border-bottom-primary">
        <i class="fa fa-info-circle"></i> Test Details  </h2>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group field-onlinetestmaster-otm_name required">
                                        <label class="control-label" for="onlinetestmaster-otm_name">Test Name</label>
                                        <input type="text" id="onlinetestmaster-otm_name" class="form-control" name="OnlineTestMaster[otm_name]" maxlength="255" placeholder="Enter Test Name" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group field-onlinetestmaster-otm_intro">
                                        <label class="control-label" for="onlinetestmaster-otm_intro">Description</label>
                                        <textarea id="onlinetestmaster-otm_intro" class="form-control" name="OnlineTestMaster[otm_intro]" rows="2" placeholder="Enter Test Introduction"></textarea>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <h2 class="page-header edusec-page-header-1 text-blue edusec-border-bottom-primary">
        <i class="fa fa-info-circle"></i> Timing & Grade    </h2>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-onlinetestmaster-otm_timeopen required">
                                        <label class="control-label" for="onlinetestmaster-otm_timeopen">Open the test</label>
                                        <div id="onlinetestmaster-otm_timeopen-datetime" class="input-group date"><span class="input-group-addon" title="Select date &amp; time"><span class="glyphicon glyphicon-time"></span></span>
                                            <input type="text" id="onlinetestmaster-otm_timeopen" class="form-control" name="OnlineTestMaster[otm_timeopen]" data-krajee-datetimepicker="datetimepicker_05bf2d9a">
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-onlinetestmaster-otm_timeclose required">
                                        <label class="control-label" for="onlinetestmaster-otm_timeclose">Close the test</label>
                                        <div id="onlinetestmaster-otm_timeclose-datetime" class="input-group date"><span class="input-group-addon" title="Select date &amp; time"><span class="glyphicon glyphicon-time"></span></span>
                                            <input type="text" id="onlinetestmaster-otm_timeclose" class="form-control" name="OnlineTestMaster[otm_timeclose]" data-krajee-datetimepicker="datetimepicker_05bf2d9a">
                                        </div>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-onlinetestmaster-otm_timelimit required">
                                        <label class="control-label" for="onlinetestmaster-otm_timelimit">Time Limit (in minute)</label>
                                        <input type="text" id="onlinetestmaster-otm_timelimit" class="form-control" name="OnlineTestMaster[otm_timelimit]" placeholder="Enter Time Limit Minute....i.e : 30, 120 etc." aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-onlinetestmaster-otm_grading_system required">
                                        <label class="control-label" for="onlinetestmaster-otm_grading_system">Grading System</label>
                                        <select id="onlinetestmaster-otm_grading_system" class="form-control" name="OnlineTestMaster[otm_grading_system]" onchange="$.get( &quot;/onlinetest/dependent/get-grading-system&quot;, { id : $(this).val() }).done(function(data) {
                    $( &quot;#seeHere&quot;).html(data);
                });" aria-required="true">
                                            <option value="">--- Select Grading System ---</option>
                                            <option value="1">Year7-WeekTest</option>
                                            <option value="2">New Grading System</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4" id="seeHere" style="margin-top:24px">
                                </div>
                            </div>
                            <h2 class="page-header edusec-page-header-1 text-blue edusec-border-bottom-primary">
        <i class="fa fa-info-circle"></i> Academic Details  </h2>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-onlinetestmaster-otm_academic_year required">
                                        <label class="control-label" for="onlinetestmaster-otm_academic_year">Academic Year</label>
                                        <select id="onlinetestmaster-otm_academic_year" class="form-control" name="OnlineTestMaster[otm_academic_year]" onchange="$.get( &quot;/dependent/get-academic-courses&quot;, { yid : $(this).val() }).done(function(data) {
                        $( &quot;#onlinetestmaster-otm_course&quot;).html(data);
                });" aria-required="true">
                                            <option value="">--- Select Academic Year ---</option>
                                            <option value="1" selected>2016-17</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-onlinetestmaster-otm_course required">
                                        <label class="control-label" for="onlinetestmaster-otm_course">Course</label>
                                        <select id="onlinetestmaster-otm_course" class="form-control" name="OnlineTestMaster[otm_course]" onchange="$.get( &quot;/dependent/get-academic-section&quot;, { cid : $(this).val(), aid : $(&quot;#onlinetestmaster-otm_academic_year&quot;).val() }).done(function(data) {
                        $( &quot;#onlinetestmaster-otm_section&quot;).html(data);
                });" aria-required="true">
                                            <option value="">--- Select Course ---</option>
                                            <option value="1">Preschool</option>
                                            <option value="2">Primary</option>
                                            <option value="3">Secondary</option>
                                            <option value="4">Computer Fundamentals</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-onlinetestmaster-otm_section required">
                                        <label class="control-label" for="onlinetestmaster-otm_section">Section</label>
                                        <select id="onlinetestmaster-otm_section" class="form-control" name="OnlineTestMaster[otm_section]" aria-required="true">
                                            <option value="">--- Select Section ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <h2 class="page-header edusec-page-header-1 text-blue edusec-border-bottom-primary">
        <i class="fa fa-info-circle"></i> Other Details </h2>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-onlinetestmaster-otm_visible required">
                                        <label class="control-label" for="onlinetestmaster-otm_visible">Visible/Invisible</label>
                                        <select id="onlinetestmaster-otm_visible" class="form-control" name="OnlineTestMaster[otm_visible]" aria-required="true">
                                            <option value="0">Invisible</option>
                                            <option value="1">Visible</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-onlinetestmaster-otm_randomquestions required">
                                        <label class="control-label" for="onlinetestmaster-otm_randomquestions">Random Questions</label>
                                        <select id="onlinetestmaster-otm_randomquestions" class="form-control" name="OnlineTestMaster[otm_randomquestions]" aria-required="true">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-onlinetestmaster-otm_randomanswer required">
                                        <label class="control-label" for="onlinetestmaster-otm_randomanswer">Random Answer</label>
                                        <select id="onlinetestmaster-otm_randomanswer" class="form-control" name="OnlineTestMaster[otm_randomanswer]" aria-required="true">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-onlinetestmaster-otm_quick_result required">
                                        <label class="control-label" for="onlinetestmaster-otm_quick_result">Quick Result Display?</label>
                                        <select id="onlinetestmaster-otm_quick_result" class="form-control" name="OnlineTestMaster[otm_quick_result]" aria-required="true">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- ./ box-body -->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-primary btn-create">Create</button>
                            <button type="reset" class="btn btn-default btn-create">Reset</button>
                        </div>
                        <!-- /.box-footer-->
                    </form>
                </div>
            </section>
        </div>
@stop

{{-- Scripts --}}

@section('scripts')
<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/kv-widgets.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">
         jQuery(document).ready(function() {
            if (jQuery('#onlinetestmaster-otm_timeopen').data('datetimepicker')) {
                jQuery('#onlinetestmaster-otm_timeopen').datetimepicker('destroy');
            }
            jQuery("#onlinetestmaster-otm_timeopen-datetime").datetimepicker(datetimepicker_05bf2d9a);

            if (jQuery('#onlinetestmaster-otm_timeclose').data('datetimepicker')) {
                jQuery('#onlinetestmaster-otm_timeclose').datetimepicker('destroy');
            }
            jQuery("#onlinetestmaster-otm_timeclose-datetime").datetimepicker(datetimepicker_05bf2d9a);

            jQuery('#w0').yiiActiveForm([{
                "id": "onlinetestmaster-otm_name",
                "name": "otm_name",
                "container": ".field-onlinetestmaster-otm_name",
                "input": "#onlinetestmaster-otm_name",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Test Name cannot be blank."
                    });
                    yii.validation.string(value, messages, {
                        "message": "Test Name must be a string.",
                        "max": 255,
                        "tooLong": "Test Name should contain at most 255 characters.",
                        "skipOnEmpty": 1
                    });
                }
            }, {
                "id": "onlinetestmaster-otm_intro",
                "name": "otm_intro",
                "container": ".field-onlinetestmaster-otm_intro",
                "input": "#onlinetestmaster-otm_intro",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.string(value, messages, {
                        "message": "Description must be a string.",
                        "skipOnEmpty": 1
                    });
                }
            }, {
                "id": "onlinetestmaster-otm_timeopen",
                "name": "otm_timeopen",
                "container": ".field-onlinetestmaster-otm_timeopen",
                "input": "#onlinetestmaster-otm_timeopen",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Open the test cannot be blank."
                    });
                }
            }, {
                "id": "onlinetestmaster-otm_timeclose",
                "name": "otm_timeclose",
                "container": ".field-onlinetestmaster-otm_timeclose",
                "input": "#onlinetestmaster-otm_timeclose",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Close the test cannot be blank."
                    });
                }
            }, {
                "id": "onlinetestmaster-otm_timelimit",
                "name": "otm_timelimit",
                "container": ".field-onlinetestmaster-otm_timelimit",
                "input": "#onlinetestmaster-otm_timelimit",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Time Limit cannot be blank."
                    });
                    yii.validation.number(value, messages, {
                        "pattern": /^\s*[+-]?\d+\s*$/,
                        "message": "Time Limit must be an integer.",
                        "skipOnEmpty": 1
                    });
                }
            }, {
                "id": "onlinetestmaster-otm_grading_system",
                "name": "otm_grading_system",
                "container": ".field-onlinetestmaster-otm_grading_system",
                "input": "#onlinetestmaster-otm_grading_system",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Grading System cannot be blank."
                    });
                    yii.validation.number(value, messages, {
                        "pattern": /^\s*[+-]?\d+\s*$/,
                        "message": "Grading System must be an integer.",
                        "skipOnEmpty": 1
                    });
                }
            }, {
                "id": "onlinetestmaster-otm_academic_year",
                "name": "otm_academic_year",
                "container": ".field-onlinetestmaster-otm_academic_year",
                "input": "#onlinetestmaster-otm_academic_year",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Academic Year cannot be blank."
                    });
                    yii.validation.number(value, messages, {
                        "pattern": /^\s*[+-]?\d+\s*$/,
                        "message": "Academic Year must be an integer.",
                        "skipOnEmpty": 1
                    });
                }
            }, {
                "id": "onlinetestmaster-otm_course",
                "name": "otm_course",
                "container": ".field-onlinetestmaster-otm_course",
                "input": "#onlinetestmaster-otm_course",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Course cannot be blank."
                    });
                    yii.validation.number(value, messages, {
                        "pattern": /^\s*[+-]?\d+\s*$/,
                        "message": "Course must be an integer.",
                        "skipOnEmpty": 1
                    });
                }
            }, {
                "id": "onlinetestmaster-otm_section",
                "name": "otm_section",
                "container": ".field-onlinetestmaster-otm_section",
                "input": "#onlinetestmaster-otm_section",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Section cannot be blank."
                    });
                    yii.validation.number(value, messages, {
                        "pattern": /^\s*[+-]?\d+\s*$/,
                        "message": "Section must be an integer.",
                        "skipOnEmpty": 1
                    });
                }
            }, {
                "id": "onlinetestmaster-otm_visible",
                "name": "otm_visible",
                "container": ".field-onlinetestmaster-otm_visible",
                "input": "#onlinetestmaster-otm_visible",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Visible/Invisible cannot be blank."
                    });
                    yii.validation.number(value, messages, {
                        "pattern": /^\s*[+-]?\d+\s*$/,
                        "message": "Visible\/Invisible must be an integer.",
                        "skipOnEmpty": 1
                    });
                }
            }, {
                "id": "onlinetestmaster-otm_randomquestions",
                "name": "otm_randomquestions",
                "container": ".field-onlinetestmaster-otm_randomquestions",
                "input": "#onlinetestmaster-otm_randomquestions",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Random Questions cannot be blank."
                    });
                    yii.validation.number(value, messages, {
                        "pattern": /^\s*[+-]?\d+\s*$/,
                        "message": "Random Questions must be an integer.",
                        "skipOnEmpty": 1
                    });
                }
            }, {
                "id": "onlinetestmaster-otm_randomanswer",
                "name": "otm_randomanswer",
                "container": ".field-onlinetestmaster-otm_randomanswer",
                "input": "#onlinetestmaster-otm_randomanswer",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Random Answer cannot be blank."
                    });
                    yii.validation.number(value, messages, {
                        "pattern": /^\s*[+-]?\d+\s*$/,
                        "message": "Random Answer must be an integer.",
                        "skipOnEmpty": 1
                    });
                }
            }, {
                "id": "onlinetestmaster-otm_quick_result",
                "name": "otm_quick_result",
                "container": ".field-onlinetestmaster-otm_quick_result",
                "input": "#onlinetestmaster-otm_quick_result",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Quick Result Display? cannot be blank."
                    });
                    yii.validation.number(value, messages, {
                        "pattern": /^\s*[+-]?\d+\s*$/,
                        "message": "Quick Result Display? must be an integer.",
                        "skipOnEmpty": 1
                    });
                }
            }], []);

            $(document).ready(function() {
                if ($("#onlinetestmaster-otm_grading_system").val() != 0) {
                    $("#seeHere").show();
                } else {
                    $("#seeHere").hide();
                }
            });
            $("#onlinetestmaster-otm_grading_system").change(function() {
                if ($("#onlinetestmaster-otm_grading_system").val() != 0) {
                    $("#seeHere").show();
                } else {
                    $("#seeHere").hide();
                }
            });
        });
        </script>

@stop