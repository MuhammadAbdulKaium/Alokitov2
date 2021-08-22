@extends('reports::layouts.report-layout')
<!-- page content -->
@section('page-content')
	<!-- grading scale -->
	<div class="col-md-12">
		<h4><strong>College Result</strong></h4>
		<hr/>
		<ul class="nav nav-pills">
			<li class="active"><a data-toggle="tab" href="#my_reports1">Tabulation </a></li>
			<li><a data-toggle="tab" href="#my_reports2">Subject Wise Marks Sheet</a></li>
			<li><a data-toggle="tab" href="#my_reports3">Summary of Grading</a></li>
			<li><a data-toggle="tab" href="#my_reports4">Result Summary</a></li>
			<li><a data-toggle="tab" href="#my_reports5">Student Wise Result Report </a></li>
			<li><a data-toggle="tab" href="#my_reports6">Board Result</a></li>
		</ul>
		<hr/>
		<div class="tab-content">
			<!-- report section -->
			<div id="my_reports1" class="tab-pane fade in active">
				<div class="row">
					<div class="box box-solid">
						<form  action="{{url('/academics/manage/assessment/semester/tabulation-sheet-college')}}" method="post" target="_blank">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<div class="box-body">
								<div class="row">
									<div class="col-sm-2">
										<div class="form-group">
											<label class="control-label" for="academic_level">Academic Level</label>
											<select id="academic_level1" class="form-control academicLevel1" name="academic_level">
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
											<label class="control-label" for="batch">Select Class</label>
											<select id="batch1" class="form-control academicBatch1" name="batch">
												<option value="" selected disabled>--- Select Class ---</option>
											</select>
											<div class="help-block"></div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label class="control-label" for="section">Section</label>
											<select id="section1" class="form-control academicSection1" name="section">
												<option value="" selected disabled>--- Select Section ---</option>
											</select>
											<div class="help-block"></div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label class="control-label" for="semester">Semester</label>
											<select id="semester1" class="form-control academicSemester1" name="semester">
												<option value="" selected disabled>--- Select Semester ---</option>
											</select>
											<div class="help-block"></div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="category">Select Category:</label>
											<select name="category" class="form-control" id="category1" required>
												<option value="">Select Category</option>
												@foreach($allGradeCategory as $category)
													<option value="{{$category->id}}">{{$category->name}}</option>

												@endforeach
											</select>
										</div>
									</div>
								</div>


								<div class="row">
									<div class="col-sm-4">
										<button type="submit" class="btn btn-success">Search</button>
										<button type="reset" class="btn btn-secondary">Reset</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div id="my_reports2" class="tab-pane fade in">
				<div class="row">
					<div class="box box-solid">
						<form action="{{URL::to('/reports/college/student/result')}}" method="POST" target="_blank">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<div class="box-body">
								<div class="row">
									<div class="col-sm-2">
										<div class="form-group">
											<label class="control-label" for="academic_level">Academic Level</label>
											<select id="academic_level2" class="form-control academicLevel2" name="academic_level">
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
											<label class="control-label" for="batch">Select Class</label>
											<select id="batch2" class="form-control academicBatch2" name="batch">
												<option value="" selected disabled>--- Select Class ---</option>
											</select>
											<div class="help-block"></div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label class="control-label" for="section">Section</label>
											<select id="section2" class="form-control academicSection2" name="section">
												<option value="" selected disabled>--- Select Section ---</option>
											</select>
											<div class="help-block"></div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label class="control-label" for="subject">Subject</label>
											<select id="subject2" class="form-control academicSubject2" name="subject">
												<option value="" selected disabled>--- Select Subject ---</option>
											</select>
											<div class="help-block"></div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label class="control-label" for="semester">Semester</label>
											<select id="semester2" class="form-control academicSemester2" name="semester">
												<option value="" selected disabled>--- Select Semester ---</option>
											</select>
											<div class="help-block"></div>
										</div>
									</div>
									<div class="col-sm-2">
										<div class="form-group">
											<label for="category">Select Category:</label>
											<select name="category" class="form-control" id="category2" required>
												<option value="">Select Category</option>
												@foreach($allGradeCategory as $category)
													<option value="{{$category->id}}">{{$category->name}}</option>

												@endforeach
											</select>
										</div>
									</div>
								</div>


								<div class="row">
									<div class="col-sm-4">
										<button type="submit" class="btn btn-success">Search</button>
										<button type="reset" class="btn btn-secondary">Reset</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>

			<div id="my_reports3" class="tab-pane fade in">
				<div class="row">
					<div class="row">
						<div class="box box-solid">
							<form action="{{URL::to('/reports/college/student/subject-wise/summary')}}" method="POST" target="_blank">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="box-body">
									<div class="row">
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="academic_level">Academic Level</label>
												<select id="academic_level3" class="form-control academicLevel3" name="academic_level">
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
												<label class="control-label" for="batch">Select Class</label>
												<select id="batch3" class="form-control academicBatch3" name="batch">
													<option value="" selected disabled>--- Select Class ---</option>
												</select>
												<div class="help-block"></div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="section">Section</label>
												<select id="section3" class="form-control academicSection3" name="section">
													<option value="" selected disabled>--- Select Section ---</option>
												</select>
												<div class="help-block"></div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="semester">Semester</label>
												<select id="semester3" class="form-control academicSemester3" name="semester">
													<option value="" selected disabled>--- Select Semester ---</option>
												</select>
												<div class="help-block"></div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label for="category">Select Category:</label>
												<select name="category" class="form-control" id="category3" required>
													<option value="">Select Category</option>
													@foreach($allGradeCategory as $category)
														<option value="{{$category->id}}">{{$category->name}}</option>

													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="row">
											<div class="col-sm-4">
												<button type="submit" class="btn btn-success">Search</button>
												<button type="reset" class="btn btn-secondary">Reset</button>
											</div>
										</div>
									</div>

							</form>
						</div>
					</div>
				</div>
			</div>

			<div id="my_reports4" class="tab-pane fade in">
				<div class="row">
					<div class="row">
						<div class="box box-solid">
							<form action="{{URL::to('/reports/college/student/result/summary')}}" method="POST" target="_blank">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="box-body">
									<div class="row">

										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="semester">Semester</label>
												<select id="semester4" class="form-control academicSemester4" name="semester">
													<option value="">--- Select Semester ---</option>
													@foreach($allsemester as $semester)
														<option value="{{$semester->id}}">{{$semester->name}}</option>
													@endforeach
												</select>
												<div class="help-block"></div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label for="category">Select Category:</label>
												<select name="category" class="form-control" id="category4" required>
													<option value="">Select Category</option>
													@foreach($allGradeCategory as $category)
														<option value="{{$category->id}}">{{$category->name}}</option>

													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-4">
											<button type="submit" class="btn btn-success">Search</button>
											<button type="reset" class="btn btn-secondary">Reset</button>
										</div>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>
			</div>

			<div id="my_reports5" class="tab-pane fade in">
				<div class="row">
					<div class="row">
						<div class="box box-solid">
							<form action="{{URL::to('/reports/college/student/tutorial-exam-report')}}" method="POST" target="_blank">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="box-body">
									<div class="row">
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="academic_level">Academic Level</label>
												<select id="academic_level5" class="form-control academicLevel5" name="academic_level">
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
												<label class="control-label" for="batch">Select Class</label>
												<select id="batch5" class="form-control academicBatch5" name="batch">
													<option value="" selected disabled>--- Select Class ---</option>
												</select>
												<div class="help-block"></div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="section">Section</label>
												<select id="section5" class="form-control academicSection5" name="section">
													<option value="" selected disabled>--- Select Section ---</option>
												</select>
												<div class="help-block"></div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="semester">Semester</label>
												<select id="semester5" class="form-control academicSemester5" name="semester">
													<option value="" selected disabled>--- Select Semester ---</option>
												</select>
												<div class="help-block"></div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label for="category">Select Category:</label>
												<select name="category" class="form-control" id="category5" required>
													<option value="">Select Category</option>
													@foreach($allGradeCategory as $category)
														<option data-value="{{$category->name}}" value="{{$category->id}}">{{$category->name}}</option>

													@endforeach
												</select>
												<input type="hidden" value="" name="category_name"  id="category_name5">
											</div>
										</div>

                                        <div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="std_name_ct">Student Name / Username</label>
												<div class="form-group">
													<input class="form-control" id="std_name_ct" type="text" placeholder="Type Student Name Or Username">
													<input id="std_id_ct" name="std_id" type="hidden" value="" />
													<div class="help-block"></div>
												</div>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-4">
											<button type="submit" class="btn btn-success">Search</button>
											<button type="reset" class="btn btn-secondary">Reset</button>
										</div>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>
			</div>

			<div id="my_reports6" class="tab-pane fade in">
				<div class="row">
					<div class="row">
						<div class="box box-solid">
							<form action="{{URL::to('/reports/college/student/hsc-exam-report')}}" method="POST" target="_blank">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="box-body">
									<div class="row">

										<div class="col-sm-2">
											<div class="form-group">
												<label class="control-label" for="semester">Semester</label>
												<select id="semester6" class="form-control academicSemester6" name="semester">
													<option value="">--- Select Semester ---</option>
													@foreach($allsemester as $semester)
														<option data-value="{{$semester->name}}" value="{{$semester->id}}">{{$semester->name}}</option>
													@endforeach
												</select>
												<div class="help-block"></div>
											</div>
										</div>
										<div class="col-sm-2">
											<div class="form-group">
												<label for="category">Select Category:</label>
												<select name="category" class="form-control" id="category6" required>
													<option  value="">Select Category</option>
													@foreach($allGradeCategory as $category)
														<option data-value="{{$category->name}}" value="{{$category->id}}">{{$category->name}}</option>

													@endforeach
												</select>
												<input type="hidden" value="" name="semester_name"  id="semester_name6">
												<input type="hidden" value="" name="category_name"  id="category_name6">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-sm-4">
											<button type="submit" class="btn btn-success">Search</button>
											<button type="reset" class="btn btn-secondary">Reset</button>
										</div>
									</div>
								</div>

							</form>
						</div>
					</div>
				</div>
			</div>




		</div>
	</div>
