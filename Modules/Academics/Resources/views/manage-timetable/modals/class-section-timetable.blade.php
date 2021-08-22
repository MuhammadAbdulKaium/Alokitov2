
@if($batchSectionPeriodId>0)
	@if($allClassPeriods->count()>0)
		<div class="row">
			<div class="col-md-12">
				<h3 class="text-center text-bold bg-green">Class TimeTable</h3>
				<table class="table table-bordered text-center table-striped">
					<thead>
					<tr class="bg-green">
						<th>#</th>
						@if($allClassPeriods)
							@foreach($allClassPeriods as $period)
								<th>{{$period->period_name}}<br/>
									<span style="font-size: 10px">({{$period->period_start_hour % 10== $period->period_start_hour?'0':''}}{{$period->period_start_hour}}:{{$period->period_start_min % 10== $period->period_start_min?'0':''}}{{$period->period_start_min}} {{$period->period_start_meridiem==0 ? 'AM' : 'PM'}} - {{$period->period_end_hour % 10== $period->period_end_hour?'0':''}}{{$period->period_end_hour}}:{{$period->period_end_min % 10== $period->period_end_min?'0':''}}{{$period->period_end_min}} {{$period->period_end_meridiem==0 ? 'AM' : 'PM'}})</span></th>
							@endforeach
						@endif
					</tr>
					</thead>

					<tbody>
					@php $days = array(1=>'Saturday',2=>'Sunday',3=>'Monday',4=>'Tuesday',5=>'Wednesday',6=>'Thursday',7=>'Friday'); @endphp
					@for($i=1; $i<=count($days); $i++)
						<tr class="{{$i%2==0?'bg-gray':'bg-gray-active'}}">
							{{--day name--}}
							<td>{{$days[$i]}}</td>

							{{--period settings--}}
							@foreach($allClassPeriods as $period)
								{{--checking period type--}}
								@if($period->is_break==0)
									<td>
										<input id="class_day_{{$i.$period->id}}" type="hidden" value="{{$i}}"/>
										<input id="class_period_{{$i.$period->id}}" type="hidden" value="{{$period->id}}"/>

										@php
											$sortedTimetableProfile = sortTimetable($i, $period->id, $allTimetables);
											$timetableCount = $sortedTimetableProfile->count();
										@endphp

										{{--Checking timetable count--}}
										@if($timetableCount>0)
											@php
												$timetableProfile = (array) $sortedTimetableProfile->toArray();
												$timetableProfile = reset($timetableProfile);
												$teacherProfile = $period->teacher($timetableProfile['teacher']);
												// $classSubjectProfile = $period->subject($timetableProfile['subject']);
												$classSubjectProfile = findClassSubject($timetableProfile['subject']);;
												$subjectProfile = $classSubjectProfile?$classSubjectProfile->subject():null;
											@endphp
											<input id="timetable_id_{{$i.$period->id}}" type="hidden" value="{{$timetableProfile['id']}}"/>
										@else
											<input id="timetable_id_{{$i.$period->id}}" type="hidden" value="0"/>
										@endif


										<div id="class_subject_list_{{$i.$period->id}}" class="form-group">
											<label class="control-label" for="class_subject">Subject</label>
											<select id="class_subject_{{$i.$period->id}}" class="form-control class_subject hide" name="class_subject">
												<option value="" selected disabled>Select Subject</option>
												@foreach($allClassSubjects as $classSubject)
													<option  value="{{$classSubject->id}}" @if($timetableCount>0){{$classSubject->id==$timetableProfile['subject']?'selected':''}}@endif >{{$classSubject->subject()->subject_name}}</option>
												@endforeach
											</select>
											<br/>

											<span id="class_subject_name_{{$i.$period->id}}" class="classSubject {{$timetableCount>0?'':'label alert-danger'}}">
											@if($timetableCount>0){{$subjectProfile?$subjectProfile->subject_name:'(removed)'}} @else No Subject @endif
										</span>
											<div class="help-block"></div>
										</div>
										<div  class="form-group">
											<label class="control-label" for="subject_teacher">Teacher</label>
											<select id="subject_teacher_{{$i.$period->id}}" class="form-control subject_teacher hide" name="subject_teacher">
												<option value="" selected disabled>Select Teacher</option>
											</select>
											<a style="margin-top: 5px;" id="subject_teacher_submit_{{$i.$period->id}}" class="btn btn-success btn-timetable-submit hide">Submit</a>
											<br/>
											<span id="subject_teacher_name_{{$i.$period->id}}" class="{{$timetableCount>0?'':'label alert-warning'}}">
										@if($timetableCount>0)
													{{$teacherProfile->first_name." ".$teacherProfile->middle_name." ".$teacherProfile->last_name}}
													<input type="hidden" id="subject_teacher_id_{{$i.$period->id}}" value="{{$timetableProfile['teacher']}}"/>
												@else
													No teacher
												@endif
									</span>
											<div class="help-block"></div>
										</div>
									</td>
								@else
									<th>
										<br/>
										<br/>
										{{$period->period_name}}<br/>
										<span style="font-size: 10px">({{$period->period_start_hour % 10== $period->period_start_hour?'0':''}}{{$period->period_start_hour}}:{{$period->period_start_min % 10== $period->period_start_min?'0':''}}{{$period->period_start_min}} {{$period->period_start_meridiem==0 ? 'AM' : 'PM'}} - {{$period->period_end_hour % 10== $period->period_end_hour?'0':''}}{{$period->period_end_hour}}:{{$period->period_end_min % 10== $period->period_end_min?'0':''}}{{$period->period_end_min}} {{$period->period_end_meridiem==0 ? 'AM' : 'PM'}})</span>
									</th>
								@endif
							@endforeach
						</tr>
					@endfor
					</tbody>
				</table>
			</div>
		</div>
	@else
		<div class=" col-md-10 col-md-offset-1 text-center alert bg-primary text-warning" style="margin-bottom:0px;">
			<i class="fa fa-warning"></i> No record found.
		</div>
	@endif
