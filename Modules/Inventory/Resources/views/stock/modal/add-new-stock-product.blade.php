<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Stock Item New</h4>
</div>
<div class="modal-body">
    <form action="/inventory/store/stock-product" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Name <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="product_name" placeholder="Name" maxlength="255" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Description <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="product_description" placeholder="Description" maxlength="255" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Unit <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="unit" class="form-control" required>
                            @foreach($uoms as $uom)
                                <option value="{{$uom->id}}">{{$uom->symbol_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Group <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="stock_group" class="form-control" required>
                            @foreach($stockGroup as $stock)
                                <option value="{{$stock->id}}">{{$stock->stock_group_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Min Stock <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="min_stock" placeholder="Min Stock" min="0" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Item Type <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="item_type" class="form-control" required>
                            <option value="1">Inventory Item</option>
                            <option value="0">Non-Inventory Item</option>
                        </select>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Has fraction Qty <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="has_fraction" id="has_fraction" class="form-control" required>
                            <option value="0" selected>No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="has_fraction" style="display: none">
                    <div class="row" style="margin-bottom: 15px">
                        <div class="col-sm-4">
                            <label for="">Decimal Places <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-8">
                            <select name="decimal_point_place" class="form-control" >
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 15px">
                        <div class="col-sm-4">
                            <label for="">Round of <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" name="round_of" min="1" placeholder="Round of">
                        </div>
                    </div>
                </div>

                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Use Serial <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="use_serial" class="form-control" id="serial" required>
                            <option value="0" selected>No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>
                <div class="has_serial" style="display: none">
                    <div class="row" style="margin-bottom: 15px">
                        <div class="col-sm-4">
                            <label for="">Numeric part <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-8">
                            <select name="numeric_part" class="form-control" >
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                            </select>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 15px">
                        <div class="col-sm-4">
                            <label for="">Prefix <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="prefix">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px">
                        <div class="col-sm-4">
                            <label for="">Suffix</label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="suffix">
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px">
                        <div class="col-sm-4">
                            <label for="">Separator <span class="text-danger">*</span></label>
                        </div>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="separator_symbol">
                        </div>
                    </div>
                </div>


            </div>
            <div class="col-md-6">
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Alias <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="alias" placeholder="alias" maxlength="100" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">SKU <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="sku" placeholder="SKU" maxlength="100" required>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Type <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="code_type_id" id="" class="form-control" required>
                            <option value="1">General Goods</option>
                            <option value="2">Finished Goods</option>
                            <option value="3">Pharmacy  Goods</option>
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Category <span class="text-danger">*</span></label>
                    </div>
                    <div class="col-sm-8">
                        <select name="category_id" id="" class="form-control" required>
                            @foreach($category as $cat)
                                <option value="{{$cat->id}}">{{$cat->stock_category_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Warranty Month:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="warrenty_month" placeholder="Warranty month" min="0"> 
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Reorder Qty:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" name="reorder_qty" min="0" placeholder="Reorder Qty">
                    </div>
                </div>
                <div class="row" style="margin-bottom: 15px">
                    <div class="col-sm-4">
                        <label for="">Additional Remarks:</label>
                    </div>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" name="additional_remarks" maxlength="155" placeholder="Additional Remarks">
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleFormControlFile1">Images</label>
                    <input type="file" class="form-control-file" name="image">
                </div>
            </div>
            <div class="col-md-8">
                <label for="exampleFormControlFile1">Store Tagging <span class="text-danger">*</span></label>
                @foreach($storeList as $store)
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" value="{{$store->id}}" id="defaultCheck1_{{$store->id}}" name="store_tagging[]">
                    <label class="form-check-label" for="defaultCheck1_{{$store->id}}">
                        {{$store->store_name}}
                    </label>
                </div>
                @endforeach
            </div>
            <div class="col-sm-12">
                <button class="btn btn-success pull-right">Add</button>
            </div>
        </div>
    </form>
    <!-- <div class="row justify-content-md-center" >
        <div class="col-md-10">
            <h4>History</h4>
            <table class="table">
                <thead class="thead-dark">
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Date & Time</th>
                    <th scope="col">Create/ Update/ Delete</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td>Otto</td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td>Thornton</td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>Larry</td>
                    <td>the Bird</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div> -->
</div>

<script>
    $(document).ready(function () {
        $('#has_fraction').change(function(){
            var has_fraction = $(this).val();
            if(has_fraction==1){
                $('.has_fraction').show();
                $('#serial').val(0);
                $('.has_serial').hide();
            }else{
                $('.has_fraction').hide();
            }
        });
        $('#serial').change(function () {
            let count = $(this).val();
            if(count==1){
                var has_fraction = $('#has_fraction').val();
                if(has_fraction==1){
                    alert('you can not use serial in franction qty');
                    $('#serial').val(0);
                }else{
                   $('.has_serial').show();  
                }
            }else{
                $('.has_serial').hide();
            }

        })
    })
    
</script>