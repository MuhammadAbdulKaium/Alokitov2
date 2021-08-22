
<div class="panel panel-default">
	<div class="panel-body">
		<div class="col-md-12">
			<p class="bg-blue-gradient text-bold text-center">Exam Settings</p>
			<form id="admission_setting_form">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<input type="hidden" name="request_type" value="store"/>
				<input type="hidden" id="exam_setting_id" name="exam_setting_id" value="{{$feesSettingProfile?$feesSettingProfile->id:'0'}}">
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group {{ $errors->has('exam_marks') ? ' has-error' : '' }}">
							<label class="control-label" for="exam_marks">Exam Marks</label>
							<input id="exam_marks" class="form-control settings" name="exam_marks" value="{{$feesSettingProfile?$feesSettingProfile->exam_marks:''}}" maxlength="100" placeholder="Enter Exam Marks" type="text" required>
							<div class="help-block">
								@if($errors->has('exam_marks'))
									<strong>{{ $errors->first('exam_marks') }}</strong>
								@endif
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group {{ $errors->has('exam_passing_marks') ? ' has-error' : '' }}">
							<label class="control-label" for="exam_passing_marks">Exam Passing Marks</label>
							<input id="exam_passing_marks" class="form-control settings" name="exam_passing_marks" value="{{$feesSettingProfile?$feesSettingProfile->exam_passing_marks:''}}" maxlength="100" placeholder="Enter Exam Passing Marks" type="text" required>
							<div class="help-block">
								@if($errors->has('exam_passing_marks'))
									<strong>{{ $errors->first('exam_passing_marks') }}</strong>
								@endif
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group {{ $errors->has('merit_list_std_no') ? ' has-error' : '' }}">
							<label class="control-label" for="merit_list_std_no">No. of Merit List Students</label>
							<input id="merit_list_std_no" class="form-control settings" name="merit_list_std_no" value="{{$feesSettingProfile?$feesSettingProfile->merit_list_std_no:''}}" maxlength="5" placeholder="Enter Merit Std. Number" type="text" required>
							<div class="help-block">
								@if ($errors->has('merit_list_std_no'))
									<strong>{{ $errors->first('merit_list_std_no') }}</strong>
								@endif
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group {{ $errors->has('waiting_list_std_no') ? ' has-error' : '' }}">
							<label class="control-label" for="waiting_list_std_no">No. of Waiting List Students</label>
							<input id="waiting_list_std_no" class="form-control settings" name="waiting_list_std_no" value="{{$feesSettingProfile?$feesSettingProfile->waiting_list_std_no:''}}" maxlength="5" placeholder="Enter Enroll Std. Number" type="text" required>
							<div class="help-block">
								@if ($errors->has('waiting_list_std_no'))
									<strong>{{ $errors->first('waiting_list_std_no') }}</strong>
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-3">
						<div class="form-group {{ $errors->has('exam_fees') ? ' has-error' : '' }}">
							<label class="control-label" for="exam_fees_amount">Application Fees</label>
							<input id="exam_fees" class="form-control settings" name="exam_fees" value="{{$feesSettingProfile?$feesSettingProfile->exam_fees:''}}" maxlength="5" placeholder="Enter Exam Fees" type="text" required>
							<div class="help-block">
								@if ($errors->has('exam_fees'))
									<strong>{{ $errors->first('exam_fees') }}</strong>
								@endif
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group {{ $errors->has('exam_date') ? ' has-error' : '' }}">
							<label class="control-label" for="exam_date">Exam Date</label>
							<input id="exam_date" class="form-control datepicker settings" style="border-radius: 0px" name="exam_date" value="{{$feesSettingProfile?date('m/d/Y',strtotime($feesSettingProfile->exam_date)):''}}" placeholder="Enter Exam date" readonly type="text" required>
							<div class="help-block">
								@if ($errors->has('exam_date'))
									<strong>{{ $errors->first('exam_date') }}</strong>
								@endif
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group {{ $errors->has('exam_start_time') ? ' has-error' : '' }}">
							<label class="control-label" for="exam_start_time">Exam Start Time</label>
							<input id="exam_start_time" class="form-control timepicker settings" name="exam_start_time" value="{{$feesSettingProfile?$feesSettingProfile->exam_start_time:''}}" placeholder="Enter Exam Start Time" type="text" readonly required>
							<div class="help-block">
								@if ($errors->has('exam_start_time'))
									<strong>{{ $errors->first('exam_start_time') }}</strong>
								@endif
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group {{ $errors->has('exam_end_time') ? ' has-error' : '' }}">
							<label class="control-label" for="exam_end_time">Exam End Time</label>
							<input id="exam_end_time" class="form-control timepicker settings" name="exam_end_time" value="{{$feesSettingProfile?$feesSettingProfile->exam_end_time:''}}" placeholder="Enter Exam End Time" type="text" readonly required>
							<div class="help-block">
								@if ($errors->has('exam_end_time'))
									<strong>{{ $errors->first('exam_end_time') }}</strong>
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-9">
						<div class="form-group {{ $errors->has('exam_venue') ? ' has-error' : '' }}">
							<label class="control-label" for="exam_venue">Exam Venue (Exam Center)</label>
							<input id="exam_venue" class="form-control settings" name="exam_venue" value="{{$feesSettingProfile?$feesSettingProfile->exam_venue:''}}" maxlength="100" placeholder="Enter Exam Venus Name" type="text" required>
							<div class="help-block">
								@if($errors->has('exam_venue'))
									<strong>{{ $errors->first('exam_venue') }}</strong>
								@endif
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group {{ $errors->has('exam_taken') ? ' has-error' : '' }}">
							<label class="control-label" for="">Result Published (Exam Taken)</label>
							<br/>
							<input name="exam_taken" value="{{$applicantResultSheet>0?'1':'0'}}" type="hidden">
							<label><input id="exam_taken" name="exam_taken" {{$applicantResultSheet>0?'disabled':''}} @if($feesSettingProfile){{$feesSettingProfile->exam_taken=='1'?'checked':''}}@endif type="checkbox" value="1"> Is Result Published</label>
							<div class="help-block">
								@if($errors->has('exam_taken'))
									<strong>{{ $errors->first('exam_taken') }}</strong>
								@endif
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer text-right">
					<button id="admission_setting_submit_btn" type="submit" class="btn btn-info settings">Submit</button>
				</div>
			</form>
		</div>
	</div>
