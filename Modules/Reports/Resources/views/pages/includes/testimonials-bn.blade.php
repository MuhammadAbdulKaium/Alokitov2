<div class="row">
	<hr/>
	<div class="col-md-12">
		<div class="col-md-4">
			ক্রমিক নং: 1<br>কেন্দ্র কোড:{{$instituteProfile->center_code}}<br>জেলা কোড: {{$instituteProfile->zilla_code}}
		</div>
		<div class="col-md-4">
			বিদ্যালয়ের কোড: {{$instituteProfile->institute_code}} <br>উপজেলা কোড:{{$instituteProfile->upazila_code}}
		</div>
		<div class="col-md-4">
			EIIN:{{$instituteProfile->eiin_code}} <br> তারিখ: ১২-০১-২০১৮
		</div>
	</div>
	<div class="col-md-12 text-center">
		<img src="{{URL::asset('assets/users/images/'.$instituteProfile->logo)}}" height="80px" width="80px" style="border-radius:50%" alt="profile" class="pro-pic">
		<h3>{{$instituteProfile->institute_name}}</h3>
		<h5><strong>{{$instituteProfile->address1}}</strong></h5>
		<h3><strong>প্রশংসা পত্র</strong></h3>

	</div>
	<div class="col-md-12">
		<form action="/reports/documents/testimonial" method="post">
			<input type="hidden" name="_token" value="{{csrf_token()}}">
			<input type="hidden" name="download" value="preview">

			<p>প্রত্যয়ন করা যায়তেছে যে , <input id="std_anme" type="text" name="std_anme" style="width: 500px" placeholder="Student Name"> </p>
			<p style="line-height: 10px">
				পিতা: <input id="father" type="text" name="father" style="width: 200px" placeholder="">,
				মাতা: <input id="mother" type="text" name="mother" style="width: 200px" placeholder="">,
				গ্রাম: <input id="village" type="text" name="village" style="width: 200px" placeholder="">,
				ডাকঘর: <input id="post" type="text" name="post" style="width: 100px" placeholder="">
				উপজেলা: <input id="upzilla" type="text" name="upzilla" style="width: 200px" placeholder=""><br/>
				জেলা: <input id="zilla" type="text" name="zilla" style="width: 200px" placeholder="">
				এই বিদ্যালয়ে  <input id="class1" type="text" name="class1" style="width: 200px" placeholder="">
				শ্রেণী হইতে  <input id="class2" type="text" name="class2" style="width: 200px" placeholder="">
				শ্রেণীর ছাত্র/ছাত্রী হিসাবে <input id="year1" type="text" name="year1" style="width: 100px" placeholder="">
				সাল হইতে<input id="year2" type="text" name="year2" style="width: 100px" placeholder="">
				সাল পর্যন্ত অধ্যয়নরত ছিল । সে  <input id="year3" type="text" name="year3" style="width: 100px" placeholder="">
				সাল  <input id="class3" type="text" name="class3" style="width: 100px" placeholder="">
				শ্রেণীর বার্ষিক  পরীক্ষা পরীক্ষা়য় সফল্যর সাথে উত্তীর্ণ হইয়াছে / হয় নাই  । এই পরীক্ষা সি. জি. পি. এ.   <input id="gpa" type="text" name="gpa" style="width: 100px" placeholder=""> পাইয়াছে । তাহার জন্ম তারিখ  ভর্তি বহি বর্ণনায় <input id="dob" type="text" name="dob" style="width: 100px" placeholder=""> তাহার নিকট হয়তে বিদ্যালয় যাবতীয় পাওনা <input id="year4" type="text" name="year4" style="width: 100px" placeholder=""> পর্যন্ত বুঝিয়া লওয়া হইয়াছে । সে এই বিদ্যালয়ে <input id="class4" type="text" name="class4" style="width: 200px" placeholder=""> শ্রেণী পর্যন্ত লেখাপড়া করিয়াছে ।
			</p>
			<br/>
			<br/>
			<p>
				আমার জানামতে সে বিদ্যলয় অধ্যয়নকালে রাষ্ট্র বিরোধী বা আইন পরিপন্থি কোনো কাজে জড়িত ছিল না ।  সে চরিএবান ।  তাহার জীবনের উন্নতি কামনা করি।
			</p>
			<div class="text-center">
				<input  class="btn btn-primary" type="submit" value="Preview" name="submit">
			</div>
		</form>
	</div>
	<div class="col-md-12">
		<br/>
		<br/>
		<br/>
		<br/>
		<br/>
		<div class="col-sm-6 text-left">
			<p>
				..................................<br>
				লেখক
			</p>
		</div>
		<div class="col-sm-6 text-right">


			<p>
				..................................<br>
				প্রধান শিক্ষকের স্বাক্ষর
			</p>
		</div>
	</div>
</div>