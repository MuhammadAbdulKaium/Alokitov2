<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">#{{ $room->id }} {{ $room->name }}</h4>
</div>
<div class="modal-body">
    <form action="{{url('academics/physical/room/allocate_students/'.$room->id)}}" method="POST">
        @csrf
        <div class="row" style="margin: 30px 0">
            <div class="col-sm-5">
                <label for="">Classes With Forms</label>
                <select name="sections[]" id="select-sections" class="form-control" multiple>
                    @foreach ($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->singleBatch->batch_name }} -
                        {{ $section->section_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-12">
                <label for="">Students</label>
                <select name="cadets[]" id="select-cadets" class="form-control" multiple>
                    <option value="">-- Select --</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-10"></div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-success pull-left">Save</button>
            </div>
        </div>
    </form>

    <div style="margin-top: 40px">
        <h3 class="text-primary">Allocation History</h3>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">Allocation ID</th>
                    <th scope="col">Classes with Forms</th>
                    <th scope="col">No Of Students</th>
                    <th scope="col">Created By</th>
                    <th scope="col">Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allocations as $allocation)
                <tr>
                    <th scope="row">{{$allocation->id}}</th>
                    <td>
                        @foreach ($allocation->sections() as $section)
                            <span class="badge">{{$section->singleBatch->batch_name}} - {{$section->section_name}}</span>
                        @endforeach
                    </td>
                    <td>{{$allocation->studentsNo()}}</td>
                    <td>{{$allocation->user->name}}</td>
                    <td>{{$allocation->created_at->format('d/m/Y')}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="modal-footer">
    <a class="btn btn-default pull-right" data-dismiss="modal">Cancel</a>
</div>

<script>
    $(document).ready(function () {
        $('#select-sections').select2();
        $('#select-cadets').select2();

        $(document).on('change', '#select-sections', function () {
            $('#select-cadets').val(null).trigger('change');

            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/physical/room/search/students') }}",
                type: 'POST',
                cache: false,
                data: {
                    '_token': $_token,
                    'sections': $(this).val()
                }, //see the $_token
                datatype: 'application/json',

                success: function (data) {
                    var txt = '';
                    data.forEach(element => {
                        txt += '<option value="' + element.std_id + '">' + element
                            .first_name + ' ' + element.last_name + '</option>';
                    });

                    $('#select-cadets').empty();
                    $('#select-cadets').append(txt);
                },

                error: function (error) {}
            });
        });
    });
</script>