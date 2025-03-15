<?php
	include __DIR__.'/../../functions/security.php'; 
	@session_start(); 
	include __DIR__."/../../functions/configuracion_matriz_rentabilidad_secciones_ine_2021.php";

	

	$_SESSION['reporte_Sistema']['columnas_nombres'] = array(
		0 => array('row' => 'id' ,'nombre' => 'Id Sección' ,'tipo' => 'string','mostrar' => 1 ),
		1 => array('row' => 'clave' ,'nombre' => 'Clave' ,'tipo' => 'string','mostrar' => 1 ),
		2 => array('row' => 'numero' ,'nombre' => 'Sección' ,'tipo' => 'integer','mostrar' => 1 ),
		3 => array('row' => 'ciudadanos_registrados' ,'nombre' => 'Ciudadanos Registrados' ,'tipo' => 'integer','mostrar' => 1 ),
		4 => array('row' => 'apoyos_programas' ,'nombre' => 'Apoyos y Programas' ,'tipo' => 'integer','mostrar' => 1 ),
		5 => array('row' => 'acciones_obras' ,'nombre' => 'Acciones y Obras' ,'tipo' => 'integer','mostrar' => 1 ),
		6 => array('row' => 'grupos_interes' ,'nombre' => 'Grupos de Interes' ,'tipo' => 'integer','mostrar' => 1 ),
		7 => array('row' => 'funcionarios' ,'nombre' => 'Funcionarios' ,'tipo' => 'integer','mostrar' => 1 ),
		8 => array('row' => 'militantes' ,'nombre' => 'Militantes' ,'tipo' => 'integer','mostrar' => 1 ),

		9 => array('row' => 'lista_nominal' ,'nombre' => 'Lista Nominal' ,'tipo' => 'integer','mostrar' => 1 ),
		10 => array('row' => 'votos_totales' ,'nombre' => 'Votos Totales' ,'tipo' => 'integer','mostrar' => 1 ),
		11 => array('row' => 'participacion_ciudadana' ,'nombre' => 'Participación Ciudadana' ,'tipo' => 'integer','mostrar' => 1 ),
		12 => array(
			'row' => array('ganador'=>'nombre_corto'),
			'nombre' => 'Partido Ganador',
			'tipo' => 'string',
			'mostrar' => 1 ),
		13 => array(
			'row' => array('ganador'=>'individual'),
			'nombre' => 'Ganador Votos individual',
			'tipo' => 'integer',
			'mostrar' => 1 ),
		14 => array(
			'row' => array('ganador'=>'coaliciones'),
			'nombre' => 'Ganador Coaliciones',
			'tipo' => 'string',
			'mostrar' => 1 ),
		15 => array(
			'row' => array('ganador'=>'votos_coalicion'),
			'nombre' => 'Ganador Votos Coaliciones ',
			'tipo' => 'integer',
			'mostrar' => 1 ),
		16 => array(
			'row' => array('ganador'=>'votos'),
			'nombre' => 'Ganador Votos Totales ',
			'tipo' => 'integer',
			'mostrar' => 1 ),
		17 => array(
			'row' => array('ganador'=>'porcentaje'),
			'nombre' => 'Ganador Votos %',
			'tipo' => 'integer',
			'mostrar' => 1 ),

		18 => array(
			'row' => array('secundario'=>'nombre_corto'),
			'nombre' => 'Partido',
			'tipo' => 'string',
			'mostrar' => 1 ),
		19 => array(
			'row' => array('secundario'=>'individual'),
			'nombre' => 'Partido Votos individual',
			'tipo' => 'integer',
			'mostrar' => 1 ),
		20 => array(
			'row' => array('secundario'=>'coaliciones'),
			'nombre' => 'Partido Coaliciones',
			'tipo' => 'string',
			'mostrar' => 1 ),
		21 => array(
			'row' => array('secundario'=>'votos_coalicion'),
			'nombre' => 'Partido Votos Coaliciones',
			'tipo' => 'integer',
			'mostrar' => 1 ),
		22 => array(
			'row' => array('secundario'=>'votos'),
			'nombre' => 'Partido Votos Totales',
			'tipo' => 'integer',
			'mostrar' => 1 ),
		23 => array(
			'row' => array('ganador'=>'porcentaje'),
			'nombre' => 'Partido Votos %',
			'tipo' => 'integer',
			'mostrar' => 1 ),
		24 => array(
			'row' => 'diferencia_votos',
			'nombre' => 'Diferencia',
			'tipo' => 'integer',
			'mostrar' => 1 ),
		25 => array(
			'row' => 'semaforo',
			'nombre' => 'Semáforo',
			'tipo' => 'string',
			'mostrar' => 1 ),
		26 => array('row' => 'tipo' ,'nombre' => 'Tipo Sección' ,'tipo' => 'string','mostrar' => 1 ),

	);

	//var_dump($_POST);
	if(!empty($_POST)){
		include __DIR__."/../../functions/distritos_locales_parametros.php"; 
		include __DIR__."/../../functions/distritos_locales.php";
		include __DIR__."/../../functions/secciones_ine_parametros.php";
		include __DIR__."/../../functions/configuracion_matriz_rentabilidad_secciones_ine_2021.php";


		$configuracion_matriz_rentabilidad_secciones_ine_2021Datos = configuracion_matriz_rentabilidad_secciones_ine_2021Datos();

		$votos_semaforo_amarillo = $configuracion_matriz_rentabilidad_secciones_ine_2021Datos['votos_semaforo_amarillo'];
		$id_tipo_categoria_ciudadano = $configuracion_matriz_rentabilidad_secciones_ine_2021Datos['id_tipo_categoria_ciudadano'] ;// funcionario
		$id_partido_2021 = $configuracion_matriz_rentabilidad_secciones_ine_2021Datos['id_partido_2021_distrito_local'];// Partidos 2021 PRI
		//$id_partido_2021 = $configuracion_matriz['id_partido_2021'] = '1';// Partidos 2021
		$id_partido_legado = $configuracion_matriz['id_partido_legado'] = '1';// Partidos Legados
		$tipo_eleccion = $configuracion_matriz['tipo_eleccion'] = '1';// 0 - Ayuntamiento | 1 - Distrito Local | 2 - Distrito Federal
		/// en el formulario segun el tipo sera lo que te va mostrar el select sera un onchange para que cambie funcionara igual que el de localidades y municipio el principal seria tipo_eleccion y segun lo que escojas sera los partidos que te salgan 




		function truncar($numero, $digitos){
			$truncar = 10**$digitos;
			return intval($numero * $truncar) / $truncar;
		}


		$zoom="8";
		//$orderby = ' ORDER BY fechaR DESC';
		$limit = 'LIMIT 0,84';
		/*
		$secciones_ine_reportesDatosArray=secciones_ine_reportesDatosArray($_POST['searchTable'][0],$orderby,$limit);

		foreach ($secciones_ine_reportesDatosArray as $key => $value) {
			$colores[$value['id']]  = array('color_border' => $value['color_border'],'color_background' => $value['color_background'] );
		}

		$secciones_ine_parametrosDatos = secciones_ine_parametrosDatos('','',' id_seccion_ine,orden ASC');
		foreach ($secciones_ine_parametrosDatos as $key => $value) {
			$secciones_area[$value['id_seccion_ine']][] = $value ;
		}
		*/


		$id_distrito_local = $_POST['searchTable'][0]['id_distrito_local'];
		$id_seccion_ine = $_POST['searchTable'][0]['id_seccion_ine'];
		$tipo_seccion = $_POST['searchTable'][0]['tipo_seccion'];
		$distritos_locales_parametrosDatosMapa = distritos_locales_parametrosDatosMapa(); 

		$sql="
			SELECT
				m.id,
				m.clave,
				m.numero,
				m.latitud,
				m.longitud
			FROM distritos_locales m
			WHERE  m.id = '{$id_distrito_local}'
		";
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			
			$datos_distritos_locales[$row['id']]=$row;
			//$datos_distritos_locales[$row['id']]['poligonos']=$distritos_locales_parametrosDatosMapa[$row['id']];
			$num=$num+1;
		}

		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','',$id_distrito_local,'','','');
		if($id_seccion_ine!=''){
			$sql_secciones_ine = "AND cvp2021.id_seccion_ine IN ({$id_seccion_ine})";
		}
		$sql="SELECT 
				p2021.id AS id_partido_2021,
				p2021.clave,
				p2021.nombre_corto,
				p2021.logo,
				p2021.clave_partidos_coaliciones,
				SUM(cvp2021.votos) votos,
				cvp2021.id_seccion_ine
			FROM casillas_votos_partidos_2021 cvp2021 
			LEFT JOIN partidos_2021 p2021 ON cvp2021.id_partido_2021 = p2021.id
			WHERE cvp2021.tipo = '{$tipo_eleccion}'  AND cvp2021.id_distrito_local = '{$id_distrito_local}' {$sql_secciones_ine}
			GROUP BY cvp2021.id_seccion_ine,id_partido_2021 
			ORDER BY cvp2021.id_seccion_ine,SUM(cvp2021.votos) DESC
			";
		$result = $conexion->query($sql);
	 
		while($row=$result->fetch_assoc()){
			//////
			if($row['clave_partidos_coaliciones'] != ''){
				$partidos_coaliciones[$row['id_seccion_ine']][$row['clave']]=$row;
			}else{
				$partidos_sin_coaliciones[$row['id_seccion_ine']][$row['nombre_corto']]=$row;
			}
		} 
		if($id_seccion_ine!=''){
			$sql_secciones_ine = " AND si.id IN ({$id_seccion_ine})";
		}
		$sql="SELECT 
			si.id AS id_seccion_ine,
			si.id,
			si.clave,
			si.latitud,
			si.longitud,
			(SELECT m.numero FROM distritos_locales m WHERE m.id = si.id_distrito_local) distrito_local,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos sic WHERE sic.id_seccion_ine = si.id) ciudadanos_registrados,

			
			(SELECT p2021.nombre_corto FROM partidos_2021 p2021 WHERE p2021.id = '{$id_partido_2021}' ) partido_sistema_nombre_corto,
			
			(SELECT p2021.clave FROM partidos_2021 p2021 WHERE p2021.id = '{$id_partido_2021}' ) partido_sistema_clave,
			si.numero,
			
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sicpa.id_seccion_ine_ciudadano = sic.id WHERE sic.id_seccion_ine = si.id) apoyos_programas,
			
			(SELECT COUNT(*) FROM secciones_ine_actividades sia WHERE sia.id_seccion_ine = si.id ) acciones_obras,
			
			(SELECT COUNT(*) FROM secciones_ine_grupos sig WHERE sig.id_seccion_ine = si.id ) grupos_interes,
			
			(SELECT COUNT(*) FROM militantes_partidos mp LEFT JOIN secciones_ine_ciudadanos sic ON mp.id_seccion_ine_ciudadano = sic.id WHERE sic.id_seccion_ine = si.id AND mp.id_partido_legado = '{$id_partido_legado}') militantes,
			
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine = si.id AND sicc.id_tipo_categoria_ciudadano = '{$id_tipo_categoria_ciudadano}') funcionarios,
			
			(SELECT SUM(cv2021.lista_nominal) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ) lista_nominal,
			
			(SELECT COUNT(*) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ) casillas,
			
			(SELECT SUM(cvp2021.votos) FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.id_seccion_ine = si.id AND cvp2021.tipo = '{$tipo_eleccion}' )votos_validos,

			
			(SELECT SUM(cv2021.votos_nulos) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ) + (SELECT SUM(cv2021.votos_can_nreg) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ) + (SELECT SUM(cvp2021.votos) FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.id_seccion_ine = si.id AND cvp2021.tipo = '{$tipo_eleccion}' ) AS votos_totales,

			
			(SELECT SUM(cv2021.votos_nulos) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ) votos_nulos,
			
			(SELECT SUM(cv2021.votos_can_nreg) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ) votos_can_nreg,
			
			ROUND(
				(
					(
						(SELECT SUM(cvp2021.votos) FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.id_seccion_ine = si.id AND cvp2021.tipo = '{$tipo_eleccion}' )
						+
						(SELECT SUM(cv2021.votos_nulos) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' )
						+
						(SELECT SUM(cv2021.votos_can_nreg) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' )
					)
					/
					(
					SELECT SUM(cv2021.lista_nominal) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ))*100,2
					) AS participacion_ciudadana,
					IF(si.tipo=1,'Urbana','Rural') AS tipo
		FROM secciones_ine si 
		WHERE 1 AND si.id_distrito_local = {$id_distrito_local} {$sql_secciones_ine} ";
		if($tipo_seccion!=""){
			$sql.= " AND si.tipo IN ({$tipo_seccion}) ";
		}
		$result = $conexion->query($sql);
		while($row=$result->fetch_assoc()){
			//////
			$datos_secciones_ine[$row['id_seccion_ine']]=$row;
			$id_seccion_ine = $row['id_seccion_ine'];
			//////
			if(empty($partidos_sin_coaliciones[$id_seccion_ine])){
				$datos_secciones_ine[$id_seccion_ine]['lista_nominal'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['casillas'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['votos_validos'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['votos_totales'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['votos_nulos'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['votos_can_nreg'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['participacion_ciudadana'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['ganador'] = array(
																			'clave' => 'NOTIENE',
																			'nombre_corto' => 'NOTIENE',
																			'individual' => 0,
																			'logo' => 'no_data.png',
																			'votos' => 0,
																			'id_partido_2021' => 0,
																			'porcentaje' => 0,
																			'coaliciones' => '',
																			'votos_coalicion' => 0,
																		);
				$datos_secciones_ine[$id_seccion_ine]['secundario'] = array(
																			'clave' => 'NOTIENE',
																			'nombre_corto' => 'NOTIENE',
																			'individual' => 0,
																			'logo' => 'no_data.png',
																			'votos' => 0,
																			'id_partido_2021' => 0,
																			'porcentaje' => 0,
																			'coaliciones' => '',
																			'votos_coalicion' => 0,
																		);
				$datos_secciones_ine[$id_seccion_ine]['semaforo'] = 'gris';
				$datos_secciones_ine[$id_seccion_ine]['diferencia_votos'] = '0';
			}else{
				/*
				Esta parte busca todos los sin caliciones para que vea cual es el ganador despues agrego a la seccion todos la suma de los partidos y sus coaliciones para saber cual es el ganadro
				La formula usada es
				1.- Saco en solitario 
				2.- Luego veo en donde tiene coalcion y sumo la totalidad del voto recuerda que aqui es por candidato y no por partido
				3.- saco los solitarios en individuales y los sumo los totales
				4.- luego al final de sear sin coalicion busco el ganador
				5.- si el ganador coincide con el del sistema entonces saco el segundo lugar para sacar la diferencia en votos
				6.- Si no coincide el ganador con el del sistema entonces es perdida.
				*/ 
				unset($partidos);
				foreach ($partidos_sin_coaliciones[$id_seccion_ine] as $key => $value) {
					$nombre_corto = $value['nombre_corto'];
					//$totales_partidos[$nombre_corto] = $value['votos'];
					unset($nombre_cortos);
					unset($totales_partidos);
					$total = 0;
					$sistema_coaliciones = false;
					foreach ($partidos_coaliciones[$id_seccion_ine] as $keyT => $valueT) {
						$nombre_corto_coalicion = $valueT['nombre_corto'];
						//sacamos el array del nombre_corto_coalicion
						$nombre_corto_coalicion;
						$coaliciones = explode("_", $nombre_corto_coalicion);
						$pos = false;
						foreach ($coaliciones as $keyX => $valueX) {
							if($nombre_corto ==  $valueX){
								$pos = true;
							}
						}
						
						if ($pos == true) {
							$sistema_coaliciones = true;
							$totales_partidos[$nombre_corto] = $totales_partidos[$nombre_corto] + $valueT['votos'];
							foreach ($coaliciones as $keyC => $valueC) {
								if($nombre_corto != $valueC){
									$nombre_cortos[$valueC] = $partidos_sin_coaliciones[$id_seccion_ine][$valueC]['votos'];
								}
							}
							$total = 0;
							foreach ($nombre_cortos as $keyL => $valueL) {
								$total = $valueL + $total;
							}
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_partido_2021'] = $value['id_partido_2021'];
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['nombre'] = $value['nombre_corto'];
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['clave'] = $value['clave'];
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['logo'] = $value['logo'];
							//
							$total = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['total'] = $value['votos'] + $totales_partidos[$nombre_corto] + $total ;
							//
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['individual'] = $value['votos'];

							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['coalicion'] = $totales_partidos[$nombre_corto];

							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['coalicion_votos'] = $total-$value['votos'];

							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['nombres_cortos'] = $valueT['nombre_corto'];
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['partidos_coaliciones'] = $nombre_cortos;
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'] =  implode(",", array_keys($nombre_cortos));

							$partidos[$row['id_seccion_ine']][$nombre_corto]['total'] = $total;
							$partidos[$row['id_seccion_ine']][$nombre_corto]['individual'] = $value['votos'];
							$partidos[$row['id_seccion_ine']][$nombre_corto]['clave'] = $value['clave']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['id_partido_2021'] = $value['id_partido_2021']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['logo'] = $value['logo']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'] = implode(",", array_keys($nombre_cortos));
							$partidos[$row['id_seccion_ine']][$nombre_corto]['votos_coalicion'] = $total-$value['votos'];
							$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos_array']=$nombre_cortos;
						}
					}

					$total = 0;
					if($sistema_coaliciones==false){
						/// aqui entra cuando el partido no tiene caliciones o va solo
						$nombre_corto = $value['nombre_corto'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_partido_2021'] = $value['id_partido_2021'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['nombre'] = $value['nombre_corto'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['clave'] = $value['clave'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['logo'] = $value['logo'];
						//
						$total = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['total'] = $value['votos'] + $totales_partidos[$nombre_corto] + $total ;
						//
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['individual'] = $value['votos'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['coalicion'] = $totales_partidos[$nombre_corto];

						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['coalicion_votos'] = $total-$value['votos'];

						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['nombres_cortos'] = $valueT['nombre_corto'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['partidos_coaliciones'] = $nombre_cortos;
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'] =  implode(",", array_keys($nombre_cortos));

						$partidos[$row['id_seccion_ine']][$nombre_corto]['total'] = $total;
						$partidos[$row['id_seccion_ine']][$nombre_corto]['individual'] = $value['votos'];
						$partidos[$row['id_seccion_ine']][$nombre_corto]['clave'] = $value['clave']; 
						$partidos[$row['id_seccion_ine']][$nombre_corto]['id_partido_2021'] = $value['id_partido_2021']; 
						$partidos[$row['id_seccion_ine']][$nombre_corto]['logo'] = $value['logo'];
						$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'] = implode(",", array_keys($nombre_cortos));
						$partidos[$row['id_seccion_ine']][$nombre_corto]['votos_coalicion'] = $total-$value['votos'];
						$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos_array']=$nombre_cortos;
					}
				}
				// sacar ganador
				$partido_ganador_votos = 0 ;
				$partido_ganador_nombre_corto = '';
				foreach ($partidos[$row['id_seccion_ine']] as $keyZ => $valueZ) {
					if($valueZ['total'] > $partido_ganador_votos){
						$partido_ganador_votos = $valueZ['total'];
						$partido_ganador_nombre_corto = $keyZ;
						$partido_ganador_clave = $valueZ['clave'];
						$partido_ganador_id_partido_2021 = $valueZ['id_partido_2021'];
						$partido_ganador_logo = $valueZ['logo'];
						$partido_ganador_coaliciones = $valueZ['partidos_nombres_cortos'];
						$partido_ganador_individual = $valueZ['individual'];
						$partido_ganador_votos_coalicion = $valueZ['votos_coalicion'];
						$partido_ganador_nombres_cortos_array = $valueZ['partidos_nombres_cortos_array'];
					}
				}
				///partido ganador add

				// sacar secundario
				$partido_secundario_votos = 0 ;
				$partido_secundario_nombre_corto = '';

				$mismacoalicion = false;
				foreach ($partido_ganador_nombres_cortos_array as $keyCC => $valueCC) {
					if($row['partido_sistema_nombre_corto'] == $keyCC){
						$mismacoalicion = true;
					}
				}

				if($partido_ganador_id_partido_2021 == $id_partido_2021 || $mismacoalicion == true ){
					$tipo=true;
					///busca el 2 lugar excluyendo a si mismo
					/// busca el secundario por si el partido configurado gano
					foreach ($partidos[$row['id_seccion_ine']] as $keyZ => $valueZ) {
						if($valueZ['total'] > $partido_secundario_votos && $valueZ['id_partido_2021'] != $partido_ganador_id_partido_2021 ){ 
							if($datos_secciones_ine[$row['id_seccion_ine']][$keyZ]['partidos_coaliciones'][$partido_ganador_nombre_corto]==''){
								$partido_secundario_votos = $valueZ['total'];
								$partido_secundario_nombre_corto = $keyZ;
								$partido_secundario_clave = $valueZ['clave'];
								$partido_secundario_id_partido_2021 = $valueZ['id_partido_2021'];
								$partido_secundario_logo = $valueZ['logo'];
								$partido_secundario_coaliciones = $valueZ['partidos_nombres_cortos'];
								$partido_secundario_individual = $valueZ['individual'];
								$partido_secundario_votos_coalicion = $valueZ['votos_coalicion'];
							}
						}
					}
				}else{
					/// colocamos el nombre corto del partido de configuracion principal
					//aqui entra si el partido que se configuro no gano
					$tipo=false;
					$nombre_corto = $row['partido_sistema_nombre_corto'];
					$partido_secundario_votos = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['total'];
					$partido_secundario_nombre_corto = $nombre_corto;
					$partido_secundario_clave = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['clave'];
					$partido_secundario_id_partido_2021 = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_partido_2021'];
					$partido_secundario_logo = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['logo'];
					$partido_secundario_coaliciones = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'];
					$partido_secundario_individual = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['individual'];
					$partido_secundario_votos_coalicion = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['coalicion_votos'];

				}
				$datos_secciones_ine[$id_seccion_ine]['ganador']['clave'] = $partido_ganador_clave;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['nombre_corto'] = $partido_ganador_nombre_corto;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['individual'] = $partido_ganador_individual;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['logo'] = $partido_ganador_logo;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['votos'] = $partido_ganador_votos;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['id_partido_2021'] = $partido_ganador_id_partido_2021;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['porcentaje'] = number_format(($partido_ganador_votos / $datos_secciones_ine[$id_seccion_ine]['votos_totales']*100),2,'.',',');
				$datos_secciones_ine[$id_seccion_ine]['ganador']['coaliciones'] = $partido_ganador_coaliciones;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['votos_coalicion'] = $partido_ganador_votos_coalicion;

				$datos_secciones_ine[$id_seccion_ine]['secundario']['clave'] = $partido_secundario_clave;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['nombre_corto'] = $partido_secundario_nombre_corto;

				$datos_secciones_ine[$id_seccion_ine]['secundario']['individual'] = $partido_secundario_individual;

				$datos_secciones_ine[$id_seccion_ine]['secundario']['logo'] = $partido_secundario_logo;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['votos'] = $partido_secundario_votos;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['id_partido_2021'] = $partido_secundario_id_partido_2021;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['porcentaje'] = number_format(($partido_secundario_votos / $datos_secciones_ine[$id_seccion_ine]['votos_totales']*100),2,'.',',');
				$datos_secciones_ine[$id_seccion_ine]['secundario']['coaliciones'] = $partido_secundario_coaliciones;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['votos_coalicion'] = $partido_secundario_votos_coalicion;
				if($tipo=='true'){
					$diferencia = $partido_ganador_votos - $partido_secundario_votos;
					if($diferencia <= $votos_semaforo_amarillo){
						$datos_secciones_ine[$id_seccion_ine]['semaforo'] = 'amarillo';
					}else{
						$datos_secciones_ine[$id_seccion_ine]['semaforo'] = 'verde';
					}
				}else{
					$diferencia = $partido_ganador_votos - $partido_secundario_votos;
					$datos_secciones_ine[$id_seccion_ine]['semaforo'] = 'rojo';
				}
				$datos_secciones_ine[$id_seccion_ine]['diferencia_votos'] = $diferencia;
				if($datos_secciones_ine[$id_seccion_ine]['semaforo'] != $_POST['searchTable'][0]['semaforo'] && $_POST['searchTable'][0]['semaforo']!=''){
					unset($datos_secciones_ine[$id_seccion_ine]);
				}
			}
			if($datos_secciones_ine[$id_seccion_ine]['semaforo'] != $_POST['searchTable'][0]['semaforo'] && $_POST['searchTable'][0]['semaforo']!=''){
				unset($datos_secciones_ine[$id_seccion_ine]);
			}
		}


	}else{

		$zoom="8";
		$orderby = ' ORDER BY fechaR DESC';
		$limit = 'LIMIT 0,84';
		/*
		$secciones_ine_reportesDatosArray=secciones_ine_reportesDatosArray($_POST['searchTable'][0],$orderby,$limit);

		foreach ($secciones_ine_reportesDatosArray as $key => $value) {
			$colores[$value['id']]  = array('color_border' => $value['color_border'],'color_background' => $value['color_background'] );
		}

		$secciones_ine_parametrosDatos = secciones_ine_parametrosDatos('','',' id_seccion_ine,orden ASC');
		foreach ($secciones_ine_parametrosDatos as $key => $value) {
			$secciones_area[$value['id_seccion_ine']][] = $value ;
		}
		*/
		$id_distrito_local;
		$_POST['searchTable'][0]['id_distrito_local']=$id_distrito_local;
		$distritos_locales_parametrosDatosMapa = distritos_locales_parametrosDatosMapa();
		$sql="
			SELECT
				m.id,
				m.clave,
				m.numero,
				m.latitud,
				m.longitud
			FROM distritos_locales m
			WHERE m.id = '{$id_distrito_local}'
		";
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			
			$datos_distritos_locales[$row['id']]=$row;
			//$datos_distritos_locales[$row['id']]['poligonos']=$distritos_locales_parametrosDatosMapa[$row['id']];
			$num=$num+1;
		}

		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','',$id_distrito_local,'','','');
		$sql="SELECT 
				p2021.id AS id_partido_2021,
				p2021.clave,
				p2021.nombre_corto,
				p2021.logo,
				p2021.clave_partidos_coaliciones,
				SUM(cvp2021.votos) votos,
				cvp2021.id_seccion_ine
			FROM casillas_votos_partidos_2021 cvp2021 
			LEFT JOIN partidos_2021 p2021 ON cvp2021.id_partido_2021 = p2021.id
			WHERE cvp2021.tipo = '{$tipo_eleccion}'  AND cvp2021.id_distrito_local = '{$id_distrito_local}'
			GROUP BY cvp2021.id_seccion_ine,id_partido_2021 
			ORDER BY cvp2021.id_seccion_ine,SUM(cvp2021.votos) DESC
			";
		$result = $conexion->query($sql);
		//echo "<pre>";
		//echo $sql;
		//echo "</pre>";
		while($row=$result->fetch_assoc()){
			//////
			if($row['clave_partidos_coaliciones'] != ''){
				$partidos_coaliciones[$row['id_seccion_ine']][$row['clave']]=$row;
			}else{
				$partidos_sin_coaliciones[$row['id_seccion_ine']][$row['nombre_corto']]=$row;
			}
		} 
		$sql="SELECT 
			si.id AS id_seccion_ine,
			si.id,
			si.clave,
			si.latitud,
			si.longitud,
			(SELECT m.numero FROM distritos_locales m WHERE m.id = si.id_distrito_local) distrito_local,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos sic WHERE sic.id_seccion_ine = si.id) ciudadanos_registrados,
			(SELECT p2021.nombre_corto FROM partidos_2021 p2021 WHERE p2021.id = '{$id_partido_2021}' ) partido_sistema_nombre_corto,
			(SELECT p2021.clave FROM partidos_2021 p2021 WHERE p2021.id = '{$id_partido_2021}' ) partido_sistema_clave,
			si.numero,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sicpa.id_seccion_ine_ciudadano = sic.id WHERE sic.id_seccion_ine = si.id) apoyos_programas,
			(SELECT COUNT(*) FROM secciones_ine_actividades sia WHERE sia.id_seccion_ine = si.id ) acciones_obras,
			(SELECT COUNT(*) FROM secciones_ine_grupos sig WHERE sig.id_seccion_ine = si.id ) grupos_interes,
			(SELECT COUNT(*) FROM militantes_partidos mp LEFT JOIN secciones_ine_ciudadanos sic ON mp.id_seccion_ine_ciudadano = sic.id WHERE sic.id_seccion_ine = si.id AND mp.id_partido_legado = '{$id_partido_legado}') militantes,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_seccion_ine = si.id AND sicc.id_tipo_categoria_ciudadano = '{$id_tipo_categoria_ciudadano}') funcionarios,
			(SELECT SUM(cv2021.lista_nominal) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ) lista_nominal,
			(SELECT COUNT(*) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ) casillas,
			(SELECT SUM(cvp2021.votos) FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.id_seccion_ine = si.id AND cvp2021.tipo = '{$tipo_eleccion}' )votos_validos,

			(SELECT SUM(cv2021.votos_nulos) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ) + (SELECT SUM(cv2021.votos_can_nreg) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ) + (SELECT SUM(cvp2021.votos) FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.id_seccion_ine = si.id AND cvp2021.tipo = '{$tipo_eleccion}' ) AS votos_totales,

			(SELECT SUM(cv2021.votos_nulos) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ) votos_nulos,
			(SELECT SUM(cv2021.votos_can_nreg) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ) votos_can_nreg,
			ROUND(
				(
					(
						(SELECT SUM(cvp2021.votos) FROM casillas_votos_partidos_2021 cvp2021 WHERE cvp2021.id_seccion_ine = si.id AND cvp2021.tipo = '{$tipo_eleccion}' )
						+
						(SELECT SUM(cv2021.votos_nulos) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' )
						+
						(SELECT SUM(cv2021.votos_can_nreg) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' )
					)
					/
					(
					SELECT SUM(cv2021.lista_nominal) FROM casillas_votos_2021 cv2021 WHERE cv2021.id_seccion_ine = si.id AND cv2021.tipo = '{$tipo_eleccion}' ))*100,2
					) AS participacion_ciudadana,
					IF(si.tipo=1,'Urbana','Rural') AS tipo
		FROM secciones_ine si 
		WHERE 1 AND si.id_distrito_local = {$id_distrito_local}  ";
		$result = $conexion->query($sql);
		while($row=$result->fetch_assoc()){
			//////
			$datos_secciones_ine[$row['id_seccion_ine']]=$row;
			$id_seccion_ine = $row['id_seccion_ine'];
			//////
			if(empty($partidos_sin_coaliciones[$id_seccion_ine])){
				$datos_secciones_ine[$id_seccion_ine]['lista_nominal'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['casillas'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['votos_validos'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['votos_totales'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['votos_nulos'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['votos_can_nreg'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['participacion_ciudadana'] = 0;
				$datos_secciones_ine[$id_seccion_ine]['ganador'] = array(
																			'clave' => 'NOTIENE',
																			'nombre_corto' => 'NOTIENE',
																			'individual' => 0,
																			'logo' => 'no_data.png',
																			'votos' => 0,
																			'id_partido_2021' => 0,
																			'porcentaje' => 0,
																			'coaliciones' => '',
																			'votos_coalicion' => 0,
																		);
				$datos_secciones_ine[$id_seccion_ine]['secundario'] = array(
																			'clave' => 'NOTIENE',
																			'nombre_corto' => 'NOTIENE',
																			'individual' => 0,
																			'logo' => 'no_data.png',
																			'votos' => 0,
																			'id_partido_2021' => 0,
																			'porcentaje' => 0,
																			'coaliciones' => '',
																			'votos_coalicion' => 0,
																		);
				$datos_secciones_ine[$id_seccion_ine]['semaforo'] = 'gris';
				$datos_secciones_ine[$id_seccion_ine]['diferencia_votos'] = '0';
			}else{
				/*
				Esta parte busca todos los sin caliciones para que vea cual es el ganador despues agrego a la seccion todos la suma de los partidos y sus coaliciones para saber cual es el ganadro
				La formula usada es
				1.- Saco en solitario 
				2.- Luego veo en donde tiene coalcion y sumo la totalidad del voto recuerda que aqui es por candidato y no por partido
				3.- saco los solitarios en individuales y los sumo los totales
				4.- luego al final de sear sin coalicion busco el ganador
				5.- si el ganador coincide con el del sistema entonces saco el segundo lugar para sacar la diferencia en votos
				6.- Si no coincide el ganador con el del sistema entonces es perdida.
				*/ 
				unset($partidos);
				foreach ($partidos_sin_coaliciones[$id_seccion_ine] as $key => $value) {
					$nombre_corto = $value['nombre_corto'];
					//$totales_partidos[$nombre_corto] = $value['votos'];
					unset($nombre_cortos);
					unset($totales_partidos);
					$total = 0;
					$sistema_coaliciones = false;
					foreach ($partidos_coaliciones[$id_seccion_ine] as $keyT => $valueT) {
						$nombre_corto_coalicion = $valueT['nombre_corto'];
						//sacamos el array del nombre_corto_coalicion
						$nombre_corto_coalicion;
						$coaliciones = explode("_", $nombre_corto_coalicion);
						$pos = false;
						foreach ($coaliciones as $keyX => $valueX) {
							if($nombre_corto ==  $valueX){
								$pos = true;
							}
						}
						
						if ($pos == true) {
							$sistema_coaliciones = true;
							$totales_partidos[$nombre_corto] = $totales_partidos[$nombre_corto] + $valueT['votos'];
							foreach ($coaliciones as $keyC => $valueC) {
								if($nombre_corto != $valueC){
									$nombre_cortos[$valueC] = $partidos_sin_coaliciones[$id_seccion_ine][$valueC]['votos'];
								}
							}
							$total = 0;
							foreach ($nombre_cortos as $keyL => $valueL) {
								$total = $valueL + $total;
							}
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_partido_2021'] = $value['id_partido_2021'];
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['nombre'] = $value['nombre_corto'];
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['clave'] = $value['clave'];
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['logo'] = $value['logo'];
							//
							$total = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['total'] = $value['votos'] + $totales_partidos[$nombre_corto] + $total ;
							//
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['individual'] = $value['votos'];

							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['coalicion'] = $totales_partidos[$nombre_corto];

							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['coalicion_votos'] = $total-$value['votos'];

							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['nombres_cortos'] = $valueT['nombre_corto'];
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['partidos_coaliciones'] = $nombre_cortos;
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'] =  implode(",", array_keys($nombre_cortos));

							$partidos[$row['id_seccion_ine']][$nombre_corto]['total'] = $total;
							$partidos[$row['id_seccion_ine']][$nombre_corto]['individual'] = $value['votos'];
							$partidos[$row['id_seccion_ine']][$nombre_corto]['clave'] = $value['clave']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['id_partido_2021'] = $value['id_partido_2021']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['logo'] = $value['logo']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'] = implode(",", array_keys($nombre_cortos));
							$partidos[$row['id_seccion_ine']][$nombre_corto]['votos_coalicion'] = $total-$value['votos'];
							$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos_array']=$nombre_cortos;
						}
					}

					$total = 0;
					if($sistema_coaliciones==false){
						/// aqui entra cuando el partido no tiene caliciones o va solo
						$nombre_corto = $value['nombre_corto'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_partido_2021'] = $value['id_partido_2021'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['nombre'] = $value['nombre_corto'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['clave'] = $value['clave'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['logo'] = $value['logo'];
						//
						$total = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['total'] = $value['votos'] + $totales_partidos[$nombre_corto] + $total ;
						//
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['individual'] = $value['votos'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['coalicion'] = $totales_partidos[$nombre_corto];

						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['coalicion_votos'] = $total-$value['votos'];

						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['nombres_cortos'] = $valueT['nombre_corto'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['partidos_coaliciones'] = $nombre_cortos;
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'] =  implode(",", array_keys($nombre_cortos));

						$partidos[$row['id_seccion_ine']][$nombre_corto]['total'] = $total;
						$partidos[$row['id_seccion_ine']][$nombre_corto]['individual'] = $value['votos'];
						$partidos[$row['id_seccion_ine']][$nombre_corto]['clave'] = $value['clave']; 
						$partidos[$row['id_seccion_ine']][$nombre_corto]['id_partido_2021'] = $value['id_partido_2021']; 
						$partidos[$row['id_seccion_ine']][$nombre_corto]['logo'] = $value['logo'];
						$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'] = implode(",", array_keys($nombre_cortos));
						$partidos[$row['id_seccion_ine']][$nombre_corto]['votos_coalicion'] = $total-$value['votos'];
						$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos_array']=$nombre_cortos;
					}
				}
				// sacar ganador
				$partido_ganador_votos = 0 ;
				$partido_ganador_nombre_corto = '';
				foreach ($partidos[$row['id_seccion_ine']] as $keyZ => $valueZ) {
					if($valueZ['total'] > $partido_ganador_votos){
						$partido_ganador_votos = $valueZ['total'];
						$partido_ganador_nombre_corto = $keyZ;
						$partido_ganador_clave = $valueZ['clave'];
						$partido_ganador_id_partido_2021 = $valueZ['id_partido_2021'];
						$partido_ganador_logo = $valueZ['logo'];
						$partido_ganador_coaliciones = $valueZ['partidos_nombres_cortos'];
						$partido_ganador_individual = $valueZ['individual'];
						$partido_ganador_votos_coalicion = $valueZ['votos_coalicion'];
						$partido_ganador_nombres_cortos_array = $valueZ['partidos_nombres_cortos_array'];
					}
				}
				///partido ganador add

				// sacar secundario
				$partido_secundario_votos = 0 ;
				$partido_secundario_nombre_corto = '';

				$mismacoalicion = false;
				foreach ($partido_ganador_nombres_cortos_array as $keyCC => $valueCC) {
					if($row['partido_sistema_nombre_corto'] == $keyCC){
						$mismacoalicion = true;
					}
				}

				if($partido_ganador_id_partido_2021 == $id_partido_2021 || $mismacoalicion == true ){
					$tipo=true;
					///busca el 2 lugar excluyendo a si mismo
					/// busca el secundario por si el partido configurado gano
					foreach ($partidos[$row['id_seccion_ine']] as $keyZ => $valueZ) {
						if($valueZ['total'] > $partido_secundario_votos && $valueZ['id_partido_2021'] != $partido_ganador_id_partido_2021 ){ 
							if($datos_secciones_ine[$row['id_seccion_ine']][$keyZ]['partidos_coaliciones'][$partido_ganador_nombre_corto]==''){
								$partido_secundario_votos = $valueZ['total'];
								$partido_secundario_nombre_corto = $keyZ;
								$partido_secundario_clave = $valueZ['clave'];
								$partido_secundario_id_partido_2021 = $valueZ['id_partido_2021'];
								$partido_secundario_logo = $valueZ['logo'];
								$partido_secundario_coaliciones = $valueZ['partidos_nombres_cortos'];
								$partido_secundario_individual = $valueZ['individual'];
								$partido_secundario_votos_coalicion = $valueZ['votos_coalicion'];
							}
						}
					}
				}else{
					/// colocamos el nombre corto del partido de configuracion principal
					//aqui entra si el partido que se configuro no gano
					$tipo=false;
					$nombre_corto = $row['partido_sistema_nombre_corto'];
					$partido_secundario_votos = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['total'];
					$partido_secundario_nombre_corto = $nombre_corto;
					$partido_secundario_clave = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['clave'];
					$partido_secundario_id_partido_2021 = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_partido_2021'];
					$partido_secundario_logo = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['logo'];
					$partido_secundario_coaliciones = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'];
					$partido_secundario_individual = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['individual'];
					$partido_secundario_votos_coalicion = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['coalicion_votos'];

				}
				$datos_secciones_ine[$id_seccion_ine]['ganador']['clave'] = $partido_ganador_clave;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['nombre_corto'] = $partido_ganador_nombre_corto;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['individual'] = $partido_ganador_individual;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['logo'] = $partido_ganador_logo;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['votos'] = $partido_ganador_votos;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['id_partido_2021'] = $partido_ganador_id_partido_2021;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['porcentaje'] = number_format(($partido_ganador_votos / $datos_secciones_ine[$id_seccion_ine]['votos_totales']*100),2,'.',',');
				$datos_secciones_ine[$id_seccion_ine]['ganador']['coaliciones'] = $partido_ganador_coaliciones;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['votos_coalicion'] = $partido_ganador_votos_coalicion;

				$datos_secciones_ine[$id_seccion_ine]['secundario']['clave'] = $partido_secundario_clave;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['nombre_corto'] = $partido_secundario_nombre_corto;

				$datos_secciones_ine[$id_seccion_ine]['secundario']['individual'] = $partido_secundario_individual;

				$datos_secciones_ine[$id_seccion_ine]['secundario']['logo'] = $partido_secundario_logo;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['votos'] = $partido_secundario_votos;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['id_partido_2021'] = $partido_secundario_id_partido_2021;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['porcentaje'] = number_format(($partido_secundario_votos / $datos_secciones_ine[$id_seccion_ine]['votos_totales']*100),2,'.',',');
				$datos_secciones_ine[$id_seccion_ine]['secundario']['coaliciones'] = $partido_secundario_coaliciones;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['votos_coalicion'] = $partido_secundario_votos_coalicion;

				if($tipo=='true'){
					$diferencia = $partido_ganador_votos - $partido_secundario_votos;
					if($diferencia <= $votos_semaforo_amarillo){
						$datos_secciones_ine[$id_seccion_ine]['semaforo'] = 'amarillo';
					}else{
						$datos_secciones_ine[$id_seccion_ine]['semaforo'] = 'verde';
					}
				}else{
					$diferencia = $partido_ganador_votos - $partido_secundario_votos;
					$datos_secciones_ine[$id_seccion_ine]['semaforo'] = 'rojo';
				}
				$datos_secciones_ine[$id_seccion_ine]['diferencia_votos'] = $diferencia;
			}
		}
	} 

	$_SESSION['reporte_Sistema']['sql'] = $datos_secciones_ine;
 
?>
	<style type="text/css">
		.divMapa{
			width:450px;
			height:200px;
			margin: -10px 0px 0px 10px;
		}
		.info_titulo{
			width:30%;
			float:left;
			height:40px;
			text-align:center;
			border: 1px solid #e5e5e5;
			padding: 2px;
			background-color:#e5e5e5;
			vertical-align: middle;
		}
		.info_seccion_ganador{
			width:40%;
			float:left;
			height:40px;
			text-align:left;
			border: 1px solid #cecece;
			padding: 6px 0px 0px 4px ;
			background-color:#cecece;
		}
		.info_seccion_ganador_button{
			width:30%;
			float:left;
			height:40px;
			text-align:left;
			border: 1px solid #cecece;
			padding: 6px 5px 0px 2px ;
			background-color:#cecece;
		}

		.info_seccion_ganador_button > button{
			background-color: #808080;
			border: none;
			color: white;
			text-align: center;
			text-decoration: none;
			cursor: pointer;
			padding: 5px;
			width: 100%;
		}

		.info_seccion_ganador_button > button:hover{
			background-color: #b0b0b0;
		}

		.info_seccion_ganador_button > button:active{
			background-color: black;  
		}


		.datos_votos{
			width:50%;
			float:left;
			height:85px;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		.logo_partido{
			width:30%;
			float:left;
			height:70px;
			text-align:left;
			border: 1px solid #00923f;
			padding: 10px 0px 2px 5px;
			background-color:#e36962;
			color:white;
		}
		.datos_partido{
			width:70%;
			float:left;
			height:70px;
			text-align:left;
			border: 1px solid #00923f;
			padding: 5px 0px 2px 5px;
			background-color:#e36962;
			color:white;
		}
		.datos{
			width:50%;
			float:left;
			height:55px;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		@media screen and (max-width: 1281px) {
			.divMapa{
				width:167px;
				height:230px;
				margin: -10px 0px 0px 10px;
			}
			.info_titulo,.info_seccion_ganador_button{
				width:100%;
			}
			.info_seccion_ganador{
				width:100%;
			}
			.datos_votos{
				width:100%;
				height: 120px;
			}
			.datos{
				width:100%;
				height: 70px;
			}
			.logo_partido{
				width:100%;
				height: 60px;
			}
			.datos_partido{
				width:100%;
				height: 105px;
			}
			.gm-style-iw  { 
			    min-width: 110px !important; 
			    padding: 2px 12px 2px 0px !important;
			}
			/*
			.gm-style-iw div, .gm-style-iw {
			    overflow: hidden !important;
			    max-width: 9999px !important;
			    max-height: 9999px !important;
			}
			*/
		}
	</style>
	<script type="text/javascript">
		function myMap(){
			zoom=9;
			var latitud='<?=$datos_distritos_locales[$id_distrito_local]['latitud'] ?>';
			var longitud='<?=$datos_distritos_locales[$id_distrito_local]['longitud'] ?>';
			//orientacion del mapa o vision
			var style = 
			[
				{
					"featureType": "administrative",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#d6e2e6"
						}
					]
				},
				{
					"featureType": "administrative",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#cfd4d5"
						}
					]
				},
				{
					"featureType": "administrative",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#7492a8"
						}
					]
				},
				{
					"featureType": "administrative.neighborhood",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"lightness": 25
						}
					]
				},
				{
					"featureType": "landscape.man_made",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#dde2e3"
						}
					]
				},
				{
					"featureType": "landscape.man_made",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#cfd4d5"
						}
					]
				},
				{
					"featureType": "landscape.natural",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#dde2e3"
						}
					]
				},
				{
					"featureType": "landscape.natural",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#7492a8"
						}
					]
				},
				{
					"featureType": "landscape.natural.terrain",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "poi",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#dde2e3"
						}
					]
				},
				{
					"featureType": "poi",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#588ca4"
						}
					]
				},
				{
					"featureType": "poi",
					"elementType": "labels.icon",
					"stylers": [
						{
							"saturation": -100
						}
					]
				},
				{
					"featureType": "poi.park",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#a9de83"
						}
					]
				},
				{
					"featureType": "poi.park",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#bae6a1"
						}
					]
				},
				{
					"featureType": "poi.sports_complex",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#c6e8b3"
						}
					]
				},
				{
					"featureType": "poi.sports_complex",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#bae6a1"
						}
					]
				},
				{
					"featureType": "road",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#41626b"
						}
					]
				},
				{
					"featureType": "road",
					"elementType": "labels.icon",
					"stylers": [
						{
							"saturation": -45
						},
						{
							"lightness": 10
						},
						{
							"visibility": "on"
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#c1d1d6"
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "geometry.stroke",
					"stylers": [
						{
							"color": "#a6b5bb"
						}
					]
				},
				{
					"featureType": "road.highway",
					"elementType": "labels.icon",
					"stylers": [
						{
							"visibility": "on"
						}
					]
				},
				{
					"featureType": "road.highway.controlled_access",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#9fb6bd"
						}
					]
				},
				{
					"featureType": "road.arterial",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#ffffff"
						}
					]
				},
				{
					"featureType": "road.local",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#ffffff"
						}
					]
				},
				{
					"featureType": "transit",
					"elementType": "labels.icon",
					"stylers": [
						{
							"saturation": -70
						}
					]
				},
				{
					"featureType": "transit.line",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#b4cbd4"
						}
					]
				},
				{
					"featureType": "transit.line",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#588ca4"
						}
					]
				},
				{
					"featureType": "transit.station",
					"elementType": "all",
					"stylers": [
						{
							"visibility": "off"
						}
					]
				},
				{
					"featureType": "transit.station",
					"elementType": "labels.text.fill",
					"stylers": [
						{
							"color": "#008cb5"
						},
						{
							"visibility": "on"
						}
					]
				},
				{
					"featureType": "transit.station.airport",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"saturation": -100
						},
						{
							"lightness": -5
						}
					]
				},
				{
					"featureType": "water",
					"elementType": "geometry.fill",
					"stylers": [
						{
							"color": "#a6cbe3"
						}
					]
				}
			];
			var myLatlng = new google.maps.LatLng(latitud, longitud); 
			var myOptions = {
				zoom: zoom,
				center: myLatlng,
				styles: style,
				panControl: true,
				zoomControl: true,
				mapTypeControl: true,
				streetViewControl: false,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				scrollwheel: true,
				minZoom: zoom - 113,
				maxZoom: zoom + 113,
			}

			var pinImage = new google.maps.MarkerImage('https://maps.google.com/mapfiles/ms/icons/red-dot.png');
			var map = new google.maps.Map(document.getElementById("mapa"), myOptions); 
			marker1 = new google.maps.Marker({ 
				position: myLatlng,
				draggable: false,
				icon: pinImage,
			});

			<?php
			foreach ($distritos_locales_parametrosDatosMapa as $key => $value) {
				if($id_distrito_local != $key){
					$paths = "";
					foreach ($value as $keyT => $valueT) {
						$path = "secciones_ine_".$key."_".$keyT;
						echo $path." = [";
						foreach ($valueT as $keyH => $valueH) {
							echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
						}
						echo "];";
						$paths .= $path.",";
					}
					if($datos_distritos_locales[$key]['partido_ganador_background']=="" || $key != $id_distrito_local ){
						$datos_distritos_locales[$key]['partido_ganador_border'] = "000000";
						$datos_distritos_locales[$key]['partido_ganador_background'] = "000000";
					}
					?>
					municipio<?= $key ?> = new google.maps.Polygon({
						paths: [<?= $paths ?>],
						strokeColor: "#<?= $datos_distritos_locales[$key]['partido_ganador_border'] ?>",
						strokeOpacity: 0.8,
						strokeWeight: 1,
						fillColor: "#<?= $datos_distritos_locales[$key]['partido_ganador_background'] ?>",
						fillOpacity: 0.5,
					});
					municipio<?= $key ?>.setMap(map);
					<?php
				}
			}
			foreach ($secciones_ine_parametrosDatosMapa as $key => $value) {
				$datos_secciones_ine[$key]['numero'];
				$datos_secciones_ine[$key]['latitud'];
				$datos_secciones_ine[$key]['longitud'];
				$div = '<div class="divMapaSeccion">
							<h4>Sección: '.$datos_secciones_ine[$key]['numero'].'</h4>
						</div>';
				$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
				$paths = "";
				foreach ($value as $keyT => $valueT) {
					$path = "secciones_ine_".$key."_".$keyT;
					echo $path." = [";
					foreach ($valueT as $keyH => $valueH) {
						echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
					}
					echo "];";

					$paths .= $path.",";
				}
				if($datos_secciones_ine[$key]['semaforo']=="rojo" ){
					$datos_secciones_ine[$key]['partido_ganador_border'] = "FF0000";
					$datos_secciones_ine[$key]['partido_ganador_background'] = "FF0000";
				}
				if($datos_secciones_ine[$key]['semaforo']=="verde" ){
					$datos_secciones_ine[$key]['partido_ganador_border'] = "00ff00";
					$datos_secciones_ine[$key]['partido_ganador_background'] = "00ff00";
				}
				if($datos_secciones_ine[$key]['semaforo']=="amarillo" ){
					$datos_secciones_ine[$key]['partido_ganador_border'] = "ffff00";
					$datos_secciones_ine[$key]['partido_ganador_background'] = "ffff00";
				}
				if($datos_secciones_ine[$key]['semaforo']=="gris" ){
					$datos_secciones_ine[$key]['partido_ganador_border'] = "8d8d8d";
					$datos_secciones_ine[$key]['partido_ganador_background'] = "8d8d8d";
				}
				if($datos_secciones_ine[$key]['partido_ganador_background']=="" ){
					$datos_secciones_ine[$key]['partido_ganador_border'] = "000000";
					$datos_secciones_ine[$key]['partido_ganador_background'] = "000000";
				}
				?>
				secciones_area<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: "#<?= $datos_secciones_ine[$key]['partido_ganador_border'] ?>",
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: "#<?= $datos_secciones_ine[$key]['partido_ganador_background'] ?>",
					fillOpacity: 0.35,
				});
				secciones_area<?= $key ?>.setMap(map);
				<?php
			}
			?>
			var marcadores = [
			<?php
			foreach ($datos_distritos_locales as $key => $value) {
				if($value['id'] != $id_distrito_local){
					echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$value['partido_ganador_logo']."' ],";
				}
			}
			foreach ($datos_secciones_ine as $key => $value) {
				echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$value['semaforo']."' ],";
			}
			?>
			];


			///informacion del marcador
			var infoWindowContent = [
					<?php
					foreach ($datos_distritos_locales as $key => $value){
						if($value['id'] != $id_distrito_local){
							$votos_totales = 0;
							$votos_totales = $votos_totales + $value['votos_validos'] + $value['votos_nulos'] +$value['votos_can_nreg'];
							$porcentaje_partido_ganador = ($value['partido_ganador_votos'] / $value['votos_validos'] )*100;
							$porcentaje_partido_ganador = truncar($porcentaje_partido_ganador, 2);
							$porcentaje_partido_sistema = ($value['partido_sistema_votos'] / $value['votos_validos'] )*100;
							$porcentaje_partido_sistema = truncar($porcentaje_partido_sistema, 2);
							$diferencia_votos = $value['partido_ganador_votos'] - $value['partido_sistema_votos'];
							$participacion_ciudadana = 0;
							if($votos_totales != 0){
								$participacion_ciudadana = ($votos_totales / $value['lista_nominal'] ) * 100;
							}else{
								$participacion_ciudadana =0 ;
							}
							$logo = $value['partido_ganador_logo'];
							$logo_partido_sistema = $value['partido_sistema_logo'];
							$div = '<div class="divMapa">
										<div class="info_content">
											<h4>Municipio: '.$value['municipio'].'</h4>
											<div class="info_titulo">
												<h5>Votación 2021</h5>
											</div>
											<div class="info_seccion_ganador">
												Lista Nominal: <b>'.number_format($value['lista_nominal'], 0, '.', ',').'</b><br>
												Partido Ganador: <b>'.$value['partido_ganador_nombre_corto'].'</b><br>
											</div>
											<div class="info_seccion_ganador_button">
												<button class="button button4" onclick="verMasMunicipio('.$value['id'].')">Ver Más</button>
											</div>
										</div>
										<div class="datos_votos">
											<p>
												Votos Válidos: <b>'.number_format($value['votos_validos'], 0, '.', ',').'</b><br>
												Votos Nulos: <b>'.number_format($value['votos_nulos'], 0, '.', ',').'</b><br>
												Votos CAN NREG: <b>'.number_format($value['votos_can_nreg'], 0, '.', ',').'</b><br>
												Votos Totales: <b>'.number_format($votos_totales, 0, '.', ',').'</b><br>
												P. Ciudadana: <b>'.number_format($participacion_ciudadana, 2, '.', ',').'%</b><br>
											</p>
										</div>
										<div class="datos_votos">
											<div style="width:100%;text-align:center;padding:0px">
												<img src="images/logos_partidos/'.$logo.'" style="width: 30px ">
											</div>
											<p style="padding:0px;text-align:left;">
												Votos Ganador: <b>'.number_format($value['partido_ganador_votos'], 0, '.', ',').'</b><br>
												Votos % Ganador: <b>'.$porcentaje_partido_ganador.'%</b><br>
											</p>
										</div>
										<div class="logo_partido">
											<center>
												<img src="images/logos_partidos/'.$logo_partido_sistema.'" style="width: 40px ">
											</center>
										</div>
										<div class="datos_partido">
											<p>
												Votos Totales: <b>'.number_format($value['partido_sistema_votos'], 0, '.', ',').'</b><br>
												Votos % Partido: <b>'.$porcentaje_partido_sistema.'%</b><br>
												Dif. de Votos: <b>'.number_format($diferencia_votos, 0, '.', ',').'</b><br>
											</p>
										</div>
									</div>';
							$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
							?>
							['<?= $div ?>'],
							<?php
						}
					}
					foreach ($datos_secciones_ine as $key => $value){
						$votos_totales = 0;
						$votos_totales = $votos_totales + $value['votos_validos'] + $value['votos_nulos'] +$value['votos_can_nreg'];
						$logo = $value['ganador']['logo']; 
						$logo_partido_sistema = $value['secundario']['logo']; 
						if($value['semaforo']=='rojo'){
							$color_semaforo = '#ff6961';
						}elseif($value['semaforo']=='amarillo'){
							$color_semaforo = '#fdfd96';
						}else{
							$color_semaforo = '#77dd77';
						}
						$div = '<div class="divMapa">
									<div class="info_content">
										<h4>Sección: '.$value['numero'].'</h4>
										<div class="info_titulo">
											<h5>Votación 2021</h5>
										</div>
										<div class="info_seccion_ganador">
											Lista Nominal: <b>'.number_format($value['lista_nominal'], 0, '.', ',').'</b><br>
											Partido Ganador: <b>'.$value['ganador']['nombre_corto'].'</b><br>
										</div> 
										<div class="info_seccion_ganador_button">
											<div style="background-color:'.$color_semaforo.';padding:5px;margin-top:2px;text-align:center;color:black"><b>'.strtoupper($value['semaforo']).'</b></div>
										</div>
									</div>
									<div class="datos_votos">
										<p>
											Votos Válidos: <b>'.number_format($value['votos_validos'], 0, '.', ',').'</b><br>
											Votos Nulos: <b>'.number_format($value['votos_nulos'], 0, '.', ',').'</b><br>
											Votos CAN NREG: <b>'.number_format($value['votos_can_nreg'], 0, '.', ',').'</b><br>
											Votos Totales: <b>'.number_format($votos_totales, 0, '.', ',').'</b><br>
											P. Ciudadana: <b>'.number_format($value['participacion_ciudadana'], 2, '.', ',').'%</b><br>
										</p>
									</div>
									<div class="datos_votos">
										<div style="width:100%;text-align:center;padding:0px">
											<img src="images/logos_partidos/'.$logo.'" style="width: 30px ">
										</div>
										<p style="padding:0px;text-align:left;">
											Votos Ganador: <b>'.number_format($value['ganador']['votos'], 0, '.', ',').'</b><br>
											Votos % Ganador: <b>'.$value['ganador']['porcentaje'].'%</b><br>
											Coaliciones: <b>'.$value['ganador']['coaliciones'].'</b><br>
										</p>
									</div>
									<div class="logo_partido">
										<center>
											<img src="images/logos_partidos/'.$logo_partido_sistema.'" style="width: 40px ">
										</center>
									</div>
									<div class="datos_partido">
										<p>
											Votos Totales: <b>'.number_format($value['secundario']['votos'], 0, '.', ',').'</b><br>
											Votos % Partido: <b>'.$value['secundario']['porcentaje'].'%</b><br>
											Dif. de Votos: <b>'.number_format($value['diferencia_votos'], 0, '.', ',').'</b><br>
											Coaliciones: <b>'.$value['secundario']['coaliciones'].'</b><br>
										</p>
									</div>
									<div class="datos"> 
										Apoyos y Programas: <b>'.number_format($value['apoyos_programas'], 0, '.', ',').'</b>
										<br>
										Acciones y Obras: <b>'.number_format($value['acciones_obras'], 0, '.', ',').'</b>
										<br>
										Grupos de Interes: <b> '.number_format($value['grupos_interes'], 0, '.', ',').'</b>
									</div>
									<div class="datos">
										Reg. Ciudadanos: <b>'.number_format($value['ciudadanos_registrados'], 0, '.', ',').'</b>
										<br>
										Militantes: <b>'.number_format($value['militantes'], 0, '.', ',').'</b>
										<br>
										Funcionario: <b>'.number_format($value['funcionarios'], 0, '.', ',').'</b>
									</div>
								</div>';
						$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
						?>
						['<?= $div ?>'],
						<?php
					}
				?>
			];
			var infowindow = new google.maps.InfoWindow();
			var marker, i;


			for (i = 0; i < marcadores.length; i++) {
				if(marcadores[i][3]==''){
					var icon = {
						//url: 'assets/images/iconos/cd-icon-location.png', // url
						//scaledSize: new google.maps.Size(20, 22), // scaled size
					};
				}else{
					if(marcadores[i][3]=='rojo'){
						var icon = {
							url: 'https://labs.google.com/ridefinder/images/mm_20_red.png', // url
							//url : 'images/iconos_partidos/'+ marcadores[i][3],
							// width, height
							scaledSize: new google.maps.Size(16, 28), // scaled size
							 
						};
					}
					if(marcadores[i][3]=='amarillo'){
						var icon = {
							url: 'https://labs.google.com/ridefinder/images/mm_20_yellow.png', // url
							//url : 'images/iconos_partidos/'+ marcadores[i][3],
							// width, height
							scaledSize: new google.maps.Size(16, 28), // scaled size
							 
						};
					}
					if(marcadores[i][3]=='verde'){
						var icon = {
							url: 'https://labs.google.com/ridefinder/images/mm_20_green.png', // url
							//url : 'images/iconos_partidos/'+ marcadores[i][3],
							// width, height
							scaledSize: new google.maps.Size(16, 28), // scaled size
							 
						};
					}
					if(marcadores[i][3]=='gris'){
						var icon = {
							url: 'https://labs.google.com/ridefinder/images/mm_20_gray.png', // url
							//url : 'images/iconos_partidos/'+ marcadores[i][3],
							// width, height
							scaledSize: new google.maps.Size(16, 28), // scaled size
						};
					}
				}

				marker = new google.maps.Marker({
					position: new google.maps.LatLng(marcadores[i][1], marcadores[i][2]),
					map: map,
					icon: icon
				});


				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						infowindow.setContent(infoWindowContent[i][0]);
						infowindow.open(map, marker);
					}
				})(marker, i));
			}
		}
		function getCoordsLimites(marker){ 
			//var latitud=document.getElementById("latitud").value=marker.getPosition().lat();
			// var longitud=document.getElementById("longitud").value=marker.getPosition().lng(); 
		}
	</script> 
	<div id="mapa" style="width:100%;height:400px;"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>  
	