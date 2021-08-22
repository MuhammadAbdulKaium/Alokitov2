
@extends('academics::manage-timetable.index')
@section('page-content')

{{--batch string--}}
@php $batchString="Class"; @endphp

	<div class="row">
		<div class="box box-solid">
			<form id="timetableStdSearchForm">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<div class="box-body">
					<div class="row">
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="academic_level">Academic Level</label>
								<select id="level" class="form-control academicLevel" name="level">
									<option value="" selected disabled>--- Select Level ---</option>
									@foreach($allAcademicsLevel as $level)
										<option value="{{$level->id}}">{{$level->level_name}}</option>
									@endforeach
								</select>
								<div class="help-block"></div>
							</div>
						</div>
						<div class="col-sm-3">
							<div class="form-group">
								<label class="control-label" for="batch">{{$batchString}}</label>
								<select id="batch" class="form-control academicBatch" name="batch" onchange="">
									<option value="" selected disabled>--- Select {{$batchString}} ---</option>
								</select>
								<div class="help-block"></div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label" for="section">Section</label>
								<select id="section" class="form-control academicSection" name="section">
									<option value="" selected disabled>--- Select Section ---</option>
								</select>
								<div class="help-block"></div>
							</div>
						</div>
						<div class="col-sm-2">
							<label class="control-label" for="section">Shift</label>
							<select id="shift" class="form-control academicShift" name="shift">
								<option value="" selected disabled>--- Select Shift ---</option>
								<option value="0"> Day </option>
								<option value="1"> Morning </option>
							</select>
							<div class="help-block"></div>
						</div>
						<div class="col-sm-2">
							<label class="control-label" for="section">Action</label>
							<button class="btn btn-success" type="submit" style="padding:4px 50px; font-size:18px;"> Submit </button>
							{{--<button class="btn btn-primary pull-left" type="submit" style="margin: 25px 10px; padding:4px 50px; font-size:18px;"> Submit </button>--}}
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div id="timetableContainer" class="row">
		{{--timetable row will be displayed here--}}
	</div>

@endsection

@section('page-script')
	<script type="text/javascript">
        $(function() { // document ready
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
                        //  refresh timetable row
                        $('#timetableContainer').html('');
                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append(op);
                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');
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

                        //  refresh timetable row
                        $('#timetableContainer').html('');
                        // set value to the academic batch
                        $('.academicSection').html("");
                        $('.academicSection').append(op);
                    },
                    error:function(){
                        // statements
                    },
                });
            });


            // request for section list using batch and section id
            jQuery(document).on('change','.academicSection',function(){
                // get academic level id
                var batch_id = $("#batch").val();
                var section_id = $(this).val();
                var op="";

                $.ajax({
                    url: "{{ url('/academics/find/subjcet') }}",
                    type: 'GET',
                    cache: false,
                    data: {'class_id': batch_id, 'section_id':section_id}, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statements
                    },

                    success:function(data){
                        op+='<option value="" selected disabled>--- Select Subject ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].sub_name+'</option>';
                        }
                        //  refresh timetable row
                        $('#timetableContainer').html('');
                        // set value to the academic batch
                        $('.academicSubject').html("");
                        $('.academicSubject').append(op);
                        //console.log(op);
                    },
                    error:function(){
                        // statements
                    },
                });
            });

            // request for section list using batch and section id
            jQuery(document).on('change','.academicShift',function(){
                $('#timetableContainer').html('');
            });

            // request for section list using batch and section id
            $('form#timetableStdSearchForm').on('submit', function (e) {
                e.preventDefault();
                // get  class section info
                var class_id = $("#batch").val();
                var section_id = $("#section").val();
                var shift_id = $("#shift").val();
                // checking
                if(class_id && section_id && shift_id){
                    // ajax request
                    $.ajax({
                        url: "/academics/timetable/classSectionDayTimetable",
                        type: 'POST',
                        cache: false,
                        data: $('form').serialize(),
                        datatype: 'html',

                        beforeSend: function() {
                            // statements
                        },

                        success:function(data){
                            if(data){
                                $('#timetableContainer').html('');
                                $('#timetableContainer').append(data);
                            }
                        },

                        error:function(data){
                            alert(JSON.stringify(data));
                        }
                    });
                }else{
                    alert('Please double check all inputs are selected.');
                }

            });
        });


	</script>
@endsection
