
@extends('setting::manage-user-rights.index')

{{--page content--}}
@section('page-content')
	<link href="{{ asset('css/bootstrap-datetimepicker.min.css') }}" rel="stylesheet">

	<div class="row">
		<div class="col-md-6 col-md-offset-3">
			<div class="form-group">
				<label class="control-label" for="employee">Teacher / Employee</label>
				<select id="employee" class="form-control select2 " name="employee" required>
					<option value="" selected disabled>--- Select Teacher / Employee ---</option>
					{{--employee list checking--}}
					@if($employeeList->count()>0)
						{{--employee list looping--}}
						@foreach($employeeList as $index=>$employee)
							{{--department--}}
							@php $department = $employee->department()->name; @endphp
							{{--designation--}}
							@php $designations = $employee->designation()->name; @endphp
							{{--set employee--}}
							<option value="{{$employee->user_id}}">
								{{$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name.' ('.$designations.', '.$department.')'}}
							</option>
						@endforeach
					@endif
				</select>
				<div class="help-block"></div>
			</div>
		</div>
		<div class="col-md-12">
			{{-- data table container --}}
			<div id="user_permission_table"></div>
		</div>
	</div>
@endsection


{{--page scripts--}}
@section('page-script')
	<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}" type="text/javascript"></script>
	<script>
        $(document).ready(function () {

            $('.select2').select2();

            // request for institute module list
            jQuery(document).on('change','#employee',function(e){
                e.preventDefault();

                // employee_id
                var employee_user_id = $(this).val();
                // checking employee
                if(employee_user_id){
                    // ajax request
                    $.ajax({
                        url: '/setting/user-permission/',
                        type: 'GET',
                        cache: false,
                        data: {'user_id': employee_user_id, '_token':'{{csrf_token()}}' },
                        datatype: 'application/json',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Submitting...');
                            $('#user_permission_table').html('');
                        },

                        success:function(data){
                            // waitingDialog
                            waitingDialog.hide();
                            // set permission list
                            var user_permission_table =  $('#user_permission_table');
                            user_permission_table.html('');
                            user_permission_table.append(data);
                        },

                        error:function(){
                            // waitingDialog
                            waitingDialog.hide();
                        }
                    });
                }else{
                    // employee not found warning
                    swal('Warning', 'No Employee Found', 'warning');

                }
            });
        });
	</script>
@endsection