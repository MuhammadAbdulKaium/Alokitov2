{{--checking grade category list--}}
@if($classGradeScaleProfile)
	{{--find grade scale--}}
	@php $gradeScale = $classGradeScaleProfile->gradeScale() @endphp
	{{--checking grade scale--}}
	@if($gradeScale->assessmentsCount()>0)
		<div class="col-sm-12">
			<h5 class="text-bold text-center bg-aqua-active">Assessment Category List</h5>
			<table class="table table-responsive table-bordered table-striped text-center">
				<thead>
				<tr>
					<th class="text-center">#</th>
					<th>Name</th>
					<th>Count Best Result</th>
				</tr>
				</thead>
				<tbody>
				@php $assessmentCategoryList = $gradeScale->assessmentsCategoryList(); @endphp
				{{--checking--}}
				@if($assessmentCategoryList AND $assessmentCategoryList->count()>0)
					{{--assessmentCategoryList looping--}}
					@foreach($assessmentCategoryList as $assessmentProfile)
						{{--category profile--}}
						@php $gradeCategoryProfile = $assessmentProfile->gradeCategory(); @endphp
						{{--checking cateory assignment--}}
						@php $assignProfile = $gradeCategoryProfile->checkAssignment($academicInfo->batch, $academicInfo->section, $academicInfo->semester); @endphp
						<tr>
							<td>
								<input id="check_{{$gradeCategoryProfile->id}}" class="grade-cat" name="ass_list[{{$gradeCategoryProfile->id}}][assign_id]" value="0" {{$assignProfile?'checked disabled':''}} type="checkbox"/>
								@if($assignProfile)
									<input type="hidden" name="ass_list[{{$gradeCategoryProfile->id}}][assign_id]" value="{{$assignProfile->id}}">
								@endif
							</td>
							<td>{{$gradeCategoryProfile->name}}</td>
							<td>
								<input class="text-center" id="input_{{$gradeCategoryProfile->id}}" {{$assignProfile?'':'disabled'}} type="text" name="ass_list[{{$gradeCategoryProfile->id}}][result_count]" value="{{$assignProfile?$assignProfile->result_count:''}}"/>
							</td>
						</tr>
					@endforeach
				@endif
				</tbody>
			</table>
		</div>
	@else
		<div class="col-sm-12">
			<div class="alert-warning alert-auto-hide alert fade in text-center" style="opacity: 474.119;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<h5><i class="fa fa-warning"></i> No Assessment Found. </h5>
			</div>
		</div>
	@endif
@else
	<div class="col-sm-12">
		<div class="alert-warning alert-auto-hide alert fade in text-center" style="opacity: 474.119;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<h5><i class="fa fa-warning"></i> No Grade Scale found. </h5>
		</div>
	</div>
@endif

<script type="text/javascript">
    $(document).ready(function(){
        // grade ca
        $('.grade-cat').click(function () {
            // cat id
            var cat_id = $(this).attr('id').replace('check_','');
            // checking
            if($(this).is(":checked")){
                $('#input_'+cat_id).removeAttr('disabled');
            }else{
                $('#input_'+cat_id).val('');
                $('#input_'+cat_id).attr('disabled', true);
            }
        });
    });
</script>