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
    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Fees Details Report Card</h4>
</div>

{{--modal-body--}}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            {{--tab content--}}
                <form id="#" action="{{url('/fees/student/details/report-card')}}" method="post">
                    <input type="hidden" name="_token" value="{{csrf_token()}}">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="fromdatepicker">Fees Id</label>
                                    <input  class="form-control" name="fid" id="fid" type="text">
                                    <div class="help-block"></div>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="todatepicker">Fees Name</label>
                                    <input class="form-control" id="search_fees_name" name="fees_name" type="text"  placeholder="Type Fees Name">
                                    <input id="fees_id" name="fees_id" type="hidden" />
                                    <div class="help-block"></div>
                                </div>
                            </div>

                            <div class="col-sm-4">
                                <div class="form-group">
                                    <label class="control-label" for="invoice_status">Invoice Status</label>
                                    <select id="invoice_status" class="form-control" name="invoice_status" required>
                                        <option value="">--- Select Type ---</option>
                                        <option value="5">All</option>
                                        <option value="1">Paid</option>
                                        <option value="2">Unpaid</option>
                                    </select>
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
</div>

<script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>


<script type="text/javascript">
    // get Fees autocomplete

    $('#search_fees_name').keypress(function() {
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
            var term = $("#search_fees_name").val();
            $.ajax({
                url: '/fees/find/all_fees',
                dataType: 'json',
                data: {
                    'term': term
                },
                success: function(data) {
                    // you can format data here if necessary
                    response($.map(data, function(el) {
                        return {
                            label: el.fee_name,
                            value: el.fee_name,
                            id: el.id
                        };
                    }));
                }
            });
        }
    });

</script>
