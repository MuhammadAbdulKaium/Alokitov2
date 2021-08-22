

<div class="modal-header">
   <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
   <h3 class="box-title"><i class="fa fa-plus-square"></i> Update Department</h3>
</div>
<form id="department-update-form" action="{{url('/employee/departments/update', [$departmentProfile->id])}}" method="POST">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
   <div class="modal-body">
                <div class="row">
                   <div class="col-sm-6">
                      <div class="form-group">
                         <label class="control-label" for="name">Department</label>
                         <input id="name" class="form-control" value="@if($departmentProfile){{$departmentProfile->name}}@endif" name="name" maxlength="50" type="text">
                         <div class="help-block"></div>
                      </div>
                   </div>
                   <div class="col-sm-6">
                      <div class="form-group">
                         <label class="control-label" for="alias">Alias</label>
                         <input id="alias" class="form-control" value="@if($departmentProfile){{$departmentProfile->alias}}@endif" name="alias" type="text">
                         <div class="help-block"></div>
                      </div>
                   </div>
                </div>
   </div>
   <!--./modal-body-->
   <div class="modal-footer">
      <button type="submit" class="btn btn-info pull-left"></i> Update</button>  <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
   </div>
   <!--./modal-footer-->
</form>


  <script type="text/javascript">
    $(document).ready(function(){

      // validate signup form on keyup and submit
      var validator = $("#department-update-form").validate({
                // Specify validation rules
                rules: {
                  name: {
                        required: true,
                        minlength: 1,
                        maxlength: 35,
                    },
                  alias: {
                        required: true,
                        minlength: 1,
                        maxlength: 35,
                    },
                },

                // Specify validation error messages
                messages: {
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
