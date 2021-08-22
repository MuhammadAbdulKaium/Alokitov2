@extends('finance::layouts.master')
@section('section-title')

    <h1>
        <i class="fa fa-search"></i>Finance
    </h1>
    <ul class="breadcrumb">
        <li>
            <a href="/">
                <i class="fa fa-home"></i>Home
            </a>
        </li>
        <li>
            <a href="/library/default/index">Finacne</a>
        </li>
        <li class="active">Manage Account</li>
    </ul>

@endsection

<!-- page content -->
@section('page-content')

    <section class="content">
        <div class="row">
            <div class="col-md-3">
                <!-- Custom Info Boxes By Omar Sher -->
                <a href="https://otsglobal.org/accountant/admin/create_account" style="color: white;">
                    <div class="info-box bg-aqua hover">
                        <span class="info-box-icon"><i class="fa fa-plus-square"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-number">Create Account</span>
                            <span class="progress-description">Create a new Account <i class="fa fa-arrow-circle-right"></i></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
            </div>

            <div class="col-md-3">
                <!-- /.admin/create_account -->
                <a href="https://otsglobal.org/accountant/admin/accounts" style="color: white;">
                    <div class="info-box bg-green hover">
                        <span class="info-box-icon"><i class="ion ion-stats-bars"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-number">Manage Accounts</span>
                            <span class="progress-description">Manage existing Accounts <i class="fa fa-arrow-circle-right"></i></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
            </div>

            <div class="col-md-3">
                <!-- /.admin/accounts -->
                <a href="https://otsglobal.org/accountant/admin/users" style="color: white;">
                    <div class="info-box bg-yellow hover">
                        <span class="info-box-icon"><i class="ion ion-person"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-number">Manage Users</span>
                            <span class="progress-description">Manage Users and Permissions <i class="fa fa-arrow-circle-right"></i></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
            </div>
                <!-- /.admin/user -->
            <div class="col-md-3">
                <a href="https://otsglobal.org/accountant/admin/settings" style="color: white;">
                    <div class="info-box bg-red hover">
                        <span class="info-box-icon"><i class="fa fa-cogs"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-number">General Settings</span>
                            <span class="progress-description">General Application Settings <i class="fa fa-arrow-circle-right"></i></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </a>
            </div>
                <!-- /.admin/settings -->
            </div>
            <!-- ./col-md-4 -->
        <div class="row">
            <!-- TABLE: LATEST ORDERS -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">List of all Years/Companies</h3>
                    <p class="pull-right" style="padding-right: 50px;">
                        <strong>Currently active year/company : <em style="font-size: 18px; padding-left: 30px">"(NONE)"</em></strong>
                    </p>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="table-responsive" style="max-height: 300px;">
                        <table id="accounts" class="table table-striped table-hover custom-table">
                            <thead>
                            <tr>
                                <th>Label</th>
                                <th>Name</th>
                                <th>Fiscal Year</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td><em>OTS20172018</em></td>
                                <td>OTS20172018</td>
                                <td>
                                    <strong>2017-Apr-01</strong> to <strong>2018-Mar-31</strong>                    </td>
                                <td data-toggle="tooltip" data-container="body" title="Click to Activate">
                                    <a href="https://otsglobal.org/accountant/user/activate/1"><span class="label label-default">Inactive</span></a>
                                </td>
                            </tr>
                            <tr>
                                <td><em>MTC</em></td>
                                <td>My Tech Company</td>
                                <td>
                                    <strong>2017-Oct-01</strong> to <strong>2018-Oct-01</strong>                    </td>
                                <td data-toggle="tooltip" data-container="body" title="Click to Activate">
                                    <a href="https://otsglobal.org/accountant/user/activate/2"><span class="label label-default">Inactive</span></a>
                                </td>
                            </tr>
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Label</th>
                                <th>Name</th>
                                <th>Fiscal Year</th>
                                <th>Status</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.table-responsive -->
                </div>
                <!-- /.box-body -->
                <div class="box-footer">
                    {{--<p style="font-size: 18px;"><strong>Note:</strong> <em>If you wish to use multiple accounts simultaneously, please use different browsers for each.</em></p>--}}
                </div>
                <!-- /.box-footer -->
            </div>
            <!-- /.box -->
        </div>
        <!-- ./col-md-8 -->
        </div>
        <!-- ./row -->
    </section>
@endsection

@section('page-script')




@endsection


