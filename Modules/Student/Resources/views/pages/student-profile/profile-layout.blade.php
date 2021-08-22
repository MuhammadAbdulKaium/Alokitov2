@extends('layouts.master')
@section('style')
	<style>
		h1.cadet-number {
			margin: 2px;
			color: #117511;
		}
	</style>

@endsection
@section('content')
	<div class="content-wrapper">
	   <section class="content-header">
	      <h1>
	         <i class = "fa fa-eye" aria-hidden="true"></i> View Cadet | <small>{{$personalInfo->title." ". $personalInfo->first_name." ".$personalInfo->middle_name." ".$personalInfo->last_name }}</small>
	      </h1>
	      <ul class="breadcrumb">
	         <li><a href="/"><i class="fa fa-home"></i>Home</a></li>
	         <li><a href="/student">Cadet</a></li>
	         <li><a href="/student/manage/">Manage Cadet</a></li>
	         <li class="active">{{ $personalInfo->first_name." ".$personalInfo->middle_name." ".$personalInfo->last_name }}</li>
	      </ul>
	   </section>
	   <section class="content">
{{--		   @include('student::pages.student-profile.includes.profile-sidebar')--}}
	      <div class="row">
	         <div class="col-md-12">
	         	@include('student::pages.student-profile.includes.profile-sidebar')
	         </div>
	         <div class="col-md-12">
		        @if(Session::has('success'))
					<div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
					</div>
				@elseif(Session::has('warning'))
					<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
					</div>
				@endif
	           	<div class="panel panel-default">
					<div class="panel-body">
					    <div id="user-profile">
						    <ul id="w2" class="nav-tabs margin-bottom nav">
						      <li class="@if($page == 'personal')active @endif"><a href="/student/profile/personal/{{$personalInfo->id}}">Personal</a></li>
						      <li class="@if($page == 'addresses')active @endif"><a href="/student/profile/addresses/{{$personalInfo->id}}">Address</a></li>
						      <li class="@if($page == 'guardians')active @endif"><a href="/student/profile/guardians/{{$personalInfo->id}}">Family</a></li>
							<li class="@if($page == 'academics')active @endif"><a href="/student/profile/academic/{{$personalInfo->id}}">Academics</a></li>
								<li class="@if($page == 'academics')active @endif"><a href="/student/profile/academic2/{{$personalInfo->id}}">Academics.</a></li>
						      <li class="@if($page == 'performance')active @endif"><a href="/student/profile/factor/{{$personalInfo->id}}/1">Performance (Co-Curricular)</a></li>
						      <li class="@if($page == 'performanceExtra')active @endif"><a href="/student/profile/factor/{{$personalInfo->id}}/9">Performance (Extra-Curricular)</a></li>
							  <li class="@if($page == 'psychology')active @endif"><a href="/student/profile/factor/{{$personalInfo->id}}/2">Psychology</a></li>
							  <li class="@if($page == 'discipline')active @endif"><a href="/student/profile/factor/{{$personalInfo->id}}/4">Discipline</a></li>
						      <li class="@if($page == 'health')active @endif"><a href="/student/profile/factor/{{$personalInfo->id}}/5">Health</a></li>
						      <li class="@if($page == 'hobby')active @endif"><a href="/student/profile/hobby/{{$personalInfo->id}}">Hobby</a></li>
						      <li class="@if($page == 'aim')active @endif"><a href="/student/profile/aim/{{$personalInfo->id}}">Aim</a></li>
						      <li class="@if($page == 'dream')active @endif"><a href="/student/profile/dream/{{$personalInfo->id}}">Dream</a></li>
						      <li class="@if($page == 'idol')active @endif"><a href="/student/profile/idol/{{$personalInfo->id}}">Idol</a></li>
							  <li class="@if($page == 'achievement')active @endif"><a href="/student/profile/achievement/{{$personalInfo->id}}">Achievement</a></li>
							  <li class="@if($page == 'documents')active @endif"><a href="/student/profile/documents/{{$personalInfo->id}}">Documents</a></li>
							  <li class="@if($page == 'photo') active @endif"><a href="/student/profile/photos/{{$personalInfo->id}}">Photo</a></li>
							  <li class="@if($page == 'fees')active @endif"><a href="/student/profile/fees/{{$personalInfo->id}}">Fees</a></li>
							  <li class="@if($page == 'remarks')active @endif"><a href="/student/profile/remarks/{{$personalInfo->id}}">Remarks</a></li>
							  <li class="@if($page == 'history')active @endif"><a href="/student/profile/history/{{$personalInfo->id}}">History</a></li>
{{--								<li class="@if($page == 'academics')active @endif"><a href="/student/profile/academic2/{{$personalInfo->id}}">Academics</a></li>--}}
						    </ul>
						    @yield('profile-content')
					    </div>
					</div>
	            </div>
	         </div>
	      </div>
	   </section>
	</div>

    <div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
    	<div class="modal-dialog">
            <div class="modal-content" id="modal-content">
                <div class="modal-body" id="modal-body">
                    <div class="loader">
                        <div class="es-spinner">
                            <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
                        </div>                        
                     </div>
                </div>
            </div>
        </div>
    </div>    
@endsection

@section('scripts')
	<script type = "text/javascript"> 
		jQuery(document).ready(function () {

			jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
				$(this).slideUp('slow', function () {
					$(this).remove();
				});
			});
		});

	</script>

	@yield('profile-scripts')
@endsection