<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span
            aria-hidden="true">×</span></button>
    <h4 class="modal-title">{{$calanderCategory->name}} Calender Set Up</h4>
</div>

<div class="modal-body" style="overflow:auto">
    <div class="row" style="margin-bottom: 20px">
        <div class="col-sm-4">
            <input type="text" id="fromDate" class="form-control hasDatepicker from-date" name="fromDate" maxlength="10"
                placeholder="From Date" aria-required="true" size="10">
        </div>
        <div class="col-sm-4">
            <input type="text" id="toDate" class="form-control hasDatepicker to-date" name="toDate" maxlength="10"
                placeholder="To Date" aria-required="true" size="10">
        </div>
        <div class="col-sm-4">
            <button class="btn btn-success search-menu-button">Search</button>
        </div>
    </div>
    <div class="calender-portion" style="display: none">
        <div class="row">
            <div class="col-sm-12">
                <label for="">Weekend: </label>
                <input type="checkbox" value="6" class="dayCheck"> Saturday
                <input type="checkbox" value="0" class="dayCheck"> Sunday
                <input type="checkbox" value="1" class="dayCheck"> Monday
                <input type="checkbox" value="2" class="dayCheck"> Tuesday
                <input type="checkbox" value="3" class="dayCheck"> Wednesday
                <input type="checkbox" value="4" class="dayCheck"> Thursday
                <input type="checkbox" value="5" class="dayCheck"> Friday
            </div>
        </div>
        <form action="{{ url('employee/save/holiday-calender/'.$calanderCategory->id) }}" method="POST">
            @csrf
    
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Day</th>
                        <th scope="col">Date</th>
                        <th scope="col">Type</th>
                        <th scope="col">Details</th>
                    </tr>
                </thead>
                <tbody class="activity-table">
                </tbody>
            </table>
            <div class="row">
                <div class="col-sm-12">
                    <button type="submit" class="btn btn-success" style="float: right">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#fromDate').datepicker();
        $('#toDate').datepicker();

        var calenderCategoryId = {!!json_encode($calanderCategory->id) !!};
        var fromDate = null;
        var toDate = null;
        var startDate = null;
        var endDate = null;
        var days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        var selectedDays = [];
        var calenderData = null;

        function setCalenderRows(startDate, endDate) {
            $('.activity-table').empty();
            var loop = startDate;

            while (loop <= endDate) {
                var date = ((loop.getDate() <= 9) ? '0' : '') + loop.getDate();
                var month = ((loop.getMonth() + 1 <= 9) ? '0' : '') + (loop.getMonth() + 1);
                // console.log(loop.getDate());

                // Previous value Checking Start
                var previousValue = null;
                calenderData.forEach(item => {
                    var scheduleDate = item.holiday.slice(0, 10);

                    if (scheduleDate == loop.getFullYear() + '-' + month + '-' + date) {
                        previousValue = item;
                    }
                });
                // Previous value Checking End

                // Table Row Showing Start
                var holiday_calender_category_id = (previousValue) ? previousValue.holiday_calender_category_id : '';
                var sampleDetails = (previousValue) ? previousValue.details : null;
                var details = (sampleDetails) ? previousValue.details : '';
                var type = (previousValue) ? previousValue.type : '';

                var todaysDate = new Date();
                var disabled = (loop < todaysDate.setDate(todaysDate.getDate() - 1)) ? 'disabled' : '';

                var holidayTypes = '';
                for (var i = 0; i < 3; i++) {
                    var selected = (type == i) ? 'selected' : '';

                    if (i == 1) {
                        if (selectedDays.includes(loop.getDay().toString())) {
                            selected = 'selected';
                        }
                        holidayTypes += '<option value="1" ' + selected + '>Weekend</option>';
                    } else if (i == 2) {
                        holidayTypes += '<option value="2" ' + selected + '>Govt. Holiday</option>';
                    } else {
                        holidayTypes += '<option value="">Working Day</option>';
                    }
                }

                var row = '<tr><td>' + days[loop.getDay()] + '</td><td>' + date + '/' + month + '/' + loop
                    .getFullYear() +
                    '<input type="date" name="dates[]" value="' + loop.getFullYear() + '-' + month + '-' +
                    date +
                    '" style="display: none"></td><td><select name="types[]" id="" class="form-control">' +
                    holidayTypes +
                    '</select></td><td><textarea name="details[]" id="" rows="1" class="form-control">' +
                    details + '</textarea></td></tr>';

                // Assign html
                $('.activity-table').append(row);
                // Table Row Showing End

                // Date Increment
                var newDate = loop.setDate(loop.getDate() + 1);
                loop = new Date(newDate);
            }

            startDate = startDate.setDate(startDate.getDate() - 1);
        }

        // For Date wise table
        $('.search-menu-button').click(function () {
            fromDate = $('.from-date').val();
            toDate = $('.to-date').val();
            startDate = new Date(fromDate);
            endDate = new Date(toDate);
            var formattedStartDate = startDate.getFullYear() + '-' + (startDate.getMonth() + 1) + '-' +
                startDate.getDate();
            var formattedEndDate = endDate.getFullYear() + '-' + (endDate.getMonth() + 1) + '-' +
                endDate.getDate();


            if ((fromDate && toDate) && (startDate < endDate)) {
                // Ajax Request Start
                $_token = "{{ csrf_token() }}";
                $.ajax({
                    headers: {
                        'X-CSRF-Token': $('meta[name=_token]').attr('content')
                    },
                    url: "{{ url('/employee/search/holiday-calender') }}",
                    type: 'GET',
                    cache: false,
                    data: {
                        '_token': $_token,
                        'calender_category_id': calenderCategoryId,
                        'startDate': formattedStartDate,
                        'endDate': formattedEndDate
                    }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function () {},

                    success: function (data) {
                        calenderData = data;

                        //Setting Up Data
                        $('.calender-portion').slideDown();
                        setCalenderRows(startDate, endDate);
                    },

                    error: function (error) {
                        console.log(error);
                    }
                });
                // Ajax Request End
            } else {
                alert("Fill up the date fields with valid data first!!");
            }
        });

        // Weekend day filter
        $('.dayCheck').click(function () {
            if ((fromDate && toDate) && (startDate < endDate)){
                var checkboxes = $('.dayCheck');
                var checkboxDays = [];

                checkboxes.each(function (index) {
                    if ($(this).is(":checked")) {
                        checkboxDays.push($(this).val());
                    }
                });

                selectedDays = checkboxDays;
                setCalenderRows(startDate, endDate);
            } else {
                alert("NO data to filter!!");
            }            
        });
    });
</script>