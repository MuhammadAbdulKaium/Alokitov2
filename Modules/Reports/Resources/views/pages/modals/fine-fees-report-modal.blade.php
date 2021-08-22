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
                <div id="summary-report" class="tab-pane fade in active">
                    <form id="#" action="{{url('/fees/student/fine/report')}}" method="post">
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


    $('#search_fees_name').keypress(function() {
        $(this).autocomplete({
            source: loadFromAjax,
            minLength: 1,

            select: function(event, ui) {
                // Prevent value from being put in the input:
                this.value = ui.item.label;
                // Set the next input's value to the "value" of the item.
                $(this).next("input").val(ui.item.id);
                // class section here...

            batch_section();

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


    function batch_section() {
                // get fees level id
                var fees_id = $('#fees_id').val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "{{ url('/fees/find/batch') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': fees_id }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },
                    success:function(data){
                        op+='<option value="" selected disabled>--- Select Batch ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                        }
                        // set value to the fees batch
                        $('.feesBatch').html("");
                        $('.feesBatch').append(op);
                        // set value to the fees secton
                        $('.feesSection').html("");
                        $('.feesSection').append('<option value="0" selected disabled>--- Select Section ---</option>');

                    },
                    error:function(){
                        // statements
                    }
                });


            // request for section list using batch id
            jQuery(document).on('change','.feesBatch',function(){
                // fees id
                var fees_id = $('#fees_id').val();
                // get fees level id
                var batch_id = $(this).val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "{{ url('/fees/find/section') }}",
                    type: 'GET',
                    cache: false,
                    data: {'fees_id': fees_id,'batch_id': batch_id }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        op+='<option value="" selected disabled>--- Select Section ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                        }
                        // set value to the fees batch
                        $('.feesSection').html("");
                        $('.feesSection').append(op);

                    },
                    error:function(){
                        // statements
                    },
                });
            });
    }



</script>
