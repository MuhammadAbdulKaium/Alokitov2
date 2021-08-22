<form action="{{ url('/mess/food-menu/assign-item/to/menu/'. $foodMenu->id) }}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">
            <i class="fa fa-info-circle"></i> Assign Menu Items
        </h4>
    </div>
    <!--modal-header-->
    <div class="modal-body">
        <div class="row">
            <div class="col-sm-12">
                <select name="items[]" id="select-items" class="form-control" multiple>
                    @foreach ($foodMenuItems as $foodMenuItem)
                        <option value="{{ $foodMenuItem->id }}" {{ (in_array($foodMenuItem->id, $menuItemIds))?'selected':'' }}>{{ $foodMenuItem->item_name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-top: 15px">
            <div class="col-sm-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>UoM</th>
                            {{-- <th>H. Value</th>
                            <th>Cost</th> --}}
                        </tr>
                    </thead>
                    <tbody id="item-holder">
                        @forelse ($menuItemIds as $key => $itemId)
                            @php
                                $item = $foodMenuItems->firstWhere('id', $itemId);
                                $uom = $uoms->firstWhere('id', $item->uom_id);
                            @endphp
                            <tr>
                                <td>{{ $loop->index+1 }}</td>
                                <td>{{ $item->item_name }}</td>
                                <td><input type="number" name="itemQty[]" class="form-control" value="{{ $menuItemQty[$key] }}" min="1" required></td>
                                <td>{{ $uom->symbol_name }}</td>
                                {{-- <td>{{ $item->value * $menuItemQty[$key] }}</td>
                                <td>{{ $item->cost * $menuItemQty[$key] }}</td> --}}
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align: center">No Item Selected</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <a class="btn btn-default pull-left" data-dismiss="modal">Cancel</a>
        <button type="submit" class="btn btn-info pull-right">Update</button>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#select-items').select2({
            placeholder: "Choose Items",
            allowClear: true
        });

        var foodMenuItems = {!! json_encode($foodMenuItems) !!};
        var uoms = {!! json_encode($uoms) !!};

        $('#select-items').change(function () {
            var itemIds = $(this).val();

            var tr = '';
            var i = 1;

            foodMenuItems.forEach(element => {
                if (itemIds) {
                    var uom_name = '';
                    uoms.forEach(uom => {
                        if (uom.id == element.uom_id) {
                            uom_name = uom.symbol_name;
                        }
                    });
                    if (itemIds.includes(element.id.toString())) {
                        tr += '<tr><td>'+(i++)+'</td><td>'+element.item_name+
                            '</td><td><input type="number" value="1" min="1" name="itemQty[]" class="form-control" required></td><td>'+uom_name+'</td></tr>';
                    }
                }
            });

            if (tr == '') {
                tr = '<tr><td colspan="6" style="text-align: center">No Item Selected</td></tr>';
            }

            $('#item-holder').html(tr);
        });
    });
</script>
