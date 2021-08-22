
<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button">
        <span aria-hidden="true">×</span>
    </button>
    <h4 class="modal-title">
        <i class="fa fa-plus-square"></i> Add Qualification
    </h4>
</div>
<form action="{{url('/employee/profile/store/qualification')}}" method="post" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="employee_id" value="{{$employeeInfo->id}}">

    <div class="modal-body">
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Qualification Type</label>
                <select name="qualification_type" class="form-control">
                    <option value="1">General Qualification</option>
                    <option value="2">Special Qualification</option>
                    <option value="3">Last Academic Qualification</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label for="">Qualification Year</label>
                <input type="year" name="qualification_year" class="form-control">
            </div>
            <div class="col-sm-5">
                <label for="">Qualification Name</label>
                <input type="year" name="qualification_name" class="form-control">
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-8">
                <label for="">Qualification Institute</label>
                <input type="text" name="qualification_institute" class="form-control">
            </div>
            <div class="col-sm-4">
                <label for="">Qualification Marks</label>
                <input type="text" name="qualification_marks" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <label for="">Qualification Institute Address</label>
                <textarea name="qualification_institute_address" class="form-control" rows="1"></textarea>
            </div>
            <div class="col-sm-4">
                <label for="">Qualification Attachment</label>
                <input type="file" name="qualification_attachment" class="form-control">
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-success pull-right">Add</button>
    </div>
</form>

<script type="text/javascript">
    $('#dateOfBirth').datepicker();

</script>