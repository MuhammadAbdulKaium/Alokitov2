<div class="modal-content" id="modal-content">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-plus-square"></i> {{$holidayProfile?"Update":"Add"}} Holiday
        </h4>
    </div>
    <form id="leave-type-form" action="{{url('/employee/manage/national-holiday/store')}}" method="post">
        <input name="_token" value="{{csrf_token()}}" type="hidden">
        <input name="holiday_id" value="{{$holidayProfile?$holidayProfile->id:0}}" type="hidden">
        <div class="modal-body">
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label" for="name">Holiday Name</label>
                        <input id="name" class="form-control" name="name" maxlength="100" value="{{$holidayProfile?$holidayProfile->name:''}}" type="text" required>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label" for="start_date">Start Date</label>
                        <input id="start_date" class="form-control date_picker" name="start_date" value="{{$holidayProfile?date('m/d/Y', strtotime($holidayProfile->start_date)):''}}" readonly type="text" required>
                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label" for="end_date">End Date</label>
                        <input id="end_date" class="form-control date_picker" name="end_date" value="{{$holidayProfile?date('m/d/Y', strtotime($holidayProfile->end_date)):''}}" readonly type="text" required>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label class="control-label" for="remarks">Remarks</label>
                        <textarea id="remarks" class="form-control" name="remarks" maxlength="250" cols="20" rows="3" type="text" required>{{$holidayProfile?$holidayProfile->remarks:''}}</textarea>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-success">Submit</button>
            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
        </div>
    </form>
    <script type="text/javascript">
        $(document).ready(function(){

            //Date picker
            $('.date_picker').datepicker({autoclose:true});

            // validate sign-up form on key-up and submit
            $("#leave-type-form").validate({
                // Specify validation rules
                rules: {
                    name: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    end_date: {
                        required: true
                    },
                    remarks: {
                        required: true
                    }
                },

                // Specify validation error messages
                messages: {},

                highlight: function(element) {
                    $(element).closest('.form-group').addClass('has-error');
                },

                unhighlight: function(element) {
                    $(element).closest('.form-group').removeClass('has-error');
                    $(element).closest('.form-group').addClass('has-success');
                },

                debug: true,
                success: "valid",
                errorElement: 'span',
                errorClass: 'help-block',

                errorPlacement: function(error, element) {
                    if (element.parent('.input-group').length) {
                        error.insertAfter(element.parent());
                    } else {
                        error.insertAfter(element);
                    }
                },

                submitHandler: function(form) {
                    form.submit();
                }
            });
        });
    </script>
</div>