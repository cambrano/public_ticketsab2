<?php
	//votos validos, votos_nulos, votos canreg
	$sql = "SELECT 
				t.votos_validos_2018,
				(SELECT SUM(votos_nulos) FROM casillas_votos_2018 WHERE id_municipio={$id_municipio} AND tipo=0 ) votos_nulos_2018,
				(SELECT SUM(votos_can_nreg) FROM casillas_votos_2018 WHERE id_municipio={$id_municipio} AND tipo=0 ) votos_can_nreg_2018,
				(SELECT COUNT(id) FROM secciones_ine WHERE id_municipio={$id_municipio} ) secciones,
				(SELECT COUNT(id) FROM casillas_votos_2018 WHERE id_municipio={$id_municipio} AND tipo=0 ) casillas_2018,
				(SELECT SUM(lista_nominal) FROM casillas_votos_2018 WHERE id_municipio={$id_municipio} AND tipo=0 ) total_lista_nominal_2018,

				( SELECT id_partido_2018 votos FROM casillas_votos_partidos_2018 WHERE id_municipio={$id_municipio} AND tipo=0 GROUP BY id_partido_2018 ORDER by SUM(votos) DESC LIMIT 1) id_partido_ganador_2018
			FROM
				(SELECT SUM(votos) votos_validos_2018 FROM  casillas_votos_partidos_2018 WHERE id_municipio={$id_municipio} AND tipo=0 )t


	";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$partido_ganador_id_2018 = $row['id_partido_ganador_2018'];
	$votos_nulos_2018 = $row['votos_nulos_2018'];
	$votos_can_nreg_2018 = $row['votos_can_nreg_2018'];
	$votos_validos_2018 = $row['votos_validos_2018'];
	$votos_totales_2018 = $votos_nulos_2018 + $votos_can_nreg_2018 + $votos_validos_2018;

	$secciones = $row['secciones'];
	$casillas_2018 = $row['casillas_2018'];
	$total_lista_nominal_2018 = $row['total_lista_nominal_2018'];
	if($total_lista_nominal_2018==""){
		$total_lista_nominal_2018=0;
	}
	if($extranjeros_mode){
		$municipios = $row['municipios']-1;
		$extranjero = 1;
	}else{
		$municipios = $row['municipios'];
		$extranjero = 0;
	}

	if($total_lista_nominal_2018==0){
		$participacion_ciudadana = 0;
	}else{
		$participacion_ciudadana = ($votos_totales_2018 / $total_lista_nominal_2018 )*100;
	}

	//partido sistema
	$sql = "SELECT
			*,
			(SELECT SUM(cpv2018.votos) FROM casillas_votos_partidos_2018 cpv2018 WHERE cpv2018.id_partido_2018 = p2018.id AND cpv2018.id_municipio={$id_municipio} AND cpv2018.tipo=0 ) votos_totales_2018
			FROM partidos_2018 p2018
			WHERE p2018.principal=1 AND p2018.codigo_plataforma = '{$codigo_plataforma}' AND p2018.tipo=0 ";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$partido_sistema_nombre_2018 = $row['nombre'];
	$partido_sistema_nombre_corto_2018 = $row['nombre_corto'];
	$partido_sistema_logo_2018 = $row['logo'];
	$partido_sistema_id_2018 = $row['id'];
	$partido_sistema_color_background_2018 = $row['color_background'];
	$partido_sistema_votos_2018 = $row['votos_totales_2018'];

	if($votos_validos_2018==0){
		$partido_sistema_votos_porcentaje_2018 = 0;
	}else{
		$partido_sistema_votos_porcentaje_2018 = ($partido_sistema_votos_2018 / $votos_validos_2018 )*100;
		$partido_sistema_votos_porcentaje_2018 = truncar($partido_sistema_votos_porcentaje_2018, 2);
	}

	$sql="
		SELECT
			p2018.id,
			p2018.nombre_corto,
			p2018.nombre,
			p2018.logo,
			p2018.color_border,
			p2018.color_background,
			SUM(cvp2018.votos) votos_2018
		FROM partidos_2018 p2018
		LEFT JOIN casillas_votos_partidos_2018 cvp2018
		ON p2018.id = cvp2018.id_partido_2018
		WHERE cvp2018.id_municipio={$id_municipio} AND p2018.tipo=0 AND cvp2018.tipo=0
		GROUP BY cvp2018.id_partido_2018
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

	foreach ($datos_partidos as $key => $value) {
		if( $value['id'] == $partido_ganador_id_2018 ){
			$partido_ganador = $value;
			unset($datos_partidos[$key]);
		}
		if( $value['id'] == $partido_sistema_id_2018 ){
			$partido_sistema = $value;
			unset($datos_partidos[$key]);
		}
	}

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
									<?=number_format($votos_validos_2018, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Nulos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_nulos_2018, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos CAN NREG:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_can_nreg_2018, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Totales:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_totales_2018, 0, '.', ','); ?>
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
									<?=number_format($total_lista_nominal_2018, 0, '.', ','); ?>
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
									<?=number_format($casillas_2018, 0, '.', ','); ?>
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
			$votos_validos_2018;
			$partido_ganador_votos_porcentaje_2018 = ($partido_ganador['votos_2018'] / $votos_validos_2018 )*100;
			$partido_ganador_votos_porcentaje_2018 = truncar($partido_ganador_votos_porcentaje_2018, 2);
			$partido_ganador_nombre_corto_2018 = str_replace("_"," - ",$partido_ganador['nombre_corto']);
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
									<?= $partido_ganador_nombre_corto_2018 ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($partido_ganador['votos_2018'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos %:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $partido_ganador_votos_porcentaje_2018; ?>%
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			$votos_validos_2018;
			$partido_sistema_votos_porcentaje_2018 = ($partido_sistema['votos_2018'] / $votos_validos_2018 )*100;
			$partido_sistema_votos_porcentaje_2018 = truncar($partido_sistema_votos_porcentaje_2018, 2);
			$partido_sistema_nombre_corto_2018 = str_replace("_"," - ",$partido_sistema['nombre_corto']);

			$partido_diferencia_votos_2018 = $partido_ganador['votos_2018'] - $partido_sistema['votos_2018'];
			$partido_diferencia_porcentaje_2018 = $partido_ganador_votos_porcentaje_2018 - $partido_sistema_votos_porcentaje_2018;
			$partido_diferencia_porcentaje_2018 = truncar($partido_diferencia_porcentaje_2018, 2);
			?>
			<div class="div50Reporte">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0" border="1">
					<thead>
						<tr>
							<tr>
							<td style="text-align: center;padding: 10px;background-color: #<?= $partido_sistema['color_background'] ?>;color: white;font-weight: bold;" colspan="2">Información <?= $partido_sistema_nombre_corto_2018 ?></td>
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
									<?=number_format($partido_sistema['votos_2018'], 0, '.', ','); ?>
								</font>
								/
								<font class="fontDataReporte" style="color: red;font-size: 12px">
									&#8595; <?=number_format($partido_diferencia_votos_2018, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos %:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $partido_sistema_votos_porcentaje_2018; ?>%
								</font>
								/
								<font class="fontDataReporte" style="color: red;font-size: 12px">
									&#8595; <?= $partido_diferencia_porcentaje_2018 ?>
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
			$votos_validos_2018;
			$partido_votos_porcentaje_2018 = ($value['votos_2018'] / $votos_validos_2018 )*100;
			$partido_votos_porcentaje_2018 = truncar($partido_votos_porcentaje_2018, 2);
			$nombre_corto_2018 = str_replace("_"," - ",$value['nombre_corto']);
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
									<?=number_format($value['votos_2018'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos %:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $partido_votos_porcentaje_2018 ?>
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