<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Add New Stock Group</h4>
</div>
<div class="modal-body">
    <form action="/inventory/update/stock-group/{{$stockGroupDeatils->id}}" method="POST">
        @csrf
        
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Group Name:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="stock_group_name" value="{{$stockGroupDeatils->stock_group_name}}">
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Parent Group:</label>
            </div>
            <div class="col-sm-8">
                <select name="parent_group_id" class="form-control" >
                    <option value="0">---</option>
                    @foreach($stockGroup as $stock)
                        <option value="{{$stock->id}}" {{$stock->id==$stockGroupDeatils->parent_group_id?"selected":''}}>{{$stock->stock_group_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Has Child:</label>
            </div>
            <div class="col-sm-8">
                <select name="has_child" id="" class="form-control" >
                    <option value="1" <?php if($stockGroupDeatils->has_child==1) echo 'selected'; ?>>Yes</option>
                    <option value="0" <?php if($stockGroupDeatils->has_child==0) echo 'selected'; ?>>No</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-success pull-right">Update</button>
            </div>
        </div>
    </form>
</div>

<script>
    
</script>