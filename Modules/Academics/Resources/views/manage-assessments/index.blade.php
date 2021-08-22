
@extends('layouts.master')

<!-- page content -->
@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                <i class="fa fa-plus-square"></i> Manage Assessments
            </h1>
            <ul class="breadcrumb">
                <li><a href="{{URL::to('home')}}"><i class="fa fa-home"></i>Home</a></li>
                <li><a href="{{URL::to('academics')}}">Academics</a></li>
                <li class="active">Manage Assessment</li>
            </ul>
        </section>
        <section class="content">
            @if(Session::has('success'))
                <div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
                </div>
            @elseif(Session::has('warning'))
                <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
                </div>
            @endif


            <div class="panel panel-default">
                <div class="panel-body">
                    <div>
                        <ul id="assessmentNav" class="nav-tabs margin-bottom nav">
                            {{--checking user role--}}
                            @role(['super-admin','admin'])
                            <li @if($page == "grade-setup") class="active" @endif id="tab-setup"><a href="/academics/manage/assessments/grade-setup">Grade Setup</a></li>
                            <li @if($page == "assessment") class="active" @endif id="tab-assessment"><a href="/academics/manage/assessments/assessment">Assessments</a></li>
                            <li @if($page == "report-card") class="active" @endif id="tab-reportcard"><a href="/academics/manage/assessments/report-card">Report Card</a></li>
                            @endrole

                            <li @if($page == "grade-book") class="active" @endif id="tab-gradebook"><a href="/academics/manage/assessments/grade-book">Gradebook</a></li>

                            @role(['super-admin','admin'])
                            {{--<li @if($page == "result") class="active" @endif id="tab-result"><a href="/academics/manage/assessments/result">Result</a></li>--}}
                            <li @if($page == "result-sorting") class="active" @endif id="tab-result"><a href="/academics/manage/assessments/search">Result</a></li>
                            <li @if($page == "exam") class="active" @endif id="tab-result"><a href="/academics/manage/assessments/exam">Exam</a></li>
                            <li @if($page == "extra-book") class="active" @endif id="tab-result">
                                <a href="/academics/manage/assessments/extra-book">GradeBook (Extra)</a>
                            </li>
                            <li @if($page == "grade-book-setting") class="active" @endif id="tab-result"><a href="/academics/manage/assessments/grade-book-setting">GradeBook (Setting)</a></li>
                            <li @if($page == "report-card-setting") class="active" @endif id="tab-result"><a href="/academics/manage/assessments/report-card-setting">Report Card (Setting)</a></li>
                            <li @if($page == "result-sms") class="active" @endif id="tab-result"><a href="/academics/manage/assessments/result-sms">Result SMS</a></li>
                            @endrole
                        </ul>
                        <!-- page content div -->
                        @yield('page-content')
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- global modal -->
    {{--<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">--}}
    {{--<div class="modal-dialog">--}}
    {{--<div class="modal-content">--}}
    {{--<div class="modal-body">--}}
    {{--<div class="loader">--}}
    {{--<div class="es-spinner">--}}
    {{--<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}
    {{--</div>--}}

    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true" style="width:100%;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="loader">
                        <div class="preview" style="padding-left: 40%;">
                            <i id="icon_msg" class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                            {{--<h3 id="msg_up">Uploading...</h3>--}}
                        </div>
                        <div class="progress" style="display:none">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
                                0%
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    @yield('page-script')
    <!-- <script type="text/javascript">
    $(document).ready(function(){

        // onload set #tab-setup active
        setManageAssessmentContent('tab-setup');

        // tab onClick function
        $("#assessmentNav li").click(function(){
           setManageAssessmentContent($(this).attr("id"));
        });

        // set page content
        function setManageAssessmentContent(tabId){
            // tab url
            var tabUrl = null;
            // set tabUrl
            switch(tabId){
              case 'tab-setup': tabUrl = '/academics/manage/assessments/grade-setup'; break;
              case 'tab-assessment': tabUrl = '/academics/manage/assessments/assessments'; break;
              case 'tab-reportcard': tabUrl = '/academics/manage/assessments/reportcard'; break;
              case 'tab-gradebook': tabUrl = '/academics/manage/assessments/gradebook'; break;
            }
            // ajax request
            $.ajax({
                url: tabUrl,
                type: 'GET',
                cache: false,
                //data: {},
                datatype: 'html',

                // before request send
                beforeSend: function() {
                    // statements
                },

                // on successfull return data
                success:function(data){
                    if(data){
                        // remove active tab
                        $("#assessmentNav li").each(function() {
                          var id = $(this).attr("id");
                          $('#'+id).removeAttr('class');
                        });
                        // remove content
                        $("#manage-assessment").html('');
                        // set content
                        $("#manage-assessment").append(data);
                        $('#'+tabId).attr('class','active');
                    }else{
                        alert('unable to set data to the content body');
                    }
                },

                // on error occured
                error:function(){
                    alert('unable to load data from remote server');
                }
            });
        }

        // auto hide alert msg
        $('.alert-auto-hide').fadeTo(7500, 500, function () {
          $(this).slideUp('slow', function () {
            $(this).remove();
          });
        });

    });
</script> -->
@endsection
