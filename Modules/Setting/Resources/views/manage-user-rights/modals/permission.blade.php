
{{--<form action="{{url('')}}" method="POST">--}}
<form id="permission_store_form">
	<input type="hidden" name="_token" value="{{csrf_token()}}" />
	<input type="hidden" name="permission_id" value="@if($permissionProfile){{$permissionProfile->id}}@else '0' @endif"/>

	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title"><i class="fa fa-plus-square"></i> @if($permissionProfile)Update @else Add @endif Permission</h4>
	</div>

	<div class="modal-body">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="name">Name:</label>
					<input type="text" class="form-control" name="name" value="@if($permissionProfile){{$permissionProfile->name}}@endif" required/>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="display_name">Display Name:</label>
					<input type="text" class="form-control" name="display_name" value="@if($permissionProfile){{$permissionProfile->display_name}}@endif" required/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="module_id">Module:</label>
					<select id="module_id" name="module_id" class="form-control permission-module" required>
						<option value="" selected disabled> --- Select Module ---</option>
						@foreach($moduleList as $module)
							<option value="{{$module->id}}" @if($permissionProfile){{$module->id==$permissionProfile->module_id?'selected':''}}@endif>
								{{$module->name}}
							</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="sub_module_id">Sub-Module:</label>
					<select id="sub_module_id" name="sub_module_id" class="form-control permission-sub-module" required>
						<option value="" selected disabled> --- Select Sub-Module ---</option>
						@if($permissionProfile)
							<option value="0" {{$permissionProfile->sub_module_id=="0"?'selected':''}} class="bg-primary"> Module (No Sub-Module) </option>
							@if($subModules = $permissionProfile->permissionModule()->subModules(1))
								@foreach($subModules as  $subModule)
									<option value="{{$subModule->id}}" {{$subModule->id==$permissionProfile->sub_module_id?'selected':''}}>
										{{$subModule->name}}
									</option>
								@endforeach
							@endif
						@else
							<option value="0" class="bg-primary"> Module (No Sub-Module) </option>
						@endif
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="description">Description:</label>
					<input type="text" class="form-control" name="description" value="@if($permissionProfile){{$permissionProfile->description}}@endif" required/>
				</div>
			</div>
		</div>
		<label>
			<input type="hidden" name="is_user" value="0">
			<input type="checkbox" name="is_user" {{$permissionProfile?($permissionProfile->is_user==1?'checked':''):''}} value="1"> Is User
		</label>
	</div>

	<!--./modal-body-->
	<div class="modal-footer">
		<button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
		<button class="btn btn-primary pull-left" type="submit">Submit</button>
	</div>
	<!--./modal-footer-->
</form>

<script>
    $(document).ready(function () {

        // request for section list using batch id
        jQuery(document).on('change','.permission-module',function(e){
            // preventDefault
            e.preventDefault();
            // ajax request
            $.ajax({
                url: "{{ url('/setting/rights/find/module') }}",
                type: 'GET',
                cache: false,
                data: {'module_id': $(this).val()}, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // alert(JSON.stringify(option));
                },

                success:function(data){
                    var option = '<option value="" selected disabled>--- Select Sub-Module ---</option>';
                    option += '<option value="0" class="bg-primary"> Module (No Sub-Module) </option>';
                    // enlisting sub-module
                    if(data.length>0) {
                        for (var i = 0; i < data.length; i++) {
                            option += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
                        }
                    }
                    // find sub module
                    var sub_module = $('#sub_module_id');
                    // empty html
                    sub_module.html("");
                    // append sub module list
                    sub_module.append(option);
                },
                error:function(){
                    // alert(JSON.stringify(option));
                },
            });
        });


        // request for section list using batch and section id
        $('form#permission_store_form').on('submit', function (e) {
            e.preventDefault();
            // ajax request
            $.ajax({
                url: '/setting/rights/permission/store',
                type: 'POST',
                cache: false,
                data: $('form#permission_store_form').serialize(),
                datatype: 'html',

                beforeSend: function() {
                },

                success:function(data){

                    if(data.status=='failed'){
                        alert(data.msg);
                    }else{
                        $('#globalModal').modal('toggle');
                        var role_permission_table_row =  $('#menu_permission_table_row');
                        role_permission_table_row.html('');
                        role_permission_table_row.append(data);
                    }
                },

                error:function(data){
                    alert(JSON.stringify(data));
                }
            });
        });

    });
</script>