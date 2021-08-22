@extends('layouts.master')
<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-users" aria-hidden="true"></i> Manage Enquiry        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/admission/default/index">Enquiry</a></li>
                <li class="active">Manage Enquiry</li>
            </ul>    </section>
        <section class="content">
            <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-replace-state data-pjax-timeout="10000">
                <form id="w1" action="/admission/stu-admission-master/index" method="get">
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class = "fa fa-filter" aria-hidden="true"></i> Filter Options        </h3>
                        </div>
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-3">
                                    <div class="form-group field-stuadmissionmastersearch-stu_academic_year">
                                        <label class="control-label" for="stuadmissionmastersearch-stu_academic_year">Academic Year</label>
                                        <select id="stuadmissionmastersearch-stu_academic_year" class="form-control" name="StuAdmissionMasterSearch[stu_academic_year]" onchange="$.get( &quot;/dependent/get-academic-courses&quot;, {
         yid : $(this).val()
         }).done(function(data) {
         $( &quot;#stuadmissionmastersearch-stu_course_id&quot;).html(data);
         });">
                                            <option value="">--- Select Academic Year ---</option>
                                            <option value="2">2017/2018 Academic Year</option>
                                            <option value="1">2016-17</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-3">
                                    <div class="form-group field-stuadmissionmastersearch-stu_course_id">
                                        <label class="control-label" for="stuadmissionmastersearch-stu_course_id">Course</label>
                                        <select id="stuadmissionmastersearch-stu_course_id" class="form-control" name="StuAdmissionMasterSearch[stu_course_id]" onchange="$.get(&quot;/dependent/course-batch&quot;, {
         cid : $(this).val(),
         yid : $(&quot;#stuadmissionmastersearch-stu_academic_year&quot;).val()
         }).done(function(data) {
         $( &quot;#stuadmissionmastersearch-stu_batch_id&quot;).html(data);
         });">
                                            <option value="">--- Select Course ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-3">
                                    <div class="form-group field-stuadmissionmastersearch-stu_batch_id">
                                        <label class="control-label" for="stuadmissionmastersearch-stu_batch_id">Batch</label>
                                        <select id="stuadmissionmastersearch-stu_batch_id" class="form-control" name="StuAdmissionMasterSearch[stu_batch_id]">
                                            <option value="">--- Select Batch ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>            </div>
                                <div class="col-sm-3" style="margin-top: 25px;">
                                    <div class="form-group field-stuadmissionmastersearch-searchinput">
                                        <input type="text" id="stuadmissionmastersearch-searchinput" class="form-control" name="StuAdmissionMasterSearch[searchInput]" placeHolder="Enter Registration No, First/Last Name or Email-Id.">
                                        <div class="help-block"></div>
                                    </div>            </div>
                            </div>
                        </div><!--./box-body-->
                        <div class="box-footer">
                            <button type="submit" class="btn btn-info">Apply</button>        <a class="btn btn-default" href="/admission/stu-admission-master/index">Reset</a>    </div>
                    </div><!--./box-solid-->
                </form>
                <div class="box box-solid">
                    <div class="box-body table-responsive" style="overflow-x:inherit">
                        <div class="pull-right">
                            <a class="btn btn-success btn-sm" href="/admission/stu-admission-master/create" data-pjax="0"><i class = "fa fa-plus-circle" aria-hidden="true"></i> New</a>                            <a class="btn btn-info btn-sm" href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=1&amp;export-excel=1" data-pjax="0"><i class = "fa fa-file-excel-o" aria-hidden="true"></i> Excel</a>                    </div>
                        <ul id="w2" class="nav-tabs nav"><li><a href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=0">Pending (1)</a></li>
                            <li><a href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=3">Waiting (1)</a></li>
                            <li><a href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=2">Disapproved (1)</a></li>
                            <li class="active"><a href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=1">Approved (4)</a></li></ul>        <div id="w3" class="grid-view"><div class="summary">Showing <b>1-4</b> of <b>4</b> items.</div>
                            <table class="table table-striped table-bordered"><thead>
                                <tr><th>#</th><th></th><th><a href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=1&amp;sort=created_at" data-sort="created_at">Enquiry Date</a></th><th><a class="desc" href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=1&amp;sort=stu_admission_master_id" data-sort="stu_admission_master_id">Registration No</a></th><th><a href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=1&amp;sort=stuName" data-sort="stuName">Name</a></th><th><a href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=1&amp;sort=stu_academic_year" data-sort="stu_academic_year">Academic Year</a></th><th><a href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=1&amp;sort=stu_course_id" data-sort="stu_course_id">Course</a></th><th><a href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=1&amp;sort=stu_batch_id" data-sort="stu_batch_id">Batch</a></th><th><a href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=1&amp;sort=stu_email_id" data-sort="stu_email_id">Email Id</a></th><th><a href="/admission/stu-admission-master/index?StuAdmissionMasterSearch%5Bapproved_status%5D=1&amp;sort=stu_followup_status" data-sort="stu_followup_status">Last Follow-up Status</a></th><th>Action</th></tr>
                                </thead>
                                <tbody>
                                <tr data-key="6"><td>1</td><td><img class="img-circle img-sm" src="/admission/stu-admission-master/image?name=no-photo.png" alt="NA"></td><td>Sep 19, 2016</td><td class="text-center" style="width:25px">6</td><td><a href="/admission/stu-admission-master/view?id=6">Sentiago Jones</a></td><td>2016-17</td><td>Preschool</td><td>Kindergarten1</td><td><a href="mailto:sentiago@demo.com">sentiago@demo.com</a></td><td class="text-center"><span class="not-set">(not set)</span></td><td><div class="btn-group pull-right" style="display:flex;">
                                            <button id="w4" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                            <ul id="w5" class="dropdown-menu dropdown-menu-right"><li><a href="/admission/stu-admission-master/delete?id=6" data-confirm="Are you sure you want to delete this item?" data-method="post" tabindex="-1">Delete</a></li>
                                                <li><a href="/admission/admission-letter/send-stu-letter?sid=6" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" tabindex="-1">Letter</a></li></ul>
                                        </div></td></tr>
                                <tr data-key="5"><td>2</td><td><img class="img-circle img-sm" src="/admission/stu-admission-master/image?name=no-photo.png" alt="NA"></td><td>Sep 19, 2016</td><td class="text-center" style="width:25px">5</td><td><a href="/admission/stu-admission-master/view?id=5">Mason White</a></td><td>2016-17</td><td>Preschool</td><td>Kindergarten1</td><td><a href="mailto:mason@demo.com">mason@demo.com</a></td><td class="text-center"><span class="not-set">(not set)</span></td><td><div class="btn-group pull-right" style="display:flex;">
                                            <button id="w6" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                            <ul id="w7" class="dropdown-menu dropdown-menu-right"><li><a href="/admission/stu-admission-master/delete?id=5" data-confirm="Are you sure you want to delete this item?" data-method="post" tabindex="-1">Delete</a></li>
                                                <li><a href="/admission/admission-letter/send-stu-letter?sid=5" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" tabindex="-1">Letter</a></li></ul>
                                        </div></td></tr>
                                <tr data-key="2"><td>3</td><td><img class="img-circle img-sm" src="/admission/stu-admission-master/image?name=no-photo.png" alt="NA"></td><td>Sep 12, 2016</td><td class="text-center" style="width:25px">2</td><td><a href="/admission/stu-admission-master/view?id=2">Theo Roy</a></td><td>2016-17</td><td>Preschool</td><td>Kindergarten1</td><td><a href="mailto:theo@demo.com">theo@demo.com</a></td><td class="text-center"><span class="not-set">(not set)</span></td><td><div class="btn-group pull-right" style="display:flex;">
                                            <button id="w8" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                            <ul id="w9" class="dropdown-menu dropdown-menu-right"><li><a href="/admission/stu-admission-master/delete?id=2" data-confirm="Are you sure you want to delete this item?" data-method="post" tabindex="-1">Delete</a></li>
                                                <li><a href="/admission/admission-letter/send-stu-letter?sid=2" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" tabindex="-1">Letter</a></li></ul>
                                        </div></td></tr>
                                <tr data-key="1"><td>4</td><td><img class="img-circle img-sm" src="/admission/stu-admission-master/image?name=no-photo.png" alt="NA"></td><td>Sep 12, 2016</td><td class="text-center" style="width:25px">1</td><td><a href="/admission/stu-admission-master/view?id=1">Amelia Pelletier</a></td><td>2016-17</td><td>Preschool</td><td>Kindergarten1</td><td><a href="mailto:amelia@demo.com">amelia@demo.com</a></td><td class="text-center"><span class="not-set">(not set)</span></td><td><div class="btn-group pull-right" style="display:flex;">
                                            <button id="w10" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">Action <span class="caret"></span></button>
                                            <ul id="w11" class="dropdown-menu dropdown-menu-right"><li><a href="/admission/stu-admission-master/delete?id=1" data-confirm="Are you sure you want to delete this item?" data-method="post" tabindex="-1">Delete</a></li>
                                                <li><a href="/admission/admission-letter/send-stu-letter?sid=1" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" tabindex="-1">Letter</a></li></ul>
                                        </div></td></tr>
                                </tbody></table>
                        </div>    </div><!-- /.box-body -->
                </div><!-- /.box-->
            </div>    </section>
    </div>
@endsection

@section('page-script')


@endsection
