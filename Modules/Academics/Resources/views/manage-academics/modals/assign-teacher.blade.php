  <!-- select2 -->
  <link href="{{ URL::asset('css/select2.min.css') }}" rel="stylesheet"/>
  <link href="{{ URL::asset('css/select2-addl.min.css') }}" rel="stylesheet"/>
  <link href="{{ URL::asset('css/select2-krajee.min.css') }}" rel="stylesheet"/>
      <div class="modal-header">
           <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
           <h4 class="modal-title"><i class="fa fa-plus-square"></i> Subject Teacher for {{$classSubjectProfile->batch()->batch_name}}@if($classSubjectProfile->batch()->get_division()) ({{$classSubjectProfile->batch()->get_division()->name}})@endif <i>{{$classSubjectProfile->section()->section_name}} Section</i></h4>
        </div>
        <form id="assign-teacher-form" action="{{url('academics/manage/subjcet/teacher/store')}}" method="post">
           <input type="hidden" name="_token" value="{{csrf_token()}}">
           <div class="modal-body">
              <div class="row">
                 <div class="col-sm-6">
                    <div class="form-group">
                       <label class="control-label" for="cs_id">Class Subject</label>
                       <select id="cs_id" class="form-control" name="cs_id" required>
                          <option value="{{$classSubjectProfile->id}}">{{$classSubjectProfile->subject()->subject_name}}</option>
                       </select>
                       <div class="help-block"></div>
                    </div>
                 </div>

                 <div class="col-sm-6">
                    <div class="form-group">
                       <label class="control-label" for="teacher_id">Class Teacher</label>
                       <select id="teacher_id" class="form-control select2 select2-hidden-accessible" style="width: 100%;"  name="teacher_id" required>
                            <option value="" selected="selected">-- Selecte Teacher --</option>
                            @foreach($teacherList as $teacher)
                            <option value="{{$teacher->id}}">{{$teacher->first_name." ".$teacher->middle_name." ".$teacher->last_name}}</option>
                            @endforeach
                       </select>
                       <div class="help-block"></div>
                    </div>
                 </div>

              </div>
              <div class="row">
                 <div class="col-sm-6">
                    <div class="form-group">
                       <label class="control-label" for="teacher_status">Teacher Staus</label>
                        <select id="teacher_status" class="form-control" name="teacher_status" required>
                          <option value="">--- Select Status ---</option>
                          <option value="PERMANENT"> Permanent </option>
                          <option value="PROVISIONAL"> Provisional </option>
                       </select>
                       <div class="help-block"></div>
                    </div>
                 </div>
                 <div class="col-sm-6">
                    <div class="form-group">
                       <label class="control-label" for="is_active">Action</label>
                       <select id="is_active" class="form-control" name="is_active" required>
                          <option value="">--- Select Action ---</option>
                          <option value="1"> Active </option>
                          <option value="0"> Deactive </option>
                       </select>
                       <div class="help-block"></div>
                    </div>
                 </div>
              </div>
           </div>
           <!--./modal-body-->
           <div class="modal-footer">
              <button type="submit" class="btn btn-info pull-left">Create</button>  <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
           </div>
           <!--./modal-footer-->
        </form>

  <script src="{{ URL::asset('js/select2.full.min.js') }}"></script>
  <script type ="text/javascript">
      $(document).ready(function () {
         //Initialize Select2 Elements
         $(".select2").select2();


        // validate signup form on keyup and submit
      var validator = $("#assign-teacher-form").validate({
                // Specify validation rules
                rules: {
                    batch_id: 'required',
                    teacher_id: 'required',
                    teacher_type: 'required',
                    teacher_status: 'required',
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
