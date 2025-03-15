<?php
	//votos validos, votos_nulos, votos canreg
	if(!empty($_POST['searchTable'][0])){
		include __DIR__."/../../functions/db.php";
		$id_municipio = $_POST['searchTable'][0]['id_municipio'];
		$id_municipio = $_POST['searchTable'][0]['id_municipio'];
		$id_seccion_ine = $_POST['searchTable'][0]['id_seccion_ine'];
		$id_seccion_ine = explode(',', $id_seccion_ine);
		$id_seccion_ine = array_filter($id_seccion_ine, 'strlen');
		$id_seccion_ine = implode(',', $id_seccion_ine);

		$id_distrito_local = $_POST['searchTable'][0]['id_distrito_local'];
		$id_distrito_local = explode(',', $id_distrito_local);
		$id_distrito_local = array_filter($id_distrito_local, 'strlen');
		$id_distrito_local = implode(',', $id_distrito_local);

		$id_distrito_federal = $_POST['searchTable'][0]['id_distrito_federal'];
		$id_distrito_federal = explode(',', $id_distrito_federal);
		$id_distrito_federal = array_filter($id_distrito_federal, 'strlen');
		$id_distrito_federal = implode(',', $id_distrito_federal);

		$tipo_seccion = strval($_POST['searchTable'][0]['tipo_seccion']);
		if($tipo_seccion == "0" || $tipo_seccion == "1" ){
			$sqlSeccionesIneCVTipo = " AND EXISTS (SELECT * FROM secciones_ine  WHERE secciones_ine.id = casillas_votos_2016.id_seccion_ine AND secciones_ine.tipo = '{$tipo_seccion}' )  ";
			$sqlSeccionesIneTipo = " AND tipo = '{$tipo_seccion}' ";
			$sqlSeccionesIneSCTipo = " AND EXISTS (SELECT * FROM secciones_ine  WHERE secciones_ine.id = secciones_ine_ciudadanos.id_seccion_ine AND secciones_ine.tipo = '{$tipo_seccion}' )  ";
			$sqlSeccionesIneCVPTipo = " AND EXISTS (SELECT * FROM secciones_ine  WHERE secciones_ine.id = casillas_votos_partidos_2016.id_seccion_ine AND secciones_ine.tipo = '{$tipo_seccion}' )  ";
			$sqlSeccionesIneCVPTipoS = " AND EXISTS (SELECT * FROM secciones_ine s  WHERE s.id = cvp.id_seccion_ine AND s.tipo = '{$tipo_seccion}' )  ";
		}
		if(!empty($id_seccion_ine)){
			$sqlSeccionesIne = " AND id_seccion_ine IN ($id_seccion_ine) ";
			$sqlSeccionesIneSimple = " AND id IN ($id_seccion_ine) ";
			$sqlSeccionesIneCVP = " AND cvp.id_seccion_ine IN ($id_seccion_ine) ";
			
		}
		if(!empty($id_distrito_local)){
			$sqlDistritoLocal = " AND id_distrito_local IN ($id_distrito_local) ";
			$sqlDistritoLocalCVP = " AND cvp.id_distrito_local IN ($id_distrito_local) ";
			
		}
		if(!empty($id_distrito_federal)){
			$sqlDistritoFederal = " AND id_distrito_federal IN ($id_distrito_federal) ";
			$sqlDistritoFederalCVP = " AND cvp.id_distrito_federal IN ($id_distrito_federal) ";
			
		}
		$sqlDistritos = $sqlDistritoLocal.$sqlDistritoFederal;
		$sqlDistritosCVP = $sqlDistritoLocalCVP.$sqlDistritoFederalCVP;
	}
	$tipo = 2;
	$sql = "SELECT 
				t.votos_validos,
				(SELECT SUM(votos_nulos) FROM casillas_votos_2016 WHERE id_municipio={$id_municipio} AND tipo = '{$tipo}' {$sqlSeccionesIne} {$sqlDistritos} {$sqlSeccionesIneCVTipo} ) votos_nulos,
				(SELECT SUM(votos_can_nreg) FROM casillas_votos_2016 WHERE id_municipio={$id_municipio} AND tipo = '{$tipo}' {$sqlSeccionesIne} {$sqlDistritos} {$sqlSeccionesIneCVTipo} ) votos_can_nreg,
				(SELECT COUNT(id) FROM secciones_ine WHERE id_municipio={$id_municipio} {$sqlSeccionesIneSimple} {$sqlDistritos} {$sqlSeccionesIneTipo} ) secciones,
				(SELECT COUNT(id) FROM casillas_votos_2016 WHERE id_municipio={$id_municipio} AND tipo = '{$tipo}' {$sqlSeccionesIne} {$sqlDistritos} {$sqlSeccionesIneCVTipo} ) casillas,
				(SELECT SUM(lista_nominal) FROM casillas_votos_2016 WHERE id_municipio={$id_municipio} AND tipo = '{$tipo}' {$sqlSeccionesIne} {$sqlDistritos} {$sqlSeccionesIneCVTipo} ) total_lista_nominal,
				(SELECT COUNT(id) FROM secciones_ine_ciudadanos WHERE id_municipio={$id_municipio} {$sqlSeccionesIne} {$sqlDistritos} {$sqlSeccionesIneSCTipo} ) ciudadanos_totales,
				(SELECT GROUP_CONCAT(DISTINCT id_distrito_local) AS id_distrito_local FROM secciones_ine WHERE id_municipio={$id_municipio} {$sqlSeccionesIneSimple} {$sqlDistritos} {$sqlSeccionesIneTipo} ) distritos_locales,
				(SELECT GROUP_CONCAT(DISTINCT id_distrito_federal) AS id_distrito_federal FROM secciones_ine WHERE id_municipio={$id_municipio} {$sqlSeccionesIneSimple} {$sqlDistritos} {$sqlSeccionesIneTipo} ) distritos_federales
			FROM
				(SELECT SUM(votos) votos_validos FROM  casillas_votos_partidos_2016 WHERE id_municipio={$id_municipio} AND tipo =  '{$tipo}' {$sqlSeccionesIne} {$sqlDistritos} {$sqlSeccionesIneCVPTipo} )t


	";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$votos_nulos = $row['votos_nulos'];
	$votos_can_nreg = $row['votos_can_nreg'];
	$votos_validos = $row['votos_validos'];
	$votos_totales = $votos_nulos + $votos_can_nreg + $votos_validos;
	$total_lista_nominal = $row['total_lista_nominal'];

	$secciones = $row['secciones'];
	$casillas = $row['casillas'];
	$distritos_locales = $row['distritos_locales'];
	$distritos_federales = $row['distritos_federales'];


	//aqui cambrano
	$ciudadanos_totales = $row['ciudadanos_totales'];
	$ciudadanos_totales_porcentaje = ($ciudadanos_totales/$total_lista_nominal)*100;

	$ciudadanos_lista_nominal_porcentaje = ($ciudadanos_totales / $total_lista_nominal) * 100;
	//$ciudadanos_lista_nominal_porcentaje = truncar($ciudadanos_lista_nominal_porcentaje,2);

	$total_lista_abstencion = $total_lista_nominal - $votos_totales;
	if($total_lista_nominal==0){
		$participacion_ciudadana=0;
		$abstencion_ciudadana=0;
	}else{
		$participacion_ciudadana = ($votos_totales / $total_lista_nominal )*100;
		$abstencion_ciudadana = ($total_lista_abstencion / $total_lista_nominal )*100;
	}

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
			p.principal
		FROM partidos_2016 p
		LEFT JOIN casillas_votos_partidos_2016 cvp
		ON p.id = cvp.id_partido_2016
		WHERE cvp.id_municipio={$id_municipio} AND cvp.tipo = '{$tipo}' {$sqlSeccionesIneCVP}  {$sqlDistritosCVP} {$sqlSeccionesIneCVPTipoS}
		GROUP BY cvp.id_partido_2016 
	";
	$result = $conexion->query($sql); 
	$num=0; 
	while($row=$result->fetch_assoc()){
		if($row['clave_partidos_coaliciones'] == ''){
			unset($row['clave_partidos_coaliciones']);
		}
		if($row['principal'] == ''){
			unset($row['principal']);
		}
		#$datos_partidos[$num]=$row;
		if($row['clave_partidos_coaliciones'] != ''){
			$partidos_coaliciones[$row['clave_partidos_coaliciones']]=$row;
		}else{
			$partidos_sin_coaliciones[$row['clave']]=$row;
		} 
		$num=$num+1;
	}


	//? Tomamos como princial el partido sin coalicion
	foreach ($partidos_sin_coaliciones as $clave => $array) {
		//? Colocamos en 0 la suma de coalciones para que no se sume con los demas
		//? El arreglo de coalciones lo vaciamos para que no se agregen de los demas partidos
		$sum_coaliciones = 0;
		unset($coaliciones); 
		unset($coalicion_orden_individual);
		foreach ($partidos_coaliciones as $nombre_corto => $arraysc) {
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
                    $coaliciones[$votos] = $partidos_sin_coaliciones[$votos];
                    //! Importante
                    //? Buscamos si existe en el arrey para que no se repita
                    //* votos == nombre del partido segun la coalicion
                    //* [] == colocamos arreglo vacio por que puede ser que uno o mas partidos tengan los mismos votos
                    #$coalicion_orden_individual[$clave][$nombre_corto][ $partidos_sin_coaliciones[$votos]['votos'] ][]=$votos;
                    $search_coalicion = array_search($votos, $coalicion_orden_individual[$partidos_sin_coaliciones[$votos]['votos'] ]);
                    if($search_coalicion === NULL){
                        $coalicion_orden_individual[$partidos_sin_coaliciones[$votos]['votos'] ][]= $votos;
                    }
                }
                $sum_coaliciones = $sum_coaliciones + $arraysc['votos'];
            }
        }

		//? Nuestro Principal arreglo
		//* clave == nombre del partido
		$datos_partidos['partidos'][$clave]['id'] = $array['id'];
		$datos_partidos['partidos'][$clave]['clave'] = $clave;
		$datos_partidos['partidos'][$clave]['nombre_corto'] = $array['nombre_corto'];
		$datos_partidos['partidos'][$clave]['nombre'] = $array['nombre'];
		$datos_partidos['partidos'][$clave]['principal'] = $array['principal'];
		$datos_partidos['partidos'][$clave]['logo'] = $array['logo'];
		$datos_partidos['partidos'][$clave]['color_border'] = $array['color_border'];
		$datos_partidos['partidos'][$clave]['color_background'] = $array['color_background'];

		$datos_partidos['partidos'][$clave]['votos_individual'] = $array['votos'];
		$datos_partidos['partidos'][$clave]['coaliciones_sin_orden'] = $coaliciones;
		$datos_partidos['partidos'][$clave]['votos_coaliciones'] = $sum_coaliciones;

		//! Importante
		//? Ordenamos las coaliciones por votos en individual
		$total_votos_individual = 0;
		krsort($coalicion_orden_individual);
		foreach ($coalicion_orden_individual as $votos => $partidos_array) {
			foreach ($partidos_array as $index => $partido) {
				$datos_partidos['partidos'][$clave]['coaliciones_orden_votos_individual'][$partido]=$votos;
				if($clave != $partido){
					$total_votos_individual = $total_votos_individual + $votos;
				}
			}
		}
		$datos_partidos['partidos'][$clave]['votos_coaliciones_individual'] = $total_votos_individual;
		$datos_partidos['partidos'][$clave]['votos_totales'] = $datos_partidos['partidos'][$clave]['votos_individual'] + $datos_partidos['partidos'][$clave]['votos_coaliciones'] + $datos_partidos['partidos'][$clave]['votos_coaliciones_individual'] ;


		$ordena_votos_individual[$array['votos']] [] = $clave ;
		$ordena_votos_totales[ $datos_partidos['partidos'][$clave]['votos_totales'] ] [] = $clave ;

		#$partidos_orden_individual[ $datos_partidos['partidos'][$clave]['votos_individual'] ][$array['votos']][] = $array['nombre_corto']; 
	}

	//! Importante
	//? Ordenamos los partidos
	krsort($ordena_votos_individual);
	krsort($ordena_votos_totales);

	foreach ($ordena_votos_individual as $votos => $partidos_array) {
		foreach ($partidos_array as $index => $partido) {
			$datos_partidos['orden_votos_individual']['partidos'][$partido]=$votos;
			if(empty($datos_partidos['orden_votos_individual']['primera_fuerza'])){
				$datos_partidos['orden_votos_individual']['primera_fuerza'] = $partido;
				if($datos_partidos['partidos'][$partido]['principal']==1 ){
					$sistema = true;
				}
			}elseif (empty($datos_partidos['orden_votos_individual']['segunda_fuerza'])  ) {
				$datos_partidos['orden_votos_individual']['segunda_fuerza'] = $partido;
				if($datos_partidos['partidos'][$partido]['principal']==1 ){
					$sistema = true;
				}
			}else{
				if($datos_partidos['partidos'][$partido]['principal'] == 1 && $sistema == false){
					$datos_partidos['orden_votos_individual']['sistema'] = $partido;
				}
			}
		}
	}
	foreach ($ordena_votos_totales as $votos => $partidos_array) {
		foreach ($partidos_array as $index => $partido) {
			$datos_partidos['orden_votos_totales']['partidos'][$partido]=$votos;
			if(empty($datos_partidos['orden_votos_totales']['primera_fuerza'])){
				$datos_partidos['orden_votos_totales']['primera_fuerza'] = $partido;
				$primera_fuerza = $partido;
				if($datos_partidos['partidos'][$partido]['principal']==1 ){
					$sistema = true;
				}
			}elseif (empty($datos_partidos['orden_votos_totales']['segunda_fuerza']) && empty($datos_partidos['partidos'][$partido]['coaliciones_orden_votos_individual'][$primera_fuerza]  )  ) {
				$datos_partidos['orden_votos_totales']['segunda_fuerza'] = $partido;
				if($datos_partidos['partidos'][$partido]['principal']==1 ){
					$sistema = true;
				}
			}else{
				if($datos_partidos['partidos'][$partido]['principal'] == 1 && $sistema == false){
					$datos_partidos['orden_votos_totales']['sistema'] = $partido;
				}
			}
		}
	}



