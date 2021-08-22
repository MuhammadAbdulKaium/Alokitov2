<!DOCTYPE html>
<html>
<head>
    <title>Certificate</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>

    <style type="text/css">

        @page {
            margin: 0.1cm;
            size: landscape;
            orientation: landscape;
            size: A4;
        }
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
            /*background: url("/assets/users/images/certificate-design.jpg") no-repeat !important;*/
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
                /*background: url("/assets/users/images/certificate-design.jpg") no-repeat !important;*/
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
            margin-top: 120px;
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
                    <div class="row">
                        <span class="topHeadingLeft">ক্রমিক নং: 1<br>কেন্দ্র কোড:{{$instituteProfile->center_code}}<br>জেলা কোড: {{$instituteProfile->zilla_code}}</span>

                        <span class="topHeadingMiddle">বিদ্যালয়ের কোড: {{$instituteProfile->institute_code}} <br>উপজেলা কোড:{{$instituteProfile->upazila_code}} </span>

                        <span class="topHeadingRight">EIIN:{{$instituteProfile->eiin_code}} <br> তারিখ: ১২-০১-২০১৮</span>
                    </div>
                    <div class="row">
                        <span class="topHeadingMiddle" style="margin-top: 40px"><img class="topHeadingMiddleImg" src="{{URL::asset('assets/users/images/'.$instituteProfile->logo)}}"></span>
                    </div>
                </div>


                <div class="headSection">
                    <div class="row" >
                        <h2 class="top_heading_title">বিদ্যালয় পরিত্যাগের ছাড়পত্র</h2>
                        <h4 class="top_heading_title_4">{{$instituteProfile->institute_name}}<br></h4>
                        <div class="col-sm-12 mainMiddleTextCenter"><span class="mainMiddleText">{{$instituteProfile->address1}}<br></span></div>
                    </div>
                </div>

                <div class="testimonialInfo">
{{--                    ddd--}}

                    <p class="testimonialContent">
                        এই মর্মে প্রত্যয়ন করা যাচ্ছে যে ,{{$testimonialInfoArray->std_name}}
                        পিতা: {{$testimonialInfoArray->father}},
                        মাতা: {{$testimonialInfoArray->mother}},
                        গ্রাম: {{$testimonialInfoArray->village}},
                        ডাকঘর: {{$testimonialInfoArray->post}},
                        উপজেলা: {{$testimonialInfoArray->upzilla}},
                        জেলা: {{$testimonialInfoArray->zilla}}
                        এই বিদ্যালয়ে {{$testimonialInfoArray->class1}}
                        শ্রেণী হতে {{$testimonialInfoArray->class2}}
                        শ্রেণীর ছাত্র/ছাত্রী হিসাবে {{$testimonialInfoArray->year1}}
                        সাল হতে {{$testimonialInfoArray->year2}}
                        সাল পর্যন্ত অধ্যয়নরত ছিল । সে {{$testimonialInfoArray->year3}}
                        সালে {{$testimonialInfoArray->class3}}
                        শ্রেণীর বার্ষিক পরীক্ষা়য় সাফল্যের সাথে উত্তীর্ণ হয়েছে / হয় নাই  । অত্র  পরীক্ষায় সে সি. জি. পি. এ. {{$testimonialInfoArray->gpa}}
                        পেয়েছে । তাহার জন্ম তারিখ  ভর্তি বহি বর্ণনায় তাহার নিকট হয়তে বিদ্যালয় যাবতীয় পাওনা  {{$testimonialInfoArray->year4}} পর্যন্ত বুঝে নিয়েছে । সে এই বিদ্যালয়ে {{$testimonialInfoArray->class4}}  শ্রেণী পর্যন্ত লেখাপড়া করেছে  ।
                        <br> <br><i>এই বিদ্যালয় অধ্যয়ন কালে তাঁহার স্বভাব-চরিত্র সম্পর্কে তথ্যাদি নিম্নে দেওয়া হলো:</i> <br>
                        তাহার চরিত্র {{$testimonialInfoArray->character}}, আচরন {{$testimonialInfoArray->behavior}}, উপস্থিতি {{$testimonialInfoArray->attendance}}, বুদ্ধিমত্তা {{$testimonialInfoArray->talent}}
                        <br> <b>বিদ্যালয় পরিত্যাগের কারণ:</b>{{$testimonialInfoArray->leave_message}}


                </div>

                <div class="footerSection">
                    <div class="row" >
                        <div class="col-sm-4 footer_left_text">-------------------------------------<br>লেখক <br></div>
                        <div class="col-sm-4 footer_middle_text"></div>
                        <div class="col-sm-4 footer_right_text">--------------------------------------<br>প্রধান শিক্ষকের স্বাক্ষর <br></div>
                    </div>
                </div>
            </div>
        </div>


            <button style="margin:50px;" class="btn btn-success" onclick="javascript:printDiv('printablediv')">Print</button>

            <form action="/reports/documents/transfer-certificate/" method="post">
                <input type="hidden" name="download" value="download">
                <input type="hidden" name="_token" value="{{csrf_token()}}">
                <input id="std_anme" type="hidden" value="{{$testimonialInfoArray->std_name}}" name="std_anme">
                <input id="father" type="hidden" name="father"  value="{{$testimonialInfoArray->father}}" >
                <input id="mother" type="hidden" name="mother" value="{{$testimonialInfoArray->mother}}"  >
                <input id="village" type="hidden" name="village" value="{{$testimonialInfoArray->village}}"  >
                <input id="post" type="hidden" name="post" value="{{$testimonialInfoArray->post}}"  >
                <input id="upzilla" type="hidden" name="upzilla" value="{{$testimonialInfoArray->upzilla}}" ><br/>
                <input id="zilla" type="hidden" name="zilla" value="{{$testimonialInfoArray->zilla}}"  >
                <input id="class1" type="hidden" name="class1" value="{{$testimonialInfoArray->class1}}"  >
                <input id="class2" type="hidden" name="class2" value="{{$testimonialInfoArray->class2}}"  >
                <input id="year1" type="hidden" name="year1" value="{{$testimonialInfoArray->year1}}" >
                <input id="year2" type="hidden" name="year2" value="{{$testimonialInfoArray->year2}}" >
                <input id="year3" type="hidden" name="year3" value="{{$testimonialInfoArray->year3}}" >
                <input id="class3" type="hidden" name="class3"  value="{{$testimonialInfoArray->class3}}" >
                <input id="gpa" type="hidden" name="gpa"  value="{{$testimonialInfoArray->gpa}}" >
                <input id="dob" type="hidden" name="dob"  value="{{$testimonialInfoArray->dob}}" >
                <input id="year4" type="hidden" name="year4" value="{{$testimonialInfoArray->year4}}"  >
                <input id="class4" type="hidden" name="class4" value="{{$testimonialInfoArray->class4}}"  >
                <button type="submit" class="btn btn-success" style="margin-top: -120px; float: right; margin-right: 40px">Download</button>
            </form>


    </div>
</div>

<script language="javascript" type="text/javascript">
    function printDiv(divID) {
        //Get the HTML of div
        var divElements = document.getElementById(divID).innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only
        document.body.innerHTML = divElements;

        //Print Page
        window.print();

        //Restore orignal HTML
        document.body.innerHTML = oldPage;
    }

    function closeWindow() {
        location.reload();
    }

</script>


</body></html></html>