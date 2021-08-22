
<div class="box box-solid">
	<div class="et">
		<div class="box-header with-border">
			<h3 class="box-title"><i class="fa fa-users"></i> Employee Leave Allocation List </h3>
			{{--<div class="box-tools">--}}
			{{--<a class="btn btn-success btn-sm" href="/employee/manage/leave/type/create" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-plus-square"></i> Add</a>--}}
			{{--</div>--}}
		</div>
	</div>
	<div class="box-body table-responsive">
		{{--<div class="box-header">--}}
		{{--</div>--}}
		<div class="box-body">
			@if($employeeEntitlementList->count()>0)
				<table id="example1" class="table table-striped table-bordered">
					<thead>
					<tr>
						<th class="text-center">#</th>
						<th>Employee Name</th>
						<th class="text-center">Category</th>
						<th class="text-center">Structure Type</th>
						<th class="text-center">Designation</th>
						<th class="text-center">Department</th>
						<th class="text-center">Action</th>
					</tr>
					</thead>
					<tbody>
					@php $i=1; @endphp
					@foreach($employeeEntitlementList as $leaveAllocation)
						<tr>
							<td class="text-center">{{$i++}}</td>
							@php $employeeProfile = $leaveAllocation->employee(); @endphp
							<td>
								<a href="{{url('/employee/profile/personal/'.$employeeProfile->id)}}">
									{{$employeeProfile->first_name." ".$employeeProfile->middle_name." ".$employeeProfile->last_name}}
								</a>
							</td>
							@php $category = $leaveAllocation->category; @endphp
							<td class="text-center">
								@if($category=='2') Employee @elseif($category=='3') Department @else General @endif
							</td>
							@php $myStructure = $leaveAllocation->structure(); @endphp
							<td class="text-center">
								{{$myStructure->name}}
								@if($myStructure->parent > 0)
									(<a href="{{url('/employee/manage/leave/structure/venus/edit/'.$myStructure->myParent()->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">{{$myStructure->myParent()->name}}</a>)
								@endif
							</td>
							<td class="text-center">{{$leaveAllocation->designation()->name}}</td>
							<td class="text-center">{{$leaveAllocation->department()->name}}</td>
							<td class="text-center">

								<a href="{{url('/employee/manage/leave/structure/venus/edit/'.$leaveAllocation->structure)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
								<a href="{{ url('/employee/manage/leave/structure/delete/'.$leaveAllocation->id) }}" title="Delete" data-confirm="Are you sure you want to delete this item?" data-method="get"><i class="fa fa-trash-o" aria-hidden="true"></i></a>

							</td>
						</tr>
					@endforeach
					</tbody>
				</table>

				{{--paginate--}}
				<div class="text-center">
					{{ $employeeEntitlementList->appends(Request::only(['search'=>'search', 'filter'=>'filter', 'department'=>'department', 'designation'=>'designation', 'category'=>'category', 'structure'=>'structure', 'employee'=>'employee']))->render() }}
				</div>
			@else
				<div id="w0-success-0" class="alert-warning alert-auto-hide text-center alert fade in" style="opacity: 353.696;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h5><i class="fa fa-warning"></i> No result found. </h5>
				</div>
			@endif
		</div>
	</div>
</div>


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
                var list_container =  $('#leave-entitlement-list-container');
                list_container.html('');
                list_container.append(data);
            }).fail(function (data) {
                alert('Unable to load data form server');
            });
        }
    });
</script>