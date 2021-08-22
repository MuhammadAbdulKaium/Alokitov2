<form action="{{url('/student/edit-activity-category/'.$activityCategory->id)}}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-plus"></i> <b>Edit Category</b></h4>
    </div>

    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group">
                    <input class="form-control" type="text" name="categoryName"
                        placeholder="Category Name" value="{{ $activityCategory->category_name }}" required>
                </div>
            </div>
            <div class="col-sm-5">
                <div class="form-group">
                    <select id="activity" class="form-control select2" name="cadetHrFm[]"
                        multiple="multiple" required>
                        @foreach ($userTypes as $userType)
                        @php
                            $flag = 0;
                        @endphp
                        @foreach ($activityCategory->userTypes as $myuserType)
                            @if ($myuserType->id == $userType->id)
                                @php
                                    $flag = 1;
                                @endphp
                            @endif
                        @endforeach
                        <option value="{{$userType->id}}" {{ ($flag == 1)?'selected':'' }}>{{$userType->title}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-7">
                <input class="form-control" type="text" name="remarks" placeholder="Remarks" value="{{ $activityCategory->remarks }}" required>
            </div>
        </div>
    </div>
    <!--./body-->

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-left">Submit</button>
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
</form>

<script>
    $('.select2').select2();
</script>