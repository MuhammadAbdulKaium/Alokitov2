<form action="{{ url('/mess/food-menu/update/'. $foodMenu->id) }}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Edit Menu
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-6">
                <div class="form-group">
                    <select class="form-control" name="categoryId" id="" required>
                        <option value="">--Select Category--</option>
                        @foreach ($foodMenuCategories as $foodMenuCategory)
                            <option value="{{ $foodMenuCategory->id }}" {{ ($foodMenu->category_id == $foodMenuCategory->id)?'selected':'' }}>{{ $foodMenuCategory->category_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="form-group">
                    <input type="text" name="menuName" value="{{ $foodMenu->menu_name }}" class="form-control" placeholder="Menu Name" required>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-info pull-right">Update</button>
    </div>
</form>

<script>
    
</script>
