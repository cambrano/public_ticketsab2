<?php

	$show_all = "";
	if($id_municipio != ""){

		$sqlMunicipip = " AND c.id_municipio = $id_municipio  ";
		$sql = 'SELECT 
			SUM(t.incidencias) incidencias,
			SUM(t.pendientes) pendientes,
			SUM(t.atendidas) atendidas,
			SUM(t.verde) verde,
			SUM(t.amarillo) amarillo,
			SUM(t.rojo) rojo
		FROM (
			SELECT 
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id) incidencias,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.status=0 ) pendientes,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.status=1 ) atendidas,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 1 ) verde,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 2 ) amarillo,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 3 ) rojo
			FROM casillas_votos_2024 c WHERE c.tipo=0 '.$sqlMunicipip.'
		) as t';
		$resultado = $conexion->query($sql);
		$row_municipal=$resultado->fetch_assoc(); 
		//votos validos, votos_nulos, votos canreg
		$sql = 'SELECT COUNT(*) total FROM casillas_votos_2024 c WHERE c.tipo=0 '.$sqlMunicipip;
		$resultado = $conexion->query($sql);
		$total_municipal=$resultado->fetch_assoc(); 

	}elseif($id_distrito_local != ""){

		$sqlDLocal = " AND c.id_distrito_local = $id_distrito_local  ";
		$sql = 'SELECT 
			SUM(t.incidencias) incidencias,
			SUM(t.pendientes) pendientes,
			SUM(t.atendidas) atendidas,
			SUM(t.verde) verde,
			SUM(t.amarillo) amarillo,
			SUM(t.rojo) rojo
		FROM (
			SELECT 
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id) incidencias,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.status=0 ) pendientes,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.status=1 ) atendidas,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 1 ) verde,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 2 ) amarillo,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 3 ) rojo
			FROM casillas_votos_2024 c WHERE c.tipo=1 '.$sqlDLocal.'
		) as t';
		$resultado = $conexion->query($sql);
		$row_local=$resultado->fetch_assoc(); 
		$sql = 'SELECT COUNT(*) total FROM casillas_votos_2024 c WHERE c.tipo=1 '.$sqlDLocal;
		$resultado = $conexion->query($sql);
		$total_local=$resultado->fetch_assoc();

	}elseif($id_distrito_federal != ""){
		$sqlDFederal = " AND c.id_distrito_federal = $id_distrito_federal  ";
		$sql = 'SELECT 
			SUM(t.incidencias) incidencias,
			SUM(t.pendientes) pendientes,
			SUM(t.atendidas) atendidas,
			SUM(t.verde) verde,
			SUM(t.amarillo) amarillo,
			SUM(t.rojo) rojo
		FROM (
			SELECT 
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id) incidencias,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.status=0 ) pendientes,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.status=1 ) atendidas,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 1 ) verde,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 2 ) amarillo,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 3 ) rojo
			FROM casillas_votos_2024 c WHERE c.tipo=2 '.$sqlDFederal.'
		) as t';
		$resultado = $conexion->query($sql);
		$row_federal=$resultado->fetch_assoc();
		$sql = 'SELECT COUNT(*) total FROM casillas_votos_2024 c WHERE c.tipo=2 '.$sqlDFederal;
		$resultado = $conexion->query($sql);
		$total_federal=$resultado->fetch_assoc();
	}else{

		$sql = 'SELECT 
			SUM(t.incidencias) incidencias,
			SUM(t.pendientes) pendientes,
			SUM(t.atendidas) atendidas,
			SUM(t.verde) verde,
			SUM(t.amarillo) amarillo,
			SUM(t.rojo) rojo
		FROM (
			SELECT 
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id) incidencias,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.status=0 ) pendientes,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.status=1 ) atendidas,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 1 ) verde,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 2 ) amarillo,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 3 ) rojo
			FROM casillas_votos_2024 c WHERE c.tipo=0
		) as t';
		$resultado = $conexion->query($sql);
		$row_municipal=$resultado->fetch_assoc(); 
		//votos validos, votos_nulos, votos canreg
		$sql = 'SELECT COUNT(*) total FROM casillas_votos_2024 c WHERE c.tipo=0 ';
		$resultado = $conexion->query($sql);
		$total_municipal=$resultado->fetch_assoc(); 

		$sql = 'SELECT 
			SUM(t.incidencias) incidencias,
			SUM(t.pendientes) pendientes,
			SUM(t.atendidas) atendidas,
			SUM(t.verde) verde,
			SUM(t.amarillo) amarillo,
			SUM(t.rojo) rojo
		FROM (
			SELECT 
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id) incidencias,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.status=0 ) pendientes,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.status=1 ) atendidas,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 1 ) verde,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 2 ) amarillo,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 3 ) rojo
			FROM casillas_votos_2024 c WHERE c.tipo=1 
		) as t';
		$resultado = $conexion->query($sql);
		$row_local=$resultado->fetch_assoc(); 
		$sql = 'SELECT COUNT(*) total FROM casillas_votos_2024 c WHERE c.tipo=1 ';
		$resultado = $conexion->query($sql);
		$total_local=$resultado->fetch_assoc();


		$sql = 'SELECT 
			SUM(t.incidencias) incidencias,
			SUM(t.pendientes) pendientes,
			SUM(t.atendidas) atendidas,
			SUM(t.verde) verde,
			SUM(t.amarillo) amarillo,
			SUM(t.rojo) rojo
		FROM (
			SELECT 
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id) incidencias,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.status=0 ) pendientes,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.status=1 ) atendidas,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 1 ) verde,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 2 ) amarillo,
				(SELECT COUNT(*) FROM casillas_votos_2024_incidencias ci WHERE ci.id_casilla_voto_2024 = c.id AND ci.semaforo = 3 ) rojo
			FROM casillas_votos_2024 c WHERE c.tipo=2
		) as t';
		$resultado = $conexion->query($sql);
		$row_federal=$resultado->fetch_assoc();
		$sql = 'SELECT COUNT(*) total FROM casillas_votos_2024 c WHERE c.tipo=2 ';
		$resultado = $conexion->query($sql);
		$total_federal=$resultado->fetch_assoc();

		$mostrar_all = 1;
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
			<div class="div50Reporte" style="padding: 2px; margin: 0px; <?=  $total_municipal['total']>0 ? '':'display:none' ?> ">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="2">Casillas Municipales</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Casillas Totales:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($total_municipal['total'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Inicidencias:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_municipal['incidencias'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Inicidencias Pendientes:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_municipal['pendientes'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Inicidencias Atendidas:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_municipal['atendidas'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Incidencias Verdes:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_municipal['verde'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Incidencias Amarillas:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_municipal['amarillo'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Incidencias Rojas:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_municipal['rojo'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="div50Reporte" style="padding: 2px; margin: 0px; <?=  $total_local['total']>0 ? '':'display:none' ?> ">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="2">Casillas Locales</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Casillas Totales:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($total_local['total'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Inicidencias:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_local['incidencias'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Inicidencias Pendientes:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_local['pendientes'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Inicidencias Atendidas:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_local['atendidas'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Incidencias Verdes:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_local['verde'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Incidencias Amarillas:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_local['amarillo'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Incidencias Rojas:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_local['rojo'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="div50Reporte" style="padding: 2px; margin: 0px; <?=  $total_federal['total']>0 ? '':'display:none' ?> ">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="2">Casillas Federales</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Casillas Totales:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($total_federal['total'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Inicidencias:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_federal['incidencias'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Inicidencias Pendientes:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_federal['pendientes'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Inicidencias Atendidas:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_federal['atendidas'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Incidencias Verdes:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_federal['verde'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Incidencias Amarillas:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_federal['amarillo'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Incidencias Rojas:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($row_federal['rojo'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>