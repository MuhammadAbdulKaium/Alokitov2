
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
                                {{--                                <li class="@if($page == 'cadet')active @endif"><a href="#">Cadet</a></li>--}}
                            </ul>
                        </div>
                    </div>
                    <div  class="admin-chart" style="padding: 9px;">
                        <div class="row">
                            <form action="">
                                {{--                                <div class="col-sm-2">--}}
                                {{--                                    <div class="form-group">--}}
                                {{--                                        <label class="control-label" for="type">Select Institute</label>--}}
                                {{--                                        <select id="inst" class="form-control" name="type">--}}
                                {{--                                            <option selected >--- All ---</option>--}}
                                {{--                                            @foreach($allInst as $inst)--}}
                                {{--                                            <option value="{{$inst->id}}">{{$inst->institute_name}}</option>--}}
                                {{--                                            @endforeach--}}
                                {{--                                        </select>--}}
                                {{--                                        <div class="help-block"></div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Academic Year</label>
                                        <select id="year" class="form-control" name="year">
                                            <option value="2020" >2020</option>
                                            <option value="2019" selected>2019</option>
                                            <option value="2018" >2018</option>
                                            <option value="2017" >2017</option>
                                            <option value="2016" >2016</option>
                                            <option value="2015" >2015</option>
                                            <option value="2014" >2014</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="month">Month</label>
                                        <select id="month" class="form-control" name="month">
                                            <option value="" selected>All Month</option>
                                            <option value="January">January</option>
                                            <option value="February">February</option>
                                            <option value="March">March</option>
                                            <option value="April">April</option>
                                            <option value="May">May</option>
                                            <option value="June">June</option>
                                            <option value="July">July</option>
                                            <option value="August">August</option>
                                            <option value="September">September</option>
                                            <option value="October">October</option>
                                            <option value="November">November</option>
                                            <option value="December">December</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                {{--                                <div class="col-sm-1">--}}
                                {{--                                    <div class="form-group">--}}
                                {{--                                        <label class="control-label" for="type">Academic Level</label>--}}
                                {{--                                        <select id="type" class="form-control" name="type">--}}
                                {{--                                            <option value="" selected disabled>--- Select ---</option>--}}
                                {{--                                            <option value="0">Junior High</option>--}}
                                {{--                                            <option value="1">College</option>--}}
                                {{--                                        </select>--}}
                                {{--                                        <div class="help-block"></div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                                <div class="col-sm-1">--}}
                                {{--                                    <div class="form-group">--}}
                                {{--                                        <label class="control-label" for="type">Division</label>--}}
                                {{--                                        <select id="type" class="form-control" name="type">--}}
                                {{--                                            <option value="" selected disabled>--- Select ---</option>--}}
                                {{--                                            <option value="0">Science</option>--}}
                                {{--                                            <option value="1">Commerce</option>--}}
                                {{--                                        </select>--}}
                                {{--                                        <div class="help-block"></div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                                <div class="col-sm-2">--}}
                                {{--                                    <div class="form-group">--}}
                                {{--                                        <label class="control-label" for="type">Class</label>--}}
                                {{--                                        <select id="cadet-class" class="form-control" name="class">--}}
                                {{--                                            <option value="" selected disabled>--- Select ---</option>--}}
                                {{--                                            <option value="0">Class 12</option>--}}
                                {{--                                            <option value="0">Class 11</option>--}}
                                {{--                                            <option value="0">Class 10</option>--}}
                                {{--                                            <option value="0">Class 9</option>--}}
                                {{--                                            <option value="1">Class 8</option>--}}
                                {{--                                            <option value="1">Class 7</option>--}}
                                {{--                                        </select>--}}
                                {{--                                        <div class="help-block"></div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                {{--                                <div class="col-sm-1">--}}
                                {{--                                    <div class="form-group">--}}
                                {{--                                        <label class="control-label" for="type">Section</label>--}}
                                {{--                                        <select id="section" class="form-control" name="section">--}}
                                {{--                                            <option value="" selected disabled>---</option>--}}
                                {{--                                            <option value="0">All</option>--}}
                                {{--                                            <option value="1">A</option>--}}
                                {{--                                            <option value="1">B</option>--}}
                                {{--                                        </select>--}}
                                {{--                                        <div class="help-block"></div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="show-for">Show Analysis for</label>
                                        <select id="cattype" class="form-control" name="show-for">
                                            <option value="" selected disabled>--- Select ---</option>
                                            @foreach($type as $item)
                                                <option value="{{$item->id}}">{{$item->performance_type}}</option>
                                            @endforeach
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="category">Category</label>
                                        <select id="categoryID" class="form-control" name="category">
                                            <option value="" selected disabled>--- Select Category ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="form-group">
                                        <label class="control-label" for="activity">Entity</label>
                                        <select id="categoryActivity" class="form-control" name="activity">
                                            <option value="" selected disabled>--- Select ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="view_type">Type</label>
                                        <select id="view_type" class="form-control" name="view_type">
                                            <option value="details" selected>Details</option>
                                            <option value="summary">Summary</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                {{--                                <div class="col-sm-2">--}}
                                {{--                                    <div class="form-group">--}}
                                {{--                                        <label class="control-label" for="type">Cadet Number</label>--}}
                                {{--                                        <input type="text" class="form-control">--}}
                                {{--                                        <div class="help-block"></div>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                                <div class="col-sm-1" style="margin-top:30px;">
                                    <a href="javascript:void(0)" id="all-search">
                                        <i class="fa fa-search fa-2x"></i>
                                    </a>

                                    {{--                                    <a href="{{url('/setting/uno/institute/pie/compare')}}">--}}
                                    {{--                                        Compare--}}
                                    {{--                                    </a>--}}

                                </div>
                            </form>
                        </div>
                        <div class="row" id="pie-chart" style="display: none;">
                            <div class="col-md-1 chart-box">
                                <div id="admin-chart0"></div>
                            </div>
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart1"></div>
                            </div>
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart2"></div>
                            </div>
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart3"></div>
                            </div>
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart4"></div>
                            </div>
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart5"></div>
                            </div>
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart6"></div>
                            </div>
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart7"></div>
                            </div>
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart8"></div>
                            </div>
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart9"></div>
                            </div>
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart10"></div>
                            </div>
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart11"></div>
                            </div>
                        </div>
                        <div class="row" id="bar-chart">
                            <div class="col-md-4" >
                                <div id="chtAnimatedBarChart0" class="bcBar"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="chtAnimatedBarChart1" class="bcBar"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="chtAnimatedBarChart2" class="bcBar"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="chtAnimatedBarChart3" class="bcBar"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="chtAnimatedBarChart4" class="bcBar"></div>
                            </div>
                            <div class="col-md-4" >
                                <div id="chtAnimatedBarChart5" class="bcBar"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="chtAnimatedBarChart6" class="bcBar"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="chtAnimatedBarChart7" class="bcBar"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="chtAnimatedBarChart8" class="bcBar"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="chtAnimatedBarChart9" class="bcBar"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="chtAnimatedBarChart10" class="bcBar"></div>
                            </div>
                            <div class="col-md-4">
                                <div id="chtAnimatedBarChart11" class="bcBar"></div>
                            </div>

                        </div>

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
        });

        function show_bar_chart()
        {
            let year = $("#year").val();
            let month = $("#month").val();
            let cadetClass = $("#cadet-class").val();
            let categoryType = $("#cattype").val();
            let categoryID = $("#categoryID").val();
            let categoryActivity = $("#categoryActivity").val();
            let viewtType = $("#view_type").val();
            let _token   = '<?php echo csrf_token() ?>';

            $.ajax({
                url: '/high-admin/academic/barchart',
                type:"POST",
                data:{
                    year:year,
                    month:month,
                    cadetClass:cadetClass,
                    categoryType:categoryType,
                    categoryID:categoryID,
                    categoryActivity:categoryActivity,
                    viewtType:viewtType,
                    _token: _token
                },
                success: function (response) {
                    console.log(response);

                    for (i = 0; i < response.length; i++) {
                        if(response[i].graph_type == "pie chart")
                        {
                            console.log(response[i].institute);
                            anychart.onDocumentReady(function () {
                                // create pie chart with passed data
                                var chart = anychart.pie3d([
                                    ['Northfarthing', 235],
                                    ['Westfarthing', 552],
                                    ['Eastfarthing', 491],
                                    ['Southfarthing', 619],
                                    ['Buckland', 388],
                                    ['Westmarch', 1105]
                                ]);

                                // set chart title text settings
                                chart
                                    .title(response[i].institute)
                                    // set chart radius
                                    .radius('100%');

                                // set container id for the chart
                                chart.container('admin-chart'+ i);
                                // initiate chart drawing
                                chart.draw();
                            });

                            if(response.length == i)
                            {
                                $("#pie-chart").show();
                            }
                        }
                        else
                        {
                            $("#chtAnimatedBarChart" + i).html("");
                            $("#chtAnimatedBarChart" + i).animatedBarChart({ data: response[i].data });
                        }

                    }


                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                    console.log(errorThrown);
                }
            });
        }
    </script>

@endsection