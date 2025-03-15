<?php
	//votos validos, votos_nulos, votos canreg
	$sql = "SELECT 
				t.votos_validos_2021,
				(SELECT SUM(votos_nulos) FROM casillas_votos_2021 WHERE id_municipio={$id_municipio} AND tipo = 1 ) votos_nulos_2021,
				(SELECT SUM(votos_can_nreg) FROM casillas_votos_2021 WHERE id_municipio={$id_municipio} AND tipo = 1 ) votos_can_nreg_2021,
				(SELECT COUNT(id) FROM secciones_ine WHERE id_municipio={$id_municipio} ) secciones,
				(SELECT COUNT(id) FROM casillas_votos_2021 WHERE id_municipio={$id_municipio} AND tipo = 1 ) casillas_2021,
				(SELECT SUM(lista_nominal) FROM casillas_votos_2021 WHERE id_municipio={$id_municipio} AND tipo = 1 ) total_lista_nominal_2021,

				( SELECT id_partido_2021 votos FROM casillas_votos_partidos_2021 WHERE id_municipio={$id_municipio} AND tipo = 1 GROUP BY id_partido_2021 ORDER by SUM(votos) DESC LIMIT 1) id_partido_ganador_2021,

				(SELECT COUNT(id) FROM secciones_ine_ciudadanos WHERE id_municipio={$id_municipio} ) ciudadanos_totales,
				(SELECT COUNT(id) FROM secciones_ine_ciudadanos_check_2021 WHERE id_municipio={$id_municipio} AND check_in=1 AND check_out=1 ) ciudadanos_totales_doble_check,
				(SELECT COUNT(id) FROM secciones_ine_ciudadanos_check_2021 WHERE id_municipio={$id_municipio} AND check_in=1 ) ciudadanos_totales_check_in,
				(SELECT COUNT(id) FROM secciones_ine_ciudadanos_check_2021 WHERE id_municipio={$id_municipio} AND check_out=1 ) ciudadanos_totales_check_out,
				(SELECT COUNT(sic.id) FROM secciones_ine_ciudadanos sic LEFT JOIN secciones_ine_ciudadanos_check_2021 sicc2021 ON sic.id = sicc2021.id_seccion_ine_ciudadano WHERE IF(sicc2021.check_in IS NULL, 0,sicc2021.check_in ) = 0 AND IF(sicc2021.check_out IS NULL, 0,sicc2021.check_out ) = 0 AND sic.id_municipio={$id_municipio} ) ciudadanos_totales_sin_check
			FROM
				(SELECT SUM(votos) votos_validos_2021 FROM  casillas_votos_partidos_2021 WHERE id_municipio={$id_municipio} AND tipo =  1 )t


	";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$partido_ganador_id_2021 = $row['id_partido_ganador_2021'];
	$votos_nulos_2021 = $row['votos_nulos_2021'];
	$votos_can_nreg_2021 = $row['votos_can_nreg_2021'];
	$votos_validos_2021 = $row['votos_validos_2021'];
	$votos_totales_2021 = $votos_nulos_2021 + $votos_can_nreg_2021 + $votos_validos_2021;

	$secciones = $row['secciones'];
	$casillas_2021 = $row['casillas_2021'];
	$municipios_2021 = $row['municipios_2021']-1;
	$distritos_federales_2021 = $row['distritos_federales_2021'];
	$total_lista_nominal_2021 = $row['total_lista_nominal_2021'];
	//aqui cambrano
	$ciudadanos_totales = $row['ciudadanos_totales'];
	$ciudadanos_totales_porcentaje = ($ciudadanos_totales/$total_lista_nominal_2021)*100;

	$ciudadanos_totales_doble_check = $row['ciudadanos_totales_doble_check'];
	$ciudadanos_totales_doble_check_restantes = $ciudadanos_totales - $ciudadanos_totales_doble_check;
	$ciudadanos_totales_doble_check_porcentaje = ($ciudadanos_totales_doble_check / $ciudadanos_totales)*100;
	//$ciudadanos_totales_doble_check_porcentaje = truncar($ciudadanos_totales_doble_check_porcentaje,2);
	$total_lista_nominal_ciudadanos_2021 = $total_lista_nominal_2021-$ciudadanos_totales;

	$ciudadanos_totales_check_in = $row['ciudadanos_totales_check_in'];
	$ciudadanos_totales_check_in_restantes = $ciudadanos_totales - $ciudadanos_totales_check_in;
	$ciudadanos_totales_check_in_porcentaje = ($ciudadanos_totales_check_in / $ciudadanos_totales)*100;

	$ciudadanos_totales_check_out = $row['ciudadanos_totales_check_out'];
	$ciudadanos_totales_check_out_restantes = $ciudadanos_totales - $ciudadanos_totales_check_out;
	$ciudadanos_totales_check_out_porcentaje = ($ciudadanos_totales_check_out / $ciudadanos_totales)*100;

	$ciudadanos_totales_sin_check = $row['ciudadanos_totales_sin_check'];
	$ciudadanos_totales_sin_check_restantes = $ciudadanos_totales - $ciudadanos_totales_sin_check;
	$ciudadanos_totales_sin_check_porcentaje = ($ciudadanos_totales_sin_check / $ciudadanos_totales)*100;
	//$ciudadanos_totales_sin_check_porcentaje = truncar($ciudadanos_totales_sin_check_porcentaje,2);

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

	//partido sistema
	$sql = "SELECT
			*,
			(SELECT SUM(cpv2021.votos) FROM casillas_votos_partidos_2021 cpv2021 WHERE cpv2021.id_partido_2021 = p2021.id AND cpv2021.id_municipio={$id_municipio} AND cpv2021.tipo = 1 ) votos_totales_2021
			FROM partidos_2021 p2021
			WHERE p2021.principal=1 AND p2021.codigo_plataforma = '{$codigo_plataforma}' AND p2021.tipo = 1";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$partido_sistema_nombre_2021 = $row['nombre'];
	$partido_sistema_nombre_corto_2021 = $row['nombre_corto'];
	$partido_sistema_logo_2021 = $row['logo'];
	$partido_sistema_id_2021 = $row['id'];
	$partido_sistema_color_background_2021 = $row['color_background'];
	$partido_sistema_votos_2021 = $row['votos_totales_2021'];
	$partido_sistema_votos_porcentaje_2021 = ($partido_sistema_votos_2021 / $votos_validos_2021 )*100;
	$partido_sistema_votos_porcentaje_2021 = truncar($partido_sistema_votos_porcentaje_2021, 2);

	$sql="
		SELECT
			p2021.id,
			p2021.nombre_corto,
			p2021.nombre,
			p2021.logo,
			p2021.color_border,
			p2021.color_background,
			SUM(cvp2021.votos) votos_2021
		FROM partidos_2021 p2021
		LEFT JOIN casillas_votos_partidos_2021 cvp2021
		ON p2021.id = cvp2021.id_partido_2021
		WHERE cvp2021.id_municipio={$id_municipio} AND cvp2021.tipo = 1
		GROUP BY cvp2021.id_partido_2021
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
		if( $value['id'] == $partido_ganador_id_2021 ){
			$partido_ganador = $value;
			unset($datos_partidos[$key]);
		}
		if( $value['id'] == $partido_sistema_id_2021 ){
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
		.div25Reporte,.div50Reporte,.div100Reporte{
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
		.div25Reporte,.div50Reporte,.div100Reporte{
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
		.totales,.div50Reporte,.div25Reporte{
			width: 100%;
		}
		.div25Reporte,.div50Reporte,.div100Reporte{
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
		.totales,.div50Reporte,.div25Reporte,.div25Reportepartidos{
			width: 100%;
		}
		.div25Reporte,.div50Reporte,.div100Reporte{
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
			<div class="div25Reporte" style="padding: 2px; margin: 0px;display: none">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">Participación</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; padding: 2px 5px 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid #191919;" colspan="1">
								<font class="fontLabelReporte">
									<?php include "graficas_principales.php" ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;"><font class="fontLabelReporte"></font></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="div50Reporte" style="padding: 2px; margin: 0px;">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">Votos</td>
						</tr>
					</thead>
					<tbody> 
						<tr>
							<td style="text-align: left; padding: 2px 5px 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid #191919;" colspan="1">
								<font class="fontLabelReporte">
									<?php include "graficas_votos.php" ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;"><font class="fontLabelReporte"></font></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="div50Reporte" style="padding: 2px; margin: 0px;">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="2">Totales Votaciones 2021</td>
						</tr>
					</thead>
					<tbody>
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
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Porcentaje:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($abstencion_ciudadana, 2, '.', ','); ?>%
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="div50Reporte" style="padding: 2px; margin: 0px;">
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
									<?=number_format($total_lista_nominal_2021, 0, '.', ','); ?>
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
									<?=number_format($casillas_2021, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte"></font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte"></font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<div class="div50Reporte" style="padding: 2px; margin: 0px 0px 10px 0px;display: none">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="2">Participación Ciudadanos</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="text-align: center; padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white; border-right: 1px solid white" colspan="2"><font class="fontLabelReporte"></font></td>
					</tr>
					<tr>
						<td style="text-align: left; padding: 2px 5px 5px 2px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid #191919;" colspan="2">
							<?php include "grafica_lista_nominal_barras.php"; ?>
						</td>
					</tr>
					<tr>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white">L.Nominal Y Ciudadanos</td>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white">Votos Lista Nominal</td>
					</tr>
					<tr>
						<td style="text-align: left;padding: 2px 5px 5px 2px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid #191919;">
							<?php include "grafica_lista_nomnial_ciudadanos.php" ?>
							
						</td>
						<td style="text-align: left;padding: 0px 5px 0px 2px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid #191919;">
							<?php include "grafica_votos_lista_nominal.php" ?>
						</td>
					</tr>
					<tr>
						<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;" colspan="2"><font class="fontLabelReporte"></font></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="div50Reporte" style="padding: 2px; margin: 0px 0px 10px 0px;display: none">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">Doble Check</td>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">Sin Check</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white;" colspan="2"><font class="fontLabelReporte"></font></td>
					</tr>
					<tr>
						<td style="text-align: left; width: 25%;padding: 2px 5px 5px 2px;background-color: rgba(176,176,176,0.3);  ">
							<?php include "grafica_doble_check.php" ?><br>
						</td>
						<td style="text-align: left; width: 25%;padding: 0px 5px 0px 2px;background-color: rgba(176,176,176,0.3); ">
							<?php include "grafica_sin_check.php" ?><br>
						</td>
					</tr>
					<tr>
						<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white;" colspan="2"><font class="fontLabelReporte"></font></td>
					</tr>
					<tr>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">Check IN</td>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">Check OUT</td>
					</tr>
					<tr>
						<td style="text-align: left; width: 25%;padding: 2px 5px 5px 2px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid #191919;">
							<?php include "grafica_check_in.php" ?>
						</td>
						<td style="text-align: left; width: 25%;padding: 0px 5px 0px 2px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid #191919;">
							<?php include "grafica_check_out.php" ?>
						</td>
					</tr>
					<tr>
						<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;" colspan="2"><font class="fontLabelReporte"></font></td>
					</tr>
				</tbody>
			</table>
		</div>

		<hr style="width: 80%;border-top: 1px solid #333333;">
		<div style="background-color: white;padding: 5px;display: table;">
			<?php
			$votos_validos_2021;
			$partido_ganador_votos_porcentaje_2021 = ($partido_ganador['votos_2021'] / $votos_validos_2021 )*100;
			$partido_ganador_votos_porcentaje_2021 = truncar($partido_ganador_votos_porcentaje_2021, 2);
			$partido_ganador_nombre_corto_2021 = str_replace("_"," - ",$partido_ganador['nombre_corto']);
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
									<?= $partido_ganador_nombre_corto_2021 ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($partido_ganador['votos_2021'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos %:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $partido_ganador_votos_porcentaje_2021; ?>%
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
			$votos_validos_2021;
			$partido_sistema_votos_porcentaje_2021 = ($partido_sistema['votos_2021'] / $votos_validos_2021 )*100;
			$partido_sistema_votos_porcentaje_2021 = truncar($partido_sistema_votos_porcentaje_2021, 2);
			$partido_sistema_nombre_corto_2021 = str_replace("_"," - ",$partido_sistema['nombre_corto']);

			$partido_diferencia_votos_2021 = $partido_ganador['votos_2021'] - $partido_sistema['votos_2021'];
			$partido_diferencia_porcentaje_2021 = $partido_ganador_votos_porcentaje_2021 - $partido_sistema_votos_porcentaje_2021;
			$partido_diferencia_porcentaje_2021 = truncar($partido_diferencia_porcentaje_2021, 2);
			?>
			<div class="div50Reporte">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0" border="1">
					<thead>
						<tr>
							<tr>
							<td style="text-align: center;padding: 10px;background-color: #<?= $partido_sistema['color_background'] ?>;color: white;font-weight: bold;" colspan="2">Información <?= $partido_sistema_nombre_corto_2021 ?></td>
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
									<?=number_format($partido_sistema['votos_2021'], 0, '.', ','); ?>
								</font>
								/
								<font class="fontDataReporte" style="color: red;font-size: 12px">
									&#8595; <?=number_format($partido_diferencia_votos_2021, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos %:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $partido_sistema_votos_porcentaje_2021; ?>%
								</font>
								/
								<font class="fontDataReporte" style="color: red;font-size: 12px">
									&#8595; <?= $partido_diferencia_porcentaje_2021 ?>
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
			$votos_validos_2021;
			$partido_votos_porcentaje_2021 = ($value['votos_2021'] / $votos_validos_2021 )*100;
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
								<font class="fontLabelReporte">Votos:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_2021'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos %:</font>
							</td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $partido_votos_porcentaje_2021 ?>
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









