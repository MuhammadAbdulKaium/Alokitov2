<div class="modal-header">
           <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
           <h4 class="modal-title"><i class="fa fa-plus-square"></i> Add Section to <strong>{{$batchProfile->batch_name}} @if($batchProfile->get_division())({{$batchProfile->get_division()->name}})@endif</strong></h4>
        </div>
        <form id="manage-batch-section-add-form" action="{{url('academics/manage/section/store')}}" method="post">
           <input type="hidden" name="_token" value="{{csrf_token()}}">
           <div class="modal-body">
              <div class="row">
                 <div class="col-sm-6">
                    <div class="form-group">
                       <label class="control-label" for="academics_year">Academic year</label>
                       <select id="academics_year" class="form-control" name="academics_year" required>
                          <option value="" disabled selected>--- Select Academic Year ---</option>
                          @foreach($allAcademicsYears as $year)
                          <option value="{{$year->id}}">{{$year->year_name}}</option>
                          @endforeach
                       </select>
                       <div class="help-block"></div>
                    </div>
                 </div>
                 <div class="col-sm-6">
                    <div class="form-group">
                       <label class="control-label" for="batch">Batch Name</label>
                       <select id="batch" class="form-control" name="batch" required>
                          <option value="{{$batchProfile->id}}" selected>{{$batchProfile->batch_name}} @if($batchProfile->get_division())({{$batchProfile->get_division()->name}})@endif</option>
                       </select>
                       <div class="help-block"></div>
                    </div>
                 </div>
              </div>
              <div class="row">
                 <div class="col-sm-6">
                    <div class="form-group">
                       <label class="control-label" for="section">Section Name</label>
                       <input type="text" id="section" class="form-control" name="section" maxlength="20" placeholder="Enter Section Name" required>
                       <div class="help-block"></div>
                    </div>
                 </div>
                 <div class="col-sm-6">
                    <div class="form-group">
                       <label class="control-label" for="intake">Intake</label>
                       <input type="text" id="intake" class="form-control" name="intake" maxlength="20" placeholder="Enter Section Intake" required>
                       <div class="help-block"></div>
                    </div>
                 </div>
              </div>
             <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
               <p class="text-center">Showing All Sections of <strong>{{$batchProfile->batch_name}} @if($batchProfile->get_division())({{$batchProfile->get_division()->name}})@endif</strong></p>
                    <table class="table table-bordered text-center">
                       <tbody>
                          <tr>
                            <th>Section Name</th>
                            <th>Intake</th>
                            <th>Batch Name</th>
                            <th>Academic Year</th>
                          </tr>
                          @foreach($batchProfile->section() as $section)
                          <tr>
                            <td>{{$section->section_name}}</td>
                            <td>{{$section->intake}}</td>
                            <td>{{$section->batchName()->batch_name}} @if($batchProfile->get_division())({{$batchProfile->get_division()->name}})@endif</td>
                            <td>@if(!empty($section->academicsYear()))
                                {{$section->academicsYear()->year_name}}
                            @endif</td>
                          </tr>
                          @endforeach
                       </tbody>
                    </table>
                </div>
             </div>            
           </div>
           <!--./modal-body-->
           <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-left">Create</button>  <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
           </div>
           <!--./modal-footer-->
        </form>


<script type ="text/javascript">
$(document).ready(function(){
        // validate signup form on keyup and submit
      var validator = $("#manage-batch-section-add-form").validate({
                // Specify validation rules
                rules: {
                    intake: {
                        required: true,
                        number: true,
                        minlength: 1,
                        maxlength: 5,
                    },
                    batch:{
                      required: true,
                    },
                    section: 'required',
                    academic_year: 'required',
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
