<form action="{{ url('/mess/food-menu/item/update/'. $foodMenuItem->id) }}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Edit Menu Item
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="text" name="itemName" value="{{ $foodMenuItem->item_name }}" class="form-control" placeholder="Menu Item" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <select class="form-control" name="uomId" id="" required>
                        <option value="">--UoM--</option>
                        @foreach ($uoms as $uom)
                            <option value="{{ $uom->id }}" {{ ($foodMenuItem->uom_id == $uom->id)?'selected':'' }}>{{ $uom->formal_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="number" name="value" value="{{ $foodMenuItem->value }}" class="form-control" placeholder="Value" required>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="number" name="cost" value="{{ $foodMenuItem->cost }}" class="form-control" placeholder="Costing" required>
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
