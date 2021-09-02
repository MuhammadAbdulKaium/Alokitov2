<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">#{{ $room->id }} - {{ $room->name }}</h4>
</div>
<div class="modal-body">
    <form action="{{ url('/student/update/club/students') }}" method="POST">
        @csrf

        <input type="hidden" name="roomId" value="{{ $room->id }}">
        <input type="hidden" name="allocationId" value="{{ ($allocation)?$allocation->id:'' }}">

        <div class="row">
            <div class="col-sm-4">
                <label for="">Classes With Forms</label>
                <select name="sections[]" id="select-sections" class="form-control" multiple>
                    @foreach ($sections as $section)
                        @php
                            $flag = false;
                        @endphp
                        @if ($allocation)    
                        @foreach ($allocation->sections() as $sec)
                            @php
                                if ($sec->id == $section->id) {
                                    $flag = true;
                                }
                            @endphp
                        @endforeach
                        @endif
                        <option value="{{ $section->id }}" {{ ($flag)?'selected':'' }}>{{ $section->singleBatch->batch_name }} -
                            {{ $section->section_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-8">
                <label for="">Students</label>
                <select name="cadets[]" id="select-cadets" class="form-control" multiple>
                    <option value="">-- Select --</option>
                    @foreach ($students as $student)
                        @php
                            $flag = false;
                        @endphp
                        @if ($room->students())    
                        @foreach ($room->students() as $stu)
                            @php
                                if ($stu->std_id == $student->std_id) {
                                    $flag = true;
                                }
                            @endphp
                        @endforeach
                        @endif
                        <option value="{{ $student->std_id }}" {{ ($flag)?'selected':'' }}>{{$student->first_name}} {{$student->last_name}}</option>
                    @endforeach
                </select>
                <button class="btn btn-success" style="float: right; margin-top: 20px">Update</button>
            </div>
        </div>
    </form>
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