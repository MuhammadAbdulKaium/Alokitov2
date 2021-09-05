<div class="panel panel-default" xmlns="http://www.w3.org/1999/html">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-2">
				@if($personalInfo->singelAttachment("PROFILE_PHOTO"))
					<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$personalInfo->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:100px;height:100px">
				@else
					<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:100px;height:100px">
				@endif
				<a class="btn center-block" href="/student/profile/photo/{{$personalInfo->id}}" title="Change Profile Picture" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-sm"><i class = "fa fa-pencil-square" aria-hidden="true"></i> Change Picture</a>
				<h4 class="profile-username text-center">{{ $personalInfo->title." ".$personalInfo->first_name." ".$personalInfo->middle_name." ".$personalInfo->last_name }}</h4>
				{{--				<p class="text-muted text-center text-bold">({{$personalInfo->user()->roles()->count()>0?$personalInfo->user()->roles()->first()->display_name:'No Role'}})</p>--}}
								{{-- <h5 class="text-center"><span class="label label-success">ACTIVE</span></h5> --}}
								{{--student enrollment--}}
								@php $enrollment = $personalInfo->enroll(); @endphp
			</div>
			<div class="col-sm-1"></div>
			<div class="col-md-2">
				<strong>Roll No.</strong>
				<p class="text-muted">{{$enrollment->gr_no}}</p>
				@php $division = null; @endphp
				@if($divisionInfo = $enrollment->batch()->get_division())
					@php $division = " (".$divisionInfo->name.") "; @endphp
				@endif
				<strong>Enroll Details</strong>
				<p class="text-muted">
					<strong>Level: </strong> @if($enrollment->level()) {{$enrollment->level()->level_name}} @endif <br/>
					<strong>Class: </strong> @if($enrollment->batch()) {{$enrollment->batch()->batch_name.$division}} @endif <br/>
					<strong>Form: </strong> @if($enrollment->section()) {{$enrollment->section()->section_name}} @endif </p>
			</div>
			
			<div class="col-md-2">
				@if($personalInfo->getWaiverList()->count()>0)
					<strong>Waiver Details</strong>
					<p class="text-muted">
						@foreach($personalInfo->getWaiverList() as $waiver)
							@if($waiver->waiver_type==1)
								<strong>General: </strong> {{$waiver->value}} @if($waiver->type==1) %  @else .TK @endif <br>
							@endif

							@if($waiver->waiver_type==2)
								<strong>Upbritti:</strong> {{$waiver->value}} @if($waiver->type==1) %  @else .TK @endif <br>
							@endif
							@if($waiver->waiver_type==3)
								<strong>Scholarship: </strong> {{$waiver->value}} @if($waiver->type==1) %  @else .TK @endif
							@endif
						@endforeach
					</p>
				@endif
{{--				<strong>--}}
{{--					Username--}}
{{--					--}}{{--<a id="edit-email" href="/student/profile/email/{{$personalInfo->id}}" title="Change Email/Login ID" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">--}}
{{--					--}}{{--<i class="fa fa-pencil-square fa-lg"></i>--}}
{{--					--}}{{--</a>--}}
{{--				</strong>--}}
{{--				<p class="text-muted">{{$personalInfo->user()->username}}</p>--}}
				<strong>
					Student Number
					<a id="edit-email" href="/student/profile/email/{{$personalInfo->id}}" title="Change Email/Login ID" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
						<i class="fa fa-pencil-square fa-lg"></i>
					</a>
				</strong>
				<h1 class="cadet-number" style="margin: 2px;color: #117511;"><b>{{$personalInfo->user()->username}}</b></h1>
				<strong>Mobile No</strong>
				<p class="text-muted">{{$personalInfo->phone}}</p>
				<strong>Identification Mark</strong>
				<p class="text-muted">{{$personalInfo->identification_mark}}</p>
			</div>
			<div class="col-md-1">
				
			</div>
			<div class="col-md-4">


				<div class="progress sm" style="background-color:#efefef">
					<div style="width: 100%;" class="progress-bar progress-bar-green"></div>
				</div>
				<strong> Reports: </strong> <br/>
				<a class="btn btn-app" href="/student/report/profile/search/{{$personalInfo->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
					<i class="fa fa-hand-o-up"></i> Attendance
				</a>
				<a class="btn btn-app" href="/academics/timetable/studentTimeTable/{{$personalInfo->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">
					<i class="fa fa-calendar-o"></i> Timetable
				</a>
				<a class="btn btn-app" href="/student/id-card/{{$personalInfo->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg">
					<i class="fa fa-id-card"></i> ID Card
				</a>
			</div>
		</div>


	</div>
	<!--./pannel-body-->
</div>
<!--./pannel-default-->
@section('scripts')
	<script src="{{URL::asset('js/jquery.cookie.js')}}" type="text/javascript"></script>
	<script src="{{URL::asset('js/bootstrap-waitingfor.min.js')}}" type="text/javascript"></script>

	<script type = "text/javascript">
        //        $(document).ready(function(){
        //            $.removeCookie('downloadToken', { path: '/' });
        //            jQuery("#student_profile_report").click(function(){
        //
        //                waitingDialog.show('Downloading...');
        //                var id =  window.setInterval(function() {
        //                    var cookie_val =  $.cookie("downloadToken");
        //                    console.log(cookie_val);
        //                    if(cookie_val == 1 && cookie_val != undefined){
        //                        $.removeCookie('downloadToken', { path: '/' });
        //                        waitingDialog.hide();
        //                        stop_interval();
        //                    }
        //                }, 1000);
        //
        //                function stop_interval(){
        //                    clearInterval(id);
        //                }
        //            });
        //
        //
        //        });


	</script>
@endsection