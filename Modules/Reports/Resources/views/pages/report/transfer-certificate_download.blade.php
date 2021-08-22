<!DOCTYPE html>
<html>
<head>
    <title>Certificate</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>


    <style type="text/css">

        @font-face {
            font-family: 'Siyamrupali';
            /*font-style: normal;*/
            /*font-weight: normal;*/
            /*src: url(http://venusitltd.com/fonts/Siyamrupali.ttf) format('truetype');*/
        }

        body {  font-family: 'Siyamrupali';  }

        .mainTestimonial {
            position: relative;
        }

        .testimonialHead {
            left: 50%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, 80%);
            font-size: 18px;
            padding: 3px 5px;
        }

        .topHeadingMiddleImg {
            width: 50px;
            height: 50px;
        }

        .topHeadingLeft {
            font-family: 'Prosto One', cursive;
            left: 11%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, 50%);
            font-size: 14px;
            padding: 3px 5px;
        }

        .topHeadingMiddle {
            font-family: 'Prosto One', cursive;
            left: 50%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, 50%);
            font-size: 14px;
            padding: 3px 5px;
        }

        .topHeadingRight {
            font-family: 'Prosto One', cursive;
            left: 84%;
            position: absolute;
            top: 50%;
            transform: translate(-50%, 50%);
            font-size: 14px;
            padding: 3px 5px;
        }

        .mainMiddleTextCenter {
            text-align: center;
        }

        .mainMiddleText {
            font-family: 'Limelight', cursive;
            font-size: 20px;
            padding: 3px 5px;
            width: 100%;
            text-align: center;
        }

        .top_heading_title {
            text-align: center;
            font-family: 'Allerta Stencil', sans-serif;
            text-transform: uppercase;
        }

        .top_heading_title_4 {
            text-align: center;
            font-family: 'Allerta Stencil', sans-serif;
            text-transform: uppercase;
        }


        .testimonial {
            min-height: 550px;
            margin-left: auto;
            margin-right: auto;
            padding-top: 4%;
            padding-left: 10%;
            padding-right: 10%;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            /*background: url("https://demo.inilabs.net/school/v3.5/uploads/images/certificate-defualt.jpg") no-repeat !important;*/
            background-size: 100% 100% !important;
            -webkit-print-color-adjust: exact;
        }

        @media print {
            .testimonial {
                min-height: 550px;
                margin-left: auto;
                margin-right: auto;
                padding: 10%;
                width: 100%;
                margin-left: auto;
                margin-right: auto;
                /*background: url("https://demo.inilabs.net/school/v3.5/uploads/images/certificate-defualt.jpg") no-repeat !important;*/
                background-size: 100% 100% !important;
                -webkit-print-color-adjust: exact;
            }
        }

        .slno span {
            font-size: 16px;
            font-weight: 600;
        }

        .testimonialInfo {
            margin-top: 30px;

        }

        .testimonialContent {
            font-size: 24px;
            line-height: 25px;
            text-align: justify;
            font-size: 17px;
        }

        .testimonialContent .dots {
            display: inline-block;
            height: 20px;
            line-height: 10px;
            position: relative;
            font-weight: 600;
        }

        .testimonialContent .dots::after {
            border-bottom: 2px dotted #999;
            bottom: 0;
            content: "";
            height: 0;
            left: 0;
            position: absolute;
            width: 100%;
        }

        .testimonialContent .dots::before {
            content: attr(data-hover);
            font-style: italic;
            height: 5px;
            left: 0;
            position: absolute;
            text-align: center;
            top: 10px;
            width: 100%;
        }

        .testimonialContent .dots.widthcss{
            width: 20%;
        }

        .headSection {
            margin-top: 10px;
        }

        .footerSection {
            margin-top: 30px;
        }

        .footer_left_text {
            font-family: 'Prosto One', cursive;
            font-size: 17px;

        }

        .footer_middle_text {
            font-family: 'Prosto One', cursive;
            font-size: 17px;
            text-align: center;
        }

        .footer_right_text {
            font-family: 'Prosto One', cursive;
            font-size: 17px;
            text-align: center;
        }
    </style>
</head>
<body>

<div class="row">
    <div class="col-sm-12">
        <div id="printablediv">
            <div class="testimonial">
                <div class="mainTestimonial">
                    <div class="row" style="width: 100%; float: left;">
                        <div class="topHeadingLeft col-sm-4" style="width: 32%; float: left">ক্রমিক নং: 1<br>কেন্দ্র কোড:{{$instituteProfile->center_code}}<br>জেলা কোড: {{$instituteProfile->zilla_code}}</div>

                        <div class="topHeadingMiddle col-sm-4" style="width: 32%; float: left">বিদ্যালয়ের কোড: {{$instituteProfile->institute_code}} <br>উপজেলা কোড:{{$instituteProfile->upazila_code}} </div>

                        <div class="topHeadingRight col-sm-4" style="width: 20%; float: right">EIIN:{{$instituteProfile->eiin_code}} <br> তারিখ: ১২-০১-২০১৮</div>
                    </div>
                    <div class="row" style="width: 100%; text-align: center">
                    </div>
                </div>



                <div class="headSection">
                    <div class="row" >
                        <h2 class="top_heading_title">বিদ্যালয় পরিত্যাগের ছাড়পত্র</h2>
                        <h4 class="top_heading_title_4">{{$instituteProfile->institute_name}} TT<br></h4>
                        <div class="col-sm-12 mainMiddleTextCenter"><span class="mainMiddleText">{{$instituteProfile->address1}}<br></span></div>
                    </div>
                </div>

                <div class="testimonialInfo">
                    <p class="testimonialContent">
                        প্রত্যয়ন করা যায়তেছে যে ,{{$testimonialInfoArray->std_name}}
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
                        <br> <br><b>এই বিদ্যালয় অধ্যয়ন কালে তাঁহার স্বভাব-চরিত্র সম্পর্কে তথ্যাদি নিম্নে দেওয়া হল :</b> <b>
                        তাহার চরিত্র {{$testimonialInfoArray->character}}, আচরন {{$testimonialInfoArray->behavior}}, উপস্থিতি {{$testimonialInfoArray->attendance}}, বুদ্ধিমত্তা {{$testimonialInfoArray->talent}}
                        <br> <b>বিদ্যালয় পরিত্যাগের কারণ:</b>{{$testimonialInfoArray->leave_message}}

                </div>

                <div class="footerSection">
                    <div class="row" style="width: 100%;">
                        <div class="col-sm-4 footer_left_text" style="width: 30%">-------------------------------------<br>লেখক <br></div>
                        <div class="col-sm-4 footer_right_text" style="width: 30%; float: right; margin-top: -50px">--------------------------------------<br>প্রধান শিক্ষকের স্বাক্ষর <br></div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>

</body>
</html>