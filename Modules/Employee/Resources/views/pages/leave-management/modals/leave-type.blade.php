<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> {{$leaveTypeProfile?'Update':'Create'}} Leave Type
    </h4>
</div>

@if($leaveTypeProfile)
    @php $url = url('/employee/manage/leave/type/update', [$leaveTypeProfile->id]) @endphp
@else
    @php $url = url('/employee/manage/leave/type/store') @endphp
@endif

<form id="leave-type-form" action="/employee/manage/leave/type/store" method="post">
    <input name="_token" value="{{csrf_token()}}" type="hidden">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="name">Leave Type</label>
                    <input id="name" class="form-control" name="name" aria-required="true" type="text" value="@if($leaveTypeProfile){{$leaveTypeProfile->name}}@endif">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="details">Details</label>
                    <textarea id="details" class="form-control" name="details" maxlength="255">@if($leaveTypeProfile){{$leaveTypeProfile->details}}@endif</textarea>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
            <b>Leave Closing Cycle</b>
                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="closing_cycle" id="flexRadioDefault1" value="1">
                        <label class="form-check-label" for="flexRadioDefault1">
                            Cycle Closed Month
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="closing_cycle" id="flexRadioDefault2" value="2">
                        <label class="form-check-label" for="flexRadioDefault2">
                            DOJ Effect
                        </label>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <label><input id="carray_forward" name="carray_forward" type="checkbox" class="form-check-input"> Carray Forward</label>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4" id="cf-amount" style="display: none">
                <div class="form-group">
                    <label class="control-label" for="max_cf_amount">Maximum CF Amount</label>
                    <input id="max_cf_amount" class="form-control" name="max_cf_amount" type="number">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="form-group">
                    <label><input id="encash_forward" name="leave_encash" type="checkbox" class="form-check-input"> Leave Encash</label>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-4 " id="encash-box" style="display: none">
                <div class="form-group">
                    <select name="salary_type" id="" class="form-control">
                        <option value="0">--Select Pay Head---</option>
                        <option value="1">Gross</option>
                        <option value="2">Basic</option>
                    </select>
                    <div class="help-block"></div>
                </div>
                <div class="form-group">
                    <input type="number" class="form-control" placeholder="Enter Percentage % " name="percentage">
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-success">Submit</button>    <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
</form>

<script type="text/javascript">
    $(document).ready(function(){
        $('#encash_forward').val(this.checked);
        $('#encash_forward').change(function() {

            if(this.checked) {
                // var returnVal = confirm("Are you sure?");
                // $(this).prop("checked", returnVal);
                $("#encash-box").show();

            }
            else {
                $("#encash-box").hide();
            }
            $('#encash_forward').val(this.checked);
        });

        // CF
        $('#carray_forward').val(this.checked);
        $('#carray_forward').change(function() {

            if(this.checked) {
                $("#cf-amount").show();

            }
            else {
                $("#cf-amount").hide();
            }
            $('#carray_forward').val(this.checked);
        });

        // validate signup form on keyup and submit
        var validator = $("#leave-type-form").validate({

            // Specify validation rules
            rules: {
                name: {
                    required: true,
                    minlength: 1,
                    maxlength: 35,
                },
                details: {
                    required: true,
                    minlength: 1,
                    maxlength: 200,
                },
                closing_cycle: {
                    required: true,
                },
                max_cf_amount: {
                    required: false,
                    number:true,
                },
            },

            // Specify validation error messages
            messages: {
            },

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