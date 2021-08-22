<h4 class="text-center text-bold bg-blue-gradient">Uploaded Attendance List</h4>
<div class="col-md-12">
	@if(!empty($allAttendance) && $allAttendance->count()>0)
		@if($allAttendance->count()<=500)
			<form id="final_attendance_upload_form" action="{{url('/academics/attendance/upload/store')}}" method="POST">
				<input type="hidden" name="_token" value="{{csrf_token()}}"/>
				<table class="table table-bordered table-responsive table-striped text-center">
					<thead>
					<tr class="bg-gray-active">
						<th>Std ID.</th>
						<th>Std. Name</th>
						@for($x=0; $x<count($dateList); $x++)
							{{--checking for loop break--}}
							@if($x==7) @break @endif
							{{--date name--}}
							@php $myDate = str_replace('_','/',$dateList[$x]) @endphp
							{{--Checking--}}
							@if($myDate==0) @continue @endif
							<th>{{$myDate}}</th>
						@endfor
					</tr>
					</thead>

					<tbody class="text-bold">
					<input  type="hidden" id="last_date" value="{{date("m/d/Y", strtotime(str_replace("/", "-", $myDate)))}}"/>
					@for($i=0; $i<count($allAttendance); $i++)
						@php $singleAttendance = $allAttendance[$i]; @endphp
						<tr class="{{$i%2==0?'bg-gray':'bg-gray-active'}}">
							<td>{{$singleAttendance['std_id']}}</td>
							<input type="hidden" name="std_list[{{($i+1)}}]" value="{{$singleAttendance['std_id']}}" />
							<td>{{$singleAttendance['std_name']}}</td>
							{{--$dateList looping--}}
							@for($k=0; $k<count($dateList); $k++)
								{{--checking for loop break--}}
								@if($k==7) @break @endif
								{{--find attendance list--}}
								@php $attendance = $singleAttendance[$dateList[$k]]; @endphp
								{{--checking--}}
								@if($attendance==null) @continue @endif
								<td id="att_td_{{$i.$k}}" style="cursor: pointer;" class="attendance alert alert-{{$attendance=='P'?'success':'danger'}}">
									{{$attendance}}
								</td>
								<input id="att_input_{{$i.$k}}" type="hidden" name="att_list[{{$singleAttendance['std_id']}}][{{$dateList[$k]}}]" value="@if($attendance){{$attendance=='P'?'1':'0'}}@endif" />
							@endfor
						</tr>
					@endfor
					</tbody>
				</table>
				<div class="modal-footer">
					<button id="final_attendance_upload_form_submit_btn" type="button" class="btn btn-info">Submit</button>
				</div>
			</form>
		@else
			<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in">
				<h5 class="text-bold"><i class="fa fa-warning"></i> Can not Upload above 500 !!! </h5>
			</div>
		@endif
	@else
		<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in">
			<h5 class="text-bold"><i class="fa fa-warning"></i> Attendance List is empty !!!</h5>
		</div>
	@endif
</div>
<script src="{{ asset('js/fc/moment.js') }}" type="text/javascript"></script>
<script src="{{URL::asset('js/jquery.form.js')}}" type="text/javascript"></script>
<script>
    $(document).ready(function () {

        // submit button click action
        $("#final_attendance_upload_form_submit_btn").click(function () {
            // subject info
            var subjectInfo = $('#subject');
            var subjectValue = null;
            if(subjectInfo.length != 0){
                subjectValue = subjectInfo.val();
            }else {
                subjectValue = 0;
            }
            // form info
            var final_attendance_upload_form = $('#final_attendance_upload_form');
            // append academics details
            final_attendance_upload_form.append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>'
                +'<input type="hidden" name="section" value="'+$('#section').val()+'"/>'
                +'<input type="hidden" name="subject" value="'+subjectValue+'"/>'
                +'<input type="hidden" name="session" value="'+$('#session').val()+'"/>');

            // form submit
            final_attendance_upload_form.ajaxForm({
                beforeSend: function() {
                    // statements
                    // show waiting dialog
                    waitingDialog.show('Submitting...');
                },
                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // checking
					if(data.status == 'success'){
                        var attendanceContainer =  $('#attendanceContainer');
                        attendanceContainer.html('');
                        attendanceContainer.append(data.html);
					}else{
                        // sweet alert
                        swal("Warning", data.msg, "warning");
					}
                },
                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // sweet alert
                    swal("Error", 'Unable to load data form server', "error");
                }
            }).submit();
        });



        // attendance click action
        $('.attendance').click(function () {
            // td id
            var id = $(this).attr('id').replace('att_td_','');
            // find attendance type
            if($(this).hasClass('alert-success')){
                $(this).removeClass('alert-success');
                $(this).addClass('alert-danger');
                $(this).text('A');
                $('#att_input_'+id).val(0);
            }else if($(this).hasClass('alert-danger')){
                $(this).removeClass('alert-danger');
                $(this).addClass('alert-success');
                $(this).text('P');
                $('#att_input_'+id).val(1);
            }else{
                $(this).removeClass('alert-info');
                $(this).addClass('alert-success');
                $(this).text('P');
                $('#att_input_'+id).val(1);
            }
        });
    });
</script>