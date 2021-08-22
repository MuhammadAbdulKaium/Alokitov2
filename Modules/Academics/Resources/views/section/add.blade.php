<form action="{{url('/academics/store-section')}}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Create Form
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="academic_level">Academic Level</label>
                    <select id="academic_level" class="form-control academicLevel" required>
                        <option value="" selected disabled>--- Select Level ---</option>
                        @foreach($academicLevels as $level)
                            <option value="{{$level->id }}">{{$level->level_name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="batch">Class</label>
                    <select id="batch" class="form-control academicBatch select-batch" name="batch_id" required>
                        <option value="" selected disabled>--- Select Class ---</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="section_name">Form Name:</label>
                    <input type="text" class="form-control" id="section_name" name="section_name" required>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <label for="intake">Intake:</label>
                    <input type="text" class="form-control" id="intake" name="intake" value="" required>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                {{-- <label class="control-label" for="start_date">Is Division</label>
                <input id="division-checkbox" class="checkbox" name="division" maxlength="20"
                    type="checkbox"> --}}
            </div>
            <div class="col-md-6" id="division-id">
                <label class="control-label" for="academic_level">Group</label>
                <div class="division-checks">
                    @foreach($divisions as $division)
                <input type="checkbox" name="section_divisions[]" value="{{$division->id }}"> {{$division->name}}
                @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-left">Submit</button>
        <a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
    </div>
</form>

<script>
    // $('#division-id').hide();
    //     $('#division-checkbox').click(function () {
    //         if ($(this).is(':checked')) {
    //             // alert('Check');
    //             $('#division-id').show();
    //         } else {
    //             // alert('un Check');
    //             $('#division-id').hide();
    //         }
    //     });

        


</script>
