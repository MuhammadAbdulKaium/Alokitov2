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
            <i class="fa fa-th-list"></i> Manage |<small> Assignment </small>        </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Academics</a></li>
                    <li class="active">Assignment</li>
                </ul>
            </section>
            <section class="content">
                <div class="box box-solid">
                    <div>
                        <div class="box-header with-border">
                            <h3 class="box-title">
                <i class="fa fa-search"></i> View Assignment List           </h3>
                            <div class="box-tools">
                                <a class="btn btn-info btn-sm" href="/academics/assignment/create" title="Add Assignment" data-toggle="tooltip"><i class="fa fa-plus-square"></i> <span class="hidden-xs">Add Assignment</span></a> </div>
                        </div>
                    </div>
                    <div class="box-body table-responsive">
                        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                            <div id="w1" class="grid-view">
                                <div class="summary">Showing <b>1-2</b> of <b>2</b> items.</div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><a href="/academics/assignment/index?sort=assignment_name" data-sort="assignment_name">Name</a></th>
                                            <th><a href="/academics/assignment/index?sort=assignment_academic_year_id" data-sort="assignment_academic_year_id">Academic Year</a></th>
                                            <th><a href="/academics/assignment/index?sort=assignment_batch_id" data-sort="assignment_batch_id">Batch</a></th>
                                            <th><a href="/academics/assignment/index?sort=assignment_section_id" data-sort="assignment_section_id">Section</a></th>
                                            <th><a href="/academics/assignment/index?sort=assignment_subject_id" data-sort="assignment_subject_id">Subject</a></th>
                                            <th><a href="/academics/assignment/index?sort=assignment_allocate_date" data-sort="assignment_allocate_date">Allocate Date</a></th>
                                            <th><a href="/academics/assignment/index?sort=assignment_submit_date" data-sort="assignment_submit_date">Due Date</a></th>
                                            <th class="action-column">Download</th>
                                            <th class="action-column">&nbsp;</th>
                                        </tr>
                                        <tr id="w1-filters" class="filters">
                                            <td>&nbsp;</td>
                                            <td>
                                                <input type="text" class="form-control" name="AssignmentSearch[assignment_name]">
                                            </td>
                                            <td>
                                                <select class="form-control" name="AssignmentSearch[assignment_academic_year_id]">
                                                    <option value=""></option>
                                                    <option value="2">xzczxc</option>
                                                    <option value="1">2016-17</option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="AssignmentSearch[assignment_batch_id]">
                                                    <option value=""></option>
                                                </select>
                                            </td>
                                            <td>
                                                <select class="form-control" name="AssignmentSearch[assignment_section_id]">
                                                    <option value=""></option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="AssignmentSearch[assignment_subject_id]">
                                            </td>
                                            <td>
                                                <input type="text" id="assignmentsearch-assignment_allocate_date" class="form-control" name="AssignmentSearch[assignment_allocate_date]">
                                            </td>
                                            <td>
                                                <input type="text" id="assignmentsearch-assignment_submit_date" class="form-control" name="AssignmentSearch[assignment_submit_date]">
                                            </td>
                                            <td>&nbsp;</td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-key="2">
                                            <td>1</td>
                                            <td>Test Assignment</td>
                                            <td>2016-17</td>
                                            <td>Kindergarten1</td>
                                            <td>KG1-Section1</td>
                                            <td>Arts</td>
                                            <td>31/12/2016</td>
                                            <td>31/01/2017</td>
                                            <td class="text-center"><a href="/academics/assignment/assignment-file-download?id=2" title="Download" data-method="post" data-toggle="tooltip"><span class="fa fa-download fa-lg text-green"></span></a></td>
                                            <td><a href="/academics/assignment/view?id=2" title="View" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/academics/assignment/update?id=2" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/academics/assignment/delete?id=2" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="1">
                                            <td>2</td>
                                            <td>Computer Network</td>
                                            <td>2016-17</td>
                                            <td>Kindergarten1</td>
                                            <td>KG1-Section1</td>
                                            <td>English</td>
                                            <td>01/09/2016</td>
                                            <td>30/09/2016</td>
                                            <td class="text-center"><a href="/academics/assignment/assignment-file-download?id=1" title="Download" data-method="post" data-toggle="tooltip"><span class="fa fa-download fa-lg text-green"></span></a></td>
                                            <td><a href="/academics/assignment/view?id=1" title="View" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/academics/assignment/update?id=1" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/academics/assignment/delete?id=1" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
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
<script src="{{ asset('js/jquery-ui.min.js') }}" type="text/javascript"></script>
<script type="text/javascript">jQuery(document).ready(function () {
jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
jQuery('#assignmentsearch-assignment_allocate_date').datepicker({"changeMonth":true,"changeYear":true,"yearRange":"1900:2099","dateFormat":"dd-mm-yy"});
jQuery('#assignmentsearch-assignment_submit_date').datepicker({"changeMonth":true,"changeYear":true,"yearRange":"1900:2099","dateFormat":"dd-mm-yy"});
jQuery('#w1').yiiGridView({"filterUrl":"\/academics\/assignment\/index","filterSelector":"#w1-filters input, #w1-filters select, select[name=\u0027per-page\u0027]"});
jQuery(document).pjax("#p0 a", {"push":true,"replace":false,"timeout":"10000","scrollTo":false,"container":"#p0"});
jQuery(document).on("submit", "#p0 form[data-pjax]", function (event) {jQuery.pjax.submit(event, {"push":true,"replace":false,"timeout":"10000","scrollTo":false,"container":"#p0"});});
});</script>
@stop