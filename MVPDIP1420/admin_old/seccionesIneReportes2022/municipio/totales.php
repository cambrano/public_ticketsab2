<?php
	//votos validos, votos_nulos, votos canreg
	$sql = "SELECT 
				t.votos_validos_2022,
				(SELECT SUM(votos_nulos) FROM casillas_votos_2022 WHERE id_municipio={$id_municipio} AND tipo=0 ) votos_nulos_2022,
				(SELECT SUM(votos_can_nreg) FROM casillas_votos_2022 WHERE id_municipio={$id_municipio} AND tipo=0 ) votos_can_nreg_2022,
				(SELECT COUNT(id) FROM secciones_ine WHERE id_municipio={$id_municipio} ) secciones,
				(SELECT COUNT(id) FROM casillas_votos_2022 WHERE id_municipio={$id_municipio} AND tipo=0 ) casillas_2022,
				(SELECT SUM(lista_nominal) FROM casillas_votos_2022 WHERE id_municipio={$id_municipio} AND tipo=0 ) total_lista_nominal_2022,

				( SELECT id_partido_2022 votos FROM casillas_votos_partidos_2022 WHERE id_municipio={$id_municipio} AND tipo=0 GROUP BY id_partido_2022 ORDER by SUM(votos) DESC LIMIT 1) id_partido_ganador_2022
			FROM
				(SELECT SUM(votos) votos_validos_2022 FROM  casillas_votos_partidos_2022 WHERE id_municipio={$id_municipio} AND tipo=0 )t


	";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$partido_ganador_id_2022 = $row['id_partido_ganador_2022'];
	$votos_nulos_2022 = $row['votos_nulos_2022'];
	$votos_can_nreg_2022 = $row['votos_can_nreg_2022'];
	$votos_validos_2022 = $row['votos_validos_2022'];
	$votos_totales_2022 = $votos_nulos_2022 + $votos_can_nreg_2022 + $votos_validos_2022;

	$secciones = $row['secciones'];
	$casillas_2022 = $row['casillas_2022'];
	$total_lista_nominal_2022 = $row['total_lista_nominal_2022'];
	if($total_lista_nominal_2022==""){
		$total_lista_nominal_2022=0;
	}
	if($extranjeros_mode){
		$municipios = $row['municipios']-1;
		$extranjero = 1;
	}else{
		$municipios = $row['municipios'];
		$extranjero = 0;
	}

	if($total_lista_nominal_2022==0){
		$participacion_ciudadana = 0;
	}else{
		$participacion_ciudadana = ($votos_totales_2022 / $total_lista_nominal_2022 )*100;
	}

	
	$sql="SELECT 
			p2022.id AS id_partido_2022,
			p2022.clave,
			p2022.nombre_corto,
			p2022.logo,
			p2022.color_background,
			p2022.color_border,
			p2022.clave_partidos_coaliciones,
			SUM(cvp2022.votos) votos,
			cvp2022.id_seccion_ine
		FROM casillas_votos_partidos_2022 cvp2022 
		LEFT JOIN partidos_2022 p2022 ON cvp2022.id_partido_2022 = p2022.id
		WHERE cvp2022.tipo = '{$tipo_eleccion}'  AND cvp2022.id_municipio = '{$id_municipio}'
		GROUP BY cvp2022.id_seccion_ine,id_partido_2022 
		ORDER BY cvp2022.id_seccion_ine,SUM(cvp2022.votos) DESC
		";
	$result = $conexion->query($sql);
	//echo "<pre>";
	//echo $sql;
	//echo "</pre>";
	while($row=$result->fetch_assoc()){
		if($row['clave_partidos_coaliciones'] != ''){
			$partidos_coaliciones[$row['id_seccion_ine']][$row['clave']]=$row;
		}else{
			$partidos_sin_coaliciones[$row['id_seccion_ine']][$row['nombre_corto']]=$row;
		}
	} 



	$sql="
		SELECT
			p2022.id,
			p2022.nombre_corto,
			p2022.nombre,
			p2022.logo,
			p2022.color_border,
			p2022.color_background,
			SUM(cvp2022.votos) votos_2022
		FROM partidos_2022 p2022
		LEFT JOIN casillas_votos_partidos_2022 cvp2022
		ON p2022.id = cvp2022.id_partido_2022
		WHERE cvp2022.id_municipio={$id_municipio} AND p2022.tipo=0 AND cvp2022.tipo=0
		GROUP BY cvp2022.id_partido_2022
	";
	$sql;
	$result = $conexion->query($sql); 
	$num=0; 
	while($row=$result->fetch_assoc()){
		foreach($row as $key => $value){
			if(is_numeric($key)) unset($row[$key]);
		}
		$datos_partidos[$num]=$row;
		$num=$num+1;
	}

	/*
	foreach ($datos_partidos as $key => $value) {
		if( $value['id'] == $partido_ganador_id_2022 ){
			$partido_ganador = $value;
			unset($datos_partidos[$key]);
		}
		if( $value['id'] == $partido_sistema_id_2022 ){
			$partido_sistema = $value;
			unset($datos_partidos[$key]);
		}
	}
	*/

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
	.div100Reporte{
		width: 100%; 
		padding: 5px 25px 10px 25px ; 
		float: left;
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
		.div25Reporte,.div50Reporte,.div100Reporte{
			padding: 10px;
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
		.div25Reporte,.div50Reporte,.div100Reporte{
			padding: 10px;
		}
	}

	@media only screen and (max-width: 760px) and (min-width: 600px) {
	/* For mobile phones: */
		.div25Reportepartidos{
			width: 50%;
			padding: 5px 5px 10px 5px ; 
		}
		.totales,.div50Reporte,.div25Reporte{
			width: 100%;
		}
		.div25Reporte,.div50Reporte,.div100Reporte{
			padding: 10px;
		}
	}
	@media only screen and (max-width: 620px) and (min-width: 6px) {
	/* For mobile phones: */
		.totales,.div50Reporte,.div25Reporte,.div25Reportepartidos{
			width: 100%;
		}
		.div25Reporte,.div50Reporte,.div100Reporte{
			padding: 10px;
		}
	}
	
</style>
<div class="totales">
	<div style="width: 100%;display: table;padding: 5px 5px 5px 0px;background-color: white">
		<div style="background-color: white;padding: 5px;display: table;">
			<div class="div50Reporte">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="2">Totales Votaciones</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Válidos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_validos_2022, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Nulos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_nulos_2022, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos CAN NREG:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_can_nreg_2022, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Totales:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_totales_2022, 0, '.', ','); ?>
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
					</tbody>
				</table>
			</div>
			<div class="div50Reporte">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="2">Cartografía</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Lista Nominal:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($total_lista_nominal_2022, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
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
									<?=number_format($casillas_2022, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<hr style="width: 80%;border-top: 1px solid #333333;">
		<div style="background-color: white;padding: 5px;display: table;">
			<?php
			$votos_validos_2022;
			$partido_ganador_votos_porcentaje_2022 = ($partido_ganador['votos_2022'] / $votos_validos_2022 )*100;
			$partido_ganador_votos_porcentaje_2022 = truncar($partido_ganador_votos_porcentaje_2022, 2);
			$partido_ganador_nombre_corto_2022 = str_replace("_"," - ",$partido_ganador['nombre_corto']);
			?>
			<div class="div50Reporte">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0" border="1">
					<thead>
						<tr>
							<tr>
							<td style="text-align: center;padding: 10px;background-color: #<?= $partido_ganador['color_background'] ?>;color: white;font-weight: bold;" colspan="2">Mayoría</td>
						</tr>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; width: 25%;padding: 5px 5px 5px 5px;text-align: center;">
								<img src="images/logos_partidos/<?= $partido_ganador['logo'] ?>" style="width: 40%">
							</td>
							<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $partido_ganador_nombre_corto_2022 ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($partido_ganador['votos_2022'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos %:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $partido_ganador_votos_porcentaje_2022; ?>%
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			$votos_validos_2022;
			$partido_sistema_votos_porcentaje_2022 = ($partido_sistema['votos_2022'] / $votos_validos_2022 )*100;
			$partido_sistema_votos_porcentaje_2022 = truncar($partido_sistema_votos_porcentaje_2022, 2);
			$partido_sistema_nombre_corto_2022 = str_replace("_"," - ",$partido_sistema['nombre_corto']);

			$partido_diferencia_votos_2022 = $partido_ganador['votos_2022'] - $partido_sistema['votos_2022'];
			$partido_diferencia_porcentaje_2022 = $partido_ganador_votos_porcentaje_2022 - $partido_sistema_votos_porcentaje_2022;
			$partido_diferencia_porcentaje_2022 = truncar($partido_diferencia_porcentaje_2022, 2);
			?>
			<div class="div50Reporte">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0" border="1">
					<thead>
						<tr>
							<tr>
							<td style="text-align: center;padding: 10px;background-color: #<?= $partido_sistema['color_background'] ?>;color: white;font-weight: bold;" colspan="2">Información <?= $partido_sistema_nombre_corto_2022 ?></td>
						</tr>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; width: 25%;padding: 5px 5px 5px 5px;text-align: center;">
								<img src="images/logos_partidos/<?= $partido_sistema['logo'] ?>" style="width: 40%">
							</td>
							<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $partido_sistema['nombre'] ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($partido_sistema['votos_2022'], 0, '.', ','); ?>
								</font>
								/
								<font class="fontDataReporte" style="color: red;font-size: 12px">
									&#8595; <?=number_format($partido_diferencia_votos_2022, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos %:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $partido_sistema_votos_porcentaje_2022; ?>%
								</font>
								/
								<font class="fontDataReporte" style="color: red;font-size: 12px">
									&#8595; <?= $partido_diferencia_porcentaje_2022 ?>
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<hr style="width: 80%;border-top: 1px solid #333333;">
		<?php

		foreach ($datos_partidos as $key => $value) {
			$votos_validos_2022;
			$partido_votos_porcentaje_2022 = ($value['votos_2022'] / $votos_validos_2022 )*100;
			$partido_votos_porcentaje_2022 = truncar($partido_votos_porcentaje_2022, 2);
			$nombre_corto_2022 = str_replace("_"," - ",$value['nombre_corto']);
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
								<font class="fontLabelReporte">Votos:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_2022'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos %:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $partido_votos_porcentaje_2022 ?>
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