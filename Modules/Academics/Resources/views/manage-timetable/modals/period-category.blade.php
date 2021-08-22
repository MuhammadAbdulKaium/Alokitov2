
<form action="{{url('/academics/timetable/period/category/store/')}}" method="POST">
@if($categoryProfile)
	<input type="hidden" name="type" value="update">
	<input type="hidden" name="cat_id" value="{{$categoryProfile->id}}">
@else
	<input type="hidden" name="type" value="create">
@endif
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title">
			<i class="fa fa-info-circle"></i> @if($categoryProfile) Update @else Add @endif Class Period Category
		</h4>
	</div>
	<!--modal-header-->
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-8 col-sm-offset-2">
				<div class="form-group">
					<label for="name">Period Category:</label>
					<input type="text" class="form-control" id="name" name="name" value="@if($categoryProfile){{$categoryProfile->name}}@endif">
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-success pull-left">Submit</button>
		<a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
	</div>
</form>