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
            {{-- <li class=""><a data-toggle="tab" href="#enroll_active">Active</a></li> --}}
            <li class="active"><a data-toggle="tab" href="#exam_result_graph">Graph</a></li>
            <li class=""><a data-toggle="tab" href="#enroll_history">History</a></li>
        </ul>
        <div class="tab-content">
            {{--student current/active enroll--}}
            {{-- <div id="enroll_active" class="tab-pane fade in active">
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
            </div> --}}

            {{--student enrollment history--}}
            @php $enrollment = $personalInfo->enroll(); @endphp
            <div id="enroll_history" class="tab-pane fade in">
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        {{-- @if(count($academics)>0)
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
                                        <td>{{$academic->batch()->batch_name}}</td>
                                        <td>{{$academic->section()->section_name}}</td>
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
                        @else
                            <div class="alert bg-warning text-warning">
                                <i class="fa fa-warning"></i> No record found.	</div>
                        @endif --}}



                        <table class="table table-striped table-bordered text-center" id="exam-history-table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Academic Year</th>
                                    <th>Term</th>
                                    <th>Class</th>
                                    <th>Form</th>
                                    <th>Exam Name</th>
                                    <th>GPA</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($examLists as $examList)
                                    <tr>
                                        <td>{{ $loop->index+1 }}</td>
                                        <td>{{ ($examList->year)?$examList->year->year_name:"" }}</td>
                                        <td>{{ ($examList->term)?$examList->term->name:"" }}</td>
                                        <td>{{ ($examList->batch)?$examList->batch->batch_name:"" }}</td>
                                        <td>{{ ($examList->section)?$examList->section->section_name:"" }}</td>
                                        <td>{{ ($examList->exam)?$examList->exam->exam_name:"" }}</td>
                                        <td></td>
                                        <td>
                                            <a class="btn btn-xs btn-primary"
                                               href="{{url('/academics/profile/academic2/exam-result/'.$std_id.'/'.$examList->batch_id.'/'.$examList->section_id.
                                                                                '/'.$examList->academic_year_id.'/'.$examList->term_id.'/'.$examList->exam_id.'/')}}"
                                               data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="50" class="text-danger text-center">No Exams Found!</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            {{-- Student's Exam Result Graph --}}
            <div id="exam_result_graph" class="tab-pane fade in active">
                <form method="post" id="search-chart-form" action="">
                    @csrf

                    <input type="hidden" name="stdId" value="{{ $personalInfo->id }}">
                    <div class="row" style="margin-top: 10px">
                        <div class="col-md-2">
                            <label for="">Year:</label>
                            <select name="yearIds[]" class="form-control" id="select-years" multiple>
                                <option value="">--All--</option>
                                @foreach ($years as $year)
                                    <option value="{{ $year->id }}">{{ $year->year_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="">Term:</label>
                            <select name="termId" class="form-control" id="select-terms">
                                <option value="">--All Term--</option>
                                @foreach ($terms as $term)
                                    <option value="{{ $term->id }}">{{ $term->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="">Exam:</label>
                            <select name="examId" class="form-control" id="select-exams">
                                <option value="">--Choose Exam--</option>
                                @foreach ($exams as $exam)
                                    <option value="{{ $exam->id }}">{{ $exam->exam_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <div style="margin-top: 8px">
                                <input type="radio" name="type" value="summary" checked required> <label for="">Summary</label><br>
                                <input type="radio" name="type" value="details" required> <label for="">Details</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label for="">Subject:</label>
                            <select name="subjectIds[]" class="form-control" id="select-subjects" multiple>
                                @foreach ($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-1">
                            <button class="btn btn-success" id="search-chart-btn" style="margin-top: 23px">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="row" style="margin-top: 30px">
                    <div id="examAnimatedBarChart" class="bcBar"></div>
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
            $('#select-years').select2({
                placeholder: "--All Years--"
            });
            $('#select-subjects').select2({
                placeholder: "--All Subjects--"
            });
            show_graph();

            // $('#exam-history-table').Datatable();

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

            $('form#search-chart-form').submit(function (e) {
                e.preventDefault();

                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/student/get/exam/result/data/for/chart') }}",
                    type: 'GET',
                    cache: false,
                    data: $('form#search-chart-form').serialize(), //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },
                
                    success: function (response) {
                        // hide waiting dialog
                        waitingDialog.hide();
                
                        console.log(response);

                        $("#examAnimatedBarChart").empty();

                        for (const prop in response) {
                            $("#examAnimatedBarChart").append('<b>'+response[prop].yearName+':</b>');
                            $("#examAnimatedBarChart").animatedBarChart({ data: response[prop].data });
                        }
                    },
                
                    error: function (error) {
                        // hide waiting dialog
                        waitingDialog.hide();
                
                        console.log(error);
                    }
                });
                // Ajax Request End
            });

            $('#search-chart-btn').click();

        });

    </script>
@endsection