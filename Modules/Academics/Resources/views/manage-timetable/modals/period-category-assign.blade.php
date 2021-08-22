
<form action="{{url('/academics/timetable/period/category/store/')}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title text-bold">
			<i class="fa fa-info-circle"></i> Class-Period Category Assignment
		</h4>
	</div>

{{--batch string--}}
@php $batchString="Class"; @endphp

	<!--modal-header-->
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label" for="academic_level">Academic Level</label>
					<select id="level" class="form-control academicLevel" name="level">
						<option value="" selected disabled>--- Select Level ---</option>
						@foreach($allAcademicsLevel as $level)
							<option value="{{$level->id}}">{{$level->level_name}}</option>
						@endforeach
					</select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label" for="batch">{{$batchString}}</label>
					<select id="batch" class="form-control academicBatch" name="batch" onchange="">
						<option value="" selected disabled>--- Select {{$batchString}} ---</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label" for="section">Section</label>
					<select id="section" class="form-control academicSection" name="section">
						<option value="" selected disabled>--- Select Section ---</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-3">
				<div class="form-group">
					<label class="control-label" for="shift">Shift</label>
					<select id="shift" class="form-control academicShift" name="shift">
						<option value="" selected disabled>--- Select Shift ---</option>
						<option value="0">Day</option>
						<option value="1">Morning</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
		<div id="class_period_category_history" class="row hide">
			<hr/>
			<div class="col-sm-10 col-sm-offset-1">
				<h5 class="text-bold text-center">Class period Category Details</h5>
				<input type="hidden" id="cs_period_id" value="0">
				<table class="table text-center table-bordered table-responsive table-striped">
					<thead>
					<tr>
						<th>Academic Details</th>
						<th>Shift</th>
						<th>Status</th>
						<th>Period Category</th>
						<th>Action</th>
					</tr>
					</thead>
					<tbody>
					<tr>
						<td id="period_academic_details_container">{{--Academic Details--}}</td>
						<td id="period_academic_shift_container">{{--Shift--}}</td>
						<td id="period_academic_status_container"> {{--Status--}} </td>
						<td>
							<div class="form-group">
								<select id="periodCategory" class="form-control periodCategory" name="periodCategory" required>
									<option value="" selected disabled>--- Select Category ---</option>
								</select>
								<div class="help-block"></div>
							</div>
						</td>
						<td id="period_academic_details_container">
							<button id="period_category_assign_btn" type="button" class="btn btn-success">Submit</button>
						</td>
					</tr>
					</tbody>
				</table>
			</div>
		</div>

	</div>
	<div class="modal-footer">
		{{--<button type="submit" class="btn btn-info pull-left">Submit</button>--}}
		<a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
	</div>
</form>

