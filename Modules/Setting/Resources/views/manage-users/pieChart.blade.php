
@extends('layouts.Highermaster')

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
                                <li class="@if($page == 'institute')active @endif"><a href="/setting/uno/institute/pie">All Institute</a></li>
                                <li class="@if($page == 'cadet')active @endif"><a href="#">Cadet</a></li>
                            </ul>
                        </div>
                    </div>
                    <div  class="admin-chart" style="padding: 9px;">
                        <div class="row">
                            <form action="">
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Select Institute</label>
                                        <select id="type" class="form-control" name="type">
                                            <option value="" selected disabled>--- All Institute ---</option>
                                            <option value="0">Barishal Cadet</option>
                                            <option value="1">Jhinedha Cadet</option>
                                            <option value="2">Gazirhat cadet</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Academic year</label>
                                        <select id="type" class="form-control" name="type">
                                            <option value="" selected disabled>--- Select Year ---</option>
                                            <option value="0">2020</option>
                                            <option value="1">2019</option>
                                            <option value="2">2018</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Month</label>
                                        <select id="type" class="form-control" name="type">
                                            <option value="" selected disabled>--- Select Month ---</option>
                                            <option value="0">January</option>
                                            <option value="1">February</option>
                                            <option value="2">March</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Academic Level</label>
                                        <select id="type" class="form-control" name="type">
                                            <option value="" selected disabled>--- Select ---</option>
                                            <option value="0">Junior High</option>
                                            <option value="1">College</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Division</label>
                                        <select id="type" class="form-control" name="type">
                                            <option value="" selected disabled>--- Select ---</option>
                                            <option value="0">Science</option>
                                            <option value="1">Commerce</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Class</label>
                                        <select id="type" class="form-control" name="type">
                                            <option value="" selected disabled>--- Select ---</option>
                                            <option value="0">10</option>
                                            <option value="1">9</option>
                                            <option value="1">8</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Section</label>
                                        <select id="type" class="form-control" name="type">
                                            <option value="" selected disabled>--- Select ---</option>
                                            <option value="0">ALl</option>
                                            <option value="1">A</option>
                                            <option value="1">B</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Show for</label>
                                        <select id="type" class="form-control" name="type">
                                            <option value="" selected disabled>--- Select ---</option>
                                            <option value="0">Academic</option>
                                            <option value="1">Cariculam</option>
                                            <option value="1">Result</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Category</label>
                                        <select id="type" class="form-control" name="type">
                                            <option value="" selected disabled>--- Select ---</option>
                                            <option value="0">Result</option>
                                            <option value="1">Cariculam</option>
                                            <option value="1">Extra Caricular</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Entity</label>
                                        <select id="type" class="form-control" name="type">
                                            <option value="" selected disabled>--- Select ---</option>
                                            <option value="0">General Science</option>
                                            <option value="1">English</option>
                                            <option value="1">Math</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1">
                                    <div class="form-group">
                                        <label class="control-label" for="type">Cadet Number</label>
                                        <select id="type" class="form-control" name="type">
                                            <option value="" selected disabled>---</option>
                                            <option value="0">Md Rahim</option>
                                            <option value="1">Md Karim</option>
                                            <option value="1">Md Jabbar</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-1" style="margin-top:30px;">
                                    <a href="#">
                                        <i class="fa fa-search"></i>
                                    </a>

                                    <a href="{{url('/setting/uno/institute/pie/compare')}}">
                                        Compare
                                    </a>

                                </div>
                            </form>
                        </div>
                        <div class="row">
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart"></div>
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
                            <div class="col-md-3 chart-box">
                                <div id="admin-chart12"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>

    </div>
@endsection

@section('scripts')

{{--    <script src="{{asset('js/pic-chart-js.js')}}"></script>--}}

{{--    <script src="{{URL::asset('js/pic-chart.js')}}"></script>--}}
    <script src="{{URL::asset('js/alokito-Chart.js')}}"></script>

@endsection
