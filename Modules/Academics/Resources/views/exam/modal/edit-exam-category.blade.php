<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Update Category</h4>
</div>
<div class="modal-body">
    <form action="/academics/update/exam/category/{{$examCategory->id}}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-10">
                <div class="col-sm-8">
                    <label for="">Exam Category Name</label>
                    <input type="text" class="form-control" name="exam_category_name" value="{{$examCategory->exam_category_name}}">
                </div>
                <div class="col-sm-4">
                    <button class="btn btn-success" style="margin-top: 23px">Update</button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>

</script>
