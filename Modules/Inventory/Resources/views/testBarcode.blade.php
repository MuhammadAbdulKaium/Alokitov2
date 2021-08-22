<html>	
	<head>
		<title>Barcode - Websolutionstuff</title>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	</head>
	<h1 class="text-primary" style="text-align: center;margin-bottom: 20px;">Laravel Barcode Generator - Websolutionstuff</h1>
	<div style="text-align: center;">
		<?php $name = "test"; ?>
		<img src="data:image/png;base64,{{DNS1D::getBarcodePNG($name, 'C39')}}" alt="barcode" /><br><br>
		<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('123456789', 'C39+',1,33,array(0,0,0), true)}}" alt="barcode" /><br><br>
		<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('4', 'C39+',3,33,array(255,0,0))}}" alt="barcode" /><br><br>
		<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('12', 'C39+')}}" alt="barcode" /><br><br>
		<img src="data:image/png;base64,{{DNS1D::getBarcodePNG('23', 'POSTNET')}}" alt="barcode" /><br/><br/>
		<?php echo DNS2D::getBarcodeHTML('4445645656', 'QRCODE',5,5); ?>
	</div>
</html>