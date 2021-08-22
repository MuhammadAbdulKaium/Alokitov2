<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button">
		<span aria-hidden="true">×</span>
	</button>
	<h4 class="modal-title">
		<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Class Section Subject Student Report<br>
	</h4>
</div>

<form id="class-section-report-form" action="{{url('/academics/class/subject/student/list')}}" method="post" target="_blank">

	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" id="batch_name" name="batch_name">
	<input type="hidden" id="section_name" name="section_name">
	<input type="hidden" id="subject_name" name="subject_name">

	<div class="modal-body">
		<div class="row">
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="academic_level">Academic Level</label>
					<select id="academic_level" class="form-control academicLevel" name="academic_level" required>
						<option value="" selected disabled>--- Select Level ---</option>
						@foreach($allAcademicsLevel as $level)
							<option value="{{$level->id}}">{{$level->level_name}}</option>
						@endforeach
					</select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="batch">Batch</label>
					<select id="batch" class="form-control academicBatch" name="batch" onchange="" required>
						<option value="" selected disabled>--- Select Batch ---</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-4">
				<div class="form-group">
					<label class="control-label" for="section">Section</label>
					<select id="section" class="form-control academicSection" name="section" required>
						<option value="" selected disabled>--- Select Section ---</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="subject">Subject</label>
					<select id="subject" class="form-control academicSubject" name="subject" required>
						<option value="" selected disabled>--- Select Subject ---</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="doc_type">Type</label>
					<select id="doc_type" class="form-control" name="download_type" required>
						<option value="" disabled selected>--- Select Type ---</option>
						<option value="pdf">PDF</option>
						<option value="excel">Excel</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
	</div>
	<!--./body-->
	<div class="modal-footer">
		<button  type="submit" class="btn btn-info">Submit</button>
		<button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
	</div>
</form>

<script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    $(function() { // document ready

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
                    // console.log(level_id);
                },
                success:function(data){
                    op+='<option value="" selected disabled>--- Select Batch ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }
                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);
                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');
                },
                error:function(){
                    //
                }
            });
        });


        // request for section list using batch id
        jQuery(document).on('change','.academicBatch',function(){
            // get academic level id
            var batch_id = $(this).val();
            var batch_name = $(this ).find(":selected").text();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/section') }}",
                type: 'GET',
                cache: false,
                data: {'id': batch_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    //
                },

                success:function(data){
                    op+='<option value="" selected disabled>--- Select Section ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }
                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);

                    // set batch name
                    $('#batch_name').val(batch_name);

                },
                error:function(){
                    //
                },
            });
        });


        // request for section list using batch and section id
        jQuery(document).on('change','.academicSection',function(){
            // get academic level id
            var batch_id = $("#batch").val();
            var section_id = $(this).val();
            var section_name = $(this).find(":selected").text();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/subjcet') }}",
                type: 'GET',
                cache: false,
                data: {'class_id': batch_id, 'section_id':section_id}, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // statements
                },

                success:function(data){
                    op+='<option value="" selected disabled>--- Select Subject ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].sub_name+'</option>';
                    }
                    // set value to the academic batch
                    $('.academicSubject').html("");
                    $('.academicSubject').append(op);
                    $('#assessment_table_row').html('');

                    // set section name
                    $('#section_name').val(section_name);

                    // semester list reset
                    resetSemester();
                },
                error:function(){
                    // statements
                },
            });
        });

        // request for section list using batch and section id
        jQuery(document).on('change','.academicSubject',function() {
            var subject_name = $(this).find(":selected").text();
            // set section name
            $('#subject_name').val(subject_name);
        });

    });
</script>