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
        <!-- Small boxes (Stat box) -->
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">Account Create</h3>
            </div>
            <!-- /.box-header -->
            <form action="{{URL::to('/finance/accounts/store')}}" method="post" accept-charset="utf-8">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <div class="box-body">
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="name">Account Name</label><input type="text" placeholder="account name" name="name"  class="form-control"  />
                            </div>
                            <div class="form-group">
                                <label for="name">Account Code</label><input type="text" name="code" placeholder="accountCode"  class="form-control"  />
                            </div>
                        </div>

                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="fy_start">Financial year start</label><input type="text" placeholder="dd-mm-yyyy" name="start_date" class="form-control" id="start_date"  />
                            </div>
                            <div class="form-group">
                                <label for="fy_end">Financial year end</label><input type="text" placeholder="dd-mm-yyyy" name="end_date"  class="form-control" id="end_date"  />
                            </div>

                        </div>

                        <div class="col-xs-4">
                            <div class="form-group">
                                <label for="email">Email</label><input type="text" placeholder="email" name="email"  class="form-control"  />
                            </div>
                            <div class="form-group">
                                <label for="address">Address</label><input type="text" placeholder="address" name="address"  class="form-control"  />
                            </div>

                        </div>


                    </div>
                </div>
                <div class="box-footer">
                    <div class="form-group">
                        <input type="submit" name="submit" value="Submit"  class="btn btn-success  pull-left" />
                    </div>
                </div>
            </form>
        </div>
    <!-- /.content -->
@endsection

@section('page-script')
            <script>
                $('#start_date').datepicker({format: 'dd-mm-yyyy',todayHighlight: true});
                $('#end_date').datepicker({format: 'dd-mm-yyyy',todayHighlight: true});
            </script>

@endsection


