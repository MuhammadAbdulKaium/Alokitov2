@extends('layouts.master')
<!-- page content -->
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-user" aria-hidden="true"></i> Enquiry Details        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="/admission/default/index">Enquiry</a></li>
                <li><a href="/admission/stu-admission-master/index">Manage Enquiry</a></li>
                <li class="active">asdasdasd asdasdasd</li>
            </ul>    </section>
        <section class="content">
            <div class="row">
                <div class ="col-sm-3">
                    <div class="box box-solid">
                        <div class="box-body box-profile">
                            <img class="profile-user-img img-responsive img-circle" src="/admission/stu-admission-master/image?name=no-photo.png" alt="No Image" style="height:100px;">                <h3 class="profile-username text-center">
                                asdasdasd asdasdasd                </h3>
                            <h4 class="text-muted text-center">
                                <span class="label label-primary">Pending</span>                </h4>
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Registration No</b>
                                    <span class="pull-right">7</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Email Id</b>
                                    <span class="pull-right">asdas232d@gmail.com</span>
                                </li>
                                <li class="list-group-item">
                                    <b>Mobile No</b>
                                    <span class="pull-right"></span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-sm-9">
                    <div class="nav-tabs-custom">
                        <ul id="w1" class="nav nav-tabs"><li class="active"><a href="#w1-tab0" data-toggle="tab"><i class = "fa fa-user" aria-hidden="true"></i> Personal</a></li>
                            <li><a href="#w1-tab1" data-toggle="tab"><i class = "fa fa-map-marker" aria-hidden="true"></i> Address</a></li>
                            <li><a href="#w1-tab2" data-toggle="tab"><i class = "fa fa-graduation-cap" aria-hidden="true"></i> Apply</a></li>
                            <li><a href="#w1-tab4" data-toggle="tab"><i class = "fa fa-file-o" aria-hidden="true"></i> Documents</a></li>
                            <li class="pull-right dropdown"><a class="dropdown-toggle" href="#" data-toggle="dropdown"><i class = "fa fa-gear" aria-hidden="true"></i> Action <b class="caret"></b></a>
                                <ul id="w2" class="dropdown-menu"><li><a href="/admission/stu-admission-master/update?id=7" tabindex="-1">Update</a></li>
                                    <li><a href="/admission/stu-admission-master/delete?id=7" data-confirm="Are you sure you want to delete this item?" data-method="post" tabindex="-1">Delete</a></li>
                                    <li><a href="/admission/admission-letter/send-stu-letter?sid=7" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" tabindex="-1">Letter</a></li>
                                    <li><a href="/admission/stu-admission-master/approve?id=7" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" data-backdrop="static" tabindex="-1">Approve</a></li>
                                    <li><a href="/admission/stu-admission-master/stu-admit-confirm?id=7&amp;status=2" data-confirm="Are you sure you want to disapprove this applicant?" data-method="post" tabindex="-1">Disapprove</a></li>
                                    <li><a href="/admission/stu-admission-master/stu-admit-confirm?id=7&amp;status=3" tabindex="-1">Waiting</a></li></ul></li></ul>
                        <div class="tab-content"><div id="w1-tab0" class="tab-pane active"><table class="table table-striped table-bordered">
                                    <colgroup>
                                        <col style="width:130px">
                                        <col style="width:130px">
                                        <col style="width:130px">
                                        <col style="width:130px">
                                    </colgroup>
                                    <tr>
                                        <th>Title</th>
                                        <td colspan="3">Mr.</td>
                                    </tr>
                                    <tr>
                                        <th>First Name</th>
                                        <td>asdasdasd</td>
                                        <th>Last Name</th>
                                        <td>asdasdasd</td>
                                    </tr>
                                    <tr>
                                        <th>Gender</th>
                                        <td></td>
                                        <th>Date of Birth</th>
                                        <td>Aug 1, 2017</td>
                                    </tr>
                                    <tr>
                                        <th>Religion</th>
                                        <td> - </td>
                                        <th>Nationality</th>
                                        <td>Not Set</td>
                                    </tr>
                                    <tr>
                                        <th>Category</th>
                                        <td>Not Set</td>
                                        <th>Source Type</th>
                                        <td> - </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="w1-tab1" class="tab-pane"><table class="table table-striped table-bordered">
                                    <colgroup>
                                        <col style="width:130px">
                                        <col style="width:130px">
                                        <col style="width:130px">
                                        <col style="width:130px">
                                    </colgroup>
                                    <tr>
                                        <th>Address</th>
                                        <td colspan="3"> - </td>
                                    </tr>
                                    <tr>
                                        <th>City</th>
                                        <td> - </td>
                                        <th>State</th>
                                        <td> - </td>
                                    </tr>
                                    <tr>
                                        <th>Country</th>
                                        <td> - </td>
                                        <th>Pincode</th>
                                        <td> - </td>
                                    </tr>
                                    <tr>
                                        <th>House No</th>
                                        <td> - </td>
                                        <th>Phone No</th>
                                        <td> - </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="w1-tab2" class="tab-pane"><table class="table table-striped table-bordered">
                                    <colgroup>
                                        <col style="width:130px">
                                        <col style="width:130px">
                                        <col style="width:130px">
                                        <col style="width:130px">
                                    </colgroup>
                                    <tr>
                                        <th>Academic Year</th>
                                        <td colspan="3">2016-17</td>
                                    </tr>
                                    <tr>
                                        <th>Course</th>
                                        <td>Preschool</td>
                                        <th>Batch</th>
                                        <td>Kindergarten1</td>
                                    </tr>
                                    <tr>
                                        <th>Applied Time</th>
                                        <td>Aug 7, 2017, 12:02:57 PM</td>
                                        <th>Applied User</th>
                                        <td>admin@edusec.org</td>
                                    </tr>
                                    <tr>
                                        <th>Updated Time</th>
                                        <td>Aug 7, 2017, 12:02:57 PM</td>
                                        <th>Updated User</th>
                                        <td>admin@edusec.org</td>
                                    </tr>
                                </table>
                            </div>
                            <div id="w1-tab4" class="tab-pane"><table class="table table-bordered">
                                    <colgroup>
                                        <col style="width:200px">
                                        <col style="width:150px">
                                        <col style="width:150px">
                                        <col style="width:10px">
                                    </colgroup>
                                    <thead>
                                    <tr>
                                        <th>
                                            Document Category            </th>
                                        <th>
                                            Document Details            </th>
                                        <th>
                                            Submited At            </th>
                                        <th class="text-center">
                                            Download            </th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td class="text-center" colspan="4">
                                            <strong>
                                                No Documents Submitted !!                    </strong>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div></div>        </div><!--./nav-tabs-custom-->
                </div><!--./col-->
                <div class="col-sm-9">
                    <div class="box box-solid">
                        <div class="extra-div">
                            <div class="box-header with-border">
                                <h3 class="box-title">Follow-ups</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm pull-right" href="/admission/applicant-followups/create?id=7" data-target="#globalModal" data-toggle="modal"><i class = "fa fa-plus-square" aria-hidden="true"></i> Add</a>                                       </div>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <div class="alert bg-warning text-warning">
                                <i class="fa fa-warning"></i>
                                No record found.                    </div>
                        </div>
                    </div>
                </div><!--./col-->
            </div><!--./row-->
        </section>
    </div>

@endsection

@section('scripts')


@endsection
