<style> .input-group { min-width: 130px; width: 100%;} </style>

<div class="row">
	<div class="col-md-10 col-md-offset-1">
		<h4 class="text-center bg-green">Assessment Passing Marks Setting </h4>
		<p class="text-center text-bold bg-success">Grading Scale : {{$gradeScale->name}}</p>
		@php $assessmentCategoryList = $gradeScale->assessmentCategory(); @endphp
		<form id="subject_assessment_pass_mark_assign" method="POST">
			<input type="hidden" name="_token" value="{{csrf_token()}}"/>
			<input type="hidden" name="ass_pass_mark_id" value="{{$assPassMarkProfile?$assPassMarkProfile->id:0}}"/>
			{{--find passing marks--}}
			@php $allMarkArrayList = (array)($assPassMarkProfile?json_decode($assPassMarkProfile->marks):[]); @endphp
			{{--checking category--}}
			@php $allCategory = (array)(array_key_exists('category', $allMarkArrayList)?$allMarkArrayList['category']:[]); @endphp
			{{--checking category--}}
			@php $allAssessment = (array)(array_key_exists('assessment', $allMarkArrayList)?$allMarkArrayList['assessment']:[]); @endphp

			<div class="row">
				<div class="col-md-6">
					<table width="100%" class="table table-bordered table-striped text-center">
						<thead>
						<tr class="bg-info text-center">
							<th width="10%">
								<input type="hidden" name="marks[category][status]" value="0">
								<input type="checkbox" {{$allCategory?($allCategory['status']==1?'checked':''):''}} id="category" class="my-pass-mark" name="marks[category][status]" value="1">
							</th>
							<th>Category Name</th>
							<th>Category Passing Mark</th>
						</tr>
						</thead>

						<tbody>
						{{--assessment list--}}
						@if($gradeScale->assessmentsCount()>0)
							@if($assessmentCategoryList)
								@foreach($assessmentCategoryList as $category)
									{{--checking category in the weighted average array list--}}
									@if(array_key_exists($category->id, $weightedAverageArrayList))
										{{--find single category passing mark--}}
										@php $singleCategory = (array) (array_key_exists($category->id, $allCategory)?$allCategory[$category->id]:[]); @endphp
										<tr>
											<td>
												<input type="hidden" name="marks[category][{{$category->id}}][status]" value="0">
												<input type="checkbox" {{$singleCategory?($singleCategory['status']==1?'checked':''):''}} class="category ass-checkbox" id="cat_{{$category->id}}" name="marks[category][{{$category->id}}][status]" value="1">
											</td>
											<td>{{$category->name}}</td>
											<td>
												<input type="text" class="input-group category-input" id="cat_{{$category->id}}_mark" name="marks[category][{{$category->id}}][mark]" value="{{$singleCategory?array_key_exists('mark', $singleCategory)?$singleCategory['mark']:'':''}}">
											</td>
										</tr>
									@endif
								@endforeach
							@endif
						@endif
						</tbody>
					</table>
				</div>

				<div class="col-md-6">
					<table width="100%" class="table table-bordered table-striped text-center">
						<thead>
						<tr class="bg-info text-center">
							{{--student Name and id th--}}
							<th width="10%">
								<input type="hidden" name="marks[assessment][status]" value="0">
								<input type="checkbox" {{$allAssessment?($allAssessment['status']==1?'checked':''):''}} id="assessment" class="my-pass-mark text-center" name="marks[assessment][status]" value="{{$allAssessment?$allAssessment['status']:1}}">
							</th>
							<th>Assessment Name</th>
							<th>Assessment Passing Mark</th>
						</tr>
						</thead>

						<tbody>
						{{--assessment list--}}
						@if($gradeScale->assessmentsCount()>0)
							@if($assessmentCategoryList)
								@foreach($assessmentCategoryList as $category)
									{{--checking category in the weighted average array list--}}
									@if(array_key_exists($category->id, $weightedAverageArrayList))
										{{--category assessment list--}}
										@php $allAssessmentList = $category->allAssessment($gradeScale->id) @endphp
										@if($allAssessmentList->count()>0)
											@foreach($allAssessmentList as $assessment)
												{{--checking assessment types--}}
												@if($assessment->counts_overall_score==0)
													{{--find single assessment passing mark--}}
													@php $singleAssessment = (array) ($allAssessment?$allAssessment[$assessment->id]:[]); @endphp
													<tr>
														<td>
															<input type="hidden" name="marks[assessment][{{$assessment->id}}][status]}" value="0">
															<input type="checkbox" {{$singleAssessment?($singleAssessment['status']==1?'checked':''):''}} class="assessment ass-checkbox" id="ass_{{$assessment->id}}" name="marks[assessment][{{$assessment->id}}][status]" value="1">
														</td>
														<td>{{$assessment->name}}</td>
														<td>
															<input type="text" class="input-group assessment-input" id="ass_{{$assessment->id}}_mark" name="marks[assessment][{{$assessment->id}}][mark]" value="{{array_key_exists('mark', $singleAssessment)?$singleAssessment['mark']:''}}">
														</td>
													</tr>
												@endif
											@endforeach
										@endif
									@endif
								@endforeach
							@endif
						@endif
						</tbody>
					</table>
				</div>
			</div>

			<div class="modal-footer">
				<button type="reset" class="btn btn-info pull-left">Reset</button>
				<button type="submit" class="btn btn-success">Submit</button>
			</div>
		</form>
	</div>
