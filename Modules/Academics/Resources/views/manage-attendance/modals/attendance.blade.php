<link href="{{ asset('css/fc/fullcalendar.css')}}" rel="stylesheet">
<link href="{{ asset('css/fc/fullcalendar_002.css')}}" rel="stylesheet" media="print">
<link href="{{ asset('css/fc/scheduler.css') }}" rel="stylesheet">

<style>
  table.fc-border-separate { table-layout: fixed; }
  table.fc-border-separate, table.fc-border-separate.fc td, .fc th {
    /*width: 10px !important;*/
    height: 55px !important;
  }

  .fc-timeline .fc-body .fc-scroller {
    min-height: 200px;
  }

</style>

<p class="text-right">
  <a class="btn btn-primary" href="{{url('/academics/attendance/import')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-plus-square" aria-hidden="true"></i> Import</a>
  <button type="submit" id="attendance_export_btn" class="btn btn-info"><i class="fa fa-plus-square" aria-hidden="true"></i> Export</button>
</p>
<h4 class="text-center text-bold bg-blue-gradient"> Attendance List</h4>
<form id="vanusAttendanceCalendar" method="POST">
  <input type="hidden" name="_token" value="{{csrf_token()}}">

  <input id="att_class" type="hidden" name="att_class" value="">
  <input id="att_std_list" type="hidden" name="att_std_list" value="{{json_encode($studentList)}}">
  <input id="att_sesction" type="hidden" name="att_sesction" value="">
  <input id="att_subjcet" type="hidden" name="att_subjcet" value="">
  <input id="att_session" type="hidden" name="att_session" value="">
  <input id="att_date" type="hidden" name="att_date" value="">
  <input id="date_counter" type="hidden" name="date_counter" value="0">
  <input id="delete_counter" type="hidden" name="delete_counter" value="0">

  <div id="calendar"></div>
  <div id="calendar_info"></div>
  <div id="date_check_info"></div>
  @if($attendanceModule->count()>0)
    <p>Send Automatic SMS  <label><input type="radio" class="send_automatic_sms" name="send_automatic_sms" value="1">Yes</label> <label><input type="radio" class="send_automatic_sms" name="send_automatic_sms" value="0" checked="checked">No</label></p>
  @endif
  <button type="submit" class="btn btn-info pull-right" style="margin-right: 20px">Submit</button>
</form>


<script src="{{ asset('js/fc/moment.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/fc/fullcalendar.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/fc/scheduler.js') }}" type="text/javascript"></script>

<script>
    $(document).ready(function () {
        $('#attendance_export_btn').click(function () {

            if($('#subject').length != 0){
                var subject = $('#subject').val();
            }else{
                var subject = 0;
            }

            // dynamic html form
            $('<form id="attendance_export_form" action="/academics/attendance/export" method="GET" style="display:none;"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>')
                .append('<input type="hidden" name="section" value="'+$('#section').val()+'"/>')
                .append('<input type="hidden" name="subject" value="'+subject+'"/>')
                .append('<input type="hidden" name="session" value="'+$('#session').val()+'"/>')
                // append to body and submit the form
                .appendTo('body').submit();
            // remove form from the body
            $('#attendance_export_form').remove();
        });

    });
</script>

