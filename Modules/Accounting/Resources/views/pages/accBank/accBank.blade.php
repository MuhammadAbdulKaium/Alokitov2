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
                <li><a href="#"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{url('accounting')}}">Accounting</a></li>
                <li><a href="#">Add Bank</a></li>
            </ul>
        </section>
        {{--<ol>
            @foreach($accHeads as $accHead)
            <li>{{$accHead->chart_name}}</li>
            @endforeach
        </ol>--}}
        <section class="content">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <!-- general form elements -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title">Add Bank</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('accounting/accbank')}}">
                                        <i class="fa"></i> List </a>
                                </div>
                            </div>
                            <form method="post" action="{{URL::asset('accounting/accbank')}}">
                                {{csrf_field()}}
                                <div class="box-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bank_code">Bank Code</label>
                                            <input type="text" class="form-control" name="bank_code" id="bank_code" placeholder="Enter Bank Code">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bank_name">Bank Name</label>
                                            <input type="text" class="form-control" name="bank_name" id="bank_name" placeholder="Enter Bank Name">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bank_acc_no">Bank Acc No</label>
                                            <input type="text" class="form-control" name="bank_acc_no" id="bank_acc_no" placeholder="Enter Bank Acc No">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bank_acc_name">Bank Acc Name</label>
                                            <input type="text" class="form-control" name="bank_acc_name" id="bank_acc_name" placeholder="Enter Bank Acc Name">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="bank_parent">Parent</label>
                                            <select class="form-control" name="bank_parent" id="bank_parent">
                                                <option>Select Parent</option>
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