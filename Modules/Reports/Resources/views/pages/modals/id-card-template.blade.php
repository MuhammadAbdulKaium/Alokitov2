
{{--template setting--}}
@php
	$tempSetting = null;
	$tempType = null;
@endphp

{{--checking template profle--}}
@if($templateProfile)
	@php
		$tempSetting = json_decode($templateProfile->setting);
		$tempType =  $templateProfile->temp_type;
	@endphp
@endif

<style>



	{{--.id-card-two{--}}
		{{--/*border: 1px solid red;*/--}}
		{{--color: {{$tempSetting?($tempType==1?($tempSetting->font_color):'#000000'):'#000000'}};--}}
		{{--padding: 10px;--}}
		{{--border-radius: 5px;--}}
		{{--margin-bottom: 5px;--}}
		{{--width: {{$tempSetting?($tempType==1?($tempSetting->width.'px'):'250px'):'250px'}};--}}
		{{--height: {{$tempSetting?($tempType==1?($tempSetting->height.'px'):'350px'):'350px'}};--}}
		{{--background-color: {{$tempSetting?($tempType==1?($tempSetting->color):'#a3cde7'):'#a3cde7'}};--}}
		{{---webkit-box-shadow: 3px 0px 162px -17px rgba(194,186,194,0.76);--}}
		{{---moz-box-shadow: 3px 0px 162px -17px rgba(194,186,194,0.76);--}}
		{{--box-shadow: 3px 0px 162px -17px rgba(194,186,194,0.76);--}}
	{{--}--}}






	/*Start Landsacape one Section */

	.land_one_FrontSide {
		width: {{$tempSetting?($tempType==0?($tempSetting->width.'px'):'350px'):'350px'}};
		height: {{$tempSetting?($tempType==0?($tempSetting->height.'px'):'210px'):'210px'}};
		background-color: {{$tempSetting?($tempType==0?($tempSetting->color):'#a3cde7'):'#a3cde7'}};
		border: 2px solid #3c3c3c;
		padding: 5px;
		float: left;
	}
	.land_one_school_seciton {
		width: 100%;
		float: left;
		border-bottom: 2px solid #efefef;
		padding-bottom: 10px;
	}

	.land_one_school_logo {
		width: 70px;
		text-align: center;
		float: left;
		width: 20%;
	}

	.land_one_logo {
		width: 70px;
		height: 50px;
	}
	.land_one_school_info{
		float: left;
		text-align: center;
		width: 80%;
		line-height: 15px;
		color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}};
	}

	p.land_one_port_one_schoolName {
		text-align: center;
		text-transform: uppercase;
		font-weight: bold;
		font-size: {{$tempSetting?($tempType==0?($tempSetting->title_font.'px'):'20px'):'20px'}};
		color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}};
		margin-top: 15px;
	}
	p.land_one_address {
		text-align: center;
		line-height: 0px;
		font-size: 13px;
		color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}};
	}
	.land_one_profilePic{
		text-align: center;
		margin-top: 10px;
		width: 20%;
		float: left;
		color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}};
	}
	.land_one_proifleImage {
		height: 60px;
		width: 63px;
		border: 2px solid #efefef;
		color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}};
	}

	.land_one_studentInfo_and_image {
		margin-top: 10px;
	}
	.land_one_studentInfo {
		width: 74%;
		float: left;
		margin-left: 16px;
		color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}};
	}
	p.land_one_normaltext {
		font-size: 12px;
		line-height: 6px;
		color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}};
	}

	p.principal {
		float: right;
		padding: 10px;
		font-size: 12px;
		color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}};
	}

	/*back Side design Html Css*/
	.land_one_port_one_backSide {
		float: left;
		width: {{$tempSetting?($tempType==0?($tempSetting->width.'px'):'350px'):'350px'}};
		height: {{$tempSetting?($tempType==0?($tempSetting->height.'px'):'210px'):'210px'}};
		background-color: {{$tempSetting?($tempType==0?($tempSetting->color):'#a3cde7'):'#a3cde7'}};
		border: 2px solid #3c3c3c;
		padding: 5px;
		margin-left: 50px;
	}

	p.land_one_port_one_stduentInfoTitle {
		font-size: 14px;
		font-weight: bold;
		line-height: 20px;
		text-align: center;
		border-bottom: 2px solid #3c3c3c;
		color: {{$tempSetting?($tempType==0?($tempSetting->font_color):'#000000'):'#000000'}};
	}
	p.found{
		margin-top: 45px;
		text-align: center;
		border-top: 2px solid #ccc;
		line-height: 25px;
	}
	p.phone {
		font-size: 12px;
		text-align: center;
	}
	.land_one_schoolLogo {
		text-align: center;
	}
	.imageOpacity {
		opacity: 0.1;
	}

	.clear_both {
		clear: both;
	}


	/*End Landsacape one Section */

	.port_one_frontSide {
		width: 250px;
		height: 350px;
		border: 2px solid #3c3c3c;
		padding: 5px;
		float: left;
		background: #7acbfa;
	}
	p.port_one_schoolName {
		text-align: center;
		text-transform: uppercase;
		font-weight: bold;
		font-size: {{$tempSetting?($tempType==1?($tempSetting->title_font.'px'):'17px'):'17px'}};
	}
	p.address {
		text-align: center;
		line-height: 0px;
		font-size: 13px;
	}
	.profilePic{
		text-align: center;
		margin-top: 10px;
	}
	.proifleImage {
		height: 100px;
		border-radius: 50px;
		width: 100px;
		margin-top: 20px;
	}
	.studentInfo {
		margin-left: 15px;
		margin-top: 25px;
	}
	p.normaltext {
		font-size: 12px;
		line-height: 6px;
	}
	p.studentName {
		font-size: 14px;
		text-align: center;
		font-weight: bold;
	}

	.principalImage {
		text-align: center;
		float: right;
		margin-right: 10px;
	}

	p.port_one_principal {
		float: right;
		padding: 20px;
	}

	.port_one_signature{
		width:30px;
	}

	/*back Side design Html Css*/
	.port_one_backSide {
		float: left;
		width: 250px;
		height: 350px;
		border: 2px solid #3c3c3c;
		padding: 5px;
		margin-left: 50px;
		background: #7acbfa;
	}

	p.port_one_stduentInfoTitle {
		font-size: 14px;
		font-weight: bold;
		line-height: 20px;
		text-align: center;
		border-bottom: 2px solid #3c3c3c;
	}
	p.found{
		margin-top: 45px;
		text-align: center;
		border-top: 2px solid #ccc;
		line-height: 25px;
	}
	p.phone {
		font-size: 12px;
		text-align: center;
	}
	.schoolLogo {
		text-align: center;
	}
	.imageOpacity {
		opacity: 0.1;
		width: 150px;
		height: 100px;
	}














