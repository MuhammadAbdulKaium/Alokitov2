
<form action="{{url('/academics/timetable/period/store/')}}" method="POST">
	@if($periodProfile)
		<input type="hidden" name="action_type" value="update">
		<input type="hidden" name="period_id" value="{{$periodProfile->id}}">
	@else
		<input type="hidden" name="action_type" value="create">
	@endif
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title">
			<i class="fa fa-info-circle"></i> @if($periodProfile) Update @else Add @endif Class Period
		</h4>
	</div>
	<!--modal-header-->
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="name">Period Name:</label>
					<input type="text" class="form-control" name="period_name" value="@if($periodProfile){{$periodProfile->period_name}}@endif">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="name">Period Category:</label>
					<select class="form-control" name="period_category">
						@if($classPeriodCategories)
							<option value="" selected disabled>--- Select Category ---</option>
							@foreach($classPeriodCategories as $category)
								<option value="{{$category->id}}" @if($periodProfile) {{$periodProfile->period_category == $category->id?'selected':''}}  @endif>{{$category->name}}</option>
							@endforeach
						@else
							<option value="" selected disabled>--- Category Not Found ---</option>
						@endif
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="name">Period Shift:</label>
					<select class="form-control" name="period_shift">
						<option value="" selected disabled>--- Select Shift ---</option>
						<option value="0" @if($periodProfile)  {{$periodProfile->period_shift == 0?"selected":""}} @endif> Day </option>
						<option value="1" @if($periodProfile)  {{$periodProfile->period_shift == 1?"selected":""}} @endif> Morning </option>
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<label>Period Start Time:</label>
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<select class="form-control" name="period_start_hour">
								<option value="" selected disabled>Hour</option>
								@for($i=1; $i<=12; $i++)
									<option value="@if($i%10==$i){{"0"}}@endif{{$i}}" @if($periodProfile) @if($periodProfile->period_start_hour == $i) selected=="selected" @endif @endif >@if($i%10==$i){{"0"}}@endif{{$i}}</option>
								@endfor
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<select class="form-control" name="period_start_min">
								<option value="" selected disabled>Min</option>
								@for($i=0; $i<60; $i++)
									<option value="@if($i%10==$i){{"0"}}@endif{{$i}}" @if($periodProfile) @if($periodProfile->period_start_min == $i) selected=="selected" @endif @endif>@if($i%10==$i){{"0"}}@endif{{$i}}</option>
								@endfor
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<select class="form-control" name="period_start_meridiem">
								<option value="0" @if($periodProfile) @if($periodProfile->period_start_meridiem == 0) selected=="selected" @endif @endif>AM</option>
								<option value="1" @if($periodProfile) @if($periodProfile->period_start_meridiem == 1) selected=="selected" @endif @endif>PM</option>
							</select>
						</div>
					</div>
				</div>
			</div>

			<div class="col-md-6">
				<label for="name">Period End Time:</label>
				<div class="row">
					<div class="col-sm-4">
						<div class="form-group">
							<select class="form-control" name="period_end_hour">
								<option value="" selected disabled>Hour</option>
								@for($i=1; $i<=12; $i++)
									<option value="@if($i%10==$i){{"0"}}@endif{{$i}}" @if($periodProfile) @if($periodProfile->period_end_hour == $i) selected=="selected" @endif @endif>@if($i%10==$i){{"0"}}@endif{{$i}}</option>
								@endfor
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<select class="form-control" name="period_end_min">
								<option value="" selected disabled>Min</option>
								@for($i=0; $i<60; $i++)
									<option value="@if($i%10==$i){{"0"}}@endif{{$i}}" @if($periodProfile) @if($periodProfile->period_end_min == $i) selected=="selected" @endif @endif>@if($i%10==$i){{"0"}}@endif{{$i}}</option>
								@endfor
							</select>
						</div>
					</div>
					<div class="col-sm-4">
						<div class="form-group">
							<select class="form-control" name="period_end_meridiem">
								<option value="0" @if($periodProfile) @if($periodProfile->period_end_meridiem == 0) selected=="selected" @endif @endif>AM</option>
								<option value="1" @if($periodProfile) @if($periodProfile->period_end_meridiem == 1) selected=="selected" @endif @endif>PM</option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">
			<input type="hidden" name="break" value="0">
			<span class="pull-right">Break</span><input class="pull-right" type="checkbox" {{($periodProfile AND ($periodProfile->is_break==1))?'checked':''}} value="1"  name="break">
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-success pull-left">Submit</button>
		<a class="btn btn-default pull-right" href="#" data-dismiss="modal">Cancel</a>
	</div>
</form>