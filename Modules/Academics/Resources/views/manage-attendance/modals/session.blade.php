<form>
  <div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> @if($attendanceSessionProfile) Update @else Add @endif Session
    </h4>
  </div>
   <!--modal-header-->
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-12">
          <div class="form-group">
            <label class="control-label" for="">Session Name</label>
            <input name="institution_id" value="1" type="hidden">
            <input name="campus_id" value="1" type="hidden">
            <input name="session_name" maxlength="35" value="@if($attendanceSessionProfile){{$attendanceSessionProfile->session_name}}@endif" class="form-control" type="text" placeholder="Session Name">
            <div class="help-block"></div>
          </div>
        </div>
    </div>
  </div>

  <div class="modal-footer">
    <button type="submit" class="btn btn-info pull-left">Submit</button>
    <a class="btn btn-default pull-right" href="#" data-dismiss="modal">Cancel</a>
  </div>
</form>


<script type ="text/javascript">
   $(document).ready(function(){
      $('form').on('submit', function (e) {
          e.preventDefault();

          var tr;
          @if($attendanceSessionProfile)
          var routeLink = '/academics/manage/attendance/settings/session/update/{{$attendanceSessionProfile->id}}';
          @else
          var routeLink = '/academics/manage/attendance/settings/session/store';
          @endif

          $.ajax({
            type: 'post',
            url: routeLink,
            data: $('form').serialize(),
            datatype: 'application/json',

            beforeSend: function() {
              // statement
            },

            success: function (data) {
              if(data.length>0){
                $("#settingSessionTableBody").html('');
                // looping
                for(var i=0;i<data.length;i++){

                  // table rows
                   tr +='<tr><td class="text-center">'+(i+1)+'</td><td>'+data[i].session_name+'</td><td class="text-center"><a href="/academics/manage/attendance/settings/session/edit/'+data[i].id+'" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><span class=" fa fa-pencil-square-o"></span></a>  <a id="'+data[i].id+'" style="cursor: pointer;" onclick="deleteSession(this.id)" title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
                }
                // append tabe data
                $("#settingSessionTableBody").append(tr);
                // hide modal
                $('#globalModal').modal('hide');
              }
            },

            error:function(){
              // statements
            }
          });

        });
   });
</script>

  