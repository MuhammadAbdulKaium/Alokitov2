@extends('layouts.master')


@section('content')
<div class="content-wrapper">
    <link href="{{ asset('css/datatable.css') }}" rel="stylesheet" type="text/css" />
    <section class="content-header">
        <h1>
            <i class="fa fa-th-list"></i> Exam |<small>Seat Plan</small>
        </h1>
        <ul class="breadcrumb">
            <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
            <li><a href="/academics/default/index">Academics</a></li>
            <li>SOP Setup</li>
            <li>Exam</li>
            <li class="active">Exam Seat Plan</li>
        </ul>
    </section>
    <section class="content">
        @if(Session::has('message'))
            <p class="alert alert-success alert-auto-hide" style="text-align: center"> <a href="#" class="close"
                style="text-decoration:none" data-dismiss="alert"
                aria-label="close">&times;</a>{{ Session::get('message') }}</p>
        @elseif(Session::has('alert'))
            <p class="alert alert-warning alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('alert') }}</p>
        @elseif(Session::has('errorMessage'))
            <p class="alert alert-danger alert-auto-hide" style="text-align: center">  <a href="#" class="close" style="text-decoration:none" data-dismiss="alert" aria-label="close">&times;</a>{{ Session::get('errorMessage') }}</p>
        @endif
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title" style="line-height: 40px"><i class="fa fa-tasks"></i> Exam Seat Plan </h3>
            </div>
            <div class="box-body">
                <div class="row">
                    <div class="col-sm-1">
                        <label for="">Year*</label>
                        <select name="" id="select-year" class="form-control">
                            <option value="">--Select--</option>
                            @foreach ($academicYears as $academicYear)
                                <option value="{{ $academicYear->id }}">{{ $academicYear->year_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <label for="">Term*</label>
                        <select name="" id="select-term" class="form-control">
                            <option value="">--Select--</option>
                            @foreach ($semesters as $semester)
                                <option value="{{ $semester->id }}">{{ $semester->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="">Exam Category*</label>
                        <select name="" id="select-exam-category" class="form-control">
                            <option value="">--Select--</option>
                            @foreach ($examCategories as $examCategory)
                                <option value="{{ $examCategory->id }}">{{ $examCategory->exam_category_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <label for="">Exam Name*</label>
                        <select name="" id="select-exam" class="form-control">
                            <option value="">--Select--</option>
                        </select>
                    </div>
                    <div class="col-sm-1">
                        <label for="">Exam Date*</label>
                        <input type="text" id="date" class="form-control hasDatepicker date" name="date" maxlength="10"
                        placeholder="Date" aria-required="true" size="10">
                    </div>
                    <div class="col-sm-2">
                        <label for="">From*</label>
                        <input type="time" class="form-control" id="from-time">
                    </div>
                    <div class="col-sm-2">
                        <label for="">To*</label>
                        <input type="time" class="form-control" id="to-time">
                    </div>
                    <div class="col-sm-1" style="margin-top: 23px">
                        <button class="btn btn-success" id="search-seat-btn">Search</button>
                    </div>
                </div>

                <div class="row" style="margin-top: 30px" id="seat-config-holder">
                    
                </div>
            </div>
        </div>

        <div class="seat-plan-holder">
            
        </div>

        <form class="seat-plan-submit-form" action="{{ url('/academics/save/seat/plan') }}" method="POST">
            @csrf

            <input type="hidden" name="yearId" class="hidden-yearId-field" required>
            <input type="hidden" name="termId" class="hidden-termId-field" required>
            <input type="hidden" name="examId" class="hidden-examId-field" required>
            <input type="hidden" name="employeeIds" class="hidden-employeeIds-field" required>
            <input type="hidden" name="date" class="hidden-date-field" required>
            <input type="hidden" name="fromTime" class="hidden-fromTime-field" required>
            <input type="hidden" name="toTime" class="hidden-toTime-field" required>
            <input type="hidden" name="roomIds" class="hidden-roomIds-field" required>
            <input type="hidden" name="batchIds" class="hidden-batchIds-field" required>
            <input type="hidden" name="sectionIds" class="hidden-sectionIds-field" required>
            <input type="hidden" name="batchesWithSubject" class="hidden-batchesWithSubject-field" required>
            <input type="hidden" name="seatPlan" class="hidden-seatPlan-field" required>

            <button class="save-seat-plan-form-submit-btn" style="display: none"></button>
        </form>

        <form action="{{ url('/academics/print/seat/plan') }}" method="POST" target="_blank">
            @csrf

            <input type="hidden" name="yearId" class="hidden-yearId-field" required>
            <input type="hidden" name="termId" class="hidden-termId-field" required>
            <input type="hidden" name="examId" class="hidden-examId-field" required>
            <input type="hidden" name="date" class="hidden-date-field" required>
            <input type="hidden" name="fromTime" class="hidden-fromTime-field" required>
            <input type="hidden" name="toTime" class="hidden-toTime-field" required>
            <input type="hidden" name="seatPlan" class="print-hidden-seatPlan-field" required>
            <input type="hidden" name="employeeIds" class="hidden-employeeIds-field" required>
            <input type="hidden" name="totalStudents" class="print-hidden-totalStudents-field" required>

            <button class="print-seat-plan-form-print-btn" style="display: none"></button>
        </form>

        <input type="hidden" class="can_check_invigilator_history" value="{{ (in_array("academics/exam/invigilator.history" ,$pageAccessData))?true:false }}">
        <input type="hidden" class="can_save" value="{{ (in_array("academics/save/seat/plan" ,$pageAccessData))?true:false }}">
        <input type="hidden" class="can_print" value="{{ (in_array("academics/print/seat/plan" ,$pageAccessData))?true:false }}">
    </section>
</div>
@endsection



{{-- Scripts --}}

@section('scripts')
<script>
    $(document).ready(function () {
        $('#date').datepicker();

        jQuery('.alert-auto-hide').fadeTo(7500, 500, function() {
            $(this).slideUp('slow', function() {
                $(this).remove();
            });
        });
        
        $('#select-exam-category').change(function () {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/exam/from/exam-category') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'examCategoryId': $(this).val(),
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    var txt = '<option value="">--Select--</option>';

                    data.forEach(element => {
                        txt += '<option value="'+element.id+'">'+element.exam_name+'</option>';
                    });

                    $('#select-exam').html(txt);
                }
            });
            // Ajax Request End
        });


        var roomIds = [];
        var batchIds = [];
        var batchesWithSubject = [];
        var sectionIds = [];
        var rooms = [];
        var studentsGrouped = [];
        var seatPlan = [];
        var totalStudents = 0;
        var employeeIds = null;

        function showSeatPlan() {
            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/get/seat/plan/view') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'seatPlan': JSON.stringify(seatPlan),
                    'totalStudents': totalStudents,
                    'employeeIds': employeeIds,
                    'canCheckInvigilatorHistory': $('.can_check_invigilator_history').val(),
                    'canSave': $('.can_save').val(),
                    'canPrint': $('.can_print').val()
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    $('.seat-plan-holder').html(data);
                }
            });
            // Ajax Request End
        }

        function generateSeats() {
            seatPlan = [];

            rooms.forEach(room => {
                var roomSeatPlan = [];
                var roomBatchIds = [];
                var noOfStudents = 0;
                var seatNo = 1;
                var incrementValue = (room.nextSeatBlank)?2:1;
                var lastRowFirstSeatBlank = false;
                var newRowSeatNo = (room.cols*room.perSeat)+1;
                var newRow = false;

                if (room.noSameClass) {
                    var b = 0;
                    var lastRowFirstBatchId = batchIds[b]; 
                    
                    for (let i = 0; i < room.totalSeats/incrementValue; i++) {
                        if (newRow) {
                            if(incrementValue == 2 && room.cols%2 == 0){
                                if (lastRowFirstSeatBlank) {
                                    lastRowFirstSeatBlank = false;
                                    seatNo--;
                                } else{
                                    lastRowFirstSeatBlank = true;
                                    seatNo++;
                                }
                            }

                            if (lastRowFirstBatchId == batchIds[b]) {
                                if (batchIds.length <= b+1) {
                                    b = 0;
                                }else{
                                    b++;
                                }
                            }
                            lastRowFirstBatchId = batchIds[b]; 
                            newRow = false;
                        }

                        for (let j = 0; j < Object.keys(studentsGrouped).length; j++) {
                            if (studentsGrouped[batchIds[b]].length > 0) {
                                break;
                            }else{
                                if (batchIds.length <= b+1) {
                                    b = 0;
                                }else{
                                    b++;
                                }
                            }
                        }

                        if (studentsGrouped[batchIds[b]].length > 0) {
                            roomSeatPlan[seatNo] = studentsGrouped[batchIds[b]].shift().std_id;
                            seatNo += incrementValue;
                            noOfStudents++;
                            roomBatchIds.push(batchIds[b]);
                        }

                        if (seatNo >= newRowSeatNo) {
                            newRow = true;
                            newRowSeatNo += (room.cols*room.perSeat);
                        }

                        if (batchIds.length <= b+1) {
                            b = 0;
                        }else{
                            b++;
                        }
                    }
                    roomBatchIds = roomBatchIds.filter((v, i, a) => a.indexOf(v) === i);
                } else {
                    for (var key in studentsGrouped) {
                        var len = studentsGrouped[key].length;
                        for (let i = 0; i < len; i++) {
                            if (newRow) {
                                if(incrementValue == 2 && room.cols%2 == 0){
                                    if (lastRowFirstSeatBlank) {
                                        lastRowFirstSeatBlank = false;
                                        seatNo--;
                                    } else{
                                        lastRowFirstSeatBlank = true;
                                        seatNo++;
                                    }
                                }
                                newRow = false;
                            }

                            if (seatNo > room.totalSeats) {
                                break;
                            } else{
                                if (i == 0) {
                                    roomBatchIds.push(key);
                                }
                                roomSeatPlan[seatNo] = studentsGrouped[key].shift().std_id;
                                seatNo += incrementValue;
                                noOfStudents++;
                            }

                            if (seatNo >= newRowSeatNo) {
                                newRow = true;
                                newRowSeatNo += (room.cols*room.perSeat);
                            }
                        }
                    }
                }

                seatPlan.push({
                    roomId: room.id,
                    batchIds: roomBatchIds,
                    noOfStudents: noOfStudents,
                    seatPlan: roomSeatPlan
                });
            });
        }

        function setVariables() {
            var firstWrap = $('.check1-wrap');
            var secondWrap = $('.check2-wrap');

            roomIds = secondWrap.find('.room').map(function () {
                if ($(this).is(':checked')) {   
                    return $(this).val();
                }
            }).get();

            batchIds = firstWrap.find('.class').map(function () {
                if ($(this).is(':checked')) {   
                    return $(this).val();
                }
            }).get();

            sectionIds = firstWrap.find('.form').map(function () {
                if ($(this).is(':checked')) {   
                    return $(this).val();
                }
            }).get();

            batchesWithSubject = firstWrap.find('.class').map(function () {
                var parent = $(this).parent().parent().parent();
                if ($(this).is(':checked')) {   
                    return {
                        batchId: $(this).val(),
                        subjectId: parent.find('.subject').val(),
                        criteriaId: parent.find('.criteria').val(),
                    };
                }
            }).get();

            rooms = secondWrap.find('.room').map(function () {
                if ($(this).is(':checked')) {
                    return {
                        id: $(this).val(),
                        nextSeatBlank: $(this).data('next-seat-blank'),
                        noSameClass: $(this).data('no-same-class'),
                        rows: $(this).parent().find('.seat-row').text(),
                        cols: $(this).parent().find('.seat-col').text(),
                        perSeat: $(this).parent().find('.stu-per-seat').text(),
                        totalSeats: $(this).parent().find('.total-seat').text(),
                        availableSeats: $(this).parent().parent().parent().find('.available-seats').val(),
                    };
                }
            }).get();

            totalStudents = $('.grand-total').text();
        }


        $('#search-seat-btn').click(function () {
            var yearId = $('#select-year').val();
            var termId = $('#select-term').val();
            var examId = $('#select-exam').val();
            var date = $('#date').val();
            var fromTime = $('#from-time').val();
            var toTime = $('#to-time').val();

            if (examId && date && fromTime && toTime) {
                if (fromTime <= toTime) {
                    // Ajax Request Start
                    $_token = "{{ csrf_token() }}";
                    $.ajax({
                        headers: {
                            'X-CSRF-Token': $('meta[name=_token]').attr('content')
                        },
                        url: "{{ url('/academics/search/exam-seat') }}",
                        type: 'GET',
                        cache: false,
                        data: {
                            '_token': $_token,
                            'yearId': yearId,
                            'termId': termId,
                            'examId': examId,
                            'date': date,
                            'fromTime': fromTime,
                            'toTime': toTime,
                        }, //see the _token
                        datatype: 'application/json',
                    
                        beforeSend: function () {},
                    
                        success: function (data) {
                            $('.seat-plan-holder').empty();
                            
                            if (data == 1) {
                                swal('Error!', 'A seat plan is already assigned in this time, try another time!', 'error');
                            } else{
                                $('#seat-config-holder').html(data[1]);
                                $('.hidden-yearId-field').val(yearId);
                                $('.hidden-termId-field').val(termId);
                                $('.hidden-examId-field').val(examId);
                                $('.hidden-date-field').val(date);
                                $('.hidden-fromTime-field').val(fromTime);
                                $('.hidden-toTime-field').val(toTime);

                                if (data[0] == 2) {
                                    seatPlan = JSON.parse(data[2]);
                                    employeeIds = JSON.parse(data[3]);
                                    setVariables();
                                    showSeatPlan();
                                }
                            }
                        }
                    });
                    // Ajax Request End
                } else {
                    swal('Error!', 'From Time can not be greater than To Time!', 'error');
                }
            } else {
                swal('Error!', 'Fill all the required fields first!', 'error');
            }            
        });


        // Generate Seat
        $(document).on('click', '.generate-seat', function () {
            setVariables();

            // Ajax Request Start
            $_token = "{{ csrf_token() }}";
            $.ajax({
                headers: {
                    'X-CSRF-Token': $('meta[name=_token]').attr('content')
                },
                url: "{{ url('/academics/get/students/from/sections') }}",
                type: 'GET',
                cache: false,
                data: {
                    '_token': $_token,
                    'sectionIds': sectionIds,
                }, //see the _token
                datatype: 'application/json',
            
                beforeSend: function () {},
            
                success: function (data) {
                    studentsGrouped = data;
                    generateSeats();
                    showSeatPlan();        
                }
            });
            // Ajax Request End
        });

        $(document).on('click', '#save-seat-plan-btn', function () {
            var roomSummaryRows = $('.room-summary-row');
            var invigilatorIds = {};
            var invigilatorsSelected = true;

            roomSummaryRows.each((index, value) => {
                var roomId = $(value).data('room-id');
                invigilatorIds[roomId] = $(value).find('.select-invigilator').val();
                if (!invigilatorIds[roomId]) {
                    invigilatorsSelected = false;
                }
            });

            $('.hidden-employeeIds-field').val(JSON.stringify(invigilatorIds));
            $('.hidden-roomIds-field').val(JSON.stringify(roomIds));
            $('.hidden-batchIds-field').val(JSON.stringify(batchIds));
            $('.hidden-sectionIds-field').val(JSON.stringify(sectionIds));
            $('.hidden-batchesWithSubject-field').val(JSON.stringify(batchesWithSubject));
            $('.hidden-seatPlan-field').val(JSON.stringify(seatPlan));

            if (invigilatorsSelected) {
                $('.save-seat-plan-form-submit-btn').click();
            } else {
                swal("Error!", "Please select invigilators first!", "error");
            }

        });

        $(document).on('click', '#print-seat-plan-btn', function () {
            setVariables();

            var roomSummaryRows = $('.room-summary-row');
            var invigilatorIds = {};

            roomSummaryRows.each((index, value) => {
                var roomId = $(value).data('room-id');
                invigilatorIds[roomId] = $(value).find('.select-invigilator').val();
            });

            $('.hidden-employeeIds-field').val(JSON.stringify(invigilatorIds));
            $('.print-hidden-seatPlan-field').val(JSON.stringify(seatPlan));
            $('.print-hidden-totalStudents-field').val(totalStudents);

            $('.print-seat-plan-form-print-btn').click();
        });



        // Drag and Drop seat manipulation starts
        var dragged = null;
        var dropped = null;
        var temp = null;
        var sTemp = null;


        function seatNumberColorCorrection(item) {
            if (item.find('.seat-student-id').text()) {
                item.find('h3').removeClass().addClass('text-success');
            } else {
                item.find('h3').removeClass().addClass('text-danger');
            }
        }

        function seatPlanDataCorrection() {
            var draggedRoomId = dragged.data('room-id');
            var draggedRoomIndex = null;
            var draggedSeatNo = dragged.data('seat-no');
            var droppedRoomId = dropped.data('room-id');
            var droppedRoomIndex = null;
            var droppedSeatNo = dropped.data('seat-no');

            seatPlan.forEach((element, index) => {
                if (element.roomId == draggedRoomId) {
                    draggedRoomIndex = index;
                } 
                if(element.roomId == droppedRoomId){
                    droppedRoomIndex = index;
                }
            });

            sTemp = seatPlan[draggedRoomIndex].seatPlan[draggedSeatNo];
            seatPlan[draggedRoomIndex].seatPlan[draggedSeatNo] = seatPlan[droppedRoomIndex].seatPlan[droppedSeatNo];
            seatPlan[droppedRoomIndex].seatPlan[droppedSeatNo] = sTemp;
        }

        $(document).on('dragover', '.exam-seat', function (event) {
            event.preventDefault();
        });

        $(document).on('dragstart', '.exam-seat', function (event) {
            dragged = $(this);
        });

        $(document).on('drop', '.exam-seat', function (event) {
            dropped = $(this);

            temp = dragged.find('.student-in-seat').html();
            dragged.find('.student-in-seat').html(dropped.find('.student-in-seat').html());
            dropped.find('.student-in-seat').html(temp);

            seatNumberColorCorrection(dragged);
            seatNumberColorCorrection(dropped);

            seatPlanDataCorrection();
        });
        // Drag and Drop seat manipulation ends

        $('form.seat-plan-submit-form').on('submit', function (e) {
            e.preventDefault();
            // ajax request
            $.ajax({
                url: "/academics/save/seat/plan",
                type: 'POST',
                cache: false,
                data: $('form.seat-plan-submit-form').serialize(),
                datatype: 'application/json',

                beforeSend: function() {
                    waitingDialog.show('Loading...');
                },

                success:function(data){
                    console.log(data);
                    waitingDialog.hide();
                    swal("Success!", "Seat Plan saved successfully!", "success");
                },

                error:function(data){
                    waitingDialog.hide();
                    swal("Error!", "Error saving Seat Plan!", "error");
                    console.log(data);
                }
            });
        });
    });
</script>
@stop