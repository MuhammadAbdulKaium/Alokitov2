<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-success">Add Menu</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <form name="add_name" id="add_name">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="alert alert-danger show-error-message" style="display:none">
                                <ul></ul>
                            </div>
                            <div class="alert alert-success show-success-message" style="display:none">
                                <ul></ul>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-bordered" id="dynamic_field">
                                    <tr>
                                        <td><input type="text" name="menu_name" placeholder="Enter Menu Name" class="form-control name_list" id="menu" required></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="route" placeholder="Enter URL" class="form-control name_list" id="url" required></td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="icon" placeholder="Enter Icon" class="form-control name_list" id="icon" required></td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="status" id="" class="form-control" required>
                                                <option>Status</option>
                                                <option value="1">Active</option>
                                                <option value="2">De-Active</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="menu_type" id="" class="form-control" required>
                                                <option value="">Type</option>
                                                <option value="1">Module</option>
                                                <option value="2">Sub Module</option>
                                                <option value="3">Page Menu</option>
                                                <option value="4">Link Menu</option>
                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><input type="text" name="comment" placeholder="Enter Comments" class="form-control name_list" id="comment"></td>
                                    </tr>
                                    <tr id="sub_menu">
                                        <td><button type="button" name="add" id="add" class="btn btn-success">Add Parent Menu</button></td>
                                    </tr>
                                    <tr id="grand_sub_menu">
                                        <td><button type="button" name="addGrand" id="addGrand" class="btn btn-success">Add Sub Menu</button></td>
                                    </tr>
                                </table>
                                <input type="button" name="submit" id="submit" class="btn btn-primary" value="Submit" />
                                <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        var url = "{{ url('add-remove-input-fields') }}";
        var i=1;
        $("#grand_sub_menu").hide();
        $('#add').click(function(){
            var title = $("#menu").val();
            i++;
            $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><select name="parent_id" id="parent_id" class="form-control"><option value="0">--Parent Menu--</option>"+@foreach($parent as $par)+"<option value="'+{{$par->id}}+'">'+`{{$par->menu_name}}`+'</option>"+@endforeach+"</select></td></tr>');
            $("#grand_sub_menu").show();
            $("#sub_menu").hide();
        });
        $('#addGrand').click(function(){
            $("#grand_sub_menu").hide();
            var title = $("#url").val();
            i++;
            $('#dynamic_field').append('<tr id="row'+i+'" class="dynamic-added"><td><select name="grand_parent_id" id="grand_parent_id" class="form-control"><option value="0">--Sub Menu--</option>"+@foreach($child as $ch)+"<option value="'+{{$ch->id}}+'">'+`{{$ch->menu_name}}`+'</option>"+@endforeach+"</select></td></tr>');
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
                url:"{{ url('/role-management/menus/store') }}",
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
                        $(".show-success-message").find("ul").append('<li>Menu Has Been Successfully Inserted.</li>');
                    }
                    $("#globalModal").modal('hide');
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
</script>