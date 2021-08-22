<form>
  <div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i>@if($attendanceTypeProfile) Update @else Add @endif Status
    </h4>
  </div>
   <!--modal-header-->
  <div class="modal-body">
    <div class="row">
      <div class="col-sm-12">
          <div class="form-group">
            <label class="control-label" for="status">Status</label>
            <input name="status" maxlength="35" value="@if($attendanceTypeProfile){{$attendanceTypeProfile->type_name}}@endif" class="form-control" type="text" placeholder="Enter Status Name">
              <div class="help-block"></div>
          </div>
        </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label" for="short_code">Short Code</label>
            <input name="short_code" maxlength="35" value="@if($attendanceTypeProfile){{$attendanceTypeProfile->short_code}}@endif" class="form-control" type="text" placeholder="Short Code">
              <div class="help-block"></div>
          </div>
      </div>
      <div class="col-sm-6">
          <div class="form-group">
            <label class="control-label" for="color">Color</label>
            <input name="color" maxlength="35" value="@if($attendanceTypeProfile){{$attendanceTypeProfile->color}}@endif" class="form-control" type="color">
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
          @if($attendanceTypeProfile)
          var routeLink = '/academics/manage/attendance/settings/status/update/{{$attendanceTypeProfile->id}}';
          @else
          var routeLink = '/academics/manage/attendance/settings/status/store';
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
                $("#settingStatusTypeTableBody").html('');
                // looping
                for(var i=0;i<data.length;i++){

                  // table rows
                   tr +='<tr><td class="text-center">'+(i+1)+'</td><td>'+data[i].type_name+'</td><td>'+data[i].short_code+'</td><td><input name="color" disabled value="'+data[i].color+'" class="form-control" type="color"></td><td class="text-center"><a href="/academics/manage/attendance/settings/status/edit/'+data[i].id+'" title="Edit" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><span class=" fa fa-pencil-square-o"></span> </a>  <a id="'+data[i].id+'" style="cursor: pointer;" onclick="deleteStatusType(this.id)"   title="Delete"><span class="glyphicon glyphicon-trash"></span></a></td></tr>';
                }
                // append tabe data
                $("#settingStatusTypeTableBody").append(tr);
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


