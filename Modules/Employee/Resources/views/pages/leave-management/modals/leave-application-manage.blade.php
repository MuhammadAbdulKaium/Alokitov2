
<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
	<h4 class="modal-title"> <i class="fa fa-info-circle"></i> Leave Application Details  </h4>
</div>

<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<table class="table table-responsive">
				<tbody>
				<tr>
					<th>Employee</th>
					@php $employeeInfo = $leaveApplicationProfile->employee() @endphp
					<td>{{$employeeInfo->title." ". $employeeInfo->first_name." ".$employeeInfo->middle_name." ".$employeeInfo->last_name}}</td>
					@php $structure = $employeeInfo->leaveAllocation()->structure(); @endphp
					@php $applicationStatus = $leaveApplicationProfile->status; @endphp
					<th>Leave Structure</th>
					<td>{{$structure->name}}</td>
				</tr>
				<tr>
					<th>Designation</th>
					<td>{{$employeeInfo->designation()->name}}</td>
					<th>Leave Type</th>
					<td>{{ $leaveApplicationProfile->leaveType()->name }}</td>
				</tr>
				<tr>
					<th>Department</th>
					<td>
						@if(!empty($employeeInfo->department()))
						{{$employeeInfo->department()->name}}
							@endif
					</td>
					<th>Leave Days</th>
					<td>{{$leaveApplicationProfile->leave_days}}</td>
				</tr>
				<tr>
					<th>Leave Start Date</th>
					<td>{{date('d M, Y', strtotime($leaveApplicationProfile->start_date))}}</td>
					<th>Leave End Date</th>
					<td>{{date('d M, Y', strtotime($leaveApplicationProfile->end_date))}}</td>
				</tr>

				<tr>
					<th>Application Date</th>
					<td>{{date('d M, Y', strtotime($leaveApplicationProfile->created_at))}}</td>
					<th>Approved/Rejected Date</th>
					<td>@if($applicationStatus>0){{date('d M, Y', strtotime($leaveApplicationProfile->updated_at))}}@endif</td>
				</tr>
				<tr>
					<th>Reason</th>
					<td colspan="3">{{$leaveApplicationProfile->leave_reason}}</td>
				</tr>
				<tr>
					<th>Remarks</th>
					<td id="leave-remarks-container" colspan="3">
						@if($applicationStatus>0)
							{{$leaveApplicationProfile->remarks}}
						@else
							<textarea id="leave-remarks" rows="2" cols="105" {{$applicationStatus>0?'readonly':''}} maxlength="255" placeholder="Type Leave Remarks" required>{{$leaveApplicationProfile->remarks}}</textarea>
						@endif
					</td>
				</tr>
				<tr>
					<th>Status</th>
					<td>
						@if($applicationStatus>0)
							<p class="label {{$applicationStatus=='1'?'label-success':'label-danger'}}">{{$applicationStatus=='1'?'Approved':'Rejected'}}</p>
						@else
							<p class="label label-primary application-status-{{$leaveApplicationProfile->id}}">Pending</p>
						@endif
					</td>

					@if($applicationStatus>0)
						<th></th>
						<td></td>
					@else
						<th class="application-approval-container">Approval Status</th>
						<td class="application-approval-container">
							<button class="btn btn-success application-status" data-id="1" type="button">Approve</button>
							<button class="btn btn-danger application-status" data-id="2" type="button">Reject</button>
						</td>
					@endif
				</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="modal-footer">
	<button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
</div>


<script>
    $(document).ready(function () {

        $('.application-status').click(function () {

            var application_id = '{{$leaveApplicationProfile->id}}';
            var approve_type = $(this).attr('data-id');
            var _token = '{{csrf_token()}}';
            var remarks = $('#leave-remarks').val();

            // checking
            if(remarks){
                $.ajax({
                    url: '/employee/manage/leave/application/status',
                    type: 'POST',
                    cache: false,
                    data:{'application_id':application_id, 'application_status':approve_type, '_token':_token, 'remarks':remarks},
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        // checking
                        if(data.status=='success'){

                            // remove text-area form leave remarks
                            $('#leave-remarks-container').html(remarks);

                            // remove approval btn
                            $('.application-approval-container').html('');

                            // change application status
                            var application_status = $('.application-status-'+application_id);
                            // remove label color
                            application_status.removeClass('label-primary');
                            // checking
                            if(parseInt(approve_type)==1){
                                application_status.addClass('label-success');
                                application_status.html('Approved');
                            }else{
                                application_status.addClass('label-danger');
                                application_status.html('Rejected');
                            }
                        }else{
                            alert(JSON.stringify(data.msg));
                        }
                    },

                    error:function(){
                        // statements
                    },
                });
            }else{
                alert('Remarks can  not be blank');
            }
        });
    });
</script>
