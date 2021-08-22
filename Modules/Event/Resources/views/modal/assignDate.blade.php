<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title text-bold">
        <i class="fa fa-info-circle"></i> Assign Date & Team for {{$event->event_name}}
    </h4>
</div>
<!--modal-header-->
<div class="modal-body">
    @if ($event->status)
        <h5 style="font-weight: bold">Assign New Date: </h5>
        <form method="POST" action="/event/save/event/date">
            @csrf

            <input type="hidden" name="eventId" value="{{$event->id}}">
            <div class="row">
                <div class="col-sm-3">
                    <input type="datetime-local" name="dateTime" class="form-control" required>
                </div>
                <div class="col-sm-4">
                    <input type="text" name="venue" class="form-control" placeholder="Venue" required>
                </div>
                <div class="col-sm-5">
                    <select name="teamIds[]" class="form-control select-teams" multiple required>
                        <option value="">--Choose Teams--</option>
                        @foreach ($event->teams as $team)
                            <option value="{{$team->id}}">{{$team->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row team-form-holder" style="margin-top: 10px; display:none">
                <div class="col-sm-3">
                    <input type="text" class="form-control team-name" id="" placeholder="Team Name">
                </div>
                <div class="col-sm-3">
                    <select name="" id="" class="form-control select-house">
                        <option value="">--House--</option>
                        @foreach ($houses as $house)
                            <option value="{{$house->id}}">{{$house->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <select name="" id="" class="form-control select-batch">
                        <option value="">--Batch--</option>
                        @foreach ($batches as $batch)
                            <option value="{{$batch->id}}">{{$batch->batch_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <select name="" id="" class="form-control select-section">
                        <option value="">--Section--</option>
                    </select>
                </div>
                <div class="col-sm-10" style="margin-top: 10px">
                    <select name="" id="" class="form-control select-students" multiple>
                        <option value="">--Students--</option>
                    </select>
                </div>
                <div class="col-sm-2" style="margin-top: 10px">
                    <button type="button" class="btn btn-info save-team-btn">Add</button>
                    <button type="button" class="btn btn-danger cancel-team-btn"><i class="fa fa-times"></i></button>
                </div>
            </div>
            <div class="row" style="margin: 10px 0">
                <div class="col-sm-12">
                    <button class="btn btn-success" style="float: right; margin-left: 10px">Assign</button>
                    <button type="button" class="btn btn-info add-team-btn" style="float: right">Add Team</button>
                </div>
            </div>
        </form>
    @endif
    
    <h5 style="font-weight: bold">Assigned Dates: </h5>
    @if ($event->status)
        @forelse ($previousDatesGrouped as $previousDate)
            @php
                $date = substr($previousDate[0]->date_time, 0, 10);
                $time = substr($previousDate[0]->date_time, 11, 18);
            @endphp
            <form method="POST" action="/event/save/event/date">
                @csrf

                <input type="hidden" name="eventId" value="{{$event->id}}">
                <input type="hidden" name="previousDateTime" value="{{$previousDate[0]->date_time}}">
                <div class="row">
                    <div class="col-sm-3">
                        <input type="datetime-local" name="dateTime" class="form-control" value="{{$date}}T{{$time}}" required>
                    </div>
                    <div class="col-sm-4">
                        <input type="text" name="venue" class="form-control" placeholder="Venue" value="{{$previousDate[0]->venue}}" required>
                    </div>
                    <div class="col-sm-5">
                        <select name="teamIds[]" class="form-control select-teams" multiple required>
                            <option value="">--Choose Teams--</option>
                            @foreach ($event->teams as $team)
                                @php
                                    $existingTeam = $previousDate->where('team_id', $team->id)->first();
                                @endphp
                                <option value="{{$team->id}}" {{($existingTeam)?'selected':''}}>{{$team->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row team-form-holder" style="margin-top: 10px; display:none">
                    <div class="col-sm-3">
                        <input type="text" class="form-control team-name" placeholder="Team Name">
                    </div>
                    <div class="col-sm-3">
                        <select name="" id="" class="form-control select-house">
                            <option value="">--House--</option>
                            @foreach ($houses as $house)
                                <option value="{{$house->id}}">{{$house->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="" id="" class="form-control select-batch">
                            <option value="">--Batch--</option>
                            @foreach ($batches as $batch)
                                <option value="{{$batch->id}}">{{$batch->batch_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-3">
                        <select name="" id="" class="form-control select-section">
                            <option value="">--Section--</option>
                        </select>
                    </div>
                    <div class="col-sm-10" style="margin-top: 10px">
                        <select name="" id="" class="form-control select-students" multiple>
                            <option value="">--Students--</option>
                        </select>
                    </div>
                    <div class="col-sm-2" style="margin-top: 10px">
                        <button type="button" class="btn btn-info save-team-btn">Add</button>
                        <button type="button" class="btn btn-danger cancel-team-btn"><i class="fa fa-times"></i></button>
                    </div>
                </div>
                <div class="row" style="margin: 10px 0">
                    <div class="col-sm-12">
                        <button type="button" class="btn btn-danger delete-date-button" data-date-time="{{$previousDate[0]->date_time}}" style="float: right; margin-left: 10px"><i class="fa fa-trash"></i></button>
                        <button class="btn btn-success" style="float: right; margin-left: 10px">Update</button>
                        <button type="button" class="btn btn-info add-team-btn" style="float: right">Add Team</button>
                    </div>
                </div>
            </form>
        @empty
            <div class="text-danger" style="text-align: center">No Assigned Dates Found!</div>
        @endforelse
    @else
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>Date Time</th>
                    <th>Venue</th>
                    <th>Teams</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($previousDatesGrouped as $previousDate)
                    @php
                        $date = substr($previousDate[0]->date_time, 0, 10);
                        $time = substr($previousDate[0]->date_time, 11, 18);
                    @endphp
                    
                    <tr>
                        <td>{{$loop->index+1}}</td>
                        <td>Date: {{$date}}, Time: {{$time}}</td>
                        <td>{{$previousDate[0]->venue}}</td>
                        <td>
                            @foreach ($event->teams as $team)
                                @php
                                    $existingTeam = $previousDate->where('team_id', $team->id)->first();
                                @endphp
                                @if ($existingTeam)
                                    <span class="badge">{{$team->name}}</span>
                                @endif
                            @endforeach
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-danger" style="text-align: center">No Assigned Dates Found!</td>
                    </tr>
                @endforelse
            </tbody>
        </table>        
    @endif
    
</div>


<script>
    $(document).ready(function () {
        $('.select-teams').select2({
            placeholder: "Select Teams",
        });


        $('.add-team-btn').click(function () {
            var teamFormHolder = $(this).parent().parent().parent().find('.team-form-holder');
            teamFormHolder.css('display', 'block');
            $('.select-students').select2({
                placeholder: "Select Students",
            });

            teamFormHolder.find('.team-name').val('');
            teamFormHolder.find('.select-house').val('');
            teamFormHolder.find('.select-batch').val('');
            teamFormHolder.find('.select-section').val('');
            teamFormHolder.find('.select-students').val('');

            teamFormHolder.find('.select-house').attr('disabled', false);
            teamFormHolder.find('.select-batch').attr('disabled', false);
            teamFormHolder.find('.select-section').attr('disabled', false);
            teamFormHolder.find('.select-section').html('<option value="">--Section--</option>');
            teamFormHolder.find('.select-students').empty();
            teamFormHolder.find('.select-students').val(null).trigger('change');

            $(this).css('display', 'none');
        });

        $('.cancel-team-btn').click(function () {
            var teamFormHolder = $(this).parent().parent();
            teamFormHolder.css('display', 'none');

            teamFormHolder.parent().find('.add-team-btn').css('display', 'inline-block');
        });

        $('.save-team-btn').click(function () {
            var teamFormHolder = $(this).parent().parent().parent().find('.team-form-holder');

            $_token = "{{ csrf_token() }}";
            var datas = {
                '_token': $_token, //see the _token
                eventId: {!! $event->id !!},
                teamName: teamFormHolder.find('.team-name').val(),
                houseId: teamFormHolder.find('.select-house').val(),
                batchId: teamFormHolder.find('.select-batch').val(),
                sectionId: teamFormHolder.find('.select-section').val(),
                studentIds: teamFormHolder.find('.select-students').val(),
            }

            if (datas.eventId && datas.teamName && datas.studentIds && (datas.houseId || datas.batchId)) {
                // Ajax Request Start
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/event/add/team') }}",
                    type: 'GET',
                    cache: false,
                    data: datas,
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        if (data) {
                            var option = '<option value="'+data+'" selected>'+datas.teamName+'</option>';

                            teamFormHolder.parent().find('.select-teams').append(option);
                            teamFormHolder.parent().find('.select-teams').select2({
                                placeholder: "Select Teams",
                            });
                            teamFormHolder.css('display', 'none');
                            teamFormHolder.parent().find('.add-team-btn').css('display', 'inline-block');
                        }else{
                            swal('Error!', "Error adding new team.", 'error');
                        }
                    }
                });
                // Ajax Request End
            }else{
                swal('Error!', 'Fill up all the fields first.', 'error');
            }
        });

        $('.delete-date-button').click(function () {
            var dateTimeBtn = $(this);
            var dateTime = $(this).data('date-time');
           swal({
                title: "Are You Sure!",
                text: "Are you sure, you want to delete this event on this date?",
                type: "error"
            }, function() {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/event/remove/event/date') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'eventId': {!! $event->id !!},
                        'dateTime': dateTime,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (result) {
                        if (result == 2) {
                            swal('Can not delete!', 'Marks Assigned for students performed in this event.', 'error');
                        }else{
                            dateTimeBtn.parent().parent().parent().empty();
                        }
                    }
                });
                // Ajax Request End
            });
        });

        var globalStudents = [];

        $('.select-house').change(function () {
            var selectStudents = $(this).parent().parent().find('.select-students');
            if($(this).val()){
                $('.select-batch').val('');
                $('.select-batch').attr('disabled', true);
                $('.select-section').val('');
                $('.select-section').attr('disabled', true);
            }else{
                $('.select-batch').attr('disabled', false);
                $('.select-section').attr('disabled', false);
            }

           // Ajax Request Start
           $_token = "{{ csrf_token() }}";
           $.ajax({
               headers: {
                   'X-CSRF-Token': $('meta[name=_token]').attr('content')
               },
               url: "{{ url('/event/get/students/from/house') }}",
               type: 'GET',
               cache: false,
               data: {
                   '_token': $_token,
                   'houseId': $(this).val(),
               }, //see the _token
               datatype: 'application/json',
           
               beforeSend: function () {},
           
               success: function (data) {
                   var txt = '';
                    data.forEach(element => {
                        txt += '<option value="'+element.std_id+'">'+element.first_name+' '+element.last_name+'</option>';
                    });

                    selectStudents.empty();
                    selectStudents.append(txt);
                    selectStudents.val(null).trigger('change');                    
               }
           });
           // Ajax Request End 
        });

        $('.select-batch').change(function () {
            var selectStudents = $(this).parent().parent().find('.select-students');
            if($(this).val()){
                $('.select-house').val('');
                $('.select-house').attr('disabled', true);
            }else{
                $('.select-house').attr('disabled', false);
            }

           // Ajax Request Start
           $_token = "{{ csrf_token() }}";
           $.ajax({
               headers: {
                   'X-CSRF-Token': $('meta[name=_token]').attr('content')
               },
               url: "{{ url('/event/get/sections/students/from/batch') }}",
               type: 'GET',
               cache: false,
               data: {
                   '_token': $_token,
                   'batchId': $(this).val(),
               }, //see the _token
               datatype: 'application/json',
           
               beforeSend: function () {},
           
               success: function (data) {
                    var batches = '<option value="">--Section--</option>';
                    globalStudents = data[1];
                    var students = '';

                    data[0].forEach(element => {
                        batches += '<option value="'+element.id+'">'+element.section_name+'</option>';
                    });

                    data[1].forEach(element => {
                        students += '<option value="'+element.std_id+'">'+element.first_name+' '+element.last_name+'</option>';
                    });

                    $('.select-section').empty();
                    $('.select-section').append(batches);

                    selectStudents.empty();
                    selectStudents.append(students);
                    selectStudents.val(null).trigger('change');                    
               }
           });
           // Ajax Request End 
        });

        $('.select-section').change(function () {
            var selectStudents = $(this).parent().parent().find('.select-students');

           // Ajax Request Start
           $_token = "{{ csrf_token() }}";
           $.ajax({
               headers: {
                   'X-CSRF-Token': $('meta[name=_token]').attr('content')
               },
               url: "{{ url('/event/get/students/from/section') }}",
               type: 'GET',
               cache: false,
               data: {
                   '_token': $_token,
                   'sectionId': $(this).val(),
               }, //see the _token
               datatype: 'application/json',
           
               beforeSend: function () {},
           
               success: function (data) {
                    var students = '';

                    if (data.length < 1) {
                        globalStudents.forEach(element => {
                            students += '<option value="'+element.std_id+'">'+element.first_name+' '+element.last_name+'</option>';
                        });
                    }else{
                        data.forEach(element => {
                            students += '<option value="'+element.std_id+'">'+element.first_name+' '+element.last_name+'</option>';
                        });
                    }                    

                    selectStudents.empty();
                    selectStudents.append(students);
                    selectStudents.val(null).trigger('change');
               }
           });
           // Ajax Request End 
        });        
    });
</script>