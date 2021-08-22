<?php
/**
 * Created by PhpStorm.
 * User: azadur
 * Date: 5/24/17
 * Time: 11:30 AM
 */
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
                <li><a href="#">Add Accounting Financial Year</a></li>
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
                                <h3 class="box-title">Accounting Financial Year</h3>
                                <div class="box-tools">
                                    <a class="btn btn-success btn-sm" href="{{url('accounting/accfyear')}}">
                                        <i class="fa"></i> List </a>
                                </div>
                            </div>
                            <form id="accFyear">
                                <div class="box-body">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Start Date</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input readonly type="text" name="start_date" id="start_date" placeholder="Start Date" class="form-control pull-right" required>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>End Date</label>
                                            <div class="input-group date">
                                                <div class="input-group-addon">
                                                    <i class="fa fa-calendar"></i>
                                                </div>
                                                <input readonly type="text" name="end_date" id="end_date" placeholder="End Date" class="form-control pull-right" required>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="col-md-6">
                                        <div class="form-group">
                                            <label for="acc_name">Account Name </label>
                                            <input type="text" class="form-control" name="acc_name" id="acc_name" placeholder="Account Name">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="acc_address">Account Address</label>
                                            <textarea class="form-control" cols="30" name="acc_address" id="acc_address" placeholder="Account Address"></textarea>
                                        </div>
                                    </div>--}}
                                </div>
                                <!-- /.box-body -->
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <span id="errorMsg"></span>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <!-- jQuery 2.2.3 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>

    <!-- bootstrap datepicker -->
    <script src="{{URL::asset('js/bootstrap-datepicker.js')}}"></script>

    <script>
        $(document).ready(function () {
            $('#accFyear').submit(function (e) {
                var token = "{{ csrf_token() }}";
                var dataSet = '_token='+token+'&'+$(this).serialize();

                var start_date = $('#start_date').val();
                var end_date = $('#end_date').val();
                if(start_date == ''){
                    alert('start date empty');
                }else if(end_date == ''){
                    alert('start date empty');
                }else {
                    $.ajax({
                     url: "{{URL::asset('accounting/accfyear')}}",
                     type: 'post',
                     data: dataSet,
                     beforeSend: function () {
                     }, success: function (data) {
                         $('#errorMsg').html(data);
                         }
                     });
                    e.preventDefault();
                }
            });
        });

        $(function () {
            $('#end_date, #start_date').datepicker({
                autoclose: true
            });
        });
    </script>
@endsection
