<?php
	include __DIR__.'/../../functions/security.php'; 
	@session_start();

	if(!empty($_POST)){
		$pagina = $_POST['searchGrafica'][0]['pagina'];
		$numero_mostrar = 11;
		$inicio = ($pagina * $numero_mostrar) - $numero_mostrar;
		$final = ($pagina * $numero_mostrar) - 1;

		$row = 0;
		foreach ($_SESSION['reporte_Sistema']['data'] as $id_seccion_ine => $datos) {
			//? identificadores
			$id_seccion_ine;
			$primera_fuerza = $datos['orden_votos_individual']['primera_fuerza'];
			if($primera_fuerza !='NoData'){
				$id_partido_primera_fuerza = $datos['partidos'][$primera_fuerza]['id'];
			}else{
				$id_partido_primera_fuerza = 0;
			}

			$semaforo = $datos['orden_votos_individual']['semaforo']['color'];
			$tipo = $datos['tipo'];
			$id_municipio = $datos['id_municipio'];

			$show = true;

			if($_POST['searchGrafica'][0]['tipo_seccion']!=''){
				$search_tipo_seccion = explode(',', $_POST['searchGrafica'][0]['tipo_seccion']);
				if( in_array($tipo, $search_tipo_seccion) == false ){
					$show = false;
				}
			}
			if($_POST['searchGrafica'][0]['id_seccion_ine']!=''){
				$search_id_seccion_ine = explode(',', $_POST['searchGrafica'][0]['id_seccion_ine']);
				if( in_array($id_seccion_ine, $search_id_seccion_ine) == false ){
					$show = false;
				}
			}

			if($_POST['searchGrafica'][0]['partido_ganador_id']!=''){
				$search_partido_ganador_id = explode(',', $_POST['searchGrafica'][0]['partido_ganador_id']);
				if( in_array($id_partido_primera_fuerza, $search_partido_ganador_id) == false ){
					$show = false;
				}
			}

			if($_POST['searchGrafica'][0]['id_municipio']!=''){
				$search_id_municipio = explode(',', $_POST['searchGrafica'][0]['id_municipio']);
				if( in_array($id_municipio, $search_id_municipio) == false ){
					$show = false;
				}
			}

			if($_POST['searchGrafica'][0]['semaforo']!=''){
				$search_semaforo = explode(',', $_POST['searchGrafica'][0]['semaforo']);
				if( in_array($semaforo, $search_semaforo) == false ){
					$show = false;
				}
			}

			if($show){
				if(($row >= $inicio) && ($row <= $final)){
					//['orden_votos_individual']['semaforo']
					$seccion = $datos['numero'];
					$diferencia = $datos['orden_votos_individual']['semaforo']['diferencia'];

					$id_seccion_ine;
					$primera_fuerza = $datos['orden_votos_individual']['primera_fuerza'];
					if($primera_fuerza !='NoData'){
						$id_partido_primera_fuerza = $datos['paridos'][$primera_fuerza]['id'];
					}else{
						$id_partido_primera_fuerza = 0;
					}

					$semaforo = $datos['orden_votos_individual']['semaforo']['color'];
					$tipo = $datos['tipo'];
					$id_municipio = $datos['id_municipio'];

					//fillColor
					//strokeColor

					if($semaforo=='rojo'){
						$fillColor = '#FF0000';
					}elseif ($semaforo=='amarillo') {
						$fillColor = '#ffff00';
					}elseif ($semaforo=='gris') {
						$fillColor = '#808080';
					}elseif ($semaforo=='verde') {
						$fillColor = '#008000';
					}else{
						$fillColor = '#000000';
					}

					$grafica_diferencia[] = array('x' => $seccion, 'y' => $diferencia, 'fillColor' => $fillColor, 'strokeColor' => $fillColor);
					$grafica_colores[] = $semaforo;

					$grafica_programas_inversion[] = array('x' => $seccion, 'y' => $datos['acciones_obras']);
					$grafica_programas_gobierno[] = array('x' => $seccion, 'y' => $datos['apoyos_programas']);
					
					$grafica_ciudadanos_registrados[] = array('x' => $seccion, 'y' => $datos['ciudadanos_registrados']);
					$grafica_funcionarios[] = array('x' => $seccion, 'y' => $datos['funcionarios']);
					$grafica_militantes[] = array('x' => $seccion, 'y' => $datos['militantes']);
					$grafica_grupos_interes[] = array('x' => $seccion, 'y' => $datos['grupos_interes']);
				}
				$row = $row + 1;
			}
		}

	}else{
		$pagina = 1;
		$numero_mostrar = 11;
		$inicio = ($pagina * $numero_mostrar) - $numero_mostrar;
		$final = ($pagina * $numero_mostrar) - 1;
		/*
		$total = count($_SESSION['reporte_Sistema']['data']);
		echo $total;
		echo "<br>";
		$resultado = explode(',', $total / $numero_mostrar);
		echo $resultado[0];
		echo "<br>";
		echo $total  % $numero_mostrar;
		*/
		$row = 0;
		foreach ($_SESSION['reporte_Sistema']['data'] as $id_seccion_ine => $datos) {

			if(($row >= $inicio) && ($row <= $final)){
				//['orden_votos_individual']['semaforo']
				$seccion = $datos['numero'];
				$diferencia = $datos['orden_votos_individual']['semaforo']['diferencia'];

				$id_seccion_ine;
				$primera_fuerza = $datos['orden_votos_individual']['primera_fuerza'];
				if($primera_fuerza !='NoData'){
					$id_partido_primera_fuerza = $datos['paridos'][$primera_fuerza]['id'];
				}else{
					$id_partido_primera_fuerza = 0;
				}

				$semaforo = $datos['orden_votos_individual']['semaforo']['color'];
				$tipo = $datos['tipo'];
				$id_municipio = $datos['id_municipio'];

				//fillColor
				//strokeColor

				if($semaforo=='rojo'){
					$fillColor = '#FF0000';
				}elseif ($semaforo=='amarillo') {
					$fillColor = '#ffff00';
				}elseif ($semaforo=='gris') {
					$fillColor = '#808080';
				}elseif ($semaforo=='verde') {
					$fillColor = '#008000';
				}else{
					$fillColor = '#000000';
				}

				$grafica_diferencia[] = array('x' => $seccion, 'y' => $diferencia, 'fillColor' => $fillColor, 'strokeColor' => $fillColor);
				$grafica_colores[] = $semaforo;

				$grafica_programas_inversion[] = array('x' => $seccion, 'y' => $datos['acciones_obras']);
				$grafica_programas_gobierno[] = array('x' => $seccion, 'y' => $datos['apoyos_programas']);
				
				$grafica_ciudadanos_registrados[] = array('x' => $seccion, 'y' => $datos['ciudadanos_registrados']);
				$grafica_funcionarios[] = array('x' => $seccion, 'y' => $datos['funcionarios']);
				$grafica_militantes[] = array('x' => $seccion, 'y' => $datos['militantes']);
				$grafica_grupos_interes[] = array('x' => $seccion, 'y' => $datos['grupos_interes']);

			}
			$row = $row + 1;
		}
	}

	///Programas y value mayor
	$valor_mayor = 0;
	foreach ($grafica_ciudadanos_registrados as $key => $value) {
		$caso1 = $value['y'];
		$caso2 = $grafica_funcionarios[$key]['y'];
		$caso3 = $grafica_militantes[$key]['y'];
		if(($caso1 > $caso2) and ($caso1 > $caso3)){
			//mayor caso 1
			$valor = $caso1;
		}
		if(($caso2 > $caso1) and ($caso2 > $caso3)){
			//mayor caso 2
			$valor = $caso2;
		}
		if(($caso3 > $caso1) and ($caso3 > $caso2)){
			//mayor caso 2
			$valor = $caso3;
		}
		if($valor > $valor_mayor ){
			$valor_mayor = $valor;
		}
	}
	$y_graphProgramas = number_format(($valor_mayor * 0.10) + $valor_mayor ,0,'.','' );


	///Ciudadanos y value mayor
	$valor_mayor = 0;
	foreach ($grafica_ciudadanos_registrados as $key => $value) {

		if($value['y'] > ($grafica_funcionarios[$key]['y'] + $grafica_militantes[$key]['y']) ){
			$valor = $value['y'];
		}else{
			$valor = $grafica_funcionarios[$key]['y'] + $grafica_militantes[$key]['y'];
		}
		if($valor > $valor_mayor ){
			$valor_mayor = $valor;
		}
		/*
		$y = $value['y'] + $grafica_funcionarios[$key]['y'] + $grafica_militantes[$key]['y'];
		if($y > $valor_mayor ){
			$valor_mayor = $y;
		}
		*/
	}
	$y_graphCiudadanos = number_format(($valor_mayor * 0.10) + $valor_mayor ,0,'.','' );
	//$y_graphCiudadanos = $valor_mayor;


	// botones
	$decimales = $row % $numero_mostrar;
	if($decimales > 0){
		$extra = 1;
	}else{
		$extra = 0;
	}
	?>

	<style type="text/css">
		.dotGreen {
			height: 30px;
			width: 30px;
			background-color: green;
			border-radius: 50%;
			display: inline-block;
		}
		.dotYellow {
			height: 30px;
			width: 30px;
			background-color: yellow;
			border-radius: 50%;
			display: inline-block;
		}
		.dotRed {
			height: 30px;
			width: 30px;
			background-color: red;
			border-radius: 50%;
			display: inline-block;
		}
		.dotGray {
			height: 30px;
			width: 30px;
			background-color: gray;
			border-radius: 50%;
			display: inline-block;
		}
	</style>
	<div style="width: 100%;text-align: center;">
		<center>
			<table style="table-layout: fixed;border-color: white;" cellspacing="0" cellpadding="0" border="1">
				<thead>
					<tr>
						<tr>
							<td
								style="text-align: center;padding: 10px;background-color: #a0a0a0;color: white;font-weight: bold;"
								colspan="8">Semáforo</td>
						</tr>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<span class="dotRed"></span>
						</td>
						<td
							style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								<i class="fas fa-less-than-equal"></i>49
							</font>
						</td>

						<td style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<span class="dotYellow"></span>
						</td>
						<td
							style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								<i class="fas fa-greater-than-equal"></i> 50 & <i class="fas fa-less-than-equal"></i>99 
							</font>
						</td>

						<td style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<span class="dotGreen"></span>
						</td>
						<td
							style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								<i class="fas fa-greater-than-equal"></i>
								100
							</font>
						</td>

						<td style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<span class="dotGray"></span>
						</td>
						<td
							style="text-align: center; padding: 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								No Data
							</font>
						</td>
					</tr>
				</tbody>
			</table>
		</center>
	</div>
	<div style="width: 100%">
		<?php
		$botones = intdiv($row , $numero_mostrar) + $extra;
		for ($i=1; $i <= $botones; $i++) { 

			if($i == $pagina){
				$class_btn = 'primary';
			}else{
				$class_btn = 'info';
			}

			?>
			<button class="btn btn-<?= $class_btn ?> bt_responsive" onclick="searchGrafica(<?= $i ?>)" ><?= $i ?></button>
			<?php
		}
		?>
	</div>
	<script type="text/javascript">
		function searchGrafica(pag) {
			document.getElementById("pagina_valor").value=pag;
			setTimeout(searchTable('pagina'),3000);
		}
	</script>
	<input type="hidden" id="pagina_valor" value="<?= $pagina ?>">

	<style type="text/css">
		.graphLeft{
			width: 50%;
			padding: 2px;
			float: left;
		}
		.graphRight{
			width: 50%;
			padding: 2px;
			float: right;
		}
		.graphCenter{
			width: 100%;
			padding: 2px;
			float: right;
		}
		@media only screen and (max-width: 1200px) and (min-width: 980px) {
			.graphLeft{
				width: 50%;
				padding: 2px;
				float: left;
			}
			.graphRight{
				width: 50%;
				padding: 2px;
				float: right;
			}
			.graphCenter{
				width: 100%;
				padding: 2px;
				float: right;
			}
		}
		@media only screen and (max-width: 980px) and (min-width: 761px) {
			.graphLeft{
				width: 100%;
				padding: 2px;
				float: left;
			}
			.graphRight{
				width: 100%;
				padding: 2px;
				float: right;
			}
			.graphCenter{
				width: 100%;
				padding: 2px;
				float: right;
			}
		}

		@media only screen and (max-width: 760px) and (min-width: 600px) {
			.graphLeft{
				width: 100%;
				padding: 2px;
				float: left;
			}
			.graphRight{
				width: 100%;
				padding: 2px;
				float: right;
			}
			.graphCenter{
				width: 100%;
				padding: 2px;
				float: right;
			}
		}
		@media only screen and (max-width: 620px) and (min-width: 6px) {
			.graphLeft{
				width: 100%;
				padding: 2px;
				float: left;
			}
			.graphRight{
				width: 100%;
				padding: 2px;
				float: right;
			}
			.graphCenter{
				width: 100%;
				padding: 2px;
				float: right;
			}
		}
	</style>

	<div class="graphLeft" id="appTerritorio">
		<div id="chart" style="width:100%">
			<apexchart type="line" height="400" width="100%" :options="chartOptionsCiudadanosTerritorio" :series="series"></apexchart>
		</div>
	</div>
	<div class="graphRight" id="appProgramas">
		<div id="chart" style="width:100%">
			<apexchart type="line" height="400" width="100%" :options="chartOptionsCiudadanosProgramas" :series="series"></apexchart>
		</div>
	</div>
	<div class="graphCenter" id="appCiudadanos">
		<div id="chart" style="width:100%">
			<apexchart type="bar" height="400"  width="100%" :options="chartOptionsCiudadanos" :series="series"></apexchart>
		</div>
	</div>
	<script>
		new Vue({
			el: '#appTerritorio',
			components: {
				apexchart: VueApexCharts
			},
			barOptions: {
				spaceRatio: 0.25
			},
			data: {
				series: [
					{
						name: 'Diferencia Votos',
						type: 'bar',
						labels: <?= json_encode($grafica_colores); ?>,
						data: <?= json_encode($grafica_diferencia); ?>,
					}, 
				],
				chartOptionsCiudadanosTerritorio: {
					chart: {
						locales: [{
						  "name": "es",
						  "options": {
							"toolbar": {
								"exportToSVG": "Descarga SVG",
								"exportToPNG": "Descarga PNG",
								"exportToCSV": "Descarga CSV",
								"menu": "Menu",
								"selection": "Selección",
								"selectionZoom": "Selección Zoom",
								"zoomIn": "Zoom In",
								"zoomOut": "Zoom Out",
								"pan": "Arrastrar",
								"reset": "Reset Zoom"
							}
						  }
						}],
						defaultLocale: "es",
						height: 350,
						type: 'bar',
						animations: {
							enabled: true,
							easing: 'easeinout',
							speed: 800,
							animateGradually: {
								enabled: true,
								delay: 150
							},
							dynamicAnimation: {
								enabled: true,
								speed: 350
							}
						},
						toolbar: {
							show: false,
							offsetX: 0,
							offsetY: 0,
							tools: {
								download: false,
								selection: true,
								zoom: true,
								zoomin: true,
								zoomout: true,
								pan: true,
								reset:  true | '<img src="reset.png" width="20">',
								customIcons: []
							},
							export: {
							csv: {
								filename: 'analisis_territorio',
								columnDelimiter: ',',
								headerCategory: 'Secciones',
								headerValue: 'value',
								dateFormatter(timestamp) {
									return new Date(timestamp).toDateString()
								}
							},
							svg: {
								title:'alex',
								filename: 'analisis_territorio',
							},
							png: {
								filename: 'analisis_territorio',
							}
							},
							autoSelected: 'zoom' 
						},
					},
					plotOptions: {
						bar: {
							horizontal: false,
							columnWidth: '80%',
							borderRadius: 10,
						}
					},
					colors: ['#3B5998',],
					stroke: {
						width: [0],
						curve: ['straight']
					},
					title: {
						text: 'Análisis de territorio',
						align: 'center',
					},
					dataLabels: {
						enabled: true,
						enabledOnSeries: [0],
						style: {
							//colors: []
						},
					},
					legend: {
						show: true,
					},
					fill: {
						
					},
					yaxis: [
						{
							show: true,
							title: {
								text: 'Diferencia Votos',
								style: {
									fontWeight: 900,
									color: '#3B5998'
								}
							},
							labels: {
								formatter: function (val) {
									return val.toLocaleString()
								}
							},
							tooltip: {
								enabled: false,
								offsetX: 0,
							},
						}, 
					],
					xaxis: {
						labels: {
							formatter: function (value) {
								return parseFloat(value).toLocaleString();
							}
						},
						title: {
							text: 'Secciones',
							style: {
								color: '#ff0000',
								fontSize: '12px',
								fontFamily: 'Helvetica, Arial, sans-serif',
								fontWeight: 600,
								cssClass: 'apexcharts-xaxis-title',
							},
						},
						tooltip: {
							enabled: false,
							offsetX: 0,
						},
					},
					tooltip: {
						enabled: true,
						shared: true,
						followCursor: true,
						intersect: false,
						inverseOrder: false,
						custom: undefined,
						fillSeriesColor: false,
						onDatasetHover: {
							highlightDataSeries: false,
						}, 
						x: {
							formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
								seccion = w.config.series[seriesIndex].data[dataPointIndex].x ;
								color = w.config.series[seriesIndex].labels[dataPointIndex];
								console.log(color);

								if(color=='rojo'){
									bg_color = 'rgba(255,0,0,0.6)';
									bg_color = '#FF6961';
								}else if(color=='amarillo'){
									bg_color = '#ffff00';
								}else if(color=='gris'){
									bg_color = '#9b9b9b';
								}else if(color=='verde'){
									bg_color = '#77dd77';
								}else{
									bg_color = '#000000';
								}
								div = '<table><tr><td style="opacity: 0.97;background-color:'+bg_color+'">                </td><td>   Sección : ' + parseFloat(seccion).toLocaleString() +'</td></tr></table>';
								//<div style="display:table"><div style="background-color:red; width:10px"> </div><div>'+'Sección : ' + parseFloat(seccion).toLocaleString()+'</div></div>
								return div;
							}
						},
						marker: {
							show: true,
						},
						fixed: {
							enabled: true,
							position: 'topRight',
							offsetX: 0,
							offsetY: 0,
						},
						
					},
					fill: {
						opacity: ['0.7'],
						type: ['straight'],
						pattern: {
							style: "verticalLines",
							
						}
					},
					markers: {
						size: 5,
						hover: {
							size: 9
						}
					},
					stroke: {
						show: true,
						curve: 'straight',
						lineCap: 'butt',
						colors: ['#34495E','#1A5276'],
						width: 2,
						dashArray: 1,      
					},
					grid: {
						show: true,
						borderColor: '#90A4AE',
						strokeDashArray: 0,
						position: 'back',
						xaxis: {
							lines: {
								show: false
							}
						},
						row: {
							colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
							opacity: 0.5
						},
						yaxis: {
							lines: {
								show: true
							}
						},
						column: {
							colors: undefined,
							opacity: 0.5
						},
						padding: {
							top: 0,
							right: 0,
							bottom: 0,
							left: 0
						}, 
					},
				},
			}
		})
		new Vue({
			el: '#appProgramas',
			components: {
				apexchart: VueApexCharts
			},
			barOptions: {
				spaceRatio: 0.25
			},
			data: {
				series: [
					{
						name: 'Ciudadanos Registrados',
						type: 'bar',
						labels: <?= json_encode($grafica_colores); ?>,
						data: <?= json_encode($grafica_ciudadanos_registrados); ?>,
					}, 
					{
						name: 'Programas Inversion ',
						type: 'line',
						labels: <?= json_encode($grafica_colores); ?>,
						data: <?= json_encode($grafica_programas_inversion); ?>,
					}, 
					{
						name: 'Programas de Gobierno ',
						type: 'line',
						labels: <?= json_encode($grafica_colores); ?>,
						data: <?= json_encode($grafica_programas_gobierno); ?>,
					}, 
				],
				chartOptionsCiudadanosProgramas: {
					theme: {
						mode: 'light', 
						palette: 'palette1', 
						monochrome: {
							enabled: false,
							color: '#255aee',
							shadeTo: 'light',
							shadeIntensity: 0.65
						},
					},
					chart: {
						locales: [{
						  "name": "es",
						  "options": {
							"toolbar": {
								"exportToSVG": "Descarga SVG",
								"exportToPNG": "Descarga PNG",
								"exportToCSV": "Descarga CSV",
								"menu": "Menu",
								"selection": "Selección",
								"selectionZoom": "Selección Zoom",
								"zoomIn": "Zoom In",
								"zoomOut": "Zoom Out",
								"pan": "Arrastrar",
								"reset": "Reset Zoom"
							}
						  }
						}],
						defaultLocale: "es",
						height: 350,
						type: 'bar',
						stacked: true,
						animations: {
							enabled: true,
							easing: 'easeinout',
							speed: 800,
							animateGradually: {
								enabled: true,
								delay: 150
							},
							dynamicAnimation: {
								enabled: true,
								speed: 350
							}
						},
						toolbar: {
							show: false,
							offsetX: 0,
							offsetY: 0,
							tools: {
								download: false,
								selection: true,
								zoom: true,
								zoomin: true,
								zoomout: true,
								pan: true,
								reset:  true | '<img src="reset.png" width="20">',
								customIcons: []
							},
							export: {
							csv: {
								filename: 'analisis_territorio',
								columnDelimiter: ',',
								headerCategory: 'Secciones',
								headerValue: 'value',
								dateFormatter(timestamp) {
									return new Date(timestamp).toDateString()
								}
							},
							svg: {
								title:'alex',
								filename: 'analisis_territorio',
							},
							png: {
								filename: 'analisis_territorio',
							}
							},
							autoSelected: 'zoom' 
						},
					},
					plotOptions: {
						bar: {
							horizontal: false,
							columnWidth: '80%',
							borderRadius: 10,
						}
					},
					 
					 
					title: {
						text: 'Programas y Ciudadanos registrados',
						align: 'center',
					},
					dataLabels: {
						enabled: true,
						enabledOnSeries: [0,1,2],
						style: {
							//colors: ['#000','#00C4CC','#25d366']
						},
					},
					legend: {
						show: true,
						showForSingleSeries: false,
						showForNullSeries: true,
						showForZeroSeries: true,
						position: 'top',
						horizontalAlign: 'left', 
						floating: false,
						fontSize: '10px',
						fontFamily: 'Helvetica, Arial',
						fontWeight: 400,
						formatter: undefined,
						inverseOrder: false,
						width: undefined,
						height: undefined,
						tooltipHoverFormatter: undefined,
						customLegendItems: [],
						offsetX: 0,
						offsetY: 0,
						labels: {
								colors: undefined,
								useSeriesColors: false
						},
						markers: {
								width: 12,
								height: 12,
								strokeWidth: 0,
								strokeColor: '#fff',
								fillColors: undefined,
								radius: 12,
								customHTML: undefined,
								onClick: undefined,
								offsetX: 0,
								offsetY: 0
						},
						itemMargin: {
								horizontal: 5,
								vertical: 0
						},
						onItemClick: {
								toggleDataSeries: false
						},
						onItemHover: {
								highlightDataSeries: true
						},
					},
					yaxis: [
						{
							show: true,
							logBase: 10,
							allowDecimals: true,
							max: <?= $y_graphProgramas ?>,
							title: {
								text: 'Ciudadanos Registrados',
								style: {
									fontWeight: 900,
									color: '#3B5998'
								}
							},
							labels: {
								formatter: function (val) {
									return Math.floor(val)
								}
							},
							tooltip: {
								enabled: false,
								offsetX: 0,
							},
							//min: 0,
							//max: 22,
							//tickAmount: 1,
						},
						{
							show: false,
							logBase: 10,
							allowDecimals: true,
							max: <?= $y_graphProgramas ?>,
							opposite: true,
							title: {
								text: 'Programas de Inversion',
								style: {
									fontWeight: 900,
									color: '#00C4CC'
								}
							},
							labels: {
								formatter: function (val) {
									return Math.floor(val)
								}
							}

						}, 
						{
							show: false,
							logBase: 10,
							allowDecimals: true,
							max: <?= $y_graphProgramas ?>,
							title: {
								text: 'Programas de Gobierno',
								style: {
									fontWeight: 900,
									color: '#25d366'
								}
							},
							labels: {
								formatter: function (val) {
									return Math.floor(val)
								}
							}
						},
					],
					xaxis: {
						labels: {
							formatter: function (value) {
								return parseFloat(value).toLocaleString();
							}
						},
						title: {
							text: 'Secciones',
							style: {
								color: '#ff0000',
								fontSize: '12px',
								fontFamily: 'Helvetica, Arial, sans-serif',
								fontWeight: 600,
								cssClass: 'apexcharts-xaxis-title',
							},
						},
						tooltip: {
							enabled: false,
							offsetX: 0,
						},
					},
					tooltip: {
						enabled: true,
						shared: true,
						followCursor: true,
						intersect: false,
						inverseOrder: false, 
						fillSeriesColor: false,
						x: {
							formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
								seccion = w.config.series[seriesIndex].data[dataPointIndex].x ;
								color = w.config.series[seriesIndex].labels[dataPointIndex];
								console.log(color);

								if(color=='rojo'){
									bg_color = 'rgba(255,0,0,0.6)';
									bg_color = '#FF6961';
								}else if(color=='amarillo'){
									bg_color = '#ffff00';
								}else if(color=='gris'){
									bg_color = '#9b9b9b';
								}else if(color=='verde'){
									bg_color = '#77dd77';
								}else{
									bg_color = '#000000';
								}
								div = '<table><tr><td style="opacity: 0.97;background-color:'+bg_color+'">                </td><td>   Sección : ' + parseFloat(seccion).toLocaleString() +'</td></tr></table>';
								//<div style="display:table"><div style="background-color:red; width:10px"> </div><div>'+'Sección : ' + parseFloat(seccion).toLocaleString()+'</div></div>
								return div;
							}
						}, 
						fixed: {
							enabled: true,
							position: 'topRight',
							offsetX: 0,
							offsetY: 0,
						},
						
					},
					fill: {
						opacity: ['0.7','0.9','0.9'],
						type: ['straight','straight','straight'],
						pattern: {
							style: "circles",
							
						}
					},
					markers: {
						size: 2,
						hover: {
							size: 9
						}
					},
					stroke: {
						show: true,
						curve: ['smooth', 'straight', 'straight'],
						lineCap: 'butt',
						//colors: ['#34495E','#1A5276'],
						width: ['1','3','3'],
						dashArray: 0,      
					},
					grid: {
						show: true,
						borderColor: '#90A4AE',
						strokeDashArray: 0,
						position: 'back',
						xaxis: {
							lines: {
								show: false
							}
						},
						row: {
							colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
							opacity: 0.5
						},
						column: {
							colors: undefined,
							opacity: 0.5
						},
						padding: {
							top: 0,
							right: 0,
							bottom: 0,
							left: 0
						}, 
					},
				},
			}
		}) 
		new Vue({
			el: '#appCiudadanos',
			components: {
				apexchart: VueApexCharts
			},
			barOptions: {
				spaceRatio: 0.25
			},
			data: {
				series: [
					{
						name: 'Ciudadanos Registrados',
						type: 'area',
						labels: <?= json_encode($grafica_colores); ?>,
						data: <?= json_encode($grafica_ciudadanos_registrados); ?>,
					}, 
					{
						name: 'Funcionario',
						type: 'bar',
						labels: <?= json_encode($grafica_colores); ?>,
						data: <?= json_encode($grafica_programas_gobierno); ?>,
					}, 
					{
						name: 'Militantes ',
						type: 'bar',
						labels: <?= json_encode($grafica_colores); ?>,
						data: <?= json_encode($grafica_militantes); ?>,
					}, 
				],
				chartOptionsCiudadanos: {
					theme: {
						mode: 'light', 
						palette: 'palette1', 
						monochrome: {
							enabled: false,
							color: '#255aee',
							shadeTo: 'light',
							shadeIntensity: 0.65
						},
					},

					chart: {
						locales: [{
						  "name": "es",
						  "options": {
							"toolbar": {
								"exportToSVG": "Descarga SVG",
								"exportToPNG": "Descarga PNG",
								"exportToCSV": "Descarga CSV",
								"menu": "Menu",
								"selection": "Selección",
								"selectionZoom": "Selección Zoom",
								"zoomIn": "Zoom In",
								"zoomOut": "Zoom Out",
								"pan": "Arrastrar",
								"reset": "Reset Zoom"
							}
						  }
						}],

						defaultLocale: "es",
						height: 350,
						type: 'bar',
						stacked: true,
						animations: {
							enabled: true,
							easing: 'easeinout',
							speed: 800,
							animateGradually: {
								enabled: true,
								delay: 150
							},
							dynamicAnimation: {
								enabled: true,
								speed: 350
							}
						},
						toolbar: {
							show: false,
							offsetX: 0,
							offsetY: 0,
							tools: {
								download: false,
								selection: true,
								zoom: true,
								zoomin: true,
								zoomout: true,
								pan: true,
								reset:  true | '<img src="reset.png" width="20">',
								customIcons: []
							},
							export: {
							csv: {
								filename: 'analisis_territorio',
								columnDelimiter: ',',
								headerCategory: 'Secciones',
								headerValue: 'value',
								dateFormatter(timestamp) {
									return new Date(timestamp).toDateString()
								}
							},
							svg: {
								title:'alex',
								filename: 'analisis_territorio',
							},
							png: {
								filename: 'analisis_territorio',
							}
							},
							autoSelected: 'zoom' 
						},
					},
					plotOptions: {
						bar: {
							horizontal: false,
							columnWidth: '80%',
							borderRadius: 10,
						},
						area: {
					        fillTo: 'end'
					    }
					},

					title: {
						text: 'Ciudadanos',
						align: 'center',
					},
					dataLabels: {
						enabled: true,
						enabledOnSeries: [0,1,2],
						style: {
							colors: ['#008FFB','#101084','#101084']
						},
						textAnchor: 'end',
						background: {
							enabled: true,
							foreColor: '#fff',
							padding: 4,
							borderRadius: 2,
							borderWidth: 1,
							borderColor: '#fff',
							opacity: 0.9,
							dropShadow: {
							  enabled: false,
							  top: 1,
							  left: 1,
							  blur: 1,
							  color: '#000',
							  opacity: 0.45
							}
						  },
						dropShadow: {
							  enabled: false,
							  top: 1,
							  left: 1,
							  blur: 1,
							  color: '#000',
							  opacity: 0.45
						}
					},
					legend: {
						show: true,
						showForSingleSeries: true,
						showForNullSeries: true,
						showForZeroSeries: true,
						position: 'bottom',
						horizontalAlign: 'center', 
						floating: false,
						fontSize: '10px',
						fontFamily: 'Helvetica, Arial',
						fontWeight: 400,
						formatter: undefined,
						inverseOrder: false,
						width: undefined,
						height: 29,
						tooltipHoverFormatter: undefined,
						customLegendItems: [],
						offsetX: 0,
						offsetY: 0,
						labels: {
								colors: undefined,
								useSeriesColors: false
						},
						markers: {
								width: 12,
								height: 12,
								strokeWidth: 0,
								strokeColor: '#fff',
								fillColors: undefined,
								radius: 12,
								customHTML: undefined,
								onClick: undefined,
								offsetX: 0,
								offsetY: 0
						},
						itemMargin: {
								horizontal: 5,
								vertical: 0
						},
						onItemClick: {
								toggleDataSeries: false
						},
						onItemHover: {
								highlightDataSeries: true
						},
					},
					yaxis: [
						{
							show: true,
							max: <?= $y_graphCiudadanos ?>,
							title: {
								text: 'Ciudadanos Registrados',
								style: {
									fontWeight: 900,
									color: '#3B5998'
								}
							},
							 
							 
							//min: 0,
							//max: 22,
							//tickAmount: 1,
						},
					],
					xaxis: {
						labels: {
							formatter: function (value) {
								return parseFloat(value).toLocaleString();
							}
						},
						title: {
							text: 'Secciones',
							style: {
								color: '#ff0000',
								fontSize: '12px',
								fontFamily: 'Helvetica, Arial, sans-serif',
								fontWeight: 600,
								cssClass: 'apexcharts-xaxis-title',
							},
						},
						tooltip: {
							enabled: false,
							offsetX: 0,
						},
					},
					tooltip: {
						enabled: true,
						shared: true,
						followCursor: true,
						intersect: false,
						inverseOrder: false, 
						fillSeriesColor: false,
						x: {
							formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
								seccion = w.config.series[seriesIndex].data[dataPointIndex].x ;
								color = w.config.series[seriesIndex].labels[dataPointIndex];
								console.log(color);

								if(color=='rojo'){
									bg_color = 'rgba(255,0,0,0.6)';
									bg_color = '#FF6961';
								}else if(color=='amarillo'){
									bg_color = '#ffff00';
								}else if(color=='gris'){
									bg_color = '#9b9b9b';
								}else if(color=='verde'){
									bg_color = '#77dd77';
								}else{
									bg_color = '#000000';
								}
								div = '<table><tr><td style="opacity: 0.97;background-color:'+bg_color+'">                </td><td>   Sección : ' + parseFloat(seccion).toLocaleString() +'</td></tr></table>';
								//<div style="display:table"><div style="background-color:red; width:10px"> </div><div>'+'Sección : ' + parseFloat(seccion).toLocaleString()+'</div></div>
								return div;
							}
						}, 
						fixed: {
							enabled: true,
							position: 'topRight',
							offsetX: 0,
							offsetY: 0,
						},
						
					},
					fill: {
						type: ['gradient','solid','solid'],
						opacity: ['0.7','0.9','0.9'],
						gradient: {
						  shade: "dark",
						  type: "vertical",
						  shadeIntensity: 0,
						  opacityFrom: 0.9,
						  opacityTo: 0.5,
						  stops: [0, 50, 100],
						},
						tooltip: { enabled: false}
					},
					
					markers: {
						/*
						size: 2,
						hover: {
							size: 9
						}
						*/
						shape: "square",
						colors:'#3B5998',
						strokeColors: '#fff',
						strokeWidth: 3,
						hover: {
						  size: 22,
						  
						}
					},
					stroke: {
						show: true,
						curve: ['straight', 'straight', 'straight'],
						lineCap: 'butt',
						//colors: ['#34495E','#1A5276'],
						width: ['3','3','3'],
						dashArray: 0,      
					},
					grid: {
						show: true,
						borderColor: '#90A4AE',
						strokeDashArray: 0,
						position: 'back',
						xaxis: {
							lines: {
								show: false
							}
						},
						row: {
							colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
							opacity: 0.5
						},
						column: {
							colors: undefined,
							opacity: 0.5
						},
						padding: {
							top: 0,
							right: 0,
							bottom: 0,
							left: 0
						}, 
					},
				},
			}
		}) 
	</script>