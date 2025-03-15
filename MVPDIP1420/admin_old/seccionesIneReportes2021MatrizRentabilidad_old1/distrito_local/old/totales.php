<?php
	//votos validos, votos_nulos, votos canreg
	include __DIR__."/../../functions/configuracion_matriz_rentabilidad_secciones_ine_2021.php";
	$configuracion_matriz_rentabilidad_secciones_ine_2021Datos = configuracion_matriz_rentabilidad_secciones_ine_2021Datos();
	$votos_semaforo_amarillo = $configuracion_matriz_rentabilidad_secciones_ine_2021Datos['votos_semaforo_amarillo'];
	$id_tipo_categoria_ciudadano = $configuracion_matriz_rentabilidad_secciones_ine_2021Datos['id_tipo_categoria_ciudadano'] ;// funcionario
	$id_partido_2021 = $configuracion_matriz_rentabilidad_secciones_ine_2021Datos['id_partido_2021_distrito_local'];// Partidos 2021 PRI
	//$id_partido_2021 = $configuracion_matriz['id_partido_2021'] = '1';// Partidos 2021
	$id_partido_legado = $configuracion_matriz['id_partido_legado'];// Partidos Legados
	$tipo = $configuracion_matriz['tipo_eleccion'] = '1';// 0 - Ayuntamiento | 1 - Distrito Local 



	$sql = "SELECT 
				t.votos_validos_2021,
				(SELECT SUM(votos_nulos) FROM casillas_votos_2021 WHERE id_distrito_local={$id_distrito_local} AND tipo = '{$tipo}' ) votos_nulos_2021,
				(SELECT SUM(votos_can_nreg) FROM casillas_votos_2021 WHERE id_distrito_local={$id_distrito_local} AND tipo = '{$tipo}' ) votos_can_nreg_2021,
				(SELECT COUNT(id) FROM secciones_ine WHERE id_distrito_local={$id_distrito_local} ) secciones,
				(SELECT COUNT(id) FROM casillas_votos_2021 WHERE id_distrito_local={$id_distrito_local} AND tipo = '{$tipo}' ) casillas_2021,
				(SELECT SUM(lista_nominal) FROM casillas_votos_2021 WHERE id_distrito_local={$id_distrito_local} AND tipo = '{$tipo}' ) total_lista_nominal_2021,
				(SELECT COUNT(id) FROM secciones_ine_ciudadanos WHERE id_distrito_local={$id_distrito_local} ) ciudadanos_totales,

				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sicpa.id_seccion_ine_ciudadano = sic.id WHERE sic.id_distrito_local={$id_distrito_local} ) apoyos_programas,
				(SELECT COUNT(*) FROM secciones_ine_actividades sia LEFT JOIN secciones_ine si ON sia.id_seccion_ine = si.id WHERE si.id_distrito_local={$id_distrito_local}  ) acciones_obras,
				(SELECT COUNT(*) FROM secciones_ine_grupos sig LEFT JOIN secciones_ine si ON sig.id_seccion_ine = si.id WHERE si.id_distrito_local={$id_distrito_local}  ) grupos_interes,
				(SELECT COUNT(*) FROM militantes_partidos mp LEFT JOIN secciones_ine_ciudadanos sic ON mp.id_seccion_ine_ciudadano = sic.id WHERE sic.id_distrito_local={$id_distrito_local}  AND mp.id_partido_legado = '{$id_partido_legado}') militantes,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_distrito_local={$id_distrito_local}  AND sicc.id_tipo_categoria_ciudadano = '{$id_tipo_categoria_ciudadano}') funcionarios
			FROM
				(SELECT SUM(votos) votos_validos_2021 FROM  casillas_votos_partidos_2021 WHERE id_distrito_local={$id_distrito_local} AND tipo =  '{$tipo}' )t
	";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$votos_nulos_2021 = $row['votos_nulos_2021'];
	$votos_can_nreg_2021 = $row['votos_can_nreg_2021'];
	$votos_validos_2021 = $row['votos_validos_2021'];
	$votos_totales_2021 = $votos_nulos_2021 + $votos_can_nreg_2021 + $votos_validos_2021;
	$total_lista_nominal_2021 = $row['total_lista_nominal_2021'];

	$apoyos_programas = $row['apoyos_programas'];
	$acciones_obras = $row['acciones_obras'];
	$grupos_interes = $row['grupos_interes'];
	$militantes = $row['militantes'];
	$funcionarios = $row['funcionarios'];


	$secciones = $row['secciones'];
	$casillas_2021 = $row['casillas_2021'];


	//aqui cambrano
	$ciudadanos_totales = $row['ciudadanos_totales'];
	$ciudadanos_totales_porcentaje = ($ciudadanos_totales/$total_lista_nominal_2021)*100;

	$ciudadanos_lista_nominal_porcentaje = ($ciudadanos_totales / $total_lista_nominal_2021) * 100;
	//$ciudadanos_lista_nominal_porcentaje = truncar($ciudadanos_lista_nominal_porcentaje,2);

	$total_lista_abstencion_2021 = $total_lista_nominal_2021 - $votos_totales_2021;
	if($total_lista_nominal_2021==0){
		$participacion_ciudadana=0;
		$abstencion_ciudadana=0;
	}else{
		$participacion_ciudadana = ($votos_totales_2021 / $total_lista_nominal_2021 )*100;
		$abstencion_ciudadana = ($total_lista_abstencion_2021 / $total_lista_nominal_2021 )*100;
	}

	$sql="
		SELECT
			p2021.id,
			p2021.clave,
			p2021.nombre_corto,
			p2021.nombre,
			p2021.logo,
			p2021.color_border,
			p2021.color_background,
			SUM(cvp2021.votos) votos_2021,
			p2021.clave_partidos_coaliciones,
			p2021.principal
		FROM partidos_2021 p2021
		LEFT JOIN casillas_votos_partidos_2021 cvp2021
		ON p2021.id = cvp2021.id_partido_2021
		#WHERE cvp2021.id_distrito_local={$id_distrito_local} AND cvp2021.tipo = 1 AND cvp2021.id_seccion_ine = '199'  223
		WHERE cvp2021.id_distrito_local={$id_distrito_local} AND cvp2021.tipo = '{$tipo}' #AND cvp2021.id_seccion_ine = '199'
		GROUP BY cvp2021.id_partido_2021 
	";
	$sql;
	$result = $conexion->query($sql); 
	$num=0; 
	while($row=$result->fetch_assoc()){
		#$datos_partidos[$num]=$row;
		if($row['clave_partidos_coaliciones'] != ''){
			$partidos_coaliciones[$row['nombre_corto']]=$row;
		}else{
			$partidos_sin_coaliciones[$row['nombre_corto']]=$row;
		} 
		$num=$num+1;
	}

	foreach ($partidos_sin_coaliciones as $clave => $array) {
		$sum_coaliciones = 0;
		unset($coaliciones);
		foreach ($partidos_coaliciones as $nombre_corto => $arraysc) {
			$pos = strpos($nombre_corto, $array['nombre_corto']);
			if ($pos !== false ) {
				$porciones = explode("_", $nombre_corto);
				foreach ($porciones as $key => $value) {
					$coaliciones[$value] = 0;
				}
				$sum_coaliciones = $sum_coaliciones + $arraysc['votos_2021'];
			}
		}
		$datos_partidos[$clave]['id'] = $array['id'];
		$datos_partidos[$clave]['clave'] = $clave;
		$datos_partidos[$clave]['nombre_corto'] = $array['nombre_corto'];
		$datos_partidos[$clave]['nombre'] = $array['nombre'];
		$datos_partidos[$clave]['principal'] = $array['principal'];
		$datos_partidos[$clave]['logo'] = $array['logo'];
		$datos_partidos[$clave]['color_border'] = $array['color_border'];
		$datos_partidos[$clave]['color_background'] = $array['color_background'];

		$datos_partidos[$clave]['votos_individual'] = $array['votos_2021'];
		$datos_partidos[$clave]['coaliciones'] = $coaliciones;
		$datos_partidos[$clave]['votos_coaliciones'] = $sum_coaliciones;
		$datos_partidos[$clave]['votos_totales'] = $sum_coaliciones + $array['votos_2021'];

		$partidos_orden[ $datos_partidos[$clave]['votos_totales'] ][$array['votos_2021']][] = $array['nombre_corto']; 
	}

	#ordenamos los partidos del mas votado hasta el menos votado puede ser que algunos tengan los mismos votos taltes
	#lo que hacemos es que lo metenemos en un array de los que tienen los mismos votos

	unset($orden_coaliciones);
	foreach ($datos_partidos as $key => $value) {
		foreach ($value['coaliciones'] as $keyPartido => $valueP) {
			/*
			if($value['nombre_corto'] != $keyPartido ){
				$datos_partidos[$key]['coaliciones'][$keyPartido] = $partidos_sin_coaliciones[$keyPartido]['votos_2021'];
			}else{
				unset($datos_partidos[$key]['coaliciones'][$keyPartido]);
			}
			*/
			$datos_partidos[$key]['coaliciones'][$keyPartido] = $partidos_sin_coaliciones[$keyPartido]['votos_2021'];

		}
	}

 
	#ordenamos
	krsort($partidos_orden);
	foreach ($partidos_orden as $key => $value) {
		krsort($value);
		foreach ($value as $keyT => $valueT) {
			$partidos_ordenados[$key][$keyT] = $valueT;
			foreach ($valueT as $keyS => $valueS) {
				$datos_partidos['ordenado'][$valueS] = $key;
				# code...
			}
		}
	}

	foreach ($partidos_ordenados as $totales => $totales_individuales) {
		foreach ($totales_individuales as $orden => $partido_nombres) {
			foreach ($partido_nombres as $keyT => $nombre) {

				
				if(empty($eleccion['primera_fuerza'])){
					$eleccion['primera_fuerza'] = $datos_partidos[$nombre];
					if($datos_partidos[$nombre]['principal']==1 ){
						$sistema = true;
					}
				}elseif (empty($eleccion['segunda_fuerza']) && empty($eleccion['primera_fuerza'] ['coaliciones'][$nombre])  ) {
					$eleccion['segunda_fuerza'] = $datos_partidos[$nombre];
					if($datos_partidos[$nombre]['principal']==1 ){
						$sistema = true;
					}
				}else{
					if($datos_partidos[$nombre]['principal']==1 && $sistema == false){
						$eleccion['sistema'] = $datos_partidos[$nombre];
					}
				}
				/*
				elseif (empty($eleccion['tercera_fuerza']) && empty($eleccion['primera_fuerza'] ['coaliciones'][$nombre]) && empty($eleccion['segunda_fuerza'] ['coaliciones'][$nombre])  ) {
					$eleccion['tercera_fuerza'] = $datos_partidos[$nombre];
				}
				*/
			}
		}
	}

	#####Mayoria

