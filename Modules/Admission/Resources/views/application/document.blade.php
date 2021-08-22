@extends('admission::layouts.applicant-profile-layout')
<!-- page content -->
@section('profile-content')
	<div id="w1-tab4" class="tab-pane active">
		<div class="row">
			<div class="col-md-12">
				<p class="pull-right flip">
					<a class="btn btn-success" href="{{url('/admission/applicant/'.$applicantProfile->id.'/document/add')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">Add Document</a>
				</p>
			</div>
		</div>
		<table class="table table-bordered">
			<colgroup>
				<col style="width:200px">
				<col style="width:150px">
				<col style="width:150px">
				<col style="width:10px">
				<col style="width:10px">
			</colgroup>
			<thead>
			<tr>
				<th> Document Category </th>
				<th> Document Details </th>
				<th class="text-center"> Submitted At </th>
				<th class="text-center"> Download </th>
				<th class="text-center"> Action </th>
			</tr>
			</thead>
			<tbody>
				@php $allDocument = $applicantProfile->document('doc'); @endphp
				@if($allDocument->count()>0)
					@foreach($allDocument as $document)
					<tr>
						<td>{{$document->doc_type}}</td>
						<td>{{$document->doc_details}}</td>
						<td class="text-center">{{date('d M, Y', strtotime($document->created_at))}}</td>
						<td class="text-center">
							<a class="btn btn-default btn-sm" href="{{url($document->doc_path.$document->doc_name)}}" title="Click here to download" download>
								<i class="fa fa-download" aria-hidden="true"></i>
							</a>
						</td>
						<td class="text-center">
							<div class="btn-group">
								<button id="w1" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown"> <span class="caret"></span></button>
								<ul id="w2" class="dropdown-menu dropdown-menu-right">
									<li>
										<a id="update-document" href="/admission/applicant/document/{{$document->id}}/edit" title="Update Document" data-target="#globalModal" data-toggle="modal" tabindex="-1">Edit</a>
									</li>
									<li><a href="/admission/applicant/document/{{$document->id}}/delete" title="Delete Document" data-confirm="Are you sure you want to delete this document?" data-method="get" tabindex="-1">Delete</a></li>
								</ul>
							</div>
						</td>
					</tr>
					@endforeach
				@else
					<tr>
						<td class="text-center" colspan="4">
							<strong> No Documents Submitted !! </strong>
						</td>
					</tr>
				@endif
			</tbody>
		</table>
	</div>
@endsection