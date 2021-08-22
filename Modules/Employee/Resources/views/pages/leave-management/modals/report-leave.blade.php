<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
	<h4 class="modal-title">
		<i class="fa fa-info-circle"></i> Leave Reports
	</h4>
</div>

<form id="employee_leave_search_form" action="{{url('/employee/manage/leave/report')}}" method="post" target="_blank">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="box-body">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="category">Category</label>
					<select id="category" class="form-control category" name="category" required>
						<option value="" selected>--- Select Category ---</option>
						<option value="1"> Teaching </option>
						<option value="0"> Non - Teaching </option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="department">Department</label>
					<select id="department" class="form-control department" name="department">
						<option value="" selected disabled>--- Select Department ---</option>
						@if($allDepartments)
							@foreach($allDepartments as $department)
								<option value="{{$department->id}}">{{$department->name}}</option>
							@endforeach
						@endif
					</select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="designation">Designation</label>
					<select id="designation" class="form-control designation" name="designation">
						<option value="" selected disabled>--- Select Designation ---</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>

		</div>
	</div>
	<div class="box-footer">
		<button id="teacher_list_search_btn" type="submit" class="btn btn-primary pull-right">Search</button>
		<button type="reset" class="btn btn-default pull-left">Reset</button>
	</div>
</form>

<script>
    jQuery(document).ready(function () {

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
            $(this).slideUp('slow', function () {
                $(this).remove();
            });
        });

        // request for section list using batch id
        jQuery(document).on('change','.department',function(){
            // get academic level id
            var dept_id = $(this).val();
            var op="";

            $.ajax({
                url: '/employee/find/designation/list/'+dept_id,
                type: 'GET',
                cache: false,
                datatype: 'application/json',

                beforeSend: function() {
                    //
                },

                success:function(data){
                    op+='<option value="" selected disabled>--- Select Designation ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                    }
                    // refresh attendance container row
                    $('#employee_list_container').html('');
                    // set value to the academic batch
                    $('.designation').html("");
                    $('.designation').append(op);
                },
                error:function(){
                    // sweet alert
                    swal("Error", 'Unable to load data form server', "error");
                }
            });
        });
        // request for section list using batch id
        jQuery(document).on('change','.designation',function(){
            // get academic level id
            // refresh attendance container row
            $('#employee_list_container').html('');
        });
        // request for section list using batch id
        jQuery(document).on('change','.category',function(){
            // get academic level id
            // refresh attendance container row
            $('#employee_list_container').html('');
        });
    });

</script>