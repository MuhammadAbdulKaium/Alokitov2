


                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <p>
                    </p><h4>
                        <i class="fa fa-plus-square"></i> Create Leave Type</h4>
                    <p></p>
                </div>

                <form id="leave-type-form" action="/hr/leave-type/create" method="post">
                    <input name="_csrf" value="LXRkcjJrVExmWVwmC10CJFwyKhdjBCU7XTxXFndaBRtCEggdXlMLIA==" type="hidden">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group field-leavetype-elt_name required">
                                    <label class="control-label" for="leavetype-elt_name">Leave Type</label>
                                    <input id="leavetype-elt_name" class="form-control" name="LeaveType[elt_name]" maxlength="150" aria-required="true" type="text">

                                    <div class="help-block"></div>
                                </div>        </div>
                            <div class="col-sm-6">
                                <div class="form-group field-leavetype-elt_details">
                                    <label class="control-label" for="leavetype-elt_details">Details</label>
                                    <textarea id="leavetype-elt_details" class="form-control" name="LeaveType[elt_details]" maxlength="255"></textarea>

                                    <div class="help-block"></div>
                                </div>        </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Default radio
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        Default checked radio
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group field-leavetype-elt_carray_forward">

                                    <input name="LeaveType[elt_carray_forward]" value="0" type="hidden"><label><input id="leavetype-elt_carray_forward" name="LeaveType[elt_carray_forward]" value="1" type="checkbox"> Carray Forward</label>

                                    <div class="help-block"></div>
                                </div>        </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group field-leavetype-elt_percentage_of_cf">
                                    <label class="control-label" for="leavetype-elt_percentage_of_cf">Percentage of CF</label>
                                    <select id="leavetype-elt_percentage_of_cf" class="form-control" name="LeaveType[elt_percentage_of_cf]">
                                        <option value="">--- Select Percentage ---</option>
                                        <option value="10">10%</option>
                                        <option value="20">20%</option>
                                        <option value="30">30%</option>
                                        <option value="40">40%</option>
                                        <option value="50">50%</option>
                                        <option value="60">60%</option>
                                        <option value="70">70%</option>
                                        <option value="80">80%</option>
                                        <option value="90">90%</option>
                                        <option value="100">100%</option>
                                    </select>

                                    <div class="help-block"></div>
                                </div>        </div>
                            <div class="col-sm-4">
                                <div class="form-group field-leavetype-elt_max_cf_amount">
                                    <label class="control-label" for="leavetype-elt_max_cf_amount">Maximum CF Amount</label>
                                    <input id="leavetype-elt_max_cf_amount" class="form-control" name="LeaveType[elt_max_cf_amount]" type="text">

                                    <div class="help-block"></div>
                                </div>        </div>
                            <div class="col-sm-4">
                                <div class="form-group field-leavetype-elt_cf_availability_period">
                                    <label class="control-label" for="leavetype-elt_cf_availability_period">CF Leave Availability Period</label>
                                    <select id="leavetype-elt_cf_availability_period" class="form-control" name="LeaveType[elt_cf_availability_period]">
                                        <option value="">--- Select Availability Period ---</option>
                                        <option value="1">1 Month</option>
                                        <option value="3">3 Month</option>
                                        <option value="6">6 Month</option>
                                        <option value="12">1 Year</option>
                                    </select>

                                    <div class="help-block"></div>
                                </div>        </div>
                        </div>
                    </div>

                    <div class="box-footer">
                        <button type="submit" class="btn btn-success">Create</button>    <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
                    </div>

                </form>

            <script src="/js/all-dff093c83c582ace1d8c6d13bea54306.js?v=1491379972"></script>
            <script type="text/javascript">jQuery('#leave-type-form').yiiActiveForm([{"id":"leavetype-elt_name","name":"elt_name","container":".field-leavetype-elt_name","input":"#leavetype-elt_name","enableAjaxValidation":true,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.required(value, messages, {"message":"Leave Type cannot be blank."});yii.validation.string(value, messages, {"message":"Leave Type must be a string.","max":150,"tooLong":"Leave Type should contain at most 150 characters.","skipOnEmpty":1});}},{"id":"leavetype-elt_details","name":"elt_details","container":".field-leavetype-elt_details","input":"#leavetype-elt_details","enableAjaxValidation":true,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.string(value, messages, {"message":"Details must be a string.","max":255,"tooLong":"Details should contain at most 255 characters.","skipOnEmpty":1});}},{"id":"leavetype-elt_proportinate_on_joined_date","name":"elt_proportinate_on_joined_date","container":".field-leavetype-elt_proportinate_on_joined_date","input":"#leavetype-elt_proportinate_on_joined_date","enableAjaxValidation":true,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Proportionate leaves on Joined Date must be an integer.","skipOnEmpty":1});}},{"id":"leavetype-elt_carray_forward","name":"elt_carray_forward","container":".field-leavetype-elt_carray_forward","input":"#leavetype-elt_carray_forward","enableAjaxValidation":true,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Carray Forward must be an integer.","skipOnEmpty":1});}},{"id":"leavetype-elt_percentage_of_cf","name":"elt_percentage_of_cf","container":".field-leavetype-elt_percentage_of_cf","input":"#leavetype-elt_percentage_of_cf","enableAjaxValidation":true,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"Percentage of CF must be an integer.","skipOnEmpty":1});}},{"id":"leavetype-elt_max_cf_amount","name":"elt_max_cf_amount","container":".field-leavetype-elt_max_cf_amount","input":"#leavetype-elt_max_cf_amount","enableAjaxValidation":true,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.number(value, messages, {"pattern":/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/,"message":"Maximum CF Amount must be a number.","min":0,"tooSmall":"Maximum CF Amount must be no less than 0.","skipOnEmpty":1});}},{"id":"leavetype-elt_cf_availability_period","name":"elt_cf_availability_period","container":".field-leavetype-elt_cf_availability_period","input":"#leavetype-elt_cf_availability_period","enableAjaxValidation":true,"validate":function (attribute, value, messages, deferred, $form) {yii.validation.number(value, messages, {"pattern":/^\s*[+-]?\d+\s*$/,"message":"CF Leave Availability Period must be an integer.","skipOnEmpty":1});}}], []);</script></div>