<script>
    $(document).ready(function () {

        $('#period_category_assign_btn').click(function () {
            // assign request type
            var period_category_id = $('#periodCategory').val();
            // checking
            if(period_category_id){
                // get academic level id
                var level_id = $('#level').val();
                var level_name = $('#level option:selected').text();

                var batch_id = $("#batch").val();
                var batch_name = $("#batch option:selected").text();

                var section_id = $('#section').val();
                var section_name = $('#section').find("option:selected").text();

                var shift_id = $('#shift').val();
                var shift_name = $('#shift').find("option:selected").text();

                // period_category_id
                var cs_period_id  = $('#cs_period_id').val();
                // ajax request
                $.ajax({
                    url: "{{ url('/academics/timetable/period/category/assign-manage') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        'academic_level':level_id,
                        'batch': batch_id,
                        'section':section_id,
                        'shift':shift_id,
                        'request_type':'assign',
                        'period_category_id':period_category_id,
                        'cs_period_id':cs_period_id
                    },
                    datatype: 'application/json',

                    // beforeSend action
                    beforeSend: function() { },

                    // success action
                    success:function(data){
                        if(data.status=='success'){
                            // update cat id
                            $('#cs_period_id').val(data.cat_id);
                            $('#period_academic_status_container').html('Assigned');
                            $('#period_category_assign_btn').html('Submit');
                        }else{
                            //
                        }
                    },
                    // error action
                    error:function(){ }
                });
            }else{
                alert('Please select a Period category');
            }
        });

        // request for batch list using level id
        jQuery(document).on('change','.periodCategory',function(){
            $('#period_category_assign_btn').html('Update');
        });


        // request for batch list using level id
        jQuery(document).on('change','.academicLevel',function(){
            // get academic level id
            var level_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/batch') }}",
                type: 'GET',
                cache: false,
                data: {'id': level_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // statements
                },

                success:function(data){
                    op+='<option value="" selected disabled>--- Select {{$batchString}} ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }
                    //  refresh timetable row
                    $('#timetableContainer').html('');
                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);
                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');

                    $('.academicShift option:first').prop('selected',true);
                    $('#class_period_category_history').addClass('hide');
                    $('#period_assigned_btn').addClass('hide');
                    $('#period_assign_btn').addClass('hide');
                },

                error:function(){
                    // statements
                }
            });
        });


        // request for section list using batch id
        jQuery(document).on('change','.academicBatch',function(){
            // get academic level id
            var batch_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/section') }}",
                type: 'GET',
                cache: false,
                data: {'id': batch_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // statements
                },

                success:function(data){
                    op+='<option value="" selected disabled>--- Select Section ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }

                    $('.academicShift option:first').prop('selected',true);
                    $('.academicSection option:first').prop('selected',true);
                    //  refresh timetable row
                    $('#timetableContainer').html('');
                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);

                    $('#class_period_category_history').addClass('hide');
                    $('#period_assigned_btn').addClass('hide');
                    $('#period_assign_btn').addClass('hide');
                },
                error:function(){
                    // statements
                },
            });
        });


        // request for section list using batch and section id
        jQuery(document).on('change','.academicSection',function(){
            $('.academicShift option:first').prop('selected',true);
        });

        // request for section list using batch and shift id
        jQuery(document).on('change','.academicShift',function(){
            // get academic level id
            var level_id = $('#level').val();
            var level_name = $('#level option:selected').text();

            var batch_id = $("#batch").val();
            var batch_name = $("#batch option:selected").text();

            var section_id = $('#section').val();
            var section_name = $('#section').find("option:selected").text();

            var shift_id = $(this).val();
            var shift_name = $(this).find("option:selected").text();

            $.ajax({
                url: "{{ url('/academics/timetable/period/category/assign-manage') }}",
                type: 'GET',
                cache: false,
                data: {
                    'academic_level':level_id,
                    'batch': batch_id,
                    'section':section_id,
                    'shift':shift_id,
                    'request_type':'check'
                },
                datatype: 'application/json',

                // beforeSend action
                beforeSend: function() {
                    $('#period_assign_btn').addClass('hide');
                    $('#period_update_btn').addClass('hide');
                },

                // success action
                success:function(data){
                    $('#class_period_category_history').removeClass('hide')
                    $('#period_academic_details_container').html(level_name+' / '+batch_name+' ('+section_name+')');
                    $('#period_academic_shift_container').html(shift_name);

                    var op = null;
                    var cat_list = data.cat_list;

                    op+='<option value="" selected disabled>--- Select Category ---</option>';
                    for(var i=0;i<cat_list.length;i++){
                        op+='<option value="'+cat_list[i].id+'">'+cat_list[i].name+'</option>';
                    }
                    $('#periodCategory').html('');
                    $('#periodCategory').append(op);

                    // checking
                    if(data.status=='success'){
                        $('#period_update_btn').removeClass('hide');
                        $('#period_academic_status_container').html('Assigned');
                        $('#cs_period_id').val(data.cat_id);

                        var csp_id = data.csp_id;
                        $('#periodCategory option[value='+csp_id+']').attr('selected','selected');
                    }else{
                        $('#period_assign_btn').removeClass('hide');
                        $('#period_academic_status_container').html('Not Assigned');
                        $('#cs_period_id').val(data.cat_id);
                    }
                },
                // error action
                error:function(){ }
            });
        });

    });
</script>