

@extends('academics::manage-attendance.index')
@section('page-content')
	@if(!empty($attendanceSettingProfile))
		<div class="col-md-6">
			<h4> <strong>Attendance Status</strong> </h4>
			<p class="text-right">
				<a id="edit-personal" href="{{url('academics/manage/attendance/settings/status/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square" aria-hidden="true"></i> Add New Status </a>
			</p>
			<table class="table table-striped table-bordered">
				<thead>
				<tr>
					<td class="text-center"> # </th>
					<th>Status</th>
					<th>Short Code</th>
					<th>Color</th>
					<th class="text-center">Actions</th>
				</tr>
				</thead>
				<tbody id="settingStatusTypeTableBody">
				@php $i=1; @endphp
				@foreach($allAttendanceType as $attendanceType)
					<tr>
						<td class="text-center">{{$i++}}</td>
						<td>{{$attendanceType->type_name}}</td>
						<td>{{$attendanceType->short_code}}</td>
						<td><input name="color" maxlength="35" disabled value="{{$attendanceType->color}}" class="form-control" type="color"></td>
						<td class="text-center">
							<a href="/academics/manage/attendance/settings/status/edit/{{$attendanceType->id}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><span class=" fa fa-pencil-square-o"></span></a>
							<a id="{{$attendanceType->id}}" style="cursor: pointer;" onclick="deleteStatusType(this.id)" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>

		<div class="col-md-5 col-md-offset-1">
			<h4><strong>Attendance Seetings</strong> </h4>
			<div class="row">
				<div class="col-md-6 text-center">
					<p>Subject wise attendance?</p>
				</div>
				<div class="col-md-6">
					<p id="att_type">
						<a href="/academics/manage/attendance/settings/type/attendance/1" class="label btn-setting @if($attendanceSettingProfile->subject_wise ==1) label-success @else label-danger @endif">Yes</a>
						<a>&nbsp;</a>
						<a href="/academics/manage/attendance/settings/type/attendance/0" class="label btn-setting @if($attendanceSettingProfile->subject_wise ==0) label-success @else label-danger @endif">No</a>
					</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6 text-center">
					<p>Multiple sessions each day? </p>
				</div>
				<div class="col-sm-6">
					<p id="session_on">
						<a href="/academics/manage/attendance/settings/type/session/1" class="label btn-setting @if($attendanceSettingProfile->multiple_sessions == 1) label-success @else label-danger @endif">Yes</a>
						<a>&nbsp;</a>
						<a href="/academics/manage/attendance/settings/type/session/0" class="label btn-setting @if($attendanceSettingProfile->multiple_sessions == 0) label-success @else label-danger @endif">No</a>
					</p>
				</div>
			</div>
			@if($attendanceSettingProfile->multiple_sessions == 1)
				<div class="row">
					<div class="col-md-12">
						<h4><strong>Attendance Sessions</strong> </h4>
						<p class="text-right">
							<a href="{{url('academics/manage/attendance/settings/session/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square" aria-hidden="true"></i> Add New Session </a>
						</p>
						<table class="table table-striped table-bordered">
							<thead>
							<tr>
								<td class="text-center"> # </th>
								<th> Session Name</th>
								<th class="text-center">Actions</th>
							</tr>
							</thead>
							<tbody id="settingSessionTableBody">
							@php $i=1; @endphp
							@foreach($allAttendanceSession as $attendanceSession)
								<tr>
									<td class="text-center">{{$i++}}</td>
									<td>{{$attendanceSession->session_name}}</td>
									<td class="text-center">
										<a href="/academics/manage/attendance/settings/session/edit/{{$attendanceSession->id}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><span class=" fa fa-pencil-square-o"></span></a>
										<a id="{{$attendanceSession->id}}" style="cursor: pointer;" onclick="deleteSession(this.id)" title="Delete"><span class="glyphicon glyphicon-trash"></span></a>
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			@endif
		</div>

		<script type="text/javascript">

            function deleteStatusType(id){
                var x = confirm("Are you sure you want to delete?");
                if (x){
                    var tr = '';
                    $.ajax({
                        type: 'get',
                        url: '/academics/manage/attendance/settings/status/delete/'+id,
                        data: $('form').serialize(),
                        datatype: 'application/json',

                        beforeSend: function() {
                            // statement
                        },

                        success: function (data) {
                            $("#settingStatusTypeTableBody").html('');
                            // looping
                            for(var i=0;i<data.length;i++){

                                // table rows
                                tr +='<tr><td class="text-center">'+(i+1)+'</td><td>'+data[i].type_name+'</td><td>'+data[i].short_code+'</td><td><input name="color" disabled value="'+data[i].color+'" class="form-control" type="color"></td><td class="text-center"><a href="/academics/manage/attendance/settings/status/edit/'+data[i].id+'" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><span class=" fa fa-pencil-square-o"></span> </a>  <a id="'+data[i].id+'" style="cursor: pointer;" onclick="deleteStatusType(this.id)"   title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
                            }
                            // append tabe data
                            $("#settingStatusTypeTableBody").append(tr);
                        },

                        error:function(data){
                            // sweet alert
                            swal("Error", 'No response form server', "error");
                        }
                    });
                }
            }


            function deleteSession(id){
                var x = confirm("Are you sure you want to delete?");
                if (x){
                    var tr = '';
                    $.ajax({
                        type: 'get',
                        url: '/academics/manage/attendance/settings/session/delete/'+id,
                        data: $('form').serialize(),
                        datatype: 'application/json',

                        beforeSend: function() {
                            // statement
                        },

                        success: function (data) {
                            $("#settingSessionTableBody").html('');
                            // looping
                            for(var i=0;i<data.length;i++){

                                // table rows
                                tr +='<tr><td class="text-center">'+(i+1)+'</td><td>'+data[i].session_name+'</td><td class="text-center"><a href="/academics/manage/attendance/settings/session/edit/'+data[i].id+'" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><span class=" fa fa-pencil-square-o"></span></a>  <a id="'+data[i].id+'" style="cursor: pointer;" onclick="deleteSession(this.id)" title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
                            }
                            // append tabe data
                            $("#settingSessionTableBody").append(tr);
                        },

                        error:function(data){
                            // sweet alert
                            swal("Error", 'No response form server', "error");
                        }
                    });
                }
            }

		</script>
	@else
		<div class="alert-auto-hide alert alert-info alert-dismissable" style="opacity: 408.083;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="fa fa-info-circle"></i> There is no <b>Institute Attendance Setting Profile</b>.
		</div>
	@endif

@endsection
