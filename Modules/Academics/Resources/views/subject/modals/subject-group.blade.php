{{--form--}}
<form action="{{url('/academics/subject/group/store')}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="sub_group_id" value="{{$subjectGroupProfile?$subjectGroupProfile->id:0}}">

	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title">
			<i class="fa fa-info-circle"></i> {{$subjectGroupProfile?'Update':'Add'}} Subject Group
		</h4>
	</div>
	<!--modal-header-->
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-8">
				<div class="form-group">
					<label for="sub_group_name"> Subject Group Name:</label>
					<input type="text" class="form-control" id="sub_group_name" name="sub_group_name"  placeholder="Subject Group Name" value="{{$subjectGroupProfile?$subjectGroupProfile->name:''}}" required>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label for="sub_group_type"> Subject Type:</label>
					<select class="form-control" id="sub_group_type" name="sub_group_type" required>
						<option value="1" {{$subjectGroupProfile?($subjectGroupProfile->type==1?'selected':''):''}}>Compulsory</option>
						<option value="2" {{$subjectGroupProfile?($subjectGroupProfile->type==2?'selected':''):''}}>Elective</option>
						<option value="3" {{$subjectGroupProfile?($subjectGroupProfile->type==3?'selected':''):''}}>Optional</option>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-info pull-left">Submit</button>
		<a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
	</div>
</form>
