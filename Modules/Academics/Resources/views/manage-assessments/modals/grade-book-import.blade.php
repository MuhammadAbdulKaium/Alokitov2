

<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
	<h4 class="modal-title">
		<i class="fa fa-plus-square"></i> Grade Upload
	</h4>
</div>
<form id="grade-upload-form" action="{{url('/academics/manage/assessments/gradebook/upload')}}" method="post" enctype="multipart/form-data">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-body">
		<div class="row">
			<div class="col-md-12">
				<div class="callout callout-info">
					<h4><i class="fa fa-bullhorn" aria-hidden="true"></i> Instructions :</h4>
					<ol>
						{{--<li><b>The field with red color are required.</b></li>--}}
						<li>All date must be enter <strong>DD-MM-YYYY</strong> format.</li>
						{{--<li>Student ID is auto generated.</li>--}}
						<li>Birth date must be less than current date.</li>
						{{--<li>Email ID should be in valid email format.</li>--}}
						<li>Max upload records limit is <strong>300</strong>.</li>
						<li>Grade import data must match with current application language.</li>
					</ol>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label" for="studocs-stu_docs_path">Document</label>
					<input id="image" name="grade_book" title="Browse Document" type="file" required>
					<div class="hint-block">NOTE : Upload only .xlsx file and smaller than 512KB</div>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
	</div>
	<!--./modal-body-->
	<div class="modal-footer">
		<button type="button" id="submitBtn" class="btn btn-success"><i class="fa fa-upload"></i> Import</button> <button data-dismiss="modal" class="btn btn-default " type="button">Close</button>
	</div>
	<!--./modal-footer-->
</form>



{{--<script src="http://malsup.github.com/jquery.form.js"></script>--}}
{{--<script src="{{URL::asset('js/jquery.redirect.js')}}" type="text/javascript"></script>--}}
<script src="{{URL::asset('js/jquery.form.js')}}" type="text/javascript"></script>

<script type="text/javascript">
    $(document).ready(function(){

        $("#submitBtn").click(function () {
            // checking
            if($('#image').val()){

                $('#grade-upload-form')
                    .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                    .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                    .append('<input type="hidden" name="section" value="'+$('#section').val()+'"/>')
                    .append('<input type="hidden" name="subject" value="'+$('#subject').val()+'"/>')
                    .append('<input type="hidden" name="semester" value="'+$('#semester').val()+'"/>');

                $("#globalModal").modal();

                var progressbar     = $('.progress-bar');

                $("#grade-upload-form").ajaxForm({
                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Submitting...');
                    },

                    success:function(data){
                        $("#globalModal").modal('toggle');
                        $('#assessment_table_row').html('');
                        $('#assessment_table_row').append(data);
                        // hide dialog
                        waitingDialog.hide();
                    }

                }).submit();
            }else{
                alert('No file selected');
            }
        });
    });
</script>
