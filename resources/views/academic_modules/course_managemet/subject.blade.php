@extends('layouts.master')
<
<body class="layout-top-nav skin-blue-light">
    <div class="wrapper">

        <div class="content-wrapper">
            <section class="content-header">
                <h1>
            <i class="fa fa-th-list"></i> Manage  |<small>Subject</small>        </h1>
                <ul class="breadcrumb">
                    <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                    <li><a href="/academics/default/index">Academics</a></li>
                    <li class="active">Course Management</li>
                    <li class="active">Subject</li>
                </ul>
            </section>
            <section class="content">
                <div class="box box-solid">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Subject</h3>
                    </div>
                    <form id="subject-master-form" action="{{url('store-subject')}}}" method="post">
                        <input type="hidden" name="_csrf" value="S0AGg4rBw==">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group field-subjectmaster-sub_master_name required">
                                        <label class="control-label" for="subjectmaster-sub_master_name">Subject Name</label>
                                        <input type="text" id="subjectmaster-sub_master_name" class="form-control" name="SubjectMaster[sub_master_name]" maxlength="60" placeholder="Enter Subject Name" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-subjectmaster-sub_master_code required">
                                        <label class="control-label" for="subjectmaster-sub_master_code">Subject Code</label>
                                        <input type="text" id="subjectmaster-sub_master_code" class="form-control" name="SubjectMaster[sub_master_code]" maxlength="10" placeholder="Enter Subject Code" aria-required="true">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group field-subjectmaster-sub_master_alias required">
                                        <label class="control-label" for="subjectmaster-sub_master_alias">Subject Alias</label>
                                        <input type="text" id="subjectmaster-sub_master_alias" class="form-control" name="SubjectMaster[sub_master_alias]" maxlength="10" placeholder="Enter Subject Alias" aria-required="true">
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
                        <h3 class="box-title"><i class="fa fa-search"></i> View Subject List</h3>
                    </div>
                    <div class="box-body table-responsive">
                        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                            <div id="w1" class="grid-view">
                                <div class="summary">Showing <b>1-9</b> of <b>9</b> items.</div>
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th><a href="/course/subject-master/index?sort=sub_master_name" data-sort="sub_master_name">Subject Name</a></th>
                                            <th><a href="/course/subject-master/index?sort=sub_master_code" data-sort="sub_master_code">Subject Code</a></th>
                                            <th><a href="/course/subject-master/index?sort=sub_master_alias" data-sort="sub_master_alias">Subject Alias</a></th>
                                            <th><a href="/course/subject-master/index?sort=is_status" data-sort="is_status">Status</a></th>
                                            <th class="action-column">&nbsp;</th>
                                        </tr>
                                        <tr id="w1-filters" class="filters">
                                            <td>&nbsp;</td>
                                            <td>
                                                <input type="text" class="form-control" name="SubjectMasterSearch[sub_master_name]">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="SubjectMasterSearch[sub_master_code]">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="SubjectMasterSearch[sub_master_alias]">
                                            </td>
                                            <td>
                                                <select class="form-control" name="SubjectMasterSearch[is_status]">
                                                    <option value=""></option>
                                                    <option value="0">Active</option>
                                                    <option value="1">Inactive</option>
                                                </select>
                                            </td>
                                            <td>&nbsp;</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr data-key="9">
                                            <td>1</td>
                                            <td>Operating System</td>
                                            <td>OS</td>
                                            <td>OS</td>
                                            <td class="text-center"><a class="toggle-column" href="/course/subject-master/toggle?id=9" title="Inactive" data-method="post" data-pjax="0"><span class="fa fa-check-circle fa-lg"></span></a></td>
                                            <td><a href="/course/subject-master/update?id=9" title="Update" aria-label="Update" data-pjax="0"><i class="fa fa-pencil "></i></span></a> <a href="/course/subject-master/delete?id=9" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><i class="fa fa-trash"></i></a></td>
                                        </tr>
                                        <tr data-key="8">
                                            <td>2</td>
                                            <td>Microsoft Office</td>
                                            <td>MSOffice</td>
                                            <td>MSOffice</td>
                                            <td class="text-center"><a class="toggle-column" href="/course/subject-master/toggle?id=8" title="Inactive" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-ok-circle fa-lg"></span></a></td>
                                            <td><a href="/course/subject-master/update?id=8" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/course/subject-master/delete?id=8" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="7">
                                            <td>3</td>
                                            <td>Computer Networking</td>
                                            <td>CN01</td>
                                            <td>CN</td>
                                            <td class="text-center"><a class="toggle-column" href="/course/subject-master/toggle?id=7" title="Inactive" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-ok-circle fa-lg"></span></a></td>
                                            <td><a href="/course/subject-master/update?id=7" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/course/subject-master/delete?id=7" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="6">
                                            <td>4</td>
                                            <td>Language &amp; Litereacy</td>
                                            <td>LL</td>
                                            <td>LL</td>
                                            <td class="text-center"><a class="toggle-column" href="/course/subject-master/toggle?id=6" title="Inactive" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-ok-circle fa-lg"></span></a></td>
                                            <td><a href="/course/subject-master/update?id=6" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/course/subject-master/delete?id=6" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="5">
                                            <td>5</td>
                                            <td>Arts</td>
                                            <td>Arts</td>
                                            <td>Arts</td>
                                            <td class="text-center"><a class="toggle-column" href="/course/subject-master/toggle?id=5" title="Inactive" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-ok-circle fa-lg"></span></a></td>
                                            <td><a href="/course/subject-master/update?id=5" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/course/subject-master/delete?id=5" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="4">
                                            <td>6</td>
                                            <td>Mathematics</td>
                                            <td>Maths</td>
                                            <td>Maths</td>
                                            <td class="text-center"><a class="toggle-column" href="/course/subject-master/toggle?id=4" title="Inactive" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-ok-circle fa-lg"></span></a></td>
                                            <td><a href="/course/subject-master/update?id=4" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/course/subject-master/delete?id=4" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="3">
                                            <td>7</td>
                                            <td>Social Studies</td>
                                            <td>SS</td>
                                            <td>SS</td>
                                            <td class="text-center"><a class="toggle-column" href="/course/subject-master/toggle?id=3" title="Inactive" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-ok-circle fa-lg"></span></a></td>
                                            <td><a href="/course/subject-master/update?id=3" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/course/subject-master/delete?id=3" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="2">
                                            <td>8</td>
                                            <td>Science</td>
                                            <td>Sci</td>
                                            <td>Sci</td>
                                            <td class="text-center"><a class="toggle-column" href="/course/subject-master/toggle?id=2" title="Inactive" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-ok-circle fa-lg"></span></a></td>
                                            <td><a href="/course/subject-master/update?id=2" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/course/subject-master/delete?id=2" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
                                        </tr>
                                        <tr data-key="1">
                                            <td>9</td>
                                            <td>English</td>
                                            <td>Eng</td>
                                            <td>Eng</td>
                                            <td class="text-center"><a class="toggle-column" href="/course/subject-master/toggle?id=1" title="Inactive" data-method="post" data-pjax="0"><span class="glyphicon glyphicon-ok-circle fa-lg"></span></a></td>
                                            <td><a href="/course/subject-master/update?id=1" title="Update" aria-label="Update" data-pjax="0"><span class="glyphicon glyphicon-pencil"></span></a> <a href="/course/subject-master/delete?id=1" title="Delete" aria-label="Delete" data-pjax="0" data-confirm="Are you sure you want to delete this item?" data-method="post"><span class="glyphicon glyphicon-trash"></span></a></td>
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
        <div id="slideToTop"><i class="fa fa-chevron-up"></i></div>
        </div>
        <footer class="main-footer">
            <div class="pull-right hidden-xs">
                <div class="pull-right hidden-xs text-bold">
                    Powered by <a href="http://www.rudrasoftech.com">EduSec<sup>&trade;</sup></a>
                </div>
            </div>
            <strong>Copyright &copy; 2017 <a href="http://www.rudrasoftech.com">Rudra Softech</a>.</strong> All rights reserved. </footer>
        <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="loader">
                            <div class="es-spinner">
                                <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="../../js/all.js?v=1486729641"></script>
        <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery('#subject-master-form').yiiActiveForm([{
                "id": "subjectmaster-sub_master_name",
                "name": "sub_master_name",
                "container": ".field-subjectmaster-sub_master_name",
                "input": "#subjectmaster-sub_master_name",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Subject Name cannot be blank."
                    });
                    yii.validation.string(value, messages, {
                        "message": "Subject Name must be a string.",
                        "max": 60,
                        "tooLong": "Subject Name should contain at most 60 characters.",
                        "skipOnEmpty": 1
                    });
                }
            }, {
                "id": "subjectmaster-sub_master_code",
                "name": "sub_master_code",
                "container": ".field-subjectmaster-sub_master_code",
                "input": "#subjectmaster-sub_master_code",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Subject Code cannot be blank."
                    });
                    yii.validation.string(value, messages, {
                        "message": "Subject Code must be a string.",
                        "max": 10,
                        "tooLong": "Subject Code should contain at most 10 characters.",
                        "skipOnEmpty": 1
                    });
                }
            }, {
                "id": "subjectmaster-sub_master_alias",
                "name": "sub_master_alias",
                "container": ".field-subjectmaster-sub_master_alias",
                "input": "#subjectmaster-sub_master_alias",
                "validate": function(attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {
                        "message": "Subject Alias cannot be blank."
                    });
                    yii.validation.string(value, messages, {
                        "message": "Subject Alias must be a string.",
                        "max": 10,
                        "tooLong": "Subject Alias should contain at most 10 characters.",
                        "skipOnEmpty": 1
                    });
                }
            }], []);
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
                "filterUrl": "\/course\/subject-master\/index",
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
</body>

</html>