</div>

<script>
    $(document).ready(function () {
        // checking $applicantResultSheet
	    @if($applicantResultSheet>0)
	        $('.settings').attr("disabled", 'disabled');
	    @endif


        $('form#admission_setting_form').on('submit', function (e) {
            e.preventDefault();
            // append academics details
            var exam_setting_form = $(this)
                .append('<input type="hidden" name="academic_year" value="'+$('#academic_year_setting').val()+'"/>')
                .append('<input type="hidden" name="academic_level" value="'+$('#academic_level_setting').val()+'"/>')
                .append('<input type="hidden" name="batch" value="'+$('#batch_setting').val()+'"/>');
            // ajax request
            $.ajax({
                type: 'POST',
                cache: false,
                url: '/admission/assessment/setting/exam',
                data: $(exam_setting_form).serialize(),
                datatype: 'html',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Submitting...');
                },

                success: function (data) {
                    waitingDialog.hide();
                    // checking
                    if(data.status=='failed'){
                        $('#applicant_grade_content_row').html('');
                        alert('Unable to Perform the action');
                    }else{
                        // success
                        $('#exam_setting_id').val(data.exam_setting_id)
                    }
                },

                error:function(data){
                    alert(JSON.stringify(data));
                }
            });
        });

        //birth_date picker
        $('.datepicker').datepicker({
            autoclose: true
        });

        $('.timepicker').timepicker({
            timeFormat: 'h:mm p',
            interval: 15,
            dynamic: true,
            dropdown: true,
            scrollbar: true,
        });



    });
</script>