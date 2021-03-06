@extends('admission::layouts.admission-layout')
{{--styles--}}
@section('styles')
	<!-- page styles -->
	@yield('page-styles')
@endsection

@section('content')
	<div class="content-wrapper">
		<section class="content-header">
			<h1> <i class = "fa fa-money" aria-hidden="true"></i> Manage Fees </h1>
			<ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Fees</a></li>
				<li class="active">Manage Fees</li>
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

			<div id="p0">
				<form id="applicant_fees_search_form">
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
										<select id="academic_year" class="form-control academicYear academicChange" name="academic_year" required>
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
										<select id="academic_level" class="form-control academicLevel academicChange" name="academic_level">
											<option value="" selected disabled>--- Select Level ---</option>
										</select>
										<div class="help-block">
											@if ($errors->has('academic_level'))
												<strong>{{ $errors->first('academic_level') }}</strong>
											@endif
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group {{ $errors->has('batch') ? ' has-error' : '' }}">
										<label class="control-label" for="batch">Batch</label>
										<select id="batch" class="form-control academicBatch academicChange" name="batch">
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
									<div class="form-group {{ $errors->has('application_no') ? ' has-error' : '' }}">
										<label class="control-label" for="section">Application No.</label>
										<input type="text" id="application_no" class="form-control" name="application_no" placeHolder="Enter Application No.">
										<div class="help-block">
											@if($errors->has('application_no'))
												<strong>{{ $errors->first('application_no') }}</strong>
											@endif
										</div>
									</div>
								</div>
							</div>
						</div><!--./box-body-->
						<div class="box-footer text-right">
							<button type="submit" class="btn btn-info">Submit</button>
							<button type="reset" class="btn btn-default pull-left">Reset</button>
						</div>
					</div><!--./box-solid-->
				</form>
				{{--manage-enquiry-content-row--}}
				<div id="manage_fees_content_row" class="manage_fees_content_row">
					{{--manage-enquiry-content-will-be-displayed-here--}}
					<div class="alert-auto-hide alert alert-info alert-dismissable" style="opacity: 257.188;">
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
        $(function () {

            $('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });

            $('.my-tab').click(function(){
                $('#manage_fees_content_row').html('');
            });

            $('form#applicant_fees_search_form').on('submit', function (e) {
                e.preventDefault();
                // ajax request
                $.ajax({
                    type: 'GET',
                    cache: false,
                    url: '/admission/find/fees',
                    data: $('form#applicant_fees_search_form').serialize(),
                    datatype: 'html',

                    beforeSend: function() {
                        $('#fees_info_section').html('');
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success: function (data) {
                        // alert(JSON.stringify(data));
                        // statements
                        var manage_fees_content_row=  $('#manage_fees_content_row');
                        manage_fees_content_row.html('');
                        manage_fees_content_row.append(data);
                        waitingDialog.hide();
                    },

                    error:function(data){
                        alert(JSON.stringify(data));
                    }
                });
            });


            jQuery(document).on('change','.academicChange',function(){
                $('#manage_fees_content_row').html('');
            });

            //birth_date picker
            $('#applicant_dob').datepicker({
                autoclose: true
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
                        var op='<option value="" selected>--- Select Batch ---</option>';
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
                        var op ='<option value="">--- Select Section ---</option>';
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