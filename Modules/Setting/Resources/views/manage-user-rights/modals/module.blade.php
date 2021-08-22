
<form action="{{url('/setting/rights/module/store')}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}" />
	<input type="hidden" name="module_id" value="@if($moduleProfile){{$moduleProfile->id}}@else 0 @endif"/>

	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title"><i class="fa fa-plus-square"></i> @if($moduleProfile)Update @else Add @endif Module</h4>
	</div>

	<div class="modal-body">
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="name">Name:</label>
					<input id="name" type="text" class="form-control" name="name" value="{{$moduleProfile?$moduleProfile->name:''}}"/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="icon">icon:</label>
					<input id="icon" type="text" class="form-control" name="icon" value="{{$moduleProfile?$moduleProfile->icon:''}}"/>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="parent_id">Parent:</label>
					<select id="parent_id" name="parent_id" class="form-control module" required>
						<option value="" selected disabled> --- Select Module ---</option>
						<option class="bg-primary" value="0" @if($moduleProfile){{$moduleProfile->parent_id=="0"?'selected':''}}@endif> Module (No Parent) </option>
						@foreach($moduleList as $module)
							<option value="{{$module->id}}" @if($moduleProfile){{$module->id==$moduleProfile->parent_id?'selected':''}}@endif>{{$module->name}}</option>
						@endforeach
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="route">Route:</label>
					<input id="route" type="text" class="form-control" name="route" value="{{$moduleProfile?$moduleProfile->route:''}}"/>
				</div>
			</div>
		</div>
	</div>

	<!--./modal-body-->
	<div class="modal-footer">
		<button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
		<button class="btn btn-primary pull-left" type="submit">Submit</button>
	</div>
	<!--./modal-footer-->
</form>