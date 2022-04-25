<form action="/academics/update/exam/category/{{$examCategory->id}}" method="POST">
    @csrf
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><i class="fa fa-pencil"></i> Update Category</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-7">
                <label for="">Exam Category Name</label>
                <input type="text" class="form-control" name="exam_category_name" value="{{$examCategory->exam_category_name}}" required>
            </div>
            <div class="col-sm-5">
                <label for="">Alias</label>
                <input type="text" class="form-control" name="alias" value="{{$examCategory->alias}}" required>
            </div>
        </div>

        <div class="row" style="margin-top: 10px">
            <div class="col-sm-6">
                <label for="">Mode</label>
                <select class="form-control" id="edit_cal_mode_field" name="result_cal_mode">
                    <option value="avg">Average</option>
                    <option value="best_count" {{ ($examCategory->best_count != 0)?'selected':'' }}>Best Count</option>
                </select>
            </div>
            <div class="col-sm-4">
                <label for="">Best Count</label>
                <input type="number" class="form-control" id="edit_best_count_field" name="best_count" value="{{ ($examCategory->best_count != 0)?$examCategory->best_count:'' }}" {{ ($examCategory->best_count == 0)?'disabled':'' }}>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-info pull-right">Save</button>
    </div>
</form>

<script>

</script>
