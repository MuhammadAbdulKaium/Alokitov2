{{--modal script--}}
<style type="text/css">
	.ui-autocomplete {z-index:2147483647;}
	.ui-autocomplete span.hl_results {background-color: #ffff66;}
</style>
<link href="{{ asset('css/bootstrap-datepicker3.css') }}" rel="stylesheet">

{{--modal-header--}}
<div class="modal-header">
	<button aria-label="Close" data-dismiss="modal" class="close" type="button"><span aria-hidden="true">×</span></button>
	<h4 class="modal-title"><i class="fa fa-plus-square"></i> Student Report card</h4>
</div>

{{--modal-body--}}
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<ul class="nav nav-tabs">
				<li class="active"><a data-toggle="pill" href="#summary-report">Summary Report</a></li>
				<li><a data-toggle="pill" href="#details-report">Details Report</a></li>
				<li><a data-toggle="pill" href="#final-report">Final Report</a></li>
			</ul>
			{{--tab content--}}
			<div class="tab-content">
				{{--summary report--}}
				<div id="summary-report" class="tab-pane fade in active">
					<form action="{{url('/academics/manage/assessments/report-card/download/')}}" method="POST" target="_blank">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="hidden" name="report_type" value="summary">
						{{--report type can be summary and details--}}
						<div class="row">
							<div class="col-sm-6">
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

							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="batch">Batch</label>
									<select id="batch" class="form-control academicBatch" name="batch" onchange="">
										<option value="" selected disabled>--- Select Batch ---</option>
									</select>
									<div class="help-block"></div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="section">Section</label>
									<select id="section" class="form-control academicSection" name="section">
										<option value="" selected disabled>--- Select Section ---</option>
									</select>
									<div class="help-block"></div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="semester">Semester</label>
									<select id="semester" class="form-control" name="semester" required>
										<option value="">--- Select Semester ---</option>
										@foreach($allSemesterList as $semester)
											<option value="{{$semester->id}}">{{$semester->name}}</option>
										@endforeach
									</select>
									<div class="help-block"></div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="report_format"> Report Format</label>
									<select id="report_format" class="form-control" name="report_format" required>
										<option value="">--- Report Format ---</option>
										<option value="0"> Default </option>
										<option value="1"> W/A (Detail) </option>
										<option value="2"> W/A (Summary) </option>
										<option value="3"> W/A (Group) </option>
										<option value="4"> W/A new Sumary</option>
									</select>
									<div class="help-block"></div>
								</div>
							</div>

							<div class="col-sm-6">
								<div class="form-group">
									<label class="control-label" for="doc_type">Type</label>
									<select id="doc_type" class="form-control" name="doc_type" required>
										<option value="" disabled>--- Report Type ---</option>
										<option value="pdf" selected> PDF </option>
									</select>
									<div class="help-block"></div>
								</div>
							</div>
						</div>

						{{--submit--}}
						<div class="row">
							<div class="modal-footer">
								<button type="submit" class="btn btn-info pull-left">Submit</button>
								<button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
							</div>
						</div>
					</form>
				</div>


				{{--details report--}}
				<div id="details-report" class="tab-pane fade">
					<form action="{{url('/academics/manage/assessments/report-card/download/')}}" method="POST" target="_blank">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="hidden" name="report_type" value="subject_detail">
						<input type="hidden" name="doc_type" value="pdf">

						<div class="row">
							<div class="col-sm-8">
								<label class="control-label">Select Student</label>
								<div class="form-group">
									<input class="form-control" id="std_name" type="text" placeholder="Type Student Name">
									<input id="std_id" name="std_id" type="hidden" value="" />
									<div class="help-block"></div>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label" for="report_format"> Report Format</label>
									<select id="report_format" class="form-control" name="report_format" required>
										<option value="">--- Report Format ---</option>
										<option value="0"> Default </option>
										<option value="1"> W/A (Detail) </option>
										<option value="2"> W/A (Summary) </option>
									</select>
									<div class="help-block"></div>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="modal-footer">
								<button type="submit" class="btn btn-info pull-left">Submit</button>
								<button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
							</div>
						</div>
					</form>
				</div>

				{{--summary report--}}
				<div id="final-report" class="tab-pane fade in">
					<form action="{{url('/academics/manage/assessments/final/report-card/batch/section')}}" method="POST" target="_blank">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input type="hidden" name="request_type" value="download">
						{{--report type can be summary and details--}}
						<div class="row">
							<div class="col-sm-4">
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

							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label" for="batch">Batch</label>
									<select id="batch" class="form-control academicBatch" name="batch" onchange="">
										<option value="" selected disabled>--- Select Batch ---</option>
									</select>
									<div class="help-block"></div>
								</div>
							</div>

							<div class="col-sm-4">
								<div class="form-group">
									<label class="control-label" for="section">Section</label>
									<select id="section" class="form-control academicSection" name="section">
										<option value="" selected disabled>--- Select Section ---</option>
									</select>
									<div class="help-block"></div>
								</div>
							</div>
						</div>

						{{--submit--}}
						<div class="row">
							<div class="modal-footer">
								<button type="submit" class="btn btn-info pull-left">Submit</button>
								<button data-dismiss="modal" class="btn btn-default pull-right" type="button">Close</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script src="{{ asset('js/bootstrap-datepicker.js') }}" type="text/javascript"></script>
<script type="text/javascript">
    $(function() {

        $('#std_name').keypress(function(){
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
                var term = $("#std_name").val();
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
        });


        // summary report section
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
                    op+='<option value="" selected disabled>--- Select Batch ---</option>';
                    for(var i=0;i<data.length;i++){
                        op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                    }
                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append(op);
                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');
                    $('#assessment_table_row').html('');
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
                    $('#assessment_table_row').html('');
                },
                error:function(){
                    // statements
                },
            });
        });


    });
</script>