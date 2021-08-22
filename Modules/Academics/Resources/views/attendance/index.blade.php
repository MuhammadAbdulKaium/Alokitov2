@extends('layouts.master')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage <small>Student Attendance</small>        </h1>
            <ul class="breadcrumb">
                <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/academics/default/index">Academics</a></li>
                <li class="active">Student Attendance</li>
            </ul>
        </section>
        <section class="content">
            <div class="box box-solid">
                <form id="w1" action="/attendance/stu-attendance/index" method="get">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-filter"></i> Filter Criteria</h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group field-stuattendancesearch-sa_batch_id required">
                                    <label class="control-label" for="stuattendancesearch-sa_batch_id">Batch</label>
                                    <select id="stuattendancesearch-sa_batch_id" class="form-control" name="StuAttendanceSearch[sa_batch_id]" onchange="" aria-required="true">
                                        <option value="">--- Select Batch ---</option>
                                        <optgroup label="Preschool">
                                            <option value="1" selected>Kindergarten1</option>
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
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group field-stuattendancesearch-sa_section_id required">
                                    <label class="control-label" for="stuattendancesearch-sa_section_id">Section</label>
                                    <select id="stuattendancesearch-sa_section_id" class="form-control" name="StuAttendanceSearch[sa_section_id]" onchange="$.get( '/dependent/get-batch-sub', { id : $('#stuattendancesearch-sa_batch_id').val() })
                    .done(function(data) {
                        $('#stuattendancesearch-sa_ba_sub_id').html(data);
                });" aria-required="true">
                                        <option value="">--- Select Section ---</option>
                                        <option value="1" selected>KG1-Section1</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group field-stuattendancesearch-sa_ba_sub_id">
                                    <label class="control-label" for="stuattendancesearch-sa_ba_sub_id">Subject</label>
                                    <select id="stuattendancesearch-sa_ba_sub_id" class="form-control" name="StuAttendanceSearch[sa_ba_sub_id]">
                                        <option value="">--- Select Subject ---</option>
                                        <option value="1" selected>English</option>
                                        <option value="2">Science</option>
                                        <option value="3">Social Studies</option>
                                        <option value="4">Mathematics</option>
                                        <option value="5">Arts</option>
                                        <option value="6">Language &amp; Litereacy</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group field-stuattendancesearch-sa_date">
                                    <label class="control-label" for="stuattendancesearch-sa_date">Date</label>
                                    <input type="text" id="stuattendancesearch-sa_date" class="form-control" name="StuAttendanceSearch[sa_date]" value="15-02-2017" readOnly>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ./box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info">Search</button> <a class="btn btn-default" href="/attendance/stu-attendance/index">Reset</a></div>
                </form>
            </div>
            <div class="box box-solid">
                <div class="extra-div">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-bars"></i> Manage Student Attendance</h3>
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                        <div id="w2" class="grid-view">
                            <table class="table table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Student</th>
                                    <th>Employee</th>
                                    <th>Subject</th>
                                    <th>Lecture</th>
                                    <th>Attendance</th>
                                    <th>Absent Remark</th>
                                    <th class="action-column">&nbsp;</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td colspan="9">
                                        <div class="empty">No results found.</div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div id="slideToTop"><i class="fa fa-chevron-up"></i></div>
    </div>
@stop
