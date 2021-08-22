<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">
        <i class="fa fa-info-circle"></i> Leave Allocation
    </h4>
</div>
<form id="leave-allocation-form" action="{{url('/employee/manage/leave/entitlement/store')}}" method="post">
    <input name="_token" value="{{csrf_token()}}" type="hidden">
    <input type="hidden" disabled id="row_counter" name="row_counter" value="0">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="name">Category</label>
                    <select id="leave_category_id" class="form-control" name="category" required>
                        <option value="">--- Select category ---</option>
                        <option value="1">General</option>
                        <option value="2">Employee</option>
                        <option value="3">Department</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <!-- employee list -->
            <div id="leave_employee_col_id" class="col-sm-6 hidden">
                <div class="form-group">
                    <label class="control-label" for="employee">Employee</label>
                    <select id="employee" class="form-control select2 select2-hidden-accessible" style="width: 100%; border-radius: 0px" aria-hidden="true"  name="employee">
                        <option value="" selected="selected">--- Select Employee ---</option>
                        @foreach($allEmployee as $employee)
                            <option value="{{$employee->id}}">{{$employee->first_name." ".$employee->middle_name." ".$employee->last_name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <!-- department -->
            <div id="leave_department_col_id" class="col-sm-6 hidden">
                <div class="form-group">
                    <label class="control-label" for="department">Department</label>
                    <select id="department" class="form-control" name="department">
                        <option value="">--- Select Department ---</option>
                        @foreach($allDepartment as $department)
                            <option value="{{$department->id}}">{{$department->name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="structure">Leave Structure</label>
                    <select id="leave_structure_id" class="form-control" name="structure" required>
                        <option value="">--- Select Structure ---</option>
                        @if($allLeaveStructures)
                            @foreach($allLeaveStructures as $structure)
                                <option value="{{$structure->id}}">{{$structure->name}}</option>
                            @endforeach
                        @endif
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div id="structureTypeTableRow" class="row hidden">
            <div class="col-sm-10 col-sm-offset-1">
                <p class="text-center"><strong>Showing All Leave Type of </strong><i>Structure</i></p>
                <input name="custom" value="0" type="hidden"><label>
                    <input id="leave_custom_id" name="custom" value="1" type="checkbox"> Custom Leave days</label>
                <table class="table table-bordered table-inverse text-center">
                    <thead style="background-color: gray">
                    <tr>
                        <th>#</th>
                        <th>Leave Type</th>
                        <th>Leave Days</th>
                    </tr>
                    </thead>
                    <tbody id="structureTypeTableBody"></tbody>
                </table>
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

        $('#leave_custom_id').click(function(){
            if($(this).is(':checked')){
                $('tr.item').each(function() {
                    $this = $(this);
                    var rowId = $this.attr("id");
                    var typeId = rowId.replace("row_","");

                    // type id
                    $("#name_"+typeId).removeAttr('disabled');
                    // row counter
                    $('#row_counter').removeAttr('disabled');
                    // days
                    $("#days_"+typeId).removeAttr('disabled');
                });
            }else{
                $('tr.item').each(function() {
                    $this = $(this);
                    var rowId = $this.attr("id");
                    var typeId = rowId.replace("row_","");
                    // type id
                    $("#name_"+typeId).attr('disabled', '');
                    // row counter
                    $('#row_counter').attr('disabled', '');

                    // days
                    $("#days_"+typeId).attr('disabled', '');
                });
            }
        });


        // structure onChange action
        $(document).on('change', '#leave_structure_id', function() {
            var structureId = $(this).val();
            var myUrl = '/employee/find/leave/structure/type/'+structureId;
            // ajax request
            $.ajax({
                url: myUrl,
                type: 'get',
                cache: false,
                datatype: 'application/json',
                beforeSend: function() {
                    $('#structureTypeTableBody').html('');
                    $('#row_counter').val(0);
                },
                success:function(data){
                    if(data.length>0){
                        var tr = null;
                        // looping
                        for(var i=0; i<data.length;i++){

                            var type_id = data[i].type_id;
                            var type_name = data[i].type_name;
                            var leave_days = data[i].leave_days;
                            var row_count = i+1;

                            tr += '<tr class="item" id="row_'+type_id+'"><td>'+row_count+'</td><td>'+type_name+'<input type="hidden" disabled id="name_'+type_id+'" name="'+row_count+'[type_id]" value="'+type_id+'"></td><td><input class="form-control input-days" id="days_'+type_id+'" disabled type="text" name="'+row_count+'[leave_days]" value="'+leave_days+'"></td></tr>';
                        }

                        $('#row_counter').val(data.length);
                        // append table row to the table body
                        $('#structureTypeTableBody').html('');
                        $('#structureTypeTableBody').append(tr);
                        // visible the table row
                        $('#structureTypeTableRow').removeClass('hidden');
                    }else{
                        // remove structureTypeTableBody all html
                        $('#structureTypeTableBody').html('');
                        // hide the table row
                        if(!$('#structureTypeTableRow').hasClass('hidden')){
                            $('#structureTypeTableRow').addClass('hidden');
                        }
                    }
                },
                //
                error:function(){
                    // statements
                }
            });
        });


        // leave_category_id onChange action
        $(document).on('change', '#leave_category_id', function() {
            var categoryId = $(this).val();
            // checking
            if (categoryId==2) {
                $('#leave_employee_col_id').removeClass('hidden');
                if(!$('#leave_department_col_id').hasClass('hidden')){
                    $('#leave_department_col_id').addClass('hidden');
                }
            } else if (categoryId==3) {
                $('#leave_department_col_id').removeClass('hidden');
                if(!$('#leave_employee_col_id').hasClass('hidden')){
                    $('#leave_employee_col_id').addClass('hidden');
                }
            } else {
                if(!$('#leave_employee_col_id').hasClass('hidden')){
                    $('#leave_employee_col_id').addClass('hidden');
                }
                if(!$('#leave_department_col_id').hasClass('hidden')){
                    $('#leave_department_col_id').addClass('hidden');
                }
            }
        });

    });
</script>
