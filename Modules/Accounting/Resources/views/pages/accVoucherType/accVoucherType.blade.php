<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 3/20/17
 * Time: 5:08 PM
 */
use Modules\Accounting\Entities\AccCharts;
?>
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
                <li><a href="#">Add Voucher Type</a></li>
            </ul>
        </section>
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Add Voucher Type</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('accounting/accvouchertype')}}">
                                        <i class="fa"></i> List </a>
                                </div>
                            </div>
                            <form method="post" action="{{URL::asset('accounting/accvouchertype')}}">
                                {{csrf_field()}}
                                <div class="box-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="voucher_code">Voucher code</label>
                                            <input type="text" class="form-control" name="voucher_code" id="voucher_code" placeholder="Enter Voucher Code">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="voucher_name">Voucher Name</label>
                                            <input type="text" class="form-control" name="voucher_name" id="voucher_name" placeholder="Enter Voucher Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="voucher_type">Voucher Type</label>
                                            <select class="form-control" name="voucher_type" id="voucher_type">
                                                <option value="">Select Voucher Type</option>
                                                <option value="1">CONTRA</option>
                                                <option value="2">CREDIT NOTE</option>
                                                <option value="3">DEBIT NOTE</option>
                                                <option value="4">DELIVERY NOTE</option>
                                                <option value="5">JOURNAL</option>
                                                <option value="6">MEMORANDUM</option>
                                                <option value="7">PAYMENT</option>
                                                <option value="8">PHYSICAL STOCK</option>
                                                <option value="9">PURCHASE</option>
                                                <option value="10">PURCHASE ORDER</option>
                                                <option value="11">RECEIPT</option>
                                                <option value="12">RECEIPT NOTE</option>
                                                <option value="13">REJECTIONS IN</option>
                                                <option value="14">REJECTIONS OUT</option>
                                                <option value="15">REVERSING JOURNAL</option>
                                                <option value="16">SALES</option>
                                                <option value="17">SALES ORDER</option>
                                                <option value="18">STOCK JOURNAL</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="voucher_parent">Default Ledger</label>
                                            <select class="form-control" name="voucher_default" id="voucher_default">
                                                <option value="">Select Default Ledger</option>
                                                @foreach($accHeads as $accHead)
                                                    <option disabled value="{{$accHead->id}}">{{$accHead->chart_name}}</option>
                                                    @if(count($accHead->childs))
                                                        @include('accounting::pages.accSubHead.manageChildObtion',['childs' => $accHead->childs])
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="notes">Notes</label>
                                            <textarea class="form-control" cols="30" name="notes" id="notes"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection