<hr/>
<div class="row">
	<div class="col-md-12">
		<p class="pull-right flip">
			<a class="btn btn-success" href="/setting/rights/permission/create" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Create Permission</a>
		</p>
	</div>
</div>
@if($allPermission->count() > 0)
	<table class="table table-striped table-bordered">
		<thead>
		<tr>
			<th class="text-center">#</th>
			<th>Name</th>
			<th>Display Name</th>
			<th>Description</th>
			<th class="text-center">Is User</th>
			<th class="text-center">Status</th>
			<th class="text-center">Action</th>
		</tr>
		</thead>
		<tbody>
		@php $i=1; @endphp
		@foreach($allPermission as $permission)
			<tr id="permission_{{$permission->id}}">
				<td class="text-center">{{$i++}}</td>
				<td><a href="#">{{$permission->name}}</a></td>
				<td>{{$permission->display_name}}</td>
				<td>{{$permission->description}}</td>
				<td class="text-center">
					<i class="{{$permission->is_user==0?'fa fa-ban text-red':'fa fa-check text-green'}}" aria-hidden="true"></i>
				</td>
				<td class="text-center">
					<a data-key="status" data-id="{{$permission->id}}" class="permission-status-delete" title="Status">
						<i id="permission_status_{{$permission->id}}" class="{{$permission->status==0?'fa fa-ban text-red':'fa fa-check text-green'}}" aria-hidden="true"></i>
					</a>
				</td>
				<td class="text-center">
					<a href="{{url('/setting/rights/permission/edit/'.$permission->id)}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					</a>
					<a data-id="{{$permission->id}}" data-key="delete" class="permission-status-delete" title="Delete">
						<i class="fa fa-trash-o" aria-hidden="true"></i>
					</a>
				</td>
			</tr>
			@php $i=($i++); @endphp
		@endforeach
		</tbody>
	</table>
	{{--paginate--}}
	<div class="text-center">
		{{ $allPermission->appends(Request::only(['search'=>'search', 'filter'=>'filter', 'module_id'=>'module_id', 'sub_module_id'=>'sub_module_id']))->render() }}
	</div>
@else
	<div class="alert-warning alert-auto-hide alert fade in" style="opacity: 474.119;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h6><i class="fa fa-warning"></i> No records found. </h6>
	</div>
@endif

<script>
    $(document).ready(function () {
        // paginating
        $('.pagination a').on('click', function (e) {
            e.preventDefault();
            var url = $(this).attr('href').replace('store', 'find');
            loadRolePermissionList(url);
            // window.history.pushState("", "", url);
            // $(this).removeAttr('href');
        });
        // loadRole-PermissionList
        function loadRolePermissionList(url) {
            $.ajax({
                url : url,
                //  type: 'GET',
                //  cache: false
            }).done(function (data) {
                var role_permission_table_row =  $('#menu_permission_table_row');
                role_permission_table_row.html('');
                role_permission_table_row.append(data);
            }).fail(function () {
                alert('permission could not be loaded.');
            });
        }

        $('.permission-status-delete').click(function(){
    		// permission id
    		var permission_id = $(this).attr('data-id');
    		// request type
    		var request_type = $(this).attr('data-key');
    		// checking
    		if(request_type=='status'){
    			if(confirm('Are you sure to change status?')){
					var myUrl = '/setting/rights/permission/status/'+permission_id;
					menuStatusDelete(myUrl, permission_id);
    			}
    		}else{
    			if(confirm('Are you sure you want to delete this item?')){
					var myUrl = '/setting/rights/permission/delete/'+permission_id;
					menuStatusDelete(myUrl, permission_id);
    			}
    		}
    	});
    	// menu Status Delete 
    	function menuStatusDelete(myUrl, permission_id){
    		// ajax request
            $.ajax({
                url : myUrl,
                type: 'GET',
                cache: false
            }).done(function (data) {
            	// checking status
            	if(data.status=='success'){
	            	// checking response_type
	            	if(data.response_type=='status'){
	            		// status request type
	            		var status_permission = $('#permission_status_'+permission_id);
	            		// checking class
	            		if(status_permission.hasClass('fa-ban')){
	            			status_permission.removeClass('fa-ban');
	            			status_permission.removeClass('text-red');
	            			status_permission.addClass('fa-check');
	            			status_permission.addClass('text-green');
	            		}else{
	            			status_permission.removeClass('fa-check');
	            			status_permission.removeClass('text-green');
	            			status_permission.addClass('fa-ban');
	            			status_permission.addClass('text-red');
	            		}
	            	}else{
	            		// delete request type
	            		// remove menu row
	            		$('#permission_'+permission_id).remove();
	            	}
            	}
            }).fail(function (data) {
                // checking status
            	if(data.status=='failed'){
            		alert('unable to perform the action');
            	}
            });
    	}

    });
</script>