?>
<style type="text/css">
	.totales {
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
	.div25Reporte {
		width: 25%;
		padding: 5px 25px 10px;
		float: left;
	}
	.div25Reportepartidos {
		width: 25%;
		padding: 5px 25px 10px;
		float: left;
	}
	.div30Reportepartidos {
		width: 33.29%;
		padding: 5px 25px 10px;
		float: left;
	}
	.div33Reporte {
		width: 33%;
		padding: 5px 25px 10px;
		float: left;
	}
	.div50Reporte {
		width: 50%;
		padding: 5px 25px 10px;
		float: left;
	}
	.div50ReporteSNF {
		width: 50%;
		padding: 5px 25px 10px;
	}
	.div100Reporte {
		width: 100%;
		padding: 5px 25px 10px;
		float: left;
	}
	.grafica_barras_horizontales {
		width: 100%;
		height: 128.5px;
		display: block;
		padding: 10px;
	}

	@media only screen and (max-width: 1200px) and (min-width: 980px) {
		/* For mobile phones: */
		.div25Reportepartidos {
			width: 25%;
			padding: 5px 5px 10px;
		}
		.div30Reportepartidos {
			width: 33.29%;
			padding: 5px 25px 10px;
			float: left;
		}
		.div25Reporte {
			width: 50%;
		}
		.div100Reporte,
		.div25Reporte,
		.div50Reporte,
		.div50ReporteSNF {
			padding: 10px;
		}
		.grafica_barras_horizontales {
			width: 100%;
			height: 88.5px;
			display: table;
		}
	}
	@media only screen and (max-width: 980px) and (min-width: 761px) {
		/* For mobile phones: */
		.div25Reportepartidos {
			width: 33%;
			padding: 5px 5px 10px;
		}
		.div30Reportepartidos {
			width: 33.29%;
			padding: 5px 25px 10px;
			float: left;
		}
		.div25Reporte {
			width: 50%;
		}
		.div100Reporte,
		.div25Reporte,
		.div50Reporte,
		.div50ReporteSNF {
			padding: 10px;
		}
		.grafica_barras_horizontales {
			width: 100%;
			height: 88.5px;
			display: table;
		}
	}

	@media only screen and (max-width: 760px) and (min-width: 600px) {
		/* For mobile phones: */
		.div25Reportepartidos {
			width: 50%;
			padding: 5px 5px 10px;
		}
		.div30Reportepartidos {
			width: 50%;
			padding: 5px 25px 10px;
			float: left;
		}
		.div25Reporte,
		.div50Reporte,
		.div50ReporteSNF,
		.totales {
			width: 100%;
		}
		.div100Reporte,
		.div25Reporte,
		.div50Reporte,
		.div50ReporteSNF {
			padding: 10px;
		}
		.grafica_barras_horizontales {
			width: 100%;
			height: 88.5px;
			display: table;
		}
	}
	@media only screen and (max-width: 620px) and (min-width: 6px) {
		/* For mobile phones: */
		.div25Reporte,
		.div25Reportepartidos,
		.div30Reportepartidos,
		.div50Reporte,
		.div50ReporteSNF,
		.totales {
			width: 100%;
		}
		.div100Reporte,
		.div25Reporte,
		.div50Reporte,
		.div50ReporteSNF {
			padding: 10px;
		}
		.grafica_barras_horizontales {
			width: 100%;
			height: 108.5px;
			display: block;
		}
	}
</style>
<div class="totales">
	<div
		style="width: 100%;display: table;padding: 5px 5px 5px 0px;background-color: white">
		<div style="background-color: white;padding: 5px;display: table;">
			<div class="div50Reporte">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0">
					<thead>
						<tr>
							<td
								style="text-align: center;padding: 10px;background-color: #191919;color: white"
								colspan="2">Totales Votaciones</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos Válidos:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_validos, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos Nulos:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_nulos, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos CAN NREG:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($votos_can_nreg, 0, '.', ','); ?>
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
									<?=number_format($votos_totales, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Participación Ciudadana:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
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
							<td
								style="text-align: center;padding: 10px;background-color: #191919;color: white"
								colspan="2">Cartografía</td>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Distritos Local(es):</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $distritos_locales ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Distritos Federal(es):</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?= $distritos_federales ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Lista Nominal:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($total_lista_nominal, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Seccion(es):</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($secciones, 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Casilla(s):</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($casillas, 0, '.', ','); ?>
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
		if(!empty($datos_partidos['orden_votos_individual']['sistema'])){
			$sistema = $datos_partidos['orden_votos_individual']['sistema'];
			$value = $datos_partidos['partidos'][$sistema];
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
															echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".$datos_partidos['partidos'][$partido]['nombre_corto']."</td>";
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
													echo "<td style='border:1px solid;padding: 2px 2px 2px 5px'>".$datos_partidos['partidos'][$partido]['nombre_corto']."</td>";
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
	</div>
</div>