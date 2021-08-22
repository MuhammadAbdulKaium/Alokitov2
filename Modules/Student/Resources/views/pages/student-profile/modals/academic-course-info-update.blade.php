         <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button">
            <span aria-hidden="true">×</span>
            </button>
            <h4 class="modal-title">
               <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Update Course Info | <small>Primary</small>    
            </h4>
         </div>
         <form id="stu-enrol-course" action="#" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">    
            <div class="modal-body">
               <div class="row">
                  <div class="col-sm-6">
                     <div class="form-group field-stuenrolcourse-sec_gr_no">
                        <label class="control-label" for="stuenrolcourse-sec_gr_no">GR No.</label>
                        <input id="stuenrolcourse-sec_gr_no" class="form-control" name="StuEnrolCourse[sec_gr_no]" value="" maxlength="20" type="text">
                        <div class="help-block"></div>
                     </div>
                  </div>
               </div>
            </div>
            <!--./body-->
            <div class="modal-footer">
               <button type="submit" class="btn btn-info">Update</button>       <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            </div>
         </form>
         
         <script type="text/javascript"> 
            jQuery('#stu-enrol-course').yiiActiveForm([{
               "id": "stuenrolcourse-sec_gr_no",
               "name": "sec_gr_no",
               "container": ".field-stuenrolcourse-sec_gr_no",
               "input": "#stuenrolcourse-sec_gr_no",
               "enableAjaxValidation": true,
               "validate": function (attribute, value, messages, deferred, $form) {
                  yii.validation.string(value, messages, {
                     "message": "GR No. must be a string.",
                     "max": 20,
                     "tooLong": "GR No. should contain at most 20 characters.",
                     "skipOnEmpty": 1
                  });
               }
            }], []); 
         </script>