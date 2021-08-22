@extends('admin::layouts.master')

{{-- Web site Title --}}

@section('styles')

@stop



@section('js')


@endsection

{{-- Content --}}
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-plus-square"></i> Hostel Management - Hostel Facilities</h1>

            {{-- <ul class="breadcrumb">
                 <li><a href="http://127.0.0.1:8000/home"><i class="fa fa-home"></i>Home</a></li>
                 <li><a href="http://127.0.0.1:8000/finance">Finance</a></li>
                 <li><a href="#">Online Acedemic </a></li>
             </ul>--}}
        </section>
        <section class="content">


            <div class="panel panel-default">
                <div class="panel-body">
                    <div>

                        <!-- page content div -->
                        <!-- grading scale -->
                        <style type="text/css">

                            .label-margin50 {
                                margin-left: 50px;

                            }

                            .redcolor {
                                color: red;
                            }

                        </style>

                        <div class="col-md-12">
                            <div class="box box-solid">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">Facility
                                                            Name</label>
                                                        <input type="text" i="" class="form-control" name=""
                                                               maxlength="250" placeholder="Facility Name"
                                                               aria-required="true">
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">Amount</label>
                                                        <input type="text" i="" class="form-control" name=""
                                                               maxlength="250" placeholder="Amount"
                                                               aria-required="true">
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">


                                                    <div class="">
                                                        <div class="">
                                                            <div class="span5">
                                                                <table class="table table-striped table-condensed">
                                                                    <thead>
                                                                    <tr>
                                                                        <th>facility Name</th>
                                                                        <th>Amount</th>

                                                                        <th>Status</th>
                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                        <td>
                                                                            <span class="label label-success">Edit</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Room rent-1</td>
                                                                        <td>200</td>

                                                                        <td>
                                                                            <span class="label label-success">Edit</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                        <td>
                                                                            <span class="label label-success">Edit</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Room rent-1</td>
                                                                        <td>200</td>

                                                                        <td>
                                                                            <span class="label label-success">Edit</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                    <tr>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                        <td>
                                                                            <span class="label label-success">Edit</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Room rent-1</td>
                                                                        <td>200</td>

                                                                        <td>
                                                                            <span class="label label-success">Edit</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                        <td>
                                                                            <span class="label label-success">Edit</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Room rent-1</td>
                                                                        <td>200</td>

                                                                        <td>
                                                                            <span class="label label-success">Edit</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                        <td>
                                                                            <span class="label label-success">Edit</span>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>Room rent-1</td>
                                                                        <td>200</td>

                                                                        <td>
                                                                            <span class="label label-success">Edit</span>
                                                                        </td>
                                                                    </tr>


                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>


                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-3">

                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">package Name</label>
                                                        <input type="text" i="" class="form-control" name=""
                                                               maxlength="250" placeholder="package Name"
                                                               aria-required="true">
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">

                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">


                                                    <div class="">
                                                        <div class="">
                                                            <div class="span5">
                                                                <table class="table table-striped table-condensed">
                                                                    <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th>facility Name</th>
                                                                        <th>Amount</th>


                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>
                                                                        <td><input type="checkbox"
                                                                                   class="custom-control-input"
                                                                                   id="customCheck1"></td>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox"
                                                                                   class="custom-control-input"
                                                                                   id="customCheck1"></td>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox"
                                                                                   class="custom-control-input"
                                                                                   id="customCheck1"></td>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox"
                                                                                   class="custom-control-input"
                                                                                   id="customCheck1"></td>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox"
                                                                                   class="custom-control-input"
                                                                                   id="customCheck1"></td>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox"
                                                                                   class="custom-control-input"
                                                                                   id="customCheck1"></td>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox"
                                                                                   class="custom-control-input"
                                                                                   id="customCheck1"></td>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox"
                                                                                   class="custom-control-input"
                                                                                   id="customCheck1"></td>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr>
                                                                    <tr>
                                                                        <td><input type="checkbox"
                                                                                   class="custom-control-input"
                                                                                   id="customCheck1"></td>
                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr>


                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-sm-4">
                                            <div class="row">
                                                <div class="col-sm-3">

                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">package Name</label>
                                                        <select id="Hostel" class="form-control" name="Subject">
                                                            <option value="">Select-package</option>
                                                            <option value="">package-1</option>
                                                            <option value="">package-2</option>
                                                        </select>
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-sm-3">

                                                </div>

                                            </div>
                                            <div class="row">
                                                <div class="col-sm-12">


                                                    <div class="">
                                                        <div class="">
                                                            <div class="span5">
                                                                <table class="table table-striped table-condensed">
                                                                    <thead>
                                                                    <tr>

                                                                        <th>facility Name</th>
                                                                        <th>Amount</th>


                                                                    </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <tr>

                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr><tr>

                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr><tr>

                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr><tr>

                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr><tr>

                                                                        <td>Basic Charge</td>
                                                                        <td>100</td>

                                                                    </tr>
                                                                    <tr>

                                                                        <td>monthly total</td>
                                                                        <td>5000</td>

                                                                    </tr>



                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>




                                    </div>
                                </div>


                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </section>
    </div>
@stop

{{-- Scripts --}}

@section('scripts')

@stop