<?php
	@session_start();
	$color1 = "#004c00";
	$color1_hover = "#4c814c";

	$color2 = "#36a2eb";
	$color2_hover = "#2571a4";
?>

	<div class="grafica_barras_horizontales">
		<canvas id="chartTotalesListaNominalCiudadanos"></canvas>
	</div>
	<script type="text/javascript">
		Chart.pluginService.register({
			beforeDraw: function (chart) {
				if (chart.config.options.elements.center) {
					//Get ctx from string
					var ctx = chart.chart.ctx;

					//Get options from the center object in options
					var centerConfig = chart.config.options.elements.center;
					var fontStyle = centerConfig.fontStyle || 'Arial';
					var txt = centerConfig.text;
					var color = centerConfig.color || '#000';
					var sidePadding = centerConfig.sidePadding || 20;
					var sidePaddingCalculated = (sidePadding / 100) * (chart.innerRadius * 2)
					//Start with a base font of 30px
					ctx.font = "10px " + fontStyle;

					//Get the width of the string and also the width of the element minus 10 to give it 5px side padding
					var stringWidth = ctx.measureText(txt).width;
					var elementWidth = (chart.innerRadius * 2) - sidePaddingCalculated;

					// Find out how much the font can grow in width.
					var widthRatio = elementWidth / stringWidth;
					var newFontSize = Math.floor(30 * widthRatio);
					var elementHeight = (chart.innerRadius * 2);

					// Pick a new font size so it will not be larger than the height of label.
					var fontSizeToUse = Math.min(newFontSize, elementHeight);

					//Set font settings to draw it correctly.
					ctx.textAlign = 'center';
					ctx.textBaseline = 'middle';
					var centerX = ((chart.chartArea.left + chart.chartArea.right) / 2);
					var centerY = ((chart.chartArea.top + chart.chartArea.bottom) / 2);
					ctx.font = fontSizeToUse + "px " + fontStyle;
					ctx.fillStyle = color;
					//Draw text in center
					ctx.fillText(txt, centerX, centerY);
				}
			}
		});


	var config = {
		type: 'doughnut',
		data: {
			labels: [
				"Votos Totales",
				"Lista Nominal",
			],
			datasets: [{
				data: [
					//300, 
					<?= number_format($votos_totales_2018,2,'.',''); ?>,
					<?= number_format($total_lista_abstencion_2018,2,'.',''); ?>,
				],
				dataX: [
					//300, 
					"<?=number_format($votos_totales_2018, 0, '.', ','); ?>",
					"<?=number_format($total_lista_abstencion_2018, 0, '.', ','); ?>",
				],
				backgroundColor: [
					"<?= $color1 ?>",
					"<?= $color2 ?>",
					//"#FFCE56"
				],
				hoverBackgroundColor: [
					"<?= $color1_hover ?>",
					"<?= $color2_hover ?>",
					//"#FFCE56"
				]
			}]
		},
		options: {
			responsive: true,
			legend: {
				display: false,
				responsive: true,
			},
			tooltips: {
				callbacks: {
					title: function(tooltipItem, data) {
						return data['labels'][tooltipItem[0]['index']];
					},
					label: function(tooltipItem, data) {
						return data['datasets'][0]['dataX'][tooltipItem['index']];
					},
					afterLabel: function(tooltipItem, data) {
						var dataset = data['datasets'][0];
						var percent = ((dataset['data'][tooltipItem['index']] / dataset["_meta"][4]['total']) * 100).toFixed(2);
						if (isNaN(percent)) percent = 0;
						return '(' + percent + '%)';
					}
				},
				backgroundColor: 'rgba(0, 0, 0, 0.8)',
				titleFontSize: 10,
				titleFontColor: '#fff',
				bodyFontColor: '#fff',
				bodyFontSize: 10,
				displayColors: true
			},
			elements: {
				center: {
					text: '<?= number_format( is_nan ($participacion_ciudadana) ? '0' : $participacion_ciudadana, 2, '.', ','); ?>%',
					color: '#000000', // Default is #000000
					fontStyle: 'Arial', // Default is Arial
					sidePadding: 75 // Defualt is 20 (as a percentage)
				}
			}
		}
	};

	var ctx = document.getElementById("chartTotalesListaNominalCiudadanos").getContext("2d");
	var chartTotalesListaNominalCiudadanos = new Chart(ctx, config); 
	</script>