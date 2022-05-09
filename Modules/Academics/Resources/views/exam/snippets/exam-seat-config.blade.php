@php
    if($previousSeatPlan){
        $previousBatchIds = json_decode($previousSeatPlan->batch_ids);
        $previousSectionIds = json_decode($previousSeatPlan->section_ids);
        $previousRoomIds = json_decode($previousSeatPlan->physical_room_ids);
        $batchWithSubjects = json_decode($previousSeatPlan->batch_with_subjects, 1);
    }else{
        $previousBatchIds = null;
        $previousSectionIds = null;
        $previousRoomIds = null;
        $batchWithSubjects = null;
    }
@endphp
<div class="col-sm-6 check1-wrap">
    @foreach ($batches as $batch)
        @php
            $batchSubjectId = null;
            $batchCriteriaId = null;

            if ($batchWithSubjects) {
                foreach($batchWithSubjects as $batchWithSubject){
                    if ($batchWithSubject['batchId'] == $batch->id) {
                        $batchSubjectId = $batchWithSubject['subjectId'];
                        $batchCriteriaId = $batchWithSubject['criteriaId'];
                    }
                }
            }
        @endphp
        <div class="row batch-check-list">
            <div class="col-sm-2">
                <div class="form-group">
                    <input type="checkbox" value="{{ $batch->id }}" class="check1 class" {{ ($previousBatchIds)?(in_array($batch->id, $previousBatchIds))?'checked':'':'' }}>
                    <span for="">{{ $batch->batch_name }}</span>
                </div>
            </div>
            <div class="col-sm-4">
                @foreach ($batch->section() as $section)
                    @php
                        $noOfStds = $students->where('section', $section->id)->count();
                    @endphp
                    <div class="form-group" style="float: left; margin-right: 15px">
                        <input type="checkbox" class="check1 form" value="{{$section->id}}" data-no-of-stds="{{$noOfStds}}" {{ ($previousSectionIds)?(in_array($section->id, $previousSectionIds))?'checked':'':'' }}>
                        <span for="">{{ $section->section_name }}({{$noOfStds}})</span>
                    </div>
                @endforeach
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <span for="">Total: <b class="total">0</b></span>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <select name="" id="" class="form-control subject" data-batch-id="{{$batch->id}}">
                        <option value="">Subject</option>
                        @php
                            $batchWiseExamSchedules = $examSchedules->where('batch_id', $batch->id);
                        @endphp
                        @foreach ($batchWiseExamSchedules as $examSchedule)
                            @php
                                if ($batchSubjectId == $examSchedule->subject->id) {
                                    $schedules = json_decode($examSchedule->schedules, 1);
                                    $criteriaIds = [];
                                    foreach ($schedules as $key => $schedule) {
                                        array_push($criteriaIds, $key);
                                    }
                                }
                            @endphp
                            <option value="{{$examSchedule->subject->id}}" @if ($batchSubjectId == $examSchedule->subject->id) selected @endif>{{$examSchedule->subject->subject_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <select name="" id="" class="form-control criteria">
                        <option value="">Criteria</option>
                        @isset($criteriaIds)
                            @foreach ($criteriaIds as $criteriaId)
                                <option value="{{ $criteriaId }}" @if ($batchCriteriaId == $criteriaId) selected @endif>@isset($criterias[$criteriaId]){{ $criterias[$criteriaId]->name }}@endisset</option>
                            @endforeach
                        @endisset
                    </select>
                </div>
            </div>
        </div>
    @endforeach
    <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-3">
            <label for="">Total: <span class="grand-total">0</span></label>
        </div>
    </div>
</div>
<div class="col-sm-6 check2-wrap">
    @foreach ($physicalRooms as $physicalRoom)
        @php
            $totalSeats = $physicalRoom->rows * $physicalRoom->cols * $physicalRoom->cadets_per_seat;
        @endphp
        <div class="row">
            <input type="hidden" class="available-seats" value="{{$totalSeats}}">
            <div class="col-sm-4">
                <div class="form-group">
                    <input type="checkbox" class="check2 room" value="{{ $physicalRoom->id }}" data-next-seat-blank="0" data-no-same-class="0" {{ ($previousRoomIds)?(in_array($physicalRoom->id, $previousRoomIds))?'checked':'':'' }}>
                    <span>{{ $physicalRoom->name }} (<span class="seat-row">{{ $physicalRoom->rows }}</span>*<span class="seat-col">{{ $physicalRoom->cols }}</span>*<span class="stu-per-seat">{{ $physicalRoom->cadets_per_seat }}</span> = <span class="total-seat">{{ $totalSeats }}</span>)</span>
                </div>
            </div>
            <div class="col-sm-2">
                <div class="form-group">
                    <span>Per Seat: <b>{{ $physicalRoom->cadets_per_seat }}</b></span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="checkbox" class="next-seat-blank-checkbox" value="{{ $totalSeats/2 }}">
                    <span>Next Seat: Blank</span>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="form-group">
                    <input type="checkbox" class="no-same-class-checkbox">
                    <span>No Same Class Adjacent</span>
                </div>
            </div>
        </div>
    @endforeach

    <div class="row">
        <div class="col-sm-6"></div>
        <div class="col-sm-6">
            <label for="" style="display: block; margin-bottom: 20px">Seats Occupied: <b class="check2-total">0</b></label>
            <button class="btn btn-success generate-seat">Generate Seats</button>
        </div>
    </div>
</div>


<script>
        // Check Box 1
        function check1GrandTotal(){
            var allTotal = $('.check1-wrap').find('.total');

            var grandTotal = 0;

            // console.log(allTotal);

            allTotal.each(function (index){
                grandTotal += parseInt($(this).text());
            });

            $('.check1-wrap').find('.grand-total').text(grandTotal);
        }

        function check1Total(parent) {
            var classDiv = parent.find('.class');
            var forms = parent.find('.form');
            var total = parent.find('.total');
            var totalVal = 0;

            forms.each((index, value) => {
                if ($(value).is(':checked')) {
                    totalVal += parseInt($(value).data('no-of-stds'));
                }
            });

            if (classDiv.is(':checked')) {
                total.text(totalVal);
            }else{
                total.text(0);
            }
        }

        $('.check1').click(function () {
            var parent = $(this).parent().parent().parent();

            check1Total(parent);
            check1GrandTotal();
        });

        $('.subject').change(function () {
            var examId = {!! json_encode($examName->id) !!};
            var date = {!! json_encode($date) !!};
            var fromTime = {!! json_encode($fromTime) !!};
            var toTime = {!! json_encode($toTime) !!};
            var batchId = $(this).data('batch-id');

            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/schedule/wise/criteria/from/subject') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'examId': examId,
                    'batchId': batchId,
                    'subjectId': $(this).val(),
                    'date': date,
                    'fromTime': fromTime,
                    'toTime': toTime,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    console.log(data);
                    var txt = '<option value="">Criteria</option>';

                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.name+'</option>';
                    });

                    $('.criteria').html(txt);
                }
            });
            // Ajax Request End
        });


        function check2Total() {
            var allCheck = $('.check2-wrap').find('.room');

            var total = 0;

            allCheck.each(function (index){
                if ($(this).is(':checked')) {
                    total += parseInt($(this).next().find('.total-seat').text());
                }
            });

            $('.check2-total').text(total);
        }

        $('.check2').click(function () {
            check2Total();
        });

        $('.next-seat-blank-checkbox').click(function () {
            var parent = $(this).parent().parent().parent();
            var room = parent.find('.room');

            if ($(this).is(':checked')) {
                room.data('next-seat-blank', 1);
            } else{
                room.data('next-seat-blank', 0);
            }

            parent.find('.available-seats').val($(this).val());
        });

        $('.no-same-class-checkbox').click(function () {
            var parent = $(this).parent().parent().parent();
            var room = parent.find('.room');

            if ($(this).is(':checked')) {
                room.data('no-same-class', 1);
            } else{
                room.data('no-same-class', 0);
            }
        });


        // Automatically run functions
        var batchCheckLists = $('.batch-check-list');
        batchCheckLists.each((index, element) => {
            check1Total($(element));
        });
        check1GrandTotal();
        check2Total();
</script>