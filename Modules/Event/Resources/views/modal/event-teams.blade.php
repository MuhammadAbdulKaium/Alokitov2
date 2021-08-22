<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title text-bold">
        <i class="fa fa-info-circle"></i> Manage Team for {{$event->event_name}}
    </h4>
</div>
<!--modal-header-->
<div class="modal-body">
    @forelse ($event->teams as $team)
        @php
            $selectedStudentIds = json_decode($team->students);
        @endphp
        <form method="POST" action="{{url('/event/update/event/team')}}">
            @csrf

            <input type="hidden" name="teamId" value="{{$team->id}}">

            <div class="row team-form-holder" style="margin-top: 10px">
                <div class="col-sm-3">
                    <input type="text" name="teamName" class="form-control team-name" placeholder="Team Name" value="{{$team->name}}">
                </div>
                <div class="col-sm-3">
                    <select name="houseId" id="" class="form-control select-house" {{($team->batch_id)?'disabled':''}}>
                        <option value="">--House--</option>
                        @foreach ($houses as $house)
                            <option value="{{$house->id}}" {{($team->house_id == $house->id)?'selected':''}}>{{$house->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <select name="batchId" id="" class="form-control select-batch" {{($team->house_id)?'disabled':''}}>
                        <option value="">--Batch--</option>
                        @foreach ($batches as $batch)
                            <option value="{{$batch->id}}" {{($team->batch_id == $batch->id)?'selected':''}}>{{$batch->batch_name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3">
                    <select name="sectionId" id="" class="form-control select-section" {{($team->house_id)?'disabled':''}}>
                        <option value="">--Section--</option>
                        @if ($team->batch)
                            @foreach ($team->batch->section() as $section)
                                <option value="{{$section->id}}" {{($team->section_id == $section->id)?'selected':''}}>{{$section->section_name}}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <span class="text-warning" style="margin-left: 18px">You can select either House or Batch</span>
                <div class="col-sm-10" style="margin-top: 10px">
                    <select name="studentIds[]" id="" class="form-control select-students" multiple>
                        <option value="">--Students--</option>
                        @if ($team->students())
                            @foreach ($team->students() as $student)
                            @php
                                $selected = '';
                                if(in_array($student->std_id, $selectedStudentIds)){
                                    $selected = 'selected';
                                }
                            @endphp
                                <option value="{{ $student->std_id }}" {{ $selected }}>{{ $student->first_name }} {{ $student->last_name }}</option>
                            @endforeach
                        @endif
                    </select>
                </div>
                <div class="col-sm-2" style="margin-top: 10px">
                    <button class="btn btn-info update-team-btn">Update</button>
                    <button type="button" class="btn btn-danger delete-team-btn" data-team-id="{{ $team->id }}"><i class="fa fa-trash"></i></button>
                </div>
            </div>
            <div class="row" style="margin-bottom: 50px"></div>
        </form>
    @empty
        <div class="text-danger" style="text-align: center">No Teams Found!</div>
    @endforelse
</div>


<script>
    $(document).ready(function () {
        $('.select-students').select2({
            placeholder: "Select Students",
        });  
        
        var globalStudents = [];

        $('.select-house').change(function () {
            var parent = $(this).parent().parent().parent();
            var selectStudents = parent.find('.select-students');
            if($(this).val()){
                parent.find('.select-batch').val('');
                parent.find('.select-batch').attr('disabled', true);
                parent.find('.select-section').val('');
                parent.find('.select-section').attr('disabled', true);
            }else{
                parent.find('.select-batch').attr('disabled', false);
                parent.find('.select-section').attr('disabled', false);
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
            var parent = $(this).parent().parent().parent();
            var selectStudents = parent.find('.select-students');
            if($(this).val()){
                parent.find('.select-house').val('');
                parent.find('.select-house').attr('disabled', true);
            }else{
                parent.find('.select-house').attr('disabled', false);
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
        
        $('.delete-team-btn').click(function () {
           var team = $(this).parent().parent().parent();
           var teamId = $(this).data('team-id');
           
           // Ajax Request Start
           $_token = "{{ csrf_token() }}";
           $.ajax({
               headers: {
                   'X-CSRF-Token': $('meta[name=_token]').attr('content')
               },
               url: "{{ url('/event/delete/event/team') }}",
               type: 'GET',
               cache: false,
               data: {
                   '_token': $_token,
                   'teamId': teamId,
               }, //see the _token
               datatype: 'application/json',
           
               beforeSend: function () {},
           
               success: function (data) {
                    if (data == 1) {
                        team.empty();
                    }else if (data == 2) {
                        swal('Error', 'This team is assigned to an event date, can not delete.', 'error');
                    }
               }
           });
           // Ajax Request End 
        });
    });
</script>