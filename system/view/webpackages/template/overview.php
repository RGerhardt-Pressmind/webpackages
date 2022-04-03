<?php

use system\core\Language;

require dirname(__FILE__).DIRECTORY_SEPARATOR.'header.php';

?>
<section id="main-slider" class="carousel">
	<div class="carousel-inner">
		<div class="item active">
			<div class="container">
				<div class="carousel-content">
					<h1><?php echo Language::translate('item1.headline'); ?></h1>
					<p class="lead"><?php echo Language::translate('item1.description'); ?></p>
				</div>
			</div>
		</div><!--/.item-->
		<div class="item">
			<div class="container">
				<div class="carousel-content">
					<h1><?php echo Language::translate('item2.headline'); ?></h1>
					<p class="lead"><?php echo Language::translate('item2.description'); ?></p>
				</div>
			</div>
		</div><!--/.item-->
	</div><!--/.carousel-inner-->
	<a class="prev" href="#main-slider" data-slide="prev"><i class="icon-angle-left"></i></a>
	<a class="next" href="#main-slider" data-slide="next"><i class="icon-angle-right"></i></a>
</section><!--/#main-slider-->

<section class="container">
	<div class="row" style="margin-top: 4em;">
		<div class="col-md-12">
			<p class="text-center" style="font-size: 1.68em;"><?php echo Language::translate('container.text') ?></p>
		</div>
	</div>

	<div class="row" style="margin-top: 4em;">
		<div class="col-md-4 text-center">
			<i class="icon-refresh icon-4x" style="color: #2ecc71;"></i>
			<p style="font-weight: bold; font-size: 2em;"><?php echo Language::translate('speed'); ?></p>
			<p style="font-size: 1.1em;"><?php echo Language::translate('speed.text'); ?></p>
		</div>
		<div class="col-md-4 text-center">
			<i class="icon-shield icon-4x" style="color: #4f7dd4;"></i>
			<p style="font-weight: bold; font-size: 2em;"><?php echo Language::translate('security'); ?></p>
			<p style="font-size: 1.1em;"><?php echo Language::translate('security.text'); ?></p>
		</div>
		<div class="col-md-4 text-center">
			<i class="icon-anchor icon-4x" style="color: #8e44ad;"></i>
			<p style="font-weight: bold; font-size: 2em;"><?php echo Language::translate('stable'); ?></p>
			<p style="font-size: 1.1em;"><?php echo Language::translate('stable.text'); ?></p>
		</div>
	</div>

	<div class="row" style="margin-top: 4em;">
		<h2 style="font-size: 2em; font-weight: bold;" class="text-center"><?php echo Language::translate('container.subline'); ?></h2>

		<div class="col-md-12">
			<p class="text-center" style="font-size: 1.68em;"><?php echo Language::translate('container.text2') ?></p>
		</div>
		<div class="col-md-12">
			<div id="chart-container"></div>
		</div>
		<div class="col-md-12" style="margin-top: 2em;">
			<div id="chart-container-2"></div>
		</div>
	</div>
</section>
<?php
require dirname(__FILE__).DIRECTORY_SEPARATOR.'footer.php';
?>
