<form action="{{url('/student/update-activity/'.$activity->id)}}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> <b>Edit Activity</b></h4>
    </div>

    <div class="modal-body">
        <div class="row">
            <div class="col-sm-2">
                <div class="form-group">
                    <select class="form-control" name="cmbCategory" id="cmbCategory" required>
                        <option value="" selected>Select Category</option>
                        @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ ($category->id == $activity->student_activity_directory_category_id)?'selected':'' }}>{{ $category->category_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <select class="form-control" name="room_id" id="room">
                        <option value="" selected>Select Room</option>
                        @foreach ($rooms as $room)
                        <option value="{{ $room->id }}" {{ ($room->id == $activity->room_id)?'selected':'' }}>{{ $room->name }}
                            
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <input class="form-control" type="text" name="activityName"
                        placeholder="Activity Name" required value="{{ $activity->activity_name }}"> 
                </div>
            </div>
            <div class="col-sm-4">
                <div class="form-group">
                    <input class="form-control" type="text" name="activityRemarks"
                        placeholder="Remarks" required value="{{ $activity->remarks }}">
                </div>
            </div>
        </div>
    </div>
    <!--./body-->

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-left">Submit</button>
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
</form>