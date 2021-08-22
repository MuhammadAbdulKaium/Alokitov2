@extends('layouts.master')

{{-- Web site Title --}}

<!-- @section('styles')
<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">
@stop -->


{{-- Content --}}
@section('content')
               <div class="content-wrapper">
            <section class="content-header">
                <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Online Test</small>        </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Academics</a></li>
                    <li class="active">Online Test</li>
                    <li class="active">Manage Online Test</li>
                </ul>
            </section>
            <section class="content">
                <div class="box box-solid">
                    <div>
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>
                            <div class="box-tools">
                                <a class="btn btn-success btn-sm" href="/onlinetest/online-test-master/create"><i class="fa fa-plus-square"></i> Add New Test</a> </div>
                        </div>
                    </div>
                    <div class="box-header with-border">
                        <h3 class="box-title"><span class="label label-warning"> Note: You have to first configure test and then visible test.</span></h3>
                    </div>
                    <div class="box-body table-responsive">
                        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                            <div id="w2" class="grid-view">
                                <div class="summary">Showing <b>1-1</b> of <b>1</b> item.</div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><a href="/onlinetest/online-test-master/index?sort=otm_name" data-sort="otm_name">Test Name</a></th>
                                            <th><a href="/onlinetest/online-test-master/index?sort=otm_academic_year" data-sort="otm_academic_year">Academic Year</a></th>
                                            <th><a href="/onlinetest/online-test-master/index?sort=otm_course" data-sort="otm_course">Course</a></th>
                                            <th><a href="/onlinetest/online-test-master/index?sort=otm_batch" data-sort="otm_batch">Batch</a></th>
                                            <th><a href="/onlinetest/online-test-master/index?sort=otm_section" data-sort="otm_section">Section</a></th>
                                            <th><a href="/onlinetest/online-test-master/index?sort=otm_timeopen" data-sort="otm_timeopen">Open the test</a></th>
                                            <th><a href="/onlinetest/online-test-master/index?sort=otm_timeclose" data-sort="otm_timeclose">Close the test</a></th>
                                            <th><a href="/onlinetest/online-test-master/index?sort=otm_timelimit" data-sort="otm_timelimit">Time Limit</a></th>
                                            <th><a href="/onlinetest/online-test-master/index?sort=otm_grading_system" data-sort="otm_grading_system">Grading System</a></th>
                                            <th><a href="/onlinetest/online-test-master/index?sort=otm_summarks" data-sort="otm_summarks">Total Marks</a></th>
                                            <th><a href="/onlinetest/online-test-master/index?sort=otm_visible" data-sort="otm_visible">Visible/Invisible</a></th>
                                            <th class="action-column">Question</th>
                                            <th class="action-column">&nbsp;</th>
                                        </tr>
                                        <tr id="w2-filters" class="filters">
                                            <td>&nbsp;</td>
                                            <td>
                                                <input type="text" class="form-control" name="OnlineTestMasterSearch[otm_name]">
                                            </td>
                                            <td>
                                                <select class="form-control" name="OnlineTestMasterSearch[otm_academic_year]">
                                                    <option value=""></option>
                                                    <option value="1">2016-17</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="OnlineTestMasterSearch[otm_course]">
                                                    <option value=""></option>
                                                    <option value="1">Preschool</option>
                                                    <option value="2">Primary</option>
                                                    <option value="3">Secondary</option>
                                                    <option value="4">Computer Fundamentals</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="OnlineTestMasterSearch[otm_batch]">
                                                    <option value=""></option>
                                                    <optgroup label="Preschool">
                                                        <option value="1">Kindergarten1</option>
                                                        <option value="2">Kindergarten2</option>
                                                    </optgroup>
                                                    <optgroup label="Primary">
                                                        <option value="3">Grade1</option>
                                                        <option value="4">Grade2</option>
                                                        <option value="5">Grade3</option>
                                                        <option value="6">Grade4</option>
                                                        <option value="7">Grade5</option>
                                                        <option value="8">Grade6</option>
                                                    </optgroup>
                                                    <optgroup label="Secondary">
                                                        <option value="9">Year7</option>
                                                        <option value="10">Year8</option>
                                                        <option value="11">Year9</option>
                                                        <option value="12">Year10</option>
                                                        <option value="13">Year11</option>
                                                        <option value="14">Year12</option>
                                                    </optgroup>
                                                    <optgroup label="Computer Fundamentals">
                                                        <option value="15">CFBatch1</option>
                                                    </optgroup>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="OnlineTestMasterSearch[otm_section]">
                                                    <option value=""></option>
                                                    <optgroup label="Kindergarten1">
                                                        <option value="1">KG1-Section1</option>
                                                    </optgroup>
                                                    <optgroup label="Kindergarten2">
                                                        <option value="2">KG2-Section1</option>
                                                    </optgroup>
                                                    <optgroup label="Grade1">
                                                        <option value="3">Grade1-Sec1</option>
                                                    </optgroup>
                                                    <optgroup label="Grade2">
                                                        <option value="4">Grade2-Sec1</option>
                                                    </optgroup>
                                                    <optgroup label="Grade3">
                                                        <option value="5">Grade3-Sec1</option>
                                                    </optgroup>
                                                    <optgroup label="Grade4">
                                                        <option value="6">Grade4-Sec1</option>
                                                    </optgroup>
                                                    <optgroup label="Grade5">
                                                        <option value="7">Grade5-Sec1</option>
                                                    </optgroup>
                                                    <optgroup label="Grade6">
                                                        <option value="8">Grade6-Sec1</option>
                                                    </optgroup>
                                                    <optgroup label="Year7">
                                                        <option value="9">Year7-Sec1</option>
                                                    </optgroup>
                                                    <optgroup label="Year8">
                                                        <option value="10">Year8-Sec1</option>
                                                    </optgroup>
                                                    <optgroup label="Year9">
                                                        <option value="11">Year-Sec1</option>
                                                    </optgroup>
                                                    <optgroup label="Year10">
                                                        <option value="12">Year10-Sec1</option>
                                                    </optgroup>
                                                    <optgroup label="Year11">
                                                        <option value="13">Year11-Sec1</option>
                                                    </optgroup>
                                                    <optgroup label="Year12">
                                                        <option value="14">Year12-Sec1</option>
                                                    </optgroup>
                                                    <optgroup label="CFBatch1">
                                                        <option value="15">CFBatch1-Sec1</option>
                                                    </optgroup>
                                                </select>
                                            </td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                            <td>
                                                <select class="form-control" name="OnlineTestMasterSearch[otm_visible]">
                                                    <option value=""></option>
                                                    <option value="0">Invisible</option>
                                                    <option value="1">Visible</option>
                                                </select>
                                            </td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-key="1">
                                            <td>1</td>
                                            <td>Year7WeekTestEnglish</td>
                                            <td>2016-17</td>
                                            <td>Secondary</td>
                                            <td>Year7</td>
                                            <td>Year7-Sec1</td>
                                            <td>Sep 21, 2016, 6:00:00 PM</td>
                                            <td>Sep 21, 2016, 6:30:00 PM</td>
                                            <td>30 Minute</td>
                                            <td><a href="/onlinetest/test-grading-system/view?id=1" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" data-pjax>Year7-WeekTest</a></td>
                                            <td>10</td>
                                            <td class="text-center"><a href="/onlinetest/online-test-master/change-visible-status?id=1" title="Invisible"><span class='label label-success'> Visible </span></a></td>
                                            <td class="text-center"><a href="/onlinetest/online-test-master/add-questions?testId=1" title="Add Question" data-pjax><span class="fa fa-plus-circle fa-lg text-primary"></span></a> <a href="/onlinetest/test-question-instances/remove-questions?testId=1" title="View/Remove Question" data-pjax><span class="fa fa-eye-slash fa-lg text-primary"></span></a></td>
                                            <td><a href="/onlinetest/online-test-master/view?id=1" title="View"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/online-test-master/update?id=1" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/online-test-master/delete?id=1" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
@stop

{{-- Scripts --}}

@section('scripts')
<!-- <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script> -->
<script type="text/javascript">
jQuery(document).ready(function () {
jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
jQuery('#w2').yiiGridView({"filterUrl":"\/onlinetest\/online-test-master\/index","filterSelector":"#w2-filters input, #w2-filters select, select[name=\u0027per-page\u0027]"});
jQuery(document).pjax("#p0 a", {"push":true,"replace":false,"timeout":"10000","scrollTo":false,"container":"#p0"});
jQuery(document).on("submit", "#p0 form[data-pjax]", function (event) {jQuery.pjax.submit(event, {"push":true,"replace":false,"timeout":"10000","scrollTo":false,"container":"#p0"});});
});
        </script>

@stop