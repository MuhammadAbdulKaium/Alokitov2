
@extends('admin::layouts.master')

@section('styles')
    <style>
        #admin-chart {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .anychart-credits {
            display: none;
        }
        .chart-box {
            height: 300px;
        }
        div#admin-chart,div#admin-chart2,div#admin-chart3,div#admin-chart4,div#admin-chart5,div#admin-chart6,div#admin-chart7,div#admin-chart8,div#admin-chart9,div#admin-chart10,div#admin-chart11,div#admin-chart12 {
            height: 300px;
        }
        #Welcome {
            position: absolute;
            margin: 0px;
            display: inline-block;
            top: 50%;
            transform: translate(0%, -50%);
        }
        #Header {
            position: absolute;
            margin: 0px;
            display: inline-block;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
        }
        #LogOut {
            position: absolute;
            right: 0;
            margin-right: 10px;
            display: inline-block;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
            top: 50%;
            transform: translate(0%, -50%);
        }
        #LogOut:hover {
            color: white;
        }
        #top-bar {
            position: absolute;
            top: 0px;
            left: 0px;
            width: 100%;
            height: 5%;
            max-height: 45px;
            background-color: black;
            color: white;
        }
        .container {
            display: inline-block;
            cursor: pointer;
            margin-left: 10px;
            margin-right: 10px;
        }
        .bar1, .bar2, .bar3 {
            width: 35px;
            height: 5px;
            background-color: white;
            margin: 6px 0;
            transition: 0.4s;
        }
        #left-menu {
            display: none;
            position: absolute;
            background-color: black;
            color: white;
            left: 0;
            top:4.8%;
            height:100%;
            width:25%;
            max-width:270px;
        }
        .change .bar1 {
            -webkit-transform: rotate(-45deg) translate(-9px, 6px);
            transform: rotate(-45deg) translate(-9px, 6px);
        }
        .change .bar2 {opacity: 0;}
        .change .bar3 {
            -webkit-transform: rotate(45deg) translate(-8px, -8px);
            transform: rotate(45deg) translate(-8px, -8px);
        }
        #left-menu h1{
            border-bottom-style: solid;
        }
        #left-menu .inactive {
            font-size: 25px;
            color: white;
            text-decoration: none;
        }
        #left-menu .active {
            font-size: 25px;
            color: rgba(255, 255, 255, 0.5);
            text-decoration: none;
        }
        #left-menu .active:hover {
            color: white;
        }
        #myCanvas {
            position: relative;
            width:100%;
            height:100%;
        }
        #main-content {
            position: absolute;
            color: black;
            left: 0;
            top:4.8%;
            height:95.2%;
            width:100%;
        }
    </style>
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <div class="cadet-baner" style="margin-bottom: 10px;">
                <img src="{{asset('template-2/img/all-cadet.jpg')}}" width="100%">
            </div>
            <div>
                <div class="panel ">
                    <div class="panel-body">
                        <div id="user-profile">
                            <ul id="w2" class="nav-tabs margin-bottom nav">
                                <li class="@if($page == 'institute')active @endif"><a href="/admin/dashboard/statics">All Institute</a></li>
                                <li class="@if($page == 'cadet')active @endif"><a href="/admin/dashboard/cadet/register">Cadet Register</a></li>
                            </ul>
                        </div>
                    </div>
                    <div  class="admin-chart" style="padding: 9px;">
                        <div class="row">
                            <form method="POST" id="std_manage_search_form">
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Select Institute</label>
                                        <select id="inst" class="form-control" name="inst">
                                            <option selected >--- Select ---</option>
                                            @foreach($allInst as $inst)
                                                <option value="{{$inst->id}}">{{$inst->institute_name}}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Campus</label>
                                        <select id="campusId" class="form-control" name="campusId">
                                            <option selected >--- All ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                {{--                                <div class="col-md-1">--}}
                                {{--                                    <div class="form-group">--}}
                                {{--                                        <label class="control-label" for="type">Academic Year</label>--}}
                                {{--                                        <select id="year" class="form-control" name="year">--}}
                                {{--                                            <option selected >--- All ---</option>--}}
                                {{--                                        </select>--}}
                                {{--                                        <div class="help-block"></div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                                <div class="col-sm-1">--}}
                                {{--                                    <div class="form-group">--}}
                                {{--                                        <label class="control-label" for="month">Month</label>--}}
                                {{--                                        <select id="month" class="form-control" name="month">--}}
                                {{--                                            <option value="" selected>All Month</option>--}}
                                {{--                                            <option value="January">January</option>--}}
                                {{--                                            <option value="February">February</option>--}}
                                {{--                                            <option value="March">March</option>--}}
                                {{--                                            <option value="April">April</option>--}}
                                {{--                                            <option value="May">May</option>--}}
                                {{--                                            <option value="June">June</option>--}}
                                {{--                                            <option value="July">July</option>--}}
                                {{--                                            <option value="August">August</option>--}}
                                {{--                                            <option value="September">September</option>--}}
                                {{--                                            <option value="October">October</option>--}}
                                {{--                                            <option value="November">November</option>--}}
                                {{--                                            <option value="December">December</option>--}}
                                {{--                                        </select>--}}
                                {{--                                        <div class="help-block"></div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Academic Level</label>
                                        <select id="levelID" class="form-control" name="levelID">
                                            <option value="" selected disabled>--- Select ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                {{--                                <div class="col-sm-1">--}}
                                {{--                                    <div class="form-group">--}}
                                {{--                                        <label class="control-label" for="type">Division</label>--}}
                                {{--                                        <select id="divisionId" class="form-control" name="divisionId">--}}
                                {{--                                            <option value="" selected disabled>--- Select ---</option>--}}
                                {{--                                        </select>--}}
                                {{--                                        <div class="help-block"></div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Class</label>
                                        <select id="classID" class="form-control" name="classID">
                                            <option value="" selected disabled>--- Select ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Form</label>
                                        <select id="sectionID" class="form-control" name="sectionID">
                                            <option value="" selected disabled>---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="show-for">Show for</label>
                                        <select id="cattype" class="form-control" name="cattype">
                                            <option value="" selected disabled>--- Select ---</option>
                                            @foreach($type as $item)
                                                <option value="{{$item->id}}">{{$item->performance_type}}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="category">Category</label>
                                        <select id="categoryID" class="form-control" name="categoryID">
                                            <option value="" selected disabled>--- Select Category ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="activity">Entity</label>
                                        <select id="categoryActivity" class="form-control" name="categoryActivity">
                                            <option value="" selected disabled>--- Select ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1" style="margin-top:30px">
                                    {{--                                    <div class="form-group">--}}
                                    {{--                                        <label class="control-label" for="type">Cadet Number</label>--}}
                                    {{--                                        <input type="text" class="form-control" name="std_username">--}}
                                    {{--                                        <div class="help-block"></div>--}}
                                    {{--                                    </div>--}}
                                    <input type="submit" name="search">
                                </div>
                                {{--                                <div class="col-sm-1" style="margin-top:30px;">--}}
                                {{--                                    <a href="javascript:void(0)" id="all-search">--}}
                                {{--                                        <i class="fa fa-search fa-2x"></i>--}}
                                {{--                                    </a>--}}
                                {{--                                </div>--}}
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        @if(isset($searchData))
                            @if($searchData->count()>0)
                                @php $i=1; @endphp
                                <table class="table">
                                    <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Photo</th>
                                        <th>Cadet Number</th>
                                        <th>Name</th>
                                        <th>Bengali Name</th>
                                        <th>Blood Group</th>
                                        <th>Admission Year</th>
                                        <th>Academic Year</th>
                                        <th>Academic Level</th>
                                        <th>Division</th>
                                        <th>Class</th>
                                        <th>Form</th>
                                        <th>Roll</th>
                                        <th>Guardian</th>
                                        <th>Mobile</th>
                                        <th>Waiver</th>
                                        <th>Action</th>
                                        <th>Details</th>
                                        <th>History</th>
                                    </tr>

                                    </thead>
                                    <tbody>
                                    @foreach($searchData as $data)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>
                                                {{--								<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$enroll->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">--}}

                                                @if($data->singelAttachment("PROFILE_PHOTO"))
                                                    <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$data->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:50px;height:50px">
                                                @else
                                                    <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:50px;height:50px">
                                                @endif
                                            </td>
                                            <td>{{$data->email}}</td>
                                            <td>{{$data->first_name}} {{$data->last_name}}</td>
                                            <td>{{$data->bn_fullname}}</td>
                                            <td>{{$data->student()->blood_group}}</td>
                                            <td>{{$data->year()->year_name}}</td>
                                            <td>{{$data->year()->year_name}}</td>
                                            <td>{{$data->level()->level_name}}</td>
                                            <td>Division</td>
                                            <td>{{$data->batch()->batch_name}} @if(isset($data->batch()->get_division()->name)) - {{$data->batch()->get_division()->name}}@endif</td>
                                            <td>{{$data->section()->section_name}}</td>
                                            <td>{{$data->gr_no}}</td>
                                            <td>Guardian</td>
                                            <td>{{$data->student()->phone}}</td>
                                            <td> @if(!empty($data->student_waiver()))<span class="label label-success">Active</span> @endif</td>
                                            <td>
                                                {{--                                    @if(!empty($data->student_waiver()))--}}
                                                {{--                                        <a  href="/student/student-waiver/update-waiver/{{$data->student_waiver()->id}}" title="waiver" data-target="#globalModal" data-toggle="modal">--}}
                                                {{--                                            <span class="fa fa-caret-square-o-down fa-lg"></span></a>--}}
                                                {{--                                    @else--}}
                                                {{--                                        <a href="/student/student-waiver/add-waiver/{{$data->std_id}}" title="waiver" data-target="#globalModal" data-toggle="modal">--}}
                                                {{--                                            <span class="fa fa-caret-square-o-down fa-lg"></span>--}}
                                                {{--                                        </a>--}}
                                                {{--                                    @endif--}}
                                                <a  href="{{url('/student/status/'.$data->std_id)}}" title="Student Status" data-target="#globalModal" data-toggle="modal">
                                                    <span id="status_{{$data->std_id}}" class="fa fa-pie-chart fa-4x {{$data->status==1?'text-red':'text-red'}}"></span>
                                                </a>
                                                @php $classTopper = $data->classTopper; @endphp
                                                <a href="{{url('/student/manage/class-top/'.$data->std_id)}}" title="Class Topper" data-target="#globalModal" data-toggle="modal">
                                                    <span id="ct_{{$data->std_id}}" class="fa fa fa-hand-o-up fa-lg {{$classTopper?($classTopper->status==1?'text-red':'text-blue'):'text-blue'}}"></span>
                                                </a>
                                            </td>
                                            <td>Details</td>
                                            <td>Summary</td>
                                        </tr>
                                        @php $i += 1; @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            @else
                                <h5 class="text-center"> <b>Sorry!!! No Result Found</b</h5>
                            @endif
                        @endif
                    </div>
                    <div id="std_list_container_row" class="row">
                        @if(Session::has('success'))
                            <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                            </div>
                        @elseif(Session::has('alert'))
                            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
                            </div>
                        @elseif(Session::has('warning'))
                            <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </section>

    </div>



@endsection

@section('scripts')
    <link href="{{ asset('css/bar.chart.min.css') }}" rel="stylesheet"/>
    <script src='https://d3js.org/d3.v4.min.js'></script>
    <script src="{{asset('js/jquery.bar.chart.min.js')}}"></script>
    {{--    <script src="{{URL::asset('js/any-chartCustom.js') }}"></script>--}}

    {{--    <script src="{{asset('js/pic-chart-js.js')}}"></script>--}}

    {{--    <script src="{{URL::asset('js/pic-chart.js')}}"></script>--}}
    <script src="{{URL::asset('js/alokito-Chart.js')}}"></script>

    <script type="text/javascript">
        var host = window.location.origin;
        $( document ).ready(function() {

            $("#all-search").click(function (){
                show_bar_chart();
            })

            $("#inst").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: host + '/admin/dashboard/campusInstitute/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#campusId").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#campusId").html("");
                }
            });

            $("#levelID").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: host + '/admin/dashboard/academicBatch/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#classID").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#classID").html("");
                }
            });

            $("#campusId").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: host + '/admin/dashboard/academicLevel/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#levelID").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#levelID").html("");
                }
            });

            $("#classID").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: host + '/admin/dashboard/academicSection/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#sectionID").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#sectionID").html("");
                }
            });

            $("#cattype").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: host + '/admin/dashboard/type/category/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            $("#categoryID").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }
                else
                {
                    $("#categoryID").html("");
                }
            });

            $("#categoryID").change(function(){
                if($(this).val() != "")
                {
                    $.ajax({
                        type: "get",
                        url: host + '/admin/dashboard/category/activity/'+ $(this).val(),
                        data: "",
                        dataType: "json",
                        contentType: "application/json; charset=utf-8",
                        success: function (response) {
                            // return response;
                            $("#categoryActivity").html(response);
                        },
                        error: function (XMLHttpRequest, textStatus, errorThrown) {
                            alert(errorThrown);
                            console.log(errorThrown);
                        }
                    });
                }else
                {
                    $("#categoryActivity").html("");
                }
            });
            $(function () {
                // request for parent list using batch section id
                $('form#std_manage_search_form').on('submit', function (e) {
                    e.preventDefault();
                    // ajax request
                    $.ajax({
                        url: "/admin/dashboard/searchcadetData/",
                        type: 'POST',
                        cache: false,
                        data: $('form#std_manage_search_form').serialize(),
                        datatype: 'application/json',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // console.log(data);
                            // hide waiting dialog
                            waitingDialog.hide();
                            // checking
                            if(data.status=='success'){
                                var std_list_container_row = $('#std_list_container_row');
                                std_list_container_row.html('');
                                std_list_container_row.append(data.html);
                            }else{
//                            alert(data.msg)
                            }
                        },

                        error:function(data){
//                        alert(JSON.stringify(data));
                        }
                    });
                });


            });
        });
    </script>

@endsection