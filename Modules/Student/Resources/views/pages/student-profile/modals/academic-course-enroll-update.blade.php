<div class="modal-header">
   <button aria-label="Close" data-dismiss="modal" class="close" type="button">
      <span aria-hidden="true">×</span>
   </button>
   <h4 class="modal-title">
      @php $stdProfile = $enrollProfile->student(); @endphp

      <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update Enrol Detail | <small>{{$stdProfile->first_name.' '.$stdProfile->middle_name.' '.$stdProfile->last_name}}</small>        <br>
   </h4>
</div>
<form id="academic-batch-update-form" action="#" method="post">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
   <input type="hidden" name="std_id" value="{{$stdProfile->id}}">
   <input type="hidden" name="enroll_id" value="{{$enrollProfile->id}}">
   <input type="hidden" name="academic_year" value="{{$enrollProfile->academic_year}}">
   <div class="modal-body">
      <table class="table table-bordered table-responsive text-center">
         <thead>
         <tr>
            <th>Roll</th>
            <th>Section</th>
            <th>Class</th>
            <th>Level</th>
            <th>Year</th>
         </tr>
         </thead>
         <tbody>
         <tr>
            <td>{{$enrollProfile->gr_no}}</td>
            <td>{{$enrollProfile->section()->section_name}}</td>
            <td>{{$enrollProfile->batch()->batch_name}}</td>
            <td>{{$enrollProfile->level()->level_name}}</td>
            <td>{{$enrollProfile->academicsYear()->year_name}}</td>
         </tr>
         </tbody>
      </table>
      <div class="row">
         <div class="col-md-6">
            <div class="form-group">
               <label class="control-label" for="academic_level">Academic Level</label>
               <select id="academic_level" class="form-control academicLevel" name="academic_level" required>
                  <option value="" selected disabled>--- Select Level ---</option>
                  @if($allAcademicsLevel->count()>0)
                     @foreach($allAcademicsLevel as $level)
                        <option value="{{$level->id}}" {{$level->id==$enrollProfile->academic_level?'selected':''}}>
                           {{$level->level_name}}
                        </option>
                     @endforeach
                  @endif
               </select>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group">
               <label class="control-label" for="batch">Class</label>
               <select id="batch" class="form-control academicBatch" name="batch" required>
                  <option value="" selected disabled>--- Select Class ---</option>
                  {{--checking level list--}}
                  @if($allAcademicsLevel->count()>0)
                     {{--level looping--}}
                     @foreach($allAcademicsLevel as $level)
                        @if($level->id!=$enrollProfile->academic_level) @continue @endif
                        {{--find batch list with the level id--}}
                        @php $batchList = $level->batch(); @endphp
                        {{--checking batch list--}}
                        @if($batchList->count()>0)
                           {{--batch looping--}}
                           @foreach($batchList as $batch)
                              {{--batch name--}}
                              @php
                                 if ($batch->get_division()) {
									 $batchName = $batch->batch_name . " - " . $batch->get_division()->name;
								 } else {
									 $batchName = $batch->batch_name;
								 }
                              @endphp

                              <option value="{{$batch->id}}" {{$batch->id==$enrollProfile->batch?'selected':''}}>
                                 {{$batchName}}
                              </option>
                           @endforeach
                        @endif
                     @endforeach
                  @endif
               </select>
            </div>
         </div>
      </div>
      <div class="row">
         <div class="col-md-6">
            <div class="form-group">
               <label class="control-label" for="section">Section</label>
               <select id="section" class="form-control academicSection" name="section" required>
                  <option value="" selected disabled>--- Select Section ---</option>
                  {{--checking level list--}}
                  @if($allAcademicsLevel->count()>0)
                     {{--level looping--}}
                     @foreach($allAcademicsLevel as $level)
                        {{--find batch list with the level id--}}
                        @php $batchList = $level->batch(); @endphp
                        {{--checking batch list--}}
                        @if($batchList->count()>0)
                           {{--batch looping--}}
                           @foreach($batchList as $batch)
                              @if($batch->id!=$enrollProfile->batch) @continue @endif
                              {{--find section list with the batch id--}}
                              @php $sectionList = $batch->section(); @endphp
                              {{--checking batch list--}}
                              @if($sectionList->count()>0)
                                 {{--batch looping--}}
                                 @foreach($sectionList as $section)
                                    <option value="{{$section->id}}" {{$section->id==$enrollProfile->section?'selected':''}}>
                                       {{$section->section_name}}
                                    </option>
                                 @endforeach
                              @endif
                           @endforeach
                        @endif
                     @endforeach
                  @endif
               </select>
            </div>
         </div>
         <div class="col-md-6">
            <div class="form-group required">
               <label class="control-label" for="gr_no">Roll</label>
               <input type="text" id="gr_no" name="gr_no" class="form-control" value="{{$enrollProfile?$enrollProfile->gr_no:''}}">
            </div>
         </div>
      </div>
   </div>
   <!--./body-->
   <div class="modal-footer">
      <button type="submit" class="btn btn-info pull-left">Update</button>
      <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
   </div>
