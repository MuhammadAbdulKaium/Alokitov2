
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




<form  id="payerAddForm"  class="form-horizontal margin-none" style="margin-top: 20px; padding: 10px" >
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <input type="hidden" name="fees_id" value="{{$fees_id}}">
    <div class="form-group">
        <label class="col-md-2 control-label" for="firstname">Add Payer</label>
        <div class="col-md-4">
            <input class="form-control" id="std_name" name="payer_name" type="text" placeholder="Type Student Name">
            <input id="std_id" name="std_id" type="hidden" />
            <div class="help-block"></div>
        </div>
    </div>

    @if(!empty($feesModule) && ($feesModule->count()>0))
    <div class="form-group">
        <label class="col-md-2 control-label" for="firstname">Send Auto Sms</label>
        <div class="col-md-4">
            <input type="hidden" name="auto_sms" class="auto_sms" value="0">
            <input type="checkbox" name="auto_sms" class="auto_sms" value="0">
            </div>
        </div>
    </div>

    @endif

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
    $('form#payerAddForm').on('submit', function (e) {
        e.preventDefault();
        // ajax request
        $.ajax({

            url: '/fees/feesmanage/add/payer/',
            type: 'POST',
            cache: false,
            data: $('form#payerAddForm').serialize(),
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

    $('#std_name').keypress(function() {
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
            var term = $("#std_name").val();
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