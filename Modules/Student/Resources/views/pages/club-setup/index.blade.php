@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Manage |<small>club</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/">Students</a></li>
            <li>SOP Setup</li>
            <li class="active">Club Setup</li>
        </ul>
    </section>
    
    <section class="content">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif
        <div class="box box-solid">
            <div class="box-header">
                <ul class="nav nav-tabs">
                    @foreach ($rooms as $physicalRoom)
                    <li class="nav-item {{ ($room->id == $physicalRoom->id)?'active':'' }}">
                        <a href="{{url('student/club/setup/enrollment/'.$physicalRoom->id)}}">{{$physicalRoom->name}}</a>
                    </li>
                    @endforeach
                </ul>
                <div class="box-tools">
                    <a class="btn btn-info" href="{{url('student/club/activity/history/'.$room->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">History</a>
                </div>
            </div>
        </div>
        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-plus-square"></i> Create
                            Activity </h3>
                    </div>
                    <div class="box-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Activity Directory</th>
                                    <th scope="col">Club</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($room->activities as $activity)
                                <tr>
                                    <td>{{$activity->activity_name}}</td>
                                    <td>{{$room->name}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @if ($fullAccess)
                        <form action="{{url('/student/add/club/activity')}}" method="POST" style="margin-top: 20px">
                            @csrf

                            <input name="room" type="hidden" value="{{$room->id}}">
                            <div class="row">
                                <div class="col-sm-4">
                                    <select name="category" id="" class="form-control" required>
                                        <option value="">Select</option>
                                        @foreach ($activityCategories as $category)
                                            <option value="{{$category->id}}">{{$category->category_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-8">
                                    <input name="name" type="text" class="form-control" placeholder="Activity Name" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <textarea name="remarks" id="" rows="1" class="form-control" style="margin: 20px 0;" required></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button class="btn btn-success" style="float: right">Add</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Search Activity
                        </h3>
                    </div>
                    <div class="box-body table-responsive">
                        <div class="row" style="margin-bottom: 20px">
                            <div class="col-sm-4">
                                <input type="text" id="fromDate" class="form-control hasDatepicker from-date"
                                    name="fromDate" maxlength="10" placeholder="From Date" aria-required="true"
                                    size="10">
                            </div>
                            <div class="col-sm-4">
                                <input type="text" id="toDate" class="form-control hasDatepicker to-date" name="toDate"
                                    maxlength="10" placeholder="To Date" aria-required="true" size="10">
                            </div>
                            <div class="col-sm-4">
                                <button class="btn btn-success search-menu-button">Search</button>
                            </div>
                        </div>
                        <form action="{{ url('student/save/activity/schedule/'.$room->id) }}" method="POST">
                            @csrf

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">Day</th>
                                        <th scope="col">Date</th>
                                        <th scope="col">Time</th>
                                        <th scope="col">Activity</th>
                                        <th scope="col">Comment</th>
                                        <th scope="col">Details</th>
                                    </tr>
                                </thead>
                                <tbody class="activity-table">
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-sm-12">
                                    <button type="submit" class="btn btn-success" style="float: right">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-4">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Associate HR / FM
                            List </h3>
                    </div>
                    <div class="box-body">
                        <ol>
                            @foreach ($room->employees as $employee)
                                <li>{{$employee->title}} {{$employee->first_name}} {{$employee->last_name}}</li>
                            @endforeach
                        </ol>
                        @if ($fullAccess)
                        <form action="{{url('student/edit/club/employee')}}" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-sm-8">
                                    <input type="hidden" name="room" value="{{$room->id}}">
                                    <select name="employees[]" id="select-employees-edit" class="form-control" multiple required>
                                        <option value="">-- FM / HR --</option>
                                        @foreach ($employees as $employee)
                                        @php
                                            $flag = false;
                                            foreach($room->employees as $emp){
                                                if ($emp->id == $employee->id) {
                                                    $flag = true;
                                                }
                                            }
                                        @endphp
                                        <option value="{{$employee->id}}" {{$flag? 'selected': ''}}>{{$employee->first_name}} {{$employee->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <button class="btn btn-success">Update</button>
                                </div>
                            </div>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-sm-8">
                <div class="box box-solid">
                    <div class="box-header">
                        <h3 class="box-title" style="line-height: 40px"><i class="fa fa-search"></i> Associated Student
                            List </h3>
                    </div>
                    <div class="box-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <ol>
                                    @foreach ($room->prefectStudents() as $key => $prefectStudent)
                                    <li>{{$prefectStudent->first_name}} {{$prefectStudent->last_name}} <span class="text-success">- Club Prefect</span></li>
                                    @endforeach
                                    @if ($room->students())
                                    @foreach ($room->students() as $student)
                                    @php
                                        $flag = 1;
                                        foreach ($room->prefectStudents() as $key => $prefectStudent) {
                                            if ($student->std_id == $prefectStudent->std_id) {
                                                $flag = 0;
                                            }
                                        }   
                                    @endphp
                                    @if ($flag == 1)
                                        <li>{{$student->first_name}} {{$student->last_name}}</li>
                                    @endif
                                    @endforeach
                                    @endif
                                </ol>
                            </div>
                            <div class="col-sm-8">
                                @if ($fullAccess)
                                <form action="{{ url('/student/update/club/students') }}" method="POST">
                                    @csrf
    
                                    <input type="hidden" name="roomId" value="{{ $room->id }}">
                                    <input type="hidden" name="allocationId" value="{{ ($allocation)?$allocation->id:'' }}">
    
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <label for="">Classes With Forms</label>
                                            <select name="sections[]" id="select-sections" class="form-control" multiple>
                                                @foreach ($sections as $section)
                                                    @php
                                                        $flag = false;
                                                    @endphp
                                                    @if ($allocation)    
                                                    @foreach ($allocation->sections() as $sec)
                                                        @php
                                                            if ($sec->id == $section->id) {
                                                                $flag = true;
                                                            }
                                                        @endphp
                                                    @endforeach
                                                    @endif
                                                    <option value="{{ $section->id }}" {{ ($flag)?'selected':'' }}>{{ $section->singleBatch->batch_name }} -
                                                        {{ $section->section_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-7">
                                            <label for="">Students</label>
                                            <select name="cadets[]" id="select-cadets" class="form-control" multiple>
                                                <option value="">-- Select --</option>
                                                @foreach ($students as $student)
                                                    @php
                                                        $flag = false;
                                                    @endphp
                                                    @if ($room->students())    
                                                    @foreach ($room->students() as $stu)
                                                        @php
                                                            if ($stu->std_id == $student->std_id) {
                                                                $flag = true;
                                                            }
                                                        @endphp
                                                    @endforeach
                                                    @endif
                                                    <option value="{{ $student->std_id }}" {{ ($flag)?'selected':'' }}>{{$student->first_name}} {{$student->last_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-5"></div>
                                        <div class="col-sm-7" style="margin-top: 10px">
                                            <label for="">Club Prefects</label>
                                            <select name="prefects[]" id="select-prefects" class="form-control" multiple>
                                                <option value="">-- Select Prefect --</option>
                                                @if ($room->students())
                                                    @foreach ($room->students() as $student)
                                                        @php
                                                            $selected = '';
                                                            foreach ($room->prefectStudents() as $key => $prefectStudent) {
                                                                if ($student->std_id == $prefectStudent->std_id) {
                                                                    $selected = 'selected';
                                                                }
                                                            }   
                                                        @endphp
                                                        <option value="{{$student->std_id}}" {{$selected}}>{{$student->first_name}} {{$student->last_name}}</option>
                                                    @endforeach                                                    
                                                @endif
                                            </select>
                                            <button class="btn btn-success" style="float: right; margin-top: 20px">Update</button>
                                        </div>
                                    </div>
                                </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal"  id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <div class="loader">
                    <div class="es-spinner">
                        <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('#select-employees-edit').select2();
        $('#select-sections').select2();
        $('#select-cadets').select2();
        $('#select-prefects').select2();



        var activities = {!!json_encode($room->activities) !!};
        var roomId = {!!json_encode($room->id) !!};
        var userId = {!!json_encode($userId) !!};
        var fullAccess = {!!json_encode($fullAccess) !!};
        var commentOnly = {!!json_encode($commentOnly) !!};
        


        // $('#categoryTable').DataTable();
        $('#menuTable').DataTable();
        $('#recipeTable').DataTable();


        $('#fromDate').datepicker();
        $('#toDate').datepicker();

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });


        // For Date wise table
        $('.search-menu-button').click(function () {
            var fromDate = $('.from-date').val();
            var toDate = $('.to-date').val();
            var startDate = new Date(fromDate);
            var endDate = new Date(toDate);
            var formattedStartDate = startDate.getFullYear()+'-'+(startDate.getMonth()+1)+'-'+startDate.getDate();
            var formattedEndDate = endDate.getFullYear()+'-'+(endDate.getMonth()+1)+'-'+endDate.getDate();
            var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];


            if ((fromDate && toDate) && (startDate < endDate)) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('student/search/activity/schedule') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'room_id': roomId,
                        'startDate': formattedStartDate,
                        'endDate': formattedEndDate
                    }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function () {},

                    success: function (data) {
                        // Setting Up Data
                        $('.activity-table').empty();
                        var loop = startDate;

                        while (loop <= endDate) {
                            var date = ((loop.getDate() <= 9) ? '0' : '') + loop.getDate();
                            var month = ((loop.getMonth() + 1 <= 9) ? '0' : '') + (loop.getMonth() + 1);

                            
                            // Previous value Checking Start
                            var previousValue = null;
                            data.forEach(item => {
                                var scheduleDate = item.schedule.slice(0,10);
                                
                                if (scheduleDate == loop.getFullYear()+'-'+month+'-'+date) {
                                    previousValue = item;
                                }
                            });
                            // Previous value Checking End


                            // Table Row Showing Start
                            var sampleTime = (previousValue)?previousValue.schedule.slice(11,16):'';
                            var time = (sampleTime == '00:00')?'':sampleTime;
                            var activity_id = (previousValue)?previousValue.activity_id:'';
                            var sampleDetails = (previousValue)?previousValue.details:null;
                            var details = (sampleDetails)?previousValue.details:'';
                            var sampleComment = '';
                            if (previousValue) {
                                previousValue.comments.forEach(item => {
                                    if (item.created_by == userId) {
                                        sampleComment = item.comment;
                                    }
                                });
                            }
                            var comment = (sampleComment)?sampleComment:'';


                            var todaysDate = new Date();
                            var disabled = (loop < todaysDate.setDate(todaysDate.getDate()-1))?'disabled':'';

                            var activitiesSel = '<option value="">Select</option>';
                            activities.forEach(ele => {
                                var selected = (activity_id == ele.id)?'selected':'';
                                activitiesSel += '<option value="' + ele.id + '" '+selected+'>' + ele.activity_name +
                                    '</option>';
                            });


                            if (commentOnly) {
                                var row = '<tr><td>' + days[loop.getDay()] + '</td><td>' + date + '/' +
                                month + '/' + loop.getFullYear() +
                                '<input '+disabled+' type="date" name="dates[]" value="' + loop.getFullYear() + '-' + month +
                                '-' + date +
                                '" style="display: none"></td><td><input disabled type="time" name="times[]" value="'+time+'" class="form-control"></td><td><select disabled name="activities[]" id="" class="form-control">' +
                                activitiesSel +
                                '</select></td><td><textarea '+disabled+' name="comments[]" id="" rows="1" class="form-control">'+comment+'</textarea></td><td><textarea disabled name="details[]" id="" rows="1" class="form-control">'+details+'</textarea></td></tr>';
                            }else{
                                var row = '<tr><td>' + days[loop.getDay()] + '</td><td>' + date + '/' +
                                month + '/' + loop.getFullYear() +
                                '<input '+disabled+' type="date" name="dates[]" value="' + loop.getFullYear() + '-' + month +
                                '-' + date +
                                '" style="display: none"></td><td><input '+disabled+' type="time" name="times[]" value="'+time+'" class="form-control"></td><td><select '+disabled+' name="activities[]" id="" class="form-control">' +
                                activitiesSel +
                                '</select></td><td><textarea '+disabled+' name="comments[]" id="" rows="1" class="form-control">'+comment+'</textarea></td><td><textarea '+disabled+' name="details[]" id="" rows="1" class="form-control">'+details+'</textarea></td></tr>';
                            }
                            

                            // Assign html
                            $('.activity-table').append(row);
                            // Table Row Showing End

                            // Date Increment
                            var newDate = loop.setDate(loop.getDate() + 1);
                            loop = new Date(newDate);
                        }
                    }
                });
                // Ajax Request End
            } else {
                alert("Fill up the date fields with valid data first!!");
            }
        });


        $(document).on('change', '#select-sections', function () {
            $('#select-cadets').val(null).trigger('change');

            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/physical/room/search/students') }}",
                type: 'POST',
                cache: false,
                data: {
                    '_token': $_token,
                    'sections': $(this).val()
                }, //see the $_token
                datatype: 'application/json',

                success: function (data) {
                    var txt = '';
                    data.forEach(element => {
                        txt += '<option value="' + element.std_id + '">' + element
                            .first_name + ' ' + element.last_name + '</option>';
                    });

                    $('#select-cadets').empty();
                    $('#select-cadets').append(txt);
                },

                error: function (error) {}
            });
        });

        $(document).on('change', '#select-cadets', function () {
            $('#select-prefects').val(null).trigger('change');

            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/physical/room/search/prefects') }}",
                type: 'POST',
                cache: false,
                data: {
                    '_token': $_token,
                    'students': $(this).val()
                }, //see the $_token
                datatype: 'application/json',

                success: function (data) {
                    var txt = '';
                    data.forEach(element => {
                        txt += '<option value="' + element.std_id + '">' + element
                            .first_name + ' ' + element.last_name + '</option>';
                    });

                    $('#select-prefects').empty();
                    $('#select-prefects').append(txt);
                },

                error: function (error) {}
            });
        });
    });
</script>
@stop