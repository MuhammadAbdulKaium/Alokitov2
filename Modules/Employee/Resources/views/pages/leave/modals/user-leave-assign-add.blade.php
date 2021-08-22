<style>
    .ui-autocomplete {z-index:2147483647;}
    .ui-autocomplete span.hl_results {background-color: #ffff66 ;}
</style>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <p>
        </p><h4>
            <i class="fa fa-plus-square"></i> Create Leave Assign</h4>
        <p></p>
    </div>
    <div class="modal-body">
        <form name="add_name" id="add_name">
            <div class="alert alert-danger show-error-message" style="display:none">
                <ul></ul>
            </div>
            <div class="alert alert-success show-success-message" style="display:none">
                <ul></ul>
            </div>
            <div class="row">
                <div class="col-sm-6" id="name">
                    <div class="form-group field-libraryborrowtransaction-name required">
                        <label class="control-label" for="libraryborrowtransaction-name">Employee Name</label>
                        <input class="form-control" id="employee" name="payer_name" type="text" placeholder="Type Student Name">
                        <input id="std_id" name="holder_id" type="hidden" value=""/>
                        <div class="help-block"></div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="dynamic_field">
                    <tr>
                        <td><select name="category[]" id="category" class="form-control"><option value="">Select Category</option>@foreach($leave_type as $type)<option value="{{$type->id}}">{{$type->name}}</option>@endforeach</select></td>
                        <td><input type="text" name="duration[]" placeholder="Leave Duration" class="form-control name_list" id="duration"/></td>
                        <td><select name="process[]" id="process" class="form-control"><option value="">Select Process</option><option value="">Sequential</option><option value="">Working Day</option></select</td>
                        <td><button type="button" name="add" id="add" class="btn btn-success">Add More</button></td>
                    </tr>
                </table>
                <input type="button" name="submit" id="submit" class="btn btn-primary" value="Submit" />
            </div>
            <ul class="ui-autocomplete ui-front ui-menu ui-widget ui-widget-content" id="ui-id-3" tabindex="0" style="display: none;"></ul>
        </form>
    </div>


    <script type="text/javascript">
        $(document).ready(function(){
            var url = "{{ url('add-remove-input-fields') }}";
            var i=1;
            $('#add').click(function(){
                var category = $("#category").val();
                var duration = $("#duration").val();
                var process = $("#process").val();
                i++;
                $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><select name="category[]" id="" class="form-control"><option value="">Select Category</option>@foreach($leave_type as $type)<option value="{{$type->id}}">{{$type->name}}</option>@endforeach</select></td>' +
                    '<td><input type="text" name="duration[]" placeholder="Enter Duration" class="form-control name_list" value="'+duration+'" /></td>' +
                    '<td><select name="process[]" id="" class="form-control"><option value="">Select Process</option><option value="">Sequential</option><option value="">Working Day</option></select></td>'+
                    '<td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');
            });
            $(document).on('click', '.btn_remove', function(){
                var button_id = $(this).attr("id");
                $('#row'+button_id+'').remove();
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#submit').click(function(){
                $.ajax({
                    url:"{{ url('add-remove-input-fields') }}",
                    method:"POST",
                    data:$('#add_name').serialize(),
                    type:'json',
                    success:function(data)
                    {
                        if(data.error){
                            display_error_messages(data.error);
                        }else{
                            i=1;
                            $('.dynamic-added').remove();
                            $('#add_name')[0].reset();
                            $(".show-success-message").find("ul").html('');
                            $(".show-success-message").css('display','block');
                            $(".show-error-message").css('display','none');
                            $(".show-success-message").find("ul").append('<li>Todos Has Been Successfully Inserted.</li>');
                        }
                    }
                });
            });
            function display_error_messages(msg) {
                $(".show-error-message").find("ul").html('');
                $(".show-error-message").css('display','block');
                $(".show-success-message").css('display','none');
                $.each( msg, function( key, value ) {
                    $(".show-error-message").find("ul").append('<li>'+value+'</li>');
                });
            }
        });

        //start employee
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

            // end employee
    </script>
