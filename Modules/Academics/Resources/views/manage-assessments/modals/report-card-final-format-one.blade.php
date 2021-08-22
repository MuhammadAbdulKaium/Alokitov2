<p class="text-center bg-green text-bold">Student Information</p>
<style>
	td{height: 28px}
</style>
<div class="row">
	<div class="panel-body">
		<div class="col-md-2 text-center">
			@php $photo = $studentInfo->singelAttachment("PROFILE_PHOTO") @endphp
			@if($photo)
				<img class="center-block img-thumbnail img-circle img-responsive" src="{{URL::asset('assets/users/images/'.$photo->singleContent()->name)}}" alt="No Image" style="width:100px;height:100px">
			@else
				<img class="center-block img-circle img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:100px;height:100px">
			@endif
		</div>
		<div class="col-md-10">
			<table class="table table-bordered table-striped text-center table-responsive">
				<thead>
				<tr>
					<th>Name</th>
					<th>Email</th>
					<th>Gr. No</th>
					<th>Section</th>
					<th>Batch</th>
					<th>Gender</th>
					<th>Birth Date</th>
					<th>Blood Group</th>
				</tr>
				</thead>

				<thead>
				<tr>
					<td>{{$studentInfo->first_name." ".$studentInfo->middle_name." ".$studentInfo->last_name}}</td>
					<input type="hidden" id="my_std_id" value="{{$studentInfo->id}}"/>
					<td>{{$studentInfo->email}}</td>
					@php
						$enrollment = $studentInfo->singleEnroll();
						$level  = $enrollment->level();
						$batch  = $enrollment->batch();
						$section  = $enrollment->section();
					@endphp
					<td>{{$enrollment->gr_no}}</td>
					<td>{{$section->section_name}}</td>
					<td>{{$batch->batch_name}}@if($division = $batch->get_division())  ({{$division->name}})@endif</td>
					<td>{{$studentInfo->gender}}</td>
					<td>{{ date('d M, Y', strtotime($studentInfo->dob)) }}</td>
					<td>{{$studentInfo->blood_group}}</td>

					{{--input student academics details--}}
					@php
						$levelId = $level->id;
						$batchId = $batch->id;
						$sectionId = $section->id;
					@endphp
				</tr>
				</thead>
			</table>
		</div>
	</div>
</div>
<p class="text-center text-bold bg-green">
	Student Report Card (Final)
	<i class="pull-right" style="margin-right: 10px;"><a id="std_final_report" class="text-danger" style="cursor: pointer">Download</a></i>
