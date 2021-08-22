        <link href="{{ asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
        <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button">
            <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">
               <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Class-Section Average Attendance Report<br>
            </h4>
         </div>
         <form id="#" action="{{url('academics/report/attendance/summary/class/section/')}}" method="post" target="_blank">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="modal-body">
                     <div class="row">
                        <div class="col-sm-4">
                           <div class="form-group">
                              <label class="control-label" for="academic_level">Academic Level</label>
                              <select id="academic_level" class="form-control academicLevel" name="academic_level">
                                <option value="" selected disabled>--- Select Level ---</option>
                                @foreach($allAcademicsLevel as $level)
                                  <option value="{{$level->id}}">{{$level->level_name}}</option>
                                @endforeach
                              </select>
                              <div class="help-block"></div>
                           </div>
                        </div>
                        <div class="col-sm-4">
                           <div class="form-group">
                              <label class="control-label" for="batch">Batch</label>
                              <select id="batch" class="form-control academicBatch" name="batch" onchange="">
                                <option value="" selected disabled>--- Select Batch ---</option>
                              </select>
                              <div class="help-block"></div>
                               {{--batch name--}}
                               <input id="batch_name" type="hidden" name="batch_name">
                           </div>
                        </div>
                        <div class="col-sm-4">
                           <div class="form-group">
                              <label class="control-label" for="section">Section</label>
                              <select id="section" class="form-control academicSection" name="section">
                                <option value="" selected disabled>--- Select Section ---</option>
                              </select>
                              <div class="help-block"></div>
                               {{--section name--}}
                               <input id="section_name" type="hidden" name="section_name">
                           </div>
                        </div>
                     </div>
               <div class="row">
                  <div class="col-sm-4">
                    <div class="form-group">
                       <label class="control-label" for="fromdatepicker">From Date</label>
                       <input readonly class="form-control" name="from_date" id="fromdatepicker" type="text">
                       <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                       <label class="control-label" for="todatepicker">To Date</label>
                       <input readonly class="form-control" name="to_date" id="todatepicker" type="text">
                       <div class="help-block"></div>
                    </div>
                  </div>
                  <div class="col-sm-4">
                    <div class="form-group">
                       <label class="control-label" for="doc_type">Type</label>
                       <select id="doc_type" class="form-control" name="doc_type" required>
                          <option value="">--- Select Type ---</option>
                          <option value="pdf">PDF</option>
                          <option value="xlsx">Excel</option>
                       </select>
                       <div class="help-block"></div>
                    </div>
                  </div>
               </div>
            </div>
            <!--./body-->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info">Submit</button>     <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            </div>
         </form>

<script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>

<script type="text/javascript">
  $(function() { // document ready

    //Date picker
    $('#fromdatepicker').datepicker({
      autoclose: true,
    });
    //Date picker
    $('#todatepicker').datepicker({
      autoclose: true,
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
                    op+='<option value="" selected disabled>--- Select Batch ---</option>';
                    for(var i=0;i<data.length;i++){
                      op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }
                   // set value to the academic batch
                   $('.academicBatch').html("");
                   $('.academicBatch').append(op);
                   // set value to the academic secton
                   $('.academicSection').html("");
                   $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');
                },
                error:function(){
                  //
                }
            });
          });


          // request for section list using batch id
          jQuery(document).on('change','.academicBatch',function(){
              // get academic level id
              var batch_id = $(this).val();
              var batch_name = $(this).find('option:selected').text();
              var div = $(this).parent();
              var op="";
              // set batch name
              $('#batch_name').val(batch_name);

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
                      // set value to the academic batch
                      $('.academicSection').html("");
                      $('.academicSection').append(op);
                  },
                  error:function(){
                    //
                  },
              });
          });

      // request for section list using batch id
      jQuery(document).on('change','.academicSection',function(){
          // get academic section name
          var section_name = $(this).find('option:selected').text();
          $('#section_name').val(section_name);

      });

  });
</script>
