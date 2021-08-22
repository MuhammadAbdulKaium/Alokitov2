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
                        <h3 class="box-title">Entry Types</h3>
                        <button href="#clientmodal" class="add_c btn btn-primary pull-right">
                            <i class="fa fa-plus-circle">Add Entry Type</i>
                        </button>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="responsive-table">
                            <table class="display compact table table-bordered table-striped" id="dynamic-table">
                                <thead>
                                <tr>
                                    <th>Label</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Prefix</th>
                                    <th>Suffix</th>
                                    <th>Actions</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($entriesTypes as $entirestype)
                                <tr>
                                    <td>{{$entirestype->label}}</td>
                                    <td>{{$entirestype->name}}</td>
                                    <td>{{$entirestype->desc}}</td>
                                    <td>{{$entirestype->prefix}}</td>
                                    <td>{{$entirestype->suffix}}</td>
                                    <td> <a href="" class="btn btn-primary  btn-xs"  title="" data-toggle="tooltip" data-placement="bottom"  data-original-title="Update Entry Type"><i class="fa fa-pencil"></i></a>
                                        <a  href="" class="btn btn-danger btn-xs delete_class"  data-placement="top" data-content="delete"  data-original-title="Delete Entry Type"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('page-script')




@endsection


