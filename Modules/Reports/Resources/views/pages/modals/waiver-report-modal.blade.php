<link href="{{ asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">
<style type="text/css">
    .ui-autocomplete {
        z-index:2147483647;
    }

    .ui-autocomplete span.hl_results {
        background-color: #ffff66;
    }
</style>

{{--modal-header--}}
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Waiver Report Card</h4>
</div>

{{--modal-body--}}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="pill" href="#summary-report">Summary Report</a></li>
                <li><a data-toggle="pill" href="#details-report">Details Report</a></li>
            </ul>
            {{--tab content--}}
            <div class="tab-content">
                {{--summary report--}}
                <div id="summary-report" class="tab-pane fade in active">
                    <form id="#" action="{{url('/reports/waiver/batch/section')}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label">Class </label>
                                        <select name="batch" id="batch_select"  class="form-control academicBatch">
                                            @if($batchs->count()>0)
                                                <option value="">Select Batch</option>
                                                @foreach($batchs as $batch )
                                                    <option value="{{$batch->id}}">{{$batch->batch_name}} @if($batch->get_division()) {{$batch->get_division()->name}} @endif</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>


                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="section">Section</label>
                                        <select id="section" class="form-control academicSection" name="section">
                                            <option value="" selected disabled>--- Select Section ---</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="doc_type">Waiver Status</label>
                                        <select id="waiver_status" class="form-control" name="waiver_status" required>
                                            <option value="">--- Select Waiver Status ---</option>
                                            <option value="1">Active</option>
                                            <option value="2">Deactive</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="fromdatepicker">From Date</label>
                                        <input readonly class="form-control" name="from_date" id="fromdatepicker" type="text">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="todatepicker">To Date</label>
                                        <input readonly class="form-control" name="to_date" id="todatepicker" type="text">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="doc_type">Type</label>
                                        <select id="doc_type" class="form-control" name="doc_type" required>
                                            <option value="">--- Select Type ---</option>
                                            <option value="pdf">PDF</option>
                                            <option value="xlsx">Excel</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--./body-->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info">Submit</button>     <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
                        </div>
                    </form>
                </div>


                {{--details report--}}
                <div id="details-report" class="tab-pane fade">
                    <form action="{{url('/reports/waiver/single/student/')}}" method="POST">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">

                        <div class="row">
                            <div class="col-sm-8">
                                <label class="control-label">Select Student</label>
                                <div class="form-group">
                                    <input class="form-control" id="std_name" type="text" placeholder="Type Student Name">
                                    <input id="std_id" name="std_id" type="hidden" value="" />
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="details_doc_type">Type</label>
                                    <select id="doc_type" class="form-control" name="doc_type" required>
                                        <option value="">--- Select Type ---</option>
                                        <option value="pdf">PDF</option>
                                        <option value="xlsx">Excel</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info pull-left">Submit</button>
                                <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>


<script type="text/javascript">
    $(function() { // document ready

        //Date picker
        $('#fromdatepicker').datepicker({
            autoclose: true,
        });
        //Date picker
        $('#todatepicker').datepicker({
            autoclose: true,
        });


        $('#std_name').keypress(function(){
            $(this).autocomplete({
                source: loadFromAjax,
                minLength: 1,

                select: function (event, ui) {
                    // Prevent value from being put in the input:
                    this.value = ui.item.label;
                    // Set the next input's value to the "value" of the item.
                    $(this).next("input").val(ui.item.id);
                    event.preventDefault();
                }
            });

            function loadFromAjax(request, response) {
                var term = $("#std_name").val();
                $.ajax({
                    url: '/student/find/student',
                    dataType: 'json',
                    data:{'term': term},
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
                    // statements
                },

                success:function(data){
                    op+='<option value="" selected disabled>--- Select Section ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }
                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);

                    $('.academicSubject').html("");
                    $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                    $('#assessment_table_row').html('');
                    // semester reset
                    $('.academicSemester option:first').prop('selected', true);
                },
                error:function(){
                    // statements
                },
            });
        });



    });
</script>
