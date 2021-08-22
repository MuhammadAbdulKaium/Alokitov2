@extends('admission::layouts.admission-layout')
<!-- page content -->
@section('content')
	{{--personale info--}}
	@php $personalInfo = $applicantProfile->personalInfo();  @endphp
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class = "fa fa-user" aria-hidden="true"></i> Applicant Details |
				<small>{{$personalInfo->first_name." ".$personalInfo->middle_name." ".$personalInfo->last_name}}</small>
			</h1>
			<ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Enquiry</a></li>
				<li><a href="#">Manage Applicant</a></li>
				<li class="active">{{$personalInfo->first_name." ".$personalInfo->middle_name." ".$personalInfo->last_name}}</li>
			</ul>
		</section>
		<section class="content">
			<div class="row">
				<div class ="col-sm-3">
					<div class="box box-solid">
						<div class="box-body box-profile">
							{{--get applicant photo--}}
							@php $profilePhoto = $applicantProfile->document('PROFILE_PHOTO'); @endphp
							{{--set applicant photo--}}
							<img class="profile-user-img img-responsive img-circle" src="{{URL::asset($profilePhoto?$profilePhoto->doc_path:'assets/users/images/user-default.png')}}" alt="No Image" style="height:100px;">
							{{--appliant photo change button--}}
							<a class="btn center-block" href="/admission/applicant/{{$applicantProfile->id}}/photo/edit" title="Change Profile Picture" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-sm"><i class = "fa fa-pencil-square" aria-hidden="true"></i> Change Picture</a>
							{{--applicant name--}}
							<h3 class="profile-username text-center"> {{$personalInfo->first_name." ".$personalInfo->middle_name." ".$personalInfo->last_name}} </h3>
							{{--applicant status--}}
							<h4 class="text-muted text-center">
								{{--Applicant status--}}
								@php $applicationStatus = $applicantProfile->application_status; @endphp
								@if($applicationStatus==1)
									<span class="label label-info">Active</span>
								@elseif($applicationStatus==2)
									<span class="label label-primary">Waiting</span>
								@elseif($applicationStatus==3)
									<span class="label label-danger">Disapproved</span>
								@elseif($applicationStatus==4)
									<span class="label label-success">Approved</span>
								@else
									<span class="label label-primary">Pending</span>
								@endif
							</h4>

							<ul class="list-group">
								<li class="list-group-item">
									<b>Application No</b>
									<span class="pull-right">{{$applicantProfile->application_no}}</span>
								</li>
								<li class="list-group-item">
									<b>
										Email
										<a id="edit-email" href="/admission/applicant/{{$applicantProfile->id}}/email/edit" title="Change Email/Login ID" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md"><i class="fa fa-pencil-square fa-lg"></i></a>
									</b>
									<span class="pull-right">{{$applicantProfile->email}}</span>
								</li>
								<li class="list-group-item">
									<b>Mobile No</b>
									<span class="pull-right">{{$personalInfo->phone}}</span>
								</li>
								<li class="list-group-item">
									<b>Payment Status</b>
									@if($applicantProfile->payment_status==1)
										<i class="pull-right label label-success">Paid</i>
									@else
										<i class="pull-right label label-danger">UnPaid</i>
									@endif
								</li>
								<li class="list-group-item text-center">
									<a class="label label-success download" data-key="application" target="_blank" data-id="{{$applicantProfile->id}}">Download Application</a>
								</li>
								@if($applicantProfile->payment_status==1)
									<li class="list-group-item text-center">
										<a class="label label-success download" data-key="admit-card" target="_blank" data-id="{{$applicantProfile->id}}">Download Admit Card</a>
									</li>
								@endif

								@if($applicantResult = $applicantProfile->result())
									@if($applicantResult->applicant_merit_type=='APPROVED')
										<li class="list-group-item text-center">
											<a class="label label-success" href="/admission/admission-letter/{{$applicantProfile->id}}/download">Download Admission Letter</a>
										</li>
									@endif
								@endif
							</ul>
						</div>
					</div>
				</div>
				<div class="col-sm-9">
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
					<div class="nav-tabs-custom">
						<ul id="w1" class="nav nav-tabs">
							<li class="{{$page=='personal'?'active':''}}">
								<a href="{{url('/admission/applicant/'.$applicantProfile->id.'/personal')}}"><i class = "fa fa-user" aria-hidden="true"></i> Personal</a>
							</li>
							{{--<li class="{{$page=='address'?'active':''}}">--}}
								{{--<a href="{{url('/admission/applicant/'.$applicantProfile->id.'/address')}}"><i class = "fa fa-map-marker" aria-hidden="true"></i> Address</a>--}}
							{{--</li>--}}
							<li class="{{$page=='apply'?'active':''}}">
								<a href="{{url('/admission/applicant/'.$applicantProfile->id.'/apply')}}"><i class = "fa fa-file-o" aria-hidden="true"></i> Apply</a>
							</li>
							<li class="{{$page=='document'?'active':''}}">
								<a href="{{url('/admission/applicant/'.$applicantProfile->id.'/document')}}"><i class = "fa fa-file-o" aria-hidden="true"></i> Documents</a>
							</li>
							<li class="pull-right dropdown">
								<a class="dropdown-toggle" href="#" data-toggle="dropdown">
									<i class = "fa fa-gear" aria-hidden="true"></i> Action <b class="caret"></b>
								</a>
								<ul id="w2" class="dropdown-menu">
									<li>
										<a href="#" data-confirm="Are you sure to send Letter to this applicant?">Admission Letter</a>
									</li>
									<li>
										<a href="#" data-confirm="Are you sure to Approve this applicant?">Approve</a>
									</li>
									<li>
										<a href="#" data-confirm="Are you sure to disapprove this applicant?">Waiting</a>
									</li>
									<li>
										<a href="#" data-confirm="Are you sure to disapprove this applicant?">Disapprove</a>
									</li>
									<li>
										<a href="#" data-confirm="Are you sure to delete this applicant?">Delete</a>
									</li>
								</ul>
							</li>
						</ul>
						<div class="tab-content">
							{{--applicant profile page content--}}
							@yield('profile-content')
						</div>
					</div><!--./nav-tabs-custom-->

					{{-- Follow ups --}}
					<div class="box box-solid">
						<div class="extra-div">
							<div class="box-header with-border">
								<h3 class="box-title">Follow-ups</h3>
								<div class="box-tools">
									<a class="btn btn-success btn-sm pull-right" href="#" data-target="#globalModal" data-toggle="modal">
										<i class = "fa fa-plus-square" aria-hidden="true"></i> Add
									</a>
								</div>
							</div>
						</div>
						<div class="box-body table-responsive">
							<div class="alert bg-warning text-warning">
								<i class="fa fa-warning"></i>
								No record found.
							</div>
						</div>
					</div>
				</div>
			</div><!--./row-->
		</section>
	</div>
@endsection

@section('scripts')
	<script>
        $(document).ready(function () {

            $('.download').click(function () {
                // get data-key
                var applicant_id = $(this).attr('data-id');
                var downloadType = $(this).attr('data-key');
                var url = '/admission/download/'+downloadType+'/'+applicant_id;
                // add href to the
                $(this).attr('href', url);
            });

        });
	</script>
@endsection
