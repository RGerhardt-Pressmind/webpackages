<section id="main-slider" class="carousel">
	<div class="carousel-inner">
		<div class="item active">
			<div class="container">
				<div class="carousel-content">
					<h1><?php echo \package\core\language::translate('Kostenloses PHP Framework'); ?></h1>
					<p class="lead"><?php echo \package\core\language::translate('webpackages ist das beste und schnellste PHP Framework<br> das man zum erstellen von neuen PHP Projekten verwenden kann'); ?></p>
				</div>
			</div>
		</div><!--/.item-->
		<div class="item">
			<div class="container">
				<div class="carousel-content">
					<h1><?php echo \package\core\language::translate('Sicherheit an erster Stelle'); ?></h1>
					<p class="lead"><?php echo \package\core\language::translate('Mit webpackages brauchen Sie sich keine Sorgen mehr<br> um die Sicherheit Ihrer PHP Projekte machen.'); ?></p>
				</div>
			</div>
		</div><!--/.item-->
	</div><!--/.carousel-inner-->
	<a class="prev" href="#main-slider" data-slide="prev"><i class="icon-angle-left"></i></a>
	<a class="next" href="#main-slider" data-slide="next"><i class="icon-angle-right"></i></a>
</section><!--/#main-slider-->

<section class="container">
	<div class="row">
		<div class="col-md-12 text-center">
			<h2><?php echo \package\core\language::translate('Benchmark Ergebnis'); ?>: <?php echo \package\core\benchmark::finish().' '.\package\core\language::translate('Sekunden'); ?></h2>
		</div>
	</div>
</section>