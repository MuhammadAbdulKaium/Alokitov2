<style>
    .ui-autocomplete {z-index:2147483647;}
    .ui-autocomplete span.hl_results {background-color: #ffff66 ;}
</style>

<div class="modal-content">

    <form  action="/library/library-borrow-transaction/issue-book/store" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}">
        @if(empty($issueBookProfile))
        <input type="hidden" value="{{$bookProfile->id}}" name="book_id">
        <input type="hidden" value="{{$bookProfile->isbn_no}}" name="isbn_no">
        <input type="hidden" value="{{$bookStockProfile->asn_no}}" name="asn_no">
        @endif

        <input type="hidden" @if(!empty($issueBookProfile)) value="{{$issueBookProfile->id}}" @endif name="issue_book_id">

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
            </button>
            @if(empty($issueBookProfile))
            <h4 class="modal-title">
                <i class="fa fa-plus-square"></i> Issue Book		</h4>
                @else
                <h4 class="box-title">
                    <i class="fa fa-pencil-square"></i> Update Details		</h4>

            @endif
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="col-sm-6">
                    <div class="form-group field-libraryborrowtransaction-lbt_holder_type required">
                        <label class="control-label" for="libraryborrowtransaction-lbt_holder_type">Holder Type</label>
                        <select id="libraryborrowtransaction-lbt_holder_type" class="form-control" @if(!empty($issueBookProfile)) disabled @endif name="holder_type" aria-required="true">
                            <option value="">--- Select Holder Type ---</option>

                            <option value="1"  @if(!empty($issueBookProfile) && $issueBookProfile->holder_type=="1")  selected @endif >Student</option>
                            <option value="2"  @if(!empty($issueBookProfile) && $issueBookProfile->holder_type=="2")  selected @endif >Employee</option>
                        </select>

                        <div class="help-block"></div>
                    </div>		</div>
                <div class="col-sm-6" id="name">
                    <div class="form-group field-libraryborrowtransaction-name required">
                        <label class="control-label" for="libraryborrowtransaction-name">Holder Name</label>
                        <input class="form-control" id="employee" name="payer_name" type="text" @if(!empty($issueBookProfile)) value="{{$issueBookProfile->student()->first_name.' '.$issueBookProfile->student()->middle_name.' '.$issueBookProfile->student()->last_name}}" disabled @endif  placeholder="Type Student Name">
                        <input id="std_id" name="holder_id" type="hidden" value=""/>
                        <div class="help-block"></div>
                    </div>
                </div>
                </div>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group field-libraryborrowtransaction-lbt_daily_fine required">
                        <label class="control-label" for="libraryborrowtransaction-lbt_daily_fine">Daily Fine</label>
                        <input class="form-control" name="daily_fine" @if(!empty($issueBookProfile)) value="{{$issueBookProfile->daily_fine}}" disabled @endif placeholder="Daily fine" type="number">

                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group field-libraryborrowtransaction-lbt_issue_date required">
                        <label class="control-label" for="libraryborrowtransaction-lbt_issue_date">Issue Date</label>
                        <input class="form-control datepicker" name="issue_date" readonly=""  @if(!empty($issueBookProfile)) value="{{date('m/d/Y',strtotime($issueBookProfile->issue_date))}}"  @endif size="10" type="text">

                        <div class="help-block"></div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group field-libraryborrowtransaction-lbt_due_date required">
                        <label class="control-label" for="libraryborrowtransaction-lbt_due_date">Due Date</label>
                        <input id="libraryborrowtransaction-lbt_due_date" class="form-control datepicker" @if(!empty($issueBookProfile)) value="{{date('m/d/Y',strtotime($issueBookProfile->due_date))}}"  @endif  name="due_date" readonly="" size="10" type="text">


                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
            @if(empty($issueBookProfile))
            <button type="submit" class="btn btn-primary pull-left">Issue Book</button></div>
        @else
            <button type="submit" class="btn btn-primary pull-left">Update Details</button></div>
        @endif

        <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-3" tabindex="0" style="display: none;"></ul></form>

    <script type="text/javascript">

//        $('#libraryborrowtransaction-name').attr('disabled', 'disabled');
$('.datepicker').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"dd-mm-yy"});




        $(document).on('change','#libraryborrowtransaction-lbt_holder_type', function() {
//            $('#libraryborrowtransaction-name').removeAttr('disabled');
//            $('#libraryborrowtransaction-name').val('');
            var value = $('#libraryborrowtransaction-lbt_holder_type').val();
            if(value == '1') {

                // get student

                // get student name and select auto complete

                $('#employee').keypress(function () {
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

                    /// load student name form
                    function loadFromAjax(request, response) {
                        var term = $("#employee").val();
                        $.ajax({
                            url: '/student/find/student',
                            dataType: 'json',
                            data: {
                                'term': term
                            },
                            success: function (data) {
                                // you can format data here if necessary
                                response($.map(data, function (el) {
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

            }
            //end student

            //start employee

                if(value == '2'){

                    // get student name and select auto complete

                    $('#employee').keypress(function() {
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
                            var term = $("#employee").val();
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

                }

                // end employee

        });

$('#dueDate').datepicker({"changeMonth":true,"changeYear":true,"dateFormat":"mm/dd/yy"});


    </script>
</div>