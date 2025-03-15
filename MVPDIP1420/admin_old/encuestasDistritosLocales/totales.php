<?php
	//votos validos, votos_nulos, votos canreg
	function random_color_rgb() {
		return rand(0,255).','.rand(0,255).','.rand(0,255);
	}
	 
	function random_color_hex() {
		return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
	}
	$sql = "
		SELECT 
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos ) totales_ciudadanos,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos_encuestas  WHERE id_encuesta = 1 ) totales_encuestados,
			SUM(CASE WHEN sexo = 1 THEN 1 ELSE 0 END) AS totales_hombres,
			SUM(CASE WHEN sexo = 2 THEN 1 ELSE 0 END) AS totales_mujeres,
			SUM(CASE WHEN sexo = 1 AND edad = 18  THEN 1 ELSE 0 END) AS totales_18_hombres,
			SUM(CASE WHEN sexo = 1 AND edad = 19  THEN 1 ELSE 0 END) AS totales_19_hombres,
			SUM(CASE WHEN sexo = 1 AND edad BETWEEN 20 AND 24  THEN 1 ELSE 0 END) AS totales_20_24_hombres,
			SUM(CASE WHEN sexo = 1 AND edad BETWEEN 25 AND 29  THEN 1 ELSE 0 END) AS totales_25_29_hombres,
			SUM(CASE WHEN sexo = 1 AND edad BETWEEN 30 AND 34  THEN 1 ELSE 0 END) AS totales_30_34_hombres,
			SUM(CASE WHEN sexo = 1 AND edad BETWEEN 35 AND 39  THEN 1 ELSE 0 END) AS totales_35_39_hombres,
			SUM(CASE WHEN sexo = 1 AND edad BETWEEN 40 AND 44  THEN 1 ELSE 0 END) AS totales_40_44_hombres,
			SUM(CASE WHEN sexo = 1 AND edad BETWEEN 45 AND 49  THEN 1 ELSE 0 END) AS totales_45_49_hombres,
			SUM(CASE WHEN sexo = 1 AND edad BETWEEN 50 AND 54  THEN 1 ELSE 0 END) AS totales_50_54_hombres,
			SUM(CASE WHEN sexo = 1 AND edad BETWEEN 55 AND 59  THEN 1 ELSE 0 END) AS totales_55_59_hombres,
			SUM(CASE WHEN sexo = 1 AND edad BETWEEN 60 AND 64  THEN 1 ELSE 0 END) AS totales_60_64_hombres,
			SUM(CASE WHEN sexo = 1 AND edad > 64  THEN 1 ELSE 0 END) AS totales_65_mas_hombres,
			SUM(CASE WHEN sexo = 2 AND edad = 18  THEN 1 ELSE 0 END) AS totales_18_mujeres,
			SUM(CASE WHEN sexo = 2 AND edad = 19  THEN 1 ELSE 0 END) AS totales_19_mujeres,
			SUM(CASE WHEN sexo = 2 AND edad BETWEEN 20 AND 24  THEN 1 ELSE 0 END) AS totales_20_24_mujeres,
			SUM(CASE WHEN sexo = 2 AND edad BETWEEN 25 AND 29  THEN 1 ELSE 0 END) AS totales_25_29_mujeres,
			SUM(CASE WHEN sexo = 2 AND edad BETWEEN 30 AND 34  THEN 1 ELSE 0 END) AS totales_30_34_mujeres,
			SUM(CASE WHEN sexo = 2 AND edad BETWEEN 35 AND 39  THEN 1 ELSE 0 END) AS totales_35_39_mujeres,
			SUM(CASE WHEN sexo = 2 AND edad BETWEEN 40 AND 44  THEN 1 ELSE 0 END) AS totales_40_44_mujeres,
			SUM(CASE WHEN sexo = 2 AND edad BETWEEN 45 AND 49  THEN 1 ELSE 0 END) AS totales_45_49_mujeres,
			SUM(CASE WHEN sexo = 2 AND edad BETWEEN 50 AND 54  THEN 1 ELSE 0 END) AS totales_50_54_mujeres,
			SUM(CASE WHEN sexo = 2 AND edad BETWEEN 55 AND 59  THEN 1 ELSE 0 END) AS totales_55_59_mujeres,
			SUM(CASE WHEN sexo = 2 AND edad BETWEEN 60 AND 64  THEN 1 ELSE 0 END) AS totales_60_64_mujeres,
			SUM(CASE WHEN sexo = 2 AND edad > 64  THEN 1 ELSE 0 END) AS totales_65_mas_mujeres
		FROM   secciones_ine_ciudadanos_encuestas sice
		WHERE sice.id_encuesta = '{$id_encuesta}' ";

	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$totales_ciudadanos = $row['totales_ciudadanos'];
	$totales_encuestados = $row['totales_encuestados'];
	$totales_no_encuestados = $row['totales_ciudadanos'] - $row['totales_encuestados'];
	$totales_encuestados_porcentaje = ($totales_encuestados / $totales_ciudadanos) * 100;
	$totales_no_encuestados_porcentaje = ($totales_no_encuestados / $totales_ciudadanos) * 100;

	$totales_hombres = $row['totales_hombres'];
	$totales_mujeres = $row['totales_mujeres'];
	$totales_18_hombres = $row['totales_18_hombres'];
	$totales_18_mujeres = $row['totales_18_mujeres'];
	$totales_19_hombres = $row['totales_19_hombres'];
	$totales_19_mujeres = $row['totales_19_mujeres'];
	$totales_20_24_hombres = $row['totales_20_24_hombres'];
	$totales_20_24_mujeres = $row['totales_20_24_mujeres'];
	$totales_25_29_hombres = $row['totales_25_29_hombres'];
	$totales_25_29_mujeres = $row['totales_25_29_mujeres'];
	$totales_30_34_hombres = $row['totales_30_34_hombres'];
	$totales_30_34_mujeres = $row['totales_30_34_mujeres'];
	$totales_35_39_hombres = $row['totales_35_39_hombres'];
	$totales_35_39_mujeres = $row['totales_35_39_mujeres'];
	$totales_40_44_hombres = $row['totales_40_44_hombres'];
	$totales_40_44_mujeres = $row['totales_40_44_mujeres'];
	$totales_45_49_hombres = $row['totales_45_49_hombres'];
	$totales_45_49_mujeres = $row['totales_45_49_mujeres'];
	$totales_50_54_hombres = $row['totales_50_54_hombres'];
	$totales_50_54_mujeres = $row['totales_50_54_mujeres'];
	$totales_55_59_hombres = $row['totales_55_59_hombres'];
	$totales_55_59_mujeres = $row['totales_55_59_mujeres'];
	$totales_60_64_hombres = $row['totales_60_64_hombres'];
	$totales_60_64_mujeres = $row['totales_60_64_mujeres'];
	$totales_65_mas_hombres = $row['totales_65_mas_hombres'];
	$totales_65_mas_mujeres = $row['totales_65_mas_mujeres'];

	$sql="
		SELECT 
		m.id,
		m.clave,
		m.numero,
		m.latitud,
		m.longitud,
		( SELECT COUNT(*) FROM secciones_ine_ciudadanos s WHERE s.id_distrito_local = m.id ) totales_ciudadanos,
		( SELECT COUNT(*) FROM secciones_ine_ciudadanos_encuestas s WHERE s.id_distrito_local = m.id AND s.id_encuesta ='{$id_encuesta}' ) totales_encuestados,
		( SELECT COUNT(*) FROM secciones_ine_ciudadanos_encuestas s WHERE s.id_distrito_local = m.id AND s.id_encuesta ='{$id_encuesta}' ) / ( SELECT COUNT(*) FROM secciones_ine_ciudadanos s WHERE s.id_distrito_local = m.id  )*100 porcentaje,
		(SELECT COUNT(*) FROM secciones_ine s WHERE s.id_distrito_local = m.id) secciones,
		( SELECT s.fecha_hora FROM secciones_ine_ciudadanos_encuestas s WHERE s.id_distrito_local = m.id AND s.id_encuesta ='{$id_encuesta}' ORDER BY s.fecha_hora DESC LIMIT 1) ultima_encuesta,
		( SELECT (SELECT si.numero FROM secciones_ine si WHERE si.id = s.id_seccion_ine )  FROM secciones_ine_ciudadanos_encuestas s WHERE s.id_distrito_local = m.id AND s.id_encuesta ='{$id_encuesta}' AND s.id_distrito_local = m.id ORDER BY s.fecha_hora DESC LIMIT 1) ultima_encuesta_seccion
		FROM distritos_locales m
		WHERE 1 
	";
	$sql.=" ORDER BY porcentaje DESC ";
	$result = $conexion->query($sql);
	while($row=$result->fetch_assoc()){
		$row['totales_no_encuestados'] = $row['totales_ciudadanos'] - $row['totales_encuestados'];
		$datos_distritos_locales_encuestados[$row['id']]=$row;
		$datos_distritos_locales[] = $row;
	}

	$sql="
		SELECT 
			c.id,
			c.pregunta
		FROM cuestionarios c WHERE c.id_encuesta ='{$id_encuesta}' AND c.campo !='text'
	";
	$result = $conexion->query($sql);
	while($row=$result->fetch_assoc()){
		$cuestionarios[] = $row;
	}

	$sql="
		SELECT 
			cr.id,
			cr.id_encuesta,
			cr.id_cuestionario,
			cr.respuesta,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos_encuestas_respuestas sicer WHERE sicer.id_cuestionario_respuesta = cr.id ) respuestas
		FROM cuestionarios_respuestas cr WHERE cr.id_encuesta ='{$id_encuesta}'
	";
	$result = $conexion->query($sql);
	while($row=$result->fetch_assoc()){
		$respuestas[$row['id_cuestionario']][] = $row;
	}
	//echo "<pre>";
	//print_r($datos_distritos_locales_encuestados);
	//echo "</pre>";

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
	.div75Reporte{
		width: 75%; 
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
		.div25Reporte,.div50Reporte,.div100Reporte,.div75Reporte{
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
		.div25Reporte,.div50Reporte,.div100Reporte,.div75Reporte{
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
		.div25Reporte,.div50Reporte,.div100Reporte,.div75Reporte{
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
		.totales,.div50Reporte,.div25Reporte,.div25Reportepartidos,.div75Reporte{
			width: 100%;
		}
		.div25Reporte,.div50Reporte,.div100Reporte,.div75Reporte{
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
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">Encuestados</td>
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
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="3">Datos Demográficos</td>
						</tr>
					</thead>
					<tbody> 
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">
									<i class="far fa-male"></i><b>Hombres
									(<?=number_format($totales_hombres, 0, '.', ','); ?>)</b>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>Edad</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">
									<i class="far fa-female"></i><b>Mujeres
									(<?=number_format($totales_mujeres, 0, '.', ','); ?>) </b>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_18_hombres, 0, '.', ','); ?><br>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>18</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_18_mujeres, 0, '.', ','); ?><br>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_19_hombres, 0, '.', ','); ?><br>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>19</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_19_mujeres, 0, '.', ','); ?><br>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_20_24_hombres, 0, '.', ','); ?><br>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>20 - 24</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_20_24_mujeres, 0, '.', ','); ?><br>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_25_29_hombres, 0, '.', ','); ?><br>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>25 - 29</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_25_29_mujeres, 0, '.', ','); ?><br>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_30_34_hombres, 0, '.', ','); ?><br>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>30 - 34</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_30_34_mujeres, 0, '.', ','); ?><br>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_35_39_hombres, 0, '.', ','); ?><br>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>35 - 39</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_35_39_mujeres, 0, '.', ','); ?><br>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_40_44_hombres, 0, '.', ','); ?><br>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>40 - 44</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_40_44_mujeres, 0, '.', ','); ?><br>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_45_49_hombres, 0, '.', ','); ?><br>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>45 - 49</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_45_49_mujeres, 0, '.', ','); ?><br>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_50_54_hombres, 0, '.', ','); ?><br>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>50 - 54</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_50_54_mujeres, 0, '.', ','); ?><br>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_55_59_hombres, 0, '.', ','); ?><br>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>55 - 59</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_55_59_mujeres, 0, '.', ','); ?><br>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_60_64_hombres, 0, '.', ','); ?><br>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>60 - 64</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_60_64_mujeres, 0, '.', ','); ?><br>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_65_mas_hombres, 0, '.', ','); ?><br>
								</font>
							</td> 
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white;text-align: center;">
								<font class="fontLabelReporte">
									<b>65 Más</b><br>
								</font>
							</td>
							<td style="text-align: right; width: 33%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($totales_65_mas_mujeres, 0, '.', ','); ?><br>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;" colspan="3"><font class="fontLabelReporte"></font></td>
						</tr> 
					</tbody>
				</table>
			</div>
			<div class="div100Reporte" style="padding: 2px; margin: 0px;">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">Gráfica</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="text-align: left; padding: 2px 5px 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid #191919;" colspan="1">
							<font class="fontLabelReporte">
								<?php include "graficas_edades.php" ?>
							</font>
						</td>
					</tr>
					<tr>
						<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;"><font class="fontLabelReporte"></font></td>
					</tr>
				</tbody>
			</table>
		</div>
		</div>
	</div>
	<div style="background-color: white;padding: 5px;display: table;">
		<div class="div50Reporte" style="padding: 2px; margin: 0px;">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="3">Distritos Más Encuestados (5)</td>
					</tr>
				</thead>
				<tbody> 
					<?php 
					$num = 1;
					foreach ($datos_distritos_locales as $key => $value) {
						if($num <=5){
							$num ++;
							?>
							<tr>
								<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
									<font class="fontLabelReporte">
										<b><?= $value['numero'] ?></b><br>
										Ciudadanos:<?=number_format($value['totales_ciudadanos'], 0, '.', ','); ?>
									</font>
								</td>
								<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.5); border-bottom: 1px solid white">
									<font class="fontLabelReporte">
										Encuestados:<?=number_format($value['totales_encuestados'], 0, '.', ','); ?><br>
										NO Encuestados:<?=number_format($value['totales_no_encuestados'], 0, '.', ','); ?><br>
									</font>
								</td>

								<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
									<font class="fontDataReporte" style="font-size: 12px">
										<?=number_format($value['porcentaje'], 0, '.', ','); ?>%
									</font>
								</td>
							</tr>
							<?php
						}
					}
					?> 
					<tr>
						<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;" colspan="3"><font class="fontLabelReporte"></font></td>
					</tr> 
				</tbody>
			</table>
		</div>
		<div class="div50Reporte" style="padding: 2px; margin: 0px;">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="3">Distritos Menos Encuestados (5)</td>
					</tr>
				</thead>
				<tbody> 
					<?php 
					$num = 1;
					krsort($datos_distritos_locales);
					foreach ($datos_distritos_locales as $key => $value) {
						if($num <=5){
							$num ++;
							?>
							<tr>
								<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
									<font class="fontLabelReporte">
										<b><?= $value['numero'] ?></b><br>
										Ciudadanos:<?=number_format($value['totales_ciudadanos'], 0, '.', ','); ?>
									</font>
								</td>
								<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.5); border-bottom: 1px solid white">
									<font class="fontLabelReporte">
										Encuestados:<?=number_format($value['totales_encuestados'], 0, '.', ','); ?><br>
										NO Encuestados:<?=number_format($value['totales_no_encuestados'], 0, '.', ','); ?><br>
									</font>
								</td>

								<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
									<font class="fontDataReporte" style="font-size: 12px">
										<?=number_format($value['porcentaje'], 0, '.', ','); ?>%
									</font>
								</td>
							</tr>
							<?php
						}
					}
					?> 
					<tr>
						<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;" colspan="3"><font class="fontLabelReporte"></font></td>
					</tr> 
				</tbody>
			</table>
		</div>
		<?php
		foreach ($cuestionarios as $key => $value) {
			$id_cuestionario = $value['id'];
			?>
			<div class="div50Reporte" style="padding: 2px; margin: 0px;">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1"></td>
						</tr>
					</thead>
					<tbody> 
						<tr>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.5); border-bottom: 1px solid white; text-align: center;height: 80px;">
								<font style="font-size: 8px;font-weight: bold;" ><?= $value['pregunta'] ?></font>
							</td>
						</tr>
						<tr>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white; text-align: center;height: 340px;">
								<canvas id="birdsChart<?= $value['id'] ?>" width="650" height="400"></canvas>
							</td>
						</tr>
						<tr>
							<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;" colspan="1"><font class="fontLabelReporte"></font></td>
						</tr> 
					</tbody>
				</table>
			</div>
			<script type="text/javascript">
				var birdsCanvas = document.getElementById("birdsChart<?= $value['id'] ?>");
				var birdsData = {
					labels: [
					<?php
						foreach ($respuestas[$id_cuestionario] as $keyT => $valueT) {
							//echo '"'.$valueT['respuesta'].'('.number_format($valueT['respuestas'],0,'',',').')'.'",';
							echo '"'.$valueT['respuesta'].'",';
						}
					?>
					],
					datasets: [{
						data: [
							<?php
								foreach ($respuestas[$id_cuestionario] as $keyT => $valueT) {
									echo ''.$valueT['respuestas'].',';
								}
							?>
						],
						dataX: [
							<?php
								foreach ($respuestas[$id_cuestionario] as $keyT => $valueT) {
									echo '"'.number_format($valueT['respuestas'],0,'',',').'",';
								}
							?>
						],
						backgroundColor: [
							<?php
								foreach ($respuestas[$id_cuestionario] as $keyT => $valueT) {
									echo '"rgba('.random_color_rgb().',0.5)",';
								}
							?> 
						]
					}]
				};

				var polarAreaChart = new Chart(birdsCanvas, {
					type: 'polarArea',
					data: birdsData,
					options: {
						 
						responsive: true, 
						title: {
							display: false,
							text: 'H:<?=number_format($totales_hombres, 0, '.', ','); ?> | M:<?=number_format($totales_mujeres, 0, '.', ','); ?> ',
							text:'',
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
							},
							backgroundColor: 'rgba(0, 0, 0, 0.8)',
							titleFontSize: 10,
							titleFontColor: '#fff',
							bodyFontColor: '#fff',
							bodyFontSize: 10,
							displayColors: false
						},
					}

				});
			</script>
			<?php
		}
		?>
	</div>
</div>