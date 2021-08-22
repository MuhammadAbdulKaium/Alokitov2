
<form action="{{url('/event/update/'.$event->id)}}" method="POST">
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
                    <input type="text" class="form-control" name="event_name" id="event_name" value="{{$event->event_name}}" required>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="batch">Category</label>
                    <select id="category" class="form-control category" name="category" required>
                        <option value="" selected disabled>--- Select Category ---</option>
                        @foreach ($categories as $cat)
                            <option value="{{$cat->id}}" {{$cat->id==$event->category_id?"selected":''}}>{{$cat->performance_type}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="batch">Sub-category</label>
                    <select id="sub_category" class="form-control sub_category" name="sub_category" onchange="" required>
                        @foreach ($sub_categories as $sub_category)
                            <option value="{{$sub_category->id}}" {{$sub_category->id==$event->sub_category_id?"selected":''}}>{{$sub_category->category_name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="batch">Activity</label>
                    <select id="activity" class="form-control activity" name="activity" onchange="" required>
                        @foreach ($activities as $activity)
                            <option value="{{$activity->id}}" {{$activity->id==$event->activity_id?"selected":''}}>{{$activity->activity_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form-group">
                    <label class="control-label" for="batch">Judge</label>
                    <select name="employees[]" id="select-employees" class="form-control room-field" multiple
                            required >
                        <option value="">-- FM / HR --</option>
                        @php
                            $employee_ids=json_decode($event->employee_id,true);

                        @endphp

                            @foreach($employees as $emp_list)
                                @php
                                    $flag = false;
                                @endphp
                                @foreach($employee_ids as $em)
                                    @if($emp_list->id==$em)
                                        @php
                                          $flag=true;
                                        @endphp
                                    @endif
                                @endforeach
                            <option value="{{$emp_list->id}}" {{$flag? 'selected': ''}}>{{$emp_list->first_name}} {{$emp_list->last_name}}</option>
                            @endforeach

                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="batch">Status</label>
                    <select id="status" class="form-control academicBatch" name="status" onchange="" required>
                        <option value="" selected disabled>--- Select Sttaus ---</option>
                        <option value="1" {{$event->status==1?"selected":''}}>Open</option>
                        <option value="0" {{$event->status==0?"selected":''}}>Close</option>
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="batch">Remarks</label>
                    <textarea name="remarks" class="form-control" id="remarks" cols="30" rows="3">{{$event->remarks}}</textarea>
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