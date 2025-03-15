<?php
	@session_start();
	include __DIR__."/../functions/security.php";
	include __DIR__."/../functions/files_size.php";
	//include "functions/paquetes_sistema.php";

	$filesizeBD=filesizeBD();
	$filesizeBD['file_size'];

	$filesizeArchivo=filesizeArchivo();
	$filesizeArchivo['file_size'];

	$filesizeData=filesizeData();

	//var_dump($filesizeData);

	//echo $filesizeBD['file_size'];
	//echo "-";
	//echo $filesizeArchivo['file_size'];

	/*
	$file_size = ($filesizeBD['file_size']+$filesizeArchivo['file_size'])/1024/1024;
	$file_size = $file_size;
	$file_size=str_replace(",", ".", $file_size);
	$file_size=$file_size;
	//$capacidad_sistema="5000";
	$sql=("SELECT megas  FROM configuracion_paquete WHERE 1 = 1  ");
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$capacidad_sistema=$row['megas'];


	//$capacidad_sistema= paquetesSistema("megas");
	//$file_size_restante=($capacidad_sistema-$file_size);
	$capacidad_sistema="5000000000";
	$file_size_restante=($capacidad_sistema-$filesizeBD['file_size']+$filesizeArchivo['file_size'])/1024/1024;
	$file_size_restante=str_replace(",", ".", $file_size_restante);
	$porcentaje=(100*$file_size)/$capacidad_sistema;
	
	$file_size = ($filesizeBD['file_size']+$filesizeArchivo['file_size']);
	$file_size = $file_size;
	$file_size=str_replace(",", ".", $file_size);
	$file_size=$file_size;
	$sql=("SELECT megas  FROM configuracion_paquete WHERE 1 = 1  ");
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$capacidad_sistema=$row['megas'];
	//$capacidad_sistema="5000000000";
	$file_size_restante=($capacidad_sistema-$filesizeBD['file_size']+$filesizeArchivo['file_size'])/1024/1024;
	$porcentaje=(100*$file_size)/$capacidad_sistema;
	$file_size = ($filesizeBD['file_size']+$filesizeArchivo['file_size'])/1024/1024;
	$capacidad_sistema=5000000000/1000000;
	*/

	$file_size=$filesizeData['file_size_sistema_file_mb'];
	$file_size_restante=$filesizeData['capacidad_sistema_file_restante_mb'];
	$capacidad_sistema=$filesizeData['capacidad_sistema_file_gb'];
	$porcentaje=$filesizeData['capacidad_sistema_file_porcentaje'];


	echo $filesizeData['size'];

	if($porcentaje<='70'){
		$colorCapcidad = "#36A2EB";
		$colorCapcidadDeg = "#7cc2f2";
		
		$porcentaje=number_format($porcentaje,0,'.','');
	}

	if($porcentaje>'70' && $porcentaje<'90'){
		$colorCapcidad = "#FFCE56";
		$colorCapcidadDeg = "#ffe4a3";
		$porcentaje=number_format($porcentaje,1,'.','');
	}

	if($porcentaje >='90'){
		$colorCapcidad = "#FF6384";
		$colorCapcidadDeg = "#ffb0c0";
		$porcentaje=number_format($porcentaje,2,'.','');
	}

	if($porcentaje >'100'){
		$colorCapcidad = "#FF6384";
		$colorCapcidadDeg = "#ffb0c0";
		$porcentaje=100;
		$porcentaje=number_format($porcentaje,0,'.','');
	}
	//78,3204088211

?>
	<style type="text/css">
		.grafica_size{
			height:10px;
		}
	</style>
	<div style="text-align: left;padding: 5px;font-size: 8px">
		Usado: <?= number_format($filesizeData['file_size_print'],2,'.',',') ?> <?= $filesizeData['file_size_tipo_print'] ?>
		<br>
		Restante: <?= number_format($filesizeData['file_size_restante_print'],4,'.',',') ?> <?= $filesizeData['file_size_restante_tipo_print'] ?>
		<br>
		Capacidad: <?= number_format($filesizeData['file_size_capacidad_print'],0,'.',',') ?> <?= $filesizeData['file_size_capacidad_tipo_print'] ?>
		<br>
		Usado: <?= $porcentaje ?>%
		<br><br>
	</div>
	<center>
		<canvas id="myChart" height="43px" width="43px" style="min-width: 50px; max-width: 115px;"></canvas>
		<br>
	</center>
	<script type = "text/javascript" >
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
				"Usado",
				"Libre",
			],
			datasets: [{
				data: [
					//300, 
					<?= number_format($file_size,2,'.',''); ?>,
					<?= number_format($file_size_restante,2,'.',''); ?>,
				],
				backgroundColor: [
					"<?= $colorCapcidad ?>",
					"#b4ecb4",
					//"#FFCE56"
				],
				hoverBackgroundColor: [
					"<?= $colorCapcidadDeg ?>",
					"#ddf6dd",
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
						return data['datasets'][0]['data'][tooltipItem['index']];
					},
					afterLabel: function(tooltipItem, data) {
						var dataset = data['datasets'][0];
						var percent = Math.round((dataset['data'][tooltipItem['index']] / dataset["_meta"][0]['total']) * 100)
						return '(' + percent + '%)';
					}
				},
					backgroundColor: '#FFF',
					titleFontSize: 9,
					titleFontColor: '#0066ff',
					bodyFontColor: '#000',
					bodyFontSize: 9,
					displayColors: true
			},
			elements: {
				center: {
					text: '<?= $porcentaje ?>%',
					color: '#FFF', // Default is #000000
					fontStyle: 'Arial', // Default is Arial
					sidePadding: 75 // Defualt is 20 (as a percentage)
				}
			}
		}
	};


	var ctx = document.getElementById("myChart").getContext("2d");
	var myChart = new Chart(ctx, config); 
	</script>