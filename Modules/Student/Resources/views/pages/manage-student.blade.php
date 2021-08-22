@extends('layouts.master')

<!-- page content -->
@section('content')

{{--batch string--}}
@php $batchString="Class"; @endphp

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-th-list"></i> Manage | <small>Cadet</small>        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/student/default/index">Cadet</a></li>
                <li class="active">Manage Cadet</li>
            </ul>    </section>
        <section class="content">

            <div class="box box-solid">
                <div class="et">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> Search Cadet</h3>
                        <div class="box-tools">
                            <a class="btn btn-success btn-sm" href="/student/stu-master/create"><i class="fa fa-plus-square"></i> Add</a>				</div>
                    </div>
                </div>

                <form id="w0" action="{{url("/student/create")}}" method="post"><div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group field-stuecdetailsearch-secd_academic_year">
                                    <label class="control-label" for="stuecdetailsearch-secd_academic_year">Academic Year</label>
                                    <select id="academic_year" class="form-control" name="academic_year" onchange="">
                                        <option value="">--- Select Academic Year ---</option>

                                    </select>

                                    <div class="help-block"></div>
                                </div>		</div>
                            <div class="col-sm-4">
                                <div class="form-group field-stuecdetailsearch-secdsec-sec_course">
                                    <label class="control-label" for="stuecdetailsearch-secdsec-sec_course">Level</label>
                                    <select id="academic_level" class="form-control" name="academic_level" onchange="">
                                        <option value="">--- Select Level ---</option>

                                    </select>

                                    <div class="help-block"></div>
                                </div>		</div>
                            <div class="col-sm-4">
                                <div class="form-group field-stuecdetailsearch-secd_batch">
                                    <label class="control-label" for="stuecdetailsearch-secd_batch">{{$batchString}}</label>
                                    <select id="academic_class" class="form-control" name="academic_class" onchange="">
                                        <option value="">--- Select {{$batchString}} ---</option>
                                    </select>

                                    <div class="help-block"></div>
                                </div>		</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group field-stuecdetailsearch-secd_section">
                                    <label class="control-label" for="stuecdetailsearch-secd_section">Section</label>
                                    <select id="academic_section" class="form-control" name="academic_section">
                                        <option value="">--- Select Section ---</option>
                                    </select>

                                    <div class="help-block"></div>
                                </div>		</div>
                            <div class="col-sm-4" style="margin-top: 25px;">
                                <div class="form-group field-stuecdetailsearch-searchgrid">

                                    <input type="text" id="stu_detail_search" class="form-control" name="stu_detail_search" placeHolder="Enter Gr.No or Cadet ID">

                                    <div class="help-block"></div>
                                </div>        </div>
                            <div class="col-sm-4" style="margin-top: 25px;">
                                <div class="form-group field-stuecdetailsearch-searchinput">

                                    <input type="text" id="student_name" class="form-control" name="student_name" placeHolder="Enter Cadet First/Last Name or Email Id.">

                                    <div class="help-block"></div>
                                </div>        </div>
                        </div>
                    </div><!-- ./box-body -->
                    <div class="box-footer">
                        <button type="submit" class="btn btn-info">Search</button>    <a class="btn btn-default" href="/student/stu-master/index">Reset</a></div>
                </form></div>

            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-replace-state data-pjax-timeout="10000">	</div>    </section>
    </div>

@endsection

@section('scripts')
@endsection
