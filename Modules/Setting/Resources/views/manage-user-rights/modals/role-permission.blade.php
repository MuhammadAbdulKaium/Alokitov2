<div class="col-md-12">
	@if($allPermission->count()>0)
		<h4 class="text-center text-bold bg-blue-gradient">Role-Permission List</h4>
		<table class="table table-responsive table-bordered table-striped">
			<thead>
			<tr>
				<th class="text-center">#</th>
				<th>Name</th>
				<th>Display Name</th>
				<th>Description</th>
				<th class="text-center">Action</th>
			</tr>
			</thead>
			<tbody>
			@foreach($allPermission as $permission)
				<tr>
					<td class="text-center">
						<input id="permission_checkbox_{{$permission->id}}" type="checkbox" title="check" name="{{$permission->id}}" {{$permission->checkRole($roleProfile->id)?'checked':''}} disabled />
					</td>
					<td>{{$permission->name}}</td>
					<td>{{$permission->display_name}}</td>
					<td>{{$permission->description}}</td>
					@php $checkRole = $permission->checkRole($roleProfile->id); @endphp
					<td class="text-center">
						<button id="permission_btn_{{$permission->id}}" data-role="{{$roleProfile->id}}" data-permission="{{$permission->id}}" data-module="{{$permission->module_id}}" data-sub-module="{{$permission->sub_module_id}}" class="btn assign-btn {{$checkRole?'btn-danger':'btn-primary'}}" type="button">
							{{$checkRole?'Remove':'Assign'}}
						</button>
					</td>
				</tr>
			@endforeach
			</tbody>
		</table>

		<div class="text-center">
			{{ $allPermission->appends(Request::only([
			   'search'=>'search',
			   'filter'=>'filter',
			   'role_id'=>'role_id',
			   'module_id'=>'module_id',
			   'sub_module_id'=>'sub_module_id',
			   ]))->render() }}
		</div>
	@else
		<div class="alert-warning alert-auto-hide alert fade in" style="opacity: 474.119;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h6><i class="fa fa-warning"></i> No records found. </h6>
		</div>
	@endif
</div>

<script>
    $(document).ready(function () {

        $('.assign-btn').click(function () {
            // permission btn id
            var permission_id = $(this).attr('data-permission');
               // permission_assign_btn_form
            var role_permission_assign_btn_form =  $('<form id="role_permission_assign_btn_form" style="display:none;"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="role_id" value="'+$(this).attr('data-role')+'"/>')
                .append('<input type="hidden" name="permission_id" value="'+permission_id+'"/>')
                .append('<input type="hidden" name="module_id" value="'+$(this).attr('data-module')+'"/>')
                .append('<input type="hidden" name="sub_module_id" value="'+$(this).attr('data-sub-module')+'"/>')
                .append('<input type="hidden" name="request_type" value="assign"/>')
                // append to body and submit the form
                .appendTo('body');
            // ajax request
            $.ajax({
                url: '/setting/rights/setting/role-permission',
                type: 'GET',
                cache: false,
                data: $('form#role_permission_assign_btn_form').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Submitting...');
                },

                success:function(data){
                    // permission btn id
                    var permission_btn = $('#permission_btn_'+permission_id);
                    // permission checkbox id
                    var permission_checkbox = $('#permission_checkbox_'+permission_id);
                    // checking data
	                if(data.status =='attached'){
                        permission_btn.text('Remove');
                        permission_btn.removeClass('btn-primary');
                        permission_btn.addClass('btn-danger');
                        // checkbox add attr
                        permission_checkbox.prop("checked", true);
	                }else{
                        permission_btn.text('Assign');
                        permission_btn.removeClass('btn-danger');
                        permission_btn.addClass('btn-primary');
                        // checkbox remove attr
                        permission_checkbox.removeAttr('checked');
	                }
                    // hide waiting dialog
                    waitingDialog.hide();
                },

                error:function(data){
                    alert('Unable to perform the action')
                }
            });
            // remove form from the body
            $('#role_permission_assign_btn_form').remove();

        });

        // paginating
        $('.pagination a').on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href');
            loadPermissionList(url);
            // window.history.pushState("", "", url);
            // $(this).removeAttr('href');
        });

        function loadPermissionList(url) {
            $.ajax({
                url : url,
                // type: 'GET',
                // cache: false
            }).done(function (data) {
                var role_permission_table_row =  $('#module_role_permission_table_row');
                role_permission_table_row.html('');
                role_permission_table_row.append(data);
            }).fail(function () {
                alert('permission could not be loaded.');
            });
        }

    });
</script>