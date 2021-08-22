
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
   <input type="hidden" name="cadet_performance_category_id" value="{{$type}}">
   <input type="hidden" name="performance_category_id" value="{{$type}}">
   <div class="modal-body">
      <div class="row">
         <div class="col-sm-3">
            <div class="form-group">
               <label class="control-label" for="title">Date</label>
               <input type="date" class="form-control" name="date" required>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-9">
            <div class="form-group">
               <label class="control-label" for="title">Remarks</label>
               <input type="text" class="form-control" name="remarks" required>
               <div class="help-block"></div>
            </div>
         </div>
         <div class="col-sm-12">
            <table class="table table-bordered">
               <thead>
               <tr>
                  <th scope="col">বিবৃতিসমূহ</th>
                  <th scope="col"></th>                  
               </tr>
               </thead>
               <tbody>
                  @foreach ($activity as $item)
                  <tr>                  
                     <td>{{$item->activity_name}}</td>
                     @php
                         $data = AppHelper::CadetActivityPoint($item->id);
                     @endphp                     
                     <td>
                        <div class="custom-control custom-radio">
                           <select name="fector_point[{{$item->id}}]" class="class_value" required>
                              <option value="">- Select -</option>
                              @foreach ($data as $val)
                              <option value="{{$val->point}}">{{$val->value}}</option>
                              @endforeach
                            </select>
                        </div>
                     </td>                     
                  </tr>
                  @endforeach
               </tbody>
            </table>
            <div class="row">
               <div class="col-md-9"></div>
               <div class="col-sm-3">
                  <div class="form-group row">
                     <div class="col-sm-2">
                        <label class="control-label" for="title">Total</label>
                     </div>
                     <div class="col-sm-10">
                        <input type="text" id="totalValue" class="form-control" name="total_point" readonly>
                        <div class="help-block"></div>
                     </div>
                  </div>
               </div>
            </div>
         </div>

      </div>
   </div>
   <div class="modal-footer">
      <div class="row">
         <div class="col-md-4 col-md-offset-3">
            <button type="submit" class="btn btn-success">Create</button> 
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
         </div>
     </div>      
   </div>
</form>

<script type="text/javascript">
var host = window.location.origin;
$("#activity").change(function(){
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

$("#activity_point").change(function(){
   var point = $(this).find(':selected').data('point');
   $("#point").val(point);
});

var totalPoint=0;
$(document).ready(function() {
   $(".class_value").change(function ()
   {
      var sum = 0;
      $('select :selected').each(function() {
         sum += Number($(this).val());
      });
      $("#totalValue").val(sum);
   });
});
   
   
</script>
