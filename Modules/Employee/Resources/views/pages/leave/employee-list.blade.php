{{-- @php  print_r($allEnrollments) @endphp --}}

<div class="col-md-12">
	<div class="box box-solid">
		<div class="et">
			<div class="box-header with-border">
				<h3 class="box-title"><i class="fa fa-search"></i> View Employee List</h3>
			</div>
		</div>
		<div class="card">
			<form method="POST" id="emp_assign_submit_form">
{{--				<input id="listData" name="list[]" value="">--}}
			<table class="table">
				<thead>
				<tr>
					<th><input id="selectAll" type="checkbox"></th>
					<th>Name</th>
					<th>Emp ID</th>
					<th>Leave Type</th>
					<th>Duration</th>
				</tr>
				</thead>
				<tbody>
				@if($searchData)
				@foreach($searchData as $key =>$data)
					<tr>
						<td><input type="checkbox" name="checkbox[]" id="selectEmp_{{$data->user_id}}" onclick="selectEmp({{$data->user_id}})"></td>
						<td>{{$data->first_name}} {{$data->last_name}}</td>
						<td>{{$data->user_id}}</td>
						<td>
							@csrf
							<input type="hidden" id="emp_{{$data->user_id}}" name="emp_id[]" value="{{$data->user_id}}">
							<input type="hidden" id="dpt_{{$data->user_id}}" name="dept_id" value="{{$leave_type_id}}">
							<input type="hidden" id="dsg_{{$data->user_id}}" name="designation_id" value="{{$designation_id}}">
							<input type="hidden" id="leaveType_{{$data->user_id}}" name="leave_type_id" value="{{$leave_type_id}}">
							<input type="number" id="duration_{{$data->user_id}}" name="duration[]" value="{{$duration}}">
							<input type="hidden" id="leaveProcess_{{$data->user_id}}" name="leave_process_procedure" value="{{$leave_process_procedure}}">
							<input type="hidden" id="leave_year_{{$data->user_id}}" name="leave_year" value="{{$leave_year}}">
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
				<input type="submit" id="assignData">
			</form>
				@else
					<h5 class="text-center"> <b>Sorry!!! No Result Found</b></h5>
				@endif
		</div>
	</div>
</div>

<script>

	var selectedData = [];
	function selectEmp(id)
	{
		if($('#selectEmp_'+id).is(":checked"))
		{
			var emp = $('#emp_'+id).val();
			var dpt = $('#dpt_'+id).val();
			var dsg = $('#dsg_'+id).val();
			var leaveType = $('#leaveType_'+id).val();
			var duration = $('#duration_'+id).val();
			var leaveProcess = $('#leaveProcess_'+id).val();
			var leave_year = $('#leave_year'+id).val();
			var jsonData = JSON.parse('{"emp":"'+emp+'", "dpt" : "'+dpt+'" ,"dsg" : "'+dsg+'","leaveType" : "'+leaveType+'","duration" : "'+duration+'","leaveProcess": "'+leaveProcess+'","leave_year": "'+leave_year+'"}');
			selectedData.push(jsonData);
		}
		else
		{
			var index = 0;
			for (index = selectedData.length - 1; index >= 0; --index) {
				console.log(selectedData[index]);
				if (selectedData[index].emp == id) {
					selectedData.splice(index, 1);
				}
			}
		}
	}


	$(function (){
		$("#assignData").click(function (e) {
			e.preventDefault();

			// ajax request
			$.ajax({
				url: "/employee/assign/form/submit/",
				type: 'POST',
				cache: false,
				data: {"_token": "{{ csrf_token() }}",
					"selectedData":selectedData},
				datatype: 'application/json',


				beforeSend: function() {
					// show waiting dialog
					waitingDialog.show('Loading...');
				},

				success:function(data){

					console.log(data);
				},

				error:function(data){
					alert(JSON.stringify(data));
				}
			});
		});
	});

	$(function () {
		$("#selectAll").click(function() {
			$("input[type=checkbox]").prop("checked", $(this).prop("checked"));
		});

		$("input[type=checkbox]").click(function() {
			if (!$(this).prop("checked")) {
				$("#selectAll").prop("checked", false);
			}
		});

		// request for parent list using batch section id
		$('form#emp_assign_submit_form').on('submit', function (e) {
			e.preventDefault();
			// ajax request
			$.ajax({
				url: "/employee/assign/form/submit/",
				type: 'POST',
				cache: false,
				data: $('form#emp_assign_submit_form').serialize(),
				datatype: 'application/json',


				// beforeSend: function() {
				//  // show waiting dialog
				//  waitingDialog.show('Loading...');
				// },

				success:function(data){

					console.log(data);
				},

				error:function(data){
					alert(JSON.stringify(data));
				}
			});
		});
	});
</script>