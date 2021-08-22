@if (count($parents)>0)
@foreach($parents as $parent)
	@php $guardian = $parent->guardian(); @endphp
	{{--	{{dd($parent->guardian())}}--}}
	@if($guardian)
		<div class="row">
			<div class="col-xs-12 col-md-12 col-lg-12">
				<h2>
					@php
						switch($guardian->type) {
						   case '0': echo "Mother"; break;
						   case '1': echo "Father"; break;
						   case '2': echo "Sister"; break;
						   case '3': echo "Brother"; break;
						   case '4': echo "Relative"; break;
						   case '5': echo "Others"; break;
						}
					@endphp
				</h2>
				<h2 class="page-header">
					{{$guardian->title." ".$guardian->first_name." ".$guardian->last_name}} ({{$guardian->user()->username}})
					@if($guardian->is_guardian == 1) <span class="badge badge-success">Guardian</span> @endif
					<div class="pull-right" style="display:inline-table">
						<small class="text-bold text-sm">Is Emergency Contact </small>
						<h4 class="label inline">
							@if($parent->is_emergency == 1)
								<span class="label label-success">Yes </span>
							@else
								<span class="label label-danger"> No </span>
							@endif

						</h4>
						<div class="btn-group">
							<button id="w0" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars" aria-hidden="true"></i> <span class="caret"></span></button>
							<ul id="w1" class="dropdown-menu dropdown-menu-right">
								@if($parent->is_emergency == 0)
									<li><a href="/student/profile/guardian/emergency/{{$personalInfo->id}}/{{$guardian->id}}" data-confirm="Are you sure you want to set this guardian as emergency contact and set to inactive other.?" tabindex="-1">Set as Emergency</a></li>
								@endif
								<li><a href="/student/profile/guardian/edit/{{$guardian->id}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" tabindex="-1">Edit</a></li>
								<li><a href="/student/profile/make/guardian/{{$personalInfo->id}}/{{$guardian->id}}">Make Guardian</a></li>
								<li><a href="/student/profile/guardian/delete/{{$guardian->id}}" data-confirm="Are you sure you want to delete this item?">Delete</a></li>
							</ul>
						</div>
					</div>
				</h2>

			</div>
			<!-- /.col -->
		</div>
		<div class="table-responsive">
			<table class="table">
				<colgroup>
					<col style="width:200px">
					<col style="width:200px">
					<col style="width:300px">
					<col style="width:200px">
					<col style="width:300px">
				</colgroup>
				<tbody>
				<tr>
					<td rowspan="10">
						@if($guardian->guardian_photo)
							<img src="{{asset('/family/'.$guardian->guardian_photo)}}" width="100" alt="Guardian Image"><br>
						@endif
						@if($guardian->guardian_signature)
							<img src="{{asset('/family/'.$guardian->guardian_signature)}}" width="100" alt="Guardian Signature">
						@endif
					</td>
					<th>Title</th>
					<td>{{$guardian->title}}</td>
					<th class="text-red text-bold">Relation</th>
					<td class="text-red text-bold">
						@php
							switch($guardian->type) {
							   case '0': echo "Mother"; break;
							   case '1': echo "Father"; break;
							   case '2': echo "Sister"; break;
							   case '3': echo "Brother"; break;
							   case '4': echo "Relative"; break;
							   case '5': echo "Others"; break;
							}
						@endphp
					</td>
				</tr>
				<tr>
					<th>
						First Name
					</th>
					<td>{{$guardian->first_name}}</td>
					<th>
						Last Name
					</th>
					<td>{{$guardian->last_name}}</td>
				</tr>
				<tr>
					<th>
						Email/Login Id
					</th>
					<td>
						{{$guardian->email}}
					</td>
					<th>
						Mobile No
					</th>
					<td>{{$guardian->mobile}}</td>
				</tr>
				<tr>
					<th>
						Phone No
					</th>
					<td>{{$guardian->phone}}</td>
					<th>
						Income
					</th>
					<td>{{$guardian->income}}</td>
				</tr>
				<tr>
					<th>
						Occupation
					</th>
					<td>{{$guardian->occupation}}</td>
					<th>
						Qualification
					</th>
					<td>{{$guardian->qualification}}</td>
				</tr>
				<tr>
					<th>Date Of Birth</th>
					<td> @if($guardian->date_of_birth) {{\Carbon\Carbon::parse($guardian->date_of_birth)->format('d/m/Y')}} @endif</td>
					<th>Gender</th>
					<td>
						@if($guardian->gender == 1)
							Male
						@elseif($guardian->gender == 2)
							Female
						@elseif($guardian->gender == 3)
							Other
						@endif
					</td>
				</tr>
				<tr>
					<th>
						Home Address
					</th>
					<td >{{$guardian->home_address}}</td>
					<th>
						Office Address
					</th>
					<td >{{$guardian->office_address}}</td>
				</tr>
				<tr>
					<th>Remarks</th>
					<td>{{$guardian->remarks}}</td>
					<th>NID number</th>
					<td>
						{{$guardian->nid_number}}
						@if($guardian->nid_file)
							<a class="popImage" href="{{asset('/family-attachment/'.$guardian->nid_file)}}"><i class="fa fa-eye"></i></a>
						@endif
					</td>
				</tr>
				<tr>
					<th>Birth Certificate</th>
					<td>
						{{$guardian->birth_certificate}}
						@if($guardian->birth_file)
							<a class="popImage" href="{{asset('/family-attachment/'.$guardian->birth_file)}}"><i class="fa fa-eye"></i></a>
						@endif
					</td>
					<th>TIN Number</th>
					<td>
						{{$guardian->tin_number}}
						@if($guardian->tin_file)
							<a class="popImage" href="{{asset('/family-attachment/'.$guardian->tin_file)}}"><i class="fa fa-eye"></i></a>
						@endif
					</td>
				</tr>
				<tr>
					<th>Passport Number</th>
					<td>
						{{$guardian->passport_number}}
						@if($guardian->passport_file)
							<a class="popImage" href="{{asset('/family-attachment/'.$guardian->passport_file)}}"><i class="fa fa-eye"></i></a>
						@endif
					</td>
					<th>Driving License</th>
					<td>
						{{$guardian->dln}}
						@if($guardian->driving_lic_file)
							<a class="popImage" href="{{asset('/family-attachment/'.$guardian->driving_lic_file)}}"><i class="fa fa-eye"></i></a>
						@endif
					</td>
				</tr>
				</tbody>
			</table>
		</div>
	@endif
@endforeach

<div class="modal" tabindex="-1" role="dialog" id="imageModal">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<img id="imagepreview" width="100%" src="" alt="">
			</div>
		</div>
	</div>
</div>

@endif

@section('scripts')
	<script>
		$('.popImage').click(function (e){
			e.preventDefault();
			$('#imagepreview').attr('src', $(this).attr('href'));
			$('#imageModal').modal('show');
		});
	</script>
@endsection