</div>

<script type="text/javascript">

    $(document).ready(function () {

        {{--@if($allCategory==null || $allCategory['status']==0)--}}
			{{--$('.category').attr('disabled', true);--}}
            {{--$('.category-input').attr('disabled', true);--}}
	    {{--@endif--}}

		{{--@if($allAssessment==null || $allAssessment['status']==0)--}}
			{{--$('.assessment').attr('disabled', true);--}}
			{{--$('.assessment-input').attr('disabled', true);--}}
		{{--@endif--}}

        // my-pass-mark click action
        $('.my-pass-mark').click(function () {
            // find id
            var my_id = $(this).attr('id');
            if ($(this).is(":checked")) {
                // remove attribute
                $('.'+my_id).removeAttr('disabled');
            }else{
                // add attribute
                $('.'+my_id).attr('disabled', true);
                $('.'+my_id+'-input').attr('disabled', true);
                // remove attribute
                $('.'+my_id).removeAttr('checked');
                $('.'+my_id+'-input').val('');
            }
        });


        // my-pass-mark click action
        $('.ass-checkbox').click(function () {
            // find id
            var my_id = $(this).attr('id');
            if ($(this).is(":checked")) {
                // remove attribute
                $('#'+my_id+'_mark').removeAttr('disabled');
            }else{
                // add attribute
                $('#'+my_id+'_mark').attr('disabled', true);
                $('#'+my_id+'_mark').val('');
            }
        });





        // request for section list using batch and section id
        $('form#subject_assessment_pass_mark_assign').on('submit', function (e) {
            // alert('hello');
            e.preventDefault();
            // append batch
            $(this).append('<input type="hidden" name="batch" value="'+$('#batch').val()+'" />');

            // ajax request
            $.ajax({
                url: "{{ url('/academics/manage/assessments/passing-mark/setting/manage') }}",
                type: 'POST',
                cache: false,
                data: $('form#subject_assessment_pass_mark_assign').serialize(),
                datatype: 'html',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Loading...');
                },

                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // checking
                    if(data.status){
                        // sweet alert success
                        swal("Success", data.msg, "success");
                    }else{
                        // sweet alert warning
                        swal("Warning", data.msg, "warning");
                    }
                },

                error:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    // sweet alert error
                    swal("Error", 'No data response from the server', "error");
                }
            });
        });


    });

</script>

