
@extends('academics::manage-assessments.index')

<!-- page content -->
@section('page-content')

	{{--batch string--}}
	@php $batchString="Class"; @endphp

	<div class="row">
		<div class="box box-solid">
			<form id="subject_passing_mark_setting_form" method="POST">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-2 col-md-offset-3">
							<div class="form-group">
								<label class="control-label" for="academic_level">Academic Level</label>
								<select id="academic_level" class="form-control academicLevel" name="academic_level">
									<option value="" selected disabled>--- Select Level ---</option>
									@foreach($allAcademicsLevel as $level)
										<option value="{{$level->id}}">{{$level->level_name}}</option>
									@endforeach
								</select>
								<div class="help-block"></div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label" for="batch">{{$batchString}}</label>
								<select id="batch" class="form-control academicBatch" name="batch">
									<option value="" selected disabled>--- Select {{$batchString}} ---</option>
								</select>
								<div class="help-block"></div>
							</div>
						</div>
						<div class="col-sm-2 text-center">
							<label class="control-label" >Action</label>
							<button class="btn btn-success pull-left" type="submit" style="padding:4px 60px; font-size:18px; "> Submit </button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div id="subject_passing_mark_setting_row" class="row">
		{{--subject_passing_mark_setting table will be displayed here--}}
	</div>
@endsection

@section('page-script')
	<script type="text/javascript">
        $(function() { // document ready

            // request for section list using batch and section id
            $('form#subject_passing_mark_setting_form').on('submit', function (e) {
                // alert('hello');
                e.preventDefault();

                if($('#batch').val()){
                    // ajax request
                    $.ajax({
                        url: "{{ url('/academics/manage/assessments/passing-mark/setting') }}",
                        type: 'POST',
                        cache: false,
                        data: $('form#subject_passing_mark_setting_form').serialize(),
                        datatype: 'html',

                        beforeSend: function() {
                            // statements
                            // show waiting dialog
                            waitingDialog.show('Loading...');
                        },

                        success:function(data){
                            // hide waiting dialog
                            waitingDialog.hide();
                            // replace html
                            var setting_row = $('#subject_passing_mark_setting_row');
                            setting_row.html('');
                            setting_row.append(data);
                        },

                        error:function(data){
                            // show waiting dialog
                            waitingDialog.hide();
                            // statements
                            alert('No data response from the server');
                        }
                    });
                }else{
                    $('#subject_passing_mark_setting_row').html('');
                    alert('Please double check all inputs are selected.');
                }
            });


            // request for batch list using level id
            jQuery(document).on('change','.academicLevel',function(){
                // get academic level id
                var level_id = $(this).val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "{{ url('/academics/find/batch') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': level_id }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },
                    success:function(data){
                        op+='<option value="" selected disabled>--- Select {{$batchString}} ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                        }
                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append(op);
                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');

                        $('.academicSubject').html("");
                        $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                        $('#subject_passing_mark_setting_row').html('');
                    },
                    error:function(){
                        // statements
                    }
                });
            });


            // request for section list using batch id
            jQuery(document).on('change','.academicBatch',function(){
                // get academic level id
                var batch_id = $(this).val();
                var div = $(this).parent();
                var op="";

                $.ajax({
                    url: "{{ url('/academics/find/section') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': batch_id }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        op+='<option value="" selected disabled>--- Select Section ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                        }
                        // set value to the academic batch
                        $('.academicSection').html("");
                        $('.academicSection').append(op);

                        $('.academicSubject').html("");
                        $('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');

                        $('#subject_passing_mark_setting_row').html('');
                    },
                    error:function(){
                        // statements
                    }
                });
            });

        });

	</script>
@endsection
