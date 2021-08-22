<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 3/23/17
 * Time: 5:22 PM
 */?>
@extends('layouts.master')
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class = "fa fa-eye" aria-hidden="true"></i> Accounting
            </h1>
            <ul class="breadcrumb">
                <li><a href="{{url('/home')}}"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{url('/finance')}}"><i class="fa fa-home"></i>Finance</a></li>
                <li><a href="{{url('accounting')}}">Accounting</a></li>
                <li><a href="#">List of Voucher entry</a></li>
            </ul>
        </section>
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">List of Voucher entry</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('accounting/accvoucherentry/add')}}">
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
                                                <th><a  data-sort="sub_master_name">Transaction Date</a></th>
                                                <th><a  data-sort="sub_master_alias">Serial</a></th>
                                                <th><a  data-sort="sub_master_alias">Amount</a></th>
                                                <th><a  data-sort="sub_master_alias">Details</a></th>
                                                <th><a  data-sort="sub_master_alias">Status</a></th>
                                                <th></th>
                                            </tr>

                                            </thead>

                                            <tbody>
                                            @php $i=1; @endphp
                                            @foreach($accVoucherEntrys as $accVoucherEntry)
                                                <tr>
                                                    <td>{{ $i++}}</td>
                                                    <td>{{ date('d-m-Y',strtotime($accVoucherEntry->tran_date)) }}</td>
                                                    <td>{{ $accVoucherEntry->tran_serial }}</td>
                                                    <td>{{ $accVoucherEntry->tran_amt_dr }}</td>
                                                    <td>{{ $accVoucherEntry->tran_details }}</td>
                                                    <td>@if($accVoucherEntry->status==1) <p>Approved</p>
                                                        @elseif($accVoucherEntry->status==2) <p>Not Approved Yet</p>
                                                        @elseif($accVoucherEntry->status==0) <p>Deleted</p>
                                                        @endif
                                                    </td>
                                                    <td>

                                                            @if($accVoucherEntry->status !=1 )
                                                            <a class="btn btn-danger btn-sm" data-toggle="modal" data-target="#myModal" onclick="modalLoad({{$accVoucherEntry->tran_serial}})">Approve
                                                            @else
                                                                <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal" onclick="modalLoad({{$accVoucherEntry->tran_serial}})"><i class="fa fa-eye"></i>View
                                                            @endif
                                                        </a>
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
                url: "{{ url('accounting/accvoucherentry/approve')}}",
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