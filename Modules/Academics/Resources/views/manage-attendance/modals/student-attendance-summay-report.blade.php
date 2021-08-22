<link href="{{ asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
<style type="text/css">
    .ui-autocomplete {
        z-index:2147483647;
    }

    .ui-autocomplete span.hl_results {
        background-color: #ffff66;
    }
</style>
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-pencil-square-o" aria-hidden="true"></i> Student Attendance Report<br>
    </h4>
</div>
<form id="#" action="{{url('/student/report/attendance/')}}" method="post">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="academic_level">Student Name</label>
                    <input class="form-control" id="std_name" type="text" placeholder="Type Student Name" required>
                    <input id="std_id" name="std_id" type="hidden" value="" />
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="academic_semester">Academic Semester</label>
                    <select id="academic_semester" class="form-control" required>
                        <option value="" disabled selected>--- Select Semester ---</option>
                        @foreach($semesterList as $semester)
                            <option value="{{$semester->id}}" data-id="{{ date('m/d/Y', strtotime($semester->start_date)) }}" data-key="{{ date('m/d/Y', strtotime($semester->end_date)) }}">{{$semester->name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="from_date">From Date</label>
                    <input readonly class="form-control dataPicker" name="from_date" id="from_date" type="text" required>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="to_date">To Date</label>
                    <input readonly class="form-control dataPicker" name="to_date" id="to_date" type="text" required>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="attendance_type">Attendance Type</label>
                    <select id="attendance_type" class="form-control" name="attendance_type" required>
                        <option value="" disabled selected>--- Select attendance Type ---</option>
                        <option value="att_school">School Attendance</option>
                        <option value="att_class">Class Attendance</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="doc_type">Report Type</label>
                    <select id="doc_type" class="form-control" name="doc_type" required>
                        <option value="" disabled selected>--- Select Type ---</option>
                        <option value="pdf">PDF</option>
                        <option value="excel">Excel</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
    </div>
    <!--./body-->
    <div class="modal-footer">
        <button id="std-att-report-submit-btn" type="submit" class="btn btn-info">Submit</button>
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
</form>

<script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>

<script type="text/javascript">
    $(function() { // document ready

        //Date picker
        $('.dataPicker').datepicker({ autoclose: true });
        // set attendance date into the to_date and from_date
        jQuery(document).on('change','#academic_semester',function(){
            $('#from_date').val($(this).find(':selected').attr('data-id'));
            $('#to_date').val($(this).find(':selected').attr('data-key'));
        });


        $('#std_name').keypress(function(){
            // clear std_id
            $('#std_id').val('');
            // refresh submit button
            refreshSubmit();
            // auto complete
            $(this).autocomplete({
                source: loadFromAjax,
                minLength: 1,

                select: function (event, ui) {
                    // Prevent value from being put in the input:
                    this.value = ui.item.label;
                    // Set the next input's value to the "value" of the item.
                    $(this).next("input").val(ui.item.id);
                    event.preventDefault();
                    // refresh submit button
                    refreshSubmit();
                }
            });

            function loadFromAjax(request, response) {
                var term = $("#std_name").val();
                $.ajax({
                    url: '/student/find/student',
                    dataType: 'json',
                    data:{'term': term},

                    beforeSend: function() {
                        // refresh submit button
                        refreshSubmit();
                    },

                    success: function(data) {
                        // you can format data here if necessary
                        response($.map(data, function (el) {
                            return {
                                label: el.name,
                                value: el.name,
                                id:el.id
                            };
                        }));
                    }
                });
            }

            function refreshSubmit() {
                var std_att_report_submit_btn = $('#std-att-report-submit-btn');
                // checking
                if($('#std_id').val()){
                    std_att_report_submit_btn.removeClass('hide');
                }else{
                    std_att_report_submit_btn.addClass('hide');
                }
            }
        });

    });
</script>
