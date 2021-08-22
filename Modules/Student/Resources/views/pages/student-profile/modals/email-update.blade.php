
         <!-- modla header -->
         <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title"><i class="fa fa-pencil-square-o"></i> Update Cadet Number </h4>
         </div>
         <form id="update-username" name="update-username" action="{{url('/student/profile/email/update', [$studentProfile->id])}}" method="POST">
            <input type="hidden" name="_token" value="{{csrf_token()}}">

            <div class="modal-body">
               <div class="row">
                  <div class="col-xs-12 col-md-12">
                     <div class="form-group">
                        <label class="control-label" for="email">Cadet Number</label>
                        <input id="email" class="form-control" name="username" value="{{$studentProfile->user()->username}}" maxlength="65" placeholder="Enter Cadet Number" aria-required="true" type="text">
                        <div class="help-block"></div>
                     </div>
                  </div>
<!--                   <div class="col-xs-12 col-md-12">
                     <div class="checkbox">
                        <label><input name="isResetPassword" value="1" type="checkbox">Reset Password</label>
                     </div>
                  </div> -->
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
                        minlength: 4,
                        maxlength:100,
                    },
                },

                // Specify validation error messages
                messages: {
                    email: {
                        required: 'Please enter Cadet Number',
                        email: 'Please enter a vaild Cadet Number',
                        minlength: "Cadet Number can't contain at most 4 characters.",
                        maxlength: 'Cadet Number should contain at most 60 characters.',
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

