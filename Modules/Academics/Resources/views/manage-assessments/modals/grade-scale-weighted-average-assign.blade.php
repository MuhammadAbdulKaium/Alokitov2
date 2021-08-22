
<form>
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title text-bold">
            <i class="fa fa-info-circle"></i> Academic Grade Scale W/A Assignment
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="academic_level">Academic Level</label>
                    <select id="academic_level" class="form-control academicLevel" name="academic_level">
                        <option value="" selected disabled>--- Select Level ---</option>
                        @foreach($allAcademicsLevel as $level)
                            <option value="{{$level->id}}">{{$level->level_name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="batch">Batch</label>
                    <select id="batch" class="form-control academicBatch" name="batch" onchange="">
                        <option value="" selected disabled>--- Select Batch ---</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        {{--<div class="row">--}}
            {{--<div class="col-sm-6">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="control-label" for="section">Section</label>--}}
                    {{--<select id="section" class="form-control academicSection" name="section">--}}
                        {{--<option value="" selected disabled>--- Select Section ---</option>--}}
                    {{--</select>--}}
                    {{--<div class="help-block"></div>--}}
                {{--</div>--}}
            {{--</div>--}}
            {{--<div class="col-sm-6">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="control-label" for="shift">Shift</label>--}}
                    {{--<select id="shift" class="form-control academicShift" name="shift">--}}
                        {{--<option value="" selected disabled>--- Select Shift ---</option>--}}
                        {{--<option value="0">Day</option>--}}
                        {{--<option value="1">Morning</option>--}}
                    {{--</select>--}}
                    {{--<div class="help-block"></div>--}}
                {{--</div>--}}
            {{--</div>--}}
        {{--</div>--}}


        <div id="wa-list-container">
            {{--semester list will be displayed here--}}
        </div>

    </div>
    <div class="modal-footer">
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
    </div>
</form>

<script>
    $(document).ready(function () {

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
                    // statements
                },

                success:function(data){
                    op+='<option value="" selected disabled>--- Select Batch ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);

                    // set value to the academic section
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');

                    // set value to the shift
                    $('.academicShift option:first').prop('selected',true);

                    //  refresh timetable row
                    $('#wa-list-container').html('');
                },

                error:function(){
                    // statements
                }
            });
        });

        // request for section list using batch and shift id
        jQuery(document).on('change','.academicBatch',function(){
            // get academic level id
            var level_id = $('#academic_level').val();
            var batch_id = $("#batch").val();
            // checking
            if(batch_id){
                // ajax request
                $.ajax({
                    url: '/academics/manage/assessments/grade/weight_average/assign',
                    type: 'POST',
                    cache: false,
                    data: {
                        '_token': '{{csrf_token()}}',
                        'level_id': level_id,
                        'batch_id': batch_id,
                        'request_type':'LIST'
                    },
                    datatype: 'html',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        //  refresh timetable row
                        $('#wa-list-container').html('');
                        $('#wa-list-container').html(data);
                    },
                    error:function(){
                        // statements
                    }
                });
            }else {
                alert('Please Double Check all Inputs are selected');
            }
        });


        {{--// request for section list using batch id--}}
        {{--jQuery(document).on('change','.academicBatch',function(){--}}
            {{--// get academic level id--}}
            {{--var batch_id = $(this).val();--}}
            {{--var div = $(this).parent();--}}
            {{--var op="";--}}

            {{--$.ajax({--}}
                {{--url: "{{ url('/academics/find/section') }}",--}}
                {{--type: 'GET',--}}
                {{--cache: false,--}}
                {{--data: {'id': batch_id }, //see the $_token--}}
                {{--datatype: 'application/json',--}}

                {{--beforeSend: function() {--}}
                    {{--// statements--}}
                {{--},--}}

                {{--success:function(data){--}}
                    {{--op+='<option value="" selected disabled>--- Select Section ---</option>';--}}
                    {{--for(var i=0;i<data.length;i++){--}}
                        {{--op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';--}}
                    {{--}--}}

                    {{--// set value to the academic batch--}}
                    {{--$('.academicSection').html('');--}}
                    {{--$('.academicSection').append(op);--}}

                    {{--// set value to the shift--}}
                    {{--$('.academicShift option:first').prop('selected',true);--}}

                    {{--//  refresh timetable row--}}
                    {{--$('#semester-list-container').html('');--}}

                {{--},--}}
                {{--error:function(){--}}
                    {{--// statements--}}
                {{--}--}}
            {{--});--}}
        {{--});--}}


        {{--// request for section list using batch and section id--}}
        {{--jQuery(document).on('change','.academicSection',function(){--}}
            {{--// set value to the shift--}}
            {{--$('.academicShift option:first').prop('selected',true);--}}

            {{--//  refresh timetable row--}}
            {{--$('#semester-list-container').html('');--}}
        {{--});--}}

        {{--// request for section list using batch and shift id--}}
        {{--jQuery(document).on('change','.academicShift',function(){--}}
            {{--// get academic level id--}}
            {{--var level_id = $('#academic_level').val();--}}
            {{--var batch_id = $("#batch").val();--}}
            {{--var section_id = $('#section').val();--}}
            {{--var shift_id = $(this).val();--}}
            {{--// checking--}}
            {{--if(section_id){--}}
                {{--// ajax request--}}
                {{--$.ajax({--}}
                    {{--url: '/academics/manage/assessments/grade/weight_average/assign',--}}
                    {{--type: 'POST',--}}
                    {{--cache: false,--}}
                    {{--data: {--}}
                        {{--'_token': '{{csrf_token()}}',--}}
                        {{--'level_id': level_id,--}}
                        {{--'batch_id': batch_id,--}}
                        {{--'section_id': section_id,--}}
                        {{--'shift_id': shift_id,--}}
                        {{--'request_type':'LIST'--}}
                    {{--},--}}
                    {{--datatype: 'html',--}}

                    {{--beforeSend: function() {--}}
                        {{--// statements--}}
                    {{--},--}}

                    {{--success:function(data){--}}
                        {{--//  refresh timetable row--}}
                        {{--$('#semester-list-container').html('');--}}
                        {{--$('#semester-list-container').html(data);--}}
                    {{--},--}}
                    {{--error:function(){--}}
                        {{--// statements--}}
                    {{--}--}}
                {{--});--}}
            {{--}else {--}}
                {{--alert('Please Double Check all Inputs are selected');--}}
            {{--}--}}
        {{--});--}}


    });
</script>