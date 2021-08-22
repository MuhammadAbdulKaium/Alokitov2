<form action="{{url('/student/update/task/schedule/date/'.$taskScheduleDate->id)}}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title"><i class="fa fa-pencil"></i> <b>Edit Schedule Date</b></h4>
    </div>

    <div class="modal-body">
        <div class="row">
            <div class="col-sm-4">
                <input type="text" name="name" class="form-control" placeholder="Name" value="{{ $taskScheduleDate->name }}" required>
            </div>
            <div class="col-sm-4">
                <input type="text" id="fromDateEdit" class="form-control hasDatepicker from-date" name="fromDate" maxlength="10"
                placeholder="From Date" aria-required="true" size="10" value="{{ $taskScheduleDate->start_date }}" required>
            </div>
            <div class="col-sm-4">
                <input type="text" id="expectedDateEdit" class="form-control hasDatepicker expected-date" name="expectedDate" maxlength="10"
                placeholder="Expected Date" aria-required="true" size="10" value="{{ $taskScheduleDate->expected_date }}" required>
            </div>
        </div>
    </div>
    <!--./body-->

    <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-left">Update</button>
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
</form>

<script>
    $('#fromDateEdit').datepicker();
    $('#expectedDateEdit').datepicker();   
    
    $(".hasDatepicker").each(function() {    
        $(this).datepicker('setDate', $(this).val());
    });
</script>