</style>

<form id="inst_id_card_setting_form" enctype="multipart/form-data" action="{{url('/reports/student/id-card/setting')}}" method="POST">
	<input type="hidden" name="_token" value="{{csrf_token()}}">
	<input type="hidden" id="temp_setting_id" name="temp_setting_id" value="{{$templateProfile?$templateProfile->id:0}}">
	<div class="row text-center">
		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('font_size') ? ' has-error' : '' }}">
				<label class="control-label" for="l_title_font_size">Institute Title Font Size (L)</label>
				<input type="number" id="l_title_font_size" name="l_title_font_size" value="{{$tempSetting?($tempType==0?($tempSetting->title_font):'20'):'20'}}" class="form-control text-center title-font-size">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('font_size') ? ' has-error' : '' }}">
				<label class="control-label" for="l_body_font_size">Font Size (L)</label>
				<input type="number" id="l_body_font_size" name="l_body_font_size" value="{{$tempSetting?($tempType==0?($tempSetting->body_font):'13'):'13'}}" class="form-control text-center body-font-size">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('width') ? ' has-error' : '' }}">
				<label class="control-label" for="l_width">Width (L)</label>
				<input type="number" id="l_width" name="l_width" value="{{$tempSetting?($tempType==0?($tempSetting->width):'350'):'350'}}" class="form-control text-center width">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('height') ? ' has-error' : '' }}">
				<label class="control-label" for="l_height">Height (L)</label>
				<input type="number" id="l_height" name="l_height" value="{{$tempSetting?($tempType==0?($tempSetting->height):'210'):'210'}}" class="form-control text-center height">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('l_font_color') ? ' has-error' : '' }}">
				<label class="control-label" for="l_font_color">Font Color (L)</label>
				<input type="color" id="l_font_color" name="l_font_color" value="{{$tempSetting?($tempType==0?($tempSetting->font_color):'5'):'5'}}" class="form-control text-center font_color">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('margin_bottom') ? ' has-error' : '' }}">
				<label class="control-label" for="l_color">ID Card Color (L)</label>
				<input type="color" id="l_color" name="l_color" value="{{$tempSetting?($tempType==0?($tempSetting->color):'#a3cde7'):'#a3cde7'}}" class="form-control color">
			</div>
		</div>
	</div>
	<div class="row text-center">
		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('font_size') ? ' has-error' : '' }}">
				<label class="control-label" for="p_title_font_size">Institute Title Font Size (P)</label>
				<input type="number" id="p_title_font_size" name="p_title_font_size" value="{{$tempSetting?($tempType==1?($tempSetting->title_font):'17'):'17'}}" class="form-control text-center title-font-size">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('font_size') ? ' has-error' : '' }}">
				<label class="control-label" for="p_body_font_size">Font Size (P)</label>
				<input type="number" id="p_body_font_size" name="p_body_font_size" value="{{$tempSetting?($tempType==1?($tempSetting->body_font):'12'):'12'}}" class="form-control text-center body-font-size">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('width') ? ' has-error' : '' }}">
				<label class="control-label" for="p_width">Width (P)</label>
				<input type="number" id="p_width" name="p_width" value="{{$tempSetting?($tempType==1?($tempSetting->width):'250'):'250'}}" class="form-control text-center width">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('height') ? ' has-error' : '' }}">
				<label class="control-label" for="p_height">Height (P)</label>
				<input type="number" id="p_height" name="p_height" value="{{$tempSetting?($tempType==1?($tempSetting->height):'350'):'350'}}" class="form-control text-center height">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('margin_bottom') ? ' has-error' : '' }}">
				<label class="control-label" for="p_margin_bottom">Font Color (P)</label>
				<input type="color" id="p_font_color" name="p_font_color" value="{{$tempSetting?($tempType==1?($tempSetting->font_color):'5'):'5'}}" class="form-control text-center font_color">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('margin_bottom') ? ' has-error' : '' }}">
				<label class="control-label" for="p_color">ID Card Color (P)</label>
				<input type="color" id="p_color" name="p_color" value="{{$tempSetting?($tempType==1?($tempSetting->color):'#a3cde7'):'#a3cde7'}}" class="form-control color">
			</div>
		</div>

		<div class="col-sm-2">
			<div class="form-group {{ $errors->has('bg_image') ? ' has-error' : '' }}">
				<label class="control-label" for="bg_image">Signature</label>
				<input type="file" id="signature" name="signature" class="form-control color">
			</div>
		</div>

	</div>
	<input type="hidden" name="temp_id" value="{{$templateProfile?$templateProfile->temp_id:1}}">
	<input type="hidden" id="temp_type" name="temp_type" value="{{$templateProfile?$templateProfile->temp_type:0}}">

	<hr/>
	<div class="row">
		<p class="bg-green-gradient text-center text-bold">
			Landscape (L) Format
			<label class="pull-right" style="margin-right: 20px;">
				<input type="checkbox" data-key="0" value="1" name="temp_id" {{$templateProfile?($templateProfile->temp_type==0?'checked':''):0}}> Select Template (Landscape)
			</label>
		</p>
		<div class="radio" style="margin-left: 30px">
			<label><input type="radio" value="1" @if(!empty($templateProfile) && ($templateProfile->temp_type==0))  {{$templateProfile?($templateProfile->temp_id==1?'checked':''):0}} @endif name="l_template" >Template One</label>
		</div>
		<div class="col-md-10 col-md-offset-1">
			<div class="land_one_FrontSide id-card-one-color id-card-one-width  id-card-font-color-land id-card-one-height p_font_color">
				<div class="land_one_school_seciton ">
					<div class="land_one_school_logo">
						@if($instituteInfo->logo)
							<img class="land_one_logo" src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
						@endif
					</div>
					<div class="land_one_school_info">
						<p class="land_one_port_one_schoolName inst-font-size-land">{{$instituteInfo->institute_name}}</p>
						<p class="land_one_address">{{$instituteInfo->address2}}</p>
					</div>
				</div>
				<div class="clear_both"></div>
				<div class="land_one_studentInfo_and_image">

					<div class="land_one_profilePic">
						<img class="land_one_proifleImage" src="{{asset('/assets/users/images/user-default.png')}}">
					</div>
					<div class="land_one_studentInfo">
						<p class="land_one_normaltext">Student Name: </p>
						<p class="land_one_normaltext">ID NO: </p>
						<p class="land_one_normaltext">Class: </p>
						<p class="land_one_normaltext">Section: </p>
						<p class="land_one_normaltext">Blood Group: </p>
					</div>
				</div>
				<p class="principal">Principal</p>
			</div>

			{{--Back Side Design Code--}}

			<div class="land_one_port_one_backSide id-card-one-color id-card-one-width  id-card-font-color-land id-card-one-height">
				<p class="land_one_port_one_stduentInfoTitle">Address here </p>
				<div class="land_one_studentInfo">
					<p class="land_one_normaltext">Father Name: </p>
					<p class="land_one_normaltext">Mother Name: </p>
					<p class="land_one_normaltext">Emergency Contact: </p>
				</div>
				<div class="land_one_schoolLogo">
					<img class="imageOpacity" src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
				</div>

			</div>

		</div>
	</div>


	<br> <br> <br>
	{{--portrait--}}
	<div class="row">
		<p class="bg-green-gradient text-center text-bold">
			Portrait (P) Format
			<label class="pull-right" style="margin-right: 20px;">
				<input type="checkbox" data-key="1" value="2" name="temp_id" {{$templateProfile?($templateProfile->temp_type==0?'':'checked'):0}}> Select Template (Portrait)
			</label>

		<div class="radio" style="margin-left: 30px">
			<label><input type="radio" value="1" @if(!empty($templateProfile) && ($templateProfile->temp_type==1))  {{$templateProfile?($templateProfile->temp_id==1?'checked':''):0}} @endif name="p_template">Template One</label>
		</div>
		</p>
		<div class="col-md-10 col-md-offset-1">
			<div class="port_one_frontSide ">
				<p class="port_one_schoolName">{{$instituteInfo->institute_name}}</p>
				<p class="address">{{$instituteInfo->address2}}</p>

				<div class="profilePic">
					<img class="proifleImage" src="{{asset('/assets/users/images/user-default.png')}}">
				</div>
				<p class="studentName">Student Name</p>
				<div class="studentInfo">
					<p class="normaltext">ID NO: </p>
					<p class="normaltext">Class: </p>
					<p class="normaltext">Section: </p>
					<p class="normaltext">Blood Group:</p>
				</div>

				@if(!empty($templateProfile))
				<div class="principalImage">
					<img class="port_one_signature" src="{{asset('/assets/id-card/'.$templateProfile->signature)}}">
					<p>Principal</p>
				</div>
				@endif

			</div>

			{{--Back Side Design Code --}}

			<div class="port_one_backSide">
				<p class="port_one_stduentInfoTitle">Student Information</p>
				<div class="studentInfo">
					<p class="normaltext">Father Name: </p>
					<p class="normaltext">Mother Name: </p>
					<p class="normaltext">Date of Birth: </p>
					<p class="normaltext">Contact: </p>
				</div>
				<div class="schoolLogo">
					@if($instituteInfo->logo)
						<img class="imageOpacity" src="{{asset('/assets/users/images/'.$instituteInfo->logo)}}">
						@endif
				</div>
				<p class="found">If it is found please return to</p>
				<p class="address">Address Here </p>

				<p class="phone">Mob: </p>

			</div>
		</div>
	</div>

	<br> <br> <br>


	<div class="box-footer text-right">
		<button type="submit" class="btn btn-info">Submit</button>
		<button type="reset" class="pull-left btn btn-default">Reset</button>
	</div>
</form>
