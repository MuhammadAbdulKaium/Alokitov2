
<style type="text/css">
    .ui-autocomplete {z-index:2147483647;}
    .ui-autocomplete span.hl_results {background-color: #ffff66 ;}
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">×</button>
    <h4 class="modal-title" id="gsmTitle">Add Payer</h4>
</div>

{{--@php--}}
{{--$fees=$invoiceInfo->fees();--}}

{{--@endphp--}}


<div class="alert alert-warning payer-error-message" style="margin: 30px; display: none">
    <p>Payer Already Exits</p>
</div>

<div class="alert alert-success payer-success-message" style="margin: 30px; display: none">
    <p>Payer Added Successfully</p>
</div>




<form  id="advacePaymentModal"  class="form-horizontal margin-none" style="margin-top: 20px; padding: 10px" >
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    {{--<input type="hidden" name="fees_id" value="{{$fees_id}}">--}}
    <div class="form-group">
        <label class="col-md-2 control-label" for="firstname">Add Payer</label>
        <div class="col-md-4">
            <input class="form-control" id="std_name_2" name="payer_name" type="text" placeholder="Type Student Name">
            <input id="std_id" name="std_id" type="hidden" />
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <label class="col-md-2 control-label" for="advance_amount">Advance Amount</label>
        <div class="col-md-4">
            <input class="form-control" id="advance_amount" name="advance_amount" type="text" placeholder="Amount">
            <div class="help-block"></div>
        </div>
    </div>

    <div class="form-group">
        <div class="col-md-4 col-md-offset-2">
            <button type="submit" class="btn btn-primary" id="addStudnetPayer">Submit</button>

        </div>
    </div>
    </div>


    <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="apymentCloseButton" data-dismiss="modal">Close</button>
    </div>
</form>


<script>

    $(document).ready(function() {
        $('.auto_sms').on('change', function(){
            this.value = this.checked ? 1 : 0;
        }).change();
    });


    // request for payers fees payer id and fees id
    $('form#advacePaymentModal').on('submit', function (e) {
        e.preventDefault();
        // ajax request
        $.ajax({

            url: '/fees/advance/payment/store/',
            type: 'POST',
            cache: false,
            data: $('form#advacePaymentModal').serialize(),
            datatype: 'json/application',

            beforeSend: function() {
                {{--alert($('form#PayerStudent').serialize());--}}
            },

            success:function(data){
//                alert(JSON.stringify(data));
                if(data=="error") {
                    $(".payer-success-message").hide();
                    $(".payer-error-message").show();
                } else {
                    $(".payer-error-message").hide();
                    $(".payer-success-message").show();
                    $("tbody.add-payer-section").prepend(data);
                }
//                if(data == 'success'){
//                    alert("Payer Added Successfully");
//                }else if(data == 'error'){
//                    alert("Payer Already Exist");
//                }
            },

            error:function(data){
                alert(JSON.stringify(data));
            }
        });


    });







    // get student name and select auto complete

    $('#std_name_2').keypress(function() {
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
            var term = $("#std_name_2").val();
            $.ajax({
                url: '/student/find/student',
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



</script>