<?php
	//votos validos, votos_nulos, votos canreg
	
	$configuracion_matriz_rentabilidad_secciones_ine_2024Datos = configuracion_matriz_rentabilidad_secciones_ine_2024Datos();

	$votos_semaforo_amarillo = $configuracion_matriz_rentabilidad_secciones_ine_2024Datos['votos_semaforo_amarillo'];
	$id_tipo_categoria_ciudadano = $configuracion_matriz_rentabilidad_secciones_ine_2024Datos['id_tipo_categoria_ciudadano'] ;// funcionario
	$id_partido_2024 = $configuracion_matriz_rentabilidad_secciones_ine_2024Datos['id_partido_2024_distrito_federal'];// Partidos 2024 PRI
	//$id_partido_2024 = $configuracion_matriz['id_partido_2024'] = '1';// Partidos 2024
	$id_partido_legado = $configuracion_matriz_rentabilidad_secciones_ine_2024Datos['id_partido_legado'];// Partidos Legados
	#$tipo = $configuracion_matriz_rentabilidad_secciones_ine_2024Datos['tipo_eleccion'] = '0';// 0 - Ayuntamiento | 1 - Municipio | 2 - Distrito Federal
	/// en el formulario segun el tipo sera lo que te va mostrar el select sera un onchange para que cambie funcionara igual que el de localidades y municipio el principal seria tipo_eleccion y segun lo que escojas sera los partidos que te salgan 

	$perido_fecha_inicial = $_COOKIE['fecha_inicial'];
	$perido_fecha_final = $_COOKIE['fecha_final'];

	if($perido_fecha_inicial != '' && $perido_fecha_final != ''){
		$sql_secciones_ine_ciudadanos = " AND DATE(fechaR) BETWEEN '{$perido_fecha_inicial}' AND '{$perido_fecha_final}'";

		$sql_programas_apoyos = " AND sicpa.fecha BETWEEN '{$perido_fecha_inicial}' AND '{$perido_fecha_final}' ";

		$sql_actividades = " AND sia.fecha_inicio BETWEEN '{$perido_fecha_inicial}' AND '{$perido_fecha_final}' ";

		$sql_grupos = " AND sig.fecha BETWEEN '{$perido_fecha_inicial}' AND '{$perido_fecha_final}' ";

		$sql_giras = " AND sig.fecha BETWEEN '{$perido_fecha_inicial}' AND '{$perido_fecha_final}' ";

	}elseif($perido_fecha_inicial != '' && $perido_fecha_final == ''){
		$sql_secciones_ine_ciudadanos = " AND DATE(fechaR) <= '{$perido_fecha_inicial}' ";

		$sql_programas_apoyos = " AND sicpa.fecha <= '{$perido_fecha_inicial}' ";

		$sql_actividades = " AND sia.fecha_inicio <= '{$perido_fecha_inicial}' ";

		$sql_grupos = " AND sig.fecha <= '{$perido_fecha_inicial}' ";

		$sql_giras = " AND sig.fecha <= '{$perido_fecha_inicial}' ";

	}elseif($perido_fecha_inicial == '' && $perido_fecha_final != ''){
		$sql_secciones_ine_ciudadanos = " AND DATE(fechaR) >= '{$perido_fecha_final}' ";

		$sql_programas_apoyos = " AND sicpa.fecha >= '{$perido_fecha_final}' ";

		$sql_actividades = " AND sia.fecha_inicio >= '{$perido_fecha_final}' ";

		$sql_grupos = " AND  sig.fecha >= '{$perido_fecha_final}' ";

		$sql_giras = " AND sig.fecha >= '{$perido_fecha_final}' ";

	}else{
		$sql_secciones_ine_ciudadanos = "";

		$sql_programas_apoyos = "";

		$sql_actividades = "";

		$sql_grupos = "";

		$sql_giras = "";
	}

	$sql = "SELECT 
				t.votos_validos,
				(SELECT SUM(votos_nulos) FROM casillas_votos_2024 WHERE id_municipio={$id_municipio} AND tipo = '{$tipo}' ) votos_nulos,
				(SELECT SUM(votos_can_nreg) FROM casillas_votos_2024 WHERE id_municipio={$id_municipio} AND tipo = '{$tipo}' ) votos_can_nreg,
				(SELECT COUNT(id) FROM secciones_ine WHERE id_municipio={$id_municipio} ) secciones,
				(SELECT COUNT(id) FROM casillas_votos_2024 WHERE id_municipio={$id_municipio} AND tipo = '{$tipo}' ) casillas,
				(SELECT SUM(lista_nominal) FROM casillas_votos_2024 WHERE id_municipio={$id_municipio} AND tipo = '{$tipo}' ) total_lista_nominal,
				(SELECT COUNT(id) FROM secciones_ine_ciudadanos WHERE id_municipio={$id_municipio} {$sql_secciones_ine_ciudadanos} ) ciudadanos_totales,

				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sicpa.id_seccion_ine_ciudadano = sic.id WHERE sic.id_municipio={$id_municipio} {$sql_programas_apoyos} ) apoyos_programas,
				(SELECT COUNT(*) FROM secciones_ine_actividades sia LEFT JOIN secciones_ine si ON sia.id_seccion_ine = si.id WHERE si.id_municipio={$id_municipio} {$sql_actividades} ) acciones_obras,
				(SELECT COUNT(*) FROM secciones_ine_grupos sig LEFT JOIN secciones_ine si ON sig.id_seccion_ine = si.id WHERE si.id_municipio={$id_municipio} {$sql_grupos} ) grupos_interes,
				(SELECT COUNT(*) FROM militantes_partidos mp LEFT JOIN secciones_ine_ciudadanos sic ON mp.id_seccion_ine_ciudadano = sic.id WHERE sic.id_municipio={$id_municipio} AND mp.id_partido_legado = '{$id_partido_legado}') militantes,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_municipio={$id_municipio} AND sicc.id_tipo_categoria_ciudadano = '{$id_tipo_categoria_ciudadano}') funcionarios,

				(SELECT COUNT(*) FROM secciones_ine_giras sig WHERE sig.id_municipio = {$id_municipio} AND sig.tipo = 'junta' {$sql_giras}  ) juntas,
				(SELECT COUNT(*) FROM secciones_ine_giras sig WHERE sig.id_municipio = {$id_municipio} AND sig.tipo = 'visita' {$sql_giras}  ) visitas,
				(SELECT COUNT(*) FROM secciones_ine_giras sig WHERE sig.id_municipio = {$id_municipio} AND sig.tipo = 'caminata' {$sql_giras}  ) caminatas
			FROM
				(SELECT SUM(votos) votos_validos FROM  casillas_votos_partidos_2024 WHERE id_municipio={$id_municipio} AND tipo =  '{$tipo}' )t
	";
	//echo "<pre>".$sql."</pre>";
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();

	$datos_generales['votos_totales'] =  $row['votos_nulos'] + $row['votos_can_nreg'] + $row['votos_validos'];
	$datos_generales['participacion_ciudadana'] = $datos_generales['votos_totales'] / $row['total_lista_nominal'] * 100;
	$datos_generales['territorio_tipo'] = $tipo;
	$datos_generales['territorio_nombre'] = $municipioNombre;
	$datos_generales['ano'] = $ano;
	
	$datos_generales = $row + $datos_generales;
	$rutaEfs = rutaEfs();
	$json_data = json_encode($datos_generales);//Json donde se guarda la informacion para evitar las variables de sesion
	if ($json_data === false) {
		// Manejar el error de codificación JSON si es necesario
		//echo "Error al codificar el JSON";
	} else {
		// Guarda el JSON en un archivo
		$archivo_json = $rutaEfs.'datos_generales_forzar_distrito_federal_2024_'.$id_municipio.'-'.$_COOKIE["id_usuario"].'.json';
		if (file_put_contents($archivo_json, $json_data)) {
			//echo "Los datos se han guardado en $archivo_json";
		} else {
			//echo "Error al guardar los datos en $archivo_json";
		}
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
		FROM partidos_2024 p
		LEFT JOIN casillas_votos_partidos_2024 cvp
		ON p.id = cvp.id_partido_2024
		WHERE cvp.id_municipio={$id_municipio} AND cvp.tipo = '{$tipo}'
		GROUP BY cvp.id_partido_2024 
	";
	$sql;
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
			$pos = strpos($nombre_corto, $array['clave']);
			if ($pos !== false ) {
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
	//? Ordenamos los partidos Mayor a Menor
	krsort($ordena_votos_individual);
	krsort($ordena_votos_totales);

	foreach ($ordena_votos_individual as $votos => $partidos_array) {
		foreach ($partidos_array as $index => $partido) {
			$datos_partidos['orden_votos_individual']['partidos'][$partido]=$votos;

			$datos_partidos['orden_votos_individual']['partidos'][$partido]=$votos;
			$datos_partidos['orden_votos_individual']['graficas']['partidos'][] = $partido;
			$datos_partidos['orden_votos_individual']['graficas']['votos'][] = $votos;
			$datos_partidos['orden_votos_individual']['graficas']['background'][] = '#'.$datos_partidos['partidos'][$partido]['color_background'];

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
	$primera_fuerza = $datos_partidos['orden_votos_individual']['primera_fuerza'];
	$primera_fuerza_votos = $datos_partidos['orden_votos_individual']['partidos'][$primera_fuerza];
	$segunda_fuerza = $datos_partidos['orden_votos_individual']['segunda_fuerza'];
	$segunda_fuerza_votos = $datos_partidos['orden_votos_individual']['partidos'][$segunda_fuerza];

	$partido_sistema;
	$partido_sistema_votos = $datos_partidos['orden_votos_individual']['partidos'][$partido_sistema];

	$datos_partidos['orden_votos_individual']['diferencia_votos_fuerzas'] = $primera_fuerza_votos - $segunda_fuerza_votos ;
	if($primera_fuerza == $partido_sistema){
		$datos_partidos['orden_votos_individual']['diferencia_votos_sistema'] = $partido_sistema_votos - $segunda_fuerza_votos ;
	}elseif ($segunda_fuerza == $partido_sistema) {
		$datos_partidos['orden_votos_individual']['diferencia_votos_sistema'] = $primera_fuerza_votos - $partido_sistema_votos ;
	}else{
		$datos_partidos['orden_votos_individual']['diferencia_votos_sistema'] = $primera_fuerza_votos - $partido_sistema_votos ;
	}
	foreach ($ordena_votos_totales as $votos => $partidos_array) {
		foreach ($partidos_array as $index => $partido) {
			$datos_partidos['orden_votos_totales']['partidos'][$partido]=$votos;

			$datos_partidos['orden_votos_totales']['partidos'][$partido]=$votos;
			$datos_partidos['orden_votos_totales']['graficas']['partidos'][] = $partido;
			$datos_partidos['orden_votos_totales']['graficas']['votos'][] = $votos;
			$datos_partidos['orden_votos_totales']['graficas']['background'][] = '#'.$datos_partidos['partidos'][$partido]['color_background'];

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
	$primera_fuerza = $datos_partidos['orden_votos_totales']['primera_fuerza'];
	$primera_fuerza_votos = $datos_partidos['orden_votos_totales']['partidos'][$primera_fuerza];
	$segunda_fuerza = $datos_partidos['orden_votos_totales']['segunda_fuerza'];
	$segunda_fuerza_votos = $datos_partidos['orden_votos_totales']['partidos'][$segunda_fuerza];

	$partido_sistema;
	$partido_sistema_votos = $datos_partidos['orden_votos_totales']['partidos'][$partido_sistema];

	$datos_partidos['orden_votos_totales']['diferencia_votos_fuerzas'] = $primera_fuerza_votos - $segunda_fuerza_votos ;
	if($primera_fuerza == $partido_sistema){
		$datos_partidos['orden_votos_totales']['diferencia_votos_sistema'] = $partido_sistema_votos - $segunda_fuerza_votos ;
	}elseif ($segunda_fuerza == $partido_sistema) {
		$datos_partidos['orden_votos_totales']['diferencia_votos_sistema'] = $primera_fuerza_votos - $partido_sistema_votos ;
	}else{
		$datos_partidos['orden_votos_totales']['diferencia_votos_sistema'] = $primera_fuerza_votos - $partido_sistema_votos ;
	}

	//? Ordenamos los partidos Menor a Mayor
	ksort($ordena_votos_individual);
	ksort($ordena_votos_totales);

	foreach ($ordena_votos_individual as $votos => $partidos_array) {
		foreach ($partidos_array as $index => $partido) {
			$datos_partidos['orden_votos_individual_menor_mayor']['partidos'][$partido]=$votos;
			$datos_partidos['orden_votos_individual_menor_mayor']['graficas']['partidos'][] = $partido;
			$datos_partidos['orden_votos_individual_menor_mayor']['graficas']['votos'][] = $votos;
			$datos_partidos['orden_votos_individual_menor_mayor']['graficas']['background'][] = '#'.$datos_partidos['partidos'][$partido]['color_background'];
		}
	}
	foreach ($ordena_votos_totales as $votos => $partidos_array) {
		foreach ($partidos_array as $index => $partido) {
			$datos_partidos['orden_votos_totales_menor_mayor']['partidos'][$partido]=$votos;
			$datos_partidos['orden_votos_totales_menor_mayor']['graficas']['partidos'][] = $partido;
			$datos_partidos['orden_votos_totales_menor_mayor']['graficas']['votos'][] = $votos;
			$datos_partidos['orden_votos_totales_menor_mayor']['graficas']['background'][] = '#'.$datos_partidos['partidos'][$partido]['color_background'];

		}
	}

	//? Revocación mandato 2022
	$sql = "SELECT 
			prm.nombre_corto,
			prm.nombre,
			prm.logo,
			prm.icono,
			prm.color_border,
			prm.color_background,
			prm.id,
			(SELECT SUM(cvrm.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cvrm WHERE cvrm.id_municipio='{$id_municipio}' ) votos_nulos,
			(SELECT SUM(cvrm.votos) FROM casillas_preguntas_2022_revocacion_mandato cvrm WHERE cvrm.id_pregunta_2022_revocacion_mandato = prm.id AND cvrm.id_municipio='{$id_municipio}' ) votos
		FROM preguntas_2022_revocacion_mandato prm ORDER BY prm.id DESC;
	";
	$result = $conexion->query($sql); 
	while($row=$result->fetch_assoc()){
		$revocacion_mandato2022[] = $row;
	}

	#####Mayoria  ['orden_votos_individual']['partidos']
	$rutaEfs = rutaEfs();
	$json_data = json_encode($datos_partidos);//Json donde se guarda la informacion para evitar las variables de sesion
	if ($json_data === false) {
		// Manejar el error de codificación JSON si es necesario
		//echo "Error al codificar el JSON";
	} else {
		// Guarda el JSON en un archivo
		$archivo_json = $rutaEfs.'datos_partidos_forzar_distrito_federal_2024_'.$id_municipio.'-'.$_COOKIE["id_usuario"].'.json';
		if (file_put_contents($archivo_json, $json_data)) {
			//echo "Los datos se han guardado en $archivo_json";
		} else {
			//echo "Error al guardar los datos en $archivo_json";
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
	.div60Reporte {
		width: 60%;
		padding: 5px 25px 10px;
		float: left;
	}
	.div40Reporte {
		width: 40%;
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
	.graficas_partidos_totales{
		width:60%;
		padding:10px
	}
	.graficas_partidos_porcentaje{
		width:40%;
		padding:10px
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
		.div40Reporte{
			width: 40%;
		}
		.div60Reporte{
			width: 60%;
		}
		.grafica_barras_horizontales {
			width: 100%;
			height: 88.5px;
			display: table;
		}
		.graficas_partidos_totales{
			width:60%;
			padding:10px
		}
		.graficas_partidos_porcentaje{
			width:40%;
			padding:10px
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
		.div60Reporte,
		.div40Reporte{
			padding: 0px
		}
		.div40Reporte,.div60Reporte{
			width: 100%;
		}
		.grafica_barras_horizontales {
			width: 100%;
			height: 88.5px;
			display: table;
		}
		.graficas_partidos_totales{
			width:60%;
			padding:10px;
			float: left;
		}
		.graficas_partidos_porcentaje{
			width:40%;
			padding:10px;
			float: left;
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
		.div60Reporte,
		.div40Reporte,
		.div50ReporteSNF,
		.totales {
			width: 100%;
		}
		.div100Reporte,
		.div25Reporte,
		.div50Reporte,
		.div60Reporte,
		.div40Reporte,
		.div50ReporteSNF {
			padding: 10px;
		}
		.grafica_barras_horizontales {
			width: 100%;
			height: 88.5px;
			display: table;
		}
		.graficas_partidos_totales,.graficas_partidos_porcentaje{
			width:100%;
			padding:10px;
			float: left;
		}
		.div60Reporte,
		.div40Reporte{
			padding: 0px
		}
	}
	@media only screen and (max-width: 620px) and (min-width: 6px) {
		/* For mobile phones: */
		.div25Reporte,
		.div25Reportepartidos,
		.div30Reportepartidos,
		.div50Reporte,
		.div60Reporte,
		.div40Reporte,
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
		.graficas_partidos_totales,.graficas_partidos_porcentaje{
			width:100%;
			padding:10px;
			float: left;
		}
		.div60Reporte,
		.div40Reporte{
			padding: 0px
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
								<font class="fontLabelReporte">Lista Nominal:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['total_lista_nominal'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Secciones:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['secciones'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Casillas:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['casillas'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos Válidos:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['votos_validos'], 0, '.', ','); ?>
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
									<?=number_format($datos_generales['votos_nulos'], 0, '.', ','); ?>
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
									<?=number_format($datos_generales['votos_can_nreg'], 0, '.', ','); ?>
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
									<?=number_format($datos_generales['votos_totales'], 0, '.', ','); ?>
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
									<?=number_format($datos_generales['participacion_ciudadana'], 2, '.', ','); ?>%
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
								<font class="fontLabelReporte">Programas de Gobierno:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['apoyos_programas'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Programas de Inversión:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['acciones_obras'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Ciudadanos:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['ciudadanos_totales'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Funcionarios:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['funcionarios'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Militantes:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['militantes'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Grupos de Interes:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['grupos_interes'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Juntas:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['juntas'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Visitas:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['visitas'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td
								style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Caminatas:</font>
							</td>
							<td
								style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($datos_generales['caminatas'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<hr style="width: 80%;border-top: 1px solid #333333;">
		<div style="background-color: white;padding: 5px;display: table;">
		<div style="width:100%;text-align: center;"><h3><b>Consulta Revocación de Mandato 2022</b></h3></div>
		<?php

		foreach ($revocacion_mandato2022 as $key => $value) {
			?>
			<div class="div50Reporte">
				<table style="table-layout: fixed; width: 100%" cellspacing="0" cellpadding="0" border="1">
					<thead>
						<tr>
							<tr>
							<td style="text-align: center;padding: 10px;background-color: #<?= $value['color_background'] ?>;color: white;font-weight: bold;" colspan="2">Información <?= $value['nombre_corto'] ?></td>
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
									<?= $value['nombre'] ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
						<tr>
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white"><font class="fontLabelReporte">Votos Nulos:</font></td>
							<td style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
								<font class="fontDataReporte" style="font-size: 12px">
									<?=number_format($value['votos_nulos'], 0, '.', ','); ?>
								</font>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<?php
		}
		?>

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
									<td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.1); border-bottom: 1px solid white; height: 135px">
										<font class="fontLabelReporte">Coaliciones:</font>
										<?php
											if(!empty($value['coaliciones_orden_votos_individual'])){
												unset($value['coaliciones_orden_votos_individual'][$value['nombre_corto']]);
												?>
												<table style="width: 100%;text-align: left;font-size: 10px;table-layout: fixed;">
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
								<tr>
									<td
										style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
										<font class="fontLabelReporte">Diferencia:</font>
									</td>
									<td
										style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?=number_format($datos_partidos['orden_votos_individual']['diferencia_votos_sistema'], 0, '.', ','); ?>
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
			if ($key !='sistema' && $key !='partidos' && $key !='graficas' && $key !='diferencia_votos_fuerzas' && $key !='diferencia_votos_sistema'){
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
							<td colspan="2" style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.1); border-bottom: 1px solid white; height: 135px">
								<font class="fontLabelReporte">Coaliciones:</font>
								<?php
									if(!empty($value['coaliciones_orden_votos_individual'])){
										unset($value['coaliciones_orden_votos_individual'][$value['nombre_corto']]);
										?>
										<table style="width: 100%;text-align: left;font-size: 10px;table-layout: fixed;">
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
							<td style="text-align: left; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.6); border-bottom: 1px solid white">
								<font class="fontLabelReporte">Votos Totales:</font>
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
										<font class="fontLabelReporte">Diferencia:</font>
									</td>
									<td
										style="text-align: right; width: 25%;padding: 0px 5px 0px 5px;background-color: rgba(176,176,176,0.3); border-bottom: 1px solid white">
										<font class="fontDataReporte" style="font-size: 12px">
											<?=number_format($datos_partidos['orden_votos_individual']['diferencia_votos_fuerzas'], 0, '.', ','); ?>
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
		
		<div style="background-color: white;padding: 5px;display: table;width:100%">
			<div class='div60Reporte' >
				<div id="appPartidosTotales">
					<div id="chart">
						<apexchart type="bar" height="380" :options="chartOptionsCiudadanosPartidosTotales" :series="series"></apexchart>
					</div>
				</div>
				<script>
					new Vue({
						el: '#appPartidosTotales',
						components: {
							apexchart: VueApexCharts
						},
						data: {
							series: [
								{
									data: <?= json_encode($datos_partidos['orden_votos_individual_menor_mayor']['graficas']['votos']); ?>
								}
							],
							chartOptionsCiudadanosPartidosTotales: {
								plotOptions: {
									bar: {
										barHeight: '100%',
										distributed: true,
										horizontal: true,
										dataLabels: {
											position: 'bottom'
										}
									}
								},
								chart: {
									type: 'bar',
									height: 380,
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
										show: false,
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
								colors: <?= json_encode($datos_partidos['orden_votos_individual_menor_mayor']['graficas']['background']) ?>,
								dataLabels: {
									enabled: true,
									textAnchor: 'start',
									style: {
										colors: ['#fff']
									},
									formatter: function (val, opt) {
										return opt.w.globals.labels[opt.dataPointIndex] + ":  " + val.toLocaleString();
									},
									offsetX: 0,
									dropShadow: {
										enabled: true
									}
								},
								legend: {
									show: false
								},
								stroke: {
									width: 0.2,
									colors: ['#fff']
								},
								xaxis: {
									categories: <?= json_encode($datos_partidos['orden_votos_individual_menor_mayor']['graficas']['partidos']) ?>,
									labels: {
										formatter: function (value) {
											return parseFloat(value).toLocaleString();
										}
									},								
								},
								yaxis: {
									labels: {
										show: false,
										formatter: function (val) {
											return val.toLocaleString()
										}
									}
								},
								title: {
									text: 'Votos Partidos',
									align: 'center',
									floating: true
								},
								subtitle: {
									//text: 'Ordenado según los votos en individual',
									//align: 'center'
								},
								tooltip: {
									theme: 'light',
									enabled: true,
									shared: true,
									followCursor: true,
									intersect: false,
									inverseOrder: false,
									custom: undefined,
									fillSeriesColor: false,
									onDatasetHover: {
										highlightDataSeries: true,
									}, 
									x: {
										formatter: function(value, { series, seriesIndex, dataPointIndex, w }) {
											//<div style="display:table"><div style="background-color:red; width:10px"> </div><div>'+'Sección : ' + parseFloat(seccion).toLocaleString()+'</div></div>
											return value;
										}
									},
									y: {
										title: {
											formatter: function () {
												return 'Votos: '
											}
										}
									}
								},
								grid: {
									row: {
										colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
										opacity: 0.5
									},
								},
							}
						}
					})
				</script>
			</div>
			<div class='div40Reporte' >
				<div id="appPartidosPorcentajes">
					<div id="chart">
						<apexchart type="donut" height="300" :options="chartOptionsCiudadanosPartidosPorcentajes" :series="series"></apexchart>
					</div>
				</div>
				<script>
					new Vue({
						el: '#appPartidosPorcentajes',
						components: {
							apexchart: VueApexCharts,
						},
						data: {
							series: <?= json_encode($datos_partidos['orden_votos_individual_menor_mayor']['graficas']['votos']); ?>,
							chartOptionsCiudadanosPartidosPorcentajes: {
								tooltip: {
									enabled: false,
								},
								labels: <?= json_encode($datos_partidos['orden_votos_individual_menor_mayor']['graficas']['partidos']); ?>,
								title: {
									text: 'Porcentaje partidos',
									align: 'center',
									floating: false,
								},
								subtitle: {
									//text: 'Porcentaje de los votos validos emitidos',
									//align: 'center'
								},
								fill: {
									opacity: 1,
								},

								chart: {
									width: 200,
									type: 'donut',
								},
								colors: <?= json_encode($datos_partidos['orden_votos_individual_menor_mayor']['graficas']['background']) ?>,
								plotOptions: {
									
									pie: {
										//startAngle: 225,
										//endAngle: -90,
										//offsetY: -10
									}
									
								},
								grid: {
									padding: {
										bottom: 0
									}
								},
								legend: {
									show: false
								},
								stroke: {
									width: 1
								},
								dataLabels: {
									enabled: true,
									formatter: function(value, { seriesIndex, dataPointIndex, w }) {
										data = []
										data[0] = w.config.labels[seriesIndex];
										//data[1] = w.config.series[seriesIndex].toLocaleString();
										data[1] = value.toFixed(2)+ " %";
										return data;
									},
									style: {
										colors: ['#111'],
										fontSize: "10px",
									},
									background: {
										enabled: true,
										foreColor: '#fff',
										borderWidth: 0
									}
								},
							},
						},
					})
					</script>
			</div>
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