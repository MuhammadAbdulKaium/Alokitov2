@extends('layouts.master')
<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-dashboard"></i> Dashboard | <small>Enquiry</small>        </h1>
            <ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
                <li class="active">Enquiry</li>
            </ul>    </section>
        <section class="content">

            <!--- 1st Row Block--->
            <div class="row">
                <!---Start Count Active Student--->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <!-- small box -->
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="fa fa-users"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text" title="" data-toggle="tooltip" data-original-title="Total Applicants">Total Applicants</span>
                            <span class="info-box-number">
                    7                </span>
                        </div><!-- /.info-box-content -->
                    </div>
                </div>

                <!---Start Count Deactive Student--->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <!-- small box -->
                    <div class="info-box">
                        <span class="info-box-icon bg-green"><i class="fa fa-user-plus"></i></span>
                        <div class="info-box-content">
               	<span class="info-box-text" title="" data-toggle="tooltip" data-original-title="Approved Enquiry">
                    Approved Enquiry                </span>
                            <span class="info-box-number">
                    4                </span>
                        </div>
                    </div>
                </div>

                <!---Start Count Active Employee--->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <!-- small box -->
                    <div class="info-box">
                        <span class="info-box-icon bg-yellow"><i class="glyphicon glyphicon-hourglass"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text" title="" data-toggle="tooltip" data-original-title="Pending Enquiry">Pending Enquiry</span>
                            <span class="info-box-number">
                    1                </span>
                        </div>
                    </div>
                </div>

                <!---Start Count Deactive Employee--->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <!-- small box -->
                    <div class="info-box">
                        <span class="info-box-icon bg-red"><i class="glyphicon glyphicon-time"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text" title="" data-toggle="tooltip" data-original-title="Waiting Enquiry">Waiting Enquiry</span>
                            <span class="info-box-number">
                    1                </span>
                        </div>
                    </div>
                </div>

            </div>
            <!--- /.row 1st Row Block--->

            <!--- 2nd Row Year Wise Admissions Block / Appoval Status Wise Admissions--->
            <div class="row">
                <div class="col-sm-12"> <!--  Year Wise Admissions Block -->
                    <div class="box box-solid">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="glyphicon glyphicon-stats"></i> Course Wise Approved Enquiry</h3>
                            <div class="box-tools pull-right">
                                <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="w0" data-highcharts-chart="0"><div class="highcharts-container" id="highcharts-0" style="position: relative; overflow: hidden; width: 1240px; height: 400px; text-align: left; line-height: normal; z-index: 0; left: 0px; top: 0.299988px;"><svg version="1.1" style="font-family:&quot;Lucida Grande&quot;, &quot;Lucida Sans Unicode&quot;, Arial, Helvetica, sans-serif;font-size:12px;" xmlns="http://www.w3.org/2000/svg" width="1240" height="400"><desc>Created with Highcharts 4.1.5</desc><defs><clipPath id="highcharts-1"><rect x="0" y="0" width="1175" height="299"></rect></clipPath></defs><rect x="0" y="0" width="1240" height="400" strokeWidth="0" fill="#FFFFFF" class=" highcharts-background"></rect><g class="highcharts-grid" zIndex="1"></g><g class="highcharts-grid" zIndex="1"><path fill="none" d="M 55 309.5 L 1230 309.5" stroke="#D8D8D8" stroke-width="1" zIndex="1" opacity="1"></path><path fill="none" d="M 55 249.5 L 1230 249.5" stroke="#D8D8D8" stroke-width="1" zIndex="1" opacity="1"></path><path fill="none" d="M 55 189.5 L 1230 189.5" stroke="#D8D8D8" stroke-width="1" zIndex="1" opacity="1"></path><path fill="none" d="M 55 130.5 L 1230 130.5" stroke="#D8D8D8" stroke-width="1" zIndex="1" opacity="1"></path><path fill="none" d="M 55 70.5 L 1230 70.5" stroke="#D8D8D8" stroke-width="1" zIndex="1" opacity="1"></path><path fill="none" d="M 55 9.5 L 1230 9.5" stroke="#D8D8D8" stroke-width="1" zIndex="1" opacity="1"></path></g><g class="highcharts-axis" zIndex="2"><path fill="none" d="M 348.5 309 L 348.5 319" stroke="#C0D0E0" stroke-width="1" opacity="1"></path><path fill="none" d="M 642.5 309 L 642.5 319" stroke="#C0D0E0" stroke-width="1" opacity="1"></path><path fill="none" d="M 935.5 309 L 935.5 319" stroke="#C0D0E0" stroke-width="1" opacity="1"></path><path fill="none" d="M 1230.5 309 L 1230.5 319" stroke="#C0D0E0" stroke-width="1" opacity="1"></path><path fill="none" d="M 54.5 309 L 54.5 319" stroke="#C0D0E0" stroke-width="1" opacity="1"></path><text x="642.5" zIndex="7" text-anchor="middle" transform="translate(0,0)" class=" highcharts-xaxis-title" style="color:#707070;fill:#707070;" visibility="visible" y="346"><tspan style="font-weight:bold">Courses</tspan></text><path fill="none" d="M 55 309.5 L 1230 309.5" stroke="#C0D0E0" stroke-width="1" zIndex="7" visibility="visible"></path></g><g class="highcharts-axis" zIndex="2"><text x="24" zIndex="7" text-anchor="middle" transform="translate(0,0) rotate(270 24 159.5)" class=" highcharts-yaxis-title" style="color:#707070;fill:#707070;" visibility="visible" y="159.5"><tspan>Total Students</tspan></text></g><g class="highcharts-series-group" zIndex="3"><g class="highcharts-series highcharts-tracker" visibility="visible" zIndex="0.1" transform="translate(55,10) scale(1 1)" style="" clip-path="url(#highcharts-1)"><rect x="94" y="300" width="106" height="0" fill="#7cb5ec" rx="0" ry="0"></rect><rect x="388" y="300" width="106" height="0" fill="#7cb5ec" rx="0" ry="0"></rect><rect x="682" y="300" width="106" height="0" fill="#7cb5ec" rx="0" ry="0"></rect><rect x="975" y="61" width="106" height="239" fill="#7cb5ec" rx="0" ry="0"></rect></g><g class="highcharts-markers" visibility="visible" zIndex="0.1" transform="translate(55,10) scale(1 1)" clip-path="none"></g></g><g class="highcharts-legend" zIndex="7" transform="translate(561,360)"><g zIndex="1"><g><g class="highcharts-legend-item" zIndex="1" transform="translate(8,3)"><text x="21" style="color:#333333;font-size:12px;font-weight:bold;cursor:pointer;fill:#333333;" text-anchor="start" zIndex="2" y="15"><tspan>Year - 2016 (4)</tspan></text><rect x="0" y="4" width="16" height="12" zIndex="3" fill="#7cb5ec"></rect></g></g></g></g><g class="highcharts-axis-labels highcharts-xaxis-labels" zIndex="7"><text x="201.875" style="color:#606060;cursor:default;font-size:11px;fill:#606060;width:295px;text-overflow:clip;" text-anchor="middle" transform="translate(0,0)" y="328" opacity="1">CF</text><text x="495.625" style="color:#606060;cursor:default;font-size:11px;fill:#606060;width:295px;text-overflow:clip;" text-anchor="middle" transform="translate(0,0)" y="328" opacity="1">Secondary</text><text x="789.375" style="color:#606060;cursor:default;font-size:11px;fill:#606060;width:295px;text-overflow:clip;" text-anchor="middle" transform="translate(0,0)" y="328" opacity="1">Primary</text><text x="1083.125" style="color:#606060;cursor:default;font-size:11px;fill:#606060;width:295px;text-overflow:clip;" text-anchor="middle" transform="translate(0,0)" y="328" opacity="1">Preschool</text></g><g class="highcharts-axis-labels highcharts-yaxis-labels" zIndex="7"><text x="40" style="color:#606060;cursor:default;font-size:11px;fill:#606060;width:399px;text-overflow:clip;" text-anchor="end" transform="translate(0,0)" y="314" opacity="1">0</text><text x="40" style="color:#606060;cursor:default;font-size:11px;fill:#606060;width:399px;text-overflow:clip;" text-anchor="end" transform="translate(0,0)" y="254" opacity="1">1</text><text x="40" style="color:#606060;cursor:default;font-size:11px;fill:#606060;width:399px;text-overflow:clip;" text-anchor="end" transform="translate(0,0)" y="194" opacity="1">2</text><text x="40" style="color:#606060;cursor:default;font-size:11px;fill:#606060;width:399px;text-overflow:clip;" text-anchor="end" transform="translate(0,0)" y="135" opacity="1">3</text><text x="40" style="color:#606060;cursor:default;font-size:11px;fill:#606060;width:399px;text-overflow:clip;" text-anchor="end" transform="translate(0,0)" y="75" opacity="1">4</text><text x="40" style="color:#606060;cursor:default;font-size:11px;fill:#606060;width:399px;text-overflow:clip;" text-anchor="end" transform="translate(0,0)" y="15" opacity="1">5</text></g><g class="highcharts-tooltip" zIndex="8" style="cursor:default;padding:0;white-space:nowrap;" transform="translate(0,-9999)"><path fill="none" d="M 3 0 L 13 0 C 16 0 16 0 16 3 L 16 13 C 16 16 16 16 13 16 L 3 16 C 0 16 0 16 0 13 L 0 3 C 0 0 0 0 3 0" isShadow="true" stroke="black" stroke-opacity="0.049999999999999996" stroke-width="5" transform="translate(1, 1)"></path><path fill="none" d="M 3 0 L 13 0 C 16 0 16 0 16 3 L 16 13 C 16 16 16 16 13 16 L 3 16 C 0 16 0 16 0 13 L 0 3 C 0 0 0 0 3 0" isShadow="true" stroke="black" stroke-opacity="0.09999999999999999" stroke-width="3" transform="translate(1, 1)"></path><path fill="none" d="M 3 0 L 13 0 C 16 0 16 0 16 3 L 16 13 C 16 16 16 16 13 16 L 3 16 C 0 16 0 16 0 13 L 0 3 C 0 0 0 0 3 0" isShadow="true" stroke="black" stroke-opacity="0.15" stroke-width="1" transform="translate(1, 1)"></path><path fill="rgba(249, 249, 249, .85)" d="M 3 0 L 13 0 C 16 0 16 0 16 3 L 16 13 C 16 16 16 16 13 16 L 3 16 C 0 16 0 16 0 13 L 0 3 C 0 0 0 0 3 0"></path><text x="8" zIndex="1" style="font-size:12px;color:#333333;fill:#333333;" transform="translate(0,20)"></text></g></svg></div></div>                    	</div> <!--End Body--->
                    </div> <!--End Box Info-->
                </div> <!-- /. Year Wise Admssion Block-->
            </div>

            <!--- 3rd Row Recently Approved Student's Admissions Block--->
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-history" aria-hidden="true"></i> Recently Added        </h3>
                    <div class="box-tools pull-right">
                        <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div id="w1" class="grid-view"><table class="table table-striped table-bordered"><thead>
                            <tr><th>#</th><th></th><th>Registration No</th><th>Name</th><th>Academic Year</th><th>Course</th><th>Batch</th><th>Email Id</th><th>Enquiry Status</th></tr>
                            </thead>
                            <tbody>
                            <tr data-key="7"><td>1</td><td><img class="img-circle img-sm" src="/admission/stu-admission-master/image?name=no-photo.png" alt="NA"></td><td class="text-center">7</td><td><a href="/admission/stu-admission-master/view?id=7">asdasdasd asdasdasd</a></td><td>2016-17</td><td>Preschool</td><td>Kindergarten1</td><td><a href="mailto:asdas232d@gmail.com">asdas232d@gmail.com</a></td><td class="text-center"><span class="label label-primary">Pending</span></td></tr>
                            <tr data-key="6"><td>2</td><td><img class="img-circle img-sm" src="/admission/stu-admission-master/image?name=no-photo.png" alt="NA"></td><td class="text-center">6</td><td><a href="/admission/stu-admission-master/view?id=6">Sentiago Jones</a></td><td>2016-17</td><td>Preschool</td><td>Kindergarten1</td><td><a href="mailto:sentiago@demo.com">sentiago@demo.com</a></td><td class="text-center"><span class="label label-success">Approved</span></td></tr>
                            <tr data-key="5"><td>3</td><td><img class="img-circle img-sm" src="/admission/stu-admission-master/image?name=no-photo.png" alt="NA"></td><td class="text-center">5</td><td><a href="/admission/stu-admission-master/view?id=5">Mason White</a></td><td>2016-17</td><td>Preschool</td><td>Kindergarten1</td><td><a href="mailto:mason@demo.com">mason@demo.com</a></td><td class="text-center"><span class="label label-success">Approved</span></td></tr>
                            <tr data-key="4"><td>4</td><td><img class="img-circle img-sm" src="/admission/stu-admission-master/image?name=no-photo.png" alt="NA"></td><td class="text-center">4</td><td><a href="/admission/stu-admission-master/view?id=4">Camden Clark</a></td><td>2016-17</td><td>Primary</td><td>Grade1</td><td><a href="mailto:camden@demo.com">camden@demo.com</a></td><td class="text-center"><span class="label label-warning">Waiting</span></td></tr>
                            <tr data-key="3"><td>5</td><td><img class="img-circle img-sm" src="/admission/stu-admission-master/image?name=no-photo.png" alt="NA"></td><td class="text-center">3</td><td><a href="/admission/stu-admission-master/view?id=3">Quinn Thompson</a></td><td>2016-17</td><td>Preschool</td><td>Kindergarten1</td><td><a href="mailto:quinn@demo.org">quinn@demo.org</a></td><td class="text-center"><span class="label label-danger">Disapproved</span></td></tr>
                            <tr data-key="2"><td>6</td><td><img class="img-circle img-sm" src="/admission/stu-admission-master/image?name=no-photo.png" alt="NA"></td><td class="text-center">2</td><td><a href="/admission/stu-admission-master/view?id=2">Theo Roy</a></td><td>2016-17</td><td>Preschool</td><td>Kindergarten1</td><td><a href="mailto:theo@demo.com">theo@demo.com</a></td><td class="text-center"><span class="label label-success">Approved</span></td></tr>
                            <tr data-key="1"><td>7</td><td><img class="img-circle img-sm" src="/admission/stu-admission-master/image?name=no-photo.png" alt="NA"></td><td class="text-center">1</td><td><a href="/admission/stu-admission-master/view?id=1">Amelia Pelletier</a></td><td>2016-17</td><td>Preschool</td><td>Kindergarten1</td><td><a href="mailto:amelia@demo.com">amelia@demo.com</a></td><td class="text-center"><span class="label label-success">Approved</span></td></tr>
                            </tbody></table></div>    </div> <!--End Body--->
            </div> <!--End Box Info-->
        </section>
    </div>
@endsection

@section('page-script')

    <script src="{{URL::asset('js/charts/highchart.js')}}"></script>


@endsection
