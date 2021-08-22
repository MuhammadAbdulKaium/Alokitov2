
@extends('admission::layouts.admission-layout')

@section('styles')
	<!-- DataTables -->
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-info-circle"></i> Admission | <small>Student</small>
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="/student/">Student</a></li>
				<li class="active">Confirm Student</li>
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

			@if(count($approvedApplicantList)>0 AND count($admitApplicantList)>0)
				<form action="{{url('/admission/assessment/result')}}" method="POST">
					<input type="hidden" name="_token" value="{{csrf_token()}}" >
					<div class="box box-solid">
						<div class="box-body">
							<div class="row">
								<div class="col-sm-4">
									<div class="panel panel-default">
										<div class="panel-heading">
											<i class="fa fa-info-circle" aria-hidden="true"></i> Preview Selected Details
										</div>
										<table class="table">
											<colgroup>
												<col style="width:135px">
											</colgroup>
											<tbody>
											<tr>
												<th class="text-center">Academic Year</th>
												<td>{{$academicInfo->year_name}}</td>
											</tr>
											<tr>
												<th class="text-center">Academic Level</th>
												<td>{{$academicInfo->level_name}}</td>
											</tr>
											<tr>
												<th class="text-center">Academic Batch</th>
												<td>{{$academicInfo->batch_name}}</td>
											</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div class="col-sm-3 text-center">
									<div class="panel panel-warning">
										<div class="panel-body">
											<h4> <i class="fa fa-cog"></i> Apply Action </h4>
											<div class="form-group">
												<div class="col-sm-8">
													{{--<input id="confirm_promo_action" name="promo_action_type" value="{{$promoteAction}}" type="hidden">--}}
												</div>
											</div>
											<h4 class="text-yellow"><strong>Admit</strong></h4>
											<h4><i class="fa fa-hand-o-right"></i></h4>
										</div>
									</div>
								</div>
								<div class="col-sm-5">
									<div class="panel panel-default">
										<div class="panel-heading">
											<i class="fa fa-check-circle"></i> Select Promote Details
										</div>
										<div class="panel-body">
											<div class="row">
												<div class="col-sm-4 text-center">
													<label class="control-label" for="academic_year">Academic Year</label>
												</div>
												<div class="col-sm-8">
													<div class="form-group">
														<select id="academic_year" class="form-control academicYear" name="academic_year" required>
															<option value="{{$academicInfo->year_id}}" selected>{{$academicInfo->year_name}}</option>
														</select>
														<div class="help-block"></div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-4 text-center">
													<label class="control-label" for="academic_level">Academic Level</label>
												</div>
												<div class="col-sm-8">
													<div class="form-group">
														<select id="academic_level" class="form-control academicLevel" name="academic_level" required>
															<option value="" disabled>--Select Level--</option>
															{{--checking academic level list--}}
															@if($academicLevels AND $academicLevels->count()>0)
																{{--academic level list looping--}}
																@foreach($academicLevels as $level)
																	<option value="{{$level->id}}" {{$level->id==$academicInfo->level_id?'selected':''}}> {{$level->level_name}} </option>
																@endforeach
															@endif
														</select>
														<div class="help-block"></div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-4 text-center">
													<label class="control-label" for="batch">Academic Batch</label>
												</div>
												<div class="col-sm-8">
													<div class="form-group">
														<select id="batch" class="form-control academicBatch" name="batch" required>
															<option value="" disabled>--Select Batch--</option>
															{{--<option value="{{$academicInfo->batch_id}}" selected>{{$academicInfo->batch_name}}</option>--}}

															{{--checking academic level list--}}
															@if($academicLevels AND $academicLevels->count()>0)
																{{--academic level list looping--}}
																@foreach($academicLevels as $level)
																	{{--checking level id--}}
																	@if($level->id==$academicInfo->level_id) @else @continue @endif
																	{{--find level batch list--}}
																	@php $batchList = $level->batch(); @endphp
																	{{--checking academic batch list--}}
																	@if($batchList AND $batchList->count()>0)
																		{{--academic level list looping--}}
																		@foreach($batchList as $batch)
																			{{--batch name formation--}}
																			@php
																				if ($batch->get_division()) {
																					$batchName = $batch->batch_name . " - " . $batch->get_division()->name;
																				} else {
																					$batchName = $batch->batch_name ;
																				}
																			@endphp
																			<option value="{{$batch->id}}" {{$batch->id==$academicInfo->batch_id?'selected':''}}> {{$batchName}} </option>
																		@endforeach
																	@endif
																@endforeach
															@endif

														</select>
														<div class="help-block"></div>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-4 text-center">
													<label class="control-label" for="section">Academic Section</label>
												</div>
												<div class="col-sm-8">
													<div class="form-group">
														<select id="section" class="form-control academicSection" name="section" required>
															<option value="" selected disabled>--- Select Section ---</option>
														</select>
														<div class="help-block"></div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<!--./box-body-->
						<div class="box-footer">
							<a class="btn btn-primary" href="{{ url()->previous() }}"><i class="fa fa-times" aria-hidden="true"></i>
								Cancel</a>
							<button type="submit" class="btn btn-success pull-right" onclick="return confirm('Are you sure to Continue ?')"><i class="fa fa-floppy-o" aria-hidden="true"></i> Confirm &amp; Submit</button>
						</div>
					</div>

					{{--selected student list--}}
					<div class="panel panel-default">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fa fa-check-square-o"></i> Selected Applicant</h3>
						</div>
						<div class="panel-body">
							<table class="table table-striped text-center table-bordered table-responsive">
								<colgroup>
									<col>
									<col>
									<col>
									<col>
									<col>
									<col>
									<col>
									<col class="text-center">
									<col>
								</colgroup>
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
												{{$applicantInfo->std_name}}
											</a>
										</td>
										<td>{{$applicant->email}}</td>
										<td> {{$applicantResult->academicYear()->year_name}}</td>
										<td>{{$applicantResult->academicLevel()->level_name}}</td>
										<td>{{$applicantResult->batch()->batch_name}}</td>
										@php $meritStatus = $applicantResult->applicant_merit_type; @endphp
										<td class="text-center">
											@if($meritStatus=='Undefined')
												<p class="label label-success text-bold">{{$meritStatus}}</p>
											@elseif($meritStatus=='MERIT')
												<p class="label label-primary text-bold">{{$meritStatus}}</p>
											@elseif($meritStatus=='WAITING')
												<p class="label label-primary text-bold">{{$meritStatus}}</p>
											@elseif($meritStatus=='APPROVED')
												<p class="label label-primary text-bold">{{$meritStatus}}</p>
											@elseif($meritStatus=='DISAPPROVED')
												<p class="label label-primary text-bold">{{$meritStatus}}</p>
											@else
												<p class="label label-danger text-bold">{{$meritStatus}}</p>
											@endif
										</td>
									</tr>
									@php $i = ($i++); @endphp
								@endforeach
								</tbody>
							</table>
						</div>
						<!-- /.box-body -->
					</div>
				</form>
			@else
				<div class="alert-auto-hide alert alert-info alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<i class="fa fa-info-circle"></i> Please select the required fields from the search form.
				</div>
			@endif

		</section>
	</div>