</form>

<script type ="text/javascript">
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
                op+='<option value="" selected disabled>--- Select Class ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                }
                // refresh attendance container row
                $('#attendanceContainer').html('');
                // set value to the academic batch
                $('.academicBatch').html("");
                $('.academicBatch').append(op);
                // set value to the academic secton
                $('.academicSection').html("");
                $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');
                // set value to the academic secton
                $('.academicSubject').html("");
                $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');
                // set value to the academic secton
                $('.academicSession option:first').prop('selected',true);
            },
            error:function(){
                // sweet alert
                swal("Error", 'Unable to load data form server', "error");
            }
        });
    });


    // request for section list using batch id
    jQuery(document).on('change','.academicBatch',function(){
        // get academic level id
        var batch_id = $(this).val();
        var div = $(this).parent();
        var op="";

        $.ajax({
            url: "{{ url('/academics/find/section') }}",
            type: 'GET',
            cache: false,
            data: {'id': batch_id }, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                //
            },

            success:function(data){
                op+='<option value="" selected disabled>--- Select Section ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                }
                // refresh attendance container row
                $('#attendanceContainer').html('');
                // set value to the academic batch
                $('.academicSection').html("");
                $('.academicSection').append(op);

                // set value to the academic secton
                $('.academicSubject').html("");
                $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');
                // set value to the academic secton
                $('.academicSession option:first').prop('selected',true);
            },
            error:function(){
                // sweet alert
                swal("Error", 'Unable to load data form server', "error");
            }
        });
    });


    // request for section list using batch and section id
    jQuery(document).on('change','.academicSection',function(){
        // get academic level id
        var batch_id = $("#batch").val();
        var section_id = $(this).val();
        var op="";

        $.ajax({
            url: "{{ url('/academics/find/subjcet') }}",
            type: 'GET',
            cache: false,
            data: {'class_id': batch_id, 'section_id':section_id}, //see the $_token
            datatype: 'application/json',

            beforeSend: function() {
                // console.log(batch_id+" "+section_id);
            },

            success:function(data){

                op+='<option value="" selected disabled>--- Select Subject ---</option>';
                for(var i=0;i<data.length;i++){
                    op+='<option value="'+data[i].id+'">'+data[i].sub_name+'</option>';
                }
                // refresh attendance container row
                $('#attendanceContainer').html('');
                // set value to the academic batch
                $('.academicSubject').html("");
                $('.academicSubject').append(op);
                // set value to the academic secton
                $('.academicSession option:first').prop('selected',true);
                //console.log(op);
            },
            error:function(){
                // sweet alert
                swal("Error", 'Unable to load data form server', "error");
            }
        });
    });

    // academic academic-batch-update-form submit action
    // request for section list using batch and section id
    $('form#academic-batch-update-form').on('submit', function (e) {
        e.preventDefault();
        // class section details
        var class_id = $("#batch").val();
        var section_id = $("#section").val();
        var gr_no = $("#gr_no").val();
        // checking
        if(class_id && section_id && gr_no){
            // ajax request
            $.ajax({
                url: '/student/course-update',
                type: 'POST',
                cache: false,
                data: $('form#academic-batch-update-form').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success:function(data){
                    // show waiting dialog
                    waitingDialog.hide();
                    // checking status
                    if(data.status){
                        // sweet alert success
                        swal({
                                title: "Good Job",
                                text: data.msg,
                                type: "success",
                                showCancelButton: false,
                                confirmButtonClass: "btn-danger",
                                confirmButtonText: "Yes",
                                cancelButtonText: "No",
                                closeOnConfirm: false,
                                closeOnCancel: false
                            },
                            function(isConfirm) {
                                if (isConfirm) {
                                    // reload the page
                                    window.location.reload(true);
                                }else{

                                }
                            });
                    }else{
                        // sweet alert success
                        swal("Warning", data.msg, "warning");
                    }
                },

                error:function(){
                    // show waiting dialog
                    waitingDialog.hide();
                    // sweet alert
                    swal("Error", 'Unable to load data form server', "error");
                }
            });
        }else{
            // sweet alert
            swal("Warning", 'Please double check all inputs are selected.', "warning");
        }
    });


</script>
