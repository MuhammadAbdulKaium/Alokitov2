<link href="{{ asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">

<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> {{$leaveStructureProfile?"Update":"Create"}} Leave Structure
    </h4>
</div>

@if($leaveStructureProfile)
    @php $url = url('/employee/manage/leave/structure/update', [$leaveStructureProfile->id]); @endphp
@else
    @php $url = url('/employee/manage/leave/structure/store'); @endphp
@endif

<form id="leave-structure-form" action="{{$url}}" method="post">
    <input name="_token" value="{{csrf_token()}}" type="hidden">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="name">Leave Structure</label>
                    <input id="name" class="form-control" name="name" value="@if($leaveStructureProfile){{$leaveStructureProfile->name}}@endif" maxlength="45" aria-required="true" type="text" placeholder="Leave Structure name" required>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="start_date">Start Date</label>
                    <input readonly class="form-control" name="start_date" id="start_date" value="@if($leaveStructureProfile){{date('m/d/Y', strtotime($leaveStructureProfile->start_date))}}@endif" type="text" placeholder="Select Start Date" required="required">
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="end_date">End Date</label>
                    <input readonly class="form-control" name="end_date" id="end_date" value="@if($leaveStructureProfile){{date('m/d/Y', strtotime($leaveStructureProfile->end_date))}}@endif" type="text" placeholder="Select End Date" required="required">
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
<script src="{{ URL::asset('js/bootstrap-datepicker.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function(){

        //Date picker
        $('#start_date').datepicker({
            autoclose: true,
        });

        //Date picker
        $('#end_date').datepicker({
            autoclose: true,
        });

        // validate signup form on keyup and submit
        $("#leave-type-form").validate({
            // Specify validation rules
            rules: {
                name: {
                    required: true,
                    minlength: 1,
                    maxlength: 35,
                },
                start_date: {
                    required: true,
                },
                end_date: {
                    required: true,
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
