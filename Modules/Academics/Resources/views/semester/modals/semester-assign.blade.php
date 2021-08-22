
<form action="{{url('/academics/timetable/period/category/store/')}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title text-bold">
			<i class="fa fa-info-circle"></i> Semester Assignment
		</h4>
	</div>
	<!--modal-header-->
	<div class="modal-body">
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="academic_level">Academic Level</label>
					<select id="academic_level" class="form-control academicLevel" name="academic_level">
						<option value="" selected disabled>--- Select Level ---</option>
						@foreach($allAcademicsLevel as $level)
							<option value="{{$level->id}}">{{$level->level_name}}</option>
						@endforeach
					</select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="batch">Batch</label>
					<select id="batch" class="form-control academicBatch" name="batch" onchange="">
						<option value="" selected disabled>--- Select Batch ---</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
		<div id="semester-list-container">
			{{--semester list will be displayed here--}}
		</div>

	</div>
	<div class="modal-footer">
		<a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
	</div>
</form>

<script>
    $(document).ready(function () {

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
                    op+='<option value="" selected disabled>--- Select Batch ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }
                    //  refresh timetable row
                    $('#semester-list-container').html('');
                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);
                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');
                },

                error:function(){
                    // statements
                }
            });
        });


        // request for section list using batch id
        jQuery(document).on('change','.academicBatch',function(){
            // get academic level id
            var level_id = $('#academic_level').val();
            var batch_id = $(this).val();
            // ajax request
            $.ajax({
                url: "{{ url('/academics/semester/batch/semester') }}",
                type: 'GET',
                cache: false,
                data: {'batch_id': batch_id, 'level_id': level_id, 'return_type': 'list' },
                datatype: 'html',

                beforeSend: function() {
                    // statements
                },

                success:function(data){
                    //  refresh timetable row
                    $('#semester-list-container').html('');
                    $('#semester-list-container').html(data);
                },
                error:function(){
                    // statements
                },
            });
        });
    });
</script>