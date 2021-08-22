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
    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Fees Invoice Report card</h4>
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
                    <form id="#" action="{{url('/fees/all/student/invoice/report-card')}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="modal-body">
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
                                        <label class="control-label" for="invoice_type">Type</label>
                                        <select id="invoice_type" class="form-control" name="invoice_type" required>
                                            <option value="">--- Select Type ---</option>
                                            <option value="3">All</option>
                                            <option value="1">Fees</option>
                                            <option value="2">Attendance Fine</option>
                                        </select>
                                        <div class="help-block"></div>
                                    </div>
                                </div>

                                <div class="col-sm-4">
                                    <div class="form-group">
                                        <label class="control-label" for="doc_type">Report Type</label>
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
                    <form action="{{url('/fees/single/student/invoice/report-card')}}" method="POST">
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
                                    <label class="control-label" for="invoice_type">Type</label>
                                    <select id="invoice_type" class="form-control" name="invoice_type" required>
                                        <option value="">--- Select Type ---</option>
                                        <option value="3">All</option>
                                        <option value="1">Fees</option>
                                        <option value="2">Attendance Fine</option>
                                    </select>
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="details_doc_type">Report Type</label>
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

    });
</script>
