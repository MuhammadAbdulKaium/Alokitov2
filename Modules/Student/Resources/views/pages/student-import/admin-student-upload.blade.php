@extends('layouts.master')

@section('content')
	{{--datatable style sheet--}}
	<link href="{{ URL::asset('css/datatables/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css"/>
	<div class="content-wrapper">
		<section class="content-header">
			<h1>
				<i class="fa fa-upload"></i> Import Student (Admin)
			</h1>
			<ul class="breadcrumb"><li><a href="/"><i class="fa fa-home"></i>Home</a></li>
				<li><a href="#">Student</a></li>
				<li class="active">Import Student (Admin)</li>
			</ul>
		</section>

		<section class="content">

			@if(Session::has('error'))
				<div class="alert alert-success alert-dismissible alert-auto-hide">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('error') }} </h4>
				</div>
			@elseif(Session::has('success'))
				<div id="w0-success-0" class="alert-success alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('success') }} </h4>
				</div>
			@elseif(Session::has('warning'))
				<div id="w0-success-0" class="alert-warning alert-auto-hide alert fade in" style="opacity: 423.642;">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<h4><i class="icon fa fa-check"></i> {{ Session::get('warning') }} </h4>
				</div>
			@endif


			<div class="col-md-12">
				<ul class="nav nav-pills">
					<li class="my-tab active" id="attendance_upload_tab"><a data-toggle="tab" href="#uploaded_list">Uploaded List
					<li class="my-tab" id="attendance_upload_history_tab"><a data-toggle="tab" href="#new_upload">New Upload</a></li>
				</ul>
				{{--<hr/>--}}
				<br/>
				<div class="tab-content">
					{{--uploaded_list--}}
					<div id="uploaded_list" class="tab-pane fade in active">
						<div class="row">
							<div class="box box-solid">
								<div class="box-header with-border">
									<h3 class="box-title"><i class="fa fa-file-excel-o"></i> Uploaded File List</h3>
								</div><!--./box-header-->
								<div class="box-body">
									<table id="myTable" class="table table-striped table-bordered table-responsive text-center">
										<thead>
										<tr>
											<th>#</th>
											<th>File Name</th>
											<th>Created at</th>
											<th>Status</th>
											<th>Action</th>
										</tr>
										</thead>
										<tbody>
										{{--checking upload list--}}
										@if($uploadList->count()>0)
											{{--upload list looping--}}
											@foreach($uploadList as $index=>$singleUpload)
												<tr>
													<td>{{$index+1}}</td>
													<td>
														{{$singleUpload->name}}
														<a href="{{url('/assets/documents/student-list/'.$singleUpload->file_name.'.'.$singleUpload->mime)}}" title="Download Original File" style="font-size: 15px; margin-right: 15px;" download>
															<i class="fa fa-download" aria-hidden="true"></i>
														</a>
													</td>
													<td>{{date('d M, Y', strtotime($singleUpload->created_at))}}</td>
													<td>
														<i id="status_{{$singleUpload->id}}" class="{{$singleUpload->status==0?'fa fa-ban text-red':'fa fa-check text-green'}}" aria-hidden="true"></i>
													</td>
													<td>
														@if($singleUpload->status==0)
															<a title="Upload" id="{{$singleUpload->id}}" class="std-upload" style="font-size: 15px; margin-right: 15px;">
																<i class="fa fa-upload" aria-hidden="true"></i>
															</a>
															<a id="delete_{{$singleUpload->id}}" href="{{url('/student/service/'.$singleUpload->id.'/delete')}}" title="Delete" onclick="return confirm('Are you sure want to delete this item?');" style="font-size: 15px;">
																<i class="fa fa-trash-o" aria-hidden="true"></i>
															</a>
														@endif
														{{--download uloaded file--}}
														<a id="download_{{$singleUpload->id}}" href="{{url('/assets/documents/student-list/'.$singleUpload->file_name.'_uploaded.'.$singleUpload->mime)}}" title="download uploaded file" style="font-size: 15px; {{$singleUpload->status==0?'display:none':''}}" download>
															Download Uploaded File
															<i class="fa fa-download" aria-hidden="true"></i>
														</a>
													</td>
												</tr>
											@endforeach
										@else
											<tr>
												<td colspan="5">No Records found.</td>
											</tr>
										@endif
										</tbody>
									</table>

								</div>
							</div>
						</div>
					</div>
					{{--new_upload--}}
					<div id="new_upload" class="tab-pane fade in">
						<div class="row">
							<form id="import-student" action="{{url('/student/import/service/store')}}" method="POST" enctype="multipart/form-data">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="box box-solid">
									<div class="box-header with-border">
										<h3 class="box-title"><i class="fa fa-file-excel-o"></i> Select File</h3>
									</div><!--./box-header-->
									<div class="box-body">
										<div class="row">
											<div class="col-sm-12 col-xs-12">
												<div class="form-group field-stumaster-importfile">
													<input type="file" name="student_list" title="Browse Excel File" required>
													<div class="hint-block">[<b>NOTE</b> : Only upload <b>.xlsx</b> file format.]</div>
													<div class="help-block"></div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-sm-12 col-xs-12">
												<div class="callout callout-info">
													<h4><i class="fa fa-bullhorn" aria-hidden="true"></i> Instructions :</h4>
													<ol>
														{{--<li><b>The field with red color are required.</b></li>--}}
														<li>All date must be enter <strong>DD-MM-YYYY</strong> format.</li>
														{{--<li>Student ID is auto generated.</li>--}}
														<li>Birth date must be less than current date.</li>
														<li>Email ID should be in valid email format and unique in the system.</li>
														<li>Max upload records limit is <strong>300</strong>.</li>
														<li>Student import data must match with current application language.</li>
													</ol>
													<h4>
														<strong><a href="#" target="_blank">Click here to download</a></strong>
														sample format of import data in <b>XLSX</b> format.
													</h4>
												</div><!--./callout-->
											</div><!--./col-->
										</div><!--./row-->
									</div><!--./box-body-->

									{{--box footer--}}
									<div class="box-footer">
										<button type="submit" class="btn btn-primary"><i class="fa fa-upload"></i> Import </button>
									</div>
								</div><!--./box-->
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>

	{{--global modal--}}
	<div class="modal" id="globalModal" tabindex="-1" role="dialog" aria-labelledby="esModalLabel" aria-hidden="true" style="width:100%;">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-body">
					<div class="loader">
						<div class="preview" style="padding-left: 40%;">
							<i id="icon_msg" class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
							<h3 id="msg_up">Uploading...</h3>
						</div>
						<div class="progress" style="display:none">
							<div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%">
								0%
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('scripts')
	<script src="{{ URL::asset('js/datatables/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::asset('js/datatables/dataTables.bootstrap.min.js') }}"></script>
	<script>
        $(document).ready(function () {
            $('#myTable').dataTable();

            // std-upload button click action
            $('.std-upload').click(function () {
                // file id
                var my_id = $(this).attr('id');

                $.ajax({
                    url: "{{url('/student/import/service/upload') }}",
                    type: 'POST',
                    cache: false,
                    data: {'file_id': my_id, '_token':'{{csrf_token()}}' }, //see the $_token
                    datatype: 'application/json',

                    beforeSend: function() {
                        // show waiting dialog
                        waitingDialog.show('Uploading ...');
                    },

                    success:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();

                        // checking
                        if(data.status=='success'){
                            // get file id
                            var file_id = data.file_id;
                            // status btn action
                            var status_btn = $('#status_'+file_id);
                            status_btn.removeClass('fa-ban text-red');
                            status_btn.addClass('fa-check text-green');
                            // upload btn action
                            $('#'+file_id).remove();
                            // deleted btn action
                            $('#delete_'+file_id).remove();
                            // download btn action
                            $('#download_'+file_id).show();
                            // sweet alert
                            swal("Success", data.msg, "success");
                        }else{
                            // sweet alert
                            swal("Warning", data.msg, "warning");
                        }
                    },

                    error:function(){
                        // hide waiting dialog
                        waitingDialog.hide();
                        // sweet alert
                        swal("Error", 'Unable to load data form server', "error");
                    }
                });

            });
        });
	</script>
@endsection