@endsection


@section('page-script')
		$(function() { // document ready

{{--		<script>--}}
			// for report 2.1
			// request for batch list using level id
			jQuery(document).on('change','.academicLevel1',function(){
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
						op+='<option value="" selected disabled>--- Select Class ---</option>';
						for(var i=0;i<data.length;i++){
							op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
						}
						// set value to the academic batch
						$('.academicBatch1').html("");
						$('.academicBatch1').append(op);
						// set value to the academic secton
						$('.academicSection1').html("");
						$('.academicSection1').append('<option value="0" selected disabled>--- Select Section ---</option>');

						$('#assessment_table_row').html('');
						// semester list reset
						resetSemester1();
					},
					error:function(){
						// statements
					}
				});
			});


			// request for section list using batch id
			jQuery(document).on('change','.academicBatch1',function(){
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
						$('.academicSection1').html("");
						$('.academicSection1').append(op);

						$('#assessment_table_row').html('');
						// semester list reset
						resetSemester1();
					},
					error:function(){
						// statements
					},
				});
			});


			// request for section list using batch and section id
			jQuery(document).on('change','.academicSection1',function(){
				// get academic level id
				var batch_id = $("#batch1").val();
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
						$('#assessment_table_row').html('');
						// semester list reset
						resetSemester1();
					},
					error:function(){
						// statements
					},
				});
			});



			// request for section list using batch and section id
			jQuery(document).on('change','.academicSemester1',function(){
				$('#assessment_table_row').html('');
			});


			// reset semester list
			function  resetSemester1() {
				// get academic batch id
				var batch_id = $("#batch1").val();
				// get academic level id
				var level_id = $("#academic_level1").val();
				// select option
				var op="";
				// checking
				if(batch_id && level_id){
					// ajax request
					$.ajax({
						url: "/academics/find/batch/semester",
						type: 'GET',
						cache: false,
						data: {'batch': batch_id, 'academic_level':level_id}, //see the $_token
						datatype: 'application/json',

						beforeSend: function() {
							// statements
						},

						success:function(data){
							op+='<option value="" selected disabled>--- Select Semester ---</option>';
							for(var i=0;i<data.length;i++){
								op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
							}
							// set value to the academic semester
							$('.academicSemester1').html('');
							$('.academicSemester1').append(op);
						},
						error:function(){
							// statements
						},
					});
				}else{
					op+='<option value="" selected disabled>--- Select Semester ---</option>';
					// set value to the academic semester
					$('.academicSemester1').html('');
					$('.academicSemester1').append(op);
				}
			}




		// for report 2.1
			// request for batch list using level id
			jQuery(document).on('change','.academicLevel2',function(){
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
						op+='<option value="" selected disabled>--- Select Class ---</option>';
						for(var i=0;i<data.length;i++){
							op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
						}
						// set value to the academic batch
						$('.academicBatch2').html("");
						$('.academicBatch2').append(op);
						// set value to the academic secton
						$('.academicSection2').html("");
						$('.academicSection2').append('<option value="0" selected disabled>--- Select Section ---</option>');

						$('.academicSubject2').html("");
						$('.academicSubject2').append('<option value="" selected disabled>--- Select Subject ---</option>');

						$('#assessment_table_row').html('');
						// semester list reset
		resetSemester2();
					},
					error:function(){
						// statements
					}
				});
			});


			// request for section list using batch id
			jQuery(document).on('change','.academicBatch2',function(){
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
						$('.academicSection2').html("");
						$('.academicSection2').append(op);

						$('.academicSubject2').html("");
						$('.academicSubject2').append('<option value="" selected disabled>--- Select Subject ---</option>');

						$('#assessment_table_row').html('');
						// semester list reset
		resetSemester2();
					},
					error:function(){
						// statements
					},
				});
			});


			// request for section list using batch and section id
			jQuery(document).on('change','.academicSection2',function(){
				// get academic level id
				var batch_id = $("#batch2").val();
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
						// set value to the academic batch
						$('.academicSubject2').html("");
						$('.academicSubject2').append(op);
						$('#assessment_table_row').html('');
						// semester list reset
		resetSemester2();
					},
					error:function(){
						// statements
					},
				});
			});




			// request for section list using batch and section id
			jQuery(document).on('change','.academicSubject2',function(){
				$('#assessment_table_row').html('');
				// semester reset
						resetSemester2();
			});

			// request for section list using batch and section id
			jQuery(document).on('change','.academicSemester2',function(){
				$('#assessment_table_row').html('');
			});


			// reset semester list
			function  resetSemester2() {
				// get academic batch id
				var batch_id = $("#batch2").val();
				// get academic level id
				var level_id = $("#academic_level2").val();
				// select option
				var op="";
				// checking
				if(batch_id && level_id){
					// ajax request
					$.ajax({
						url: "/academics/find/batch/semester",
						type: 'GET',
						cache: false,
						data: {'batch': batch_id, 'academic_level':level_id}, //see the $_token
						datatype: 'application/json',

						beforeSend: function() {
							// statements
						},

						success:function(data){
							op+='<option value="" selected disabled>--- Select Semester ---</option>';
							for(var i=0;i<data.length;i++){
								op+='<option value="'+data[i].id+'">'+data[i].name+'</option>';
							}
							// set value to the academic semester
							$('.academicSemester2').html('');
							$('.academicSemester2').append(op);
						},
						error:function(){
							// statements
						},
					});
				}else{
					op+='<option value="" selected disabled>--- Select Semester ---</option>';
					// set value to the academic semester
					$('.academicSemester2').html('');
					$('.academicSemester2').append(op);
				}
			}



		// for report 3
		// request for batch list using level id
		jQuery(document).on('change', '.academicLevel3', function() {
			// get academic level id
			var level_id = $(this).val();
			var div = $(this).parent();
			var op = "";

			$.ajax({
				url: "{{ url('/academics/find/batch') }}",
				type: 'GET',
				cache: false,
				data: {
					'id': level_id
				}, //see the $_token
				datatype: 'application/json',

				beforeSend: function() {
					// statements
				},
				success: function(data) {
					op += '<option value="" selected disabled>--- Select Class ---</option>';
					for (var i = 0; i < data.length; i++) {
						op += '<option value="' + data[i].id + '">' + data[i].batch_name + '</option>';
					}
					// set value to the academic batch
					$('.academicBatch3').html("");
					$('.academicBatch3').append(op);
					// set value to the academic secton
					$('.academicSection3').html("");
					$('.academicSection3').append('<option value="0" selected disabled>--- Select Section ---</option>');

					$('.academicSubject3').html("");
					$('.academicSubject3').append('<option value="" selected disabled>--- Select Subject ---</option>');

					$('#assessment_table_row').html('');
					// semester list reset
					resetSemester3();
				},
				error: function() {
					// statements
				}
			});
		});


		// request for section list using batch id
		jQuery(document).on('change', '.academicBatch3', function() {
			// get academic level id
			var batch_id = $(this).val();
			var div = $(this).parent();
			var op = "";

			$.ajax({
				url: "{{ url('/academics/find/section') }}",
				type: 'GET',
				cache: false,
				data: {
					'id': batch_id
				}, //see the $_token
				datatype: 'application/json',

				beforeSend: function() {
					// statements
				},

				success: function(data) {
					op += '<option value="" selected disabled>--- Select Section ---</option>';
					for (var i = 0; i < data.length; i++) {
						op += '<option value="' + data[i].id + '">' + data[i].section_name + '</option>';
					}
					// set value to the academic batch
					$('.academicSection3').html("");
					$('.academicSection3').append(op);

					$('.academicSubject3').html("");
					$('.academicSubject3').append('<option value="" selected disabled>--- Select Subject ---</option>');

					$('#assessment_table_row').html('');
					// semester list reset
					resetSemester3();
				},
				error: function() {
					// statements
				},
			});
		});




		// request for section list using batch and section id
		jQuery(document).on('change', '.academicSubject3', function() {
			$('#assessment_table_row').html('');
			// semester reset
			resetSemester3();
		});

		// request for section list using batch and section id
		jQuery(document).on('change', '.academicSemester3', function() {
			$('#assessment_table_row').html('');
		});


		// reset semester list
		function resetSemester3() {
			// get academic batch id
			var batch_id = $("#batch3").val();
			// get academic level id
			var level_id = $("#academic_level3").val();
			// select option
			var op = "";
			// checking
			if (batch_id && level_id) {
				// ajax request
				$.ajax({
					url: "/academics/find/batch/semester",
					type: 'GET',
					cache: false,
					data: {
						'batch': batch_id,
						'academic_level': level_id
					}, //see the $_token
					datatype: 'application/json',

					beforeSend: function() {
						// statements
					},

					success: function(data) {
						op += '<option value="" selected disabled>--- Select Semester ---</option>';
						for (var i = 0; i < data.length; i++) {
							op += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
						}
						// set value to the academic semester
						$('.academicSemester3').html('');
						$('.academicSemester3').append(op);
					},
					error: function() {
						// statements
					},
				});
			} else {
				op += '<option value="" selected disabled>--- Select Semester ---</option>';
				// set value to the academic semester
				$('.academicSemester3').html('');
				$('.academicSemester3').append(op);
			}
		}


