<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Asssign Class</h4>
</div>
<div class="modal-body">
    <form action="{{url('academics/exam/class/assign/'.$eXamId)}}" method="POST">
        @csrf
        <div class="row">
            <div class="col-md-10">
                <select name="sections[]" id="select-sections" class="form-control" multiple>
                    @foreach ($sections as $section)
                        <option value="{{ $section->id }}"
                                @if($examAssign->classes != null)
                        @foreach($examAssign->classes as $class){{$section->id == $class ? 'selected': ''}}   @endforeach @endif>{{ $section->singleBatch->batch_name }} -
                            {{ $section->section_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-success pull-left">Save</button>
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
