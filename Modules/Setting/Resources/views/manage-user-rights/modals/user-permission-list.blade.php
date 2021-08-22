@if($allPermission->count() > 0)
	{{--lable--}}
	<p class="text-center text-bold bg-green-active">User Permission list</p>
	<table class="table table-striped table-bordered">
		<thead>
		<tr>
			<th class="text-center">#</th>
			<th>Name</th>
			{{--<th>Display Name</th>--}}
			<th>Description</th>
			<th class="text-center">Status</th>
			<th class="text-center">Validity</th>
			<th class="text-center">Action</th>
		</tr>
		</thead>
		<tbody>
		@foreach($allPermission as $index=>$permission)
			<tr id="permission_{{$permission->id}}">
				<td class="text-center">{{$index+1}}</td>
				<td><a href="#">{{$permission->name}}</a></td>
				<td>{{$permission->description}}</td>
				<td class="text-center">
					<a data-key="status" data-id="{{$permission->id}}" class="permission-status-delete" title="Status">
						<i id="status_{{$permission->id}}" class="{{array_key_exists($permission->id, $userPermissionArrayList)?'fa fa-check text-green':'fa fa-ban text-red'}}" aria-hidden="true"></i>
					</a>
				</td>
				<td class="text-center">
					{{--checking user permission--}}
					@if(array_key_exists($permission->id, $userPermissionArrayList))
						{{--user permission--}}
						@php $userPermission = (object) $userPermissionArrayList[$permission->id]; @endphp
						<input id="datetime_{{$permission->id}}" type="text" class="form-control text-center datetime {{$userPermission->date_text_color}}" value="{{$userPermission->date_time}}">
					@else
						<input id="datetime_{{$permission->id}}" type="text" class="form-control text-center datetime" value="">
					@endif
				</td>
				<td class="text-center">
					<a id="{{$permission->id}}" class="btn btn-success submit-permission"> Submit </a>
				</td>
			</tr>
		@endforeach
		</tbody>
	</table>
@else
	<div class="alert-warning alert-auto-hide alert fade in text-center text-bold" style="opacity: 474.119;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h4><i class="fa fa-warning"></i> No records found. </h4>
	</div>
@endif

<script>
    $(document).ready(function () {
        $('.datetime').datetimepicker({
            format: 'yyyy-mm-dd hh:ii:ss',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            forceParse: 0,
            showMeridian: 1
        });

        // submit-permission
        $('.submit-permission').click(function () {
            // user / teacher / employee
            var user_id = $('#employee').val();
            // permission id
            var permission = $(this).attr('id');
            // find permission validity time
            var validity = $('#datetime_'+permission).val();

            // checking
            if(permission && validity){
                // ajax request
                $.ajax({
                    url: '/setting/user-permission/',
                    type: 'POST',
                    cache: false,
                    data: {'user_id': user_id, 'permission_id':permission, 'valid_up_to':validity, '_token':'{{csrf_token()}}' },
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Submitting...');
                    },

                    success:function(data){
                        // waitingDialog
                        waitingDialog.hide();
                        // checking status
                        if(data.status){
                            //status
                            var status = $('#status_'+permission);
                            // change status text and color
                            status.removeClass('fa-ban text-red');
                            status.addClass('fa-check text-green');
                            // datetime
                            var datetime = $('#datetime_'+permission);
                            datetime.removeClass('text-red text-green');
                            datetime.addClass(data.date_text_color);
                            // success alert
                            swal('Success', data.msg, 'success');
                        }else{
                            // warning alert
                            swal('Warning', data.msg, 'warning');
                        }
                    },

                    error:function(data){
                        // waitingDialog
                        waitingDialog.hide();
                        // error alert
                        swal('Error', 'No response form server', 'error');
                    }
                });
            }else{
                // warning alert
                swal('Warning', 'Please Check All inputs are selected !!!', 'warning');
            }
        });


    });
</script>
