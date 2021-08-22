@extends('layouts.master')

{{-- Web site Title --}}

@section('styles')
	{{--<link rel="stylesheet" type="text/css" href="https://www.pigno.se/barn/PIGNOSE-Calendar/demo/css/semantic.ui.min.css />--}}
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('template-2/pg-calender/css/style.css') }}" />
	<link rel="stylesheet" type="text/css" href="{{ URL::asset('template-2/pg-calender/css/pignose.calendar.css') }}" />

	<style type="text/css">
		.input-calendar {
			display: block;
			width: 100%;
			max-width: 360px;
			margin: 0 auto;
			height: 3.2em;
			line-height: 3.2em;
			font: inherit;
			padding: 0 1.2em;
			border: 1px solid #d8d8d8;
			box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
			-o-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
			-moz-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
			-webkit-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
		}

		.btn-calendar {
			display: block;
			width: 100%;
			max-width: 360px;
			height: 3.2em;
			line-height: 3.2em;
			background-color: #52555a;
			margin: 0 auto;
			font-weight: 600;
			color: #ffffff !important;
			text-decoration: none !important;
			box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
			-o-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
			-moz-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
			-webkit-box-shadow: 0 4px 12px rgba(0, 0, 0, .25);
		}

		.btn-calendar:hover {
			background-color: #5a6268;
		}

		.headTag {
			font-weight: 700;
			font-size: 18px;
		}

	</style>
@stop

{{-- Content --}}
@section('content')

	<section class="breadcrumb-bg">
		<div class="container-fluid">
			<div class="col-md-6">
				<h5 class="text-color-white"><i class="fa fa-bullhorn icon-margin"></i> Message of the day:</h5>
			</div>
		</div>
	</section>

	{{--<section class="breadcrumb-bg">--}}
	{{--<div class="container-fluid">--}}
	{{--<div class="col-md-6">--}}
	{{--<ul class="breadcrumb">--}}
	{{--<li><a href="#">Home</a></li>--}}
	{{--<li><a href="#">Student</a></li>--}}
	{{--<li class="active">Accessories</li>--}}
	{{--</ul>--}}
	{{--</div>--}}
	{{--<div class="col-md-6">--}}
	{{--<h5 class="text-color-white"><i class="fa fa-bullhorn icon-margin"></i> Message of the day:</h5>--}}
	{{--</div>--}}
	{{--</div>--}}
	{{--</section><!--breadcrumb and todayes news End-->--}}
	{{--<div class="clearfix"></div>--}}

	<section class="4-big-button">
		<div class="container-fluid">
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
					<div class="offer offer-default">
						<div class="shape">
							<div class="shape-text">
								Fees
							</div>
						</div>
						<div class="offer-content">
							<h3 class="headTag">Tuition Fees Generate</h3>
							<p>{{$totalFeesGenerate}} TK</p>
							<h3 class="headTag">Tuition Fees Collected</h3>
							<p>{{$totalFeesCollected}} TK</p>

						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
					<div class="offer offer-radius offer-primary">
						<div class="shape">
							<div class="shape-text">
								fine
							</div>
						</div>
						<div class="offer-content">
						<h3 class="headTag">Total Attendance Fine</h3>
						<p>{{$totalAttendanceFineGenerate}} TK</p>
						<h3 class="headTag">Total Collected</h3>
						<p>{{$totalAttendanceFineCollected}} TK</p>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
					<div class="offer offer-info">
						<div class="shape">
							<div class="shape-text">
								Due
							</div>
						</div>
						<div class="offer-content">

							<h3 class="headTag">Due Fine Generated</h3>
							<p>{{$totalDueFineGenerated}} TK</p>
							<h3 class="headTag">Due Fine Collected</h3>
							<p>{{$totalDueFineCollected}} TK</p>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
					<div class="offer offer-warning">
						<div class="shape">
							<div class="shape-text">
								paid
							</div>
						</div>
						<div class="offer-content">
							<h3 class="lead">
								Static Data
							</h3>
							<p>
								5000Tk <br>
								Hello World
							</p>
						</div>
					</div>
				</div>
		</div><!--4-big-button Containert End-->

		<div class="container-fluid">

			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<div class="offer offer-success">
					<div class="shape">
						<div class="shape-text">
							monthly
						</div>
					</div>
					<div class="offer-content">

						<div class="form-group" style="margin-top: 10px">
							<label for="sel1">Select Month:</label>
							<select class="form-control" id="tuition_fees_month">
								{{--current Month--}}
								@php $month=date('n'); @endphp
								@for($iM =1;$iM<=12;$iM++)
									<option @if($iM==$month) selected @endif value="{{$iM}}">{{date("M", strtotime("$iM/12/10"))}}</option>

								@endfor
							</select>
						</div>

						<h3 class="headTag">Tuition Fees</h3>
						<p class="monthlyTutionFeesGenerate">{{$monthlyTuitionFees['monthly_tutionfees_generated']}} TK</p>
						<h3 class="headTag">Tuition Fees Collected</h3>
						<p class="monthlyTutionFeesCollected">{{$monthlyTuitionFees['monthly_tutionfees_collected']}} TK</p>
					</div>
				</div>
			</div>

		</div>
	</section><!--4-big-button Section End-->

	<div class="clearfix"></div>

	<div class="modal" id="globalModal" tabindex="-1" role="dialog"  aria-labelledby="esModalLabel" aria-hidden="true">
		<div id="modal-dialog" class="modal-dialog">
			<div class="modal-content" >
				<div class="modal-body">
					<div class="loader">
						<div class="es-spinner">
							<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@stop

{{-- Scripts --}}


@section('scripts')

	<script>

		$('#tuition_fees_month').change(function() {

		    var month_num= $("#tuition_fees_month").val();
            $.ajax({
                url: "/fees/monthly/tuition-fees/" + month_num,
                type: 'GET',
                cache: false,
                datatype: 'json',

                beforeSend: function (data) {
                    // statements
                },

                success: function (data) {
                   console.log(data);
                   $('.monthlyTutionFeesGenerate').text(data.monthly_tutionfees_generated);
                   $('.monthlyTutionFeesCollected').text(data.monthly_tutionfees_collected);
                }
            });

		});

	</script>


@stop
