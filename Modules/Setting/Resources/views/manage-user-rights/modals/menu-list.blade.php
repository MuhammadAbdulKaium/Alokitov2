<hr/>
<div class="row">
	<div class="col-md-12">
		<p class="pull-right flip">
			<a class="btn btn-success" href="{{url('/setting/rights/menu/create')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Add Menu</a>
		</p>
	</div>
</div>
@if($allMenu->count() > 0)
	<table class="table table-striped table-bordered">
		<thead>
		<tr>
			<th>#</th>
			<th>Icon</th>
			<th>Name</th>
			<th>Route</th>
			<th>Permission</th>
			<th class="text-center">Status</th>
			<th class="text-center">Action</th>
		</tr>
		</thead>
		<tbody>
		@php $i=1; @endphp
		@foreach($allMenu as $menu)
			<tr id="menu_{{$menu->id}}">
				<td>{{$i++}}</td>
				<td><i class="{{$menu->icon}}" aria-hidden="true"></i></td>
				<td><a href="#">{{$menu->name}}</a></td>
				<td><a href="#">{{$menu->route}}</a></td>
				<td>
					@php $menuPermission = $menu->menuPermissions()->first(); @endphp
					{{$menuPermission?$menuPermission->name:"No Permission"}}
				</td>
				<td class="text-center">
					<a data-key="status" data-id="{{$menu->id}}" class="menu-status-delete" title="Status">
						<i id="menu_status_{{$menu->id}}"class="{{$menu->status==0?'fa fa-ban text-red':'fa fa-check text-green'}}" aria-hidden="true"></i>
					</a>
				</td>
				<td class="text-center">
					<a href="/setting/rights/menu/edit/{{$menu->id}}" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
						<i class="fa fa-pencil-square-o" aria-hidden="true"></i>
					</a>
					<a data-key="delete" data-id="{{$menu->id}}" class="menu-status-delete" title="Delete">
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
		{{ $allMenu->appends(Request::only(['search'=>'search', 'filter'=>'filter', 'module_id'=>'module_id', 'sub_module_id'=>'sub_module_id']))->render() }}
	</div>
@else
	<div class="alert-warning alert-auto-hide alert fade in" style="opacity: 474.119;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h6><i class="fa fa-warning"></i> No records found. </h6>
	</div>
@endif

<script>
    $(document).ready(function () {

    	$('.menu-status-delete').click(function(){
    		// menu id
    		var menu_id = $(this).attr('data-id');
    		// request type
    		var request_type = $(this).attr('data-key');
    		// checking
    		if(request_type=='status'){
    			if(confirm('Are you sure to change status?')){
					var myUrl = '/setting/rights/menu/status/'+menu_id;
					menuStatusDelete(myUrl, menu_id);
    			}
    		}else{
    			var myUrl = '/setting/rights/menu/delete/'+menu_id;
    			if(confirm('Are you sure you want to delete this item?')){
					var myUrl = '/setting/rights/menu/delete/'+menu_id;
					menuStatusDelete(myUrl, menu_id);
    			}
    		}
    	});
    	// menu Status Delete 
    	function menuStatusDelete(myUrl, menu_id){
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
	            		var status_menu = $('#menu_status_'+menu_id);
	            		// checking class
	            		if(status_menu.hasClass('fa-ban')){
	            			status_menu.removeClass('fa-ban');
	            			status_menu.removeClass('text-red');
	            			status_menu.addClass('fa-check');
	            			status_menu.addClass('text-green');
	            		}else{
	            			status_menu.removeClass('fa-check');
	            			status_menu.removeClass('text-green');
	            			status_menu.addClass('fa-ban');
	            			status_menu.addClass('text-red');
	            		}
	            	}else{
	            		// delete request type
	            		// remove menu row
	            		$('#menu_'+menu_id).remove();
	            	}
            	}
            }).fail(function (data) {
                // checking status
            	if(data.status=='failed'){
            		alert('unable to perform the action');
            	}
            });
    	}

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
                var menu_table_row =  $('#menu_table_row');
                menu_table_row.html('');
                menu_table_row.append(data);
            }).fail(function (data) {
                alert('unable to load menu list');
            });
        }

    });
</script>