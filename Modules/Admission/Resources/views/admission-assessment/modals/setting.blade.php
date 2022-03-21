
@section('styles')
	<style>

	</style>
@endsection
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
							<input id="exam_marks" class="form-control  settings" name="exam_marks"
								   value="{{$feesSettingProfile?$feesSettingProfile->exam_marks:''}}" maxlength="100"
								   placeholder="Enter Exam Marks" type="number" required>
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
						<div class="form-group {{ $errors->has('last_date_of_submission') ? ' has-error' : '' }}">
							<label class="control-label" for="last_date_of_submission">Form submission Last Date</label>
							<input id="exam_date" class="form-control datepicker settings" style="border-radius: 0px"
								   name="last_date_of_submission" value="{{$feesSettingProfile?date('m/d/Y',strtotime
								   ($feesSettingProfile->last_date_of_submission)):''}}" placeholder="Last Date Of
								   Form Submission`	"
								   readonly type="text" required>
							<div class="help-block">
								@if ($errors->has('last_date_of_submission'))
									<strong>{{ $errors->first('last_date_of_submission') }}</strong>
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
					<div class="col-sm-3">
						<label class="control-label" for="max_applicants">Maximum Applicants</label>
						<input id="exam_start_time" class="form-control " name="max_applicants"
							   value="{{$feesSettingProfile?$feesSettingProfile->max_applicants:''}}"
							   placeholder="maximum applicants" type="text"  required>
						<div class="help-block">
							@if ($errors->has('max_applicants'))
								<strong>{{ $errors->first('max_applicants') }}</strong>
							@endif
						</div>
					</div>
					<div class="col-sm-3">
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
							<label class="control-label" for="">Result Published </label>
							<br/>
							<input name="exam_taken" value="{{$applicantResultSheet>0?'1':'0'}}" type="hidden">
							<label><input id="exam_taken" name="exam_taken"
										  @if($feesSettingProfile){{$feesSettingProfile->exam_taken=='1'?'checked':''}}@endif type="checkbox" value="1"> Is Result Published</label>
							<div class="help-block">
								@if($errors->has('exam_taken'))
									<strong>{{ $errors->first('exam_taken') }}</strong>
								@endif
							</div>
						</div>
					</div>
					<div class="col-sm-3">
						<div class="form-group {{ $errors->has('exam_status') ? ' has-error' : '' }}">
							<label class="control-label" for=""> Exam Taken</label>
							<br/>
							<input name="exam_status" value="{{$applicantResultSheet>0?'1':'0'}}" type="hidden">
							<label><input id="exam_status" name="exam_status"
										  {{$applicantResultSheet>0?'disabled':''}} @if($feesSettingProfile)
										  {{$feesSettingProfile->exam_status=='1'?'checked':''}}@endif
										  type="checkbox" value="1"> Is Exam Taken?</label>
							<div class="help-block">
								@if($errors->has('exam_status'))
									<strong>{{ $errors->first('exam_status') }}</strong>
								@endif
							</div>
						</div>
					</div>
				</div>


				<div class="subjectWise bg-secondary">

					<button type="button" class="btn-success" id="newAdd"  >Add Subject Mark <i class="fa
					fa-plus-circle"></i></button>
					<table class="table  table-bordered table-responsive">
						<thead>
						<tr>
							<th>subject Name</th>
							<th>subject mark</th>
						</tr>


						</thead>
						<tbody class="subjectWise">
						@php
						$subjectCount=0;
						$totalSubjectMarks=0;

						@endphp
						@if($feesSettingProfile)
						@foreach(json_decode($feesSettingProfile->exam_subjects_marks,1) as $key=>$examMark)
							@php
								$subjectCount++;

							@endphp
							<tr>
								<td>
									<input type="text" class="subjectInput form-control"
										   id="subjectName{{$subjectCount}}"
										   name="subjectName[]"
										   value="{{$key}}">
								</td>
								<td>
									<input type="number"
										   id="subjectMark{{$subjectCount}}"
										   class="subjectInput form-control" name="subjectMark[]"
										   value="{{$examMark}}">
									@php

										$totalSubjectMarks=$examMark+$totalSubjectMarks;
									@endphp
								</td>



							</tr>

						@endforeach
							@endif
						</tbody>

					</table>

				</div>
				<div class="box-footer text-right">
					<button id="admission_setting_submit_btn" type="submit" class="btn btn-info settings">Submit</button>
					<h5 id="errorFillMarks" class="text-danger"><i class="fa fa-exclamation-triangle"></i> Total Exam
						marks distribution
						is not filled Up
					</h5>
				</div>
			</form>
		</div>
	</div>
</div>


<script>
	function check(e){
		console.log(e)
	}

    $(document).ready(function () {



        // checking $applicantResultSheet
	    @if($feesSettingProfile && $feesSettingProfile->exam_taken===1)
	        $('.settings').attr("disabled", 'disabled');
	    @endif
		var subjectCount= {{$subjectCount}};
		var examMarks=parseInt($("#exam_marks").val());
		var filledMarks= {{$totalSubjectMarks}};
		(filledMarks!==examMarks) ? ($("#errorFillMarks").show()) : ($("#errorFillMarks").hide())
		$(document).on('keyup', ".subjectInput",function (){
			filledMarks=0;
			for(let i=1;i<=subjectCount;i++)
			{
				let subjectName=$("#subjectName"+i);
				let subjectMark=$("#subjectMark"+i);
				filledMarks+=parseInt(subjectMark.val())
			}
			console.log(filledMarks,examMarks);
			(filledMarks!==examMarks) ? ($("#errorFillMarks").show()) : ($("#errorFillMarks").hide())
		})

		$("#exam_marks").on('keyup',function (){
			examMarks=$("#exam_marks").val();
			//show error if marks is not filled with examMarks
			(filledMarks!==examMarks) ? ($("#errorFillMarks").show()) : ($("#errorFillMarks").hide())


			console.log(examMarks)
		})

		$('#newAdd').on('click',function (e) {
			console.log("boo")
			filledMarks=0;
			let errorFlag=false;
			for(let i=1;i<=subjectCount;i++){
				let subjectName=$("#subjectName"+i);
				let subjectMark=$("#subjectMark"+i);
				if(!subjectMark.val() || !subjectName.val())
				{
					Swal.fire({
						position: 'top-end',
						icon: 'error',
						title: 'Complete the previous field',
						showConfirmButton: false,
						timer: 1500
					})
					errorFlag=true;
					break;

				}
				else {
					filledMarks+=parseInt(subjectMark.val())

				}


			}

			console.log(filledMarks,examMarks);

			(filledMarks!==examMarks) ? ($("#errorFillMarks").show()) : ($("#errorFillMarks").hide())
			if(errorFlag){
				return false;
			}
			else {


			subjectCount++;
			$('.subjectWise').append(
					$(document.createElement('tr')).prop(
							{
								class:"subjectForm",
								id:"subjectForm"+subjectCount
							}
					)
			);
			$('#subjectForm'+subjectCount).append(
					$(document.createElement('td')).prop(
							{
								class:"subjectFormTd",
								id:"subjectFormTd"+subjectCount+"1"
							}
					),
			$(document.createElement('td')).prop(
					{
						class:"subjectFormTd",
						id:"subjectFormTd"+subjectCount+"2"
					}
			)
			);
			$('#subjectFormTd'+subjectCount+"1").append(

					$(document.createElement('input')).prop({
						type: 'text',
						placeholder:"Enter subject Name",
						id: 'subjectName'+subjectCount,

						className: 'form-control',
						name:'subjectName[]'
					})
			);
				$('#subjectFormTd'+subjectCount+"2").append(
						'<input type="number" value="0" class="subjectInput form-control" name="subjectMark[]" ' +
						'id="subjectMark'+subjectCount+'"/>'
				);

			}

		})


		$(".subjectInput").on('click',function(){
			console.log("voo")

		})

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
					console.log(data)
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
					waitingDialog.hide();
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