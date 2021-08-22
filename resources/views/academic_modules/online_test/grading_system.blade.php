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
            <i class="fa fa-th-list"></i> Manage |<small>Grading System</small>        </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Academics</a></li>
                    <li class="active">Online Test</li>
                    <li class="active">Grading System</li>
                </ul>
            </section>
            <section class="content">
                <div class="box box-solid">
                    <div>
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>
                            <div class="box-tools">
                                <a class="btn btn-success btn-sm" href="/onlinetest/test-grading-system/create"><i class="fa fa-plus-square"></i> Create New</a> </div>
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
                                            <th><a href="/onlinetest/test-grading-system/index?sort=tgs_name" data-sort="tgs_name">Grading System Name</a></th>
                                            <th><a href="/onlinetest/test-grading-system/index?sort=tgs_status" data-sort="tgs_status">Status</a></th>
                                            <th class="action-column">&nbsp;</th>
                                        </tr>
                                        <tr id="w1-filters" class="filters">
                                            <td>&nbsp;</td>
                                            <td>
                                                <input type="text" class="form-control" name="TestGradingSystemSearch[tgs_name]">
                                            </td>
                                            <td>
                                                <select class="form-control" name="TestGradingSystemSearch[tgs_status]">
                                                    <option value=""></option>
                                                    <option value="0">Active</option>
                                                    <option value="1">Inactive</option>
                                                </select>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-key="2">
                                            <td>1</td>
                                            <td>New Grading System</td>
                                            <td class="text-center"><a class="toggle-column" href="/onlinetest/test-grading-system/toggle?id=2" title="Inactive" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-ok-circle fa-lg"></span></a></td>
                                            <td><a href="/onlinetest/test-grading-system/view?id=2" title="View Grading System" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/test-grading-system/update?id=2" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/test-grading-system/delete?id=2" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="1">
                                            <td>2</td>
                                            <td>Year7-WeekTest</td>
                                            <td class="text-center"><a class="toggle-column" href="/onlinetest/test-grading-system/toggle?id=1" title="Inactive" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-ok-circle fa-lg"></span></a></td>
                                            <td><a href="/onlinetest/test-grading-system/view?id=1" title="View Grading System" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><span class="glyphicon glyphicon-eye-open"></span></a> <a href="/onlinetest/test-grading-system/update?id=1" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/onlinetest/test-grading-system/delete?id=1" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </section>
            </div>
            <div id="slideToTop"><i class="fa fa-chevron-up"></i></div>
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
            $("a.toggle-column").on("click", function(e) {
                e.preventDefault();
                prompt = confirm("Are you sure you want to change status of this item?");
                if (prompt == false) {
                    return false;
                }
                $.post($(this).attr("href"), function(data) {
                    var pjaxId = $(e.target).closest(".grid-view").parent().attr("id");
                    $.pjax.reload({
                        container: "#" + pjaxId
                    });
                });
                return false;
            });
            jQuery('#w1').yiiGridView({
                "filterUrl": "\/onlinetest\/test-grading-system\/index",
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