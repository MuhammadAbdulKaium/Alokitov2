<!DOCTYPE html>
<html lang="en">
<head>
		<meta charset="UTF-8">
		<!-- For IE -->
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<!-- For Resposive Device -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<title>Student Profile</title>
		<!-- Main style sheet -->
		<link rel="stylesheet" type="text/css" href="css/custom.css">
		<link rel="stylesheet" type="text/css" href="css/responsive.css">
		<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">
		<!-- j Query -->
		<script type="text/javascript" src="bootstrap/js/jquery.min.js.js"></script>
		<!-- Bootstrap JS -->
		<script type="text/javascript" src="bootstrap/css/bootstrap.min.js"></script>

		<!-- Vendor js _________ -->
    <style type="text/css">
        body {
            font-family: 'Open Sans', sans-serif;
            font-weight: normal;
            color:#6f6f6f;
            font-size: 14px;
            position: relative;
        }
        * {
            margin: 0px;
            padding: 0px;
        }
        ul li{list-style: none;}
        /*school Attendance Report*/
        .attendance-report .attendance-top-title{ text-align: center; }
        .attendance-report .attendance-top-title .attendance-title{ font-size: 36px; line-height: 50px; display: block;}
        .attendance-report .panel-info{padding-right: 15px; padding-left: 15px;}
        .attendance-top-logo img{margin-top: 10px; margin-bottom: 30px;}
        .attendance-top-title .attendance-address{line-height: 28px;}
        .attendance-report .attendance-details .table > tbody > tr > td{text-align: center;}
        .attendance-report .attendance-top-logo{padding: 0;}
        .details-center{text-align: center;}
        .atten-std-info{
            background-color:#d9edf7;
            text-align: center;
            padding-bottom: 6px;
            padding-top: 6px;
            border-bottom: 1px solid #dfdfdf;
            border-top: 1px solid #dfdfdf;
        }
        .atten-std-details{margin-bottom: 0; margin-top: 20px;}
        .page-main-title{
            background: #fcfcfc;
            padding-bottom: 15px;
            padding-top: 15px;
            box-shadow: 0px 0px 2px 0.1px;
            border: 0;
        }
        .std-title{text-align: center; display: block; padding-bottom: 2px; margin: 0;}
        /*//school Attendance Report*/
        /*student profile*/
        .attendance-report-info .area-panel{margin-top: 20px;}
        .stprofile-title h3{padding: 0; margin: 0; text-align: center; display: block;}
        .stprofile-title{margin-bottom: 34px; border-bottom: 1px solid #00a946; padding: 4px 0;}
        .stprofile-right .tb-ftbold>tbody tr td:first-child{color: #000; font-weight: 600;}
        .stprofile-right .tb-ftbold>tbody tr td:nth-child(4){color: #000; font-weight: 600;}
        .area-panel .stext-left h3{text-align: left; text-transform: uppercase; padding: 15px 0;}
        .area-panel .margin0{margin-bottom: 0;}
        .stprofile-left-inner h3{font-size: 20px; margin: 0; padding-top: 8px;}
        .stprofile-left-inner p{
            font-size: 16px;
            line-height: 24px;
            letter-spacing: 1px;
            color: #e98b59;
            padding: 20px 0;
        }
        /*Employer profile*/
        .stprofile-left .emprofession{background:#009fd1; padding: 0 15px; padding-top: 1px; color: #fff; }
        .stprofile-left .emprofession h3{text-transform: uppercase;font-size: 20px;color: #fff;padding: 0;margin-bottom: 5px;}
        .stprofile-left .emprofession p{padding-bottom: 15px;}
        .text-uper h3,
        .emeducation-panel h3,
        .employer-info{text-transform: uppercase;}
        .stprofile-left .em-contact ul li{margin: 0; padding: 0; color: #fff; font-size: 16px;}
        .stprofile-left-bg{background: #212832; margin-top: -10px; display: block; padding-left: 15px; padding-right: 15px;}
        .em-contact,.em-follow h3{margin: 0; font-size: 20px; padding-top: 15px;}
        .emcontent-bg h3{color: #fff;}
        ul.emfollow-text{margin-top: 10px;}
        ul.emfollow-text b{line-height: 25px;}
        ul.emfollow-text li:last-child{padding-bottom: 35px;}
        ul.emfollow-text li{color: #c6c6c6;}
        .stprofile-left .em-contact strong{font-weight: 600; color: #009fd1;font-size: 16px;line-height: 26px;margin: 0;padding: 0;padding-top: 15px;}
        .stprofile-left .em-contact ul li p{line-height: 25px;}
        .emfollow-text{}
        .stprofile-right{padding-right: 15px;}
        .emeducation-panel h3{border-bottom: 1px solid #dfdfdf;}
        .emeducation-right h4{font-size: 16px; text-transform: uppercase;}
        /*//....Employer profile...*/




        @media (max-width: 767px) {
            /*school Attendance Report*/
            .custom-flex{display: flex; flex-direction: column;}
            .custom-flex .attendance-table-right{order: 2 !important;}
            .custom-flex .atten-students-image{order: 1 !important;}
            .atten-students-image img{margin: 0 auto; display: block; text-align: center;}
            .attendance-top-logo img{ text-align: center; display: block; margin: 0 auto; margin-top: 10px; }
            .attendance-report .attendance-top-title .attendance-title{font-size: 17px; line-height: 24px;}
            .attendance-report .font16{font-size: 16px; margin-bottom: 3px; line-height: }
            /*school Attendance Report*/
            .stprofile-img img{text-align: center; display: block; margin: 0 auto; margin-bottom: 10px;}
        }

    </style>
			
	</head>

	<body>
		<div class="main-page-wrapper">
			<div class="container">
				<div class="row">
                <!--MAIN CONTENT-->
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="row attendance-report">
                            <div class="panel panel-info attendance-report-info">
                               <div class="attendance-top">
                                   <div class="col-md-2 col-sm-3 col-xs-12 attendance-top-logo"><img src="images/logos/logo.png" alt="image" width="150">
                                   </div>
                                   <div class="col-md-10 col-sm-9 col-xs-12 attendance-top-title">
                                        <h1 class="attendance-title">Kalapaharia Union High School</h1>
                                       <h3 class="attendance-address font16"><strong>Address: </strong>Araihazar, Narayanganj</h3>
                                       <p><b>Phone: </b>+8801712735975, <b>Email: </b>principalpba@gmail.com,<b> Website:</b> www.kalapaharia.com
                                       </p>
                                   </div>
                                   <div class="clearfix"></div>
                               </div>
                                <div class="page-main-title">
                                    <h3 class="std-title">Student Profile</h3>
                                </div><!--//table-overflow-->
                                <div class="clearfix"></div>
                               <div class="row area-panel">
                                   <div class="col-md-3 col-sm-4 col-xs-12 stprofile-left">
                                        <div class="stprofile-img">
                                        <img class="img-thumbnail" src="images/st6.jpg" alt="Profile iamge">
                                        </div>
                                   </div>
                                   <div class="col-md-9 col-sm-8 stprofile-right">
                                        <div class="stprofile-title">
                                            <h3>Class-Six (Section-A)</h3>
                                        </div>
                                        <div class="stprofile-inner table-responsive">
                                    <table class="table tb-ftbold">
                                        <tbody>
                                        <tr>
                                            <td width="15%">Roll</td>
                                            <td width="5%">:</td>
                                            <td width="30%">429109</td>
                                            <td width="15%">Email</td>
                                            <td width="5%">:</td>
                                            <td width="30%">taj2007@gmail.com</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Name</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Md. Taj Uddin</td>
                                           <td width="15%">Gender</td>
                                           <td width="5%">:</td>
                                           <td width="30%">Male</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Brithday</td>
                                            <td width="5%">:</td>
                                            <td width="30%">2007.02.28</td>
                                            <td width="15%">Religion</td>
                                            <td width="5%">:</td>
                                           <td width="30%">Islam</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Phone</td>
                                            <td width="5%">:</td>
                                            <td width="30%">01700000000</td>
                                            <td width="15%">Blood Group</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                        </tr>                                       
                                        </tbody>
                                    </table>
                                    </div><!--//table-overflow-->
                                   </div>
                               </div> <!--//area-panel-->
                               <div class="row area-panel">
                                <div class="col-md-12 col-xs-12">
                                    <div class="stprofile-title stparent stext-left margin0">
                                      <h3>Parents</h3>
                                    </div>
                                    <div class="row">
                                   <div class="col-md-3 col-sm-4 col-xs-12 stprofile-left">
                                        <div class="stprofile-left-inner">
                                            <h3 class="stparent-title">My Father</h3>
                                            <p>Authorized to pic up<br> Emergency Contact</p>
                                        </div>
                                   </div>
                                   <div class="col-md-9 col-sm-8 col-xs-12 stprofile-right">
                                        <div class="stprofile-inner table-responsive">
                                    <table class="table tb-ftbold">
                                        <tbody>
                                        <tr>
                                            <td width="15%">Address</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                            <td width="15%">Email</td>
                                            <td width="5%">:</td>
                                            <td width="30%">abcd@gmail.com</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Cell</td>
                                            <td width="5%">:</td>
                                            <td width="30%">01700000000</td>                                      
                                           <td width="15%">Phone</td>
                                           <td width="5%">:</td>
                                           <td width="30">+024578910</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Fax</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                            <td width="15%">Occupation</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Businessman</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Status</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                            <td width="15%">Others info</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                        </tr>                                       
                                        </tbody>
                                    </table>
                                    </div><!--//table-overflow-->
                                   </div>
                                   </div>
                                   </div>
                               </div> <!--//area-panel-->
                               <div class="row area-panel">
                                <div class="col-md-12 col-xs-12">
                                    <div class="stprofile-title stparent stext-left margin0">
                                      <h3>Address</h3>
                                    </div>
                                    <div class="row">
                                   <div class="col-md-3 col-sm-4 col-xs-12 stprofile-left">
                                        <div class="stprofile-left-inner ">
                                            <h3 class="stparent-title">Current Address</h3>
                                            
                                        </div>
                                   </div>
                                   <div class="col-md-9 col-sm-8 col-xs-12 stprofile-right">
                                        <div class="stprofile-inner table-responsive">
                                    <table class="table tb-ftbold">
                                        <tbody>
                                        <tr>
                                            <td width="15%">Address</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                            <td width="15%">State</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">City</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>                                      
                                           <td width="15%">House No</td>
                                           <td width="5%">:</td>
                                           <td width="30">#002</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Country</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                            <td width="15%">Road No</td>
                                            <td width="5%">:</td>
                                            <td width="30%">#121</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Zip Code</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                            <td width="15%">Phone</td>
                                            <td width="5%">:</td>
                                            <td width="30%">+024584910</td>
                                        </tr>                                       
                                        </tbody>
                                    </table>
                                    </div><!--//table-overflow-->
                                   </div>
                                   </div>
                                   </div>
                               </div> <!--//area-panel-->
                               <div class="row area-panel">
                                <div class="col-md-12 col-xs-12">
                                    <div class="stprofile-title stparent stext-left margin0">
                                      <h3>Address</h3>
                                    </div>
                                    <div class="row">
                                   <div class="col-md-3 col-sm-4 col-xs-12 stprofile-left">
                                        <div class="stprofile-left-inner">
                                            <h3 class="stparent-title">Permanent Address</h3>
                                            
                                        </div>
                                   </div>
                                   <div class="col-md-9 col-sm-8 stprofile-right">
                                        <div class="stprofile-inner table-responsive">
                                    <table class="table tb-ftbold">
                                        <tbody>
                                        <tr>
                                            <td width="15%">Address</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                            <td width="15%">State</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">City</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>                                      
                                           <td width="15%">House No</td>
                                           <td width="5%">:</td>
                                           <td width="30">#002</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Country</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                            <td width="15%">Road No</td>
                                            <td width="5%">:</td>
                                            <td width="30%">#121</td>
                                        </tr>
                                        <tr>
                                            <td width="15%">Zip Code</td>
                                            <td width="5%">:</td>
                                            <td width="30%">Null</td>
                                            <td width="15%">Phone</td>
                                            <td width="5%">:</td>
                                            <td width="30%">+024584910</td>
                                        </tr>                                       
                                        </tbody>
                                    </table>
                                    </div><!--//stprofile-inner-->
                                   </div><!--//stprofile-right-->
                                   </div>
                                   </div><!--//col-12-->
                               </div> <!--//area-panel-->
                            </div><!--//attendance-report-info-->
                    </div><!--//attendance-report-->
                </div> <!-- /.col-12- -->
                <!--//MAIN CONTENT-->

            </div>
			</div>
		</div> <!-- /.main-page-wrapper -->
	</body>

</html>		