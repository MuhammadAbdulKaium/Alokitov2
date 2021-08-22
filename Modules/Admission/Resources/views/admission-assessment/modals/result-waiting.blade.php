@if($applicantResultSheet->count()>0)
	<form id="applicant_promote_form">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<input type="hidden" name="page" value="waiting">

		@if($myPage=='result')
			<div class="box-header">
				<div class="row">
					<div class="col-sm-6">
						<label class="col-sm-3" for="promote_action" style="padding-top : 10px;">Mark selected as</label>
						<div class="col-sm-6">
							<div class="form-group">
								<select id="promote_action" class="form-control col-sm-3" name="promote_action" required>
									<option value="" disabled selected>--- Select Action ---</option>
									<option value="APPROVED">Approved</option>
									<option value="MERIT">Merit List</option>
									<option value="WAITING">Waiting List</option>
									<option value="DISAPPROVED">Disapproved List</option>

								</select>
								<div class="help-block"></div>
							</div>
						</div>
					</div>
					<div class="col-sm-6">
						{{--<button id="download-passed-list"  class="btn btn-primary pull-left" type="button">Download PDF</button>--}}
						<div class="btn-group pull-left" style="display:flex;">
							<button id="w4" class="btn btn-info text-bold btn-sm dropdown-toggle" data-toggle="dropdown">
								Download <span class="caret"></span>
							</button>
							<ul id="w5" class="dropdown-menu dropdown-menu-right">
								@for($i=1; $i<$batchList->merit_batch; $i++)
									<li>
										<a class="download-passed-list" data-key="{{$i}}" style="cursor:pointer">Waiting List {{$i}}</a>
									</li>
								@endfor
							</ul>
						</div>
						<button  class="btn btn-primary pull-right" type="submit">Select & Continue</button>
					</div>
				</div>
			</div>
		@endif

		<table id="example1" class="table table-striped table-bordered table-responsive">
			<thead>
			<tr>
				@if($myPage=='result')
					<th class="text-center">
						<input id="check_all_applicant" class="select-on-check-all" type="checkbox">
					</th>
				@endif

				<th class="text-center">#</th>
				<th>
					<img class="profile-user-img img-responsive img-circle" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="height:30px; width: 30px">
				</th>
				<th style="width: 120px" class="text-center"><a>Application No</a></th>
				<th><a>Name</a></th>
				<th class="text-center"><a>Academic Details</a></th>
				<th class="text-center"><a>Exam Date</a></th>
				<th class="text-center"><a>Exam Marks</a></th>
				<th class="text-center"><a>Exam Result</a></th>
				<th class="text-center"><a>Merit Position</a></th>
				<th class="text-center"><a>Action</a></th>
			</tr>
			</thead>
			<tbody>
			@php $i=1; @endphp
			@foreach($applicantResultSheet as $applicantResult)
				{{--applicant ID--}}
				@php $applicant = $applicantResult->application(); @endphp
				{{--table row--}}
				<tr>

					@if($myPage=='result')
						<td class="text-center">
							<input class="applicant_list" name="applicant_list[{{$applicant->id}}]" type="checkbox">
						</td>
					@endif

					<td class="text-center">{{$i++}}</td>
					{{--get applicant photo--}}
					@php $profilePhoto = $applicantResult->document('PROFILE_PHOTO'); @endphp
					<td>
						{{--set applicant photo--}}
						<img class="profile-user-img img-responsive img-circle" src="{{URL::asset($profilePhoto?$profilePhoto->doc_path.'/'.$profilePhoto->doc_name:'assets/users/images/user-default.png')}}" alt="No Image" style="height:30px; width: 30px">
					</td>
					<td class="text-center" style="width:25px">{{$applicant->application_no}}</td>
					<td>
						@php $applicantInfo = $applicant->personalInfo(); @endphp
						<a href="{{url('/admission/application/'.$applicantResult->applicant_id)}}">
							{{$applicantInfo->std_name}}
						</a>
					</td>
					<td class="text-center">
						{{$applicantResult->academicYear()->year_name}} / {{$applicantResult->academicLevel()->level_name}} ({{$applicantResult->batch()->batch_name}})
					</td>
					@php $examDetails = $applicantResult->examDetails(); @endphp
					<td class="text-center">
						{{date('d M, Y', strtotime($examDetails->exam_date))}}
					</td>
					@php $examGrade = $applicantResult->grade(); @endphp
					<td class="text-center"> {{$examGrade->applicant_grade." / ".$examDetails->exam_marks}} </td>
					<td class="text-center">
						@if($applicantResult->applicant_exam_result=='0')
							<p class="label label-danger text-bold">Failed</p>
						@else
							<p class="label label-success text-bold">Passed</p>
						@endif
					</td>
					<td class="text-center">{{$applicantResult->applicant_merit_position}}</td>
					<td class="text-center">
						<div class="btn-group pull-right" style="display:flex;">
							<button id="w4" class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
								Action <span class="caret"></span>
							</button>
							<ul id="w5" class="dropdown-menu dropdown-menu-right">
								<li>
									<a href="#" data-confirm="Are you sure you want to delete this item?" data-method="post" tabindex="-1">Delete</a>
								</li>
								<li>
									<a href="#" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-lg" tabindex="-1">Letter</a>
								</li>
							</ul>
						</div>
					</td>
				</tr>
				@php $i = ($i++); @endphp
			@endforeach
			</tbody>
		</table>

		@if($myPage=='result')
			<div class="row">
				<div class="col-sm-12 box-footer">
					<button  class="btn generate btn-primary pull-right" type="submit">Select & Continue</button>
				</div>
			</div>
		@endif

	</form>
