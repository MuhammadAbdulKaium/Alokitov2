<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
	      content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Seat Token View</title>

	<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<!------ Include the above in your HEAD tag ---------->
	<style>
		/*.invoice {*/
		/*position: relative;*/
		/*background: #fff;*/
		/*border: 1px solid #f4f4f4;*/
		/*padding: 10px;*/
		/*!*margin: 10px 25px;*!*/
		/*}*/
		/*.page-header {*/
		/*margin: 10px 0 20px 0;*/
		/*font-size: 22px;*/
		/*}*/
		.heading {
			text-align: center;
			margin: 0px;
			padding: 0px;
		}
		.heading h2 {
			font-size: 14px;
		}
		p {
			font-size: 12px;
			margin: 0px;
			padding: 0px;
		}
		h2, h5 {
			margin: 0px;
			padding: 0px;
		}

		.fontSize13 {
			font-size: 20px;
			line-height: 22px;
		}
		.table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td {
			padding: 1px !important;
		}

		.table>thead { text-align: center}

		@media print {
			.heading h2 {
				font-size: 10px;
				margin-top: 2px;
				font-weight: bold;
				text-align: center;
			}
			.col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
				float: left;
			}
			.col-sm-12 {
				width: 100%;
			}
			.col-sm-11 {
				width: 91.66666667%;
			}
			.col-sm-10 {
				width: 83.33333333%;
			}
			.col-sm-9 {
				width: 75%;
			}
			.col-sm-8 {
				width: 66.66666667%;
			}
			.col-sm-7 {
				width: 58.33333333%;
			}
			.col-sm-6 {
				width: 50%;
			}
			.col-sm-5 {
				width: 41.66666667%;
			}
			.col-sm-4 {
				width: 33.33333333%;
			}
			.col-sm-3 {
				width: 25%;
			}
			.col-sm-2 {
				width: 16.66666667%;
			}
			.col-sm-1 {
				width: 8.33333333%;
			}

			.breakNow {
				page-break-inside:avoid; page-break-after:always;
				margin-top: 10px;
			}
			.fontSize13 {
				font-size:15px;
			}
		}


	</style>
</head>
<body>

{{--<section class="container">--}}
<button class="btn btn-primary hidden-print pull-right" onclick="myFunction()"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</button>
@php $count=0; @endphp
<div class="col-sm-12" style="margin: 0px; padding: 0px">
	@foreach($studentList as $studentProfile)
		<div class="col-sm-6" style="margin-top: 15px;  padding: 2px">
			<section class="invoice" style="border: double #ccc;
                 padding: 2px; margin-left: 10px; margin-right: 10px">
				<!-- title row -->
				<div class="row">
					<div class="col-xs-12">
						{{--<img src="{{URL::to('/assets/users/images',$instituteInfo->logo)}}" style="position: absolute" height="40px;" width="40px">--}}

						<div class="heading">
							{{--<h2>{{$instituteInfo->institute_name}}</h2>--}}
							{{--<p>{{$instituteInfo->address1}}</p>--}}
							<p style="border-radius:5px; border: 1px solid #ccc; font-weight: bold; margin-top: 2px; padding: 0px; font-size: 20px"> {{$examName}}</p>
						</div>
					</div><!-- /.col -->
				</div>
				<!-- info row -->
				<div class="row invoice-info" style="margin-top: 5px">
					<div class="col-sm-3 invoice-col">
						@if($studentProfile->singelAttachment("PROFILE_PHOTO"))
							<img class="center-block  img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/'.$studentProfile->singelAttachment('PROFILE_PHOTO')->singleContent()->name)}}" alt="No Image" style="width:70px;height:70px;margin-left: 5px">
						@else
							<img class="center-block img-thumbnail img-responsive" src="{{URL::asset('assets/users/images/user-default.png')}}" alt="No Image" style="width:100px;height:100px; margin-left: 5px">
						@endif
					</div>
					<div class="col-sm-9 invoice-col" style="padding: 0px">

						<table class="fontSize13">
							{{--<tr>--}}
							{{--<td width="20%">ID</td>--}}
							{{--<td>: {{$studentProfile->username}}</td>--}}
							{{--</tr>--}}
							<tr width="20%">
								<td>Name</td>
								<td>: {{$studentProfile->first_name.' '.$studentProfile->middle_name.' '.$studentProfile->last_name}}</td>
							</tr>
							<tr>
								<td>Class </td>
								<td>: {{$studentProfile->batch()->batch_name}}</td>
							</tr>

							@php $studentInfo = findStudent($studentProfile->std_id) @endphp
							@php $studentEnroll = $studentInfo->enroll(); @endphp
							@if($studentEnroll->batch()->get_division())
								<tr>
									<td><b>Group</b></td>
									<td colspan="3">: <b>{{$studentEnroll->batch()->get_division()->name}}</b></td>
								</tr>
							@endif
							<tr>
								<td>Section </td>
								<td>: {{$studentProfile->section()->section_name}}</td>
							</tr>
							<tr>
								<td><b>Roll No</b> </td>
								<td>: <b>{{$studentProfile->gr_no}}</b></td>
							</tr>
							<tr>
								<td colspan="3"> </td>
							</tr>
						</table>
					</div><!-- /.col -->
				</div><!-- /.row -->

			</section>
		</div>
		@php $count++ @endphp
		@if($count%12==0)
			<div class="breakNow"></div>
			@php $count=0; @endphp
		@endif

	@endforeach


</div>
{{--</section>--}}


<script>
    function myFunction() {
        window.print();
    }
</script>

</body>
</html>