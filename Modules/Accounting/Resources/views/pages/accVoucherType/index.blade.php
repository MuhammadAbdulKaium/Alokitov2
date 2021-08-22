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
                <li><a href="#">List of Voucher Types</a></li>
            </ul>
        </section>
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">List of Voucher Types</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('accounting/accvouchertype/add')}}">
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
                                                <th><a  data-sort="sub_master_name">Voucher Code</a></th>
                                                <th><a  data-sort="sub_master_alias">Voucher Name</a></th>
                                                <th><a  data-sort="sub_master_alias">Voucher Type</a></th>
                                                <th><a  data-sort="sub_master_alias">Default Ledger</a></th>
                                                <th><a>Action</a></th>
                                                <th></th>
                                            </tr>

                                            </thead>

                                            <tbody>
                                            <?php $i=1;?>
                                            @foreach($accVoucherTypes as $accVoucherType)
                                                <tr>
                                                    <td>{{ $i++}}</td>
                                                    <td>{{ $accVoucherType->voucher_code }}</td>
                                                    <td>{{ $accVoucherType->voucher_name }}</td>
                                                    <td>
                                                        @if($accVoucherType->voucher_type_id == 1) {{ 'CONTRA' }}
                                                        @elseif($accVoucherType->voucher_type_id == 2) {{ 'CREDIT NOTE' }}
                                                        @elseif($accVoucherType->voucher_type_id == 3) {{ 'DEBIT NOTE' }}
                                                        @elseif($accVoucherType->voucher_type_id == 4) {{ 'DELIVERY NOTE' }}
                                                        @elseif($accVoucherType->voucher_type_id == 5) {{ 'JOURNAL' }}
                                                        @elseif($accVoucherType->voucher_type_id == 6) {{ 'MEMORANDUM' }}
                                                        @elseif($accVoucherType->voucher_type_id == 7) {{ 'PAYMENT' }}
                                                        @elseif($accVoucherType->voucher_type_id == 8) {{ 'PHYSICAL STOCK' }}
                                                        @elseif($accVoucherType->voucher_type_id == 9) {{ 'PURCHASE' }}
                                                        @elseif($accVoucherType->voucher_type_id == 10) {{ 'PURCHASE ORDER' }}
                                                        @elseif($accVoucherType->voucher_type_id == 11) {{ 'RECEIPT' }}
                                                        @elseif($accVoucherType->voucher_type_id == 12) {{ 'RECEIPT NOTE' }}
                                                        @elseif($accVoucherType->voucher_type_id == 13) {{ 'REJECTIONS IN' }}
                                                        @elseif($accVoucherType->voucher_type_id == 14) {{ 'REJECTIONS OUT' }}
                                                        @elseif($accVoucherType->voucher_type_id == 15) {{ 'REVERSING JOURNAL' }}
                                                        @elseif($accVoucherType->voucher_type_id == 16) {{ 'SALES' }}
                                                        @elseif($accVoucherType->voucher_type_id == 17) {{ 'SALES ORDER' }}
                                                        @elseif($accVoucherType->voucher_type_id == 18) {{ 'STOCK JOURNAL' }}
                                                        @endif
                                                    </td>
                                                    <td>@if($accVoucherType->acc_charts_id != '')
                                                        {{ $accVoucherType->parent()->chart_name}}
                                                        @endif
                                                    </td>
                                                    <td>@if($accVoucherType->status==1) <p>Active</p>
                                                        @elseif($accVoucherType->status==0) <p>Inactive</p>@endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-info btn-sm" data-toggle="modal" data-target="#myModal" onclick="modalLoad({{$accVoucherType->id}})"><i class="fa fa-eye"></i> Edit</a>
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
                url: "{{ url('accounting/accvouchertype/edit')}}",
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