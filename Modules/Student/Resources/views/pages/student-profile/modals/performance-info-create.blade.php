
<div class="modal-header">
   <button aria-label="Close" data-dismiss="modal" class="close" type="button">
   <span aria-hidden="true">×</span>
   </button>
   <h4 class="modal-title">
      <i class="fa fa-plus-square"></i> Add Record
   </h4>
</div>
<form id="stu-master-update" action="/student/performance/assessment" method="post">
   <input type="hidden" name="_token" value="{{csrf_token()}}">
   <input type="hidden" name="std_id" value="{{$std_id}}">
   <input type="hidden" name="type" value="{{$category->category_type_id}}">
   <input type="hidden" name="cadet_performance_category_id" value="{{$category->id}}">
   <input type="hidden" name="performance_category_id" value="{{$category->id}}">
   <div class="modal-body">
      <div class="row">
         <div class="col-sm-3">
            <div class="form-group">
               <label class="control-label" for="title">Date</label>
               <input type="date" class="form-control" name="date">
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-2">
            <div class="form-group">
               <label class="control-label" for="type">Activity</label>
               <select id="activityId" class="form-control" name="cadet_performance_activity_id">
                  <option value="" selected>Select Activity</option>
                  @foreach($activity as $var)    
                     <option value="{{$var->id}}">{{$var->activity_name}}</option>
                  @endforeach
               </select>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-2">
            <div class="form-group">
               <label class="control-label" for="type">Performance</label>
               <select id="activity_point" class="form-control" name="cadet_performance_activity_point_id">
                  <option value="" selected disabled>Select Value</option>
               </select>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-1">
            <div class="form-group">
               <label class="control-label" for="last_name">Point</label>
               <input id="point" class="form-control" name="last_name" maxlength="65" type="text" disabled>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-4">
            <div class="form-group">
               <label class="control-label" for="first_name">Remarks</label>
               <input id="bn_fullname" class="form-control" name="remarks" type="text">
               <div class="help-block"></div>
            </div>
         </div>                  
      </div>
   </div>
   <div class="modal-footer">
      <button type="submit" class="btn btn-success pull-left">Create</button> <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
   </div>
</form>

<script type="text/javascript">
   var host = window.location.origin;
   $(document).ready(function () {
      $("#activityId").change(function(){
         $.ajax({
               type: "get",
               url: '/student/profile/activity/'+ $(this).val(),
               data: "",
               dataType: "json",
               contentType: "application/json; charset=utf-8",
               success: function (response) {
                  $("#activity_point").html(response);
               },
               error: function (XMLHttpRequest, textStatus, errorThrown) {
                  alert(errorThrown);
                  console.log(errorThrown);
               }
         });
      });
   });



$("#activity_point").change(function(){
   var point = $(this).find(':selected').data('point');
   $("#point").val(point);
});
   
   
</script>
