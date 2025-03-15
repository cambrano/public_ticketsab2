<?php
	//votos validos, votos_nulos, votos canreg
	function random_color_rgb() {
		return rand(0,255).','.rand(0,255).','.rand(0,255);
	}
	function random_color_hex() {
		return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
	}

	
	if($tipo_uso_plataforma=='municipio'){
		$sqlTipoPlat= " AND sice.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sqlTipoPlat= " AND sice.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sqlTipoPlat= " AND sice.id_distrito_federal ='{$id_distrito_federal}' ";
	}
	$sqlTipoPlat_gen = $sqlTipoPlat;
	//! colocamos el tipo de plataforma
	$validar_codigo_plataforma = validar_codigo_plataforma($codigo_plataforma);
	if($validar_codigo_plataforma == false){
		$sqlTipoPlat .= " AND sice.codigo_plataforma = '{$codigo_plataforma}' ";
	}


	$sql = "
		SELECT 
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos sice WHERE 1 {$sqlTipoPlat} ) ciudadanos_totales,
			(SELECT SUM(lista_nominal) FROM manzanas_ine sice WHERE 1 = 1 {$sqlTipoPlat_gen} ) total_lista_nominal_2021,
			SUM(CASE WHEN sexo = 'hombre' THEN 1 ELSE 0 END) AS totales_hombres,
			SUM(CASE WHEN sexo = 'mujer' THEN 1 ELSE 0 END) AS totales_mujeres,
			SUM(CASE WHEN sexo = 'hombre' AND edad = 18  THEN 1 ELSE 0 END) AS totales_18_hombres,
			SUM(CASE WHEN sexo = 'hombre' AND edad = 19  THEN 1 ELSE 0 END) AS totales_19_hombres,
			SUM(CASE WHEN sexo = 'hombre' AND edad BETWEEN 20 AND 24  THEN 1 ELSE 0 END) AS totales_20_24_hombres,
			SUM(CASE WHEN sexo = 'hombre' AND edad BETWEEN 25 AND 29  THEN 1 ELSE 0 END) AS totales_25_29_hombres,
			SUM(CASE WHEN sexo = 'hombre' AND edad BETWEEN 30 AND 34  THEN 1 ELSE 0 END) AS totales_30_34_hombres,
			SUM(CASE WHEN sexo = 'hombre' AND edad BETWEEN 35 AND 39  THEN 1 ELSE 0 END) AS totales_35_39_hombres,
			SUM(CASE WHEN sexo = 'hombre' AND edad BETWEEN 40 AND 44  THEN 1 ELSE 0 END) AS totales_40_44_hombres,
			SUM(CASE WHEN sexo = 'hombre' AND edad BETWEEN 45 AND 49  THEN 1 ELSE 0 END) AS totales_45_49_hombres,
			SUM(CASE WHEN sexo = 'hombre' AND edad BETWEEN 50 AND 54  THEN 1 ELSE 0 END) AS totales_50_54_hombres,
			SUM(CASE WHEN sexo = 'hombre' AND edad BETWEEN 55 AND 59  THEN 1 ELSE 0 END) AS totales_55_59_hombres,
			SUM(CASE WHEN sexo = 'hombre' AND edad BETWEEN 60 AND 64  THEN 1 ELSE 0 END) AS totales_60_64_hombres,
			SUM(CASE WHEN sexo = 'hombre' AND edad > 64  THEN 1 ELSE 0 END) AS totales_65_mas_hombres,
			SUM(CASE WHEN sexo = 'mujer' AND edad = 18  THEN 1 ELSE 0 END) AS totales_18_mujeres,
			SUM(CASE WHEN sexo = 'mujer' AND edad = 19  THEN 1 ELSE 0 END) AS totales_19_mujeres,
			SUM(CASE WHEN sexo = 'mujer' AND edad BETWEEN 20 AND 24  THEN 1 ELSE 0 END) AS totales_20_24_mujeres,
			SUM(CASE WHEN sexo = 'mujer' AND edad BETWEEN 25 AND 29  THEN 1 ELSE 0 END) AS totales_25_29_mujeres,
			SUM(CASE WHEN sexo = 'mujer' AND edad BETWEEN 30 AND 34  THEN 1 ELSE 0 END) AS totales_30_34_mujeres,
			SUM(CASE WHEN sexo = 'mujer' AND edad BETWEEN 35 AND 39  THEN 1 ELSE 0 END) AS totales_35_39_mujeres,
			SUM(CASE WHEN sexo = 'mujer' AND edad BETWEEN 40 AND 44  THEN 1 ELSE 0 END) AS totales_40_44_mujeres,
			SUM(CASE WHEN sexo = 'mujer' AND edad BETWEEN 45 AND 49  THEN 1 ELSE 0 END) AS totales_45_49_mujeres,
			SUM(CASE WHEN sexo = 'mujer' AND edad BETWEEN 50 AND 54  THEN 1 ELSE 0 END) AS totales_50_54_mujeres,
			SUM(CASE WHEN sexo = 'mujer' AND edad BETWEEN 55 AND 59  THEN 1 ELSE 0 END) AS totales_55_59_mujeres,
			SUM(CASE WHEN sexo = 'mujer' AND edad BETWEEN 60 AND 64  THEN 1 ELSE 0 END) AS totales_60_64_mujeres,
			SUM(CASE WHEN sexo = 'mujer' AND edad > 64  THEN 1 ELSE 0 END) AS totales_65_mas_mujeres
		FROM   secciones_ine_ciudadanos sice WHERE 1 {$sqlTipoPlat} ";
	
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();

	$total_lista_nominal_2021 = $row['total_lista_nominal_2021'];
	$ciudadanos_totales = $row['ciudadanos_totales'];
	$total_lista_nominal_ciudadanos_2021 = $total_lista_nominal_2021-$ciudadanos_totales;
	$ciudadanos_lista_nominal_porcentaje = ($ciudadanos_totales / $total_lista_nominal_2021) * 100;
	$ciudadanos_totales_porcentaje = ($ciudadanos_totales/$total_lista_nominal_2021)*100;


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

	/*
	if($tipo_uso_plataforma=='municipio'){
		$sqlTipoPlat= " AND sic.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sqlTipoPlat= " AND sic.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sqlTipoPlat= " AND sic.id_distrito_federal ='{$id_distrito_federal}' ";
	}
	*/

	$sql = "
		SELECT 
			tc.nombre,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos sice WHERE sice.id_tipo_ciudadano = tc.id {$sqlTipoPlat} ) ciudadanos
		FROM tipos_ciudadanos tc WHERE 1 ";

	$resultado = $conexion->query($sql);
	while($row=$resultado->fetch_assoc()){
		$tipos_ciudadanos[] = $row;
	} 
	$sql = "
		SELECT 
			tc.nombre,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos_categorias sice WHERE sice.id_tipo_categoria_ciudadano = tc.id {$sqlTipoPlat_gen} ) ciudadanos
		FROM tipos_categorias_ciudadanos tc WHERE 1 ";
	$resultado = $conexion->query($sql);
	while($row=$resultado->fetch_assoc()){
		$tipos_categorias_ciudadanos[] = $row;
	} 

	$sql = "
		SELECT 
		'sin categoría' nombre,
		COUNT(*) ciudadanos 
		FROM secciones_ine_ciudadanos sice 
		WHERE 1
		{$sqlTipoPlat}
		AND NOT EXISTS (SELECT * FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine_ciudadano = sice.id) ";
	
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$tipos_categorias_ciudadanos[] = $row;

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
		<div class="div25Reporte" style="padding: 2px; margin: 0px;">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">L.Nominal Y Ciudadanos</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="text-align: center; padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white; border-right: 1px solid white" colspan="1"><font class="fontLabelReporte"></font></td>
					</tr>
					<tr>
						<td style="text-align: left; padding: 2px 5px 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid #191919;" colspan="1">
							<font class="fontLabelReporte">
								<?php include "grafica_lista_nomnial_ciudadanos.php" ?>
							</font>
						</td>
					</tr>
					<tr>
						<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;"><font class="fontLabelReporte"></font></td>
					</tr>
				</tbody>
			</table>
		</div> 
		<div class="div25Reporte" style="padding: 2px; margin: 0px;">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">Hombres Y Mujeres</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="text-align: center; padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white; border-right: 1px solid white" colspan="1"><font class="fontLabelReporte"></font></td>
					</tr>
					<tr>
						<td style="text-align: left; padding: 2px 5px 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid #191919;" colspan="1">
							<font class="fontLabelReporte">
								<?php include "grafica_hombres_mujeres_totales.php" ?>
							</font>
						</td>
					</tr>
					<tr>
						<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;"><font class="fontLabelReporte"></font></td>
					</tr>
				</tbody>
			</table>
		</div> 
		<div class="div25Reporte" style="padding: 2px; margin: 0px;">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">Tipos Ciudadanos</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="text-align: center; padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white; border-right: 1px solid white" colspan="1"><font class="fontLabelReporte"></font></td>
					</tr>
					<tr>
						<td style="text-align: left; padding: 2px 5px 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid #191919;" colspan="1">
							<font class="fontLabelReporte">
								<?php include "grafica_tipos_ciudadanos.php" ?>
							</font>
						</td>
					</tr>
					<tr>
						<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;"><font class="fontLabelReporte"></font></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="div25Reporte" style="padding: 2px; margin: 0px;">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">Categorías Ciudadanos</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td style="text-align: center; padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white; border-right: 1px solid white" colspan="1"><font class="fontLabelReporte"></font></td>
					</tr>
					<tr>
						<td style="text-align: left; padding: 2px 5px 2px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid #191919;" colspan="1">
							<font class="fontLabelReporte">
								<?php include "grafica_tipos_categorias_ciudadanos.php" ?>
							</font>
						</td>
					</tr>
					<tr>
						<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;"><font class="fontLabelReporte"></font></td>
					</tr>
				</tbody>
			</table>
		</div> 
		<div class="div100Reporte" style="padding: 2px; margin: 0px;">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="1">Grafica Edades</td>
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
						<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(25,25,25,0.6); border-bottom: 1px solid white;" colspan="3"><font class="fontLabelReporte"></font></td>
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

	</div>
</div>