<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 3/23/17
 * Time: 5:22 PM
 */
use Modules\Accounting\Entities\AccSubLedger;
?>
@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-eye" aria-hidden="true"></i> Accounting
            </h1>
            <ul class="breadcrumb">
                <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{url('accounting')}}">Accounting</a></li>
                <li><a href="#">List Sub Ledger</a></li>
            </ul>
        </section>
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">List of Sub Ledger</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('accounting/accsubhead/add')}}">
                                        <i class="fa fa-plus-square"></i> Add</a>
                                </div>
                            </div>
                            <div class="box-body table-responsive">
                                <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
                                    <div id="w1" class="grid-view">

                                        <table id="myTable" class="table table-striped table-bordered">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th><a  data-sort="sub_master_name">Code</a></th>
                                                <th><a  data-sort="sub_master_alias">Name</a></th>
                                                <th><a  data-sort="sub_master_alias">Parent</a></th>
                                                <th><a>Action</a></th>
                                                <th></th>
                                            </tr>

                                            </thead>

                                            <tbody>
                                            <?php $i=1;?>
                                            @foreach($accSubLedgers as $accSubLedger)
                                                <tr>
                                                    <td>{{ $i++}}</td>
                                                    <td>{{ $accSubLedger->chart_code }}</td>
                                                    <td>{{ $accSubLedger->chart_name }}</td>
                                                    <td>{{ $accSubLedger->parent()->chart_name}}</td>
                                                    <td>@if($accSubLedger->status==1) <p>Active</p>
                                                        @elseif($accSubLedger->status==0) <p>Inactive</p>@endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal" onclick="modalLoad({{$accSubLedger->id}})"><i class="fa fa-eye"></i> Edit</a>
                                                    </td>
                                                </tr>
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div><!-- /.box-body -->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        {{--data will sit here--}}
    </div>

    <script type = "text/javascript">
        function modalLoad(rowId) {
            var token = "{{ csrf_token() }}";
            var dataSet = '_token='+token+'&id='+rowId;
            $.ajax({
                url: "{{ url('accounting/accsubhead/edit')}}",
                type: 'post',
                data: dataSet,
                beforeSend: function () {
                }, success: function (data) {
                    $('#myModal').html(data);
                }
            });
        }
    </script>
@endsection