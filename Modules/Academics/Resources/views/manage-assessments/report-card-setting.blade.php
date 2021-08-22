
@extends('academics::manage-assessments.index')

<!-- page content -->
@section('page-content')
	<div class="row">
		<div class="box box-solid">
			<div class="col-md-12">
				<form id="report_card_setting_form" action="{{url('/academics/manage/assessments/report-card/setting/')}}" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<input type="hidden" id="report_setting_id" name="report_setting_id" value="{{$rSetting?$rSetting->id:0}}">


					{{--Report Card Student Image Setting Show or Hide --}}
					<h2 class="page-header">
						<i class="fa fa-info-circle"></i>Student Image
					</h2>
					<div class="row text-center">
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">
									<input type="hidden" name="is_image" value="0">
									<input type="checkbox" id="is_image" name="is_image" value="1" {{$rSetting?($rSetting->is_image==1?'checked':''):''}}>
									<br/>Show Image
								</label>
							</div>
						</div>
					</div>

					{{--report card border setting--}}
					<h2 class="page-header">
						<i class="fa fa-info-circle"></i> Border Setting
						<input type="hidden" name="is_border_color" value="0">
						<label><input type="checkbox" id="bdr" class="my-checkbox" name="is_border_color" {{$rSetting?($rSetting->is_border_color==1?'checked':''):''}} value="1"></label>
					</h2>
					<div class="row text-center">
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label" for="border_width">Report border Width (Pixel)</label>
								<input type="number" id="border_width" name="border_width" value="{{$rSetting?$rSetting->border_width:''}}" required class="form-control bdr">
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label" for="border_type">Report border (Type)</label>
								<select id="border_type" name="border_type" required class="form-control bdr">
									<option value="" disabled selected class="bg-gray-active">Select Border Type</option>
									<option value="solid" {{$rSetting?($rSetting->border_type=='solid'?'selected':''):''}}>Solid</option>
									<option value="dashed" {{$rSetting?($rSetting->border_type=='dashed'?'selected':''):''}}>Dashed</option>
									<option value="dotted" {{$rSetting?($rSetting->border_type=='dotted'?'selected':''):''}}>Dotted</option>
								</select>
							</div>
						</div>
						<div class="col-md-4">
							<div class="form-group">
								<label class="control-label" for="border_color">Report border (Color)</label>
								<input type="color" id="border_color" name="border_color" value="{{$rSetting?$rSetting->border_color:''}}" class="form-control bdr">
							</div>
						</div>
					</div>

				{{--label and watermark setting--}}
					<div class="row">
						{{--report card label setting--}}
						<div class="col-md-6">
							<h2 class="page-header">
								<i class="fa fa-info-circle"></i> Label Setting
								<input type="hidden" name="is_label_color" value="0">
								<label><input type="checkbox" id="lbl" class="my-checkbox" name="is_label_color" {{$rSetting?($rSetting->is_label_color==1?'checked':''):''}} value="1"></label>
							</h2>
							<div class="row text-center">
								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="label_bg_color">Label Background Color</label>
										<input type="color" id="label_bg_color" name="label_bg_color" value="{{$rSetting?$rSetting->label_bg_color:''}}" class="form-control lbl">
									</div>
								</div>

								<div class="col-md-6">
									<div class="form-group">
										<label class="control-label" for="label_font_color">Label Font Color</label>
										<input type="color" id="label_font_color" name="label_font_color" value="{{$rSetting?$rSetting->label_font_color:''}}" class="form-control lbl">
									</div>
								</div>
							</div>
						</div>

						{{--report card watermark setting--}}
						<div class="col-md-6">
							<h2 class="page-header">
								<i class="fa fa-info-circle"></i> Watermark Setting
								<input type="hidden" name="is_watermark" value="0">
								<label><input type="checkbox" id="w_mark" class="my-checkbox" name="is_watermark" {{$rSetting?($rSetting->is_watermark==1?'checked':''):''}} value="1"></label>
							</h2>
							<div class="row text-center">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label" for="wm_opacity">Watermark Opacity</label>
										<input type="text" id="wm_opacity" name="wm_opacity" value="{{$rSetting?$rSetting->wm_opacity:''}}" required class="form-control w_mark">
									</div>
								</div>
								<div class="col-sm-8">
									<div class="form-group">
										<label class="control-label" for="wm_url">Watermark URL</label>
										<input type="text" id="wm_url" name="wm_url" value="{{$rSetting?$rSetting->wm_url:''}}" required class="form-control w_mark">
									</div>
								</div>
							</div>
						</div>
					</div>

					{{--report card table setting--}}
					<h2 class="page-header">
						<i class="fa fa-info-circle"></i> Report Card Table Setting
						<input type="hidden" name="is_table_color" value="0">
						<label><input type="checkbox" id="tbl" class="my-checkbox" name="is_table_color" {{$rSetting?($rSetting->is_table_color==1?'checked':''):''}} value="1"></label>
					</h2>
					<div class="row text-center">
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label" for="tbl_header_tr_bg_color">Table Herder Row (bg)</label>
								<input type="color" id="tbl_header_tr_bg_color" name="tbl_header_tr_bg_color" value="{{$rSetting?$rSetting->tbl_header_tr_bg_color:''}}" class="form-control tbl">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label" for="tbl_header_tr_font_color">Table Herder Row (font)</label>
								<input type="color" id="tbl_header_tr_font_color" name="tbl_header_tr_font_color" value="{{$rSetting?$rSetting->tbl_header_tr_font_color:''}}" class="form-control tbl">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label" for="tbl_even_tr_bg_color">Table Even Row (bg)</label>
								<input type="color" id="tbl_even_tr_bg_color" name="tbl_even_tr_bg_color" value="{{$rSetting?$rSetting->tbl_even_tr_bg_color:''}}" class="form-control tbl">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label" for="tbl_even_tr_font_color">Table Even Row (font)</label>
								<input type="color" id="tbl_even_tr_font_color" name="tbl_even_tr_font_color" value="{{$rSetting?$rSetting->tbl_even_tr_font_color:''}}" class="form-control tbl">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label" for="tbl_odd_tr_bg_color">Table Odd Row (bg)</label>
								<input type="color" id="tbl_odd_tr_bg_color" name="tbl_odd_tr_bg_color" value="{{$rSetting?$rSetting->tbl_odd_tr_bg_color:''}}" class="form-control tbl">
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label" for="tbl_odd_tr_font_color">Table Odd Row (font)</label>
								<input type="color" id="tbl_odd_tr_font_color" name="tbl_odd_tr_font_color" value="{{$rSetting?$rSetting->tbl_odd_tr_font_color:''}}" class="form-control tbl">
							</div>
						</div>
					</div>


					{{--report card signature setting--}}
					<h2 class="page-header">
						<i class="fa fa-info-circle"></i> Report Card Signature Setting
					</h2>
					<div class="row text-center">
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">
									<input type="hidden" name="parent_sign" value="0">
									<input type="checkbox" id="parent_sign" name="parent_sign" value="1" {{$rSetting?($rSetting->parent_sign==1?'checked':''):''}}>
									<br/> Parent Signature
								</label>
							</div>
						</div>
						<div class="col-sm-2">
							<div class="form-group">
								<label class="control-label">
									<input type="hidden" name="teacher_sign" value="0">
									<input type="checkbox" id="teacher_sign" name="teacher_sign" value="1" {{$rSetting?($rSetting->teacher_sign==1?'checked':''):''}}>
									<br/> Teacher Signature
								</label>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="auth_name">Authorization Name (Text)</label>
								<textarea id="auth_name" name="auth_name" rows="3" class="form-control auth" >{{$rSetting?$rSetting->auth_name:''}}</textarea>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="form-group">
								<label class="control-label" for="auth_sign">
									Authorization Signature
									<input disabled type="checkbox" {{$rSetting?($rSetting->auth_sign?'checked':''):''}}>
								</label>
								<input type="file" id="auth_sign" name="auth_sign" class="form-control auth" value="{{$rSetting?$rSetting->auth_sign:''}}">

							</div>
						</div>
					</div>


					{{--form submit and reset --}}
					<div class="box-footer text-right">
						<button id="report_card_submit_btn" type="button" class="btn btn-info">Submit</button>
						<button type="reset" class="pull-left btn btn-default">Reset</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

