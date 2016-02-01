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
	<div class="row" style="margin-top: 4em;">
		<div class="col-md-12">
			<p class="text-center" style="font-size: 1.68em;">Wir von <strong>webpackages</strong> schaffen Vertrauen.<br>Denn unser PHP Framework schafft für Sie das unmögliche. Ein PHP Projekte mit einer Leichtigkeit und Freude umzusetzen und dabei noch Professionel sein.</p>
		</div>
	</div>

	<div class="row" style="margin-top: 4em;">
		<div class="col-md-4 text-center">
			<i class="icon-refresh icon-4x" style="color: #2ecc71;"></i>
			<p style="font-weight: bold; font-size: 2em;">Schnelligkeit</p>
			<p style="font-size: 1.1em;">webpackages ist dank eines gut programmierten Kerns unglaublich schnell. Um Ihnen zu demonstrieren wie schnell, hier die aktuelle Ladezeit dieser Seite: <strong><?php echo number_format(\package\core\benchmark::finish(), 4, ',', '.'); ?> Sekunden</strong>.</p>
		</div>
		<div class="col-md-4 text-center">
			<i class="icon-shield icon-4x" style="color: #4f7dd4;"></i>
			<p style="font-weight: bold; font-size: 2em;">Hohe Sicherheit</p>
			<p style="font-size: 1.1em;">Durch bewährte Technologien und Anwendungen kann webpackages Ihre Webprojekte stets vor Angriffen durch Benutzereingaben schützen. Diese Technologien werde dennoch stätig erweitert und auf neue Gefahren angepasst.</p>
		</div>
		<div class="col-md-4 text-center">
			<i class="icon-anchor icon-4x" style="color: #8e44ad;"></i>
			<p style="font-weight: bold; font-size: 2em;">Stabile Umgebung</p>
			<p style="font-size: 1.1em;">Durch unserer Ausgereifte Dokumentationen können Sie alles zu webpackages erfahren. Des Weiteren steht Ihnen unserer Hilfsbereite Community jederzeit zur Verfügung.</p>
		</div>
	</div>

	<div class="row" style="margin-top: 4em;">
		<h2 style="font-size: 2em; font-weight: bold;" class="text-center">Wir machen Ihr Leben einfacher</h2>

		<div class="col-md-12">
			<p class="text-center" style="font-size: 1.68em;">Mit dem webpackages Framework können Sie so viel mehr erreichen als Sie es bisher mit anderen Frameworks erreicht haben. Um Ihnen dies in Zahlen zu zeigen, haben wir hier für Sie eine kleine Statistik, basierend auf detailierten Technischen Daten eine sich aufbauenden Seite, bereitgestellt.</p>
		</div>
		<div class="col-md-12">
			<div id="chart-container">FusionCharts will render here</div>
		</div>
	</div>
	<?php

	echo (memory_get_usage()/1024/1024);
	?>
</section>