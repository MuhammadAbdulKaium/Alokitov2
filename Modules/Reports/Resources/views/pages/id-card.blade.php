
@extends('reports::layouts.report-layout')
<style>

	@media print
	{
		.no-print, .no-print *
		{
			display: none !important;
		}
		.logo-wrap {
			display: none; !important;
		}
		.navbar {
			display: none;
		}
	}
</style>
<!-- page content -->
@section('page-content')
	<hr/>
	<ul class="nav nav-pills">
		<li class="my-tab active"><a data-toggle="tab" href="#id_card_default">ID Card</a></li>
		{{--<li class="my-tab"><a data-toggle="tab" href="#id_card_template">Setting (Template)</a></li>--}}
		<li class="my-tab"><a data-toggle="tab" href="#all_template">Setting</a></li>
	</ul>
	<hr/>
	<div class="tab-content">
		{{--id card default format --}}
		<div id="id_card_default" class="tab-pane fade in active">
			<div id="p0">
				<form id="std_id_card_form" action="{{URL::to('reports/student/id-card')}}" method="post">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="row">`
						<div class="col-sm-2">
							<div class="form-group {{ $errors->has('academic_level') ? ' has-error' : '' }}">
								<label class="control-label" for="academic_level">Academic Level</label>
								<select id="academic_level" class="form-control academicLevel academicChange" name="academic_level" required>
									<option value="">Select Level</option>
									@foreach($academic_levels as $academic_level)
										<option value="{{$academic_level->id}}">{{$academic_level->level_name}}</option>
									@endforeach
								</select>
								<div class="help-block">
									@if ($errors->has('academic_level'))
										<strong>{{ $errors->first('academic_level') }}</strong>
									@endif
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group {{ $errors->has('batch') ? ' has-error' : '' }}">
								<label class="control-label" for="batch">Class</label>
								<select id="batch" class="form-control academicBatch academicChange" name="batch" required>
									<option value="" selected disabled>--- Select Class ---</option>
								</select>
								<div class="help-block">
									@if ($errors->has('batch'))
										<strong>{{ $errors->first('batch') }}</strong>
									@endif
								</div>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group {{ $errors->has('section') ? ' has-error' : '' }}">
								<label class="control-label" for="section">Section</label>
								<select id="section" class="form-control academicSection academicChange" name="section" required>
									<option value="" selected disabled>--- Select Section ---</option>
								</select>
								<div class="help-block">
									@if ($errors->has('batch'))
										<strong>{{ $errors->first('batch') }}</strong>
									@endif
								</div>
							</div>
						</div>

						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label" for="section">Roll No</label>
								<input type="text" id="gr_no" class="form-control" name="gr_no">
							</div>
						</div>


						{{--<div class="col-sm-3">--}}
							{{--<div class="form-group {{ $errors->has('font_size') ? ' has-error' : '' }}">--}}
								{{--<label class="control-label" for="font_size">Font Size</label>--}}
								{{--<select id="section" class="form-control" name="font_size" required>--}}
									{{--<option value="">--- Select Font Size ---</option>--}}
									{{--@for($i=9; $i<25; $i++)--}}
										{{--<option @if($i==16) selected @endif value="{{$i}}">{{$i}}</option>--}}
									{{--@endfor--}}
								{{--</select>--}}
								{{--<div class="help-block">--}}
									{{--@if ($errors->has('font_size'))--}}
										{{--<strong>{{ $errors->first('font_size') }}</strong>--}}
									{{--@endif--}}
								{{--</div>--}}
							{{--</div>--}}
						{{--</div>--}}
						{{--<div class="col-sm-3">--}}
							{{--<div class="form-group {{ $errors->has('width') ? ' has-error' : '' }}">--}}
								{{--<label class="control-label" for="width">Width (px)</label>--}}
								{{--<input type="text" name="width" value="320" class="form-control">--}}
								{{--<div class="help-block">--}}
									{{--@if ($errors->has('width'))--}}
										{{--<strong>{{ $errors->first('width') }}</strong>--}}
									{{--@endif--}}
								{{--</div>--}}
							{{--</div>--}}
						{{--</div>--}}
						{{--<div class="col-sm-3">--}}
							{{--<div class="form-group {{ $errors->has('height') ? ' has-error' : '' }}">--}}
								{{--<label class="control-label" for="height">Height (px)</label>--}}
								{{--<input type="text" name="height" value="240" class="form-control">--}}
								{{--<div class="help-block">--}}
									{{--@if ($errors->has('height'))--}}
										{{--<strong>{{ $errors->first('height') }}</strong>--}}
									{{--@endif--}}
								{{--</div>--}}
							{{--</div>--}}
						{{--</div>--}}
						{{--<div class="col-sm-3">--}}
							{{--<div class="form-group {{ $errors->has('margin_bottom') ? ' has-error' : '' }}">--}}
								{{--<label class="control-label" for="margin_bottom">Margin Bottom (px)</label>--}}
								{{--<input type="text" name="margin_bottom" value="300" class="form-control">--}}
								{{--<div class="help-block">--}}
									{{--@if ($errors->has('margin_bottom'))--}}
										{{--<strong>{{ $errors->first('margin_bottom') }}</strong>--}}
									{{--@endif--}}
								{{--</div>--}}
							{{--</div>--}}
						{{--</div>--}}
					</div><!--./box-body-->
					<div class="box-footer text-right">
						<button type="submit" class="btn btn-info" formtarget="_blank">Submit</button>
						<button type="reset" class="pull-left btn btn-default">Reset</button>
					</div>
					<input id="my_page" type="hidden" value="reports"/>
				</form>

				{{--manage-enquiry-content-row--}}
				{{--<div id="applicant_reports_container_row" class="manage-enquiry-content-row">--}}
					{{--manage-enquiry-content-will-be-displayed-here--}}
					{{--<div class="alert-auto-hide alert alert-info alert-dismissable" style="opacity: 257.188;">--}}
						{{--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>--}}
						{{--<i class="fa fa-info-circle"></i>  Please select the required fields from the search form.--}}
					{{--</div>--}}
				{{--</div>--}}
			</div>
		</div>
		{{--template customization --}}
		<div id="id_card_template" class="tab-pane fade in ">
			<div id="p0">
				{{--include all subject manage page--}}
				@include('reports::pages.modals.id-card-template')
			</div>
		</div>


		{{--templagte list --}}
		<div id="all_template" class="tab-pane fade in ">
			<div id="p0">
				{{--include all subject manage page--}}
				@include('reports::pages.modals.id-card-template-list')
			</div>
		</div>

	</div>
</div>
	</div>
	</div>
		<div id="applicant_reports_container_row" class="manage-enquiry-content-row">
			{{--manage-enquiry-content-will-be-displayed-here--}}
			<div class="alert-auto-hide alert alert-info alert-dismissable" style="opacity: 257.188;">
				<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
				<i class="fa fa-info-circle"></i>  Please select the required fields from the search form.
			</div>
		</div>

@endsection

@section('page-script')

	$('.my-tab').click(function(){
		$('#applicant_reports_container_row').html('');
	});

	$("input:checkbox").on('click', function() {
		// in the handler, 'this' refers to the box clicked on
		var $box = $(this);
		// temp type
		var temp_type = $(this).attr('data-key');

		if ($box.is(":checked")) {
			// the name of the box is retrieved using the .attr() method
			// as it is assumed and expected to be immutable
			var group = "input:checkbox[name='temp_id']";
			// the checked state of the group/box on the other hand will change and the current value is retrieved using .prop() method
			$(group).prop("checked", false);
			$box.prop("checked", true);

			// set temp type
			$('#temp_type').val(temp_type);
		} else {
			$('#temp_type').val(0);
			$box.prop("checked", false);
		}
	});


	// id card background color change
	jQuery(document).on('change','.font_color',function(){
	// color
	var color = $(this).val();
	// my id
	var my_id = $(this).attr('id');
	// checking
	if(my_id=='l_font_color'){
	// set id card color
	$('.id-card-font-color-land').css("color", color)
	}else{
	// set id card color
	$('.id-card-font-color-port').css("color", color)
	}
	});


	// id card background color change
	jQuery(document).on('change','.color',function(){
	// color
	var color = $(this).val();
	// my id
	var my_id = $(this).attr('id');
	// checking
	if(my_id=='l_color'){
	// set id card color
	$('.id-card-one-color').css("background-color", color)
	}else{
	// set id card color
	$('.id-card-two-color').css("background-color", color)
	}
	});



	// institute title font size change
	$(".title-font-size").keyup(function(){
	// color
	var font_size = $(this).val();
	// my id
	var my_id = $(this).attr('id');
	// checking
	if(my_id=='l_title_font_size'){
	// set header font size
	$('.inst-font-size-land').css("font-size", font_size+'px');
	}else{
	// set id card color
	$('.inst-name-portrait').css("font-size", font_size+'px');
	}
	});


	// institute body font size change
	$(".body-font-size").keyup(function(){
	// color
	var font_size = $(this).val();
	// my id
	var my_id = $(this).attr('id');
	// checking
	if(my_id=='l_body_font_size'){
	// set header font size
	$('.std-info').css("font-size", font_size+'px');
	}else{
	// set id card color
	$('.std-info-portrait').css("font-size", font_size+'px');
	}
	});

	// change id card width
	$(".width").keyup(function(){
	// width
	var width = $(this).val();
	// my id
	var my_id = $(this).attr('id');
	// checking
	if(my_id=='l_width'){
	// set width
	$('.id-card-one-width').css("width", width+'px');
	}else{
	// set height
	$('.id-card-two-width').css("width", width+'px');
	}
	});

	// institute title font size change
	$(".height").keyup(function(){
	// color
	var height = $(this).val();
	// my id
	var my_id = $(this).attr('id');
	// checking
	if(my_id=='l_height'){
	// set height
	$('.id-card-one-height').css("height", height+'px');
	}else{
	// set height
	$('.id-card-two-height').css("height", height+'px');
	}
	});


	$('form#inst_id_card_setting_form').on('submit', function (e) {
	e.preventDefault();
	// ajax request
	$.ajax({
	type: 'POST',
	cache: false,
	url: '/reports/student/id-card/setting',
	datatype: 'application/json',
	data: new FormData(this),
	processData: false,
	contentType: false,

	beforeSend: function() {
	// show waiting dialog
	waitingDialog.show('Loading...');
	},

	success: function (data) {
	// hide waiting dialog
	waitingDialog.hide();

	// checking
	if(data.status=='success'){
		// setting_id
		$('#temp_setting_id').val(data.setting_id);

		// success msg
		swal('Success',data.msg,'success');
	}else{
		// failed msg
		swal('Warning',data.msg,'warning');
	}

	},

	error:function(data){
	// hide waiting dialog
	waitingDialog.hide();
	// alert
	alert(JSON.stringify(data));
	}
	});
	});

	{{--$('form#std_id_card_form').on('submit', function (e) {--}}
	{{--e.preventDefault();--}}
	{{--// ajax request--}}
	{{--$.ajax({--}}
	{{--type: 'POST',--}}
	{{--cache: false,--}}
	{{--url: '/reports/student/id-card',--}}
	{{--data: $('form#std_id_card_form').serialize(),--}}
	{{--datatype: 'html',--}}

	{{--beforeSend: function() {--}}
	{{--// show waiting dialog--}}
	{{--waitingDialog.show('Loading...');--}}
	{{--},--}}

	{{--success: function (data) {--}}
	{{--alert(JSON.stringify(data));--}}
	{{--statements--}}
	{{--var applicant_reports_container_row=  $('#applicant_reports_container_row');--}}
	{{--applicant_reports_container_row.html('');--}}
	{{--applicant_reports_container_row.append(data);--}}
	{{--waitingDialog.hide();--}}
	{{--},--}}

	{{--error:function(data){--}}
	{{--alert(JSON.stringify(data));--}}
	{{--}--}}
	{{--});--}}
	{{--});--}}



	jQuery(document).on('change','.academicChange',function(){
	$('#applicant_reports_container_row').html('');
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
	var op ='<option value="0" selected disabled>--- Select Level ---</option>';
	for(var i=0; i < data.length; i++){
	// console.log(data[i].level_name);
	op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
	}

	// set value to the academic secton
	$('.academicSection').html("");
	$('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');

	// set value to the academic batch
	$('.academicBatch').html("");
	$('.academicBatch').append('<option value="" selected disabled>--- Select Batch ---</option>');

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
	var op='<option value="" selected disabled>--- Select Class ---</option>';
	for(var i=0; i < data.length; i++){
	op+='<option value="'+data[i].id+'">'+data[i].batch_name+'</option>';
	}

	// set value to the academic batch
	$('.academicBatch').html("");
	$('.academicBatch').append(op);

	// set value to the academic secton
	$('.academicSection').html("");
	$('.academicSection').append('<option value="0" selected disabled>--- Select Section ---</option>');
	},

	error:function(){
	// statement
	}
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
	//
	},

	success:function(data){
	op+='<option value="" selected disabled>--- Select Section ---</option>';
	for(var i=0; i < data.length; i++){
	op+='<option value="'+data[i].id+'">'+data[i].section_name+'</option>';
	}
	// refresh attendance container row
	$('#attendanceContainer').html('');
	// set value to the academic batch
	$('.academicSection').html("");
	$('.academicSection').append(op);

	// set value to the academic secton
	$('.academicSubject').html("");
	$('.academicSubject').append('<option value="" selected disabled>--- Select Subject ---</option>');
	// set value to the academic secton
	$('.academicSession option:first').prop('selected',true);
	},
	error:function(){
	//
	},
	});
	});


	});

	{{--// id card setting form submit here--}}
        $('form#id_card_setting_from').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                cache: false,
                url: '/reports/student/id-card/setting',
				datatype: 'application/json',
				data: new FormData(this),
				processData: false,
				contentType: false,

                beforeSend: function() {
                    {{--// show waiting dialog--}}
                    waitingDialog.show('Loading...');
                },

                success: function (data) {
                    {{--// hide waiting dialog--}}
                    waitingDialog.hide();

                    {{--// checking--}}
                    if(data.status=='success'){
                        // setting_id
                        $('#temp_setting_id').val(data.setting_id);

                        {{--// success msg--}}
                        swal('Success',data.msg,'success');
                    }else{
                        {{--// failed msg--}}
                        swal('Warning',data.msg,'warning');
                    }

                },

                error:function(data){
                    {{--// hide waiting dialog--}}
                    waitingDialog.hide();
                    {{--// alert--}}
                    alert(JSON.stringify(data));
                }
            });
        });


@endsection