@endsection

@section('scripts')
	<!-- DataTables -->
	<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
	<!-- datatable script -->
	<script>

        $.ajax({
            url: "{{ url('/academics/find/section') }}",
            type: 'GET',
            cache: false,
            data: {'id':{{$academicInfo->batch_id}} }, //see the $_token
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

        // request for batch list using level id
        jQuery(document).on('change','.academicYear',function(){
            // get academic year id
            var year_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/level') }}",
                type: 'GET',
                cache: false,
                data: {'id': year_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    console.log(year_id);

                },

                success:function(data){
                    //console.log(data.length);
                    op+='<option value="0" selected disabled>--- Select Level ---</option>';
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

                error:function(){

                }
            });
        });

        // request for batch list using level id
        jQuery(document).on('change','.academicLevel',function(){
            // get academic level id
            var level_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/batch') }}",
                type: 'GET',
                cache: false,
                data: {'id': level_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    // console.log(level_id);
                },

                success:function(data){
                    //console.log(data.length);
                    op+='<option value="" selected disabled>--- Select Batch ---</option>';
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

                error:function(){

                }
            });
        });


        // request for section list using batch id
        jQuery(document).on('change','.academicBatch',function(){
            // get academic level id
            var batch_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/section') }}",
                type: 'GET',
                cache: false,
                data: {'id': batch_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    console.log(batch_id);
                },

                success:function(data){
                    //console.log(data.length);
                    op+='<option value="" selected disabled>--- Select Section ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                    }
                    // set value to the academic batch
                    $('.academicSection').html("");
                    $('.academicSection').append(op);
                },

                error:function(){
                    //
                },
            });
        });
	</script>
@endsection
