
<form id="assessment-cat-assign-form" action="" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-header">
		<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
		<h4 class="modal-title text-bold">
			<i class="fa fa-info-circle"></i> Assessment Assignment (Count Best Result)
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
		<div class="row">
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="section">Section</label>
					<select id="section" class="form-control academicSection" name="section">
						<option value="" selected disabled>--- Select Section ---</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
			<div class="col-sm-6">
				<div class="form-group">
					<label class="control-label" for="semester">Semester</label>
					<select id="semester" class="form-control academicSemester" name="semester">
						<option value="" selected disabled>--- Select Semester ---</option>
					</select>
					<div class="help-block"></div>
				</div>
			</div>
		</div>

		<div class="row" id="semester-list-container">
			{{--semester list will be displayed here--}}
		</div>
	</div>
	<div class="modal-footer">
		<a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
		<button id="assessment-cat-assign-submit-btn" class="btn btn-success pull-right hide" type="submit">Submit</button>
	</div>
</form>

<script>
    $(document).ready(function () {


        // request for section list using batch and section id
        $('form#assessment-cat-assign-form').on('submit', function (e) {
            e.preventDefault();
            // add request type
            $(this).append('<input type="hidden" name="request_type" value="ASSIGN" />');

            // ajax request
            $.ajax({
                url: '/academics/manage/assessments/grade/category/manage-assign',
                type: 'POST',
                cache: false,
                data: $('form#assessment-cat-assign-form').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                   waitingDialog.show('Submitting...');
                },

                success:function(data){
                   // hide waiting dialog
                   waitingDialog.hide();
                   // checking
					if(data.status=='success'){
                        //  refresh timetable row
                        $('#semester-list-container').html('');
                        $('#semester-list-container').html(data.html);
                        // sweet alert success
                        swal("Success", 'Best Result Count Submitted', "success");
					}else{
                        // sweet alert warning
                        swal("Warning", data.msg, "warning");
					}
                },

                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // sweet alert error
                    swal("Warning", 'No Response form server', "warning");
                }
            });
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
                    op+='<option value="" selected disabled>--- Select Batch ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);

                    // set value to the academic section
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');

                    // set value to the shift
                    $('.academicShift option:first').prop('selected',true);

                    //  refresh timetable row
                    $('#semester-list-container').html('');
                    // semester reset
                    resetSemester()
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

                    // set value to the academic batch
                    $('.academicSection').html('');
                    $('.academicSection').append(op);

                    // set value to the shift
                    $('.academicShift option:first').prop('selected',true);

                    //  refresh timetable row
                    $('#semester-list-container').html('');
                    // semester reset
                    resetSemester()

                },
                error:function(){
                    // statements
                }
            });
        });


        // request for section list using batch and section id
        jQuery(document).on('change','.academicSection',function(){
            // set value to the shift
            $('.academicShift option:first').prop('selected',true);

            //  refresh timetable row
            $('#semester-list-container').html('');
            // semester reset
            resetSemester()
        });

        // request for section list using batch and shift id
        jQuery(document).on('change','.academicSemester',function(){
            // get academic level id
            var level_id = $('#academic_level').val();
            var batch_id = $("#batch").val();
            var section_id = $('#section').val();
            var semester_id = $(this).val();

            // ajax request
            $.ajax({
                url: '/academics/manage/assessments/grade/category/manage-assign',
                type: 'POST',
                cache: false,
                data: {
                    '_token': '{{csrf_token()}}',
                    'academic_level': level_id,
                    'batch': batch_id,
                    'section': section_id,
                    'semester': semester_id,
                    'request_type':'LIST'
                },
                datatype: 'html',

                beforeSend: function() {
                    $('#assessment-cat-assign-submit-btn').addClass('hide');
                },

                success:function(data){
                    // submit btn toggle
                    $('#assessment-cat-assign-submit-btn').removeClass('hide');
                    //  refresh timetable row
                    $('#semester-list-container').html('');
                    $('#semester-list-container').html(data);
                },
                error:function(){
                    // statements
                }
            });

        });


        // reset semester list
        function  resetSemester() {
            // get academic batch id
            var batch_id = $("#batch").val();
            // get academic level id
            var level_id = $("#academic_level").val();
            // select option
            var op="";
            // checking
            if(batch_id && level_id){
                // ajax request
                $.ajax({
                    url: "/academics/find/batch/semester",
                    type: 'GET',
                    cache: false,
                    data: {'batch': batch_id, 'academic_level':level_id}, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        op+='<option value="" selected disabled>--- Select Semester ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                        }
                        // set value to the academic semester
                        $('.academicSemester').html('');
                        $('.academicSemester').append(op);
                    },
                    error:function(){
                        // statements
                    },
                });
            }else{
                op+='<option value="" selected disabled>--- Select Semester ---</option>';
                // set value to the academic semester
                $('.academicSemester').html('');
                $('.academicSemester').append(op);
            }
        }


    });
</script>