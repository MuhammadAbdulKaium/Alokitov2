<div class="container mt-3">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h2 class="text-success">Edit Menu</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <form name="add_name" id="add_name">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" name="id" class="form-control name_list" id="id" value="{{$menus->id}}">
                            <input type="hidden" name="menu_type" class="form-control name_list" id="menu_type" value="{{$menus->menu_type}}">
                            <div class="form-group">
                                <label for="inputState">Menu Name</label>
                                <input type="text" name="menu_name" class="form-control name_list" id="menu" value="{{$menus->menu_name}}">
                            </div>
                            <div class="form-group">
                                <label for="inputState">URL</label>
                                <input type="text" name="route" placeholder="Enter URL" class="form-control name_list" id="url" required value="{{$menus->route}}">
                            </div>
                            <div class="form-group">
                                <label for="inputState">Icon</label>
                                <input type="text" name="icon" placeholder="Enter Icon" class="form-control name_list" id="icon" required value="{{$menus->icon}}">

                            </div>
                            <div class="form-group">
                                <label for="inputState">Status</label>
                                <select id="inputState" class="form-control" name="status" required>
                                    <option value="{{$menus->status}}">{{$menus->status=1 ? 'Active' : 'De-Active'}}</option>
                                    <option value="1">Active</option>
                                    <option value="1">De-Active</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputState">Comment</label>
                                <input type="text" name="comment" class="form-control name_list" id="menu" value="{{$menus->comment}}">
                            </div>
                            <div class="form-group">
                                <label for="inputState">Parent Menu</label>
                                <select id="inputState" class="form-control" name="parent_id" required>
                                    @if($menus->parent_id)
                                        <option value="{{$menus->parent_id}}">{{$menus->menu_name}}</option>
                                        @foreach($parents as $par)
                                        <option value="{{$par->id}}">{{$par->menu_name}}</option>
                                        @endforeach
                                    @else
                                        <option value="">--Select--</option>
                                        @foreach($parents as $par)
                                            <option value="{{$par->id}}">{{$par->menu_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="inputState">Sub Menu</label>
                                <select id="inputState" class="form-control" name="grand_parent_id" required>
                                    @if($menus->grand_parent_id)
                                        <option value="{{$menus->grand_parent_id}}">{{$menus->menu_name}}</option>
                                        @foreach($childs as $ch)
                                            <option value="{{$ch->id}}">{{$ch->menu_name}}</option>
                                        @endforeach
                                    @else
                                        <option value="">--Select--</option>
                                        @foreach($childs as $ch)
                                            <option value="{{$ch->id}}">{{$ch->menu_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <input type="button" name="submit" id="submit" class="btn btn-primary" value="Submit" />
                            <button type="button" class="btn btn-default pull-right" data-dismiss="modal">Close</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $('#submit').click(function(){
            $.ajax({
                url:"{{ url('/role-management/menus/update/') }}",
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