{{--		<script>--}}

			// for report 4
			// request for batch list using level id
			jQuery(document).on('change', '.academicLevel4', function() {
					resetSemester4();
			});




			// reset semester list
			function resetSemester4() {
				// get academic level id
				var level_id = $("#academic_level4").val();
				// select option
				var op = "";
				// checking
				if (batch_id && level_id) {
					// ajax request
					$.ajax({
						url: "/academics/find/batch/semester",
						type: 'GET',
						cache: false,
						data: {
							'batch': batch_id,
							'academic_level': level_id
						}, //see the $_token
						datatype: 'application/json',

						beforeSend: function() {
							// statements
						},

						success: function(data) {
							op += '<option value="" selected disabled>--- Select Semester ---</option>';
							for (var i = 0; i < data.length; i++) {
								op += '<option value="' + data[i].id + '">' + data[i].name + '</option>';
							}
							// set value to the academic semester
							$('.academicSemester4').html('');
							$('.academicSemester4').append(op);
						},
						error: function() {
							// statements
						},
					});
				} else {
					op += '<option value="" selected disabled>--- Select Semester ---</option>';
					// set value to the academic semester
					$('.academicSemester4').html('');
					$('.academicSemester4').append(op);
				}
			}


{{--		<script>--}}

			// for report 5
			// request for batch list using level id
			jQuery(document).on('change','.academicLevel5',function(){
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
						op+='<option value="" selected disabled>--- Select Class ---</option>';
						for(var i=0;i<data.length;i++){
							op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
						}
						// set value to the academic batch
						$('.academicBatch5').html("");
						$('.academicBatch5').append(op);
						// set value to the academic secton
						$('.academicSection5').html("");
						$('.academicSection5').append('<option value="0" selected disabled>--- Select Section ---</option>');

						$('.academicSubject5').html("");
						$('.academicSubject5').append('<option value="" selected disabled>--- Select Subject ---</option>');

						$('#assessment_table_row').html('');
						// semester list reset
						resetSemester5();
					},
					error:function(){
						// statements
					}
				});
			});


			// request for section list using batch id
			jQuery(document).on('change','.academicBatch5',function(){
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
						$('.academicSection5').html("");
						$('.academicSection5').append(op);

						$('.academicSubject5').html("");
						$('.academicSubject5').append('<option value="" selected disabled>--- Select Subject ---</option>');

						$('#assessment_table_row').html('');
						// semester list reset
						resetSemester5();
					},
					error:function(){
						// statements
					},
				});
			});


			// request for section list using batch and section id
			jQuery(document).on('change','.academicSection5',function(){
				// get academic level id
				var batch_id = $("#batch5").val();
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
						// set value to the academic batch
						$('.academicSubject5').html("");
						$('.academicSubject5').append(op);
						$('#assessment_table_row').html('');
						// semester list reset
						resetSemester5();
					},
					error:function(){
						// statements
					},
				});
			});




			// request for section list using batch and section id
			jQuery(document).on('change','.academicSubject5',function(){
				$('#assessment_table_row').html('');
				// semester reset
				resetSemester5();
			});

			// request for section list using batch and section id
			jQuery(document).on('change','.academicSemester5',function(){
				$('#assessment_table_row').html('');
			});


			// reset semester list
			function  resetSemester5() {
				// get academic batch id
				var batch_id = $("#batch5").val();
				// get academic level id
				var level_id = $("#academic_level5").val();
				// select option
				var op="";
				// checking
				if(batch_id && level_id){
					// ajax request
					$.ajax({
						url: "/academics/find/batch/semester",
						type: 'GET',
						cache: false,
						data: {'batch': batch_id, 'academic_level':level_id}, //see the $_token
						datatype: 'application/json',

						beforeSend: function() {
							// statements
						},

						success:function(data){
							op+='<option value="" selected disabled>--- Select Semester ---</option>';
							for(var i=0;i<data.length;i++){
								op+='<option data-value="'+data[i].name+'" value="'+data[i].id+'">'+data[i].name+'</option>';
							}
							// set value to the academic semester
							$('.academicSemester5').html('');
							$('.academicSemester5').append(op);
						},
						error:function(){
							// statements
						},
					});
				}else{
					op+='<option value="" selected disabled>--- Select Semester ---</option>';
					// set value to the academic semester
					$('.academicSemester5').html('');
					$('.academicSemester5').append(op);
				}
			}

			// document ready Student CT Section
			$('#std_name_ct').keypress(function(){
				// clear std_id
				$('#std_id_ct').val('');
				// empty std_report_card_row
				$('#std_report_card_row').html('');


				// autoComplete
				$(this).autocomplete({
					source: loadFromAjax,
					minLength: 1,

					select: function (event, ui) {
						// Prevent value from being put in the input:
						this.value = ui.item.label;
						// Set the next input's value to the "value" of the item.
						$(this).next("input").val(ui.item.id);
						event.preventDefault();
					}
				});

				function loadFromAjax(request, response) {
					// find search term
					var term = $('#std_name_ct').val();
					// checking
					if((term.trim() != '') && (term.length>2)){
						$.ajax({
							url: '/student/find/student',
							dataType: 'json',
							data:{'term': term},

							success: function(data) {
								// you can format data here if necessary
								response($.map(data, function (el) {
									return {
										label: el.name,
										value: el.name,
										id:el.id
									};
								}));
							}
						});
					}
				}
			});


			// check student field is empty or not
			$('#std_name_ct').blur(function(){
				// find search term
				var term = $('#std_name_ct').val();
				// checking
				if((term.trim() == '') && (term.length==0)){
					$('#std_id_ct').val('');
				}
			});


			$("#semester6").change(function(){
				$("#semester_name6").val($("#semester6 option:selected").data('value'));
			});

		$("#category6").change(function(){
				$("#category_name6").val($("#category6 option:selected").data('value'));
			});
		$("#semester5").change(function(){
				$("#category_name5").val($("#semester5 option:selected").data('value'));
			})

		});

@endsection