?>
<style type="text/css">
	.totales{ 
		display: table;
		float: left; 
		width: 100%;
		font-family: 'Avenir Next';
		letter-spacing: 2px;
		font-weight: 10px;
		text-transform: uppercase;

	}
	.fontLabelReporteTable {
		padding: 1px;   
		border-width: 1px;
		/*border-color: #ebccd1;*/ 
		text-transform: uppercase;
		letter-spacing: 2px; 
		font-size: 14px;
		font-family: 'Avenir Next';
		vertical-align: bottom;
	}
	.fontLabelReporte {
		padding: 1px;   
		border-width: 1px;
		/*border-color: #ebccd1;*/ 
		text-transform: uppercase;
		letter-spacing: 2px; 
		font-size: 10px;
		font-family: 'Avenir Next'; 
	}
	.fontDataReporte {
		padding: 1px; 
		font-weight: bold; 
		border-width: 1px;
		/*border-color: #ebccd1;*/ 
		text-transform: uppercase;
		letter-spacing: 1px; 
		font-size: 14px;
		font-family: 'Avenir Next';
	}
	.div25Reporte{
		width: 25%; 
		padding: 5px 25px 10px 25px ; 
		float: left;
	}
	.div25Reportepartidos{
		width: 25%; 
		padding: 5px 25px 10px 25px ; 
		float: left;
	}
	.div33Reporte{
		width: 33%; 
		padding: 5px 25px 10px 25px ; 
		float: left;
	}
	.div50Reporte{
		width: 50%; 
		padding: 5px 25px 10px 25px ; 
		float: left;
	}
	.div50ReporteSNF{
		width: 50%; 
		padding: 5px 25px 10px 25px ; 
	}
	.div100Reporte{
		width: 100%; 
		padding: 5px 25px 10px 25px ; 
		float: left;
	}
	.grafica_barras_horizontales{
		width: 100%;
		height:128.5px;
		display: block;
		padding: 10px;
	}

	@media only screen and (max-width: 1200px) and (min-width: 980px) {
	/* For mobile phones: */
		.div25Reportepartidos{
			width: 25%;
			padding: 5px 5px 10px 5px ; 
		}
		.div25Reporte{
			width: 50%;
		}
		.div25Reporte,.div50Reporte,.div50ReporteSNF,.div100Reporte{
			padding: 10px;
		}
		.grafica_barras_horizontales{
			width: 100%;
			height:88.5px;
			display: table;
		}
	}
	@media only screen and (max-width: 980px) and (min-width: 761px) {
	/* For mobile phones: */
		.div25Reportepartidos{
			width: 33%;
			padding: 5px 5px 10px 5px ; 
		}
		.div25Reporte{
			width: 50%;
		}
		.div25Reporte,.div50Reporte,.div50ReporteSNF,.div100Reporte{
			padding: 10px;
		}
		.grafica_barras_horizontales{
			width: 100%;
			height:88.5px;
			display: table;
		}
	}

	@media only screen and (max-width: 760px) and (min-width: 600px) {
	/* For mobile phones: */
		.div25Reportepartidos{
			width: 50%;
			padding: 5px 5px 10px 5px ; 
		}
		.totales,.div50Reporte,.div50ReporteSNF,.div25Reporte{
			width: 100%;
		}
		.div25Reporte,.div50Reporte,.div50ReporteSNF,.div100Reporte{
			padding: 10px;
		}
		.grafica_barras_horizontales{
			width: 100%;
			height:88.5px;
			display: table;
		}
	}
	@media only screen and (max-width: 620px) and (min-width: 6px) {
	/* For mobile phones: */
		.totales,.div50Reporte,.div50ReporteSNF,.div25Reporte,.div25Reportepartidos{
			width: 100%;
		}
		.div25Reporte,.div50Reporte,.div50ReporteSNF,.div100Reporte{
			padding: 10px;
		}
		.grafica_barras_horizontales{
			width: 100%;
			height:108.5px;
			display: block;
		}
	}
