<!-- DataTables -->
<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>

<div class="box box-solid">
    <!--
    <div class="et">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-search"></i> View Topic List</h3>
        </div>
    </div>
    -->
  



        <div id="p0" data-pjax-container="" data-pjax-push-state data-pjax-timeout="10000">
            <div id="w1" class="grid-view">
                @if(isset($allClassPeriods) && !empty($allClassPeriods) && $allClassPeriods->count()>0)
                <table  id="example2" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th><a data-sort="sub_master_name">Sl#</i></a></th>
                            <th><a data-sort="sub_master_code">Date</a></th>
                            <th><a data-sort="sub_master_alias">Routine Time</a></th>
                            <th><a data-sort="sub_master_alias">Conduct Time</a></th>
                            <th><a data-sort="sub_master_alias">Topic</a></th>
                            <th><a data-sort="sub_master_alias">Subjcet</a></th>
                            <th><a data-sort="sub_master_alias">Class</a></th> 
                            <th><a data-sort="sub_master_alias">Section</a></th>
                            <th><a data-sort="sub_master_alias">Teacher</a></th>
                            <th><a data-sort="sub_master_alias">Duration(Minutes)</a></th>
                            <th><a data-sort="sub_master_alias">Total</a></th>
                            <th><a data-sort="sub_master_alias">P</a></th>
                            <th><a data-sort="sub_master_alias redcolor">A</a></th>
                            <th><a data-sort="sub_master_alias redcolor">L</a></th>
                            <th><a data-sort="sub_master_alias">Remarks</a></th> 
                            <th><a data-sort="sub_master_alias">Status</a></th>    
                        </tr>
                    </thead>
                    <tbody >
                    @php 
                    $days = array(1=>'Saturday',2=>'Sunday',3=>'Monday',4=>'Tuesday',5=>'Wednesday',6=>'Thursday',7=>'Friday');
                    $currentDate = date('m-d-Y'); 
                    @endphp

                    @for($i=1;$i<=count($days);$i++)
                        @if(isset($total_Day_Date))
                            @foreach($total_Day_Date as $key=>$date)
                                @if($days[$i] == $key)
                                    @foreach($allClassPeriods as $period)
                                        @php
                                        $sortedTimetableProfile = sortTimetable($i, $period->id, $allTimetables);

                                        $timetableCount = $sortedTimetableProfile->count();
                                        @endphp

                                        @if($timetableCount>0)
                                            @php
                                                $timetableProfile = (array) $sortedTimetableProfile->toArray();
                                                $timetableProfile = reset($timetableProfile);
                                                $teacherProfile = $period->teacher($timetableProfile['teacher']);
                                                // $classSubjectProfile = $period->subject($timetableProfile['subject']);
                                                $classSubjectProfile = findClassSubject($timetableProfile['subject']);
                                                $subjectProfile = $classSubjectProfile?$classSubjectProfile->subject():null;
                                            @endphp
                                        @endif
                                    
                                    <tr class="gradeX">
                                        <td>{{ ++$loop->index }}.</td>
                                        <td>
                                            {{ $date }} <br/> {{ $key }} 
                                        </td>
                                        <td>
                                            @if(isset($topicList)) {{ $PeriodList[$loop->index] }} @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if(isset($scheduledData[$subjectProfile->id][17])&& $timetableCount>0)    
                                          {{date('H:i',strtotime($scheduledData[$subjectProfile->id][17]) )}}
                                          -  {{ date('H:i',strtotime($scheduledData[$subjectProfile->id][20]) ) }}


                                            @else
                                            {{ '--' }}
                                            @endif
                                        </td>
                                        <td style="text-align: center;">

                                            @if(isset($scheduledData[$subjectProfile->id][19]) && $timetableCount)    
                                               <?php $str = $scheduledData[$subjectProfile->id][21]  ?>
  
                                        <a href="#" data-toggle="tooltip" title="{{ $scheduledData[$subjectProfile->id][21] }}"><?php echo (count(explode(",",$str))); ?></a>

                                            @else
                                            {{ '--' }}
                                            @endif
                                        </td>
                                        <td style="text-align: center;">
                                            @if($timetableCount>0)
                                                {{$subjectProfile?$subjectProfile->subject_name:'(removed)'}} 
                                            @else 
                                                {{ '--' }} 
                                            @endif
                                        </td>
                                        <td>{{ $ClassName->batch_name }}</td>
                                        <td>{{ $SectionName->section_name }}</td>
                                        <td style="text-align: center;">
                                            @if($timetableCount>0)
                                                {{$teacherProfile->first_name." ".$teacherProfile->middle_name." ".$teacherProfile->last_name}}
                                            @else
                                                {{ '--' }}
                                            @endif
                                            <br/>
                                            @if(isset($scheduledData[$subjectProfile->id][17]))
                                            <strong  class="redcolor">(    
                                               {{date('H:i',strtotime($scheduledData[$subjectProfile->id][17]) )}}
                                            )</strong>
                                            @endif
                                           
                                        </td>
                                        <td style="text-align: center;">



                            @if(isset($scheduledData[$subjectProfile->id][17]))    
                                             <?php   $to_time = strtotime($scheduledData[$subjectProfile->id][20]); 
                                          $from_time =strtotime($scheduledData[$subjectProfile->id][17]);
                                          

                            $start_date = new DateTime($scheduledData[$subjectProfile->id][17]);
                            $end_date = new DateTime($scheduledData[$subjectProfile->id][20]);
                                 $interval = $start_date->diff($end_date);
                            $hours   = $interval->format('%h'); 
                            $minutes = $interval->format('%i');

                              echo $hours.':'. $minutes;
 
                                       ?>



                                            @else
                                            {{ '--' }}
                                            @endif
                                        </td>
                                        <td style="text-align: center;">{{ $studentList }}</td>
                                        <td style="text-align: center;">
                                            @if(isset($scheduledData[$subjectProfile->id][14]))    
                                                {{ $scheduledData[$subjectProfile->id][14] }}
                                            @else
                                            {{ '--' }}
                                            @endif
                                        </td>
                                        <td class="redcolor">
                                            @if(isset($scheduledData[$subjectProfile->id][15]))    
                                                {{ $scheduledData[$subjectProfile->id][15] }}
                                            @else
                                            {{ '--' }}
                                            @endif
                                        </td>
                                        <td class="redcolor">
                                            @if(isset($scheduledData[$subjectProfile->id][16]))    
                                                {{ $scheduledData[$subjectProfile->id][16] }}
                                            @else
                                            {{ '--' }}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($scheduledData[$subjectProfile->id][12]))    
                                                {{ $scheduledData[$subjectProfile->id][12] }}
                                            @else
                                            {{ '--' }}
                                            @endif
                                        </td>
                                       
                                        <td style="color: green;">
                                            @php
                                            $currentTime = date('h:i');
                                            @endphp

                             
                                            
                                            <br/>
                                            
                                     @if(isset($scheduledData) && $timetableCount>0)

                                        @if(isset($scheduledData[$subjectProfile->id][0]) && $scheduledData[$subjectProfile->id][11] == 2 && $scheduledData[$subjectProfile->id][9] == $date)
 
                                 @if (isset($scheduledData[$subjectProfile->id][9])    &&  $scheduledData[$subjectProfile->id][9] >$currentDate && $scheduledData[$subjectProfile->id][11] == 2)
                                         @php echo "Schedule But Date is not appper";
                                          @endphp

                                            @elseif (isset($scheduledData[$subjectProfile->id][9])    &&  $scheduledData[$subjectProfile->id][9] < $currentDate && $scheduledData[$subjectProfile->id][11] == 2)

                                                @php echo "Schedule But Date is Over"; @endphp



                                                 {{-- @elseif (isset($scheduledData[$subjectProfile->id][9])    &&  $scheduledData[$subjectProfile->id][9] >$currentDate && $scheduledData[$subjectProfile->id][11] == 2) --}} 
                                     @elseif ($scheduledData[$subjectProfile->id][9])
                                       @role(['super-admin','admin','teacher'])
                                    <form method="post" action="" id="forms{{ $subjectProfile->id }}">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                    @if(isset($scheduledData[$subjectProfile->id][23]))
                                                    <input type="hidden" name="class_scheduled_id" value="{{ $scheduledData[$subjectProfile->id][23] }}">
                                                    @else
                                                    <input type="hidden" name="class_scheduled_id" value="">
                                                   @endif
                                                   <span>
                                                    <i class="icon-eye-open">  </i>
                                                 </span>
                                            <button type="button" id="online_class_{{ $subjectProfile->id }}" onclick="GointScheduledClass({{ $subjectProfile->id }});">Ongoing</button>
                                          {   <button type="button" 
                                             id="online_class_audio_{{ $subjectProfile->id }}" onclick="GointScheduledClassaudio({{ $subjectProfile->id }});">Ongoing.
                                         </button> 

                                    </form>


                                      @endrole

                                                     @role(['student'])
                                                     <span style="color: red">
                                                         Tecaher Did not start  the class
                                                     </span>
                                                     @endrole   
                                                    @endif

                                                @elseif(isset($scheduledData[$subjectProfile->id][0]) && $scheduledData[$subjectProfile->id][11] == 4)

                                                    {{ 'Completed' }}

                                                @elseif(isset($scheduledData[$subjectProfile->id][0]) && $scheduledData[$subjectProfile->id][11] == 6)

                                                  
                                                    <form method="post" action="" id="forms{{ $subjectProfile->id }}">
                                                            <input type="hidden" name="_token" value="{{csrf_token()}}">
                                                            @if(isset($scheduledData[$subjectProfile->id][23]))
                                                            <input type="hidden" name="class_scheduled_id" value="{{ $scheduledData[$subjectProfile->id][23] }}">
                                                            @else
                                                            <input type="hidden" name="class_scheduled_id" value="">
                                                            @endif
                                                            <span><i class="icon-eye-open"></i></span>
                                                        <button type="button" id="online_class_{{ $subjectProfile->id }}" onclick="GointScheduledClass({{ $subjectProfile->id }});">Started</button>


                                               { <button type="button" id="online_class_audio_{{ $subjectProfile->id }}" onclick="GointScheduledClassaudio({{ $subjectProfile->id }});">Started.</button> 

                                                        </form>

                                                @else
                                                 @role(['super-admin','admin','teacher'])
                                                <form method="post" action="" id="formq{{ $subjectProfile->id }}">
                                                <input type="hidden" name="_token" value="{{csrf_token()}}">

                                                <input type="hidden" name="class_opening_date" value="{{ $date }}">
                                                <input type="hidden" name="class_opening_day" value="{{ $key }}">
                                                <input type="hidden" name="class_routine_time" value="{{ $PeriodList[$loop->index] }}">
                                                
                                                <input type="hidden" name="campus_id" value="{{ $subjectProfile->campus }}">
                                                <input type="hidden" name="institute_id" value="{{ $subjectProfile->institute }}">
                                                
                                                @if($timetableCount>0)
                                                <input type="hidden" name="class_subject" value="{{ $subjectProfile->subject_name }}">
                                                <input type="hidden" name="class_subject_id" value="{{ $subjectProfile->id }}">
                                                @else
                                                <input type="hidden" name="class_subject" value="">
                                                <input type="hidden" name="class_subject_id" value="">
                                                @endif

                                                <input type="hidden" name="academic_level_id" value="{{ $ClassName->academics_level_id }}">


                                                <input type="hidden" name="academic_section_id" value="{{ $SectionName->id }}">
                                                <input type="hidden" name="academic_section" value="{{ $SectionName->section_name }}">

                                        @if($timetableCount>0)
                                        <input type="hidden" name="class_teacher_id" value="{{$teacherProfile->id}}">
                                         <input type="hidden" name="class_teacher_name" value='{{$teacherProfile->first_name." ".$teacherProfile->middle_name." ".$teacherProfile->last_name}}'>
                                         @else
                                         <input type="hidden" name="class_teacher_id" value="">
                                         <input type="hidden" name="class_teacher_name" value=''>
                                         @endif
                                        <input type="hidden" name="class_total_student" value='{{ $studentList }}'>
                                        <input type="hidden" name="class_status" value='2'>
                                        <input type="hidden" name="academic_shift_id" value='{{ $shift }}'>
                                        <input type="hidden" name="academic_class" value="{{ $ClassName->batch_name }}">
                                        <input type="hidden" name="academic_class_id" value="{{ $ClassName->id }}">

                                    @if(isset($topicNameList[$subjectProfile->id]) && $timetableCount>0)
                                        <input type="hidden" name="class_topic_name" value="{{ $topicNameList[$subjectProfile->id] }}">
                                    @else
                                         <input type="hidden" name="class_topic_name" value="">
                                    @endif
                                                
                                                
                                                
                                <button type="button" id="schedule_status_update_{{ $subjectProfile->id }}" onclick="addFunction({{ $subjectProfile->id }});">UnScheduled</button>
                                                </form>
                                                @endif
                                            @endif
                                            @endrole

                                             @role(['student'])
                                             

                                              @endrole

                                            
                                        </td>
                                    </tr>
                                    
                                    @endforeach
                                @endif
                            @endforeach
                        @endif
                    @endfor
                    </tbody>
                </table>
                @else
                    <div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <h5><i class="fa fa-warning"></i> No result found. </h5>
                    </div>
                @endif
            </div>
        <div class="link" style="float: right"></div>
    </div>
    
</div>
<!-- DataTables -->
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>

<script src="{{ URL::asset('js/all.js') }}"></script>
<script type="text/javascript">
$(document).ready(function(){

    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });

    // request for parent list using batch section id
    $('form.class_schedule_status_form').on('submit', function (e) {
        e.preventDefault();
        // ajax request
        $.ajax({
            url: "{{ url('/onlineacademics/onlineacademic/ClassSchedule') }}",
            type: 'POST',
            cache: false,
            data: $('form.class_schedule_status_form').serialize(),
            datatype: 'html',
            // datatype: 'application/json',

            beforeSend: function() {
                // show waiting dialog
                waitingDialog.show('Loading...');
            },

            success:function(data){
                // hide waiting dialog
                waitingDialog.hide();
                // refresh attendance container row
                $('#class_schedule_list_container').html('');
                $('#class_schedule_list_container').append(data);
            },
            error:function(){
                // sweet alert
                swal("Error", 'Unable to load data form server', "error");
            }
        });
    });


    // Update Data
    jQuery('#class_schedule_status').on("submit", function(arg){
        arg.preventDefault();
       
        $.ajax({
            url: "{{ url('/onlineacademics/onlineacademic/ClassSchedule') }}",
            type: 'POST',
            cache: false,
            data: $('form.class_schedule_status_form').serialize(),
            datatype: 'html',
            // datatype: 'application/json',

            beforeSend: function() {
                // show waiting dialog
                waitingDialog.show('Loading...');
            },

            success:function(data){
                // hide waiting dialog
                waitingDialog.hide();
                // refresh attendance container row
                $('#class_schedule_list_container').html('');
                $('#class_schedule_list_container').append(data);
            },
            error:function(){
                // sweet alert
                swal("Error", 'Unable to load data form server', "error");
            }
        });
    });

    
});