<script type="text/javascript">
    var studentList  = JSON.parse( '<?php echo json_encode($studentList); ?>' );
    var studentAttendanceList  = JSON.parse( '<?php echo json_encode($attendancelist); ?>' );
    $(function() {
        var calendarStartDate;
        if($('#datepicker').val()){
            calendarStartDate = $.datepicker.formatDate('yy-mm-dd', $('#datepicker').datepicker('getDate'));
        }else{
            calendarStartDate = $.datepicker.formatDate('yy-mm-dd',  new Date(Date.now()));
            $('#datepicker').datepicker('setDate', new Date());
        }
        $('#att_date').val(calendarStartDate);


        $('#calendar').fullCalendar({
            schedulerLicenseKey: 'GPL-My-Project-Is-Open-Source',
            // theme: true,
            header: {left: 'myCustomButton',center: 'title',right: 'myCustomButton'},
//      header: {left: 'myCustomButton',center: 'title',right: 'prev,next'},
            defaultView: 'timelineTenDay',
            displayEventTime: false,
            views: {timelineTenDay:{type:'timelineWeek',columnFormat:'ddd D/M', slotDuration:'24:00',duration:{days:1}}},
//      views: {timelineTenDay:{type:'timelineWeek',columnFormat:'ddd D/M', slotDuration:'24:00',duration:{days:7}}},
            navLinks: false,
            editable: false,
            aspectRatio: 1.8,
            selectable: true,
            defaultDate: moment(calendarStartDate),
            // scrollTime: '00:00',
            resourceAreaWidth: '40%',
            resourceColumns: [
                {
                    labelText:'GR. No',
                    field:'gr_no',
                    width:'25%'
                },
                {
                    labelText:'Student Name',
                    field:'name',
                    width:"75%"
                }],
            resources: studentList,
            events: studentAttendanceList,
            eventOverlap: false,

            eventClick: function(event, jsEvent, view) {
                if (event.title){
                    switch(event.title){
                        case 'Present':
                            event.title = 'Absent';
                            event.color = 'red';
                            $('#calendar').fullCalendar('refetchEvents',event.id);
                            // $("#1"+event.id+event.resourceId+event.start).val(0);
                            $("#2"+event.id+event.resourceId+event.start).val(0);
                            // $("#hidden_1"+event.id+event.resourceId+event.start).val(0);
                            $("#hidden_2"+event.id+event.resourceId+event.start).val(0);
                            break;
                        case 'Absent':
                            event.title = 'Present';
                            event.color = 'green';
                            $('#calendar').fullCalendar('refetchEvents',event.id);
                            // $("#1"+event.id+event.resourceId+event.start).val(1);
                            $("#2"+event.id+event.resourceId+event.start).val(1);
                            // $("#hidden_1"+event.id+event.resourceId+event.start).val(1);
                            $("#hidden_2"+event.id+event.resourceId+event.start).val(1);
                            break;
                        default:
                            event.title = 'Holiday';
                            event.color = 'blue';
                            $('#calendar').fullCalendar('refetchEvents',event.id);
                            break;
                    }
                }
            },

            dayClick: function(date, jsEvent, view) {
                // alert('Clicked on: ' + date.format());
                // alert('Coordinates: ' + jsEvent.pageX + ',' + jsEvent.pageY);
                // alert('Current view: ' + view.name);
                // // change the day's background color just for fun
                // $(this).css('background-color', 'red');
            },

            eventRender: function (event, element) {

                var att_id = null;
                var att_type = null;

                // checking
                if(event.att_id>0){
                    att_id = event.att_id;
                    att_type = event.att_type;
                }else{
                    att_id = 0;
                    att_type = 1;
                }

                if($("#hidden_1"+event.id+event.resourceId+event.start).length && $("#hidden_1"+event.id+event.resourceId+event.start).length){
                    att_id = $("#hidden_1"+event.id+event.resourceId+event.start).val();
                    att_type = $("#hidden_2"+event.id+event.resourceId+event.start).val();
                }else{
                    $('#calendar_info').append('<input type="hidden" id="hidden_1'+event.id+event.resourceId+event.start+'" name="att_'+event.start+'[id_'+event.resourceId+'][att_id]" value="'+att_id+'" class="'+event.start+'"> <input type="hidden" id="hidden_2'+event.id+event.resourceId+event.start+'" name="att_'+event.start+'[id_'+event.resourceId+'][att_type]" value="'+att_type+'" class="'+event.start+'">');
                }
                element.find('.fc-content').append('<input type="hidden" id="1'+event.id+event.resourceId+event.start+'" name="att_'+event.start+'[id_'+event.resourceId+'][att_id]" value="'+att_id+'" class="'+event.start+'"> <input type="hidden" id="2'+event.id+event.resourceId+event.start+'" name="att_'+event.start+'[id_'+event.resourceId+'][att_type]" value="'+att_type+'" class="'+event.start+'">');
            },
        });

        // next button action
        $(".fc-button").click(function(){
            // pre && next button click action
            loadNextWeekAttendance();
            // set attendance dom
            setAttendance();
        });

        // set attendance dom
        setAttendance();
        // check mark all attendance date
        setAttendanceDetails(studentAttendanceList);
    });

    // set and remove attendance
    function setAttendance(){
        // set class section subject session
        $('#att_class').val($('#batch').val());
        $('#att_sesction').val($('#section').val());
        $('#att_subjcet').val($('#subject').val());
        $('#att_session').val($('#session').val());


        // set checkbox
        var item = $(".fc-head .fc-time-area table tbody tr th");
        var icon = '<input type="checkbox">';
        item.prepend(icon);

        $(':checkbox').click(function(){
            // date counter
            var dateCounter = parseInt($("#date_counter").val());
            // checkbox checking
            if($(this).is(':checked')){
                // today date
                var today = new Date($(this).parent().attr('data-date')).getTime();
                // date counter increase
                dateCounter++;
                $("#date_counter").val(dateCounter);
                // set input id attribute
                $(this).attr('id',today);
                // set input name attribute
                $(this).attr('name', 'datelist[date_'+dateCounter+']');
                $(this).attr('value', today);
                $(this).attr('data-type', 'new');
                // create json event
                var jsonArr = [];
                // looping
                for (var i = 0; i < studentList.length; i++) {
                    jsonArr.push({
                        att_id: 0,
                        id: studentList[i].id+today,
                        resourceId: studentList[i].id,
                        start: today,
                        end: today,
                        att_type: 1,
                        title: 'Present',
                        color: 'green',
                    });
                }
                // add event to the calendar
                $('#calendar').fullCalendar('addEventSource', jsonArr);
            }else{
                // today's date in milliseconds
                var today = new Date($(this).parent().attr('data-date')).getTime();
                // remove attributes
                $(this).removeAttr('id');
                $(this).removeAttr('name');
                $(this).removeAttr('value');

                // date counter decrease
                dateCounter--;
                $("#date_counter").val(dateCounter);
                // remove items
                if(removeElements(today)){
                    $("#calendar_info").find(':input.'+today).each(function() {
                        $(this).remove();
                    });
                }

                // sorting order
                var myCounter = 1;
                $("th.fc-widget-header").find(':input').each(function() {
                    console.log(myCounter);
                    if($(this).attr('id') != undefined){
                        $(this).removeAttr('name');
                        $(this).attr('name','datelist[date_'+myCounter+']');
                        myCounter++;
                    }
                });

                var dateType = $(this).attr('data-type');
                if(dateType=='old'){
                    //alert(dateType);
                    var deleteCounter = parseInt($('#delete_counter').val());
                    var newDeleteCounter = deleteCounter+1;
                    var deleteItem = '<input type="hidden" name="deleteList[date_'+newDeleteCounter+']" value="'+today+'">';
                    $("#vanusAttendanceCalendar").append(deleteItem);
                    $('#delete_counter').val(newDeleteCounter);
                }
            }
        });
    }

    function loadNextWeekAttendance(){
        var token = "{{ csrf_token() }}";
        var class_id = $("#batch").val();
        var section_id = $("#section").val();
        var subject_id = $("#subject").val();
        var session_id = $("#session").val();
        var myBtnDatepicker = $(".fc-time-area .fc-widget-header:nth-last-child(7)").attr('data-date');
        $('#att_date').val(myBtnDatepicker);

        if(class_id && section_id && session_id && myBtnDatepicker){
            // ajax request
            $.ajax({
                url: "{{ url('/academics/manage/attendance/manageanothertendance') }}",
                type: 'POST',
                cache: false,
                data: {'_token': token, 'batch': class_id, 'section':section_id, 'subject': subject_id, 'session':session_id, 'datepicker':myBtnDatepicker },
                datatype: 'application/json',
                beforeSend: function() {
                    $("#date_counter").val(0);
                    $('#delete_counter').val(0)
                    $('#calendar_info').html('');
                    refreshCalendar();
                },
                success:function(data){
                    if(setAttendanceDetails(data)){
                        $('#calendar').fullCalendar('addEventSource', data);
                    }
                },
                error:function(){
                    //console.log(data);
                },
            });
        }else{
            alert('Please double check all inputs are selected.');
        }
    }

    function refreshCalendar(){
        //
        $(".fc-time-area .fc-widget-header").each(function() {
            removeElements(new Date($(this).attr('data-date')).getTime());
        });

        return true;
    }

    function removeElements(today){
        var myFullCalendar = $('#calendar');
        var allEvents = myFullCalendar.fullCalendar('clientEvents');
        var toDayEvents = [];
        for (var i = 0; i < allEvents.length; i++) {
            if (moment(today).isSame(moment(allEvents[i].start))) {
                toDayEvents.push(allEvents[i].id);
            }
        }
        if(toDayEvents.length > 0){
            for (var i = 0; i < toDayEvents.length; i++) {
                //Remove events with ids of non usercreated events
                myFullCalendar.fullCalendar('removeEvents', toDayEvents[i]);
            }
        }
        return true;
    }

    // check mark all attendance date
    function setAttendanceDetails(studentAttendanceList){
        var dateCounter =0;
        // loop counter
        var myCounter =1;
        for (var i=0; i <studentAttendanceList.length; i++) {
            // get attendance date
            var attendanceDate = studentAttendanceList[i].start;
            // set attendance date
            $("th.fc-widget-header").find(':input').each(function() {
                var inputDate = new Date($(this).parent().attr('data-date')).getTime();
                if(attendanceDate == inputDate && !$(this).is(':checked')){
                    $(this).prop( "checked", true);
                    // set input id attribute
                    $(this).attr('id',attendanceDate);
                    // set input name attribute
                    $(this).attr('name', 'datelist[date_'+myCounter+']');
                    $(this).attr('value', attendanceDate);
                    $(this).attr('data-type', 'old');
                    dateCounter++;
                    myCounter++;
                }
            });
        }
        $("#date_counter").val(dateCounter);
        // return true
        return true;
    }


    // request for section list using batch and section id
    $('form#vanusAttendanceCalendar').on('submit', function (e) {


        var calendarStartDate;
        if($('#datepicker').val()){
            calendarStartDate = $.datepicker.formatDate('yy-mm-dd', $('#datepicker').datepicker('getDate'));
        }else{
            calendarStartDate = $.datepicker.formatDate('yy-mm-dd',  new Date(Date.now()));
        }


        // waiting dialog


        e.preventDefault();
        // ajax request
        $.ajax({
            url: "{{ url('/academics/manage/attendance/manage-attendance') }}",
            type: 'POST',
            data: $('form').serialize(),
            datatype: 'application/json',
            beforeSend: function() {
                waitingDialog.show('Submitting...');
                $("#date_counter").val(0);
                $('#delete_counter').val(0)
                $('#calendar_info').html('');
                //reander calendar
                refreshCalendar();
            },
            success:function(data){

                if(setAttendanceDetails(data)){
                    $('#calendar').fullCalendar('addEventSource', data);
                }

                waitingDialog.hide();

            },
            error:function(){
                //console.log(data);
            },
        });

    });

</script>