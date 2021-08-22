@extends('admission::layouts.admission-layout')
<!-- page content -->
@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1> <i class = "fa fa-users" aria-hidden="true"></i> Manage Enquiry </h1>
			<ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Enquiry</a></li>
				<li class="active">Manage Enquiry</li>
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

			<style>
				i {margin-right: 10px;}
				b { font-size: 20px;}
			</style>

			<div class="box box-solid" style="padding: 20px">
				<a target="_blank" href="{{URL::to('/admission/hsc/applicant/download',$appProfile->id)}}" class="btn btn-success pull-right">Download Profile</a>
				<div class="row">
					<div class="col-md-8 col-md-offset-2">
						<table class="table table-bordered table-responsive" style="font-size: 13px">
							<tbody>
							<tr>
								<th width="15%">Applicant Name</th>
								<th width="1%">:</th>
								<th width="34%">{{$appProfile->s_name}}</th>

								<th width="13%">App. Status</th>
								<th width="1%">:</th>
								<th>{{$appProfile->a_status==0?'Pending':'Active'}}</th>

								<td width=15%" rowspan="3" class="text-center">
									{{--checking std photo--}}
									@if($appProfile->std_photo)
										<img src="{{$appProfile->std_photo}}" width="80px" height="80px">
									@else
										<img src="{{asset('assets/users/images/user-default.png')}}" width="80px" height="80px">
									@endif
								</td>
							</tr>
							<tr>
								<th>App. Name (bn)</th>
								<th>:</th>
								<th>{{$appProfile->s_name_bn}}</th>

								<th>Pay. Status</th>
								<th>:</th>
								<th>{{$appProfile->p_status==0?'Un-Paid':'Paid'}}</th>
							</tr>
							<tr>
								<th>Application ID</th>
								<th>:</th>
								<th>{{$appProfile->a_no}}</th>

								<th>App. Date</th>
								<th>:</th>
								<th >{{$appProfile->created_at->format("d M, Y")}}</th>
							</tr>
							</tbody>
						</table>

						<table class="table table-bordered table-responsive">
							<tbody>
							{{--Basic Informaiton--}}
							<tr><th colspan="6"><b>1. Basic Information :</b></th></tr>
							<tr>
								{{--left side informaiton--}}
								<th width="10%">Class / Batch</th>
								<th width="1%">:</th>
								{{--batch --}}
								@php $myBatch = $appProfile->batch(); @endphp
								<td width="36%">{{$myBatch->batch_name}}</td>
								{{--right side information--}}
								<th width="13%">Group</th>
								<th width="1%">:</th>
								{{--batch group --}}
								@php
									// checking batch division
									if($division = $myBatch->get_division()){ $divisionName = $division->name;}else{$divisionName = '-';}
								@endphp
								<td width="39%">{{$divisionName}}</td>
							</tr>
							<tr>
								<th>Year</th>
								<th>:</th>
								<td>{{$appProfile->year()->year_name}}</td>
								<th>Gender</th>
								<th>:</th>
								<td>{{$appProfile->gender==0?'Male':'Female'}}</td>
							</tr>
							<tr>
								<th width="15%">Date of birth</th>
								<th>:</th>
								<td>{{date("d M, Y", strtotime($appProfile->b_date))}}</td>
								<th>Blood Group</th>
								<th>:</th>
								<td>{{$appProfile->b_group}}</td>
							</tr>
							<tr>
								<th>Mobile No</th>
								<th>:</th>
								<td>{{$appProfile->s_mobile}}</td>
								<th>National ID</th>
								<th>:</th>
								<td>{{$appProfile->s_nid}}</td>
							</tr>
							<tr>
								<th>Nationality</th>
								<th>:</th>
								<td>{{$appProfile->nationality()->nationality}}</td>
								<th>Religion</th>
								<th>:</th>
								<td>
									{{--checking religion--}}
									@if($appProfile->religion==1)
										Islam
									@elseif($appProfile->religion==2)
										Hinduism
									@elseif($appProfile->religion==3)
										Christian
									@elseif($appProfile->religion==4)
										Buddhism
									@elseif($appProfile->religion==5)
										Others
									@endif
								</td>
							</tr>
							{{--Father's Information--}}
							<tr><td colspan="6"><b>2. Father's Information :</b></td></tr>
							<tr>
								<th>Full Name</th>
								<th>:</th>
								<td>{{$appProfile->f_name}}</td>
								<th>National ID</th>
								<th>:</th>
								<td>{{$appProfile->f_nid}}</td>
							</tr>
							<tr>
								<th>Full Name (bn)</th>
								<th>:</th>
								<td>{{$appProfile->f_name_bn}}</td>
								<th>Mobile No</th>
								<th>:</th>
								<td>{{$appProfile->f_mobile}}</td>
							</tr>
							<tr>
								<th>Education</th>
								<th>:</th>
								<td>{{$appProfile->f_education}}</td>
								<th>Occupation</th>
								<th>:</th>
								<td>{{$appProfile->f_occupation}}</td>
							</tr>
							<tr>
								<th>Income</th>
								<th>:</th>
								<td>{{$appProfile->f_income}}</td>
								<th colspan="3" width="15%"></th>
							</tr>
							{{--Mother's Information--}}
							<tr><td colspan="6"><b>3. Mother's Information :</b></td></tr>
							<tr>
								<th>Full Name</th>
								<th>:</th>
								<td>{{$appProfile->m_name}}</td>
								<th>National ID</th>
								<th>:</th>
								<td>{{$appProfile->m_nid}}</td>
							</tr>
							<tr>
								<th>Full Name (bn)</th>
								<th>:</th>
								<td>{{$appProfile->m_name_bn}}</td>
								<th>Mobile No</th>
								<th>:</th>
								<td>{{$appProfile->m_mobile}}</td>
							</tr>
							<tr>
								<th>Education</th>
								<th>:</th>
								<td>{{$appProfile->m_education}}</td>
								<th>Occupation</th>
								<th>:</th>
								<td>{{$appProfile->m_occupation}}</td>
							</tr>
							<tr>
								<th>Income</th>
								<th>:</th>
								<td>{{$appProfile->m_income}}</td>
								<th colspan="3"></th>
							</tr>
							{{--Address--}}
							<tr><td colspan="6"><b>4. Address Information :</b></td></tr>
							<tr>
								<th>Zilla</th>
								<th>:</th>
								<td>{{$appProfile->zilla()->name}}</td>
								<th>Thana</th>
								<th>:</th>
								<td>{{$appProfile->thana()->name}}</td>
							</tr>
							<tr>
								<th>Village</th>
								<th>:</th>
								<td>{{$appProfile->vill}}</td>
								<th>Post Office</th>
								<th>:</th>
								<td>{{$appProfile->post}}</td>
							</tr>
							{{--SSC / EQUI--}}
							<tr><td colspan="6"><b>5. SSC / EQUI Information :</b></td></tr>
							<tr>
								<th>Name of Exam</th>
								<th>:</th>
								<td>{{$appProfile->exam_name}}</td>
								<th>Board</th>
								<th>:</th>
								<td>{{$appProfile->exam_board}}</td>
							</tr>
							<tr>
								<th>Session</th>
								<th>:</th>
								<td>{{$appProfile->exam_session}}</td>
								<th>Reg. No</th>
								<th>:</th>
								<td>{{$appProfile->exam_reg}}</td>
							</tr>
							<tr>
								<th>Roll No</th>
								<th>:</th>
								<td>{{$appProfile->exam_roll}}</td>
								<th>Result (GPA)</th>
								<th>:</th>
								<td>{{$appProfile->exam_gpa}}</td>
							</tr>
							<tr>
								<th>Passing Year</th>
								<th >:</th>
								<td>{{$appProfile->exam_year}}</td>
								<th>Institute</th>
								<th>:</th>
								<td>{{$appProfile->exam_institute}}</td>
							</tr>
							{{--Chooses Subject--}}
							<tr><td colspan="6"><b>5. Subject List :</b></td></tr>
							{{--checking subject_group and and subject list--}}
							@if($appProfile->group_list AND $appProfile->sub_list)
								@php
									// my group subject list
									$mySubGroup = (array) json_decode($appProfile->group_list);
									//my subject list
									$myElectiveOne = ($mySubGroup AND array_key_exists('e_1', $mySubGroup))?($mySubGroup['e_1']):[];
									$myElectiveTwo = ($mySubGroup AND array_key_exists('e_2', $mySubGroup))?($mySubGroup['e_2']):[];
									$myElectiveThree = ($mySubGroup AND array_key_exists('e_3', $mySubGroup))?($mySubGroup['e_3']):[];
									$myOptional = ($mySubGroup AND array_key_exists('opt', $mySubGroup))?($mySubGroup['opt']):[];
									// class subject list
									$compulsory = array_key_exists('compulsory', $groupSubject)?$groupSubject['compulsory']:[];
									$electiveOne = array_key_exists('elective_one', $groupSubject)?$groupSubject['elective_one']:[];
									$electiveTwo = array_key_exists('elective_two', $groupSubject)?$groupSubject['elective_two']:[];
									$electiveThree =array_key_exists('elective_three', $groupSubject)?$groupSubject['elective_three']:[];
									$optional =array_key_exists('optional', $groupSubject)?$groupSubject['optional']:[];
								@endphp
								<tr>
									<th>Compulsory</th>
									<th>:</th>
									<td  colspan="4">
										{{--checking compulsory subject list--}}
										@if($compulsory)
											{{--sub counter--}}
											@php $subCounter = 1; @endphp
											{{--compulsory list looping--}}
											@foreach($compulsory as $sub)
												<i>{{$subCounter}}. {{$sub['name']}} </i>
												{{--sub counter--}}
												@php $subCounter += 1; @endphp
											@endforeach
										@else
											<i> ***** No Compulsory Subject found *****</i>
										@endif
									</td>
								</tr>
								<tr>
									<th>Elective</th>
									<th>:</th>
									<td  colspan="4">
										{{--elective sub counter--}}
										@php $eSubCounter = 1; @endphp
										{{--checking elective one subject list--}}
										@if($electiveOne && $myElectiveOne)
											{{--elective three subject list looping--}}
											@foreach($electiveOne as $subId=>$sub)
												{{--cheking my elective two subject--}}
												@if($subId!=$myElectiveOne) @continue @endif
												{{--subject name--}}
												<i>{{$eSubCounter}}. {{$sub['name']}} </i>
												{{--sub counter--}}
												@php $eSubCounter += 1; @endphp
											@endforeach
										@endif

										{{--checking elective two subject list--}}
										@if($electiveTwo && $myElectiveTwo)
											{{--elective two subject list looping--}}
											@foreach($electiveTwo as $subId=>$sub)
												{{--cheking my elective two subject--}}
												@if($subId!=$myElectiveTwo) @continue @endif
												{{--subject name--}}
												<i>{{$eSubCounter}}. {{$sub['name']}} </i>
												{{--sub counter--}}
												@php $eSubCounter += 1; @endphp
											@endforeach
										@endif

										{{--checking elective three subject list--}}
										@if($electiveThree && $myElectiveThree)
											{{--elective three subject list looping--}}
											@foreach($electiveThree as $subId=>$sub)
												{{--cheking my elective three subject--}}
												@if($subId!=$myElectiveThree) @continue @endif
												{{--subject name--}}
												<i>{{$eSubCounter}}. {{$sub['name']}} </i>
												{{--sub counter--}}
												@php $eSubCounter += 1; @endphp
											@endforeach
										@endif
									</td>
								</tr>
								<tr>
									<th>Optional</th>
									<th>:</th>
									<td colspan="4">
										{{--checking optional subject list--}}
										@if($optional && $myOptional)
											{{--sub counter--}}
											@php $subCounter = 1; @endphp
											{{--optional list looping--}}
											@foreach($optional as $subId=>$sub)
												{{--cheking my optional subject--}}
												@if($subId!=$myOptional) @continue @endif
												{{--subject name--}}
												<i>{{$subCounter}}. {{$sub['name']}} </i>
												{{--sub counter--}}
												@php $subCounter += 1; @endphp
											@endforeach
										@endif
									</td>
								</tr>
							@else
								{{--checking class subject list--}}
								@if($classSubject)
									{{--class subject count--}}
									@php $csCount = count($classSubject); @endphp
									{{--class subject list looping--}}
									@for($x=0; $x<=$csCount; $x+=2)
										<tr>
											<td colspan="3">
												{{--td one--}}
												@if($x < $csCount)
													{{$classSubject[$x]['type']==3?'Opt: ':''}}{{$classSubject[$x]['code']}} ({{$classSubject[$x]['name']}})
												@endif
											</td>
											{{--td two--}}
											<td colspan="3">
												@if(($x+1) < $csCount)
													{{$classSubject[$x+1]['type']==3?'Opt: ':''}} {{$classSubject[$x+1]['code']}} ({{$classSubject[$x+1]['name']}})
												@endif
											</td>
										</tr>
									@endfor
								@else
									<tr><td colspan="6">	<i> ***** No Class Subject found *****</i></td></tr>
								@endif
							@endif
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection