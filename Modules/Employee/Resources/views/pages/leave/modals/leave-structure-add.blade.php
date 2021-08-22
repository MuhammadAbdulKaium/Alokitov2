

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <p>
                    </p><h4>
                        <i class="fa fa-plus-square"></i> Create Leave Structure                    </h4>
                    <p></p>
                </div>

                <form id="leave-structure-form" action="/hr/hr-leave-structure/create" method="post">
                    <input name="_csrf" value="LWFYajZvdVRmTGA.D1kjPFwnFg9nAAQjXSlrDnNeJANCBzQFWlcqOA==" type="hidden">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group field-hrleavestructure-els_name required">
                                    <label class="control-label" for="hrleavestructure-els_name">Leave Structure</label>
                                    <input id="hrleavestructure-els_name" class="form-control" name="HrLeaveStructure[els_name]" maxlength="150" aria-required="true" type="text">

                                    <div class="help-block"></div>
                                </div>        </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group field-hrleavestructure-els_start_date required">
                                    <label class="control-label" for="hrleavestructure-els_start_date">Start Date</label>
                                    <input id="hrleavestructure-els_start_date" class="form-control hasDatepicker" name="HrLeaveStructure[els_start_date]" placeholder="Select Start Date" readonly="" size="10" type="text">


                                    <div class="help-block"></div>
                                </div>        </div>
                            <div class="col-sm-6">
                                <div class="form-group field-hrleavestructure-els_end_date required">
                                    <label class="control-label" for="hrleavestructure-els_end_date">End Date</label>
                                    <input id="hrleavestructure-els_end_date" class="form-control hasDatepicker" name="HrLeaveStructure[els_end_date]" placeholder="Select End Date" readonly="" size="10" type="text">


                                    <div class="help-block"></div>
                                </div>        </div>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Create</button>    <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
                    </div>

                </form>



            <script src="/js/all-dff093c83c582ace1d8c6d13bea54306.js?v=1491379972"></script>
            <script src="/assets/f94f67c/jquery-ui.min.js?v=1490182548"></script>
            <script type="text/javascript">jQuery('#hrleavestructure-els_start_date').datepicker({"changeMonth":true,"yearRange":"2012:2022","changeYear":true,"autoSize":true,"dateFormat":"dd-mm-yy"});
                jQuery('#hrleavestructure-els_end_date').datepicker({"changeMonth":true,"yearRange":"2012:2022","changeYear":true,"autoSize":true,"dateFormat":"dd-mm-yy"});
                jQuery('#leave-structure-form').yiiActiveForm([{"id":"hrleavestructure-els_name","name":"els_name","container":".field-hrleavestructure-els_name","input":"#hrleavestructure-els_name","enableAjaxValidation":true,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Leave Structure cannot be blank."});yii.validation.string(value, messages, {"message":"Leave Structure must be a string.","max":150,"tooLong":"Leave Structure should contain at most 150 characters.","skipOnEmpty":1});}},{"id":"hrleavestructure-els_start_date","name":"els_start_date","container":".field-hrleavestructure-els_start_date","input":"#hrleavestructure-els_start_date","enableAjaxValidation":true,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Start Date cannot be blank."});}},{"id":"hrleavestructure-els_end_date","name":"els_end_date","container":".field-hrleavestructure-els_end_date","input":"#hrleavestructure-els_end_date","enableAjaxValidation":true,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"End Date cannot be blank."});}}], []);</script></div>
