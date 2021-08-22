

<div class="modal-header">
   <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
   <h3 class="box-title"><i class="fa fa-plus-square"></i> Create Designation</h3>
</div>
<form id="designation-create-form" action="{{url('/employee/designations/store')}}" method="POST">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
   <div class="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                                    <label class="control-label" for="department">Department</label>
                                    <select id="department" class="form-control" name="department">
                                        <option value="" selected disabled>Select Department</option>
                                        @if($allDepartments)
                                            @foreach($allDepartments as $department)
                                                <option value="{{$department->id}}" @if(old('department')==$department->id) selected="selected" @endif>{{$department->name}} </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <div class="help-block">
                                        @if ($errors->has('department'))
                                            <strong>{{ $errors->first('department') }}</strong>
                                        @endif
                                    </div>
                                </div>
                   </div>
                   <div class="col-sm-4">
                      <div class="form-group">
                         <label class="control-label" for="name">Designation</label>
                         <input id="name" class="form-control" name="name" maxlength="50" type="text">
                         <div class="help-block"></div>
                      </div>
                   </div>
                   <div class="col-sm-4">
                      <div class="form-group">
                         <label class="control-label" for="alias">Alias</label>
                         <input id="alias" class="form-control" name="alias" type="text">
                         <div class="help-block"></div>
                      </div>
                   </div>
                </div>
   </div>
   <div class="modal-footer">
      <button type="submit" class="btn btn-info pull-left"></i> Create</button>  <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
   </div>
</form>


  <script type="text/javascript">
    $(document).ready(function(){

      // validate signup form on keyup and submit
      var validator = $("#designation-create-form").validate({
                // Specify validation rules
                rules: {
                  department: {
                        required: true
                    },
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
