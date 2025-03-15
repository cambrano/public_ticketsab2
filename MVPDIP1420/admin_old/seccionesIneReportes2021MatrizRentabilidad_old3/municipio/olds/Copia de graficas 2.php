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
	// botones
	$decimales = $row % $numero_mostrar;
	if($decimales > 0){
		$extra = 1;
	}else{
		$extra = 0;
	}
	$botones = intdiv($row , $numero_mostrar) + $extra;
	for ($i=1; $i <= $botones; $i++) { 
		?>
		<button class="btn btn-primary bt_responsive" onclick="searchGrafica(<?= $i ?>)" ><?= $i ?></button>
		<?php
	}
	?>
	<script type="text/javascript">
		function searchGrafica(pag) {
			document.getElementById("pagina_valor").value=pag;
			setTimeout(searchTable('pagina'),3000);
		}
	</script>
	<input type="hidden" id="pagina_valor" value="<?= $pagina ?>">
	<div id="appTerritorio">
		<div id="chart" style="width:100%">
			<apexchart type="line" height="400" width="100%" :options="chartOptionsTerritorio" :series="series"></apexchart>
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
						name: 'Diferencia de la segunda fuerza',
						type: 'bar',
						labels: <?= json_encode($grafica_colores); ?>,
						data: <?= json_encode($grafica_diferencia); ?>,
					}, 
					{
						name: 'Programas Inversion ',
						type: 'line',
						labels: <?= json_encode($grafica_colores); ?>,
						data: <?= json_encode($grafica_programas_inversion); ?>,
					}, 
					{
						name: 'Programas de Gobierno ',
						type: 'area',
						labels: <?= json_encode($grafica_colores); ?>,
						data: <?= json_encode($grafica_programas_gobierno); ?>,
					}, 
				],
				chartOptionsTerritorio: {
					chart: {
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
							show: true,
							offsetX: 0,
							offsetY: 0,
							tools: {
								download: false,
								selection: true,
								zoom: true,
								zoomin: true,
								zoomout: true,
								pan: false,
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
							columnWidth: '80%'
						}
					},
					colors: ['#3B5998', '#00C4CC', '#25d366',],
					stroke: {
						width: [0,1,2,3],
						curve: ['straight','straight','straight']
					},
					title: {
						text: 'Análisis de territorio'
					},
					dataLabels: {
						enabled: true,
						enabledOnSeries: [0],
						style: {
							colors: ['#000']
						},
					},
					fill: {
						
					},
					yaxis: [
						{
							show: false,
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
						{
							show: false,
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
									return val.toLocaleString()
								}
							}

						}, 
						{
							show: false,
							opposite: true,
							title: {
								text: 'Programas de Gobierno',
								style: {
									fontWeight: 900,
									color: '#25d366'
								}
							},
							labels: {
								formatter: function (val) {
									return val.toLocaleString();
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
						opacity: ['0.6','0.4','0.4'],
						type: ['solid','solid','solid'],
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
						row: {
							colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
							opacity: 0.5
						},
					},
				},
			}
		})
	</script>