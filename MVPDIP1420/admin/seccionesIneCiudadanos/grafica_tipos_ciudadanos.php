<?php
	@session_start();
	$color1 = "#ff8300";
	$color1_hover = "#b25b00";

	$color2 = "#00923f";
	$color2_hover = "#00662c";
?>

	<center>
		<canvas id="chartTiposCiudadanos" height="60px" width="43px" style="min-width: 50px; max-width: 115px;"></canvas>
	</center>
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
				<?php
					foreach ($tipos_ciudadanos as $key => $value) {
						echo '"'.$value['nombre'].'",';
					}
				?>
			],
			datasets: [{
				data: [
					<?php
						foreach ($tipos_ciudadanos as $key => $value) {
							echo '"'.number_format($value['ciudadanos'],0,'.','').'",';
						}
					?> 
				],
				dataX: [
					<?php
						foreach ($tipos_ciudadanos as $key => $value) {
							$texto_tipos[]=$value['nombre'].' : '.number_format($value['ciudadanos'],0,'.',',');
							echo '"'.number_format($value['ciudadanos'],0,'.',',').'",';
						}
					?> 
				],
				backgroundColor: [
					<?php
						foreach ($tipos_ciudadanos as $key => $value) {
							echo '"'.random_color_hex().'",';
						}
					?> 
				],
				hoverBackgroundColor: [
					<?php
						foreach ($tipos_ciudadanos as $key => $value) {
							echo '"'.random_color_hex().'",';
						}
					?> 
				]
			}]
		},
		options: {
			responsive: true,
			rotation: 1 * Math.PI,
			circumference: 1 * Math.PI,
			responsive: true, 
			title: {
				display: true,
				text: 'Tipos Ciudadanos',
				position: 'bottom'
			},
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
						var percent = ((dataset['data'][tooltipItem['index']] / dataset["_meta"][2]['total']) * 100).toFixed(2);
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
			 
		}
	};

	//console.log(config);
	var ctx = document.getElementById("chartTiposCiudadanos").getContext("2d");
	var chartTiposCiudadanos = new Chart(ctx, config); 
	</script>
	 