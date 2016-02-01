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
						"yAxisName": "Sales (In USD)",
						"numberPrefix": "$",
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
							"label": "Bakersfield Central",
							"value": "880000"
						},
						{
							"label": "Garden Groove harbour",
							"value": "730000"
						},
						{
							"label": "Los Angeles Topanga",
							"value": "590000"
						},
						{
							"label": "Compton-Rancho Dom",
							"value": "520000"
						},
						{
							"label": "Daly City Serramonte",
							"value": "330000"
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