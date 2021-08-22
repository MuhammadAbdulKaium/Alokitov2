@if($applicantProfiles->count()>0 AND $examTaken !==null)
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<form id="applicant_grade_book_form">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<div class="box box-solid">
			<div class="box-header with-border">
				<div class="row">
					<div class="col-sm-6">
						@if($examTaken=='0')
							<a class="btn btn-primary" href="{{url('/admission/assessment/grade-book/import')}}" data-target="#globalModal" data-toggle="modal" data-modal-size="modal-md">
								<i class="fa fa-plus-square" aria-hidden="true"></i> Import
							</a>
						@endif
						<button id="grade_book_export_btn" class="btn btn-primary text-bold" data-key="export" type="button">
							<i class="fa fa-plus-square" aria-hidden="true"></i> Export
						</button>
					</div>
					<div class="col-sm-6 box-tool">
						@if($examTaken=='0')
							<button class="btn generate btn-primary pull-right text-bold" type="submit">Submit</button>
						@endif
					</div>
				</div>
			</div>
			<div class="box-body" style="overflow-x:inherit">
				<table id="example11" class="table table-striped table-bordered table-responsive">
					<thead>
					<tr>
						<th>#</th>
						<th>
							<img class="profile-user-img img-responsive img-circle" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="height:30px; width: 30px">
						</th>
						<th style="width: 120px" class="text-center"><a>Application No</a></th>
						<th><a>Name</a></th>
						<th class="text-center"><a>Academic Details</a></th>
						<th class="text-center"><a>Exam Date</a></th>
						<th class="text-center"><a>Exam Grade (Marks)</a></th>
						<th class="text-center">Action</th>
					</tr>
					</thead>
					<tbody>
					@php $i=1; @endphp
					@foreach($applicantProfiles as $applicant)
						{{--checking applicant payment status--}}
						@if($applicant->payment_status==0)  @continue @endif
						{{--applicant ID--}}
						@php $applicantId = $applicant->applicant_id; @endphp
						{{--table row--}}
						<tr id="row_{{$applicantId}}">
							<td>
								<input type="hidden" name="applicant_list[]" value="{{$applicantId}}" />
								{{$i++}}
							</td>
							{{--get applicant photo--}}
							@php $profilePhoto = $applicant->document('PROFILE_PHOTO'); @endphp
							<td>
								{{--set applicant photo--}}
								<img class="profile-user-img img-responsive img-circle" src="{{URL::asset($profilePhoto?$profilePhoto->doc_path.'/'.$profilePhoto->doc_name:'assets/users/images/user-default.png')}}" alt="No Image" style="height:30px; width: 30px">
							</td>
							<td class="text-center" style="width:25px">{{$applicant->application_no}}</td>
							<td>
								<a href="{{url('/admission/application/'.$applicant->applicant_id)}}">
									{{$applicant->name}}
								</a>
							</td>
							<td class="text-center">
								{{$applicant->academicYear()->year_name}} / {{$applicant->academicLevel()->level_name}} ({{$applicant->batch()->batch_name}})
							</td>
							@php $examDetails = $applicant->examDetails(); @endphp
							<td class="text-center">
								{{date('d M, Y', strtotime($examDetails->exam_date))}}
							</td>
							@php $examGrade = $applicant->grade(); @endphp
							<td class="text-center">
								<div class="input-group text-center">
									@if(!empty($gradeList))
										<input id="applicant_grade_{{$applicantId}}" data-id="{{$applicantId}}" class="form-control mark-input text-center" maxlength="3" name="{{$applicantId}}[applicant_grade]" value="{{$gradeList[$applicantId]['grade_mark']}}" type="text">
									@else
										<input id="applicant_grade_{{$applicantId}}" data-id="{{$applicantId}}" class="form-control mark-input text-center" maxlength="3" name="{{$applicantId}}[applicant_grade]" value="{{$examGrade?$examGrade->applicant_grade:''}}" {{$examTaken=='1'?'disabled':''}} type="text">
									@endif

									<input type="hidden" id="exam_grade_{{$applicantId}}" name="{{$applicantId}}[exam_grade]" value="{{$examDetails?$examDetails->exam_marks:'0'}}">
									<div class="input-group-addon"> / {{$examDetails?$examDetails->exam_marks:'0'}}</div>
								</div>
							</td>
							<td class="text-center">
								@if(!empty($gradeList))
									<p id="btn_{{$applicant->applicant_id}}" class="btn btn-info grade text-bold">Save</p>
									<input id="applicant_grade_id_{{$applicant->applicant_id}}" name="{{$applicantId}}[grade_id]" value="{{$gradeList[$applicantId]['grade_id']}}" type="hidden">
								@else
									@if($examTaken=='0')
										<p id="btn_{{$applicant->applicant_id}}" class="btn btn-info grade text-bold">{{$examGrade?'Update':'Save'}}</p>
										<input id="applicant_grade_id_{{$applicant->applicant_id}}" name="{{$applicantId}}[grade_id]" value="{{$examGrade?$examGrade->id:'0'}}" type="hidden">
									@else
										<p  class="btn btn-default text-bold">Result Published</p>
									@endif
								@endif
							</td>
						</tr>
						@php $i = ($i++); @endphp
					@endforeach
					</tbody>
				</table>
			</div><!-- /.box-body -->
			<div class="box-footer ">
				@if($examTaken=='0')
					<button class="btn btn-primary pull-right submit-assessment text-bold" type="submit">Submit</button>
				@endif
			</div>
		</div><!-- /.box-->
	</form>
