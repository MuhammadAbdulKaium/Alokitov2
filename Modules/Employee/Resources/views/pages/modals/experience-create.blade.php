
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-plus-square"></i> Add Experience
    </h4>
</div>
<form action="{{url('/employee/profile/store/experience')}}" method="post" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="employee_id" value="{{$employeeInfo->id}}">

    <div class="modal-body">
        <div class="row">
            <div class="col-sm-4">
                <label for="">From Date</label>
                <input type="text" name="experience_from_date" class="form-control" id="fromDate" placeholder="From Date">
            </div>
            <div class="col-sm-4">
                <label for="">To Date</label>
                <input type="text" name="experience_to_date" class="form-control" id="toDate" placeholder="To Date">
            </div>
            <div class="col-sm-4">
                <label for="">Last Designation</label>
                <input type="text" name="experience_last_designation" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label for="">Organization Name</label>
                <input type="text" class="form-control" name="experience_organization_name">
            </div>
            <div class="col-sm-4">
                <label for="">Organization Address</label>
                <input type="text" class="form-control" name="experience_organization_address">
            </div>
            <div class="col-sm-4">
                <label for="">Organization Contact Person</label>
                <input type="text" class="form-control" name="experience_organization_contact_person">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label for="">Organization Email</label>
                <input type="email" class="form-control" name="experience_organization_contact_email">
            </div>
            <div class="col-sm-4">
                <label for="">Organization Contact Number</label>
                <input type="text" class="form-control" name="experience_organization_contact_number">
            </div>
            <div class="col-sm-4">
                <label for="">Organization Experience Attachment</label>
                <input type="file" class="form-control" name="experience_attachment">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success pull-right">Add</button>
    </div>
</form>

<script type="text/javascript">
    $('#fromDate').datepicker();
    $('#toDate').datepicker();

</script>