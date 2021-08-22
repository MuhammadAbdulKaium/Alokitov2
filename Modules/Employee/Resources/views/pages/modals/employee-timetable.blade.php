<div class="modal-header">
    <button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title"><i class="fa fa-plus-square"></i> Teacher Timetable</h4>
</div>
<div class="modal-body">
    <div class="row">
        <h4 class="text-center text-bold bg-green-gradient">Employee Details</h4>
        <div class="col-sm-12">
            <table class="table table-bordered table-striped text-center">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Department</th>
                    <th>Designation</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{$teacherProfile->first_name." ".$teacherProfile->middle_name." ".$teacherProfile->last_name}} </td>
                    <td>{{$teacherProfile->email}}</td>
                    <td>{{$teacherProfile->department()->name}}</td>
                    <td>{{$teacherProfile->designation()->name}}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="row">
        <h4 class="text-center text-bold bg-green-gradient">Class TimeTable</h4>
        <div class="col-md-12">
            @if($teacherTimeTables->count()>0)
                <table class="table table-bordered table-striped table-responsive text-center">
                    @php
                        $tdCounter = 0;
                        $days = array(1=>'Saturday',2=>'Sunday',3=>'Monday',4=>'Tuesday',5=>'Wednesday',6=>'Thursday',7=>'Friday');
                    @endphp

                    @for($k=1; $k<=count($days); $k++)
                        @php
                            $timetableCounter = daySorter($k, $teacherTimeTables)->count();
                            if($timetableCounter>$tdCounter){
                                $tdCounter = $timetableCounter;
                            }
                        @endphp
                    @endfor

                    <tbody>
                    @for($i=1; $i<=count($days); $i++)
                        <tr>
                            {{--day name--}}
                            <td><strong>{{$days[$i]}}</strong></td>
                            {{--teacher class periods--}}
                            @php
                                $sortedTimetables = daySorter($i, $teacherTimeTables);
                                $timetableCount = $sortedTimetables->count();
                            @endphp

                            @if($timetableCount>0)
                                @foreach($sortedTimetables as $timetable)
                                    @php
                                        $classSubjectProfile = $timetable->classSubject();
                                        $subjectProfile = $classSubjectProfile->subject();
                                        $period = $timetable->classPeriod();
                                        $batchProfile = $classSubjectProfile->batch();
                                        $sectionProfile = $classSubjectProfile->section()
                                    @endphp
                                    <td>
                                        {{$classSubjectProfile->subject_code}}<br/>
                                        <span style="font-size: 10px">({{$period->period_start_hour % 10== $period->period_start_hour?'0':''}}{{$period->period_start_hour}}:{{$period->period_start_min % 10== $period->period_start_min?'0':''}}{{$period->period_start_min}} {{$period->period_start_meridiem==0 ? 'AM' : 'PM'}} - {{$period->period_end_hour % 10== $period->period_end_hour?'0':''}}{{$period->period_end_hour}}:{{$period->period_end_min % 10== $period->period_end_min?'0':''}}{{$period->period_end_min}} {{$period->period_end_meridiem==0 ? 'AM' : 'PM'}})</span><br/>
                                        <span>
{{$batchProfile->batch_name}}@if($division = $batchProfile->get_division()) - {{$division->name}}@endif  {{'('.$sectionProfile->section_name.')'}}
                                            {{--{{'('.$batchProfile->academicsLevel()->level_name.')'}}--}}
							</span>
                                    </td>
                                @endforeach
                                {{----}}
                                @if($tdCounter > $timetableCount)
                                    @php $myTdCounter = ($tdCounter-$timetableCount); @endphp
                                    @for($j=1; $j<=$myTdCounter; $j++)
                                        <td>-</td>
                                    @endfor
                                @endif
                            @else
                                @if($tdCounter>0)
                                    @for($h=1; $h<=$tdCounter; $h++)
                                        <td>-</td>
                                    @endfor
                                @else
                                    <td>-</td>
                                @endif
                            @endif
                        </tr>
                    @endfor
                    </tbody>
                </table>
            @else
                <div class=" col-md-10 col-md-offset-1 text-center alert bg-warning text-warning" style="margin-bottom:0px;">
                    <i class="fa fa-warning"></i> No record found.
                </div>
            @endif
        </div>
    </div>
</div>

<!--./modal-body-->
<div class="modal-footer">
    @if($teacherTimeTables->count()>0)
        <a class="btn btn-success pull-left"  target="_blank" href="{{url('academics/timetable/teacherTimeTable/report/'.$teacherProfile->id)}}">
            <i class="fa fa-download"></i> Download
        </a>
    @endif
    <button data-dismiss="modal" class="btn btn-info pull-right" type="button">Close</button>
</div>
<!--./modal-footer-->