function addFunction(id) {
    //document.getElementById('formq'+id).submit();
    var formId = 'formq'+id;
    $.ajax({
        url: "{{ url('/onlineacademics/onlineacademic/ClassSchedule') }}",
        type: 'POST',
        cache: false,
        data: $('form#'+formId).serialize(),
        //datatype: 'html',
        datatype: 'application/json',

        beforeSend: function() {
            // show waiting dialog
            waitingDialog.show('Loading...');
        },

        success:function(data){
            // hide waiting dialog
            waitingDialog.hide();
            // refresh attendance container row
            //$('#class_schedule_list_container').html('');
            //$('#class_schedule_list_container').append(data);


            if(data.status == 'Error'){
                swal("Error", 'Scheduled All Ready Exit', "error");
            }
            else{
                swal("Success", 'Scheduled Created Successfully', "success");
                //var id = data.subject_id;
                //$("button#schedule_status_update_"+data.subject_id).css('color','');
                //$('#schedule_status_update_'+data.subject_id).hide();
                $('#schedule_status_update_'+data.subject_id).html('Scheduled');
            }


        },
        error:function(){
            // sweet alert
            swal("Error", 'Unable to load data form server', "error");
        }
    });  
}


function GointScheduledClass(id) {
    var formId = 'forms'+id;
    $.ajax({
        url: "{{ url('/onlineacademics/onlineacademic/OnlineScheduleClass') }}",
        type: 'POST',
        cache: false,
        data: $('form#'+formId).serialize(),
        //datatype: 'html',
        datatype: 'application/json',

        beforeSend: function() {
            // show waiting dialog
            waitingDialog.show('Loading...');
        },
        success:function(data){
            // hide waiting dialog
            waitingDialog.hide();

            if(data.tabActive == 'onlineclass'){
                //$('#onlineclass').html('active');
                //window.open('http://127.0.0.1:8000/onlineacademics/onlineacademic/onlineclass');
                //var url = "http://127.0.0.1:8000/onlineacademics/onlineacademic/onlineclass";
                //window.open(url, "_blank");

                var ScheduleId = data.class_scheduled_id;

                window.location.href ="{{ url('/onlineacademics/onlineacademic/LiveClassScheduled/') }}/"+ScheduleId;
            }   
        },
        error:function(){
            // sweet alert
            swal("Error", 'Unable to load data form server', "error");
        }
    });  
}


function GointScheduledClassaudio(id) {
    var formId = 'forms'+id;
    $.ajax({
        url: "{{ url('/onlineacademics/onlineacademic/OnlineScheduleClass') }}",
        type: 'POST',
        cache: false,
        data: $('form#'+formId).serialize(),
        //datatype: 'html',
        datatype: 'application/json',

        beforeSend: function() {
            // show waiting dialog
            waitingDialog.show('Loading...');
        },
        success:function(data){
            // hide waiting dialog
            waitingDialog.hide();

            if(data.tabActive == 'onlineclass'){
                //$('#onlineclass').html('active');
                //window.open('http://127.0.0.1:8000/onlineacademics/onlineacademic/onlineclass');
                //var url = "http://127.0.0.1:8000/onlineacademics/onlineacademic/onlineclass";
                //window.open(url, "_blank");

                var ScheduleId = data.class_scheduled_id;

                window.location.href ="{{ url('/onlineacademics/onlineacademic/LiveClassScheduledbroadcast/') }}/"+ScheduleId;
            }   
        },
        error:function(){
            // sweet alert
            swal("Error", 'Unable to load data form server', "error");
        }
    });  
}


$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();   
});


</script>
