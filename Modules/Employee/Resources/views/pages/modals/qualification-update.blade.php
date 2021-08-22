
<form id="employee-update" action="{{ url('/employee/profile/update/qualification/'.$qualification->id) }}" method="POST">
    <input type="hidden" name="_token" value="{{csrf_token()}}">
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Update Qualification
        </h4>
    </div>
    <div class="modal-body">
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Qualification Type</label>
                <select name="qualification_type" class="form-control">
                    <option value="1" @if($qualification->qualification_type == 1) selected @endif>General Qualification</option>
                    <option value="2" @if($qualification->qualification_type == 2) selected @endif>Special Qualification</option>
                    <option value="3" @if($qualification->qualification_type == 3) selected @endif>Last Academic Qualification</option>
                </select>
            </div>
            <div class="col-sm-3">
                <label for="">Qualification Year</label>
                <input type="year" name="qualification_year" value="{{ $qualification->qualification_year }}" class="form-control">
            </div>
            <div class="col-sm-5">
                <label for="">Qualification Name</label>
                <input type="text" name="qualification_name" value="{{ $qualification->qualification_name }}" class="form-control">
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-8">
                <label for="">Qualification Institute</label>
                <input type="text" name="qualification_institute" value="{{ $qualification->qualification_institute }}" class="form-control">
            </div>
            <div class="col-sm-4">
                <label for="">Qualification Marks</label>
                <input type="text" name="qualification_marks" value="{{ $qualification->qualification_marks }}" class="form-control">
            </div>
        </div>
        <div class="row">
            <div class="col-sm-8">
                <label for="">Qualification Institute Address</label>
                <textarea name="qualification_institute_address" class="form-control" rows="1">{{ $qualification->qualification_institute_address }}</textarea>
            </div>
            <div class="col-sm-4">
                <label for="">Qualification Attachment</label>
                <input type="file" name="qualification_attachment" class="form-control">
            </div>
        </div>
    </div>
    <div class="box-footer">
        <button type="submit" class="btn btn-info">Update</button>  <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
</form>


<script type="text/javascript">
    $(document).ready(function(){

    });
</script>
