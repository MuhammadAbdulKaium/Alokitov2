
         <!-- modla header -->
         <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> Update Email/Login ID | Muhammad </h4>
         </div>
         <form id="employee-email-update" name="update-username" action="{{url('/employee/email/update', [$employeeInfo->id])}}" method="POST">
            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <div class="modal-body">
               <div class="row">
                  <div class="col-xs-12 col-md-12">
                     <div class="form-group">
                        <label class="control-label" for="email">Email/Login Id</label>
                        <input id="email" class="form-control" name="email" value="{{$employeeInfo->user()->email}}" maxlength="65" placeholder="Enter Email/Login ID" aria-required="true" type="text">
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

    <script type="text/javascript">
        $(document).ready(function(){

            // validate signup form on keyup and submit
            var validator = $("#employee-email-update").validate({
                // Specify validation rules
                rules: {
                    
                    email: {
                        required: true,
                        email: true,
                        minlength: 5,
                        maxlength:60,
                    }
                },

                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },

                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                    $(element).closest('.form-group').addClass('has-success');
                },

                debug: true,
                success: "valid",
                errorElement: 'span',
                errorClass: 'help-block',

                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },

                submitHandler: function(form) {
                    form.submit();
                }
            });


        });
    </script>