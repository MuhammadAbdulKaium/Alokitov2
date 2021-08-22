<form action="{{url('employee/employee/status/store')}}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                aria-hidden="true">×</span></button>
        <h4 class="box-title"><i class="fa fa-plus-square"></i> Create Employee Status</h4>
    </div>
    <div class="modal-body">

        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label">Employee Status Name</label>
                    <input class="form-control" type="text" name="name" required>
                    <div class="help-block"></div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="form-group">
                    <label class="control-label" for="alias">Category</label>
                    <select id="" class="form-control" name="category" required>
                        <option value="">Select Category</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                        <option value="3">Closed</option>
                    </select>
                    <div class="help-block"></div>
                </div>
            </div>
        </div>
    </div>
    <!--./modal-body-->
    <div class="modal-footer">
        <button type="submit" class="btn btn-success pull-left"></i> Create</button>
        <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
    </div>
    <!--./modal-footer-->
</form>


<script type="text/javascript">

</script>