<form action="{{url('academics/physical/room/update/'.$room->id)}}" method="POST">
    @csrf

    <div class="modal-header">
        <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
                aria-hidden="true">×</span></button>
        <h4 class="modal-title">Edit Room</h4>
    </div>
    <div class="modal-body">
        <div class="row" style="margin: 30px 0">
            <div class="col-sm-3">
                <select name="category_id" id="" class="form-control" required>
                    <option value="">-- Category --</option>
                    @foreach ($roomCategories as $category)
                    <option value="{{$category->id}}" {{($room->category->id == $category->id)? 'selected': ''}}>{{$category->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3">
                <input type="text" name="name" class="form-control" value="{{$room->name}}" required>
            </div>
            <div class="col-sm-1">
                <label for="">FM/HR:</label>
            </div>
            <div class="col-sm-3">
                <select name="employees[]" id="select-employees-edit" class="form-control" multiple required>
                    <option value="">-- FM / HR --</option>
                    @foreach ($employees as $employee)
                    @php
                        $flag = false;
                        foreach($room->employees as $emp){
                            if ($emp->id == $employee->id) {
                                $flag = true;
                            }
                        }
                    @endphp
                    <option value="{{$employee->id}}" {{$flag? 'selected': ''}}>{{$employee->first_name}} {{$employee->last_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    
        <div class="row" style="margin: 30px 0">
            <div class="col-sm-2">
                <label for="">No. of Rows:</label>
                <input type="number" class="form-control edit-val" name="rows" value="{{$room->rows}}" required>
            </div>
            <div class="col-sm-2">
                <label for="">No. of Columns:</label>
                <input type="number" class="form-control edit-val" name="cols" value="{{$room->cols}}" required>
            </div>
            <div class="col-sm-2">
                <label for="">Students Per Seat:</label>
                <input type="number" class="form-control edit-val" name="cadets_per_seat" value="{{$room->cadets_per_seat}}" required>
            </div>
            <div class="col-sm-2">
                <label for="">Total:</label>
                @php
                    $roomTotal = $room->rows * $room->cols * $room->cadets_per_seat;
                @endphp
                <input type="number" class="form-control edit-total" disabled value="{{$roomTotal}}">
            </div>
        </div>                
    </div>
    <div class="modal-footer">
        <button type="submit" class="btn btn-info pull-left">Update</button>
        <a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
    </div>
</form>

<script>
    $(document).ready(function () {
        $('#select-employees-edit').select2();

        $('.edit-val').keyup(function (){
            var values = $('.edit-val');
            var result = 1;
            var hasVal = false;
            values.each(function (index) {
               if ($(this).val()) {
                   hasVal = true;
                   result *= parseInt($(this).val());
               }
            });
            if (hasVal) {
                $('.edit-total').val(result);
            }else{
                $('.edit-total').val(0);
            }
        });
    });
</script>