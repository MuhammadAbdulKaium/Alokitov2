<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">Add New Stock Group</h4>
</div>
<div class="modal-body">
    <form action="/inventory/store/stock-group" method="POST">
        @csrf
        
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Group Name:</label>
            </div>
            <div class="col-sm-8">
                <input type="text" class="form-control" name="stock_group_name">
            </div>
        </div>
        <div class="row" style="margin-bottom: 15px">
            <div class="col-sm-4">
                <label for="">Parent Group:</label>
            </div>
            <div class="col-sm-8">
                <select name="parent_group_id" id="" class="form-control" >
                    <option value="0">Select Parent</option>
                    @foreach($stockGroup as $stock)
                        <option value="{{$stock->id}}">{{$stock->stock_group_name}}</option>
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
                    <option value="1">Yes</option>
                    <option value="0" selected>No</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-success pull-right">Add</button>
            </div>
        </div>
    </form>
</div>

