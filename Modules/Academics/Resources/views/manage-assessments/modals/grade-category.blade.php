
@if($gradeCategoryProfile)
<form id="add-assessment-form" action="{{url('academics/manage/assessments/category/update',[$gradeCategoryProfile->id])}}" method="POST">
@else
<form id="add-assessment-form" action="{{url('academics/manage/assessments/category/store')}}" method="POST">
@endif
   <!-- modal header -->
   <div class="modal-header">
      <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
      <h4 class="modal-title">
         <i class="fa fa-info-circle"></i>@if($gradeCategoryProfile) Update @else Add @endif Grading Category
      </h4>
   </div>
   <!--modal-header-->
   <div class="modal-body">
      <div class="row">
         <div class="col-sm-10 col-sm-offset-1">
            <div class="form-group">
               <label class="control-label" for="name">Category Name</label>
               <input type="hidden" name="_token" value="{{csrf_token()}}">
               <input id="name" name="name" value="@if($gradeCategoryProfile){{$gradeCategoryProfile->name}}@endif" maxlength="35" class="form-control" type="text" placeholder="Category Name">
               <label>
                  <input type="hidden" name="is_sba" value="0">
                  <input type="checkbox" name="is_sba" style="cursor:pointer" @if($gradeCategoryProfile){{$gradeCategoryProfile->is_sba==1?"checked":''}}@endif value="1"> IS SBA ???
               </label>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
   </div>
   <!-- modal footer -->
   <div class="modal-footer">
      <button type="submit" class="btn btn-success pull-left">Submit</button>
      <a class="btn btn-default pull-right" href="#" data-dismiss="modal">Cancel</a>
   </div>
</form>

<script type ="text/javascript">
   $(document).ready(function(){
      // validate signup form on keyup and submit
      $("#add-assessment-form").validate({
         // Specify validation rules
         rules: {
            name: {
               required: true,
               minlength: 1,
               maxlength: 30,
            },
         },

         // Specify validation error messages
         messages: {},

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
      }); // validation form ends here
   });
</script>