@else
	<div class="alert-auto-hide alert alert-info alert-dismissable" style="opacity: 450.741;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-info-circle"></i>  No Records found.
	</div>
@endif

<script>
    $(document).ready(function () {


        // download result sheet
        $('.download-passed-list').click(function () {
            // dynamic html form
            $('<form id="applicant_result_list_download_form" action="/admission/assessment/result/download" target="_blank" method="POST"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="academic_year" value="'+$("#academic_year").val()+'"/>')
                .append('<input type="hidden" name="academic_level" value="'+$("#academic_level").val()+'"/>')
                .append('<input type="hidden" name="batch" value="'+$("#batch").val()+'"/>')
                .append('<input type="hidden" name="request_merit_batch" value="'+$(this).attr('data-key')+'"/>')
                .append('<input type="hidden" name="request_list_type" value="WAITING"/>').appendTo('body').submit();
            // remove form from the body
            $('#applicant_result_list_download_form').remove();
        });

        $('form#applicant_promote_form').on('submit', function () {
            if(verifyStdList()){

                if($('#promote_action').val()){
                    $(this)
                        .append('<input type="hidden" name="academic_year" value="'+$('#academic_year').val()+'"/>')
                        .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                        .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>');

                    // ajax request
                    $.ajax({
                        type: 'POST',
                        cache: false,
                        url: '/admission/assessment/result/promote',
                        data: $('form#applicant_promote_form').serialize(),
                        datatype: 'html',

                        beforeSend: function() {
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success: function (data) {
                            // statements
                            var applicant_result_list_container =  $('#applicant_result_list_container');
                            applicant_result_list_container.html('');
                            applicant_result_list_container.append(data);
                            waitingDialog.hide();
                        },

                        error:function(data){
                            alert(JSON.stringify(data));
                        }
                    });
                    return false
                }else{
                    alert('Please Select a Promo Action Type');
                    return false
                }
            }else{
                alert('Please select one or more items from the list.');
                return false
            }
        });

        // check_all_applicant
        $("#check_all_applicant").click(function () {
            if($(this).is(':checked')){
                $(".applicant_list").each(function () {
                    $(this).prop('checked', true);
                });
            }else{
                $(".applicant_list").each(function () {
                    $(this).prop("checked", false);
                });
            }
        });

        // verify check_all_applicant
        function verifyStdList() {
            var stdListSelected = null;
            $(".applicant_list").each(function () {
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
