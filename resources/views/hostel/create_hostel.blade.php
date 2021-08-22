@extends('admin::layouts.master')

{{-- Web site Title --}}

@section('styles')

@stop


{{-- Content --}}
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1><i class="fa fa-plus-square"></i> Hostel Management - Hostel Create</h1>

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
                    {{--<ul class="nav-tabs margin-bottom nav" id="">
                        <li id="#">
                            <a href="http://127.0.0.1:8000/fees/onlineacedemic/classtopic">Class Topic</a>
                        </li>
                        <li id="#">
                            <a href="http://127.0.0.1:8000/fees/onlineacedemic/Online Class Histrory">Class Histrory </a>
                        </li>

                        <li id="#">
                            <a href="http://127.0.0.1:8000/fees/onlineacedemic/Online Class">Online Class</a>
                        </li>

                        <li id="#">
                            <a href="http://127.0.0.1:8000/fees/onlineacedemic/Question Bank">Question bank</a>
                        </li>
                        <li id="#">
                            <a href="#">Assignment</a>
                        </li>


                        <li id="#">
                            <a href="http://127.0.0.1:8000/fees/onlineacedemic/Exam">Exam</a>
                        </li>


                    </ul>--}}
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
                                        <div class="col-sm-3">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">Hostel Building</label>
                                                        <input type="text" i="" class="form-control" name="" maxlength="250" placeholder="write a hostel name" aria-required="true">
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">Building ID</label>
                                                        <input type="text" i="" class="form-control" name="" maxlength="250" placeholder="ID" aria-required="true">
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
                                                        <label class="control-label label-margin50">Hostel/Building Description</label>
                                                        <input type="text" i="" class="form-control" name="" maxlength="250" placeholder="Write a Clear Description of the Hostel" aria-required="true">
                                                        <div class="help-block"></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">For Gender</label>
                                                        <input type="text" i="" class="form-control" name="" maxlength="250" placeholder="Write a Clear Description of the Hostel" aria-required="true">
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
                                                        <input type="text" i="" class="form-control" name="" maxlength="250" placeholder="Gender" aria-required="true">
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">of Floors</label>
                                                        <input type="text" i="" class="form-control" name="" maxlength="250" placeholder="Floors" aria-required="true">
                                                        <div class="help-block">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-4">
                                                    <div class="form-group">
                                                        <label class="control-label label-margin50">Of Rooms</label>
                                                        <input type="text" i="" class="form-control" name="" maxlength="250" placeholder="Rooms" aria-required="true">
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
                                                        <label class="control-label label-margin50">Hostel Warden</label>

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

                                {{--<div class="box-body table-responsive">
                                    <div id="p0" data-pjax-container="" data-pjax-push-state=""
                                         data-pjax-timeout="10000">
                                        <div id="w1" class="grid-view">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                <tr>
                                                    <th><a data-sort="sub_master_name">
                                                            Sl#</a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_code">Date
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias">
                                                            Routine Time
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias">Conduct Time
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias">
                                                            Topic
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias">
                                                            Subjcet
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias">
                                                            Class
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias">
                                                            Section
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias">
                                                            Teacher
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias">
                                                            Duration(Minutes)
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias">
                                                            Total
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias">
                                                            P
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias redcolor">
                                                            A
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias redcolor">
                                                            L
                                                        </a>
                                                    </th>

                                                    <th>
                                                        <a data-sort="sub_master_alias">
                                                            Remarks
                                                        </a>
                                                    </th>
                                                    <th>
                                                        <a data-sort="sub_master_alias">
                                                            Status
                                                        </a>
                                                    </th>

                                                </tr>

                                                </thead>
                                                <tbody>
                                                <tr class="gradeX">
                                                    <td>1</td>
                                                    <td>01/01/2020</td>
                                                    <td>8.00AM-9.00AM</td>
                                                    <td><strong class="redcolor">8.15AM-9.45AMM</strong></td>
                                                    <td>English Grammar Noun,Pronoun,Adjcetive,Verb</td>
                                                    <td>English 2nd Paper</td>
                                                    <td>Class 8</td>
                                                    <td>Pankourl</td>
                                                    <td>Flora Sarker<br><strong class="redcolor">(Late In ,Ealry
                                                            End)</strong></td>
                                                    <td>62</td>
                                                    <td>55</td>
                                                    <td>54</td>
                                                    <td class="redcolor">1</td>
                                                    <td class="redcolor">5</td>
                                                    <td>Sumi Haque Join the Session</td>
                                                    <td style="color: green">Over</td>

                                                </tr>
                                                <tr class="gradeX">
                                                    <td>2</td>
                                                    <td>01/01/2020</td>
                                                    <td>8.00AM-9.00AM</td>
                                                    <td>8.00AM-9.00AM</td>
                                                    <td>Bangla Bakaron</td>
                                                    <td>Bangla 2nd Paper</td>
                                                    <td>Class 8</td>
                                                    <td>Pankourl</td>
                                                    <td>Flora Sarker</td>
                                                    <td class="redcolor">55</td>
                                                    <td>55</td>
                                                    <td>50</td>
                                                    <td class="redcolor">1</td>
                                                    <td class="redcolor">4</td>
                                                    <td>4 Student late Due to PC issu</td>
                                                    <td style="color: green">Over</td>

                                                </tr>
                                                <tr class="gradeX">
                                                    <td>3</td>
                                                    <td>01/01/2020</td>
                                                    <td>8.00AM-9.00AM</td>
                                                    <td> 9.15AM-<strong class="redcolor">9.45AM </strong></td>
                                                    <td>Bangla bakoron</td>
                                                    <td>English 2nd Paper</td>
                                                    <td>Class 8</td>
                                                    <td>Pankourl</td>
                                                    <td>Flora Sarker<strong class="redcolor">(Early End)</strong></td>
                                                    <td>62</td>
                                                    <td>55</td>
                                                    <td>54</td>
                                                    <td class="redcolor">1</td>
                                                    <td class="redcolor">5</td>
                                                    <td>Sumi Haque Join the Session</td>
                                                    <td style="color: green">Over</td>

                                                </tr>
                                                <tr class="gradeX">
                                                    <td>3</td>
                                                    <td>01/01/2020</td>
                                                    <td>8.00AM-9.00AM</td>
                                                    <td><strong class="redcolor">9.15AM </strong> - 9.55AM</td>
                                                    <td>Bangla bakoron</td>
                                                    <td>English 2nd Paper</td>
                                                    <td>Class 8</td>
                                                    <td>Pankourl</td>
                                                    <td>Aeysha Salma <strong class="redcolor">(Late In)</strong></td>
                                                    <td>62</td>
                                                    <td>55</td>
                                                    <td>54</td>
                                                    <td class="redcolor">1</td>
                                                    <td style="color: red">5</td>
                                                    <td>Sumi Haque Join the Session</td>
                                                    <td style="color:green">Over</td>

                                                </tr>
                                                <tr class="gradeX">
                                                    <td>3</td>
                                                    <td>02/01/2020</td>
                                                    <td>9.00AM-10.00AM</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>English 2nd Paper</td>
                                                    <td>Class 8</td>
                                                    <td>Pankourl</td>
                                                    <td>Flora Sarker</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="color:black">UnScheduled</td>

                                                </tr>
                                                <tr class="gradeX">
                                                    <td>3</td>
                                                    <td>02/01/2020</td>
                                                    <td>9.00AM-10.00AM</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>English 2nd Paper</td>
                                                    <td>Class 8</td>
                                                    <td>Pankourl</td>
                                                    <td>Flora Sarker</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="color: black">UnScheduled</td>

                                                </tr>

                                                <tr class="gradeX">
                                                    <td>3</td>
                                                    <td>02/01/2020</td>
                                                    <td>9.00AM-10.00AM</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>English 2nd Paper</td>
                                                    <td>Class 8</td>
                                                    <td>Pankourl</td>
                                                    <td>Flora Sarker</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="color: green">Scheduled</td>

                                                </tr>

                                                <tr class="gradeX">
                                                    <td>3</td>
                                                    <td>02/01/2020</td>
                                                    <td>9.00AM-10.00AM</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td>English 2nd Paper</td>
                                                    <td>Class 8</td>
                                                    <td>Pankourl</td>
                                                    <td>Aeysha Siddiqu</td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td style="color: green">Scheduled</td>

                                                </tr>

                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="link" style="float: right">

                                        </div>
                                    </div>

                                </div>--}}
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