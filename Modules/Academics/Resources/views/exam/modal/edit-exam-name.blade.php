<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Update Exam Name</h4>
</div>
<div class="modal-body">
    <form action="/academics/update/exam/name/{{$examName->id}}" method="post">
        @csrf
        <div class="row">
            <div class="col-sm-3">
                <label for="">Exam Name</label>
                <input type="text" class="form-control" name="exam_name" value="{{$examName->exam_name}}">
            </div>
            <div class="col-sm-3">
                <label for="">Exam Category</label>
                <select class="form-control" name="exam_category_id">
                    <option value="">Select Category</option>
                    @foreach($category as $cat)
                        <option value="{{$cat->id}}" {{$cat->id == $examName->exam_category_id ? 'selected': ''}}>{{$cat->exam_category_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <label for="">Term</label>
                <select class="form-control" name="term_id">
                    <option value="">Select Term</option>
                    @foreach($semester as $sem)
                        <option value="{{$sem->id}}" {{$sem->id == $examName->term_id ? 'selected': ''}}>{{$sem->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2" style="margin-top: 20px">
                <input type="checkbox" name="effective_on" {{1 == $examName->effective_on ? 'checked==checked': ''}}> Effective
            </div>
            <div class="col-sm-2">
                <button class="btn btn-success" style="margin-top: 23px">Save</button>
            </div>
        </div>
    </form>
</div>
<script>

</script>
