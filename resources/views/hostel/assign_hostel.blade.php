@extends('admin::layouts.master')
{{-- Web site Title --}}
@section('styles')
@stop
{{-- Content --}}
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-plus-square"></i> Hostel Management - Hostel Create</h1>
            {{--
            <ul class="breadcrumb">
               <li><a href="http://127.0.0.1:8000/home"><i class="fa fa-home"></i>Home</a></li>
               <li><a href="http://127.0.0.1:8000/finance">Finance</a></li>
               <li><a href="#">Online Acedemic </a></li>
            </ul>
            --}}
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
                        <style>
                            /* Some custom styles to beautify this example */
                            .demo-content {
                                padding: 15px;
                                font-size: 18px;
                                background: #DDEBF7;
                                margin-bottom: 15px;
                            / / border-right: 1 px solid #dcdcdc;
                            }

                            .tbr-border {
                                border-right: 1px solid #fff;
                            }

                            .demo-content.bg-alt {
                                background: #abb1b8;
                            }

                            .tbr-head {
                                background: #5B9BD5;
                                min-height: 25px;
                            }
                        </style>
                        <div class="col-md-12">
                            <div class="box box-solid">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">Hostel
                                                            Building</label>
                                                        <input type="text" i="" class="form-control" name=""
                                                               maxlength="250" placeholder="write a hostel name"
                                                               aria-required="true">
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">Building ID</label>
                                                        <input type="text" i="" class="form-control" name=""
                                                               maxlength="250" placeholder="ID" aria-required="true">
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">Hostel/Building
                                                            Description</label>
                                                        <input type="text" i="" class="form-control" name=""
                                                               maxlength="250"
                                                               placeholder="Write a Clear Description of the Hostel"
                                                               aria-required="true">
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">For Gender</label>
                                                        <input type="text" i="" class="form-control" name=""
                                                               maxlength="250"
                                                               placeholder="Write a Clear Description of the Hostel"
                                                               aria-required="true">
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">For Gender</label>
                                                        <input type="text" i="" class="form-control" name=""
                                                               maxlength="250" placeholder="Gender"
                                                               aria-required="true">
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">of Floors</label>
                                                        <input type="text" i="" class="form-control" name=""
                                                               maxlength="250" placeholder="Floors"
                                                               aria-required="true">
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">Of Rooms</label>
                                                        <input type="text" i="" class="form-control" name=""
                                                               maxlength="250" placeholder="Rooms" aria-required="true">
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="row">
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">Facility</label>
                                                        <select id="selectMonth" class="form-control" name="Facility"
                                                                aria-required="true">
                                                            <option value="">--- Select Facility ---</option>
                                                        </select>
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">Hostel
                                                            Warden</label>
                                                        <select id="selectMonth" class="form-control" name="Warden"
                                                                aria-required="true">
                                                            <option value="">--- Select Warden ---</option>
                                                        </select>
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-1">
                                            <div class="input-group">
                                                <label class="control-label ">Hostel Select </label>
                                                <select id="Hostel" class="form-control" name="Subject">
                                                    <option value="">1</option>
                                                    <option value="">2</option>
                                                </select>
                                                <span class="input-group-btn ">
                                 <button class="btn btn search-button" type="button">
                                 <i class="fa fa-search font-size22"></i>
                                 </button>
                                 </span>
                                                <div class="help-block"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="row">

                                        <div class="col-sm-2 no-padding tbr-border" style="background-color: #DDEBF7">
                                            <div class="col-sm-12 tbr-head no-padding">&nbsp;</div>
                                            <div class="col-sm-12 text-center" style="height: 130px">
                                                <p style="margin-top: 40px">
                                                    <input type="checkbox"/>
                                                    Floor-1</p>

                                            </div>
                                        </div>
                                        <div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div>


                                        <!--
                                        <div class="col-md-2">
                                            <p style="text-align:left; color: white; padding: 8px; background-color: #5B9BD5">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                Room
                                                <span style="float:right;">
                                 <input type="text" placeholder="1212">
                                 </span>
                                            </p>
                                            <div class="demo-content">
                                                Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <p style="text-align:left; color: white; padding: 8px; background-color: #5B9BD5">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                Room
                                                <span style="float:right;">
                                 <input type="text" placeholder="1212">
                                 </span>
                                            </p>
                                            <div class="demo-content">
                                                Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                            </div>
                                        </div>-->

                                    </div>
                                    <div class="row">

                                        <div class="col-sm-2 no-padding tbr-border" style="background-color: #DDEBF7">
                                            <div class="col-sm-12 tbr-head no-padding">&nbsp;</div>
                                            <div class="col-sm-12 text-center" style="height: 130px">
                                                <p style="margin-top: 40px">
                                                    <input type="checkbox"/>
                                                    Floor-1</p>

                                            </div>
                                        </div>
                                        <div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div>


                                        <!--
                                        <div class="col-md-2">
                                            <p style="text-align:left; color: white; padding: 8px; background-color: #5B9BD5">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                Room
                                                <span style="float:right;">
                                 <input type="text" placeholder="1212">
                                 </span>
                                            </p>
                                            <div class="demo-content">
                                                Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <p style="text-align:left; color: white; padding: 8px; background-color: #5B9BD5">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                Room
                                                <span style="float:right;">
                                 <input type="text" placeholder="1212">
                                 </span>
                                            </p>
                                            <div class="demo-content">
                                                Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                            </div>
                                        </div>-->

                                    </div>
                                    <div class="row">

                                        <div class="col-sm-2 no-padding tbr-border" style="background-color: #DDEBF7">
                                            <div class="col-sm-12 tbr-head no-padding">&nbsp;</div>
                                            <div class="col-sm-12 text-center" style="height: 130px">
                                                <p style="margin-top: 40px">
                                                    <input type="checkbox"/>
                                                    Floor-1</p>

                                            </div>
                                        </div>
                                        <div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div>


                                        <!--
                                        <div class="col-md-2">
                                            <p style="text-align:left; color: white; padding: 8px; background-color: #5B9BD5">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                Room
                                                <span style="float:right;">
                                 <input type="text" placeholder="1212">
                                 </span>
                                            </p>
                                            <div class="demo-content">
                                                Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <p style="text-align:left; color: white; padding: 8px; background-color: #5B9BD5">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                Room
                                                <span style="float:right;">
                                 <input type="text" placeholder="1212">
                                 </span>
                                            </p>
                                            <div class="demo-content">
                                                Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                            </div>
                                        </div>-->

                                    </div>
                                    <div class="row">

                                        <div class="col-sm-2 no-padding tbr-border" style="background-color: #DDEBF7">
                                            <div class="col-sm-12 tbr-head no-padding">&nbsp;</div>
                                            <div class="col-sm-12 text-center" style="height: 130px">
                                                <p style="margin-top: 40px">
                                                    <input type="checkbox"/>
                                                    Floor-1</p>

                                            </div>
                                        </div>
                                        <div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div>


                                        <!--
                                        <div class="col-md-2">
                                            <p style="text-align:left; color: white; padding: 8px; background-color: #5B9BD5">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                Room
                                                <span style="float:right;">
                                 <input type="text" placeholder="1212">
                                 </span>
                                            </p>
                                            <div class="demo-content">
                                                Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <p style="text-align:left; color: white; padding: 8px; background-color: #5B9BD5">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                Room
                                                <span style="float:right;">
                                 <input type="text" placeholder="1212">
                                 </span>
                                            </p>
                                            <div class="demo-content">
                                                Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                            </div>
                                        </div>-->

                                    </div>
                                    <div class="row">

                                        <div class="col-sm-2 no-padding tbr-border" style="background-color: #DDEBF7">
                                            <div class="col-sm-12 tbr-head no-padding">&nbsp;</div>
                                            <div class="col-sm-12 text-center" style="height: 130px">
                                                <p style="margin-top: 40px">
                                                    <input type="checkbox"/>
                                                    Floor-1</p>

                                            </div>
                                        </div>
                                        <div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div>


                                        <!--
                                        <div class="col-md-2">
                                            <p style="text-align:left; color: white; padding: 8px; background-color: #5B9BD5">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                Room
                                                <span style="float:right;">
                                 <input type="text" placeholder="1212">
                                 </span>
                                            </p>
                                            <div class="demo-content">
                                                Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <p style="text-align:left; color: white; padding: 8px; background-color: #5B9BD5">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                Room
                                                <span style="float:right;">
                                 <input type="text" placeholder="1212">
                                 </span>
                                            </p>
                                            <div class="demo-content">
                                                Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                            </div>
                                        </div>-->

                                    </div>
                                    <div class="row">

                                        <div class="col-sm-2 no-padding tbr-border" style="background-color: #DDEBF7">
                                            <div class="col-sm-12 tbr-head no-padding">&nbsp;</div>
                                            <div class="col-sm-12 text-center" style="height: 130px">
                                                <p style="margin-top: 40px">
                                                    <input type="checkbox"/>
                                                    Floor-1</p>

                                            </div>
                                        </div>
                                        <div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div><div class="col-sm-2 no-padding tbr-border">
                                            <div class="col-sm-12 tbr-head no-padding">
                                                <div class="col-sm-6 col-xs-6">
                                                    <input type="checkbox"/> Room1

                                                </div>
                                                <div class="col-sm-6 col-xs-6">
                                                    <input style="width: 100%" type="text" placeholder="101"/>
                                                </div>

                                            </div>
                                            <div class="col-sm-12 col-xs-12 no-padding">
                                                <div class="demo-content">
                                                    Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 2 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 3 : <samp style="color: red">unsigned</samp><br>
                                                    Bed 4 : <samp style="color: red">unsigned</samp><br>

                                                </div>
                                            </div>


                                        </div>


                                        <!--
                                        <div class="col-md-2">
                                            <p style="text-align:left; color: white; padding: 8px; background-color: #5B9BD5">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                Room
                                                <span style="float:right;">
                                 <input type="text" placeholder="1212">
                                 </span>
                                            </p>
                                            <div class="demo-content">
                                                Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <p style="text-align:left; color: white; padding: 8px; background-color: #5B9BD5">
                                                <input type="checkbox" class="custom-control-input" id="customCheck1">
                                                Room
                                                <span style="float:right;">
                                 <input type="text" placeholder="1212">
                                 </span>
                                            </p>
                                            <div class="demo-content">
                                                Bed 1 : <samp style="color: red">unsigned</samp><br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                                Bed 1 : 1<br>
                                            </div>
                                        </div>-->

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