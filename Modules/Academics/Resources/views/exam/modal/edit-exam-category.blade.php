<form action="/academics/update/exam/category/{{$examCategory->id}}" method="POST">
    @csrf
    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title"><i class="fa fa-pencil"></i> Update Category</h4>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <label for="">Exam Category Name</label>
                <input type="text" class="form-control" name="exam_category_name" value="{{$examCategory->exam_category_name}}" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <label for="">Alias</label>
                <input type="text" class="form-control" name="alias" value="{{$examCategory->alias}}" required>
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
