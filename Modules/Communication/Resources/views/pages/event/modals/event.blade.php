
{{--form--}}
<form id="events-form" action="{{url('/communication/event')}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="event_id" value="{{$eventProfile?$eventProfile->id:0}}">
	{{--page style--}}
	<style>.data-time-picker{z-index:1151 !important;}</style>
	{{--modal header--}}
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal">
			<span aria-hidden="true">×</span>
			<span class="sr-only">Close</span>
		</button>
		<h4 class="modal-title">
			<i class="fa fa-pencil-square"></i> {{$eventProfile?'Update':"Create"}} Event
		</h4>
	</div>

	{{--modal body--}}
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="event_title">Title</label>
					<input type="text" id="event_title" class="form-control" name="event_title" value="{{$eventProfile?$eventProfile->title:''}}" maxlength="100" required>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="event_detail">Detail</label>
					<textarea id="event_detail" class="form-control" name="event_detail" maxlength="255" required>{{$eventProfile?$eventProfile->detail:''}}</textarea>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="event_start_date_time">Start Time</label>
					<div class="input-group datetime">
						<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
						<input type="text" id="event_start_date_time" class="form-control data-time-picker" name="event_start_date_time" readonly="" value="{{$eventProfile?$eventProfile->start_date_time:''}}" required>
					</div>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="event_end_date_time">End Time</label>
					<div class="input-group datetime">
						<span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
						<input type="text" id="event_end_date_time" class="form-control data-time-picker" name="event_end_date_time" readonly="" value="{{$eventProfile?$eventProfile->end_date_time:''}}" required>
					</div>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label">User Type</label>
					<input type="hidden" name="event_user_type" value="{{$eventProfile?$eventProfile->user_type:'1'}}">
					<div id="event_user_type">
						<label><input type="radio" @if($eventProfile){{$eventProfile->user_type=='1'?'checked':''}}@else checked @endif name="event_user_type" value="1"> General </label>
						<label><input type="radio" @if($eventProfile){{$eventProfile->user_type=='2'?'checked':''}}@endif name="event_user_type" value="2"> Employee </label>
						<label><input type="radio" @if($eventProfile){{$eventProfile->user_type=='3'?'checked':''}}@endif name="event_user_type" value="3"> Student </label>
						<label><input type="radio" @if($eventProfile){{$eventProfile->user_type=='4'?'checked':''}}@endif name="event_user_type" value="4"> Parent </label>
					</div>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="submit" class="btn btn-create btn-info pull-left">Submit</button>
		<button type="button" class="btn btn-default btn-flat" data-dismiss="modal">Close</button>
	</div>
</form>

<script type="text/javascript">
    jQuery('.data-time-picker').datetimepicker({
        "autoclose": true,
        "dateFormat": "dd-mm-yy",
        "timeFormat": "HH:mm",
        "showSecond": true
    });
</script>