</p>
{{--checking exam result sheet--}}
@if($examResultSheet['status']=='success')
	{{--find semester count--}}
	@php $totalSemester = count($allSemester); @endphp
	{{--all student result sheet--}}
	@php
		$allStdResultList = $examResultSheet['std_list'];
		$finalMeritList = $examResultSheet['final_merit_list'];
	@endphp
	{{--chekcing std list--}}
	@if(count($allStdResultList)>0)
		<table width="100%" border="1px solid black" style="font-size: 13px" class="text-center">
			<thead>
			<tr class="bg-gray">
				<th width="2%">#</th>
				<th>Subject Name</th>
				<th width="4%">Exam Marks</th>
				{{--checking semester list--}}
				@if($totalSemester>0)
					{{--semester list looping--}}
					@foreach($allSemester as $semester)
						<th>
							{{$semester['name']}}
							<table width="100%" style="border-top: 1px solid black" class="text-center">
								<thead>
								<tr>
									{{--<th width="25%" style="border-right: 1px solid black">Exam Mark</th>--}}
									<th width="25%" style="border-right: 1px solid black">Obtained</th>
									<th width="25%" style="border-right: 1px solid black">Grade</th>
									<th width="25%">Point</th>
								</tr>
								</thead>
							</table>
						</th>
					@endforeach
				@endif
				<th width="5%">Total Obtained</th>
				<th width="5%">Semester Average</th>
				<th width="4%">Percent (%)</th>
				<th width="4%">Grade</th>
				<th width="3%">Point</th>
			</tr>
			</thead>
			<tbody>
			@php
				// find result sheet
				$myResultSheet = $allStdResultList[$studentInfo->id];
				// result details
				$subList = $myResultSheet['sub_list'];
				$atdList = $myResultSheet['sem_atd_list'];
				$semMeritList = $myResultSheet['sem_merit_list'];
				$semResultSummaryList = $myResultSheet['sem_result_summary_list'];
			@endphp
			{{--checking sub list--}}
			@if($subList)
				@php
					$subCounter = 1;
					$allSubTotalMarks = 0;
					$allSubObtainedTotalMarks = 0;
					$allSubObtainedAvgMarks = 0;
					$allSubExamMarks = 0;
				@endphp
				{{--subject list looping--}}
				@foreach($subList as $subId=>$subDetails)
					{{--subject semester result sheet--}}
					@php $subSemResultSheet =  $subDetails['sub_sem_result']@endphp

					<tr>
						<th>{{$subCounter++}}</th>
						<th>{{$subDetails['sub_name']}}</th>
						<th>{{$subDetails['sub_exam_marks']}}</th>
						{{--calculate semester total result--}}
						@php
							$allSubExamMarks += $subDetails['sub_exam_marks'];
							$subTotalObtainedMarks = 0;
						@endphp
						{{--checking semester list--}}
						@if($totalSemester>0)
							{{--semester list looping--}}
							@foreach($allSemester as $semester)
								{{--chekcing subject semester result sheet--}}
								@if(array_key_exists($semester['id'], $subSemResultSheet))
									{{--find subject details--}}
									@php $subjectResult =  (object)$subSemResultSheet[$semester['id']]@endphp
									{{--checking subject result--}}
									@if($subjectResult)
										<td>
											<table width="100%">
												<tbody>
												<tr>
													<td width="25%" style="border-right: 1px solid black">{{$subjectResult->obtained}}</td>
													<td width="25%" style="border-right: 1px solid black">{{$subjectResult->letterGrade}}</td>
													<td width="25%">{{$subjectResult->letterGradePoint}}</td>
												</tr>
												</tbody>
											</table>
										</td>
										{{--calculate semester total result--}}
										@php
											$allSubTotalMarks += $subjectResult->total;
											$subTotalObtainedMarks += $subjectResult->obtained;
										@endphp
									@else
										<td>-</td>
									@endif
								@else
									<td>-</td>
								@endif
							@endforeach
						@endif
						{{--single subject all semester total marks--}}
						<th>{{$subTotalObtainedMarks}}</th>
						{{--single subject all semester average marks--}}
						@php $subAvgObtainedMarks = round(($subTotalObtainedMarks/$totalSemester), 2); @endphp
						<th>{{$subAvgObtainedMarks}}</th>
						{{--total marks counter--}}
						@php $allSubObtainedTotalMarks += $subTotalObtainedMarks; @endphp
						{{--average marks counter--}}
						@php $allSubObtainedAvgMarks += $subAvgObtainedMarks; @endphp


						{{--subject result claculation--}}
						@php
							$subPercentage = round(($subAvgObtainedMarks/$subDetails['sub_exam_marks'])*100, 2);
							$subGradeDetails = subjectGradeCalculation((int)$subPercentage, $gradeScaleDetails);
						@endphp
						<td>{{$subPercentage}}</td>
						<td>{{$subGradeDetails?$subGradeDetails['grade']:'N/A'}} </td>
						<td>{{$subGradeDetails?$subGradeDetails['point']:'N/A'}} </td>

					</tr>
				@endforeach
				<tr>
					<th colspan="2">Total:</th>
					<th>{{$allSubExamMarks}}</th>
					{{--checking semester list--}}
					@if($totalSemester>0)
						{{--semester list looping--}}
						@foreach($allSemester as $semester)
							{{--find semester marks summary list--}}
							@php $semesterId = $semester['id']; @endphp
							@php $semResultSummary = (object)(array_key_exists($semesterId, $semResultSummaryList)? $semResultSummaryList[$semesterId]:null) @endphp
							<th>
								<table width="100%" class="text-center">
									<thead>
									<tr>
										<td width="25%" style="border-right: 1px solid black"> {{$semResultSummary?$semResultSummary->total_obtained:'-'}} </td>
										<td width="25%" style="border-right: 1px solid black">-</td>
										<td width="25%">{{$semResultSummary?$semResultSummary->total_gpa:'-'}}</td>
									</tr>
									</thead>
								</table>
							</th>
						@endforeach
					@endif

					{{--final result claculation--}}
					@php
						$percentage = round(($allSubObtainedTotalMarks/$allSubTotalMarks)*100, 2);
						$gradeDetails = subjectGradeCalculation((int)$percentage, $gradeScaleDetails);
					@endphp

					<th>{{$allSubObtainedTotalMarks}}</th>
					<th>{{$allSubObtainedAvgMarks}}</th>
					<th>{{$percentage}}</th>
					<th>{{$gradeDetails?$gradeDetails['grade']:'N/A'}}</th>
					<th>{{$gradeDetails?$gradeDetails['point']:'N/A'}}</th>
				</tr>
				<tr>
					<th colspan="{{8+$totalSemester}}" class="text-center">

						<table width="80%" style="margin: 0 auto">
							<thead>
							<tr>
								<td> Total Marks: {{$allSubTotalMarks}} </td>
								<td> Obtained Marks: {{$allSubObtainedTotalMarks}} </td>
								<td> Highest Marks: {{$finalMeritList[0]}} </td>
								<td> Semester Average Marks: {{$allSubObtainedAvgMarks}} </td>
								<td> Percentage: {{$percentage}} % </td>
								<td> GPA: {{$gradeDetails?$gradeDetails['point']:'N/A'}} </td>
								<td> Merit Position: {{array_search($allSubObtainedTotalMarks, $finalMeritList)+1}} </td>
							</tr>
							</thead>
						</table>

					</th>
				</tr>
			</tbody>
			@else
				<tr>
					<td>No Subject Found</td>
				</tr>
			@endif
		</table>
	@else
		<div class="col-md-12">
			<div  class="alert-warning alert-auto-hide alert fade in text-center" style="opacity: 423.642;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<i class="fa fa-warning"></i>No Student Result Found
			</div>
		</div>
	@endif
@else
	<div class="col-md-12">
		<div  class="alert-warning alert-auto-hide alert fade in text-center" style="opacity: 423.642;">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="fa fa-warning"></i> {{$examResultSheet['msg']}}
		</div>
	</div>
@endif
<script type="text/javascript">
    // statements
    $('#std_final_report').click(function () {
        var std_id = $('#my_std_id').val();
        // dynamic form
        $('<form id="std_final_report_download_form" action="/academics/manage/assessments/final/report-card/single" target="_blank" method="POST"></form>')
            .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
            .append('<input type="hidden" name="std_id" value="'+std_id+'"/>')
            .append('<input type="hidden" name="request_type" value="download"/>').appendTo('body');
        // submit
        $('#std_final_report_download_form').submit();
        // remove form from the body
        $('#std_final_report_download_form').remove();
    });
</script>

