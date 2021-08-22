
<!-- modla header -->
<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
	<h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> Update Email/Login ID </h4>
</div>
<form id="update-username" name="update-username" action="{{url('/admission/applicant/email/update')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" name="applicant_id" value="{{$applicantProfile->id}}">

	<div class="modal-body">
		<div class="row">
			<div class="col-xs-12 col-md-12">
				<div class="form-group">
					<label class="control-label" for="email">Email/Login Id</label>
					<input id="email" class="form-control" name="email" value="{{$applicantProfile->email}}" maxlength="65" placeholder="Enter Email/Login ID" type="text" required>
					<div class="help-block"></div>
				</div>
			</div>
		</div>
	</div>
	<!-- modal footer -->
	<div class="modal-footer">
		<button type="submit" class="btn btn-info pull-left">Update</button> <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
	</div>
</form>

<script type ="text/javascript">
    jQuery(document).ready(function () {

        jQuery("form[name='update-username']").validate({
            // Specify validation rules
            rules: {
                email: {
                    required: true,
                    email: true,
                    minlength: 5,
                    maxlength:100,
                },
            },

            // Specify validation error messages
            messages: {
                email: {
                    required: 'Please enter student email address',
                    email: 'Please enter a vaild email address',
                    minlength: "Email address can't contain at most 100 characters.",
                    maxlength: 'Email address should contain at most 60 characters.',
                },

            },

            // highlight: function(element) {
            //    $(element).closest('.form-group').addClass('has-error');
            // },

            // unhighlight: function(element) {
            //    $(element).closest('.form-group').removeClass('has-error');
            // }

            submitHandler: function(form) {
                form.submit();
            }


        });



    });

</script>

