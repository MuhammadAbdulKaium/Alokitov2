@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
    <div class="row">

        {{--batch string--}}
        @php $batchString="Class"; @endphp
        {{--student enrollment--}}
        @php $enrollment = $personalInfo->enroll(); @endphp

        @if(Auth::user()->can('promote-student'))
            <div class="col-md-12">
                <p>
                    {{--<a id="course-enroll" class="btn btn-success pull-right" href="#" data-target="#globalModal" data-toggle="modal" style="margin-left: 10px;">--}}
                        {{--<i class="fa fa-plus-square" aria-hidden="true"></i> New Enroll--}}
                    {{--</a>--}}
                    <a class="btn btn-success pull-right" href="{{url('/student/course-edit/'.$enrollment->id)}}" data-target="#globalModal" data-toggle="modal">
                        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit
                    </a>
                </p>
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
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        <table class="table table-striped table-bordered text-center">
                            <thead>
                            <tr>
                                <th>Academic Year</th>
                                <th>Academic Level</th>
                                <th>{{$batchString}}</th>
                                <th>Section</th>
                                <th>Enroll Date</th>
                                <th>Enroll Status</th>
                            </tr>
                            </thead>
                            @php $division = null; @endphp
                            @if($divisionInfo = $enrollment->batch()->get_division())
                                @php $division = " (".$divisionInfo->name.") "; @endphp
                            @endif
                            <tbody>
                            <tr>
                                <td>{{$enrollment->academicsYear()->year_name}}</td>
                                <td>{{$enrollment->level()->level_name}}</td>
                                <td>{{$enrollment->batch()->batch_name.$division}}</td>
                                <td>{{$enrollment->section()->section_name}}</td>
                                <td>{{date('d M, Y', strtotime($enrollment->enrolled_at))}}</td>
                                <td>{{$enrollment->enroll_status}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{--student enrollment history--}}
            @php $history = $enrollment->history('ENROLL_HISTORY'); @endphp
            <div id="enroll_history" class="tab-pane fade in">
                <div class="row">
                    <div class="col-md-12">
                        <br/>
                        @if(count($history)>0)
                            {{--{{dd($history[0])}}--}}
                            @for($i=0; $i<count($history); $i++)
                                @php $levelEnrollHistory = $history[$i]; @endphp

                                <ul class="timeline">
                                    <li class="time-label">
                                        <div class="panel panel-default">
                                            <div class="panel-body">
                                                <div class="text-bold" style="margin-bottom:5px;font-size:18px">
                                                    Level :
                                                    <a id="show-course" href="#">{{ $levelEnrollHistory['level_name'] }}</a>
                                                    <div class="pull-right">
                                                        <a class="btn btn-primary btn-sm" id="update-course" href="#" title="Update" data-target="#globalModal" data-toggle="modal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
                                                    </div>
                                                </div>
                                                {{--<span class="text-muted">--}}
                                                {{--<strong>Admission Year</strong> : {{$academic->academicsYear()->year_name}} |--}}
                                                {{--<strong>Admission Year</strong> : {{$academic->academicsYear()->year_name}} |--}}
                                                {{--<strong>GR No.</strong> : {{$academic->gr_no}} |--}}
                                                {{--<strong>Created Time</strong> : {{ $academic->created_at->format('d-m-Y H:i:s') }} |--}}
                                                {{--<strong>Completion Status</strong> :--}}
                                                {{--<span class="label bg-blue">In Progress</span>--}}
                                                {{--</span>--}}
                                            </div>
                                        </div>
                                    </li>
                                    @foreach($levelEnrollHistory['level_enroll'] as $enrollHistory)
                                        <li>
                                            {{--enroll batch status--}}
                                            @php $batchStatus =  $enrollHistory->batch_status @endphp

                                            @if($batchStatus=='IN_PROGRESS')
                                                <i id="enroll_icon_{{$enrollHistory->id}}" class="fa fa-level-up bg-green" title="IN_PROGRESS" data-toggle="tooltip"></i>
                                            @elseif($batchStatus=='LEVEL_UP')
                                                <i id="enroll_icon_{{$enrollHistory->id}}" class="fa fa-check bg-green" title="LEVEL_UP" data-toggle="tooltip"></i>
                                            @elseif($batchStatus=='REPEATED')
                                                <i id="enroll_icon_{{$enrollHistory->id}}" class="fa fa-ban bg-green" title="REPEATED" data-toggle="tooltip"></i>
                                            @elseif($batchStatus=='DROPOUT')
                                                <i id="enroll_icon_{{$enrollHistory->id}}" class="fa fa-ban bg-red" title="DROPOUT" data-toggle="tooltip"></i>
                                            @endif

                                            <div class="timeline-item" style="box-shadow: none;">
                                                <div class="panel panel-default">
                                                    <div class="panel-body" style="font-size:15px;font-weight:600">
                                                        <strong>{{$batchString}}</strong> :
                                                        <a id="show-batch" href="#">
                                                            {{$enrollHistory->batch()->batch_name." (Section: ".$enrollHistory->section()->section_name.") ". $enrollHistory->academicsYear()->year_name}}
                                                        </a>
                                                        {{--checking batch status for batch status label--}}
                                                        @if($batchStatus=='IN_PROGRESS')
                                                            <span id="enroll_label_{{$enrollHistory->id}}" class="label label-primary">Active</span>
                                                        @elseif($batchStatus=='LEVEL_UP')
                                                            <span id="enroll_label_{{$enrollHistory->id}}" class="label label-success">{{$batchStatus}}</span>
                                                        @elseif($batchStatus=='REPEATED')
                                                            <span id="enroll_label" class="label label-warning">{{$batchStatus}}</span>
                                                        @elseif($batchStatus=='DROPOUT')
                                                            <span id="enroll_label_{{$enrollHistory->id}}" class="label label-danger">{{$batchStatus}}</span>
                                                        @endif
                                                        <div class="pull-right">
                                                            <div class="btn-group">
                                                                <button id="w1" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span></button>
                                                                <ul id="w2" class="dropdown-menu dropdown-menu-right">
                                                                    {{--<li><a href="#">Edit</a></li>--}}
                                                                    @if($batchStatus =='IN_PROGRESS')
                                                                        <li id="enroll_dropout_{{$enrollHistory->id}}"><a href="{{url('/student/enrol-detail/apply-dropout/'.$enrollHistory->id)}}" data-target="#globalModal" data-toggle="modal" tabindex="-1">Dropout</a></li>
                                                                    @endif
                                                                    <li><a href="#">Unenrol</a></li>
                                                                    {{--<li><a href="#" data-confirm="Are you sure you want to unenrolled this student into Grade4 (Grade4-Sec1) 2016-17 ?" data-method="post" tabindex="-1">Unenrol</a></li>--}}
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endfor
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

