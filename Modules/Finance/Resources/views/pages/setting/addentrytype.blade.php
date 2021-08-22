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

    <style>
        /************** TABLES ***************/
        table {
            width: 100%;
        }

        th {
            border-bottom: 2px solid #555;
            text-align: left;
            font-size: 16px;
        }

        th a {
            display: block;
            padding: 2px 4px;
            text-decoration: none;
        }

        th a.asc:after {
            content: ' â‡£';
        }

        th a.desc:after {
            content: ' â‡¡';
        }

        table tr td {
            padding: 4px;
            text-align: left;
        }

        table.stripped tr td {
            border-bottom:1px solid #DDDDDD;
            border-top:1px solid #DDDDDD;
        }

        table.stripped tr:hover {
            background-color: #FFFF99;
        }

        table.stripped .tr-ledger {

        }

        table.stripped .tr-group {
            font-weight: bold;
        }

        table.extra tr td {
            padding: 6px;
        }

        table.stripped .tr-root-group {
            background-color: #F3F3F3;
            color: #754719;
        }

    </style>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <!-- ./col -->
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header with-border">
                        <h3 class="box-title">Add Entry Types</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">

                        <div class="row">

                            @if(Session::has('message'))
                                <div class="alert alert-success" role="alert">
                                    {{ Session::get('message') }}</div>
                            @endif

                            <form id="myForm" method="post" action="{{URL::to('finance/accounts/account_settings/storeentrytype')}}" class="col s12">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="entriestype_id" value="">
                                <div class="col-md-12 col-lg-6 input-field">
                                    <div class="form-group">
                                        <label>Label</label>
                                            <input name="label" id="label" type="text" class="form-control" required="">
                                        </div>
                                </div>
                                <div class="col-md-12 col-lg-6 input-field">
                                    <div class="form-group">
                                        <label>Name</label>

                                            <input name="name" id="name" type="text" class="form-control" required="">
                                        </div>

                                </div>
                                <div class="col-md-12 col-lg-6 input-field">
                                    <div class="form-group">
                                        <label>Description</label>

                                            <input name="desc" id="desc" type="text" class="form-control" required="">
                                        </div>
                                </div>
                                <div class="col-md-12 col-lg-6 input-field">
                                    <div class="form-group">
                                        <label>Numbering</label>

                                            <select name="numbering" id="numbering" class="form-control">
                                                <option value="1">Auto</option>
                                                <option value="2">Manual (required)</option>
                                                <option value="3">Manual (optional)</option>
                                            </select>


                                    </div>
                                </div>
                                <div class="col-md-12 col-lg-6 input-field">
                                    <div class="form-group">
                                        <label>Prefix</label>
                                            <input name="prefix" id="prefix" type="text" class="form-control">

                                    </div>
                                    <button type="submit" class="btn btn-success">Submit</button>
                                </div>
                                <div class="col-md-12 col-lg-6 input-field">
                                    <div class="form-group">
                                        <label>Suffix</label>

                                            <input name="suffix" id="suffix" type="text" class="form-control">


                                    </div>
                                </div>

                            </form>
                        </div>

            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@section('page-script')




@endsection


