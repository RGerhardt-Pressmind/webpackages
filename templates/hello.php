<!DOCTYPE html>
<html>
<head lang="en">
	<meta charset="UTF-8">
	<title>Hallo Welt</title>

	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
</head>
<body>
	<div class="container text-center">
		<h3>cURL</h3>
		<form class="form-horizontal">
			<div class="control-group">
				<label class="control-label"><strong>cURL Exists</strong></label>
				<div class="controls"><?php echo (\package\curl::existCurl() === true) ? '<span class="text-success">Aktiv</span>' : '<span class="text-danger">Inaktiv</span>' ?></div>
			</div>

			<div class="control-group">
				<label class="control-label"><strong>cURL Status - http://www.google.de</strong></label>
				<div class="controls"><?php echo 'HTTP-Code: '.\package\curl::getState('http://www.google.de'); ?></div>
			</div>

			<div class="control-group">
				<label class="control-label"><strong>cURL City Coordinates - Berlin</strong></label>
				<div class="controls"><?php
					$coord	=	\package\curl::getCityCoordinates('Berlin');

					$lat	=	$coord->lat;
					$lng	=	$coord->lng;

					echo $lat.' - '.$lng;
					?></div>
			</div>

			<div class="control-group">
				<label class="control-label"><strong>cURL City Name by IP</strong></label>
				<div class="controls"><?php echo \package\curl::getCityNameByIp(); ?></div>
			</div>
		</form>
		<br><br>
		<h3>Number</h3>
		<form class="form-horizontal">
			<div class="control-group">
				<label class="control-label"><strong>Byte Formartieren - 1234567 B</strong></label>
				<div class="controls"><?php echo \package\number::byte_format(1234567, 2); ?></div>
			</div>

			<div class="control-group">
				<label class="control-label"><strong>Diff Datum - <?php echo date('d.m.Y').' - '.date('d.m.Y', strtotime('+2 days')) ?></strong></label>
				<div class="controls"><?php
					$diffIN	=	\package\number::diff(time(), strtotime('+2 days'));

					echo $diffIN->year.' Jahre '.$diffIN->month.' Monate '.$diffIN->day.' Tage '.$diffIN->hour.' Stunden '.$diffIN->min.' Minuten';
					?></div>
			</div>
		</form>
		<br><br>
		<h3>Plugin Test</h3>
		<?php
		\package\plugins::hookTemplate('hello', 'body');
		?>

		<br><br>
		<h3>Session Test</h3>
		<?php
		echo $_SESSION['test'];
		?>

		<br><br>
		<h3>Benchmark der Seite</h3>
		<?php
		\package\benchmark::endPoint(true);
		echo number_format(\package\benchmark::finish(), 4, '.', '').' Sekunden';
		?>
	</div>
</body>
</html>