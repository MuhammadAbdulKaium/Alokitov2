@extends('admission::layouts.admission-layout')
{{--styles--}}
@section('styles')
	<!-- page styles -->
	@yield('page-styles')
@endsection

<!-- page content -->
@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1> <i class = "fa fa-money" aria-hidden="true"></i> Manage Assessment </h1>
			<ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Assessment</a></li>
				<li class="active">Grade Book</li>
			</ul>
		</section>
		<section class="content">
			@if(Session::has('success'))
				<div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
				</div>
			@elseif(Session::has('alert'))
				<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
				</div>
			@elseif(Session::has('warning'))
				<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
				</div>
			@endif

			<div class="panel panel-default">
				<div class="panel-body">
					<div>
						<ul class="nav-tabs margin-bottom nav">
							<li class="my-tab {{$page=='grade-book'?'active':''}}"><a  href="{{url('/admission/assessment/grade-book')}}">Grade Book</a></li>
							<li class="my-tab {{$page=='result'?'active':''}}"><a href="{{url('/admission/assessment/result')}}">Result</a></li>
							<li class="my-tab {{$page=='setting'?'active':''}}"><a href="{{url('/admission/assessment/setting')}}">Setting</a></li>
							<li class="my-tab {{$page=='reports'?'active':''}}"><a href="{{url('/admission/assessment/reports')}}">Reports</a></li>
						</ul>
						{{--page content div--}}
						@yield('page-content')
					</div>
				</div>
			</div>

			<div id="applicant_grade_content_row" class="manage-fees-content-row">
				@if(empty($approvedApplicantList) AND empty($admitApplicantList))
					{{--applicant_grade_content-will-be-displayed-here--}}
					<div class="alert-auto-hide alert alert-info alert-dismissable" style="opacity: 257.188;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<i class="fa fa-info-circle"></i>  Please select the required fields from the search form.
					</div>
				@else
					{{--promo student list--}}
					<div id="promo_student_list">
						@if(count($approvedApplicantList)>0 AND count($admitApplicantList)>0)
							<div class="box box-success box-solid alert-auto-hide">
								<div class="box-header with-border">
									<h3 class="box-title"> Success Students</h3>
								</div>
								<div class="box-body">
									<table class="table table-striped table-bordered table-responsive">
										<thead>
										<tr>
											<th class="text-center">#</th>
											<th class="text-center">
												<img class="profile-user-img img-responsive img-circle" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="height:30px; width: 30px">
											</th>
											<th class="text-center" style="width: 150px;">Application No.</th>
											<th>Name</th>
											<th>Email</th>
											<th class="text-center">Academic Year</th>
											<th class="text-center">Course</th>
											<th class="text-center">Batch</th>
											<th class="text-center">Status</th>

										</tr>
										</thead>
										<tbody>
										@php $i=1; @endphp
										@foreach($approvedApplicantList as $applicantResult)
											{{--applicant ID--}}
											@php $applicant = $applicantResult->application(); @endphp
											{{--checking--}}
											@if(array_key_exists($applicant->id, $admitApplicantList)==false) @continue @endif
											<input type="hidden" name="student_list[{{$applicant->id}}]" value="{{$applicant->application_no}}">
											{{--table row--}}
											<tr>
												<td class="text-center">{{$i++}}</td>
												{{--get applicant photo--}}
												@php $profilePhoto = $applicantResult->document('PROFILE_PHOTO'); @endphp
												<td>
													{{--set applicant photo--}}
													<img class="profile-user-img img-responsive img-circle" src="{{URL::asset($profilePhoto?$profilePhoto->doc_path.'/'.$profilePhoto->doc_name:'assets/users/images/user-default.png')}}" alt="No Image" style="height:30px; width: 30px">
												</td>
												<td class="text-center" style="width:25px">{{$applicant->application_no}}</td>
												<td>
													@php $applicantInfo = $applicant->personalInfo(); @endphp
													<a href="{{url('/admission/application/'.$applicantResult->applicant_id)}}">
														{{$applicantInfo->first_name." ".$applicantInfo->middle_name." ".$applicantInfo->last_name}}
													</a>
												</td>
												<td>{{$applicant->email}}</td>
												<td> {{$applicantResult->academicYear()->year_name}}</td>
												<td>{{$applicantResult->academicLevel()->level_name}}</td>
												<td>{{$applicantResult->batch()->batch_name}}</td>
												@php $admissionStatus = $applicantResult->admission_status; @endphp
												<td class="text-center"><span class="label {{$admissionStatus=='1'?'label-success':'label-warning'}}">{{$admissionStatus=='1'?'Admitted':'Not Admitted'}}</span></td>
											</tr>
											@php $i = ($i++); @endphp
										@endforeach
										</tbody>
									</table>
								</div>
							</div>
						@endif
					</div>
				@endif
			</div>
		</section>
	</div>

	<!-- global modal -->
	<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
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
	<script src="{{ URL::asset('js/jquery.form.js')}}"></script>
	<script>
        $(function () {

            $('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

            $('.my-tab').click(function(){
                $('#applicant_grade_content_row').html('');
            });

            jQuery(document).on('change','.academicChange',function(){
                $('#applicant_grade_content_row').html('');
            });

            // request for batch list using level id
            jQuery(document).on('change','.academicYear',function(){
                $.ajax({
                    url: "{{ url('/academics/find/level') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': $(this).val() }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {},

                    success:function(data){
                        //console.log(data.length);
                        var op ='<option value="0" selected>--- Select Level ---</option>';
                        for(var i=0;i<data.length;i++){
                            // console.log(data[i].level_name);
                            op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                        }

                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');

                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append('<option value="" selected disabled>--- Select Batch ---</option>');

                        // set value to the academic batch
                        $('.academicLevel').html("");
                        $('.academicLevel').append(op);
                    },

                    error:function(){}
                });
            });

            // request for batch list using level id
            jQuery(document).on('change','.academicLevel',function(){
                // ajax request
                $.ajax({
                    url: "{{ url('/academics/find/batch') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': $(this).val() }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {},

                    success:function(data){
                        var op='<option value="" selected>--- Select Batch ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                        }

                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append(op);

                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');
                    },

                    error:function(){}
                });
            });

            // request for section list using batch id
            jQuery(document).on('change','.academicBatch',function(){
                $.ajax({
                    url: "{{ url('/academics/find/section') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': $(this).val() }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {},

                    success:function(data){
                        var op ='<option value="" selected>--- Select Section ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                        }

                        // set value to the academic batch
                        $('.academicSection').html("");
                        $('.academicSection').append(op);
                    },

                    error:function(){}
                });
            });

        });
	</script>
	{{-- page script --}}
	@yield('page-script')
@endsection