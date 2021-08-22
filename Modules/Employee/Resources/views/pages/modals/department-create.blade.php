<form id="department-create-form" action="{{url('/employee/departments/store')}}" method="POST">
   <div class="modal-header">
      <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
      <h4 class="box-title"><i class="fa fa-plus-square"></i> {{$departmentProfile?'Update':'Add'}} Department</h4>
   </div>
   <input type="hidden" name="_token" value="{{csrf_token()}}">
   <input type="hidden" name="dept_id" value="{{$departmentProfile?$departmentProfile->id:'0'}}">
   <div class="modal-body">

      <div class="row">
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="name">Department</label>
               <input id="name" class="form-control" name="name" maxlength="50" value="{{$departmentProfile?$departmentProfile->name:''}}" type="text">
               <div class="help-block"></div>
            </div>
         </div>

         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="alias">Alias</label>
               <input id="alias" class="form-control" name="alias" value="{{$departmentProfile?$departmentProfile->alias:''}}" type="text">
               <div class="help-block"></div>
            </div>
         </div>
      </div>

      {{--checking dept--}}
      @if($departmentProfile)
         @if($stdDepartment = $departmentProfile->studentDepartment())
            @php
               $studentDepartment = $stdDepartment;
               $academicLevel = $stdDepartment->academicLevel();
               $academicBatchList = $academicLevel->batch();
            @endphp
         @else
            @php $studentDepartment = null; $academicLevel =  null; $academicBatchList = null; @endphp
         @endif
      @else
         @php $studentDepartment = null; $academicLevel =  null; $academicBatchList = null; @endphp
      @endif

      <div class="row">
         <div class="col-sm-12">
            <div class="form-group">
               <label class="checkbox-inline text-center">
                  <input type="hidden" name="dept_type" value="0">
                  <input type="hidden" name="std_dept_id" value="{{$studentDepartment?$studentDepartment->id:'0'}}">
                  <input id="dept_type" type="checkbox" {{$studentDepartment?'checked':''}} name="dept_type" value="1"> Make as Student Department
               </label>
            </div>
         </div>
      </div>

      <div id="std_dept_row" class="row  {{$studentDepartment?'':'hide'}}">
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="academic_level">Academic Level</label>
               <select id="academic_level" class="form-control academicLevel" name="academic_level" required>
                  <option value="" selected disabled>--- Select Level ---</option>
                  @foreach($allAcademicsLevel as $level)
                     <option value="{{$level->id}}" {{$studentDepartment?($level->id==$studentDepartment->academic_level?'selected':''):''}}>{{$level->level_name}}</option>
                  @endforeach
               </select>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-6">
            <div class="form-group">
               <label class="control-label" for="batch">Batch</label>
               <select id="batch" class="form-control academicBatch" name="academic_batch" required>
                  <option value="" selected disabled>--- Select Batch ---</option>
                  @if($academicBatchList)
                     @foreach($academicBatchList as $batch)
                        @php
                           if($division = $batch->division()){
                              $batchName = $batch->batch_name.' - '.$division->name;
                           }else{
                              $batchName = $batch->batch_name;
                           }
                        @endphp
                        <option value="{{$batch->id}}" {{$batch->id==$studentDepartment->academic_batch?'Selected':''}}>{{$batchName}}</option>
                     @endforeach
                  @endif
               </select>
               <div class="help-block"></div>
            </div>
         </div>
      </div>
   </div>
   <!--./modal-body-->
   <div class="modal-footer">
      <button type="submit" class="btn btn-info pull-left"></i> Create</button>
      <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
   </div>
   <!--./modal-footer-->
</form>


<script type="text/javascript">
    $(document).ready(function(){

        $('#dept_type').click(function () {
            // std_dept_row
            var std_dept_row = $('#std_dept_row');
            // make first option value selected
            $('.academicLevel option:first').prop('selected',true);
            $('.academicBatch option:first').prop('selected',true);

            // check box selected checking
            if($(this).is(":checked")){
                std_dept_row.toggleClass('hide');
            }else{
                std_dept_row.toggleClass('hide');
            }
        });

        // request for batch list using level id
        jQuery(document).on('change','.academicLevel',function(){
            // get academic level id
            var level_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/batch') }}",
                type: 'GET',
                cache: false,
                data: {'id': level_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // console.log(level_id);
                },
                success:function(data){
                    op+='<option value="" selected disabled>--- Select Batch---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }
                    // refresh attendance container row
                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);
                },
                error:function(){
                    //
                }
            });
        });


        // validate signup form on keyup and submit
        $("#department-create-form").validate({
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
