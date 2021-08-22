@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
<link href="{{ asset('css/fullcalendar.min.css') }}" rel="stylesheet">
@stop


{{-- Content --}}
@section('content')
        <div class="content-wrapper">
            <section class="content-header">
                <h1>
            <i class="fa fa-th-list"></i> Manage |<small>Questions</small>        </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Academics</a></li>
                    <li class="active">Online Test</li>
                    <li class="active">Questions</li>
                </ul>
            </section>
            <section class="content">
                <div class="box box-solid">
                    <div>
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>
                            <div class="box-tools">
                                <a class="btn btn-success btn-sm" href="/onlinetest/question-master/select-question-type" data-target="#globalModal" data-toggle="modal"><i class="fa fa-plus-square"></i> Add Question</a> </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                            <div id="w1" class="grid-view">
                                <div class="summary">Showing <b>1-12</b> of <b>12</b> items.</div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><a href="/onlinetest/question-master/index?sort=qm_category_id" data-sort="qm_category_id">Question Category</a></th>
                                            <th><a href="/onlinetest/question-master/index?sort=qm_text" data-sort="qm_text">Question</a></th>
                                            <th><a href="/onlinetest/question-master/index?sort=qm_default_mark" data-sort="qm_default_mark">Default Mark</a></th>
                                            <th><a href="/onlinetest/question-master/index?sort=qm_type" data-sort="qm_type">Question Type</a></th>
                                            <th class="action-column">&nbsp;</th>
                                        </tr>
                                        <tr id="w1-filters" class="filters">
                                            <td>&nbsp;</td>
                                            <td>
                                                <select class="form-control" name="QuestionMasterSearch[qm_category_id]">
                                                    <option value=""></option>
                                                    <optgroup label="Secondary">
                                                        <option value="1">Multiple Choice</option>
                                                        <option value="2">True-False</option>
                                                    </optgroup>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="QuestionMasterSearch[qm_text]">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="QuestionMasterSearch[qm_default_mark]">
                                            </td>
                                            <td>
                                                <select class="form-control" name="QuestionMasterSearch[qm_type]">
                                                    <option value=""></option>
                                                    <option value="1">Multichoice (MCQ)</option>
                                                    <option value="2">True/False</option>
                                                </select>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-key="12">
                                            <td>1</td>
                                            <td>Multiple Choice</td>
                                            <td>
                                                <p>This is true orn not?</p>
                                            </td>
                                            <td>1</td>
                                            <td>True/False</td>
                                            <td><a href="/onlinetest/question-master/view?id=12" title="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-master/update?id=12" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-master/delete?id=12" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="11">
                                            <td>2</td>
                                            <td>Multiple Choice</td>
                                            <td>
                                                <p>Test for question insert ?</p>
                                            </td>
                                            <td>1</td>
                                            <td>Multichoice (MCQ)</td>
                                            <td><a href="/onlinetest/question-master/view?id=11" title="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-master/update?id=11" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-master/delete?id=11" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="10">
                                            <td>3</td>
                                            <td>Multiple Choice</td>
                                            <td>I ..... tennis every Sunday morning.</td>
                                            <td>1</td>
                                            <td>Multichoice (MCQ)</td>
                                            <td><a href="/onlinetest/question-master/view?id=10" title="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-master/update?id=10" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-master/delete?id=10" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="9">
                                            <td>4</td>
                                            <td>Multiple Choice</td>
                                            <td>Jane: "What ..... ?" Mary: "I'm trying to fix my calculator."</td>
                                            <td>1</td>
                                            <td>Multichoice (MCQ)</td>
                                            <td><a href="/onlinetest/question-master/view?id=9" title="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-master/update?id=9" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-master/delete?id=9" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="8">
                                            <td>5</td>
                                            <td>Multiple Choice</td>
                                            <td>Jane: "What ..... in the evenings?" Mary: "Usually I watch TV or read a book."</td>
                                            <td>1</td>
                                            <td>Multichoice (MCQ)</td>
                                            <td><a href="/onlinetest/question-master/view?id=8" title="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-master/update?id=8" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-master/delete?id=8" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="7">
                                            <td>6</td>
                                            <td>Multiple Choice</td>
                                            <td>Babies ..... when they are hungry.</td>
                                            <td>1</td>
                                            <td>Multichoice (MCQ)</td>
                                            <td><a href="/onlinetest/question-master/view?id=7" title="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-master/update?id=7" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-master/delete?id=7" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="6">
                                            <td>7</td>
                                            <td>Multiple Choice</td>
                                            <td>Weather report: "It's seven o'clock in Frankfurt and ..... ."</td>
                                            <td>1</td>
                                            <td>Multichoice (MCQ)</td>
                                            <td><a href="/onlinetest/question-master/view?id=6" title="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-master/update?id=6" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-master/delete?id=6" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="5">
                                            <td>8</td>
                                            <td>Multiple Choice</td>
                                            <td>How many students in your class ..... from Korea?</td>
                                            <td>1</td>
                                            <td>Multichoice (MCQ)</td>
                                            <td><a href="/onlinetest/question-master/view?id=5" title="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-master/update?id=5" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-master/delete?id=5" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="4">
                                            <td>9</td>
                                            <td>Multiple Choice</td>
                                            <td>..... many times every winter in Frankfurt.</td>
                                            <td>1</td>
                                            <td>Multichoice (MCQ)</td>
                                            <td><a href="/onlinetest/question-master/view?id=4" title="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-master/update?id=4" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-master/delete?id=4" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="3">
                                            <td>10</td>
                                            <td>Multiple Choice</td>
                                            <td>Sorry, she can't come to the phone. She ..... a bath!</td>
                                            <td>1</td>
                                            <td>Multichoice (MCQ)</td>
                                            <td><a href="/onlinetest/question-master/view?id=3" title="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-master/update?id=3" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-master/delete?id=3" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="2">
                                            <td>11</td>
                                            <td>Multiple Choice</td>
                                            <td>Jun-Sik ..... his teeth before breakfast every morning.</td>
                                            <td>1</td>
                                            <td>Multichoice (MCQ)</td>
                                            <td><a href="/onlinetest/question-master/view?id=2" title="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-master/update?id=2" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-master/delete?id=2" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="1">
                                            <td>12</td>
                                            <td>Multiple Choice</td>
                                            <td>Don't make so much noise. Noriko ..... to study for her ESL test!</td>
                                            <td>1</td>
                                            <td>Multichoice (MCQ)</td>
                                            <td><a href="/onlinetest/question-master/view?id=1" title="View" data-pjax="0"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/question-master/update?id=1" title="Update"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/question-master/delete?id=1" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
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
 <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
                $(this).slideUp('slow', function() {
                    $(this).remove();
                });
            });
            jQuery('#w1').yiiGridView({
                "filterUrl": "\/onlinetest\/question-master\/index",
                "filterSelector": "#w1-filters input, #w1-filters select, select[name=\u0027per-page\u0027]"
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