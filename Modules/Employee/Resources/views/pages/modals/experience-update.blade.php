
<form id="employee-update" action="{{ url('/employee/profile/update/experience/'.$experience->id) }}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Update Experience
        </h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-4">
                <label for="">From Date</label>
                <input type="text" name="experience_from_date" value="{{ $experience->experience_from_date }}" class="form-control" id="fromDateEdit" placeholder="From Date">
            </div>
            <div class="col-sm-4">
                <label for="">To Date</label>
                <input type="text" name="experience_to_date" value="{{ $experience->experience_to_date }}" class="form-control" id="toDateEdit" placeholder="To Date">
            </div>
            <div class="col-sm-4">
                <label for="">Last Designation</label>
                <input type="text" name="experience_last_designation" value="{{ $experience->experience_last_designation }}" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label for="">Organization Name</label>
                <input type="text" class="form-control" value="{{ $experience->experience_organization_name }}" name="experience_organization_name">
            </div>
            <div class="col-sm-4">
                <label for="">Organization Address</label>
                <input type="text" class="form-control" value="{{ $experience->experience_organization_address }}" name="experience_organization_address">
            </div>
            <div class="col-sm-4">
                <label for="">Organization Contact Person</label>
                <input type="text" class="form-control" value="{{ $experience->experience_organization_contact_person }}" name="experience_organization_contact_person">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4">
                <label for="">Organization Email</label>
                <input type="email" class="form-control" name="experience_organization_contact_email" value="{{ $experience->experience_organization_contact_email }}">
            </div>
            <div class="col-sm-4">
                <label for="">Organization Contact Number</label>
                <input type="text" class="form-control" name="experience_organization_contact_number" value="{{ $experience->experience_organization_contact_number }}">
            </div>
            <div class="col-sm-4">
                <label for="">Organization Experience Attachment</label>
                <input type="file" class="form-control" name="experience_attachment">
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-info">Update</button>  <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
</form>


<script type="text/javascript">
    $(document).ready(function(){
        $('#fromDateEdit').datepicker();
        $('#toDateEdit').datepicker();
    });
</script>
