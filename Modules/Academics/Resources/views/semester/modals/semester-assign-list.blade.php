<div class="row">
	<div class="col-sm-12">
		<h5 class="text-bold text-center bg-aqua-active">Semester List</h5>
		@if($semesterList->count()>0)
			<table class="table text-center table-bordered table-responsive table-striped">
				<thead>
				<tr>
					<th>#</th>
					<th> Name</th>
					<th>Start Date</th>
					<th>End Date</th>
					<th>Status</th>
					<th>Action</th>
				</tr>
				</thead>
				<tbody>
				@php $i=1; @endphp
				@foreach($semesterList as $semester)
					<tr>
						<td>{{$i++}}</td>
						<td>{{$semester->name}}</td>
						<td>{{$semester->start_date}}</td>
						<td>{{$semester->end_date}}</td>
						<td><i class="{{$semester->status==0?'fa fa-ban text-red':'fa fa-check text-green'}}" aria-hidden="true"></i></td>
						@php $semesterAssignment = $semester->checkBatchSemester($academicInfo->level, $academicInfo->batch); @endphp
						<td>
							@if($semesterAssignment->count()>0)
								<button id="{{$semester->id}}" data-key="remove" class="btn btn-danger semester-status" type="button">Remove</button>
							@else
								<button id="{{$semester->id}}" data-key="assign" class="btn btn-success semester-status" type="button">Assign</button>
							@endif
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		@endif
	</div>
</div>

<script>
    $(document).ready(function () {

        $('.semester-status').click(function(){
            // semester_id
            var semester_id = $(this).attr('id');
            // request type
            var request_type = $(this).attr('data-key');
            // confirmation
            if(confirm('Are you sure to change status?')) {

                // ajax request
                $.ajax({
                    url: "{{ url('/academics/semester/batch/semester/status') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        'batch_id': '{{$academicInfo->batch}}',
	                    'level_id': '{{$academicInfo->level}}',
	                    'semester_id':semester_id,
	                    'request_type':request_type
                    },
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
//                        alert(JSON.stringify(data));

                        // checking status
                        if (data.status == 'success') {
                            // checking status
                            var button = $('#'+semester_id);
                            // checking class
                            if (request_type=='remove') {
                                button.removeAttr('data-key');
                                button.attr('data-key', 'assign');
                                button.removeClass('btn-danger');
                                button.addClass('btn-success');
                                button.html('Assign');
                            } else {
                                button.removeAttr('data-key');
                                button.attr('data-key', 'remove');
                                button.removeClass('btn-success');
                                button.addClass('btn-danger');
                                button.html('Remove');
                            }
                        }else{
                            alert(JSON.stringify(data.msg));
						}
                    },
                    error:function(){
                        // statements
                    },
                });
            }
        });
    });
</script>