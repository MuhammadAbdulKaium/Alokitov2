@extends('admission::admission-assessment.index')

@section('page-content')
	<form id="applicant_exam_search_form">
		<input type="hidden" name="request_type" value="show"/>
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('academic_year') ? ' has-error' : '' }}">
					<label class="control-label" for="academic_year_setting">Academic Year</label>
					<select id="academic_year_setting" class="form-control academicYear academicChange" name="academic_year" required>
						<option value="" selected>--- Select Year ---</option>
						@foreach($academicYears as $academicYear)
							<option value="{{$academicYear->id}}">{{$academicYear->year_name}}</option>
						@endforeach
					</select>
					<div class="help-block">
						@if($errors->has('academic_year'))
							<strong>{{ $errors->first('academic_year') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('academic_level') ? ' has-error' : '' }}">
					<label class="control-label" for="academic_level_setting">Academic Level</label>
					<select id="academic_level_setting" class="form-control academicLevel academicChange" name="academic_level" required>
						<option value="" selected>--- Select Level ---</option>
					</select>
					<div class="help-block">
						@if($errors->has('academic_level'))
							<strong>{{ $errors->first('academic_level') }}</strong>
						@endif
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group {{ $errors->has('batch') ? ' has-error' : '' }}">
					<label class="control-label" for="batch_setting">Batch</label>
					<select id="batch_setting" class="form-control academicBatch academicChange" name="batch" required>
						<option value="" selected>--- Select Batch ---</option>
					</select>
					<div class="help-block">
						@if($errors->has('batch'))
							<strong>{{ $errors->first('batch') }}</strong>
						@endif
					</div>
				</div>
			</div>
		</div>
		<div class="box-footer text-right">
			<button type="submit" class="btn btn-info">Submit</button>
			<button type="reset" class="btn btn-default pull-left">Reset</button>
		</div>
	</form>
@endsection

{{--page script--}}
@section('page-script')
	<script>
        $(function () {
            $('form#applicant_exam_search_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                $.ajax({
                    type: 'GET',
                    cache: false,
                    url: '/admission/assessment/setting/exam',
                    data: $('form#applicant_exam_search_form').serialize(),
                    datatype: 'html',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success: function (data) {
                        // show waiting dialog
                        waitingDialog.hide();
                        // statements
                        var applicant_grade_content_row=  $('#applicant_grade_content_row');
                        applicant_grade_content_row.html('');
                        applicant_grade_content_row.append(data);
                        waitingDialog.hide();
                    },

                    error:function(data){
                        // show waiting dialog
                        waitingDialog.hide();
                        // sweet alert
	                    swal('Error', 'No response from server', 'error');
                    }
                });
            });

        });
	</script>
@endsection