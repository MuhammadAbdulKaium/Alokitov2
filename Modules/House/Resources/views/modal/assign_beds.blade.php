<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-bed"></i> Assign Beds</h4>
</div>
<div class="modal-body">
    <form class="assign-form">
        <h4>Assign Student to Bed <b class="heading-bed-no"></b></h4>
        <input name="bed" class="bed-field" type="hidden">
        <div class="row">
            <div class="col-sm-3">
                <select name="" id="" class="form-control select-academic-level">
                    <option value="">--Academic Level--</option>
                    @foreach ($academicLevels as $academicLevel)
                        <option value="{{$academicLevel->id}}">{{$academicLevel->level_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-4">
                <select name="" id="" class="form-control select-class">
                    <option value="">--Classes with Form--</option>
                </select>
            </div>
            <div class="col-sm-3">
                <select name="" id="" class="form-control select-cadet">
                    <option value="">--Student--</option>
                </select>
            </div>
            <div class="col-sm-2">
                <button type="button" class="btn btn-primary assign-btn">Assign</button>
            </div>
        </div>
    </form>
    <div class="bed-container">
        @for ($i = 1; $i <= $room->no_of_beds; $i++)
            @php
                $previousBed = null;
                foreach ($roomStudents as $roomStudent) {
                    if ($roomStudent->bed_no == $i) {
                        $previousBed = $roomStudent;
                    }
                }                
            @endphp
            <div data-bed-no="{{ $i }}">
                <div>Bed <b class="bed-no">{{ $i }}</b></div>
                <div class="bed-std-info">
                    @if ($previousBed)
                        <div>{{ $previousBed->student->first_name }} {{ $previousBed->student->last_name }}</div>
                        <div>{{ $previousBed->student->singleBatch->batch_name }}</div>
                        <div>Section: {{ $previousBed->student->singleSection->section_name }}</div>
                        <div>Roll: {{ $previousBed->student->gr_no }}</div>
                    @else
                        <div style="line-height: 80px">Blank</div>
                    @endif
                </div>
                <div>
                    <button class="btn btn-success btn-xs bed-add-btn" data-bed="{{ $i }}">
                        <i class="fa fa-{{ ($previousBed)?'edit':'plus-square' }}"></i>
                    </button>
                    <button class="btn btn-danger btn-xs bed-remove-btn" data-bed="{{ $i }}" style="display: {{ ($previousBed)?'inline-block':'none' }}"><i class="fa fa-trash"></i></button>
                </div>
            </div>
        @endfor
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.bed-add-btn').click(function () {
            var bedNo = $(this).data('bed');
            $('.bed-field').val(bedNo);
            $('.select-academic-level').val('');
            $('.select-class').html('<option value="">--Classes with Form--</option>');
            $('.select-cadet').html('<option value="">--Student--</option>');

            $('.assign-form').css('display', 'block');
            $('.heading-bed-no').text(bedNo);
        });

        $('.bed-remove-btn').click(function () {
            var bedNo = $(this).data('bed');

            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/remove-student/from-bed') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'roomId': {!! $room->id !!},
                    'bedNo': bedNo
                }, //see the $_token
                datatype: 'application/json',

                success: function (data) {
                    if (data == 1) {
                        var stdInfoContainer = $(".bed-container").find("[data-bed-no='" + bedNo + "']").find('.bed-std-info');
                        var bedAddBtn = $(".bed-container").find("[data-bed-no='" + bedNo + "']").find('.bed-add-btn');
                        var bedRmvBtn = $(".bed-container").find("[data-bed-no='" + bedNo + "']").find('.bed-remove-btn');
                        
                        stdInfoContainer.html('<div style="line-height: 80px">Blank</div>');
                        bedAddBtn.html('<i class="fa fa-plus-square"></i>');
                        bedRmvBtn.css('display', 'none');
                    }else if(data == 2){
                        swal('Error', "This student is house prefect can not remove.", 'error');
                    }                    
                },

                error: function (error) {}
            });
        });

        $('.assign-btn').click(function () {
            var bedNo = $('.bed-field').val();
            var stdInfoContainer = $(".bed-container").find("[data-bed-no='" + bedNo + "']").find('.bed-std-info');
            var bedAddBtn = $(".bed-container").find("[data-bed-no='" + bedNo + "']").find('.bed-add-btn');
            var bedRmvBtn = $(".bed-container").find("[data-bed-no='" + bedNo + "']").find('.bed-remove-btn');
            var datas = {
                '_token': "{{ csrf_token() }}",
                houseId: {!! $room->house_id !!},
                roomId: {!! $room->id !!},
                floorNo: {!! $room->floor_no !!},
                sectionId: $('.select-class').val(),
                studentId: $('.select-cadet').val(),
                bedNo: bedNo,
            };

            if (datas.sectionId && datas.studentId && datas.bedNo) {
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/house/assign-student/to-bed') }}",
                    type: 'GET',
                    cache: false,
                    data: datas,
                    datatype: 'application/json',

                    success: function (data) {
                        console.log(data);

                        if (data == 2) {
                            swal('Error!', 'This student is House prefect can not change', 'error');
                        } else if (data) {
                            text = '<div>'+data.student.first_name+' '+data.student.last_name+
                                '</div><div>'+data.student.single_batch.batch_name+
                                    '</div><div>Section: '+data.student.single_section.section_name+
                                        '</div><div>Roll: '+data.student.gr_no+'</div>';
                            stdInfoContainer.empty();
                            stdInfoContainer.append(text);
                            bedAddBtn.html('<i class="fa fa-edit"></i>');
                            bedRmvBtn.css('display', 'inline-block');
                        } else{
                            stdInfoContainer.html('<div style="line-height: 80px">Blank</div>');
                            swal('Error!', 'Error assigning Student.', 'error');
                        }   
                        
                        $('.assign-form').css('display', 'none');
                    },

                    error: function (error) {
                        console.log(error);
                    }
                });
            } else{
                alert("Please Fill Up all the fields first!");
            }
        });

        $('.select-academic-level').change(function () {
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/find-sections/from-academic-level') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'academicLevelId': $(this).val()
                }, //see the $_token
                datatype: 'application/json',

                success: function (data) {
                    var txt = '<option>--Classes with Form--</option>';
                    data.forEach(element => {
                        txt += '<option value="' + element.id + '">' + element
                            .single_batch.batch_name + ' - '+element.section_name+'</option>';
                    });

                    $('.select-class').empty();
                    $('.select-class').append(txt);
                    $('.select-cadet').html('<option>--Student--</option>');
                },

                error: function (error) {}
            });
        });
        
        $('.select-class').change(function () {
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/find-students/from-section') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'sectionId': $(this).val()
                }, //see the $_token
                datatype: 'application/json',

                success: function (data) {
                    var txt = '<option value="">--Student--</option>';
                    data.forEach(element => {
                        txt += '<option value="' + element.std_id + '">' + element
                            .first_name + ' '+element.last_name+'</option>';
                    });

                    $('.select-cadet').empty();
                    $('.select-cadet').append(txt);
                },

                error: function (error) {}
            });
        });
    });
</script>