<div class="panel panel-default">
    <div class="panel-body">

        @if($employeeInfo->singelAttachment("PROFILE_PHOTO"))
            <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$employeeInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:100px;height:100px">
        @else
            <img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:100px;height:100px">
        @endif

        <a class="btn center-block" href="/employee/photo/edit/{{$employeeInfo->id}}" title="Change Profile Picture" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-sm">
            <i class = "fa fa-pencil-square" aria-hidden="true"></i> Change Picture
        </a>
        <p class="text-muted text-center text-bold">({{$employeeInfo->user()->roles()->count()>0?$employeeInfo->user()->roles()->first()->display_name:'No Role'}})</p>
        <h4 class="profile-username text-center">{{$employeeInfo->first_name." ".$employeeInfo->last_name}}</h4>
        <h5 class="text-center">
            {{--Checking Status--}}
            @if($employeeInfo->status==1)
                <span class="label label-success">ACTIVE</span>
            @else
                <span class="label label-warning">Retired</span>
            @endif
        </h5>
        <hr>
        <strong>Employee Id</strong>
        <h2 class="text-success"><b>{{$employeeInfo->user()->username}}</b></h2>
{{--        <strong>--}}
{{--            Email/Login Id--}}
{{--            <a href="/employee/email/edit/{{$employeeInfo->id}}" title="Change Email/Login ID" data-target="#globalModal" data-toggle="modal"><i class="fa fa-pencil-square fa-lg"></i></a>--}}
{{--        </strong>--}}
{{--        <p class="text-muted">{{$employeeInfo->email}}</p>--}}
        <strong>Mobile No </strong>
        <p class="text-muted">{{$employeeInfo->phone}}</p>

        <div class="progress sm" style="background-color:#efefef">
            <div style="width: 100%;" class="progress-bar progress-bar-green"></div>
        </div>
        {{-- <a id="export-pdf" class="btn btn-app" href="/employee/report/profile/{{$employeeInfo->id}}" target="_blank"><i class="fa fa-file-pdf-o"></i> Profile PDF</a> --}}
        <a class="btn btn-app" href="#" target="_blank"><i class="fa fa-hand-o-up"></i> Attendance</a>
        {{--<a class="btn btn-app" href="{{url('academics/timetable/teacherTimeTable/report/'.$employeeInfo->id)}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class="fa fa-calendar-o"></i> Timetable</a>--}}
        {{--<a class="btn btn-app" target="_blank" href="{{url('academics/timetable/teacherTimeTable/report/'.$employeeInfo->id)}}"><i class="fa fa-calendar-o"></i> Timetable</a>--}}

            <a class="btn btn-app" href="/academics/timetable/teacher/{{$employeeInfo->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">
                <i class="fa fa-calendar-o"></i> Timetable
            </a>

        {{--<a class="btn btn-app" target="_blank" href="{{url('academics/timetable/teacherTimeTable/report/'.$employeeInfo->id)}}"><i class="fa fa-calendar-o"></i> Timetable</a>--}}
    </div>
    <!--./pannel-body-->
</div>
<!--./pannel-default-->