@else
	<div class=" col-md-10 col-md-offset-1 text-center alert bg-primary text-warning" style="margin-bottom:0px;">
		<i class="fa fa-warning"></i> No Period Category is assigned for this class - section.
	</div>
@endif

<script>
    $(function() {
        // request for section list using batch and section id
        jQuery(document).on('change','.class_subject',function(){
            var class_subject = $(this).attr('id').replace('class_subject_','');
            var class_subject_id = $(this).val();
            var subject_teacher_id = $('#subject_teacher_id_'+class_subject).val();

            var op="";
            $.ajax({
                url: '/academics/find/teacher/class/subject/'+class_subject_id,
                type: 'GET',
                cache: false,
                datatype: 'application/json',

                beforeSend: function() {
                    // statements
                },

                success:function(data){
                    // show teacher list
                    $('#subject_teacher_'+class_subject).removeClass('hide');
                    $('#subject_teacher_submit_'+class_subject).removeClass('hide');
                    $('#subject_teacher_name_'+class_subject).addClass('hide');

                    // make teacher list
                    op+='<option value="" selected disabled>--- Select Teacher ---</option>';
                    // make select option
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
                    }
                    // set value to the academic batch
                    $('#subject_teacher_'+class_subject).html("");
                    $('#subject_teacher_'+class_subject).append(op);
                },

                error:function(){
                    // statements
                },
            });
        });



        $('.btn-timetable-submit').click(function () {

            var myId = $(this).attr('id').replace('subject_teacher_submit_','');
            var subject_teacher = $('#subject_teacher_'+myId);
            var class_subject = $('#class_subject_'+myId);

            var teacher = subject_teacher.val();
            var subject = class_subject.val();
            var level = $('#level').val();
            var batch = $('#batch').val();
            var section = $('#section').val();
            var period = $('#class_period_'+myId).val();
            var shift = $('#shift').val();
            var day = $('#class_day_'+myId).val();
            var timetable = $('#timetable_id_'+myId).val();
            var subject_name = class_subject.find("option:selected").text();
            var teacher_name = subject_teacher.find("option:selected").text();

            // checking
            if(teacher && subject){
                // ajax request
                $.ajax({
                    url: '/academics/timetable/store/timetable',
                    type: 'GET',
                    cache: false,
                    data: {'teacher': teacher, 'subject':subject,'level': level, 'batch':batch,'section': section, 'shift':shift, 'day':day, 'period':period, 'timetable':timetable},
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        if(data.status=='success'){

                            $('#timetable_id_'+myId).val(data.timetable_id);
                            $('#subject_teacher_'+myId).addClass('hide');
                            $('#subject_teacher_submit_'+myId).addClass('hide');
                            $('#class_subject_'+myId).addClass('hide');

                            $('#subject_teacher_name_'+myId).removeClass('hide');
                            $('#class_subject_name_'+myId).removeClass('hide');

                            if($('#class_subject_name_'+myId).hasClass('alert-danger')){
                                $('#class_subject_name_'+myId).removeClass('alert-danger');
                            }
                            if($('#subject_teacher_name_'+myId).hasClass('alert-warning')){
                                $('#subject_teacher_name_'+myId).removeClass('alert-warning');
                            }
                            // set teacher name
                            $('#class_subject_name_'+myId).text(subject_name);
                            $('#subject_teacher_name_'+myId).text(teacher_name);
                        }else if(data.status=='test'){
                            alert(JSON.stringify(data.result));
                        }else{
                            alert('unable to perform the action')
                        }
                    },

                    error:function(data){
                        alert(JSON.stringify(data));
                    }
                });
            }else{
                alert('Please check all inputs are selected ?')
            }
        });

        /// class subject on double click action
        $(".classSubject").dblclick(function(){
            var myId = $(this).attr('id').replace('class_subject_name_', '');
            $('#class_subject_'+myId).removeClass('hide');
            $('#class_subject_name_'+myId).addClass('hide');
        });

    });
</script>