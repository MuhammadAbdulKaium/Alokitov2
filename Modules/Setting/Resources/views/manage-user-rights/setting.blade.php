
@extends('setting::manage-user-rights.index')

{{--page content--}}
@section('page-content')
	<div class="row">
		<div class="col-md-12">
			<ul class="nav nav-pills">
				<li class="my-tab active"><a data-toggle="tab" href="#institute_module">Institute - Module</a></li>
				<li class="my-tab"><a data-toggle="tab" href="#role_permission">Role - Permission</a></li>
			</ul>
			{{--<hr/>--}}
			<br/>
			<div class="tab-content">
				{{--institute module--}}
				<div id="institute_module" class="tab-pane fade in active">
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<label for="institute">Institute List:</label>
								<select id="institute" name="institute" class="form-control institute" required>
									<option value="" selected disabled> --- Select Institute ---</option>
									@foreach($allInstitute as $institute)
										<option value="{{$institute->id}}">{{$institute->institute_name}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
				</div>
				{{--role permission--}}
				<div id="role_permission" class="tab-pane fade in">
					<div class="row">
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label" for="module">Module</label>
								<select id="module" class="form-control module role_permission_change" name="module" required>
									<option value="" selected disabled>--- Select Module ---</option>
									@foreach($allModule as $module)
										<option value="{{$module->id}}">{{$module->name}}</option>
									@endforeach
								</select>
								<div class="help-block"></div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label" for="sub_module">Sub-Module</label>
								<select id="sub_module" class="form-control sub_module role_permission_change" name="sub_module" required>
									<option value="" selected disabled>--- Select Sub-Module ---</option>
								</select>
								<div class="help-block"></div>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label for="role">Role List:</label>
								<select id="role_id" name="role" class="form-control role role_permission_change" required>
									<option value="" selected disabled> --- Select Role ---</option>
									@foreach($allRole as $role)
										<option value="{{$role->id}}">{{$role->display_name}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>
				</div>
                {{-- data table container --}}
                <div id="module_role_permission_table_row"></div>
			</div>
		</div>
	</div>
@endsection


{{--page scripts--}}
@section('page-script')
	<script>
        $(document).ready(function () {

            //////////////////////   institute module script //////////////////
            $('.my-tab').click(function(){
                $('#module_role_permission_table_row').html('');
            });


            // request for institute module list
            jQuery(document).on('change','.institute',function(e){
                e.preventDefault();
                // institute id
                var institute_id = $(this).val();
                // ajax request
                $.ajax({
                    url: '/setting/rights/setting/institute-module/',
                    type: 'GET',
                    cache: false,
                    data: {'institute_id': institute_id },
                    datatype: 'html',

                    beforeSend: function() {
                        $('#module_role_permission_table_row').html('');
                    },

                    success:function(data){
                        var institute_module_table_row =  $('#module_role_permission_table_row');
                        institute_module_table_row.html('');
                        institute_module_table_row.append(data);
                    },

                    error:function(){
                        // alert(JSON.stringify(option));
                    }
                });
            });



            //////////////////////   role permission script //////////////////
            // request for sub-module list using module id
            jQuery(document).on('change','.module',function(e){
                // preventDefault
                e.preventDefault();
                // empty   role_permission_table_row
                $('#role_permission_table_row').html('');

                // ajax request
                $.ajax({
                    url: "{{ url('/setting/rights/find/module') }}",
                    type: 'GET',
                    cache: false,
                    data: {'module_id': $(this).val()},
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
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
                        var sub_module = $('#sub_module');
                        // empty html
                        sub_module.html("");
                        // append sub module list
                        sub_module.append(option);
                        // reset role
                        $('.role option:first').prop('selected',true);
                    },

                    error:function(){
                        // statements
                        alert('Unable to perform the action')
                    }
                });
            });

            // request for institute module list
            jQuery(document).on('change','.sub_module',function(e) {
                e.preventDefault();
                // empty   role_permission_table_row
                $('#module_role_permission_table_row').html('');
                // reset role
                $('.role option:first').prop('selected',true);
            });

            // request for institute module list
            jQuery(document).on('change','.role',function(e){
                e.preventDefault();
                // role id
                var role_id = $(this).val();
                // empty   role_permission_table_row
                $('#module_role_permission_table_row').html('');
                // ajax request
                $.ajax({
                    url: '/setting/rights/setting/role-permission',
                    type: 'GET',
                    cache: false,
                    data: {'role_id': role_id, 'module_id':$('#module').val(), 'sub_module_id':$('#sub_module').val()},
                    datatype: 'html',

                    beforeSend: function() {
						// statements
                    },

                    success:function(data){
                        var role_permission_table_row =  $('#module_role_permission_table_row');
                        role_permission_table_row.html('');
                        role_permission_table_row.append(data);
                    },

                    error:function(data){
                        alert(JSON.stringify(data));
                    }
                });
            });

        });
	</script>
@endsection