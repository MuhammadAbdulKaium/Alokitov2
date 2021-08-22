@extends('admission::admission-assessment.index')

@section('page-content')
	<form id="admission_report_search_form" method="post" action="{{url('/admission/assessment/setting/reports')}}" target="_blank">
		<input type="hidden" name="_token" value="{{csrf_token()}}"/>
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
					<select id="batch_setting" class="form-control academicBatch academicChange" name="batch">
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