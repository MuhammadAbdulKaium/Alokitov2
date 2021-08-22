<style type="text/css">
	.ui-autocomplete {z-index:2147483647;}
	.ui-autocomplete span.hl_results {background-color: #ffff66;}
</style>
<form id="menu_store_form">
	<input type="hidden" name="_token" value="{{csrf_token()}}" />
	<input type="hidden" name="menu_id" value="@if($menuProfile){{$menuProfile->id}}@else 0 @endif"/>

	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title"><i class="fa fa-plus-square"></i> @if($menuProfile)Update @else Add @endif Menu</h4>
	</div>

	<div class="modal-body">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="name">Name:</label>
					<input id="name" type="text" class="form-control" name="name" value="@if($menuProfile){{$menuProfile->name}}@endif" required/>
				</div>
			</div>

			<div class="col-sm-6">
				<div class="form-group">
					<label for="icon">Icon:</label>
					<input id="icon" type="text" class="form-control" name="icon" value="@if($menuProfile){{$menuProfile->icon}}@endif" required/>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label for="module_id">Module:</label>
					<select id="module_id" name="module_id" class="form-control menu-module" required>
						<option value="" selected disabled> --- Select Module ---</option>
						@foreach($allModule as $module)
							<option value="{{$module->id}}" @if($menuProfile){{$module->id==$menuProfile->module_id?'selected':''}}@endif>{{$module->name}}</option>
						@endforeach
					</select>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label for="sub_module_id">Sub-Module:</label>
					<select id="sub_module_id" name="sub_module_id" class="form-control menu-sub-module" required>
						<option value="" selected disabled> --- Select Sub-Module ---</option>
						@if($menuProfile)
							@if($subModules = $menuProfile->menuModule()->subModules(1))
								@foreach($subModules as  $subModule)
									<option value="{{$subModule->id}}" {{$subModule->id==$menuProfile->sub_module_id?'selected':''}}>
										{{$subModule->name}}
									</option>
								@endforeach
							@endif
						@endif
					</select>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					@if($menuProfile) @php $permissionProfile = $menuProfile->menuPermissions()->first(); @endphp @endif
					<label for="permission">Permission:</label>
					<input id="permission" type="text" class="form-control" name="permission" value="@if($menuProfile){{$permissionProfile?$permissionProfile->name:""}}@endif" required />
					<input id="permission_id" name="permission_id" value="@if($menuProfile){{$permissionProfile?$permissionProfile->id:""}}@endif" type="hidden">
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label for="route">Route:</label>
					<input id="route" type="text" class="form-control" name="route" value="{{$menuProfile?$menuProfile->route:''}}" required />
				</div>
			</div>
		</div>
	</div>

	<!--./modal-body-->
	<div class="modal-footer">
		<button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
		<button id="menu_store_form_submit_btn" class="btn btn-primary pull-left" type="submit">Submit</button>
	</div>
	<!--./modal-footer-->
</form>

<script>
    $(document).ready(function () {
        // get permission and select auto complete
        $('#permission').keypress(function() {
            // empty permission_id value
            $("#permission_id").val('');
            // auto complete
            $(this).autocomplete({
                source: loadFromAjax,
                minLength: 1,
                select: function(event, ui) {
                    // Prevent value from being put in the input:
                    this.value = ui.item.label;
                    // Set the next input's value to the "value" of the item.
                    $("#permission_id").val(ui.item.id);
                    event.preventDefault();
                }
            });
            // load permission list
            function loadFromAjax(request, response) {
                var term = $("#permission").val();
                $.ajax({
                    url: '/setting/rights/permission/find',
                    dataType: 'json',
                    data: {
                        'term': term,
                        'module_id': $('#module_id').val(),
                        'sub_module_id': $('#sub_module_id').val(),
                        'request_type': 'auto_complete'
                    },
                    success: function(data) {
                        // you can format data here if necessary
                        response($.map(data, function(el) {
                            return {
                                label: el.name,
                                value: el.name,
                                id: el.id
                            };
                        }));
                    }
                });
            }
        });

        // request for section list using batch and section id
        $('form#menu_store_form').on('submit', function (e) {
            e.preventDefault();

            // check permission id
            if(!$('#permission_id').val()){
                // empty permission value
                $('#permission').val('');
                // alert msg
                alert('Please select a permission');
            }else{
                // ajax request
                $.ajax({
                    url: '/setting/rights/menu/store',
                    type: 'GET',
                    cache: false,
                    data: $('form#menu_store_form').serialize(),
                    datatype: 'html',

                    beforeSend: function() {
                    },

                    success:function(data){
                        var menu_table_row =  $('#menu_table_row');
                        menu_table_row.html('');
                        menu_table_row.append(data);
                        $('#globalModal').modal('toggle');
                    },

                    error:function(data){
                        alert(JSON.stringify(data));
                    }
                });
            }
        });

        // request for section list using batch id
        jQuery(document).on('change','.menu-module',function(e){
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

                error:function(data){
                    // alert(JSON.stringify(option));
                }
            });
        });

    });
</script>