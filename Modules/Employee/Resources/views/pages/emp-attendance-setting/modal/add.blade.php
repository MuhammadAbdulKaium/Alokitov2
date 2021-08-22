<style type="text/css">
    .ui-autocomplete {z-index:2147483647;}
    .ui-autocomplete span.hl_results {background-color: #ffff66;}
</style>


<form id="department-create-form" action="{{url('/employee/employee-attendance-setting/store')}}" method="POST">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="box-title"><i class="fa fa-plus-square"></i> {{$attendanceSettingProfile?'Update':'Add'}} Attendance Setting</h4>
    </div>
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="emp_attendance_setting_id"  @if(!empty($attendanceSettingProfile->id)) value="{{$attendanceSettingProfile->id}}"  @endif>
    <div class="modal-body">

        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="name">Employee Name</label>

                    @if(!empty($attendanceSettingProfile->emp_id))
                        @php $empNameObj=$attendanceSettingProfile->name() @endphp
                    @endif

                    <input class="form-control" id="emp_name" name="emp_name" required type="text" @if(!empty($attendanceSettingProfile->emp_id)) value="{{$empNameObj->first_name.' '.$empNameObj->middle_name.' '. $empNameObj->last_name}}"  @endif placeholder="Type Employee Name">
                    <input id="emp_id" name="emp_id" @if(!empty($attendanceSettingProfile->emp_id)) value="{{$attendanceSettingProfile->emp_id}}"  @endif type="hidden"/>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class='col-sm-6'>
                <div class="form-group">
                    <label class="control-label" for="name">Start Time</label>
                    <div class='input-group date' id="datetimePickerStart">
                        <input required name="start_time" @if(!empty($attendanceSettingProfile->start_time)) value="{{$attendanceSettingProfile->start_time}}"  @endif type='text' class="form-control" />
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                    </div>
                </div>
            </div>

            <div class='col-sm-6'>
                <div class="form-group">
                    <label class="control-label" for="name">End Time</label>
                    <div class='input-group date' id="datetimePickerEnd">
                        <input required name="end_time" @if(!empty($attendanceSettingProfile->end_time)) value="{{$attendanceSettingProfile->end_time}}"  @endif type='text' class="form-control" />
                        <span class="input-group-addon">
                        <span class="glyphicon glyphicon-time"></span>
                    </span>
                    </div>
                </div>
            </div>


        </div>
    </div>

    </div>
    <!--./modal-body-->
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-left"></i>  {{$attendanceSettingProfile?'Update':'Create'}}</button>
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
    <!--./modal-footer-->
</form>


<script type="text/javascript">
    $(document).ready(function(){


        $(document).ready(function(){
            $('#datetimePickerStart').datetimepicker({
                format: 'LT'
            });

            $('#datetimePickerEnd').datetimepicker({
                format: 'LT'
            });
        });



        // get employeee name and select auto complete

        $('#emp_name').keypress(function() {
            $(this).autocomplete({
                source: loadFromAjax,
                minLength: 1,

                select: function(event, ui) {
                    // Prevent value from being put in the input:
                    this.value = ui.item.label;
                    // Set the next input's value to the "value" of the item.
                    $(this).next("input").val(ui.item.id);
                    event.preventDefault();
                }
            });

            /// load student name form
            function loadFromAjax(request, response) {
                var term = $("#emp_name").val();
                $.ajax({
                    url: '/employee/find/employee',
                    dataType: 'json',
                    data: {
                        'term': term
                    },
                    success: function(data) {
                        // you can format data here if necessary
                        response($.map(data, function(el) {
                            return {
                                label: el.name,
                                value: el.name,
                                id: el.id
                            };
                        }));
                    }
                });
            }
        });





        $('#dept_type').click(function () {
            // std_dept_row
            var std_dept_row = $('#std_dept_row');
            // make first option value selected
            $('.academicLevel option:first').prop('selected',true);
            $('.academicBatch option:first').prop('selected',true);

            // check box selected checking
            if($(this).is(":checked")){
                std_dept_row.toggleClass('hide');
            }else{
                std_dept_row.toggleClass('hide');
            }
        });

        // request for batch list using level id
        jQuery(document).on('change','.academicLevel',function(){
            // get academic level id
            var level_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/batch') }}",
                type: 'GET',
                cache: false,
                data: {'id': level_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // console.log(level_id);
                },
                success:function(data){
                    op+='<option value="" selected disabled>--- Select Batch---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }
                    // refresh attendance container row
                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);
                },
                error:function(){
                    //
                }
            });
        });


        // validate signup form on keyup and submit
        $("#department-create-form").validate({
            // Specify validation rules
            rules: {
                name: {
                    required: true,
                    minlength: 1,
                    maxlength: 35,
                },
                alias: {
                    required: true,
                    minlength: 1,
                    maxlength: 35,
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
