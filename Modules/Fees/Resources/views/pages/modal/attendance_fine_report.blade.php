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
    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Fine Fees Report card</h4>
</div>

{{--modal-body--}}
<div class="modal-body">
    <div class="row">
        <div class="col-md-12">
            {{--tab content--}}
            <div class="tab-content">
                {{--summary report--}}
                <div id="attendance-fine-report" class="tab-pane fade in active">
                    <form id="#" action="{{url('/fees/student/attendance/fine/report')}}" method="post">
                        <input type="hidden" name="_token" value="{{csrf_token()}}">
                        <div class="modal-body">
                            <div class="row">
                                {{--<div class="col-sm-4">--}}
                                {{--<div class="form-group">--}}
                                {{--<label class="control-label">Fees Name</label>--}}
                                {{--<input class="form-control" id="search_fees_name" name="fees_name" type="text" value="@if(!empty($allInputs)) {{$allInputs->fees_name}} @endif" placeholder="Type Fees Name">--}}
                                {{--<input id="fees_id" name="fees_id" type="hidden" value="@if(!empty($allInputs)) {{$allInputs->fees_id}} @endif" />--}}
                                {{--<div class="help-block"></div>--}}
                                {{--</div>--}}
                                {{--</div>--}}

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="fromdatepicker">From Date</label>
                                        <input readonly class="form-control" name="from_date" id="fromdatepicker" type="text">
                                        <div class="help-block"></div>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label class="control-label" for="todatepicker">To Date</label>
                                        <input readonly class="form-control" name="to_date" id="todatepicker" type="text">
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
        </div>
    </div>

    <script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>


    <script type="text/javascript">
        // get Fees autocomplete

        //Date picker
        $('#fromdatepicker').datepicker({
            autoclose: true,
        });
        //Date picker
        $('#todatepicker').datepicker({
            autoclose: true,
        });



    </script>
