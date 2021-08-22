<div class="modal-content" id="modal-content">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-plus-square"></i> Update Week Off Day
        </h4>
    </div>
    <form id="leave-type-form" action="{{url('/employee/manage/week-off/update')}}" method="post">
        <input name="_token" value="{{csrf_token()}}" type="hidden">
        <div class="modal-body">
            <div id="departments_row" class="row">
                <div class="col-sm-12">
                    <div class="form-group">
                        <label for="departments">Department(s)</label>
                        <select id="departments" name="departments[]" class="form-control select2 select2-hidden-accessible" multiple="" data-placeholder="Select Department(s)" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                            @foreach($departmentList as $department)
                                <option value="{{$department->id}}">{{$department->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input id="date" name="date" type="text" class="form-control date_picker" readonly required placeholder="Select a date">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="schedule">Schedule to</label>
                        <select id="schedule" name="schedule" class="form-control" required>
                            <option value="" disabled selected>Select a Type</option>
                            <option value="working_day">Working Day</option>
                            <option value="week_off_day">Week Off Day</option>
                            <option value="holiday">Public Holiday</option>
                        </select>
                    </div>
                </div>
            </div>
            <div id="public_holiday_row" class="row hide">
                <div class="col-sm-6">
                    <div class="form-group">
                        <label for="name">Holiday Name</label>
                        <input id="name" name="name" type="text" class="form-control"  placeholder="Enter holiday name">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="form-group">
                        <label class="control-label" for="remarks">Remarks</label>
                        <textarea id="remarks" class="form-control" name="remarks" maxlength="250" cols="20" rows="3" type="text" required></textarea>
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
    <script src="{{ URL::asset('js/select2.full.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            //Initialize Select2 Elements
            $(".select2").select2();
            //Date picker
            $('.date_picker').datepicker({autoclose:true});

            //schedule on change action
            jQuery(document).on('change','#schedule',function(){
                // date schedule details
                var schedule = $(this).val();
                var name = $('#name');
                var remarks = $('#remarks');
                var departments = $('#departments');
                var departments_row = $('#departments_row');
                var public_holiday_row = $('#public_holiday_row');

                // checking
                if(schedule=='holiday'){

                    name.removeAttr("disabled");
                    remarks.removeAttr("disabled");
                    departments.attr("disabled", true);
                    public_holiday_row.removeClass('hide');
                    departments_row.addClass('hide');

                }else{
                    name.attr("disabled", true);
                    remarks.attr("disabled", true);
                    departments.removeAttr("disabled");
                    public_holiday_row.addClass('hide');
                    departments_row.removeClass('hide');

                }
            });
        });
    </script>
</div>