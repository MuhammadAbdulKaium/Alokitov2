<!DOCTYPE html>
<html>
<head>


	<title>Testimonials</title>
	<link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<style>
		body {
			font-family: 'SolaimanLipi',' R';  }
		p{  line-height: 23px; }
		.col-md-12 { width: 100%; }
		.col-md-2 {width: 16.66666667%; }
		.float-left { float: left; }
		.clear{ clear: both; }
		.text-center { text-align: center; }

		body{
			border: 5px solid ;
			padding: 10px;
		}

		.imageOpacity {
			opacity: 0.1;
			width: 425px;
			height: 425px;
			position: absolute;
			margin:125px 0px 0px 270px;
			/*border-radius: 50%;*/
		}

		.myContainer {position: relative;}

	</style>
</head>
<body>

<div class="row myContainer">
	{{--water mark logo--}}

	<img class="imageOpacity"
		 src="{{public_path().'/assets/users/images/'.$instituteProfile->logo}}"  >


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
		<img src="{{public_path().'/assets/users/images/'.$instituteProfile->logo}}"
		style="border-radius:50%;height:80px;width: 80px;" alt="profile" class="pro-pic">
		<h3><strong>{{strtoupper($instituteProfile->institute_name)}}</strong></h3>
		<h4><strong>{{$instituteProfile->address1}}</strong></h4>
		<br/>
		<h3><strong>TESTIMONIAL</strong></h3>
		<br/>
	</div>

	<div class="col-md-12">
		<div class="testimonialInfo">
			<p class="testimonialContent">
				কেমন আছো যায়তেছে যে ,{{$testimonialInfoArray->std_name}}
				পিতা: {{$testimonialInfoArray->father}},
				মাতা: {{$testimonialInfoArray->mother}},
				গ্রাম: {{$testimonialInfoArray->village}},
				ডাকঘর: {{$testimonialInfoArray->post}},
				উপজেলা: {{$testimonialInfoArray->upzilla}},
				জেলা: {{$testimonialInfoArray->zilla}}
				এই বিদ্যালয়ে {{$testimonialInfoArray->class1}}
				শ্রেণী হইতে {{$testimonialInfoArray->class2}}
				শ্রেণীর ছাত্র/ছাত্রী হিসাবে {{$testimonialInfoArray->year1}}
				সাল হইতে {{$testimonialInfoArray->year2}}
				সাল পর্যন্ত অধ্যয়নরত ছিল । সে {{$testimonialInfoArray->year3}}
				সাল {{$testimonialInfoArray->class3}}
				শ্রেণীর বার্ষিক  পরীক্ষা পরীক্ষা়য় সফল্যর সাথে উত্তীর্ণ হইয়াছে / হয় নাই  । এই পরীক্ষা সি. জি. পি. এ. {{$testimonialInfoArray->gpa}}
				পাইয়াছে । তাহার জন্ম তারিখ  ভর্তি বহি বর্ণনায় তাহার নিকট হয়তে বিদ্যালয় যাবতীয় পাওনা  {{$testimonialInfoArray->year4}} পর্যন্ত বুঝিয়া লওয়া হইয়াছে । সে এই বিদ্যালয়ে {{$testimonialInfoArray->class4}}  শ্রেণী পর্যন্ত লেখাপড়া করিয়াছে ।
				আমার জানামতে সে বিদ্যালয় অধ্যায়নকালে রাষ্ট্র বিরোধী বা আইন পরিপ্ন্থী কোনো কাজে জড়িত ছিল না।   সে চরিত্রবান।   তাহার জীবনের উন্নতি কামনা করি ।
		</div>
		<div class="col-sm-4 footer_left_text" style="width: 30%">-------------------------------------<br>লেখক <br></div>
		<div class="col-sm-4 footer_right_text" style="width: 30%; float: right; margin-top: -50px">--------------------------------------<br>প্রধান শিক্ষকের স্বাক্ষর <br></div>

	</div>
	<br/>

	<div style="padding-top: 17px" class="col-sm-6 text-left">
	</div>
	<div  style="padding-top: -40px; padding-right: 100px" class="col-sm-6 text-right"> <h4> <strong>Principal</strong></h4> </div>
</div>
</body>
</html>