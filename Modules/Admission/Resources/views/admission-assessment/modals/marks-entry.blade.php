
@if($applicantProfiles->count()>0 AND $examStatus !==null)
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<form id="applicant_grade_book_form">
		<input type="hidden" name="_token" value="{{csrf_token()}}">
		<div class="box box-solid">
			<div class="box-header with-border">
				<div class="row">
					<div class="col-sm-6">

				</div>
			</div>
                <style>
                    th{
                        vertical-align: middle;
                        text-align: center;
                    }
                </style>
			<div class="box-body" style="overflow-x:inherit">
				<table id="example11" class="table table-striped table-bordered table-responsive">
					<thead>
					<tr>
						<th rowspan="2">#</th><th rowspan="2">
							<img class="profile-user-img img-responsive img-circle" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="height:30px; width: 30px">
						</th>
						<th rowspan="2" style="width: 120px" class="text-center"><a>Application No</a></th>
						<th rowspan="2"><a>Name</a></th>

                    @php
                    $sub_count=0;
                        foreach(json_decode($examSettingProfile->exam_subjects_marks,1) as $key=>$examMark){
                                if($key==null){
                                    $sub_count++;
                                }
                            }





                    @endphp

						<th class="text-center" rowspan="1" colspan="{{$sub_count+1}}">Subject Marks </th>
					</tr>
                    <tr>
                       @foreach(json_decode($examSettingProfile->exam_subjects_marks,1) as $key=>$examMark)
                           <th rowspan="1"> {{$key}}( {{$examMark}} )</th>
                        @endforeach
                        <th>Total</th>

                    </tr>
					</thead>
					<tbody>
					@php $i=1; @endphp

					@foreach($applicantProfiles as $applicant)

						{{--checking applicant payment status--}}
						{{--@if($applicant->payment_status!=0)  @break @endif--}}

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

                            @if($applicant->singleResult)

                                @php
                                    $subjectMark=json_decode($applicant->singleResult->subject_marks);
                                @endphp

                            @endif

                            @foreach(json_decode($examSettingProfile->exam_subjects_marks,1) as $key=>$examMark)
                                <td rowspan="1">
                                    {{$applicant->single_result}}
                                    <input type="number" max="{{$examMark}}"
                                         @if($applicant->singleResult)  value="{{$subjectMark->$key}}" @endif
                                                       name="marks[{{$applicantId}}][{{$key}}]"></td>
                            @endforeach

                            <td>

                                <input type="text"  @if($applicant->singleResult)
                                         value="{{$applicant->singleResult->applicant_grade}}"
                                       @endif
                                       name="total[{{$applicantId}}]"
                                ></td>



						</tr>
						@php $i = ($i++); @endphp
					@endforeach
					</tbody>
				</table>
			</div><!-- /.box-body -->
			<div class="box-footer ">
				@if($examStatus==true)
					<button class="btn btn-primary pull-right submit-assessment text-bold" type="submit">Submit</button>
				@endif
			</div>
		</div>
        </div>
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
        $('#all_select').change(function (event) {

            let val= $(this).prop('checked')
            let txt= $(this).text();


            if(val){
                $(".Muumuu").prop('checked', true);
            }else {
                $(".Muumuu").prop('checked', false);
            }

        });

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
                url: '/admission/marks/store',
                data: $('form#applicant_grade_book_form').serialize(),
                datatype: 'html',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Submitting...');
                },

                success: function (data) {
                    // checking
                    if(data.status=='success'){
                        console.log(data);
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
                    waitingDialog.hide();
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
                url: '/admission//marks/store',
                type: 'post',
                cache: false,
                data: $(grade_update).serialize(),
                datatype: 'json',

                beforeSend: function() {
                    // show waiting dialog
                    waitingDialog.show('Updating...');
                },

                success:function(data){
                    console.log(data)
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