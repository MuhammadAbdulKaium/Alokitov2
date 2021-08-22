
@extends('layouts.master')


@section('styles')
	<!-- DataTables -->
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

{{--batch string--}}
@php $batchString="Class"; @endphp

	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-th-list"></i> Manage | <small>Parent</small>
			</h1>
			<ul class="breadcrumb">
				<li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="/student/">Cadet</a></li>
				<li class="active">Manage Parent</li>
			</ul>
		</section>
		<section class="content">
			<div class="box box-solid">
				{{--<div class="et">--}}
				{{--<div class="box-header with-border">--}}
				{{--<h3 class="box-title"><i class="fa fa-search"></i> Search Student</h3>--}}
				{{--<div class="box-tools">--}}
				{{--<a class="btn btn-success btn-sm" href="/student/profile/create"><i class="fa fa-plus-square"></i> Add</a>--}}
				{{--</div>--}}
				{{--</div>--}}
				{{--</div>--}}
				<form id="parent_manage_search_form">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<div class="box-body">
						<div class="row">
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label" for="academic_year">Academic Year</label>
									<select id="academic_year" class="form-control academicYear" name="academic_year" onchange="">
										<option value="">--- Select Academic Year ---</option>
										@foreach($academicYears as $year)
											<option value="{{$year->id}}">{{$year->year_name}}</option>
										@endforeach
									</select>
									<div class="help-block"></div>
								</div>
							</div>
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label" for="academic_level">Academic Level</label>
									<select id="academic_level" class="form-control academicLevel" name="academic_level">
										<option value="" selected disabled>--- Select Level ---</option>
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
							<div class="col-sm-3">
								<div class="form-group">
									<label class="control-label" for="section">Section</label>
									<select id="section" class="form-control academicSection" name="section">
										<option value="" selected disabled>--- Select Section ---</option>

									</select>
									<div class="help-block"></div>
								</div>
							</div>
						</div>
					</div>
					<!-- ./box-body -->
					<div class="box-footer text-right">
						<button type="reset" class="btn btn-default">Reset</button>
						<button type="submit" class="btn btn-info">Search</button>

					</div>
				</form>
			</div>

			{{--std parent list container--}}
			<div id="parent_list_container"></div>
		</section>
	</div>
@endsection

@section('scripts')
	<!-- DataTables -->
	<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
	<!-- datatable script -->
	<script>
        $(function () {
            $("#example1").DataTable();
            $('#example2').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": false,
                "ordering": true,
                "info": true,
                "autoWidth": false
            });
        });

        jQuery(document).ready(function () {
            jQuery('.alert-auto-hide').fadeTo(7500, 500, function () {
                $(this).slideUp('slow', function () {
                    $(this).remove();
                });
            });
        });


        // request for parent list using batch section id
        $('form#parent_manage_search_form').on('submit', function (e) {
            e.preventDefault();
            // checking
            if($('#batch').val() && $('#section').val() ){
                $(this).append('<input id="parent_manage_request_type" type="hidden" name="request_type" value="search"/>');
                // ajax request
                $.ajax({
                    url: "/student/parent/manage",
                    type: 'POST',
                    cache: false,
                    data: $('form#parent_manage_search_form').serialize(),
                    datatype: 'html',

                    beforeSend: function() {
                        // statements
                        // show waiting dialog
                        waitingDialog.show('Loading...');
                    },

                    success:function(data){
						// alert(JSON.stringify(data));
                        var parent_list_container = $('#parent_list_container');
                        parent_list_container.html('');
                        parent_list_container.append(data);
                        // hide waiting dialog
                        waitingDialog.hide();
                    },

                    error:function(data){
                        //
                    }
                });
				// remove parent_manage_request_type by id
	            $('#parent_manage_request_type').remove();
            }else{
                $('#parent_list_container').html('');
                alert('Please double check all inputs are selected.');
            }

        });


        // request for batch list using level id
        jQuery(document).on('change','.academicYear',function(){
            // console.log("hmm its change");

            // get academic year id
            var year_id = $(this).val();
            var div = $(this).parent();
            var op="";

            $.ajax({
                url: "{{ url('/academics/find/level') }}",
                type: 'GET',
                cache: false,
                data: {'id': year_id }, //see the $_token
                datatype: 'application/json',

                beforeSend: function() {
                    console.log(year_id);

                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
                    op+='<option value="0" selected disabled>--- Select Level ---</option>';
                    for(var i=0;i<data.length;i++){
                        // console.log(data[i].level_name);
                        op+='<option value="'+data[i].id+'">'+data[i].level_name+'</option>';
                    }

                    // set value to the academic secton
                    $('.academicSection').html("");
                    $('.academicSection').append('<option value="" selected disabled>--- Select Section ---</option>');

                    // set value to the academic batch
                    $('.academicBatch').html("");
                    $('.academicBatch').append('<option value="" selected disabled>--- Select {{$batchString}} ---</option>');

                    // set value to the academic batch
                    $('.academicLevel').html("");
                    $('.academicLevel').append(op);
                },

                error:function(){

                }
            });
        });

        // request for batch list using level id
        jQuery(document).on('change','.academicLevel',function(){
            // console.log("hmm its change");

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
                    // console.log(level_id);
                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
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
                },

                error:function(){

                }
            });
        });


        // request for section list using batch id
        jQuery(document).on('change','.academicBatch',function(){
            console.log("hmm its change");

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
                    console.log(batch_id);
                },

                success:function(data){
                    console.log('success');

                    //console.log(data.length);
                    op+='<option value="" selected disabled>--- Select Section ---</option>';
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
	</script>
@endsection
