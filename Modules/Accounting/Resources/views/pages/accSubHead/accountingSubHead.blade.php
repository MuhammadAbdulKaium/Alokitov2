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
                <li><a href="#">Add Sub Ledger</a></li>
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
                                <h3 class="box-title">Sub Ledger</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('accounting/accsubhead')}}">
                                        <i class="fa"></i> List </a>
                                </div>
                            </div>
                            <form method="post" action="{{URL::asset('accounting/accsubhead')}}">
                                {{csrf_field()}}
                                <div class="box-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="chart_code">Code</label>
                                            <input type="text" class="form-control" name="chart_code" id="chart_code" placeholder="Enter Code">
                                            {{--<span class="help-block">Note : Enter if the ledger account is a bank or a cash account.</span>--}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="chart_name">Name</label>
                                            <input type="text" class="form-control" name="chart_name" id="chart_name" placeholder="Enter Name">
                                            {{--<span class="help-block">Note : Name of your Head.</span>--}}
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="chart_parent">Parent</label>
                                            <select class="form-control" name="chart_parent" id="chart_parent">
                                                <option>Select Parent</option>
                                                {{--@foreach($accHeads as $accHead)
                                                    <option value="{{$accHead->id}}">{{$accHead->chart_name}}</option>
                                                @endforeach--}}
                                                    @foreach($accHeads as $accHead)
                                                            <option disabled value="{{$accHead->id}}">{{$accHead->chart_name}}</option>
                                                        @if(count($accHead->childs))
                                                                @include('accounting::pages.accSubHead.manageChildObtion',['childs' => $accHead->childs])
                                                            @endif
                                                    @endforeach
                                            </select>
                                            {{--<span class="help-block">Note : Select if the ledger account is a bank or a cash account.</span>--}}
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