{{--checking grade list count--}}
@if($gradeList->count()>0)
	<div class="col-md-12">
		<h5 class="text-bold text-center bg-aqua-active">Academic Grade (Scale) List</h5>
		<form id="grade_assign_form">
			<input type="hidden" name="_token" value="{{csrf_token()}}" />
			<table class="table table-responsive table-bordered table-striped">
				<thead>
				<tr>
					<th class="text-center">#</th>
					<th>Name</th>
				</tr>
				</thead>
				<tbody>
				@foreach($gradeList as $gradeProfile)
					@php
						$gradeAssignment = $gradeProfile->checkGradeAssign($academicInfo->level,$academicInfo->batch);
					@endphp
					<tr>
						<td class="text-center {{$gradeAssignment?'bg-success':''}}">
							<input type="radio" class="checkbox-grade-scale" name="scale_id" value="{{$gradeProfile->id}}" {{$gradeAssignment? 'checked':''}}/>
						</td>
						<td class="{{$gradeAssignment?'bg-success':''}}">{{$gradeProfile->name}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
			<div id="grade_assign_table_submit_btn" class="modal-footer hide">
				<button class="btn btn-primary pull-right" type="submit">Submit</button>
			</div>
		</form>
	</div>
@else
	<div class="alert-warning alert-auto-hide alert fade in" style="opacity: 474.119;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h6><i class="fa fa-warning"></i> No records found. </h6>
	</div>
@endif

<script type="text/javascript">
    $(document).ready(function(){

        $('.checkbox-grade-scale').click(function () {
            if($(this).is(":checked")){
				$('#grade_assign_table_submit_btn').removeClass('hide');
            }
        });

        // request for section list using batch and section id
        $('form#grade_assign_form').on('submit', function (e) {
            e.preventDefault();

            // add request type to the form
            $(this).append('<input type="hidden" name="level_id" value="{{$academicInfo->level}}" />');
            $(this).append('<input type="hidden" name="batch_id" value="{{$academicInfo->batch}}" />');
            $(this).append('<input type="hidden" name="request_type" value="ASSIGN" />');

            // ajax request
            $.ajax({
                url: '/academics/manage/assessments/grade/scale/manage-assign',
                type: 'POST',
                cache: false,
                data: $('form#grade_assign_form').serialize(),
                datatype: 'html',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Submitting...');
                },

                success:function(data){
                    // hide waiting dialog
                    waitingDialog.hide();
                    //  refresh timetable row
                    $('#semester-list-container').html('');
                    $('#semester-list-container').html(data);
                },

                error:function(data){
                    // statements
                    alert('Unable to perform the action')
                }
            });
        });
    });
</script>