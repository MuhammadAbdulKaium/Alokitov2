<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-edit"></i> Weightage Config</h4>
</div>
<div class="modal-body">
    <div class="row">
        <div class="col-sm-2">
            <select name="" id="academicYear" class="form-control">
                <option value="">Year</option>
                @foreach ($academicYears as $academicYear)
                    <option value="{{$academicYear->id}}">{{$academicYear->year_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-3">
            <select name="" id="semester" class="form-control select-semester">
                <option value="">--Semester--</option>
            </select>
        </div>
        <div class="col-sm-5">
            <select name="" id="type" class="form-control select-type">
                <option value="">--Choose Type--</option>
                <option value="1">Academics - 36</option>
                <option value="2">Extra-Curricular Activities - 33</option>
                <option value="3">Co-Curricular Activities - 31</option>
            </select>
        </div>
        <div class="col-sm-2">
            <button class="btn btn-primary config-btn">Config</button>
        </div>
    </div>

    <div class="row events-holder" style="margin-top: 20px; display: none">
        <div class="col-sm-12">
            <form action="{{url('/house/save-weightage')}}" method="POST" id="weightage-config-form">
                @csrf

                <input type="hidden" name="academicYearId" class="academic-year-field">
                <input type="hidden" name="semesterId" class="semester-field">
                <input type="hidden" name="type" class="type-field">

                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Mark</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="events">
                        
                    </tbody>
                </table>
                <button type="submit" id="weightage-submit-btn" style="display: none"></button>
                <button type="button" class="btn btn-success save-weightage-btn" style="float: right">Save</button>
                <button type="button" class="btn btn-primary add-event-btn" style="float: right; margin-right: 10px">Add</button>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        var selectTxt = '';
        var universalType = null;

        $('#academicYear').change(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/house/get-semester/from-year') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'academicYearId': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">--Semester--</option>';

                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.name+'</option>';
                    });

                    $('#semester').empty();
                    $('#semester').append(txt);
                }
            });
            // Ajax Request End
        });

        $('.config-btn').click(function () {
            var academicYearId = $('#academicYear').val();
            var semesterId = $('#semester').val();
            var type = $('#type').val();
            universalType = type;

            if (academicYearId && semesterId && type) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/house/get-weightage-events/from-type') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'academicYearId': academicYearId,
                        'semesterId': semesterId,
                        'type': type,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        var options = '<option value="">--Choose Event--</option>';

                        // Generate Options
                        if (type == 1) {
                            data[1].forEach(element => {
                                options += '<option value="'+element.id+'">'+element.exam_name+'</option>';
                            });
                        } else if(type == 2 || type == 3){
                            data[1].forEach(element => {
                                options += '<option value="'+element.id+'">'+element.category_name+'</option>';
                            });
                        }

                        selectTxt = '<select name="events[]" class="form-control" required>'+options+'</select>';
                        $('.events-holder').css('display', 'block');
                        $('.events').empty();

                        // Generate Events For previous weightage
                        data[0].forEach(weightage => {
                            var preSelectOptions = '';

                            if (type == 1) {
                                data[1].forEach(element => {
                                    var select = '';
                                    if (weightage.exam_id == element.id) {
                                        select = 'selected';
                                    }
                                    preSelectOptions += '<option value="'+element.id+'" '+select+'>'+element.exam_name+'</option>';
                                });
                            } else if(type == 2 || type == 3){
                                data[1].forEach(element => {
                                    var select = '';
                                    if (weightage.performance_cat_id == element.id) {
                                        select = 'selected';
                                    }
                                    preSelectOptions += '<option value="'+element.id+'" '+select+'>'+element.category_name+'</option>';
                                });
                            }

                            $('.events').append('<tr><td><select name="events[]" class="form-control" required>'+preSelectOptions+
                            '</select></td><td><input name="marks[]" type="number" class="form-control" value="'+weightage.mark+
                            '" required></td><td><button type="button" class="btn btn-danger delete-weightage-btn" data-weightage-id="'+weightage.id+'"><i class="fa fa-trash"></i></button></td></tr>');
                        });

                        $('.academic-year-field').val(academicYearId);
                        $('.semester-field').val(semesterId);
                        $('.type-field').val(type);
                    }
                });
                // Ajax Request End
            }else{
                swal('Error!', 'Fill up all the fields first.', 'error');
            }
        });

        $('.add-event-btn').click(function () {
           if (selectTxt) {
                var tr = '<tr><td>'+selectTxt+'</td><td><input name="marks[]" type="number" class="form-control" required></td><td><button type="button" class="btn btn-danger delete-weightage-btn" data-weightage-id=""><i class="fa fa-trash"></i></button></td></tr>';
                $('.events').append(tr);
           }
        });

        $('.save-weightage-btn').click(function () {
            var form = $('form#weightage-config-form');
            var eventIds = $("select[name='events[]']")
              .map(function(){return $(this).val();}).get();
            var findDuplicates = arr => arr.filter((item, index) => arr.indexOf(item) != index);
            var marks = $("input[name='marks[]']");
            var exceptedMark = null;

            var totalMark = 0;
            marks.each((index, value) => {
                if ($(value).val()) {
                    totalMark += parseInt($(value).val());
                }
            });

            if (universalType == 1) {
                exceptedMark = 36;
            }else if (universalType == 2) {
                exceptedMark = 33;
            }else if (universalType == 3) {
                exceptedMark = 31;
            }

            if (findDuplicates(eventIds).length > 0) {
                swal('Error!', 'Can not config duplicate Events.', 'error');
            }else if(totalMark != exceptedMark){
                swal('Error!', 'Total Event mark does not match with weightage mark! Match the Total Mark.', 'error');
            }else{
                $('#weightage-submit-btn').trigger('click');              
            }
        });

        $(document).on('click', '.delete-weightage-btn', function () {
            var tr = $(this).parent().parent();
            var weightageId = $(this).data('weightage-id');

            if (weightageId) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/house/delete-weightage') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'weightageId': weightageId,
                    }, //see the _token
                    datatype: 'application/json',
                
                    beforeSend: function () {},
                
                    success: function (data) {
                        if (data) {
                            tr.remove();
                        }
                    }
                });
                // Ajax Request End
            }else{
                $(this).parent().parent().remove();
            }
        });
    });
</script>