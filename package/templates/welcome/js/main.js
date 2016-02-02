jQuery(function($) {

	$(function(){

		FusionCharts.ready(function () {
			var topStores = new FusionCharts({
				type: 'bar2d',
				renderAt: 'chart-container',
				width: '1140',
				height: '400',
				dataFormat: 'json',
				dataSource: {
					"chart": {
						"caption": "Ladezeiten einer HTML Seite",
						"subCaption": "mit 1.000 Zeilen PHP Code",
						"yAxisName": "Ladezeit in Sekunden",
						"numberPrefix": "",
						"numberSuffix": " Sekunden",
						"paletteColors": "#0075c2",
						"bgColor": "#ffffff",
						"showBorder": "0",
						"showCanvasBorder": "0",
						"usePlotGradientColor": "0",
						"plotBorderAlpha": "10",
						"placeValuesInside": "1",
						"valueFontColor": "#ffffff",
						"showAxisLines": "1",
						"axisLineAlpha": "25",
						"divLineAlpha": "10",
						"alignCaptionWithCanvas": "0",
						"showAlternateVGridColor": "0",
						"captionFontSize": "14",
						"subcaptionFontSize": "14",
						"subcaptionFontBold": "0",
						"toolTipColor": "#ffffff",
						"toolTipBorderThickness": "0",
						"toolTipBgColor": "#000000",
						"toolTipBgAlpha": "80",
						"toolTipBorderRadius": "2",
						"toolTipPadding": "5"
					},

					"data": [
						{
							"label": "codeigniter-3.0",
							"value": "1.1947"
						},
						{
							"label": "slim-2.6",
							"value": "1.0474"
						},
						{
							"label": "symfony-2.6",
							"value": "0.9173"
						},
						{
							"label": "webpackages 2.0",
							"value": "0.8241"
						}
					]
				}
			})
			.render();
		});


		FusionCharts.ready(function () {
			var topStores = new FusionCharts({
				type: 'bar2d',
				renderAt: 'chart-container-2',
				width: '1140',
				height: '400',
				dataFormat: 'json',
				dataSource: {
					"chart": {
						"caption": "Benutzter Arbeitsspeicher(Memory)",
						"subCaption": "mit 1.000 Zeilen PHP Code",
						"yAxisName": "Arbeitsspeicher in MegaByte",
						"numberPrefix": "",
						"numberSuffix": " MB",
						"paletteColors": "#0075c2",
						"bgColor": "#ffffff",
						"showBorder": "0",
						"showCanvasBorder": "0",
						"usePlotGradientColor": "0",
						"plotBorderAlpha": "10",
						"placeValuesInside": "1",
						"valueFontColor": "#ffffff",
						"showAxisLines": "1",
						"axisLineAlpha": "25",
						"divLineAlpha": "10",
						"alignCaptionWithCanvas": "0",
						"showAlternateVGridColor": "0",
						"captionFontSize": "14",
						"subcaptionFontSize": "14",
						"subcaptionFontBold": "0",
						"toolTipColor": "#ffffff",
						"toolTipBorderThickness": "0",
						"toolTipBgColor": "#000000",
						"toolTipBgAlpha": "80",
						"toolTipBorderRadius": "2",
						"toolTipPadding": "5"
					},

					"data": [
						{
							"label": "codeigniter-3.0",
							"value": "3.8812"
						},
						{
							"label": "slim-2.6",
							"value": "2.5256"
						},
						{
							"label": "symfony-2.6",
							"value": "1.3424"
						},
						{
							"label": "webpackages 2.0",
							"value": "0.7957"
						}
					]
				}
			})
			.render();
		});


		$('#main-slider.carousel').carousel({
			interval: 10000,
			pause: false
		});
	});

	//Ajax contact
	var form = $('.contact-form');
	form.submit(function () {
		$this = $(this);
		$.post($(this).attr('action'), function(data) {
			$this.prev().text(data.message).fadeIn().delay(3000).fadeOut();
		},'json');
		return false;
	});

	//smooth scroll
	$('.navbar-nav > li').click(function(event) {
		event.preventDefault();
		var target = $(this).find('>a').prop('hash');
		$('html, body').animate({
			scrollTop: $(target).offset().top
		}, 500);
	});

	//scrollspy
	$('[data-spy="scroll"]').each(function () {
		var $spy = $(this).scrollspy('refresh')
	})

	//PrettyPhoto
	$("a.preview").prettyPhoto({
		social_tools: false
	});

	//Isotope
	$(window).load(function(){
		$portfolio = $('.portfolio-items');
		$portfolio.isotope({
			itemSelector : 'li',
			layoutMode : 'fitRows'
		});
		$portfolio_selectors = $('.portfolio-filter >li>a');
		$portfolio_selectors.on('click', function(){
			$portfolio_selectors.removeClass('active');
			$(this).addClass('active');
			var selector = $(this).attr('data-filter');
			$portfolio.isotope({ filter: selector });
			return false;
		});
	});
});