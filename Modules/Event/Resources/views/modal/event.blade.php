
<form action="{{url('/event/store/')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title text-bold">
            <i class="fa fa-info-circle"></i> Event Create
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="academic_level">Event Name</label>
                    <input type="text" class="form-control" name="event_name" id="event_name" required>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="batch">Category</label>
                    <select id="category" class="form-control category" name="category" required>
                        <option value="" selected disabled>--- Select Category ---</option>
                        @foreach ($categories as $cat)
                            <option value="{{$cat->id}}">{{$cat->performance_type}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="batch">Sub-category</label>
                    <select id="sub_category" class="form-control sub_category" name="sub_category" onchange="" required>
                        <option value="" selected disabled>--- Select Sub-category ---</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="batch">Activity</label>
                    <select id="activity" class="form-control activity" name="activity" onchange="" required>
                        <option value="" selected disabled>--- Select Activity ---</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="batch">Judge</label>
                    <select name="employees[]" id="select-employees" class="form-control room-field" multiple
                            required >
                        <option value="">-- FM / HR --</option>
                        @foreach ($employees as $employee)
                            <option value="{{$employee->id}}">{{$employee->first_name}}
                                {{$employee->last_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="batch">Status</label>
                    <select id="status" class="form-control academicBatch" name="status" onchange="" required>
                        <option value="" selected disabled>--- Select Sttaus ---</option>
                        <option value="1">Open</option>
                        <option value="0">Close</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="batch">Remarks</label>
                    <textarea name="remarks" class="form-control" id="remarks" cols="30" rows="3"></textarea>
                </div>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-right">Submit</button>
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
    </div>
</form>
<script>
    $(document).ready(function () {
        $('#select-employees').select2();
    })
</script>
<script>
    var host = window.location.origin;
    $("#category").change(function(){
        if($(this).val() != "")
        {
            $.ajax({
                type: "get",
                url: host + '/event/find/sub_cat/'+ $(this).val(),
                data: "",
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                success: function (response) {
                    $("#sub_category").html(response);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                    console.log(errorThrown);
                }
            });
        }
        else
        {
            $("#sub_category").html("");
        }
    });

    $("#sub_category").change(function(){
        if($(this).val() != "")
        {
            $.ajax({
                type: "get",
                url: host + '/event/find/activity/'+ $(this).val(),
                data: "",
                dataType: "json",
                contentType: "application/json; charset=utf-8",
                success: function (response) {
                    // return response;
                    $("#activity").html(response);
                },
                error: function (XMLHttpRequest, textStatus, errorThrown) {
                    alert(errorThrown);
                    console.log(errorThrown);
                }
            });
        }else
        {
            $("#activity").html("");
        }
    });
</script>