@section('page-script')
	<script src="http://malsup.github.com/jquery.form.js"></script>

	<script type="text/javascript">
        $(function() { // document ready

            // checking setting
			@if($rSetting)
	            // checking is_border_color
	            @if($rSetting->is_border_color==0)
					$('.bdr').attr('disabled', true);
				@endif
	            // checking is_label_color
	            @if($rSetting->is_label_color==0)
					$('.lbl').attr('disabled', true);
				@endif
	            // checking is_watermark
	            @if($rSetting->is_watermark==0)
					$('.w_mark').attr('disabled', true);
				@endif
	            // checking is_table_color
	            @if($rSetting->is_table_color==0)
					$('.tbl').attr('disabled', true);
	            @endif
			@else
				$('.form-control').attr('disabled', true);
				$('.auth').removeAttr('disabled');
			@endif

            // my-checkbox click action
            $('.my-checkbox').click(function () {
                // my id
                var my_id = $(this).attr('id');
                // checking is selected or not
                if ($(this).is(":checked")) {
                    // active all inputs using this class
                    $('.'+my_id).removeAttr('disabled');
                }else{
                    $('.'+my_id).attr('disabled', true);
                }
            });


            // // submit the report card setting form
            $("#report_card_submit_btn").click(function () {
	            // submit the report card setting form
                $("#report_card_setting_form").ajaxForm({

                    // send before action
	                beforeSend: function() {
		                // show waiting dialog
		                waitingDialog.show('Loading...');
	                },

	                // on success action
                    success:function(data){
                        // hide waiting dialog
                        waitingDialog.hide();

                        // checking
                        if(data.status=='success'){
                            $('#report_setting_id').val(data.setting_id);
                            // success sweet alert
                            swal('Success', data.msg, 'success');
                        }else{
                            // warning sweet alert
                            swal('Warning', data.msg, 'warning');
                        }
                    },

                    // on error action
                    error:function(data){
                        // show waiting dialog
                        waitingDialog.hide();
                        // statements
                        alert('No data response from the server');
                    }
                }).submit();
            });
        });

	</script>
@endsection
