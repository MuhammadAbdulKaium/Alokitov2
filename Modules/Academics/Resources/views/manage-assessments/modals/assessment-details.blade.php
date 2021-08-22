<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
	<h4 class="modal-title"><i class="fa fa-plus-square"></i> {{$gradeScaleProfile->name}} (Assigned List)</h4>
	{{--<h4 class="modal-title"><i class="fa fa-plus-square"></i>  Class List (<strong></strong>)</h4>--}}
</div>

	<div class="modal-body">
		<div class="row">
			<div class="col-md-12">
				<table class="table table-bordered text-center">
					<thead>
						<tr>
							<th>#</th>
							<th>Assigned Class Name</th>
						</tr>
					</thead>

					<tbody>
						@if($classGradeScale->count()>0)
							{{--{{dd($classGradeScale)}}--}}
							@foreach($classGradeScale as $index=>$gradeScale)
								@php
									$batch = $gradeScale->batch();
									// checking batch division
									if($division = $batch->division()){
										$batchName = $batch->batch_name.' ('.$division->name.')';
									}else{
										$batchName = $batch->batch_name;
									}
								@endphp
								<tr>
									<td>{{($index+1)}}</td>
									<td>{{$batchName}}</td>
								</tr>
							@endforeach
						@else
							<tr>
								<td colspan="2">
									<p class="alert alert-warning"><i class="icon fa fa-warning"></i>No Records found</p>
								</td>
							</tr>
						@endif
					</tbody>
				</table>
			</div>

		</div>

	</div>
	<!--./modal-body-->
	<div class="modal-footer">
		 <button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
	</div>