<form action="{{url('/mess/store/table')}}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Create Table
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-4">
                <label for="">Table Name</label>
                <input type="text" name="tableName" class="form-control" required>
            </div>
            <div class="col-sm-2">
                <label for="">Total seats</label>
                <input type="number" name="totalSeats" min="4" class="form-control" required>
                <span class="text-warning">Must be even!</span>
            </div>
            <div class="col-sm-2">
                <label for="">High seats</label>
                <input type="number" name="totalHighSeats" min="2" class="form-control" required>
                <span class="text-warning">Must be even!</span>
            </div>
            <div class="col-sm-4">
                <label for="">Concerned HR</label>
                <select name="employeeId" class="form-control" required>
                    <option value="">--Concerned HR--</option>
                    @foreach ($employees as $emoployee)
                        <option value="{{$emoployee->id}}">{{$emoployee->first_name}} {{$emoployee->last_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-left">Submit</button>
        <a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
    </div>
</form>

<script>
    
</script>