@section('profile-scripts')
    <script type = "text/javascript">

        jQuery(document).ready(function () {
            $('#stuecdetail-secd-date').datepicker();

            jQuery("#course-enroll").click(function(){
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    url: "{{ url('/student/course-enroll') }}",
                    type: 'GET',
                    cache: false,
                    data: {'_token': $_token }, //see the $_token
                    datatype: 'html',

                    beforeSend: function() {
                    },

                    success: function(data) {
                        if(data.length>0) {
                            // remove modal body
                            $('#modal-body').remove();
                            // add modal content
                            $('#modal-content').html(data);
                        } else {
                            // add modal content
                            $('#modal-content').html('<h3>unable to load data</h3>');
                        }
                    },

                    error: function(xhr,textStatus,thrownError) {
                        alert(xhr + "\n" + textStatus + "\n" + thrownError);
                    }
                });
            });

            jQuery("#update-enroll").click(function(){
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    url: "{{ url('/student//course-edit') }}",
                    type: 'GET',
                    cache: false,
                    data: {'_token': $_token }, //see the $_token
                    datatype: 'html',

                    beforeSend: function() {
                    },

                    success: function(data) {
                        if(data.length>0) {
                            // remove modal body
                            $('#modal-body').remove();
                            // add modal content
                            $('#modal-content').html(data);
                        } else {
                            // add modal content
                            $('#modal-content').html('<h3>unable to load data</h3>');
                        }
                    },

                    error: function(xhr,textStatus,thrownError) {
                        alert(xhr + "\n" + textStatus + "\n" + thrownError);
                    }
                });
            });

            jQuery("#show-course").click(function(){
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    url: "{{ url('/student/course-info') }}",
                    type: 'GET',
                    cache: false,
                    data: {'_token': $_token }, //see the $_token
                    datatype: 'html',

                    beforeSend: function() {
                    },

                    success: function(data) {
                        if(data.length>0) {
                            // remove modal body
                            $('#modal-body').remove();
                            // add modal content
                            $('#modal-content').html(data);
                        } else {
                            // add modal content
                            $('#modal-content').html('<h3>unable to load data</h3>');
                        }
                    },

                    error: function(xhr,textStatus,thrownError) {
                        alert(xhr + "\n" + textStatus + "\n" + thrownError);
                    }
                });
            });

            jQuery("#update-course").click(function(){
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    url: "{{ url('/student/course-info-edit') }}",
                    type: 'GET',
                    cache: false,
                    data: {'_token': $_token }, //see the $_token
                    datatype: 'html',

                    beforeSend: function() {
                    },

                    success: function(data) {
                        if(data.length>0) {
                            // remove modal body
                            $('#modal-body').remove();
                            // add modal content
                            $('#modal-content').html(data);
                        } else {
                            // add modal content
                            $('#modal-content').html('<h3>unable to load data</h3>');
                        }
                    },

                    error: function(xhr,textStatus,thrownError) {
                        alert(xhr + "\n" + textStatus + "\n" + thrownError);
                    }
                });
            });

            jQuery("#show-batch").click(function(){
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') },
                    url: "{{ url('/student/batch-info') }}",
                    type: 'GET',
                    cache: false,
                    data: {'_token': $_token }, //see the $_token
                    datatype: 'html',

                    beforeSend: function() {
                    },

                    success: function(data) {
                        if(data.length>0) {
                            // remove modal body
                            $('#modal-body').remove();
                            // add modal content
                            $('#modal-content').html(data);
                        } else {
                            // add modal content
                            $('#modal-content').html('<h3>unable to load data</h3>');
                        }
                    },

                    error: function(xhr,textStatus,thrownError) {
                        alert(xhr + "\n" + textStatus + "\n" + thrownError);
                    }
                });
            });

        });
    </script>
@endsection