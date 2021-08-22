<form  id="attendanceSubmitForm">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="box-header">
		<p class="text-center text-bold bg-green-active">{{($userType=='e'?'Employee':'Student')}} Attendance List</p>
		{{--attendance submit button--}}
		<button type="button" class="btn submit-attendance-log btn-success pull-right">Submit</button>
	</div>
	<table id="example1" class="table table-striped text-center">
		<thead>
		<tr>
			<th>#</th>
			<th>Unit / Device Name </th>
			<th>Registration Id </th>
			<th>Access Date </th>
			<th>Access Time </th>
			<th>Access ID </th>
			<th>Card</th>
		</tr>
		</thead>
		<tbody>
		@if(!isset($attendanceList))
			<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h4><i class="icon fa fa-check"></i> No Attendance Data Found!!!</h4>
			</div>
		@else
			{{--attendance loop counter--}}
			@php $indexCounter = 1; @endphp
			{{--attendance List looping--}}
			@foreach($attendanceList as $index=>$attendance)
				{{--checking user registration id--}}
				@php
					$reg_id = strtolower($attendance->registration_id);
					if(strlen($reg_id) <2) continue;
					if(($reg_id[0] != 'e' || $reg_id[0] != 's') && $reg_id[1] != '_') continue;
					//person type
					$personType = ($reg_id[0]=='e'?'employee':'student');
				@endphp
				{{--checking user type--}}
				@if(($reg_id[0]==$userType) || ($userType==null))
					@php
						$personId = is_numeric(substr($attendance->registration_id, 2)) ? substr($attendance->registration_id, 2) : 0;
						// unit_name
						$unit_name = $attendance->unit_name;
						// registration_id
						$registration_id = $attendance->registration_id;
						// access_date
						$access_date = $attendance->access_date;
						// access_time
						$access_time = $attendance->access_time;
						// card
						$card = $attendance->card;
						// access id
						$accessId = $attendance->access_id;
					@endphp
					<tr>
						<td>
							{{$indexCounter}}
							<input type="hidden" name="attendance_list[{{$indexCounter}}][person_type]" value="{{$personType}}">
							<input type="hidden" name="attendance_list[{{$indexCounter}}][access_date]" value="{{$access_date}}">
							<input type="hidden" name="attendance_list[{{$indexCounter}}][access_time]" value="{{$access_time}}">
							<input type="hidden" name="attendance_list[{{$indexCounter}}][person_id]" value="{{$personId}}">
							<input type="hidden" name="attendance_list[{{$indexCounter}}][access_id]" value="{{$accessId}}">
							<input type="hidden" name="attendance_list[{{$indexCounter}}][card]" value="{{$card}}">
						</td>
						<td>{{$unit_name}}</td>
						<td>{{$registration_id}}</td>
						<td>{{$access_date}}</td>
						<td>{{$access_time}}</td>
						<td>{{$accessId}}</td>
						<td>{{$card}}</td>
					</tr>
					{{--attendance loop counter--}}
					@php $indexCounter += 1; @endphp
				@endif
			@endforeach
		@endif
		</tbody>
	</table>
	<!-- ./box-body -->
	<div class="box-footer">
		<button type="button" class="btn btn-success submit-attendance-log pull-right">Submit</button>
	</div>
</form>

<script>
    // request for section list using batch id
    $('.submit-attendance-log').click(function () {
		{{--@php--}}
		{{--if($userType=='e') {--}}
		{{--$actionUrl='/employee/employee-attendance/custom/store';--}}
		{{--} else if($userType=='s'){--}}
		{{--$actionUrl='/student/student-attendance/custom/store';--}}
		{{--}--}}
		{{--@endphp--}}

        // ajax request
        $.ajax({
            url: '/employee/employee-attendance/custom/store',
            type: 'POST',
            cache: false,
            data: $('form#attendanceSubmitForm').serialize(),
            datatype: 'html',

            beforeSend: function() {
                // show waiting dialog
                waitingDialog.show('Loading...');
            },

            success:function(data){
                // hide waiting dialog
                waitingDialog.hide();
                // checking status
                if(data.status){
                    // sweet alert
                    swal("Success", data.msg, "success");
                }else{
                    // sweet alert
                    swal("Warning", data.msg, "warning");
                }

            },
            error:function(){
                // hide waiting dialog
                waitingDialog.hide();
                // sweet alert
                swal("Error", 'Unable to load data form server', "error");
            }
        });

    });
</script>