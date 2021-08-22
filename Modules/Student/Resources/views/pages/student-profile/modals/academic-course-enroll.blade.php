         <link href="{{ URL::asset('css/jquery-ui.min.css') }}" rel="stylesheet" type="text/css"/>

         <div class="modal-header">
            <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title"><i class="fa fa-plus-square-o"></i> Enroll in New Course</h4>
         </div>
         <form id="stu-enrol-course" class="form-horizontal" action="$" method="post" role="form">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <div class="modal-body" style="max-height:605px;overflow:auto">
               <div class="form-group field-stuenrolcourse-sec_gr_no">
                  <label class="control-label col-sm-3" for="stuenrolcourse-sec_gr_no">GR No.</label>
                  <div class="col-sm-7">
                     <input id="stuenrolcourse-sec_gr_no" class="form-control" name="StuEnrolCourse[sec_gr_no]" maxlength="20" type="text">
                     <div class="help-block help-block-error "></div>
                  </div>
               </div>
               <div class="form-group field-stuenrolcourse-sec_admission_year required">
                  <label class="control-label col-sm-3" for="stuenrolcourse-sec_admission_year">Admission Year</label>
                  <div class="col-sm-7">
                     <select id="stuenrolcourse-sec_admission_year" class="form-control" name="StuEnrolCourse[sec_admission_year]" aria-required="true">
                        <option value="">--- Select Academic Year ---</option>
                        <option value="1" selected="">2016-17</option>
                     </select>
                     <div class="help-block help-block-error "></div>
                  </div>
               </div>
               <div class="form-group field-stuecdetail-secd_academic_year required">
                  <label class="control-label col-sm-3" for="stuecdetail-secd_academic_year">Academic Year</label>
                  <div class="col-sm-7">
                     <select id="stuecdetail-secd_academic_year" class="form-control" name="StuEcDetail[secd_academic_year]" onchange="$.get( &quot;/dependent/get-academic-courses&quot;, { yid : $(this).val() })
                        .done(function(data) {
                        $( &quot;#stuenrolcourse-sec_course&quot;).html(data);
                        });" aria-required="true">
                        <option value="">--- Select Academic Year ---</option>
                        <option value="1" selected="">2016-17</option>
                     </select>
                     <div class="help-block help-block-error "></div>
                  </div>
               </div>
               <div class="form-group field-stuenrolcourse-sec_course required">
                  <label class="control-label col-sm-3" for="stuenrolcourse-sec_course">Course</label>
                  <div class="col-sm-7">
                     <select id="stuenrolcourse-sec_course" class="form-control" name="StuEnrolCourse[sec_course]" onchange="$.get( &quot;/dependent/get-academic-section&quot;, { cid : $(this).val(), aid : $(&quot;#stuecdetail-secd_academic_year&quot;).val() })
                        .done(function(data) {
                        $( &quot;#stuecdetail-secd_section&quot;).html(data);
                        });" aria-required="true">
                        <option value="">--- Select Course ---</option>
                        <option value="1">Preschool</option>
                        <option value="2">Primary</option>
                        <option value="3">Secondary</option>
                        <option value="4">Computer Fundamentals</option>
                     </select>
                     <div class="help-block help-block-error "></div>
                  </div>
               </div>
               <div class="form-group field-stuecdetail-secd_section required">
                  <label class="control-label col-sm-3" for="stuecdetail-secd_section">Section</label>
                  <div class="col-sm-7">
                     <select id="stuecdetail-secd_section" class="form-control" name="StuEcDetail[secd_section]" aria-required="true">
                        <option value="">--- Select Section ---</option>
                     </select>
                     <div class="help-block help-block-error "></div>
                  </div>
               </div>
               <div class="form-group field-stuecdetail-secd_date">
                  <label class="control-label col-sm-3" for="stuecdetail-secd_date">Date of Enrol</label>
                  <div class="col-sm-7">
                     <input id="datepicker" class="form-control datepicker hasDatepicker" name="StuEcDetail[secd_date]" size="10" type="text">
                     <div class="help-block help-block-error "></div>
                  </div>
               </div>
            </div>
            <!--./body-->
            <div class="modal-footer">
               <button type="submit" class="btn btn-primary btn-create">Enrol</button>	<button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            </div>
         </form>
               <script src="{{URL::asset('js/all.js')}}" type="text/javascript"></script>
               <script src="{{URL::asset('js/jquery-ui.min.js')}}" type="text/javascript"></script>

         <script type ="text/javascript">
                  jQuery(document).ready(function () {
                     console.log('datepicker');
                    $('#datepicker').datepicker();
                 });

            jQuery('#stuecdetail-secd_date').datepicker({
               "changeMonth": true,
               "yearRange": "1900:2018",
               "changeYear": true,
               "autoSize": true,
               "dateFormat": "dd-mm-yy"
            });

            jQuery('#stu-enrol-course').yiiActiveForm([{
               "id": "stuenrolcourse-sec_gr_no",
               "name": "sec_gr_no",
               "container": ".field-stuenrolcourse-sec_gr_no",
               "input": "#stuenrolcourse-sec_gr_no",
               "error": ".help-block.help-block-error",
               "enableAjaxValidation": true,
               "validate": function (attribute, value, messages, deferred, $form) {
                  yii.validation.string(value, messages, {
                     "message": "GR No. must be a string.",
                     "max": 20,
                     "tooLong": "GR No. should contain at most 20 characters.",
                     "skipOnEmpty": 1
                  });
               }
            }, {
               "id": "stuenrolcourse-sec_admission_year",
               "name": "sec_admission_year",
               "container": ".field-stuenrolcourse-sec_admission_year",
               "input": "#stuenrolcourse-sec_admission_year",
               "error": ".help-block.help-block-error",
               "enableAjaxValidation": true,
               "validate": function (attribute, value, messages, deferred, $form) {
                  yii.validation.required(value, messages, {
                     "message": "Admission Year cannot be blank."
                  });
                  yii.validation.number(value, messages, {
                     "pattern": /^\s*[+-]?\d+\s*$/,
                     "message": "Admission Year must be an integer.",
                     "skipOnEmpty": 1
                  });
               }
            }, {
               "id": "stuecdetail-secd_academic_year",
               "name": "secd_academic_year",
               "container": ".field-stuecdetail-secd_academic_year",
               "input": "#stuecdetail-secd_academic_year",
               "error": ".help-block.help-block-error",
               "enableAjaxValidation": true,
               "validate": function (attribute, value, messages, deferred, $form) {
                  yii.validation.required(value, messages, {
                     "message": "Academic Year cannot be blank."
                  });
                  yii.validation.number(value, messages, {
                     "pattern": /^\s*[+-]?\d+\s*$/,
                     "message": "Academic Year must be an integer.",
                     "skipOnEmpty": 1
                  });
               }
            }, {
               "id": "stuenrolcourse-sec_course",
               "name": "sec_course",
               "container": ".field-stuenrolcourse-sec_course",
               "input": "#stuenrolcourse-sec_course",
               "error": ".help-block.help-block-error",
               "enableAjaxValidation": true,
               "validate": function (attribute, value, messages, deferred, $form) {
                  yii.validation.required(value, messages, {
                     "message": "Course cannot be blank."
                  });
                  yii.validation.number(value, messages, {
                     "pattern": /^\s*[+-]?\d+\s*$/,
                     "message": "Course must be an integer.",
                     "skipOnEmpty": 1
                  });
               }
            }, {
               "id": "stuecdetail-secd_section",
               "name": "secd_section",
               "container": ".field-stuecdetail-secd_section",
               "input": "#stuecdetail-secd_section",
               "error": ".help-block.help-block-error",
               "enableAjaxValidation": true,
               "validate": function (attribute, value, messages, deferred, $form) {
                  yii.validation.required(value, messages, {
                     "message": "Section cannot be blank."
                  });
                  yii.validation.number(value, messages, {
                     "pattern": /^\s*[+-]?\d+\s*$/,
                     "message": "Section must be an integer.",
                     "skipOnEmpty": 1
                  });
               }
            }, {
               "id": "stuecdetail-secd_date",
               "name": "secd_date",
               "container": ".field-stuecdetail-secd_date",
               "input": "#stuecdetail-secd_date",
               "error": ".help-block.help-block-error",
               "enableAjaxValidation": true
            }], []); 


            // jQuery('#stuecdetail-secd_date').datepicker({
            //    "changeMonth":true,
            //    "changeYear":true,
            //    "dateFormat":"dd-mm-yy"
            // });
         </script>

