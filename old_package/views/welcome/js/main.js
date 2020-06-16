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
							"label": "codeigniter",
							"value": "1.0295"
						},
						{
							"label": "symfony",
							"value": "0.7184"
						},
						{
							"label": "slim",
							"value": "0.6372"
						},
						{
							"label": "webpackages",
							"value": "0.4475"
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
							"label": "codeigniter",
							"value": "3.1395"
						},
						{
							"label": "slim",
							"value": "2.3194"
						},
						{
							"label": "symfony",
							"value": "1.1947"
						},
						{
							"label": "webpackages",
							"value": "0.4284"
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

		var addTime =   new Date();

		loadTimeVar    =   loadTimeVar + (addTime.getTime() / 1000) - (currentTime.getTime() / 1000);

		$('#loadTime').html(loadTimeVar.toFixed(4));
	});
});