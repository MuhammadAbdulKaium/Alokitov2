<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title"> Academic Report (EMIS Report)</h4>
</div>

<div class="modal-body text-center">
    <form id="academic_report">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        {{--page type--}}
        <input type="hidden" name="page" value="{{$page}}">

        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="academic_year">Academic Year</label>
                    <select id="academic_year" class="form-control academicYear" name="academic_year" required>
                        <option value="" selected disabled>--- Select Year ---</option>
                        @foreach($allAcademicYears as $academicYear)
                            <option value="{{$academicYear->id}}">{{$academicYear->year_name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="academic_level">Academic Level</label>
                    <select id="academic_level" class="form-control academicLevel" name="academic_level" required>
                        <option value="" selected disabled>--- Select Level ---</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <label class="control-label" for="batch">Batch</label>
                    <select id="batch" class="form-control academicBatch" name="batch" onchange="" required>
                        <option value="" selected disabled>--- Select Batch ---</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            {{--<div class="col-sm-2">--}}
                {{--<div class="form-group">--}}
                    {{--<label class="control-label" for="section">Section</label>--}}
                    {{--<select id="section" class="form-control academicSection" name="section" required>--}}
                        {{--<option value="" selected disabled>--- Select Section ---</option>--}}
                    {{--</select>--}}
                    {{--<div class="help-block"></div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="col-sm-3 text-center">
                <label class="control-label" for="submit">Action</label><br/>
                <button id="submit" type="submit" class="btn btn-info">Submit</button>
            </div>
        </div>
    </form>
    <div class="row">
        <div id="academic_report_container" class="col-md-12"></div>
    </div>
</div>

<!--./body-->
<div class="modal-footer">
    <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
</div>


<script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    $(function() { // document ready

        $('.progress-btn').click(function () {

            if($('#section').val() && $('#doc_type').val()){
                var currentTime = $.now();
                $('#downloadToken').val(currentTime);
                //waitingDialog.show("Downloading...");
                var id =  window.setInterval(function() {
                    var cookie_val =  $.cookie("downloadToken");
                    // checking
                    if(cookie_val == currentTime && cookie_val != undefined){
                        $.removeCookie('downloadToken', { path: '/' });
                        // waitingDialog.hide();
                        $('#downloadToken').val('');
                        stop_interval();
                    }
                }, 1000);

                function stop_interval(){
                    clearInterval(id);
                }
            }
        });


        $('form#academic_report').on('submit', function (e) {
            e.preventDefault();

            // ajax request
            $.ajax({
                type: 'POST',
                cache: false,
                url: '/reports/academics/batch-section-repeater-dropout-promotion-and-transfer',
                data: $('form#academic_report').serialize(),
                datatype: 'html',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success: function (data) {
                    // hide waiting dialog
                    waitingDialog.hide();
                    // statements
                    var academic_report_container=  $('#academic_report_container');
                    academic_report_container.html('');
                    academic_report_container.append(data);
                },

                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // alert
                    alert(JSON.stringify(data));
                }
            });
        });



        // request for batch list using level id
        jQuery(document).on('change','.academicYear',function(){
            // get academic year id
            var year_id = $(this).val();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/level') }}",
                type: 'GET',
                cache: false,
                data: {'id': year_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // empty academic_report_container
                    $('#academic_report_container').html('');
                },

                success:function(data){

                    op+='<option value="0" selected>--- Select Level ---</option>';
                    for(var i=0;i<data.length;i++){
                        // console.log(data[i].level_name);
                        op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                    }

                    // set value to the academic section
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="" selected>--- Select Section ---</option>');

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append('<option value="" selected>--- Select Batch ---</option>');

                    // set value to the academic batch
                    $('.academicLevel').html("");
                    $('.academicLevel').append(op);
                },

                error:function(){
                    alert(JSON.stringify(data));
                }
            });
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
                    // empty academic_report_container
                    $('#academic_report_container').html('');
                },
                success:function(data){
                    op+='<option value="" selected disabled>--- Select Batch ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }
                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);
                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');
                },
                error:function(){
                    //
                }
            });
        });


        // request for section list using batch id
        jQuery(document).on('change','.academicBatch',function(){
            // get academic level id
            var batch_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/section') }}",
                type: 'GET',
                cache: false,
                data: {'id': batch_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // empty academic_report_container
                    $('#academic_report_container').html('');
                },

                success:function(data){
                    op+='<option value="" selected disabled>--- Select Section ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }
                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);
                },
                error:function(){
                    //
                },
            });
        });

    });
</script>