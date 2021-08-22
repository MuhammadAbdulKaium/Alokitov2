
         <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button">
            <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">
               <i class="fa fa-plus-square"></i> Edit Record
            </h4>
         </div>
         <form id="stu-master-update" action="/student/performance/assessment/update/{{$personalInfo->student_id}}" method="post">

            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="std_id" value="{{$personalInfo->student_id}}">
            <input type="hidden" name="type" value="{{$personalInfo->type}}">
            <input type="hidden" name="item_id" value="{{$personalInfo->id}}">
            <input type="hidden" name="date" value="{{$personalInfo->date}}">
            <div class="modal-body">
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group">
                        <label class="control-label" for="type">Remarks</label>
                        <input type="text" name="remarks" class="form-control" value="{{$personalInfo->remarks}}">
                        <div class="help-block"></div>
                     </div>
                  </div>
               </div>
            </div>
            <div class="modal-footer">
               <button type="submit" class="btn btn-success pull-left">Update</button> <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
            </div>
         </form>

         <script type="text/javascript">

         </script>
