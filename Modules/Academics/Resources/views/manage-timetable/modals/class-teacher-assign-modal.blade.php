
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">Class Teacher Assign</h3>
            </div>
            <div class="modal-body">

                <form class="form-horizontal"  method="post" action="{{URL::to('/academics/timetable/class-teacher-assign')}}">
                    {{ csrf_field() }}
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="email">Select Teacher:</label>
                        <div class="col-sm-7">
                            <select name="teacher_id" class="form-control" id="sel1">
                                <option value="">Select Teacher</option>
                                @foreach($empList as $emp)
                                <option value="{{$emp->id}}">{{$emp->first_name.' '.$emp->middle_name.' '.$emp->last_name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="pwd">Select Level:</label>
                        <div class="col-sm-7">
                            <select id="academic_level" class="form-control academicLevel" name="academic_level" required>
                                <option value="" selected disabled>--- Select Level ---</option>
                                @if($allAcademicsLevel->count()>0)
                                    @foreach($allAcademicsLevel as $level)
                                        <option value="{{$level->id}}">{{$level->level_name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-sm-3" for="pwd">Select Batch:</label>
                        <div class="col-sm-7">
                            <select id="batch" class="form-control academicBatch" name="batch" required>
                                <option value="" selected disabled>--- Select Batch ---</option>
                            </select>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="control-label col-sm-3" for="pwd">Select Section:</label>
                        <div class="col-sm-7">
                            <select id="section" class="form-control academicSection" name="section" required>
                                <option value="" selected disabled>--- Select Section ---</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-7">
                            <button type="submit" class="btn btn-default">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
 </div>

        <script>


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
                        op+='<option value="" selected disabled>--- Select Batch ---</option>';
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

        </script>

