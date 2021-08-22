@extends('student::pages.student-profile.profile-layout')

@section('profile-content')
						@if($personalInfo)
	                     <p class="text-right">
	                        <a class="btn btn-primary btn-sm" href="/student/profile/personal/edit/{{$personalInfo->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class = "fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a>
	                     </p>
	                     @else
	                     <p class="text-right">
	                        <a class="btn btn-primary btn-sm" href="#"  data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg"><i class = "fa fa-pencil-square-o" aria-hidden="true"></i> Add</a>
	                     </p>
	                     @endif

	                     @if($personalInfo)
	                     <div class="table-responsive">
	                        <table class="table">
	                           <colgroup>
	                              <col style="width:15%">
	                              <col style="width:35%">
	                              <col style="width:15%">
	                              <col style="width:35%">
	                           </colgroup>
	                           <tr>
	                              <th>Name</th>
	                              <td>{{$personalInfo->title." ". $personalInfo->first_name." ".$personalInfo->middle_name." ".$personalInfo->last_name }}</td>
	                           </tr>
	                           <tr>
	                              <th>Gender</th>
	                              <td>{{$personalInfo->gender }}</td>

	                              <th>Date of Birth</th>
	                              <td>{{ date('d M, Y', strtotime($personalInfo->dob)) }}</td>
	                           </tr>
	                           <tr>
	                              <th>Nationality</th>
	                              <td>{{$personalInfo->nationality()?$personalInfo->nationality()->nationality:' - '}}</td>

	                              <th>Religion</th>
	                              <td>
		                              @php
			                              switch($personalInfo->religion) {
											 case '1': echo "Islam"; break;
											 case '2': echo "Hinduism"; break;
											 case '3': echo "Christianity"; break;
											 case '4': echo "Buddhism"; break;
											 case '5': echo "Others"; break;
										  }
		                              @endphp
	                              </td>
	                           </tr>
	                           <tr>
	                              <th>Birthplace</th>
	                              <td>{{ $personalInfo->birth_place }}</td>

	                              <th>Blood Group</th>
	                              <td>{{ $personalInfo->blood_group }}</td>
	                           </tr>
	                           <tr>
	                              <th>Cadet Type</th>
	                              <td>
					                  @php
					                     switch($personalInfo->type) {
					                        case '1': echo "Pre Admission"; break;
					                        case '2': echo "Regular"; break;
					                     }
					                  @endphp
	                              </td>

	                              <th>Languages</th>
		                           <td>
		                           {{$personalInfo->language}}
		                           </td>
	                           </tr>
								<tr>
									<th>Identification Mark</th>
									<td>{{ $personalInfo->identification_mark }}</td>
									<th>Tuition Fees</th>
									<td> @if($personalInfo->tuitionFees){{ $personalInfo->tuitionFees->fees }}@endif </td>
								</tr>
								<tr>
									<th>Batch No</th>
									<td>{{ $personalInfo->batch_no }}
									</td><th>Cadet No</th>
									<td>{{ $personalInfo->email }}</td>
								</tr>
								<tr>
									<th>Present Address</th>
									<td>@if($personalInfo->presentAddress()) {{$personalInfo->presentAddress()->address }} @endif</td>
									<th>Permanent Address</th>
									<td>@if($personalInfo->permanentAddress()) {{ $personalInfo->permanentAddress()->address }} @endif</td>
								</tr>
	                        </table>
	                     </div>
	                     @else
	                     <div class="alert bg-warning text-warning">
					            <i class="fa fa-warning"></i> No record found.
					     </div>
	                     @endif
@endsection