@else
	<div class="alert-auto-hide alert alert-warning alert-dismissable" style="opacity: 257.188;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<i class="fa fa-info-circle"></i> No record found. (Note: empty Exam-Setting or Applicants list)
	</div>
@endif

<!-- DataTables -->
<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
<!-- datatable script -->
<script>
    $(function () {
        $("#example1").DataTable();

        $('form#applicant_grade_book_form').on('submit', function (e) {
            e.preventDefault();

            $(this)
                .append('<input type="hidden" name="academic_year" value="'+$('#academic_year').val()+'"/>')
                .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                .append('<input type="hidden" name="academic_batch" value="'+$('#batch').val()+'"/>');

            // ajax request
            $.ajax({
                type: 'POST',
                cache: false,
                url: '/admission/assessment/grade-book/store',
                data: $('form#applicant_grade_book_form').serialize(),
                datatype: 'html',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Submitting...');
                },

                success: function (data) {
                    // checking
                    if(data.status=='success'){
                        var gradeList = data.grade_id_list;
                        // looping
                        for(var key in gradeList){
                            var applicant_id = key;
                            $('#applicant_grade_id_'+applicant_id).val(gradeList[key]);
                            $('#btn_'+applicant_id).html('Update');
                        }
                    }else{
                        alert(data.msg)
                    }
                    // hide dialog
                    waitingDialog.hide();
                },

                error:function(data){
                    alert(JSON.stringify(data));
                }
            });
        });


        $('.grade').click(function () {
            // applicant id
            var applicant_id = $(this).attr('id').replace('btn_','');
            // dynamic html form
            var grade_update =  $('<form id="std_grade_update_form" action="" method="POST"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="applicant_id" value="'+applicant_id+'"/>')
                .append('<input type="hidden" name="applicant_grade" value="'+$('#applicant_grade_'+applicant_id).val()+'"/>')
                .append('<input type="hidden" name="academic_year" value="'+$('#academic_year').val()+'"/>')
                .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                .append('<input type="hidden" name="academic_batch" value="'+$('#batch').val()+'"/>')
                .append('<input type="hidden" name="applicant_grade_id" value="'+$('#applicant_grade_id_'+applicant_id).val()+'"/>');
            ;
            // ajax request
            $.ajax({
                url: '/admission/assessment/grade-book/update',
                type: 'post',
                cache: false,
                data: $(grade_update).serialize(),
                datatype: 'json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Updating...');
                },

                success:function(data){
                    // hide dialog
                    waitingDialog.hide();
                    // checking
                    if(data.status=='success'){
                        $('#applicant_grade_id_'+applicant_id).val(data.grade_id);
                        $('#btn_'+applicant_id).html('Update');
                    }else{
                        alert(data.msg)
                    }
                },
                error:function(data){
                    alert(JSON.stringify(data));
                }
            });
        });


        // replace input values
        $(".mark-input").keyup(function(){
            var my_id = $(this).attr('data-id');
            $('#btn_'+my_id).html('Save');
            var exam_grade_mark = JSON.parse($('#exam_grade_'+my_id).val());
            var my_grade_mark = JSON.parse($(this).val());
            // checking
            if(my_grade_mark>exam_grade_mark){
                $(this).val('');
                alert('Cant be more than assigned Mark');
            }else{
                $(this).attr('value', $(this).val());
            }
        });

        $('#grade_book_export_btn').click(function () {
            var url = '{{url('/admission/assessment/grade-book/export')}}';
            // dynamic html form
            $('<form id="grade_book_export_form" action="'+url+'" method="POST" target="_blank" style="display:none;"></form>')
                .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                .append('<input type="hidden" name="academic_year" value="'+$('#academic_year').val()+'"/>')
                .append('<input type="hidden" name="academic_level" value="'+$('#academic_level').val()+'"/>')
                .append('<input type="hidden" name="batch" value="'+$('#batch').val()+'"/>').appendTo('body').submit();
            // remove form from the body
            $('#grade_book_export_form').remove();
        });
    });
</script>