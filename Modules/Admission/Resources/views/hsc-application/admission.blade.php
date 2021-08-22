@extends('admission::layouts.admission-layout')
<!-- page content -->
@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1> <i class = "fa fa-users" aria-hidden="true"></i> Manage Admission </h1>
			<ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Enquiry</a></li>
				<li class="active">Manage Admission</li>
			</ul>
		</section>
		<section class="content">
			@if(Session::has('success'))
				<div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
				</div>
			@elseif(Session::has('alert'))
				<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('alert') }} </h4>
				</div>
			@elseif(Session::has('warning'))
				<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
				</div>
			@endif

			<style>
				.admission_report { display: none; }
			</style>

			<div id="p0">
				<form id="applicant_enquiry_search_form">
					{{--csrf token--}}
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<input type="hidden" name="search_type" value="admission">
					<div class="box box-solid">
						<div class="box-header with-border">
							<h3 class="box-title">
								<i class = "fa fa-filter" aria-hidden="true"></i> Filter Options
							</h3>
						</div>
						<div class="box-body">
							<div class="row">
								<div class="col-sm-3">
									<div class="form-group {{ $errors->has('academic_year') ? ' has-error' : '' }}">
										<label class="control-label" for="academic_year">Academic Year</label>
										<select id="academic_year" class="form-control academicYear academicChange" name="year" required>
											<option value="" selected disabled>--- Select Year ---</option>
											@foreach($academicYears as $academicYear)
												<option value="{{$academicYear->id}}">{{$academicYear->year_name}}</option>
											@endforeach
										</select>
										<div class="help-block">
											@if ($errors->has('academic_year'))
												<strong>{{ $errors->first('academic_year') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group {{ $errors->has('academic_level') ? ' has-error' : '' }}">
										<label class="control-label" for="academic_level">Academic Level</label>
										<select id="academic_level" class="form-control academicLevel academicChange" name="level" required>
											<option value="" selected disabled>--- Select Level ---</option>
										</select>
										<div class="help-block">
											@if ($errors->has('academic_level'))
												<strong>{{ $errors->first('academic_level') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-3 disabled">
									<div class="form-group {{ $errors->has('batch') ? ' has-error' : '' }}">
										<label class="control-label" for="batch">Batch</label>
										<select id="batch" class="form-control academicBatch academicChange" name="batch" required>
											<option value="" selected disabled>--- Select Batch ---</option>
										</select>
										<div class="help-block">
											@if ($errors->has('batch'))
												<strong>{{ $errors->first('batch') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group {{ $errors->has('applicant_status') ? ' has-error' : '' }}">
										<label class="control-label" for="applicant_status">Applicant Type</label>
										<select id="applicant_status" class="form-control applicantStatus academicChange" name="status" required>
											<option value="" selected disabled>--- Select Section ---</option>
											<option value="0" >Pending</option>
											<option value="1" >Active</option>
										</select>
										<div class="help-block">
											@if ($errors->has('applicant_status'))
												<strong>{{ $errors->first('applicant_status') }}</strong>
											@endif
										</div>
									</div>
								</div>
							</div>

						</div><!--./box-body-->
						<div class="box-footer text-right">
							<div class="col-md-4">
								<button type="reset" class="btn btn-default pull-left">Reset</button>
							</div>
							<div id="admission_report" class="col-md-4 text-center">
								{{--<button type="button" class="btn btn-warning admission_report" >Board-2</button>--}}
								{{--<button type="button" class="btn btn-warning admission_report" >Board-1</button>--}}
								<button type="button" class="btn btn-warning admission_report" >PDF</button>
							</div>
							<div class="col-md-4">
								<button type="submit" class="btn btn-info">Submit</button>
							</div>
						</div>
					</div><!--./box-solid-->
				</form>
				{{--manage-enquiry-content-row--}}
				<div id="manage_enquiry_content_row" class="manage-enquiry-content-row">
					{{--manage-enquiry-content-will-be-displayed-here--}}
					<div class="alert-auto-hide alert alert-info" style="opacity: 257.188;">
						<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
						<i class="fa fa-info-circle"></i>  Please select the required fields from the search form.
					</div>
				</div>
			</div>
		</section>
	</div>
@endsection

@section('scripts')
	<script>
        $(document).ready(function () {

            $('form#applicant_enquiry_search_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                $.ajax({
                    type: 'POST',
                    cache: false,
                    url: '/admission/hsc/find',
                    data: $('form#applicant_enquiry_search_form').serialize(),
                    datatype: 'html',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success: function (data) {
                        // hide waiting dialog
                        waitingDialog.hide();
                        // statements
                        var manage_enquiry_content_row=  $('#manage_enquiry_content_row');
                        manage_enquiry_content_row.html('');
                        manage_enquiry_content_row.append(data);

                        $('.admission_report').show();
                    },

                    error:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // alert json stringify data
                        alert(JSON.stringify(data));
                    }
                });
            });


            // on change div show batch section here
            $(".admission_report").click(function () {

                // find request details
                var year = $('#academic_year').val();
                var level = $('#academic_level').val();
                var batch = $('#batch').val();
                var status = $('#applicant_status').val();

                var year_name = $('#academic_year option:selected').text();
                var level_name = $('#academic_level option:selected').text();
                var batch_name = $('#batch option:selected').text();
                var status_name = $('#applicant_status option:selected').text();

                // checking
                if(year && level && batch && status){
                    // dynamic form
                    $('<form id="admission_report_form" action="/admission/hsc/applicant/admission-report" method="POST" target="_blank"></form>')
                        .append('<input type="hidden" name="_token" value="{{csrf_token()}}"/>')
                        .append('<input type="hidden" name="year" value="'+year+'"/>')
                        .append('<input type="hidden" name="level" value="'+level+'"/>')
                        .append('<input type="hidden" name="batch" value="'+batch+'"/>')
                        .append('<input type="hidden" name="status" value="'+status+'"/>')

                        .append('<input type="hidden" name="year_name" value="'+year_name+'"/>')
                        .append('<input type="hidden" name="level_name" value="'+level_name+'"/>')
                        .append('<input type="hidden" name="batch_name" value="'+batch_name+'"/>')
                        .append('<input type="hidden" name="status_name" value="'+status_name+'"/>')

                        .appendTo('body').submit().remove();
                }else{
                    alert('Please check all inputs are selected');
                }
            });






            jQuery(document).on('change','.academicChange',function(){
                $('#manage_enquiry_content_row').html('');
                $('.admission_report').hide();
            });

            //birth_date picker
            $('#applicant_dob').datepicker({
                autoclose: true
            });

            jQuery(document).ready(function () {
                jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                    $(this).slideUp('slow', function () {
                        $(this).remove();
                    });
                });
            });

            // request for batch list using level id
            jQuery(document).on('change','.academicYear',function(){
                $.ajax({
                    url: "{{ url('/academics/find/level') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': $(this).val() }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statement
                    },

                    success:function(data){
                        //console.log(data.length);
                        var op ='<option value="0" selected>--- Select Level ---</option>';
                        for(var i=0;i<data.length;i++){
                            // console.log(data[i].level_name);
                            op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                        }

                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="" selected>--- Select Section ---</option>');

                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append('<option value="" selected>--- Select Batch ---</option>');

                        // set value to the academic batch
                        $('.academicLevel').html("");
                        $('.academicLevel').append(op);
                    },

                    error:function(){
                        // statement
                    }
                });
            });

            // request for batch list using level id
            jQuery(document).on('change','.academicLevel',function(){
                // ajax request
                $.ajax({
                    url: "{{ url('/academics/find/batch') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': $(this).val() }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statement
                    },

                    success:function(data){
                        var op='<option value="" selected >--- Select Batch ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
                        }

                        // set value to the academic batch
                        $('.academicBatch').html("");
                        $('.academicBatch').append(op);

                        // set value to the academic secton
                        $('.academicSection').html("");
                        $('.academicSection').append('<option value="0" selected>--- Select Section ---</option>');
                    },

                    error:function(){
                        // statement
                    }
                });
            });

            // request for section list using batch id
            jQuery(document).on('change','.academicBatch',function(){
                $.ajax({
                    url: "{{ url('/academics/find/section') }}",
                    type: 'GET',
                    cache: false,
                    data: {'id': $(this).val() }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // statement
                    },

                    success:function(data){
                        var op ='<option value="" selected>--- Select Section ---</option>';
                        for(var i=0;i<data.length;i++){
                            op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
                        }

                        // set value to the academic batch
                        $('.academicSection').html("");
                        $('.academicSection').append(op);
                    },

                    error:function(){

                    },
                });
            });

        });
	</script>

@endsection
