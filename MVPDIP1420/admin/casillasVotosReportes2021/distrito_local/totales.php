<?php
 
	include __DIR__."/../../functions/casillas_votos_2021.php";
	include __DIR__."/../../functions/tipos_casillas.php";



	$datos_partidos = $datos_secciones_ine[$id_seccion_ine];

	$sql="
		SELECT
			p.id,
			p.clave,
			p.nombre_corto,
			p.nombre,
			p.logo,
			p.color_border,
			p.color_background,
			SUM(cvp.votos) votos,
			p.clave_partidos_coaliciones,
			p.principal,
			cvp.id_distrito_local,
			cvp.id_seccion_ine,
			cvp.id_casilla_voto_2021
		FROM  casillas_votos_partidos_2021 cvp
		LEFT JOIN partidos_2021 p
		ON p.id = cvp.id_partido_2021
		WHERE cvp.id_distrito_local='{$id_distrito_local}' AND cvp.tipo = '{$tipo}' AND cvp.id_seccion_ine = '{$id_seccion_ine}'
		GROUP BY cvp.id_distrito_local,cvp.id_seccion_ine,cvp.id_casilla_voto_2021,cvp.id_partido_2021
	";
	$sql;
	$result = $conexion->query($sql);

	while($row=$result->fetch_assoc()){
		if($row['clave_partidos_coaliciones'] == ''){
			unset($row['clave_partidos_coaliciones']);
		}
		if($row['principal'] == ''){
			unset($row['principal']);
		}
		#$datos_partidos[$num]=$row;
		//? Colocamos en su arrelgo segun sea el tipo de partido
		if($row['clave_partidos_coaliciones'] != ''){
			$partidos_coaliciones[$row['id_casilla_voto_2021']][$row['clave_partidos_coaliciones']]=$row;
		}else{
			$partidos_sin_coaliciones[$row['id_casilla_voto_2021']][$row['clave']]=$row;
		} 
		$num=$num+1;
	}


	$sql="
		SELECT
			si.id,
			si.id_seccion_ine,
			si.id_tipo_casilla,
			si.clave,
			si.codigo,
			si.votos_nulos,
			si.votos_can_nreg,
			si.lista_nominal,
			si.tipo_seccion,
			(SELECT m.municipio FROM municipios m WHERE m.id = si.id_municipio) municipio,
			(SELECT COUNT(cv.id) FROM casillas_votos_2021 cv WHERE cv.id_seccion_ine = si.id_seccion_ine AND cv.tipo='{$tipo}') casillas,
			(SELECT SUM(cv.lista_nominal) FROM casillas_votos_2021 cv WHERE cv.id_seccion_ine = si.id_seccion_ine AND cv.tipo='{$tipo}' ) lista_nominal,
			(SELECT SUM(cv.votos_nulos) FROM casillas_votos_2021 cv WHERE cv.id_seccion_ine = si.id_seccion_ine AND cv.tipo='{$tipo}') votos_nulos,
			(SELECT SUM(cv.votos_can_nreg) FROM casillas_votos_2021 cv WHERE cv.id_seccion_ine = si.id_seccion_ine AND cv.tipo='{$tipo}') votos_can_nreg,
			(SELECT SUM(cv.votos) FROM casillas_votos_partidos_2021 cv WHERE cv.id_seccion_ine = si.id_seccion_ine AND cv.tipo='{$tipo}') votos_validos
		FROM casillas_votos_2021 si
		WHERE si.id_distrito_local = '$id_distrito_local' AND si.id_seccion_ine = '{$id_seccion_ine}' AND si.tipo = '{$tipo}'
	";
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$row['votos_totales'] = $row['votos_nulos'] + $row['votos_can_nreg'] + $row['votos_validos'];
		$row['participacion_ciudadana'] = truncar((($row['votos_totales'] / $row['lista_nominal'])*100), 2);
		$datos_casillas[$row['id']]=$row;

		//? Tomamos como princial el partido sin coalicion

		unset($ordena_votos_individual);
		unset($ordena_votos_totales);
		foreach ($partidos_sin_coaliciones[$row['id']] as $clave => $array) {
			//? Colocamos en 0 la suma de coalciones para que no se sume con los demas
			//? El arreglo de coalciones lo vaciamos para que no se agregen de los demas partidos
			$sum_coaliciones = 0;
			unset($coaliciones); 
			unset($coalicion_orden_individual);
			foreach ($partidos_coaliciones[$row['id']] as $nombre_corto => $arraysc) {
				//? Vemos si el nombre corto esta en la coalicion para agregarlo
				//? Si es negativo sigue con el siguiente
				$posicion_coalicion = explode(',', $nombre_corto);
				/*
				$pos = strpos($nombre_corto, $array['nombre_corto']);
				echo $nombre_corto;
				echo "-----";
				echo $array['nombre_corto'];
				echo "-----";
				echo var_dump($pos);
				echo "<br>";
				if ($pos !== false ) {
				*/
				if (in_array($array['clave'], $posicion_coalicion)) {
					$coaliciones_array = explode(",", $nombre_corto);
					foreach ($coaliciones_array as $partido => $votos) {
						$coaliciones[$votos] = $partidos_sin_coaliciones[$row['id']][$votos];
						//! Importante
						//? Buscamos si existe en el arrey para que no se repita
						//* votos == nombre del partido segun la coalicion
						//* [] == colocamos arreglo vacio por que puede ser que uno o mas partidos tengan los mismos votos
						#$coalicion_orden_individual[$clave][$nombre_corto][ $partidos_sin_coaliciones[$votos]['votos'] ][]=$votos;
						$search_coalicion = array_search($votos, $coalicion_orden_individual[$partidos_sin_coaliciones[$row['id']][$votos]['votos'] ]);
						if($search_coalicion === NULL){
							$coalicion_orden_individual[$partidos_sin_coaliciones[$row['id']][$votos]['votos'] ][]= $votos;
						}
					}
					$sum_coaliciones = $sum_coaliciones + $arraysc['votos'];
				}
			}
			//? Nuestro Principal arreglo
			//* clave == nombre del partido
			$datos_casillas[$row['id']]['partidos'][$clave]['id'] = $array['id'];
			$datos_casillas[$row['id']]['partidos'][$clave]['clave'] = $clave;
			$datos_casillas[$row['id']]['partidos'][$clave]['nombre_corto'] = $array['nombre_corto'];
			$datos_casillas[$row['id']]['partidos'][$clave]['nombre'] = $array['nombre'];
			$datos_casillas[$row['id']]['partidos'][$clave]['principal'] = $array['principal'];
			$datos_casillas[$row['id']]['partidos'][$clave]['logo'] = $array['logo'];
			$datos_casillas[$row['id']]['partidos'][$clave]['color_border'] = $array['color_border'];
			$datos_casillas[$row['id']]['partidos'][$clave]['color_background'] = $array['color_background'];

			$datos_casillas[$row['id']]['partidos'][$clave]['votos_individual'] = $array['votos'];
			$datos_casillas[$row['id']]['partidos'][$clave]['coaliciones_sin_orden'] = $coaliciones;
			$datos_casillas[$row['id']]['partidos'][$clave]['votos_coaliciones'] = $sum_coaliciones;

			//! Importante
			//? Ordenamos las coaliciones por votos en individual
			$total_votos_individual = 0;
			krsort($coalicion_orden_individual);
			foreach ($coalicion_orden_individual as $votos => $partidos_array) {
				foreach ($partidos_array as $index => $partido) {
					$datos_casillas[$row['id']]['partidos'][$clave]['coaliciones_orden_votos_individual'][$partido]=$votos;
					if($clave != $partido){
						$total_votos_individual = $total_votos_individual + $votos;
					}
				}
			}
			$datos_casillas[$row['id']]['partidos'][$clave]['votos_coaliciones_individual'] = $total_votos_individual;
			$datos_casillas[$row['id']]['partidos'][$clave]['votos_totales'] = $datos_casillas[$row['id']]['partidos'][$clave]['votos_individual'] + $datos_casillas[$row['id']]['partidos'][$clave]['votos_coaliciones'] + $datos_casillas[$row['id']]['partidos'][$clave]['votos_coaliciones_individual'] ;


			$ordena_votos_individual[$row['id']][$array['votos']] [] = $clave ;
			$ordena_votos_totales[$row['id']][ $datos_casillas[$row['id']]['partidos'][$clave]['votos_totales'] ] [] = $clave ;

			#$partidos_orden_individual[ $datos_casillas[$row['id']]['partidos'][$clave]['votos_individual'] ][$array['votos']][] = $array['nombre_corto']; 
		}
		//! Importante
		//? Ordenamos los partidos
		krsort($ordena_votos_individual[$row['id']]);
		krsort($ordena_votos_totales[$row['id']]);
		$validador = 0;
		foreach ($ordena_votos_individual[$row['id']] as $votos => $partidos_array) {
			foreach ($partidos_array as $index => $partido) {
				$datos_casillas[$row['id']]['orden_votos_individual']['partidos'][$partido]=$votos;
				$validador = $validador + $votos;
				if(empty($datos_casillas[$row['id']]['orden_votos_individual']['primera_fuerza'])){
					$datos_casillas[$row['id']]['orden_votos_individual']['primera_fuerza'] = $partido;
					if($datos_casillas[$row['id']]['partidos'][$partido]['principal']==1 ){
						$sistema = true;
					}
				}elseif (empty($datos_casillas[$row['id']]['orden_votos_individual']['segunda_fuerza'])  ) {
					$datos_casillas[$row['id']]['orden_votos_individual']['segunda_fuerza'] = $partido;
					if($datos_casillas[$row['id']]['partidos'][$partido]['principal']==1 ){
						$sistema = true;
					}
				}else{
					if($datos_casillas[$row['id']]['partidos'][$partido]['principal'] == 1 && $sistema == false){
						$datos_casillas[$row['id']]['orden_votos_individual']['sistema'] = $partido;
					}
				}
			}
		}
		if($validador <= 0){
			$datos_casillas[$row['id']]['orden_votos_individual']['segunda_fuerza'] = $datos_casillas[$row['id']]['orden_votos_individual']['primera_fuerza'] ='NoData';
		}
		$validador = 0;
		foreach ($ordena_votos_totales[$row['id']] as $votos => $partidos_array) {
			foreach ($partidos_array as $index => $partido) {
				$datos_casillas[$row['id']]['orden_votos_totales']['partidos'][$partido]=$votos;
				$validador = $validador + $votos;
				if(empty($datos_casillas[$row['id']]['orden_votos_totales']['primera_fuerza'])){
					$datos_casillas[$row['id']]['orden_votos_totales']['primera_fuerza'] = $partido;
					$primera_fuerza = $partido;
					if($datos_casillas[$row['id']]['partidos'][$partido]['principal']==1 ){
						$sistema = true;
					}
				}elseif (empty($datos_casillas[$row['id']]['orden_votos_totales']['segunda_fuerza']) && empty($datos_casillas[$row['id']]['partidos'][$partido]['coaliciones_orden_votos_individual'][$primera_fuerza]  )  ) {
					$datos_casillas[$row['id']]['orden_votos_totales']['segunda_fuerza'] = $partido;
					if($datos_casillas[$row['id']]['partidos'][$partido]['principal']==1 ){
						$sistema = true;
					}
				}else{
					if($datos_casillas[$row['id']]['partidos'][$partido]['principal'] == 1 && $sistema == false){
						$datos_casillas[$row['id']]['orden_votos_totales']['sistema'] = $partido;
					}
				}
			}
		}
		if($validador <= 0){
			$datos_casillas[$row['id']]['orden_votos_totales']['segunda_fuerza'] = $datos_casillas[$row['id']]['orden_votos_totales']['primera_fuerza'] ='NoData';
		}
		/*
		if( $datos_casillas[$row['id']]['orden_votos_individual']['primera_fuerza'] == 0 ){
			$datos_casillas[$row['id']]['orden_votos_individual']['segunda_fuerza'] = $datos_casillas[$row['id']]['orden_votos_individual']['primera_fuerza'] ='NoData';
		}
		if( $datos_casillas[$row['id']]['orden_votos_totales']['primera_fuerza'] == 0 ){
			$datos_casillas[$row['id']]['orden_votos_totales']['segunda_fuerza'] = $datos_casillas[$row['id']]['orden_votos_totales']['primera_fuerza'] = 'NoData';
		}
		*/
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
	.div30Reportepartidos {
		width: 33.29%;
		padding: 5px 25px 10px;
		float: left;
	}
	.div33Reporte{
		width: 33%; 
		padding: 5px 10px 10px 10px ; 
		float: left;
	}
	.div50Reporte{
		width: 50%; 
		padding: 5px 25px 10px 25px ; 
		float: left;
	}
	.div100Reporte{
		width: 100%; 
		padding: 5px 25px 5px 25px ; 
		float: left;
		text-align: left;
		text-transform: uppercase;
		letter-spacing: 1px; 
		font-size: 14px;
		font-family: 'Avenir Next';
		font-weight: bold;
		color: white;
		background-color: #464646;
		font-size: 10px;
	}

	@media only screen and (max-width: 1200px) and (min-width: 980px) {
	/* For mobile phones: */
		.div25Reportepartidos{
			width: 25%;
			padding: 5px 5px 10px 5px ; 
		}
		.div30Reportepartidos {
			width: 33.29%;
			padding: 5px 25px 10px;
			float: left;
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
		.div30Reportepartidos {
			width: 33.29%;
			padding: 5px 25px 10px;
			float: left;
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
		.div30Reportepartidos {
			width: 50%;
			padding: 5px 25px 10px;
			float: left;
		}
		.totales,.div50Reporte,.div25Reporte,.div33Reporte{
			width: 100%;
		}
		.div25Reporte,.div50Reporte,.div100Reporte{
			padding: 10px;
		}
	}
	@media only screen and (max-width: 620px) and (min-width: 6px) {
	/* For mobile phones: */
		.totales,.div50Reporte,.div25Reporte,.div25Reportepartidos,.div30Reportepartidos,.div33Reporte{
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
							<td style="text-align: center;padding: 10px;background-color: #464646;color: white" colspan="2">Totales Votaciones</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Válidos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_partidos['votos_validos'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Nulos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_partidos['votos_nulos'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos CAN NREG:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_partidos['votos_can_nreg'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Totales:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_partidos['votos_totales'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Participación Ciudadana:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_partidos['participacion_ciudadana'], 2, '.', ','); ?>%
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
							<td style="text-align: center;padding: 10px;background-color: #464646;color: white" colspan="2">Sección</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Sección:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $datos_partidos['numero'] ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Lista Nominal:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_partidos['lista_nominal'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Casillas:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $datos_partidos['casillas'] ?>
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<hr style="width: 80%;border-top: 1px solid #333333;">
		<div style="background-color: white;padding: 5px;display: table;">
			<!--- fuerzas ----->
			<?php
		if(!empty($datos_partidos['orden_votos_individual']['sistema1'])){
			$value = $datos_partidos['orden_votos_individual']['sistema'];
			$value =$datos_partidos['partidos'][ $value];
		?>
			<div style=" text-align: center;">
				<center>
					<div class="div50ReporteSNF">
						<table
							style="table-layout: fixed; width: 100%"
							cellspacing="0"
							cellpadding="0"
							border="1">
							<thead>
								<tr>
									<tr>
										<td
											style="text-align: center;padding: 10px;background-color: #<?= $value['color_background'] ?>;color: white;font-weight: bold;"
											colspan="2">Sistema</td>
									</tr>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td
										style="text-align: left; width: 25%;padding: 5px 5px 5px 5px;text-align: center;">
										<img src="images/logos_partidos/<?= $value['logo'] ?>" style="width: 40%">
									</td>
									<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px">
										<font class="fontDataReporte" style="font-size: 12px">
											<?= $value['nombre_corto'] ?>
										</font>
									</td>
								</tr>
								<tr>
									<td
										style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
										<font class="fontLabelReporte">Votos individual:</font>
									</td>
									<td
										style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?=number_format($value['votos_individual'], 0, '.', ','); ?>
										</font>
									</td>
								</tr>
								<tr>
									<td
										style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
										<font class="fontLabelReporte">Coaliciones:</font>
									</td>
									<td
										style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
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
									<td
										style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
										<font class="fontLabelReporte">Votos Coalición:</font>
									</td>
									<td
										style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?=number_format($value['votos_coaliciones'], 0, '.', ','); ?>
										</font>
									</td>
								</tr>
								<tr>
									<td
										style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
										<font class="fontLabelReporte">Votos:</font>
									</td>
									<td
										style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?=number_format($value['votos_totales'], 0, '.', ','); ?>
										</font>
									</td>
								</tr>
								<tr>
									<td
										style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
										<font class="fontLabelReporte">Votos %:</font>
									</td>
									<td
										style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
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
		foreach ($datos_partidos['orden_votos_individual'] as $key => $value) {
			if ($key !='sistema' && $key !='partidos'){
				$value =$datos_partidos['partidos'][ $datos_partidos['orden_votos_individual'][$key]];
				?>
			<div class="div50Reporte">
				<table
					style="table-layout: fixed; width: 100%"
					cellspacing="0"
					cellpadding="0"
					border="1">
					<thead>
						<tr>
							<tr>
								<td
									style="text-align: center;padding: 10px;background-color: #<?= $value['color_background'] ?>;color: white;font-weight: bold;"
									colspan="2"><?= strtr($key, "_", " "); ?></td>
							</tr>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 5px 5px 5px 5px;text-align: center;">
								<img src="images/logos_partidos/<?= $value['logo'] ?>" style="width: 40%">
							</td>
							<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $value['nombre_corto'] ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos individual:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_individual'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								colspan="2"
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.1); border-bottom: 1px solid white; height: 135px">
								<font class="fontLabelReporte">Coaliciones:</font>
								<?php
										if(!empty($value['coaliciones_orden_votos_individual'])){
											unset($value['coaliciones_orden_votos_individual'][$value['nombre_corto']]);
											?>
								<table
									style="width: 100%;text-align: left;font-size: 10px;table-layout: fixed;">
									<tr>
										<td colspan="2" style="border:1px solid;padding: 2px;background-color: #dee3ed">Partido</td>
										<td style="border:1px solid;padding: 2px;background-color: #dee3ed">Votos</td>
										<td style="border:1px solid;padding: 2px;background-color: #dee3ed">Diff.</td>
									</tr>
									<?php
												foreach ($value['coaliciones_orden_votos_individual'] as $partido => $votos) {
													echo "<tr>";
													echo "<td style='border:1px solid;padding: 2px 2px 2px 5px;text-align:center'><img src='images/logos_partidos/".$datos_partidos['partidos'][$partido]['logo']."'  style='width: 45%' ></td>";
													$a= "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".$datos_partidos[$valueL]['logo']."</td>";
													echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".$partido."</td>";
													echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($votos, 0, '.', ',')."</td>";
													echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".number_format($value['votos_individual']-$votos, 0, '.', ',')."</td>";
													echo "</tr>";
												}
												?>
								</table>
							<?php
										}else{
											echo ' <font class="fontDataReporte" style="font-size: 12px">';
											echo "No tiene.";
											echo ' </font>';
										}
										#echo "<pre>";
										#var_dump($value);
										#echo "</pre>";
									?>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos Coalición Ind:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_coaliciones_individual'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos Coalición Boleta:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_coaliciones'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos Totales:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_totales'], 0, '.', ','); ?>
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


		foreach ($datos_partidos['orden_votos_individual']['partidos'] as $key => $valueT) {
			$value = $datos_partidos['partidos'][$key];
			$nombre_corto = str_replace("_"," - ",$value['nombre_corto']);
			//$total = $partido_votos_porcentaje + $total;
			?>

		<div class="div30Reportepartidos" style="padding: 10px">
			<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
				<thead>
					<tr>
						<td
							colspan="3"
							style="text-align: center;padding: 10px;background-color: #<?= $value['color_background'] ?>;color: white; height: 60px">
							<?=  str_replace("_"," - ",$value['nombre_corto']) ?>
						</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td
							rowspan="2"
							style="text-align: center;padding: 5px 5px 5px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<img src="images/logos_partidos/<?= $value['logo'] ?>" style="width: 60px">
						</td>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Votos Individual:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								<?=number_format($value['votos_individual'], 0, '.', ','); ?>
							</font>
						</td>
					</tr>
					<tr>
						<td
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Votos Coalición Ind:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								<?=number_format($value['votos_coaliciones_individual'], 0, '.', ','); ?>
							</font>
						</td>
					</tr>
					<tr>
						<td
							colspan="2"
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Votos Coalición Boleta:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								<?=number_format($value['votos_coaliciones'], 0, '.', ','); ?>
							</font>
						</td>
					</tr>
					<tr>
						<td
							colspan="2"
							style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
							<font class="fontLabelReporte">Votos Totales:</font>
						</td>
						<td
							style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
							<font class="fontDataReporte" style="font-size: 12px">
								<?=number_format($value['votos_totales'], 0, '.', ','); ?>
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
		<hr style="width: 80%;border-top: 1px solid #d23026;">
		<?php
		foreach ($datos_casillas as $key => $value) {
			?>
			<div class="div100Reporte">
				<font style="font-weight: initial;">Tipo Casilla</font> <?= tipo_casillaNombre($value['id_tipo_casilla']) ?><br>
				<font style="font-weight: initial;">Casilla</font> <?= $value['codigo'] ?> <br>
			</div>
			<div class="div33Reporte">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td style="text-align: center;padding: 10px;background-color: #191919;color: white" colspan="2">Casilla</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Válidos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_validos'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Lista Nulos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_nulos'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos CAN NREG:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_can_nreg'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Totales:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_totales'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Lista Nominal:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['lista_nominal'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Totales:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['participacion_ciudadana'], 2, '.', ','); ?>%
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<!--- fuerzas ----->
			<?php
			if(!empty($value['orden_votos_individual']['sistema'])){
				$primera_fuerza = $datos_partidos['orden_votos_individual']['sistema'];
				if($primera_fuerza=='NoData'){
					$datos_segunda_fuerza = $datos_primera_fuerza =$no_data['NoData'];
				}else{
					$datos_primera_fuerza = $value['partidos'][$primera_fuerza];
					$datos_segunda_fuerza = $value['partidos'][$segunda_fuerza];
				}
				?>
				<div style=" text-align: center;">
					<center>
						<div class="div50ReporteSNF">
							<table
								style="table-layout: fixed; width: 100%"
								cellspacing="0"
								cellpadding="0"
								border="1">
								<thead>
									<tr>
										<tr>
											<td
												style="text-align: center;padding: 10px;background-color: #<?= $datos_primera_fuerza['color_background'] ?>;color: white;font-weight: bold;"
												colspan="2">Sistema</td>
										</tr>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td
											style="text-align: left; width: 25%;padding: 5px 5px 5px 5px;text-align: center;">
											<img src="images/logos_partidos/<?= $datos_primera_fuerza['logo'] ?>" style="width: 40%">
										</td>
										<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px">
											<font class="fontDataReporte" style="font-size: 12px">
												<?= $datos_primera_fuerza['nombre_corto'] ?>
											</font>
										</td>
									</tr>
									<tr>
										<td
											style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
											<font class="fontLabelReporte">Votos individual:</font>
										</td>
										<td
											style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?=number_format($datos_primera_fuerza['votos_individual'], 0, '.', ','); ?>
											</font>
										</td>
									</tr>
									<tr>
										<td
											style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
											<font class="fontLabelReporte">Coaliciones:</font>
										</td>
										<td
											style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?php
													unset($datos_primera_fuerza['coaliciones'][$datos_primera_fuerza['nombre_corto']]);
													$fields_pdo = implode(', ', array_keys($datos_primera_fuerza['coaliciones']));
													echo $fields_pdo;
													?>
											</font>
										</td>
									</tr>
									<tr>
										<td
											style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
											<font class="fontLabelReporte">Votos Coalición:</font>
										</td>
										<td
											style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?=number_format($datos_primera_fuerza['votos_coaliciones'], 0, '.', ','); ?>
											</font>
										</td>
									</tr>
									<tr>
										<td
											style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
											<font class="fontLabelReporte">Votos:</font>
										</td>
										<td
											style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?=number_format($datos_primera_fuerza['votos_totales'], 0, '.', ','); ?>
											</font>
										</td>
									</tr>
									<tr>
										<td
											style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
											<font class="fontLabelReporte">Votos %:</font>
										</td>
										<td
											style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
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
				foreach ($value['orden_votos_individual'] as $keyR => $partido) {
					if ($keyR !='sistema' && $keyR !='partidos'){
						$fuerza = $partido;
						if($fuerza=='NoData'){
							$datos_fuerza = $fuerza =$no_data['NoData'];
						}else{
							$datos_fuerza = $value['partidos'][$fuerza]; 
						}
						?>
						<div class="div33Reporte">
							<table
								style="table-layout: fixed; width: 100%"
								cellspacing="0"
								cellpadding="0"
								border="1">
								<thead>
									<tr>
										<tr>
											<td
												style="text-align: center;padding: 10px;background-color: #<?= $datos_fuerza['color_background'] ?>;color: white;font-weight: bold;"
												colspan="2"><?= strtr($keyR, "_", " "); ?></td>
										</tr>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td
											style="text-align: left; width: 25%;padding: 5px 5px 5px 5px;text-align: center;">
											<img src="images/logos_partidos/<?= $datos_fuerza['logo'] ?>" style="width: 40%">
										</td>
										<td style="text-align: center; width: 25%;padding: 0px 5px 0px 5px">
											<font class="fontDataReporte" style="font-size: 12px">
												<?= $datos_fuerza['nombre_corto'] ?>
											</font>
										</td>
									</tr>
									<tr>
										<td
											style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
											<font class="fontLabelReporte">Votos individual:</font>
										</td>
										<td
											style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?=number_format($datos_fuerza['votos_individual'], 0, '.', ','); ?>
											</font>
										</td>
									</tr>
									<tr>
										<td
											style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
											<font class="fontLabelReporte">Votos Coalición Ind:</font>
										</td>
										<td
											style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?=number_format($datos_fuerza['votos_coaliciones_individual'], 0, '.', ','); ?>
											</font>
										</td>
									</tr>
									<tr>
										<td
											style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
											<font class="fontLabelReporte">Votos Coalición Boleta:</font>
										</td>
										<td
											style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?=number_format($datos_fuerza['votos_coaliciones'], 0, '.', ','); ?>
											</font>
										</td>
									</tr>
									<tr>
										<td
											style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
											<font class="fontLabelReporte">Votos Totales:</font>
										</td>
										<td
											style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
											<font class="fontDataReporte" style="font-size: 12px">
												<?=number_format($datos_fuerza['votos_totales'], 0, '.', ','); ?>
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
			<div class="div100Reporte" style="text-align: center;background-color: white;color: black">
				Partidos
				<hr>
				<?php
				foreach ($value['orden_votos_individual']['partidos'] as $partido => $valuePartido) {
					$valuePartido = $value['partidos'][$partido];
					?>
					<div class="div33Reporte" style="padding: 10px">
						<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
							<thead>
								<tr>
									<td colspan="3" style="text-align: center;padding: 10px;background-color: #<?= $valuePartido['color_background'] ?>;color: white" colspan="2">
										<?=  str_replace("_"," - ",$valuePartido['nombre_corto']).$valor ?>
									</td>
								</tr>
							</thead>
							<tbody>
								<tr>
									<td rowspan="2" style="text-align: center;padding: 5px 5px 5px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
										<img src="images/logos_partidos/<?= $valuePartido['logo'] ?>" style="width: 60px">
									</td>
									<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
										<font class="fontLabelReporte">Votos ind:</font>
									</td>
									<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?=number_format($valuePartido['votos_individual'], 0, '.', ','); ?>
										</font>
									</td>
								</tr>
								<tr>
									<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
										<font class="fontLabelReporte">Votos Coali Ind:</font>
									</td>
									<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?=number_format($valuePartido['votos_coaliciones_individual'], 0, '.', ','); ?>
										</font>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
										<font class="fontLabelReporte">Votos Coali Boleta:</font>
									</td>
									<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?=number_format($valuePartido['votos_coaliciones'], 0, '.', ','); ?>
										</font>
									</td>
								</tr>
								<tr>
									<td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
										<font class="fontLabelReporte">Votos Totales:</font>
									</td>
									<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?=number_format($valuePartido['votos_totales'], 0, '.', ','); ?>
										</font>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<?php
				}
				?>
			</div>
			<?php
		}
		?>
	</div>
</div>