<!DOCTYPE html>
<html>
<head>
	@php $testimonial = json_decode($testimonialInfoArray) @endphp

	<title>Testimonials</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<style>

		p{ font-size: {{$testimonial->font_size?$testimonial->font_size:'15'}}px; line-height: 23px; }
		.col-md-12 { width: 100%; }
		.col-md-2 {width: 16.66666667%; }
		.float-left { float: left; }
		.clear{ clear: both; }
		.text-center { text-align: center; }

		body{
			border: 5px solid {{$testimonial->border_color?$testimonial->border_color:'#000000'}};
			padding: 25px;
		}

		.imageOpacity {
			opacity: 0.1;
			width: 450px;
			height: 450px;
			position: absolute;
			margin:125px 0px 0px 270px;
		}

		.myContainer {position: relative;}

	</style>
</head>
<body>

<div class="row myContainer">
	{{--water mark logo--}}
	<img class="imageOpacity" src="{{URL::asset('assets/users/images/'.$instituteProfile->logo)}}">

	<div class="col-md-12 text-center">
		<div class="col-md-3 float-left">
			<strong> Institute Code: {{$instituteProfile->institute_code}} </strong> <br/>
			<strong> Center Code: {{$instituteProfile->center_code}} </strong>
		</div>
		<div class="col-md-2 float-left">
			{{--<strong>   Institute Code: {{$instituteProfile->institute_code}} </strong>--}}
		</div>
		<div style="padding-left: -20px" class="col-md-2 float-left">
			<strong> EIIN: {{$instituteProfile->eiin_code}} </strong>
		</div>
		<div class="col-md-2 float-left">
			{{--<strong> Upazilla Code: {{$instituteProfile->upazila_code}}  </strong>--}}
		</div>
		<div class="col-md-3 float-left">
			<strong> Zilla Code: {{$instituteProfile->zilla_code}}  </strong> <br/>
			<strong> Upazilla Code: {{$instituteProfile->upazila_code}}  </strong>
		</div>
	</div>
	<br/>
	<br/>
	<div class="col-md-12 text-center clear">
		<img src="{{URL::asset('assets/users/images/'.$instituteProfile->logo)}}" height="80px" width="80px" style="border-radius:50%" alt="profile" class="pro-pic">
		<h3><strong>{{strtoupper($instituteProfile->institute_name)}}</strong></h3>
		<h4><strong>{{$instituteProfile->address1}}</strong></h4>
		<br/>
		<h3><strong>TESTIMONIAL</strong></h3>
		<br/>
	</div>

	<div class="col-md-12">
		<p>
			I am highly delighted to certify that <b>{{$testimonial->s_name}}</b> {{$testimonial->gender}} of <b>{{$testimonial->f_name}}</b> and <b>{{$testimonial->m_name}}</b> of village: {{$testimonial->village}}, Post: {{$testimonial->post}}, Upazilla: {{$testimonial->upzilla}}, District: {{$testimonial->zilla}}, passed the <b>{{$testimonial->exam}} Certificate</b> Examination from this institution under the board of Intermediate and Secondary Education, {{$testimonial->board}} held in {{$testimonial->year}} in {{$testimonial->group}} group. {{$testimonial->gender=="son"?"He":"She"}} obtained <b>G.P.A: {{$testimonial->gpa}}</b>, <b>Grade: {{$testimonial->grade}}</b>. {{$testimonial->gender=="son"?"His":"Her"}} Roll: {{$testimonial->center}} No. {{$testimonial->roll}}, Registration No: {{$testimonial->reg}}, Session: {{$testimonial->session}}, Date Of Birth: {{date('d M, Y', strtotime($testimonial->dob))}}.
		</p>

		<p> As far as know {{$testimonial->gender=="son"?"he":"she"}} bears a Good moral Character. {{$testimonial->gender=="son"?"He":"She"}} did't take part in any subversive activity against the state and society during {{$testimonial->gender=="son"?"his":"her"}} staying in the institution. </p>
		<br/>
		<p> I wish {{$testimonial->gender=="son"?"his":"her"}} all success in life. </p>
	</div>
	<br/>
	<br/>
	<div style="padding-top: 17px" class="col-sm-6 text-left">
		<p> Prepared by <strong>{{$testimonial->writer}} </strong> <br/> Date: <strong> {{date('d M, Y', strtotime($testimonial->date))}} </strong></p>
	</div>
	<div  style="padding-top: -40px; padding-right: 100px" class="col-sm-6 text-right"> <h4> <strong>Principal</strong></h4> </div>
</div>
</body>
</html>