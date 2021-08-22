<div class="box box-solid">
	@if($studentList->count()>0)
		<form id="student_promote_confirm_form" action="{{url('/student/promote/confirm')}}" method="POST">
			{{--hidden values--}}
			<input type="hidden" name="_token" value="{{csrf_token()}}"/>
			<input id="promo_academic_year" type="hidden" name="academic_year" value="0"/>
			<input id="promo_academic_level" type="hidden" name="academic_level" value="0"/>
			<input id="promo_batch" type="hidden" name="batch" value="0"/>
			<input id="promo_section" type="hidden" name="section" value="0"/>

			<div class="et">
				<div class="box-body table-responsive">
					<div class="box-header with-border">
						<div class="row">
							<div class="col-sm-6">
								<label class="col-sm-3" style="padding-top : 10px;">Mark selected as</label>
								<div class="col-sm-6">
									<div class="form-group">
										<select id="promote_action" class="form-control col-sm-3" name="promote_action" required>
											<option value="" disabled selected>--- Select Action ---</option>
											<option value="PROMOTE">PROMOTE / PASSED</option>
											<option value="REPEAT">REPEAT / FAILED</option>
											<option value="GRADUATE">GRADUATE / PASS-OUT</option>
										</select>
										<div class="help-block"></div>
									</div>
								</div>
							</div>
							<div class="col-sm-6 box-tool">
								<button  class="btn generate btn-primary pull-right" type="submit">Select & Continue</button>
							</div>
						</div>
					</div>
				</div>
				<div class="box-body">
					<table id="example2" class="table table-striped table-bordered table-responsive">
						<thead>
						<tr>
							<th class="text-center">
								<input id="check_all_student" class="select-on-check-all" type="checkbox">
							</th>
							<th class="text-center">GR. No.</th>
							<th>Name</th>
							<th class="text-center">Academic Year</th>
							<th class="text-center">Academic Level</th>
							<th class="text-center">Batch</th>
							<th class="text-center">Section</th>
							{{--<th class="text-center">Completion Status</th>--}}
							{{--<th class="text-center">Current Status</th>--}}
						</tr>
						</thead>
						<tbody>
						@php $i=1; @endphp
						@foreach($studentList as $student)
							<tr>
								<td class="text-center">
									<input class="std_list" name="std_list[{{$student->std_id}}]" type="checkbox">
								</td>
								<td class="text-center">{{$student->gr_no}} </td>
								<td>
									<a href="{{url('student/profile/personal/'.$student->std_id)}}">
										{{$student->first_name." ".$student->middle_name." ".$student->last_name}}
									</a>
								</td>
								<td class="text-center">{{$student->year()->year_name}} </td>
								<td class="text-center">{{$student->level()->level_name}} </td>
								<td class="text-center">{{$student->batch()->batch_name}} </td>
								<td class="text-center">{{$student->section()->section_name}} </td>
{{--								<td class="text-center">{{$student->enroll()->enroll_status}} </td>--}}
{{--								<td class="text-center">{{$student->enroll()->batch_status}} </td>--}}
							</tr>
							@php $i=($i+1); @endphp
						@endforeach
						</tbody>
					</table>
				</div>
				<div class="box-footer ">
					<button class="btn student_promote_confirm_form_submit generate btn-primary pull-right" type="submit">Select & Continue</button>
				</div>
			</div>
		</form>
	@else
		<div class="alert-auto-hide alert alert-warning alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<i class="fa fa-info-circle"></i> No records found.
		</div>
	@endif
</div>


<script>
    $(document).ready(function () {

        $('form#student_promote_confirm_form').on('submit', function () {
            if(verifyStdList()){
                if($('#promote_action').val()){
                    $('#promo_academic_year').val($('#academic_year').val());
                    $('#promo_academic_level').val($('#academic_level').val());
                    $('#promo_batch').val($('#batch').val());
                    $('#promo_section').val($('#section').val());
                }else{
                    alert('Please Select a Promo Action Type');
                    return false
                }
            }else{
                alert('Please select one or more items from the list.');
                return false
            }
        });

        // check_all_student
        $("#check_all_student").click(function () {
            if($(this).is(':checked')){
                $(".std_list").each(function () {
                    $(this).prop('checked', true);
                });
            }else{
                $(".std_list").each(function () {
                    $(this).prop("checked", false);
                });
            }
        });
        // verify check_all_student
        function verifyStdList() {
            var stdListSelected = null;
            $(".std_list").each(function () {
                if($(this).is(':checked')){
                    stdListSelected = "checked";
                    return false;
                }
            });
            // checking
            if(stdListSelected=="checked"){
                return true;
            }else{
                return false;
            }
        }

    });
</script>