</style>
<div class="totales">
	<div style="width: 100%;display: table;padding: 5px 5px 5px 0px;background-color: white">
		<div style="background-color: white;padding: 5px;display: table;">
			<div class="div50Reporte" style="padding: 2px; margin: 0px;">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="2">Totales Votaciones <?= $ano ?></td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Secciones:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($secciones, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Casillas:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($casillas_2021, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte"></font></td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Válidos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_validos_2021, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Nulos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_nulos_2021, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos CAN NREG:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_can_nreg_2021, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Totales:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_totales_2021, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte"></font></td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Lista Nominal:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($total_lista_nominal_2021, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Participación Ciudadana:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($participacion_ciudadana, 2, '.', ','); ?>%
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Abstención Ciudadana:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format( $total_lista_abstencion_2021, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Porcentaje Abstención:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($abstencion_ciudadana, 2, '.', ','); ?>%
								</font>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte"></font></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="div50Reporte" style="padding: 2px; margin: 0px;">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="2">Demografía</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Ciudadanos</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($ciudadanos_totales, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Funcionarios</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($funcionarios, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Militantes</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($ciudadanos_totales, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte"></font></td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Programas de Gobierno y apoyos</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($apoyos_programas, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Acciones y Programas de Inversion</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($acciones_obras, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte"></font></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		 

		<hr style="width: 80%;border-top: 1px solid #333333;">
		<div style="background-color: white;padding: 5px;display: table;">
			<!--- fuerzas ----->
			<?php
			if(!empty($eleccion['sistema'])){
				$value = $eleccion['sistema'];
				?>
				<div style=" text-align: center;">
					<center>
						<div class="div50ReporteSNF">
							<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0" border="1">
								<thead>
									<tr>
										<tr>
										<td style="text-align: center;padding: 10px;background-color: #<?= $value['color_background'] ?>;color: white;font-weight: bold;" colspan="2">Sistema</td>
									</tr>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td style="text-align: left; width: 25%;padding: 5px 5px 5px 5px;text-align: center;">
											<img src="images/logos_partidos/<?= $value['logo'] ?>" style="width: 40%">
										</td>
										<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px">
											<font class="fontDataReporte" style="font-size: 12px">
												<?= $value['nombre_corto'] ?>
											</font>
										</td>
									</tr>
									<tr>
										<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos individual:</font></td>
										<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?=number_format($value['votos_individual'], 0, '.', ','); ?>
											</font>
										</td>
									</tr>
									<tr>
										<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Coaliciones:</font></td>
										<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?php
												unset($value['coaliciones'][$value['nombre_corto']]);
												$fields_pdo = implode(', ', array_keys($value['coaliciones']));
												echo $fields_pdo;
												?>
											</font>
										</td>
									</tr>
									<tr>
										<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Coalición:</font></td>
										<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?=number_format($value['votos_coaliciones'], 0, '.', ','); ?>
											</font>
										</td>
									</tr>
									<tr>
										<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos:</font></td>
										<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?=number_format($value['votos_totales'], 0, '.', ','); ?>
											</font>
										</td>
									</tr>
									<tr>
										<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
											<font class="fontLabelReporte">Votos %:</font>
										</td>
										<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?= $porcentaje_total; ?>%
											</font>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</center>
				</div>
				<?php
			}
			foreach ($eleccion as $key => $value) {
				$porcentaje_total = ($value['votos_totales'] / $votos_validos_2021 )*100;
				$porcentaje_total = truncar($porcentaje_total, 2); 
				if ($key !='sistema'){
					?>
					<div class="div50Reporte">
						<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0" border="1">
							<thead>
								<tr>
									<tr>
									<td style="text-align: center;padding: 10px;background-color: #<?= $value['color_background'] ?>;color: white;font-weight: bold;" colspan="2"><?= strtr($key, "_", " "); ?></td>
								</tr>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td style="text-align: left; width: 25%;padding: 5px 5px 5px 5px;text-align: center;">
										<img src="images/logos_partidos/<?= $value['logo'] ?>" style="width: 40%">
									</td>
									<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px">
										<font class="fontDataReporte" style="font-size: 12px">
											<?= $value['nombre_corto'] ?>
										</font>
									</td>
								</tr>
								<tr>
									<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos individual:</font></td>
									<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?=number_format($value['votos_individual'], 0, '.', ','); ?>
										</font>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.1); border-bottom: 1px solid white; height: 135px"><font class="fontLabelReporte">Coaliciones:</font> 
										<font class="fontDataReporte" style="font-size: 12px">

											<?php
											if(!empty($value['coaliciones'])){
												?>

													<table style="width: 100%;border:1px solid;text-align: left;font-size: 10px">
														<tr>
															<td style="border:1px solid;padding: 2px;background-color: #dee3ed">Partido</td>
															<td style="border:1px solid;padding: 2px;background-color: #dee3ed">Votos</td>
															<td style="border:1px solid;padding: 2px;background-color: #dee3ed">Diff.</td>
														</tr>
													
													<?php
													unset($value['coaliciones'][$value['nombre_corto']]);
													unset($coaliciones_orden);
													foreach ($value['coaliciones'] as $keyZ => $valueZ) {
														$coaliciones_orden[$valueZ][]=$keyZ;
													}
													krsort($coaliciones_orden);
													foreach ($coaliciones_orden as $keyO => $valueO) {
														krsort($valueO);
														foreach ($valueO as $keyL => $valueL) {
															echo "<tr>";
															echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".$valueL."</td>";
															echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($keyO, 0, '.', ',')."</td>";
															echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($value['votos_individual']-$keyO, 0, '.', ',')."</td>";
															echo "</tr>";
														}
													}
													echo "</table>";

											}else{
												echo "No tiene.";
											}

											#unset($value['coaliciones'][$value['nombre_corto']]);
											#$fields_pdo = implode(', ', array_keys(input)($value['coaliciones']));
											#echo $fields_pdo;
											?>
										</font>
									</td>
								</tr>
								<tr>
									<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Coalición:</font></td>
									<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?=number_format($value['votos_coaliciones'], 0, '.', ','); ?>
										</font>
									</td>
								</tr>
								<tr>
									<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos:</font></td>
									<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?=number_format($value['votos_totales'], 0, '.', ','); ?>
										</font>
									</td>
								</tr>
								<tr>
									<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
										<font class="fontLabelReporte">Votos %:</font>
									</td>
									<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?= $porcentaje_total; ?>%
										</font>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<?php
				}
			}
			?>
		</div>
		<hr style="width: 80%;border-top: 1px solid #333333;">
		<?php
		foreach ($datos_partidos['ordenado'] as $key => $valueT) {
			$value = $datos_partidos[$key];
			$votos_validos_2021;
			$partido_votos_porcentaje_2021 = ($value['votos_totales'] / $votos_validos_2021 )*100;
			$partido_votos_porcentaje_2021 = truncar($partido_votos_porcentaje_2021, 2);
			$nombre_corto_2021 = str_replace("_"," - ",$value['nombre_corto']);
			//$total = $partido_votos_porcentaje + $total;
			?>

			<div class="div25Reportepartidos" style="padding: 10px">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td colspan="3" style="text-align: center;padding: 10px;background-color: #<?= $value['color_background'] ?>;color: white; height: 60px" colspan="2">
								<?=  str_replace("_"," - ",$value['nombre_corto']) ?>
							</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td rowspan="2" style="text-align: center;padding: 5px 5px 5px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<img src="images/logos_partidos/<?= $value['logo'] ?>" style="width: 60px">
							</td>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos Individual:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_individual'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos Coalición:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_coaliciones'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos Totales:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_totales'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos %:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($partido_votos_porcentaje_2021, 2, '.', ','); ?>%
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<?php
		}
		//echo $total;
		?>
	</div>
</div>