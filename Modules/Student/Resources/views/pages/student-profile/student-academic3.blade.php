@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
    <div class="row">

        {{--batch string--}}
        @php $batchString="Class"; @endphp
        {{--student enrollment--}}
        @php $enrollment = $personalInfo->enroll(); @endphp

        @if(Auth::user()->can('promote-student'))
            <div class="col-md-12">

            </div>
        @endif
    </div>
    {{--std enroll--}}
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#enroll_active">Active</a></li>
            <li class=""><a data-toggle="tab" href="#enroll_history">History</a></li>
        </ul>
        <div class="tab-content">
            {{--student current/active enroll--}}
            <div id="enroll_active" class="tab-pane fade in active">
                <a class="btn btn-success pull-right" href="#" >Overall Remarks</a>
                <a class="btn btn-success pull-right" href="{{url('/student/profile/academic/entry/'.$enrollment->id)}}" style="margin-right: 10px;">Add</a>
                <div class="row" style="margin-top: 5px;">
                    <div class="col-md-2">
                        <input name="student_id" type="hidden" value="{{$personalInfo->id}}">
                        <div class="form-group">
                            <input type="radio" id="yearly" name="duration" value="yearly" checked="checked">
                            <label for="female">Yearly</label><br>
                            <input type="radio" id="monthly" name="duration" value="monthly">
                            <label for="male">Monthly</label>
                        </div>
                    </div>
                    <div id="month_show" class="col-md-3" style="display: none;">
                        <div class="form-group">
                            <input id="month_name" type="text" class="form-control datepicker" name="month_name" placeholder="Select Year">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <input type="radio" id="details" name="type" value="details" checked="checked">
                            <label for="female">Details</label><br>
                              <input type="radio" id="summary" name="type" value="summary">
                              <label for="male">Summary</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <select id="activity" class="form-control select2" name="activity_id[]" multiple="multiple">
                                @if(@isset($activity))
                                    @foreach ($activity as $ac)
                                        <option value="{{$ac->id}}">{{$ac->activity_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <a href="javascript:void(0)" id="show_graph"><i class="fa fa-search fa-2x" aria-hidden="true"></i></a>
                </div>
                <div id="chtAnimatedBarChart" class="bcBar"></div>
            </div>

            {{--student enrollment history--}}
            @php $enrollment = $personalInfo->enroll(); @endphp
            <div id="enroll_history" class="tab-pane fade in">
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        @if(count($academics)>0)
                            <table class="table table-striped table-bordered text-center">
                                <thead>
                                <tr>
                                    <th>Academic Year</th>
                                    <th>Academic Level</th>
                                    <th>Class</th>
                                    <th>Form</th>
                                    <th>Exam Date</th>
                                    <th>Exam Name</th>
                                    <th>GPA</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($academics as $academic)
                                    <tr>
                                        <td>{{$academic->year()->year_name}}</td>
                                        <td>{{$academic->lavel()->level_name}}</td>
{{--                                        <td>Class</td>--}}
                                        <td>{{$academic->batch()->batch_name}}</td>
                                        <td>{{$academic->section()->section_name}}</td>
{{--                                        <td>{{$academic->date}}</td>--}}
                                        <td>{{date('d/m/Y', strtotime($academic->date))}}</td>
                                        <td>{{$academic->remarks}}</td>
                                        <td>{{$academic->total_point}}</td>
                                        <td>
                                            <a id="update-guard-data" class="btn btn-success" href="/student/profile/academic2/{{$std_id}}/{{$academic->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-eye" aria-hidden="true"></i>Details</a>

                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
{{--                            {{$academics}}--}}
                        @else
                            <div class="alert bg-warning text-warning">
                                <i class="fa fa-warning"></i> No record found.	</div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- <script src='https://code.jquery.com/jquery-3.3.1.min.js'></script> --}}
    <link href="{{ asset('css/bar.chart.min.css') }}" rel="stylesheet"/>
    <script src='https://d3js.org/d3.v4.min.js'></script>
    <script src="{{asset('js/jquery.bar.chart.min.js')}}"></script>
    <script type="text/javascript">
        $(document).ready(function (){
            $('.select2').select2();
            show_graph();

            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy",
                viewMode: "years",
                minViewMode: "years"
            });

            $('input[name="duration"]').click(function(){
                var radio_select = $(this).val();
                if(radio_select == 'monthly')
                {
                    $("#month_show").show(200);
                }
                else
                {
                    $("#month_name").val("");
                    $("#month_show").hide();
                }
            });

        });

        $("#show_graph").click(function (e){
            e.preventDefault();
            $("#chtAnimatedBarChart").html("");
            show_graph();

        });


        function show_graph()
        {
            // var host = window.location.origin;

            let duration = $("input[name='duration']:checked").val();
            let student_id = $("input[name=student_id]").val();
            let month_name = $("input[name=month_name]").val();
            let type = $("input[name='type']:checked").val();
            let activity_id = $("#activity").val();
            let category = 19;
            {{--let fector_item = {{$typeid}};--}}
            let _token   = '<?php echo csrf_token() ?>';

            $.ajax({
                url: '/student/profile/acadimic/graph',
                type:"POST",
                data:{
                    student_id:student_id,
                    category:category,
                    // fector_item:fector_item,
                    duration:duration,
                    month_name:month_name,
                    type:type,
                    activity_id:activity_id,
                    _token: _token
                },
                success: function (response) {
                    console.log(response);
                    $("#chtAnimatedBarChart").animatedBarChart({ data: response });
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                    console.log(errorThrown);
                }
            });
        }

    </script>
@endsection