<!DOCTYPE html>
<html lang="en">
<head>

	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Fees Invoice Report</title>
	<style>
		@font-face {
			font-family: 'Siyamrupali';
		}

		body {  font-family: 'Siyamrupali';  }
		.clearfix:after {
			content: "";
			display: table;
			clear: both;
		}

		a {
			color: #0087C3;
			text-decoration: none;
		}

		body {
			position: relative;
			width: 800px;
			height: 29.7cm;
			margin: 0 auto;
			color: #555555;
			background: #FFFFFF;
			font-family: Arial, sans-serif;
			font-size: 14px;
			font-family: SourceSansPro;
		}

		header {
			padding: 10px 0;
			margin-bottom: 20px;
			border-bottom: 1px solid #AAAAAA;
		}

		#logo {
			float: left;
			margin-top: 8px;
		}

		#logo img {
			height: 70px;
		}

		#company {
			float: right;
			text-align: right;
		}


		#details {
			margin-bottom: 50px;
		}

		#client {
			padding-left: 6px;
			border-left: 6px solid #0087C3;
			float: left;
		}

		#client .to {
			color: #777777;
		}

		h2.name {
			font-size: 1.4em;
			font-weight: normal;
			margin: 0;
		}

		#invoice {
			float: right;
			text-align: right;
		}

		#invoice h1 {
			color: #0087C3;
			font-size: 2.4em;
			line-height: 1em;
			font-weight: normal;
			margin: 0  0 10px 0;
		}

		#invoice .date {
			font-size: 1.1em;
			color: #777777;
		}

		table {
			width: 90%;
			border-collapse: collapse;
			border-spacing: 0;
			margin-bottom: 20px;
		}

		table th,
		table td {
			padding: 5px;
			background: #EEEEEE;
			text-align: center;
			border-bottom: 1px solid #FFFFFF;
		}

		table th {
			white-space: nowrap;
			font-weight: normal;
		}

		table td {
			text-align: right;
		}

		table td h3{
			color: #57B223;
			font-size: 12px;
			font-weight: normal;
			margin: 0 0 0.2em 0;
		}

		table .no {
			color: #FFFFFF;
			font-size: 12px;
			background: #57B223;
		}

		table .desc {
			text-align: left;
		}

		table .unit {
			background: #DDDDDD;
		}

		table .qty {
		}

		table .total {
			background: #57B223;
			color: #FFFFFF;
		}

		table td.unit,
		table td.qty,
		table td.total {
			font-size: 12px;
		}

		table tbody tr:last-child td {
			border: none;
		}

		table tfoot td {
			padding: 5px 10px;
			background: #FFFFFF;
			border-bottom: none;
			font-size: 12px;
			white-space: nowrap;
			border-top: 1px solid #AAAAAA;
		}

		table tfoot tr:first-child td {
			border-top: none;
		}

		table tfoot tr:last-child td {
			color: #57B223;
			font-size: 12px;
			border-top: 1px solid #57B223;

		}

		table tfoot tr td:first-child {
			border: none;
		}

		#thanks{
			font-size: 2em;
			margin-bottom: 50px;
		}

		#notices{
			padding-left: 6px;
			border-left: 6px solid #0087C3;
		}

		#notices .notice {
			font-size: 1.2em;
		}

		footer {
			color: #777777;
			width: 100%;
			height: 30px;
			position: absolute;
			bottom: 0;
			border-top: 1px solid #AAAAAA;
			padding: 4px 0;
			text-align: center;
		}
		span.label {
			background: red;
			color:#FFffff;
			padding: 3px;
			height: 60px;
		}

		.transactions_table {
			font-size: 10px;
		}
		.payment {
			font-size: 16px;
			padding: 10px;
		}
		.paid-icon {
			padding-bottom: 10px;
		}


	</style>
</head>
<body>

<header class="clearfix" style="width: 720px">
	<div id="logo">
		<img src="{{public_path('assets/users/images/'.$institute->logo)}}">
	</div>

	<div id="company" style="width: 720px">
		<h2 class="name">{{$institute->institute_name}}</h2>
		<div style="width: 200px; float:right ">
			{{$institute->address2}}
			{{$institute->phone}}
			<a href="mailto:{{$institute->email}}">{{$institute->email}}</a>
		</div>
	</div>
</header>
<div>
	@php $std = $applicantProfile->personalInfo() @endphp
	<div id="details" class="clearfix" style="width: 550px">
		<div id="client">
			<div class="to">INVOICE TO:</div>
			<h2 class="name">{{$std->std_name}}<br>
			</h2>
			E-mail : <a href="{{$applicantProfile->email}}">{{$applicantProfile->email}}</a>
			<br>
			Contact No. : {{$std->phone}}
		</div>
		<div id="invoice" style="width: 720px">
			<h3>Invoice NO # {{$applicantProfile->fees()->invoice_no}}</h3>
			<h3>Application NO # {{$applicantProfile->application_no}}</h3>
			<div class="date">Payment Date: {{date('d M, Y', strtotime($applicantProfile->fees()->created_at))}}</div>
		</div>
	</div>

	<div class="paid-icon">
		<img src="{{public_path().'/assets/admission/fees/icon-paid.gif'}}" alt="Paid Image" class="img-responsive" style="width:65px;margin-top: 5px;">
	</div>
</div>

<table  border="0" cellspacing="0" cellpadding="0">
	<thead>
	<tr>
		<th class="no">#</th>
		<th class="desc">DESCRIPTION</th>
		<th class="unit">UNIT PRICE</th>
		<th class="qty">QUANTITY</th>
		<th class="total">TOTAL</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td class="no">01</td>
		<td  style="font-family:Siyamrupali;" class="desc"> Application Fees </td>
		<td class="unit">{{$applicantProfile->fees()->fees_amount}} </td>
		<td class="qty">1</td>
		<td class="total">{{$applicantProfile->fees()->fees_amount}}</td>
	</tr>

	</tbody>
	<tfoot>
	<tr>
		<td colspan="2"></td>
		<td colspan="2">SUBTOTAL</td>
		<td>{{$applicantProfile->fees()->fees_amount}}</td>
	</tr>
	<tr>
		<td colspan="2"></td>
		<td colspan="2">Discount</td>
		<td>0</td>
	</tr>

	<tr>
		<td colspan="2"></td>
		<td colspan="2">GRAND TOTAL</td>
		<td>{{$applicantProfile->fees()->fees_amount}}</td>
	</tr>

	<tr>
		<td colspan="2"></td>
		<td colspan="2">Total Amount Paid</td>
		<td>{{$applicantProfile->fees()->fees_amount}}</td>
	</tr>

	<tr>
		<td colspan="2"></td>
		<td colspan="2">Total Amount Due</td>
		<td class="right strong">0</td>
	</tr>

	</tfoot>
</table>
<footer>
	Invoice was created on a computer and is valid without the signature and seal.
</footer>
</body>
</html>