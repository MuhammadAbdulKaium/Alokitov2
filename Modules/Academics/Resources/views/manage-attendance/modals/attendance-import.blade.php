

<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
	<h4 class="modal-title">
		<i class="fa fa-plus-square"></i> Attendance Upload
	</h4>
</div>
<form id="attendance_upload_form" action="{{url('/academics/attendance/upload')}}" method="POST" enctype="multipart/form-data">
	{{--<form id="attendance_upload_form" enctype="multipart/form-data">--}}
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<div class="modal-body">
		<div class="row">
			<div class="col-md-12">
				<div class="callout callout-info">
					<h4><i class="fa fa-bullhorn" aria-hidden="true"></i> Instructions :</h4>
					<ol>
						{{--<li><b>The field with red color are required.</b></li>--}}
						<li>All date must be enter <strong>DD-MM-YYYY</strong> format.</li>
						<li>Attendance Type must be <strong>A / P / L</strong> format</li>
						{{--<li>Student ID is auto generated.</li>--}}
						<li>Max <strong>7 days</strong> attendance can be upload.</li>
						{{--<li>Email ID should be in valid email format.</li>--}}
						<li>Max upload records limit is <strong>300</strong>.</li>
						<li>Attendance import data must match with current application language.</li>
					</ol>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">
				<div class="form-group">
					<label class="control-label" for="attendance_list">Document</label>
					<input id="attendance_list" name="attendance_list" title="Browse Document" type="file" required>
					<div class="hint-block">NOTE : Upload only <b>.xlsx</b> file and smaller than 512KB</div>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
	</div>
	<!--./modal-body-->
	<div class="modal-footer">
		<button type="button" id="submitBtn" class="btn btn-primary"><i class="fa fa-upload"></i> Import</button> <button data-dismiss="modal" class="btn btn-default " type="button">Close</button>
	</div>
	<!--./modal-footer-->
</form>



<script src="{{ asset('js/fc/moment.js') }}" type="text/javascript"></script>
<script src="{{asset('js/jquery.form.js')}}" type="text/javascript"></script>
{{--<script src="{{URL::asset('js/jquery.redirect.js')}}" type="text/javascript"></script>--}}

<script type="text/javascript">
    $(document).ready(function () {
        // submit button click action
        $("#submitBtn").click(function () {
            // checking
            if($('#attendance_list').val()){

                // modal
                $("#globalModal").modal();
                // progressbar
                var progressbar = $('.progress-bar');
                // form submit
                $("#attendance_upload_form").ajaxForm({
                    beforeSend: function() {
                        $(".progress").css("display","block");
                        progressbar.width('0%');
                        progressbar.text('0%');
                    },
                    uploadProgress: function (event, position, total, percentComplete) {
                        progressbar.width(percentComplete + '%');
                        progressbar.text(percentComplete + '%');
                    },
                    success:function(data){
                        $("#globalModal").modal('toggle');
                        var attendanceContainer =  $('#attendanceContainer');
                        attendanceContainer.html('');
                        attendanceContainer.append(data);
                    },

                    error:function () {
                        // sweet alert
                        swal("Error", 'Please check import file format.', "error");
                    }
                }).submit();
            }else{
                // sweet alert
                swal("Warning", 'No file Chosen.', "warning");
            }
        });
    });
</script>
