<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-edit"></i> Edit {{ $room->name }}</h4>
</div>
<div class="modal-body">
    <form action="{{url('/house/update-room/'.$room->id)}}" method="POST">
        @csrf

        <div class="row">
            <div class="col-sm-3">
                <label for="">House:</label>
                <select name="houseId" id="" class="form-control select-house-edit" required>
                    <option value="">--House--</option>
                    @foreach ($houses as $house)
                        <option value="{{$house->id}}" data-floors="{{$house->no_of_floors}}" {{($room->house->id == $house->id)?'selected':''}}>{{$house->name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <label for="">Floor:</label>
                <select name="floor" id="" class="form-control select-floor-edit" required>
                    <option value="">--Floor--</option>
                    @for ($i = 1; $i <= $room->house->no_of_floors; $i++)
                        <option value="{{$i}}" {{($room->floor_no == $i)?'selected':''}}>{{$i}}</option>
                    @endfor
                </select>
            </div>
            <div class="col-sm-5">
                <label for="">Room Name:</label>
                <input type="text" class="form-control" name="name" value="{{$room->name}}" required>
            </div>
            <div class="col-sm-2">
                <label for="">No of Beds:</label>
                <input type="number" class="form-control" min="1" max="999" name="beds" value="{{$room->no_of_beds}}" required>
            </div>
        </div>
        <div class="row" style="margin-top: 10px">
            <div class="col-sm-12">
                <button class="btn btn-success">Update</button>
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function () {
        $('.select-house-edit').change(function () {
            var floors = $(this).find(':selected').data('floors');

            $('.select-floor-edit').empty();
            $('.select-floor-edit').append('<option value="">--Floor--</option>');

            for (let i = 1; i <= floors; i++) {
                $('.select-floor-edit').append('<option value="'+i+'">'+i+'</option>');
            }
        });
    });
</script>