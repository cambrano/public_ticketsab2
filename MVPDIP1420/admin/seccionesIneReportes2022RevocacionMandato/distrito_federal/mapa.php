<?php
	include __DIR__.'/../../functions/security.php'; 
	@session_start(); 

	//var_dump($_POST);
	if(!empty($_POST)){
		include __DIR__."/../../functions/preguntas_2022_revocacion_mandato.php";
		include __DIR__."/../../functions/distritos_federales_parametros.php"; 
		include __DIR__."/../../functions/distritos_federales.php";
		include __DIR__."/../../functions/secciones_ine_parametros.php";
		$pregunta_2022_revocacion_mandatoPrincipalDatos = pregunta_2022_revocacion_mandatoPrincipalDatos();
		//$pregunta_2022_revocacion_mandatoPrincipalDatos = pregunta_2022_revocacion_mandatoPrincipalDatos();
		$id_pregunta_2022_revocacion_mandato = $pregunta_2022_revocacion_mandatoPrincipalDatos['id'] = 1 ;// Partidos 2018
		$tipo_eleccion = $configuracion_matriz['tipo_eleccion'] = '0';// 0 - Ayuntamiento | 1 - Distrito Federal | 2 - Distrito Federal
		/// en el formulario segun el tipo sera lo que te va mostrar el select sera un onchange para que cambie funcionara igual que el de federalidades y municipio el principal seria tipo_eleccion y segun lo que escojas sera los partidos que te salgan 

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


		$id_distrito_federal = $_POST['searchTable'][0]['id_distrito_federal'];
		$id_seccion_ine = $_POST['searchTable'][0]['id_seccion_ine'];
		$tipo_seccion = $_POST['searchTable'][0]['tipo_seccion'];

		$distritos_federales_parametrosDatosMapa = distritos_federales_parametrosDatosMapa();
		$sql="
			 SELECT
				dl.id,
				dl.clave,
				dl.numero,
				dl.latitud,
				dl.longitud,
				(SELECT SUM(cvp2022.votos)  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 WHERE cvp2022.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_votos,

				(SELECT cvp2022.id_pregunta_2022_revocacion_mandato  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 WHERE cvp2022.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_id,

				(SELECT p2022Ganador.color_background  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 LEFT JOIN preguntas_2022_revocacion_mandato p2022Ganador ON p2022Ganador.id= cvp2022.id_pregunta_2022_revocacion_mandato  WHERE cvp2022.tipo = 0 AND p2022Ganador.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_background,

				(SELECT p2022Ganador.color_border  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 LEFT JOIN preguntas_2022_revocacion_mandato p2022Ganador ON p2022Ganador.id= cvp2022.id_pregunta_2022_revocacion_mandato  WHERE cvp2022.tipo = 0 AND p2022Ganador.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_border,

				(SELECT p2022Ganador.nombre_corto  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 LEFT JOIN preguntas_2022_revocacion_mandato p2022Ganador ON p2022Ganador.id= cvp2022.id_pregunta_2022_revocacion_mandato  WHERE cvp2022.tipo = 0 AND p2022Ganador.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_nombre_corto,

				(SELECT p2022Ganador.icono  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 LEFT JOIN preguntas_2022_revocacion_mandato p2022Ganador ON p2022Ganador.id= cvp2022.id_pregunta_2022_revocacion_mandato  WHERE cvp2022.tipo = 0 AND p2022Ganador.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_icono,

				(SELECT p2022Ganador.logo  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 LEFT JOIN preguntas_2022_revocacion_mandato p2022Ganador ON p2022Ganador.id= cvp2022.id_pregunta_2022_revocacion_mandato  WHERE cvp2022.tipo = 0 AND p2022Ganador.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_logo,

				p2022Sistema.id partido_sistema_id,
				p2022Sistema.nombre_corto partido_sistema_corto,

				p2022Sistema.color_border partido_sistema_border,

				p2022Sistema.color_background partido_sistema_background,

				p2022Sistema.logo partido_sistema_logo,

				(SELECT SUM(cvp2022.votos)  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 WHERE cvp2022.tipo = 0 AND cvp2022.id_distrito_federal = dl.id AND cvp2022.id_pregunta_2022_revocacion_mandato = p2022Sistema.id ) partido_sistema_votos,

				(SELECT SUM(cv2022.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cv2022 WHERE cv2022.tipo = 0 AND cv2022.id_distrito_federal = dl.id ) votos_nulos,

				(SELECT SUM(cv2022.votos_can_nreg) FROM casillas_votos_2022_revocacion_mandato cv2022 WHERE cv2022.tipo = 0 AND cv2022.id_distrito_federal = dl.id ) votos_can_nreg,

				(SELECT SUM(cv2022.lista_nominal) FROM casillas_votos_2022_revocacion_mandato cv2022 WHERE cv2022.tipo = 0 AND cv2022.id_distrito_federal = dl.id ) lista_nominal,

				(SELECT SUM(cvp2022.votos) FROM casillas_preguntas_2022_revocacion_mandato cvp2022 WHERE cvp2022.tipo = 0 AND cvp2022.id_distrito_federal = dl.id ) votos_validos

			FROM distritos_federales dl
			LEFT JOIN preguntas_2022_revocacion_mandato p2022Sistema
			ON p2022Sistema.principal = 1
			/*WHERE dl.id = '{$id_distrito_federal}' AND p2022Sistema.tipo = 0 */
			WHERE  p2022Sistema.tipo = 0 
		";
		if($id_distrito_federal !=''){
			$sql .= " AND dl.id = {$id_distrito_federal} ";
		}
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			
			$datos_distritos_federales[$row['id']]=$row;
			//$datos_distritos_federales[$row['id']]['poligonos']=$distritos_federales_parametrosDatosMapa[$row['id']];
			$num=$num+1;
		}
		
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','','',$id_distrito_federal,'','');
		if($id_seccion_ine!=''){
			$sql_secciones_ine = "AND cvp2018.id_seccion_ine IN ({$id_seccion_ine})";
		}

		$sql="SELECT 
				p2018.id AS id_pregunta_2022_revocacion_mandato,
				p2018.clave,
				p2018.nombre_corto,
				p2018.nombre,
				p2018.logo,
				p2018.color_border,
				p2018.color_background,
				'' AS clave_partidos_coaliciones,
				SUM(cvp2018.votos) votos,
				cvp2018.id_seccion_ine
			FROM casillas_preguntas_2022_revocacion_mandato cvp2018 
			LEFT JOIN preguntas_2022_revocacion_mandato p2018 ON cvp2018.id_pregunta_2022_revocacion_mandato = p2018.id
			WHERE cvp2018.tipo = '{$tipo_eleccion}'  AND cvp2018.id_distrito_federal = '{$id_distrito_federal}' {$sql_secciones_ine}
			GROUP BY cvp2018.id_seccion_ine,id_pregunta_2022_revocacion_mandato 
			ORDER BY cvp2018.id_seccion_ine,SUM(cvp2018.votos) DESC
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
			(SELECT m.numero FROM distritos_federales m WHERE m.id = si.id_distrito_federal) distrito_federal,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos sic WHERE sic.id_seccion_ine = si.id) ciudadanos_registrados,
			(SELECT p2018.nombre_corto FROM preguntas_2022_revocacion_mandato p2018 WHERE p2018.id = '{$id_pregunta_2022_revocacion_mandato}' ) partido_sistema_nombre_corto,
			(SELECT p2018.clave FROM preguntas_2022_revocacion_mandato p2018 WHERE p2018.id = '{$id_pregunta_2022_revocacion_mandato}' ) partido_sistema_clave,
			si.numero,
			(SELECT SUM(cv2018.lista_nominal) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' ) lista_nominal,
			(SELECT COUNT(*) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' ) casillas,
			(SELECT SUM(cvp2018.votos) FROM casillas_preguntas_2022_revocacion_mandato cvp2018 WHERE cvp2018.id_seccion_ine = si.id AND cvp2018.tipo = '{$tipo_eleccion}' )votos_validos,

			(SELECT SUM(cv2018.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' ) + (SELECT SUM(cv2018.votos_can_nreg) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' ) + (SELECT SUM(cvp2018.votos) FROM casillas_preguntas_2022_revocacion_mandato cvp2018 WHERE cvp2018.id_seccion_ine = si.id AND cvp2018.tipo = '{$tipo_eleccion}' ) AS votos_totales,

			(SELECT SUM(cv2018.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' ) votos_nulos,
			(SELECT SUM(cv2018.votos_can_nreg) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' ) votos_can_nreg,
			
			ROUND(
				(
					(
						(SELECT SUM(cvp2018.votos) FROM casillas_preguntas_2022_revocacion_mandato cvp2018 WHERE cvp2018.id_seccion_ine = si.id AND cvp2018.tipo = '{$tipo_eleccion}' )
						+
						(SELECT SUM(cv2018.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' )
						+
						(SELECT SUM(cv2018.votos_can_nreg) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' )
					)
					/
					(SELECT SUM(cv2018.lista_nominal) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' )
				)*100

				,2

			) AS participacion_ciudadana,si.tipo

		FROM secciones_ine si 
		WHERE 1 AND si.id_distrito_federal = {$id_distrito_federal} {$sql_secciones_ine} ";
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
																			'id' => 'x',
																			'clave' => 'NOTIENE',
																			'nombre_corto' => 'NOTIENE',
																			'nombre' => 'NO TIENE',
																			'individual' => 0,
																			'logo' => 'no_data.png',
																			'votos' => 0,
																			'id_pregunta_2022_revocacion_mandato' => 0,
																			'porcentaje' => 0,
																			'coaliciones' => '',
																			'votos_coalicion' => 0,
																		);
				$datos_secciones_ine[$id_seccion_ine]['secundario'] = array(
																			'id' => 'x',
																			'clave' => 'NOTIENE',
																			'nombre_corto' => 'NOTIENE',
																			'nombre' => 'NO TIENE',
																			'individual' => 0,
																			'logo' => 'no_data.png',
																			'votos' => 0,
																			'id_pregunta_2022_revocacion_mandato' => 0,
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
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_pregunta_2022_revocacion_mandato'] = $value['id_pregunta_2022_revocacion_mandato'];
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
							$partidos[$row['id_seccion_ine']][$nombre_corto]['id_pregunta_2022_revocacion_mandato'] = $value['id_pregunta_2022_revocacion_mandato']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['logo'] = $value['logo']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['color_background'] = $value['color_background']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['color_border'] = $value['color_border']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'] = implode(",", array_keys($nombre_cortos));
							$partidos[$row['id_seccion_ine']][$nombre_corto]['votos_coalicion'] = $total-$value['votos'];
							$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos_array']=$nombre_cortos;
							$partidos[$row['id_seccion_ine']][$nombre_corto]['nombre'] = $value['nombre']; 
						}
					}

					$total = 0;
					if($sistema_coaliciones==false){
						/// aqui entra cuando el partido no tiene caliciones o va solo
						$nombre_corto = $value['nombre_corto'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_pregunta_2022_revocacion_mandato'] = $value['id_pregunta_2022_revocacion_mandato'];
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
						$partidos[$row['id_seccion_ine']][$nombre_corto]['id_pregunta_2022_revocacion_mandato'] = $value['id_pregunta_2022_revocacion_mandato']; 
						$partidos[$row['id_seccion_ine']][$nombre_corto]['logo'] = $value['logo'];
						$partidos[$row['id_seccion_ine']][$nombre_corto]['color_background'] = $value['color_background']; 
						$partidos[$row['id_seccion_ine']][$nombre_corto]['color_border'] = $value['color_border'];
						$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'] = implode(",", array_keys($nombre_cortos));
						$partidos[$row['id_seccion_ine']][$nombre_corto]['votos_coalicion'] = $total-$value['votos'];
						$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos_array']=$nombre_cortos;
						$partidos[$row['id_seccion_ine']][$nombre_corto]['nombre'] = $value['nombre']; 
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
						$partido_ganador_id_pregunta_2022_revocacion_mandato = $valueZ['id_pregunta_2022_revocacion_mandato'];
						$partido_ganador_logo = $valueZ['logo'];
						$partido_ganador_coaliciones = $valueZ['partidos_nombres_cortos'];
						$partido_ganador_individual = $valueZ['individual'];
						$partido_ganador_color_background = $valueZ['color_background'];
						$partido_ganador_color_border = $valueZ['color_border'];
						$partido_ganador_votos_coalicion = $valueZ['votos_coalicion'];
						$partido_ganador_nombres_cortos_array = $valueZ['partidos_nombres_cortos_array'];
						$partido_ganador_nombre = $valueZ['nombre'];
						$partido_ganador_id = $valueZ['id_pregunta_2022_revocacion_mandato'];
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

				if($partido_ganador_id_pregunta_2022_revocacion_mandato == $id_pregunta_2022_revocacion_mandato || $mismacoalicion == true ){
					$tipo=true;
					///busca el 2 lugar excluyendo a si mismo
					/// busca el secundario por si el partido configurado gano
					foreach ($partidos[$row['id_seccion_ine']] as $keyZ => $valueZ) {
						if($valueZ['total'] > $partido_secundario_votos && $valueZ['id_pregunta_2022_revocacion_mandato'] != $partido_ganador_id_pregunta_2022_revocacion_mandato ){ 
							if($datos_secciones_ine[$row['id_seccion_ine']][$keyZ]['partidos_coaliciones'][$partido_ganador_nombre_corto]==''){
								$partido_secundario_votos = $valueZ['total'];
								$partido_secundario_nombre_corto = $keyZ;
								$partido_secundario_clave = $valueZ['clave'];
								$partido_secundario_id_pregunta_2022_revocacion_mandato = $valueZ['id_pregunta_2022_revocacion_mandato'];
								$partido_secundario_logo = $valueZ['logo'];
								$partido_secundario_coaliciones = $valueZ['partidos_nombres_cortos'];
								$partido_secundario_individual = $valueZ['individual'];
								$partido_secundario_color_background = $valueZ['color_background'];
								$partido_secundario_color_border = $valueZ['color_border'];
								$partido_secundario_votos_coalicion = $valueZ['votos_coalicion'];
								$partido_secundario_nombre = $valueZ['nombre'];
								$partido_secundario_id = $valueZ['id_pregunta_2022_revocacion_mandato'];
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
					$partido_secundario_id_pregunta_2022_revocacion_mandato = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_pregunta_2022_revocacion_mandato'];
					$partido_secundario_logo = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['logo'];
					$partido_secundario_coaliciones = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'];
					$partido_secundario_individual = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['individual'];
					$partido_secundario_color_background = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['color_background'];
					$partido_secundario_color_border = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['color_border'];
					$partido_secundario_votos_coalicion = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['coalicion_votos'];
					$partido_secundario_nombre = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['nombre'];
					$partido_secundario_id = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_pregunta_2022_revocacion_mandato'];

				}
				$datos_secciones_ine[$id_seccion_ine]['ganador']['clave'] = $partido_ganador_clave;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['nombre_corto'] = $partido_ganador_nombre_corto;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['individual'] = $partido_ganador_individual;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['logo'] = $partido_ganador_logo;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['votos'] = $partido_ganador_votos;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['id_pregunta_2022_revocacion_mandato'] = $partido_ganador_id_pregunta_2022_revocacion_mandato;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['porcentaje'] = number_format(($partido_ganador_votos / $datos_secciones_ine[$id_seccion_ine]['votos_totales']*100),2,'.',',');
				$datos_secciones_ine[$id_seccion_ine]['ganador']['coaliciones'] = $partido_ganador_coaliciones;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['votos_coalicion'] = $partido_ganador_votos_coalicion;

				$datos_secciones_ine[$id_seccion_ine]['ganador']['color_background'] = $partido_ganador_color_background;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['color_border'] = $partido_ganador_color_border;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['nombre'] = $partido_ganador_nombre;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['id'] = $partido_ganador_id;


				$datos_secciones_ine[$id_seccion_ine]['secundario']['clave'] = $partido_secundario_clave;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['nombre_corto'] = $partido_secundario_nombre_corto;

				$datos_secciones_ine[$id_seccion_ine]['secundario']['individual'] = $partido_secundario_individual;

				$datos_secciones_ine[$id_seccion_ine]['secundario']['logo'] = $partido_secundario_logo;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['votos'] = $partido_secundario_votos;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['id_pregunta_2022_revocacion_mandato'] = $partido_secundario_id_pregunta_2022_revocacion_mandato;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['porcentaje'] = number_format(($partido_secundario_votos / $datos_secciones_ine[$id_seccion_ine]['votos_totales']*100),2,'.',',');
				$datos_secciones_ine[$id_seccion_ine]['secundario']['coaliciones'] = $partido_secundario_coaliciones;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['votos_coalicion'] = $partido_secundario_votos_coalicion;

				$datos_secciones_ine[$id_seccion_ine]['secundario']['color_background'] = $partido_secundario_color_background;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['color_border'] = $partido_secundario_color_border;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['nombre'] = $partido_secundario_nombre;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['id'] = $partido_secundario_id;


				if($tipo=='true'){
					$diferencia = $partido_ganador_votos - $partido_secundario_votos;
				}else{
					$diferencia = $partido_ganador_votos - $partido_secundario_votos;
				}
				$datos_secciones_ine[$id_seccion_ine]['diferencia_votos'] = $diferencia;
			}
			/////aqui es para borrar las que no coinciden
			if(!empty($_POST['searchTable'][0]['partido_ganador_id'])){
				if($datos_secciones_ine[$id_seccion_ine]['ganador']['id_pregunta_2022_revocacion_mandato']==''){
					$datos_secciones_ine[$id_seccion_ine]['ganador']['id_pregunta_2022_revocacion_mandato']='x';
				}
				$datos_secciones_ine[$id_seccion_ine]['ganador']['id_pregunta_2022_revocacion_mandato'];
				$searchPregunta = explode(",", $_POST['searchTable'][0]['partido_ganador_id']);
				if (!in_array($datos_secciones_ine[$id_seccion_ine]['ganador']['id_pregunta_2022_revocacion_mandato'], $searchPregunta)) {
					unset($datos_secciones_ine[$id_seccion_ine]);
				}
			}
		}
	}else{
		$pregunta_2022_revocacion_mandatoPrincipalDatos = pregunta_2022_revocacion_mandatoPrincipalDatos();
		//$pregunta_2022_revocacion_mandatoPrincipalDatos = pregunta_2022_revocacion_mandatoPrincipalDatos();
		$id_pregunta_2022_revocacion_mandato = $pregunta_2022_revocacion_mandatoPrincipalDatos['id'] ;// Partidos 2018
		$tipo_eleccion = $configuracion_matriz['tipo_eleccion'] = '0';// 0 - Ayuntamiento | 1 - Distrito Federal | 2 - Distrito Federal
		/// en el formulario segun el tipo sera lo que te va mostrar el select sera un onchange para que cambie funcionara igual que el de federalidades y municipio el principal seria tipo_eleccion y segun lo que escojas sera los partidos que te salgan 
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
		$_POST['searchTable'][0]['id_distrito_federal']=$id_distrito_federal;
		$distritos_federales_parametrosDatosMapa = distritos_federales_parametrosDatosMapa();
		$sql="
			 SELECT
				dl.id,
				dl.clave,
				dl.numero,
				dl.latitud,
				dl.longitud,
				(SELECT SUM(cvp2022.votos)  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 WHERE cvp2022.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_votos,

				(SELECT cvp2022.id_pregunta_2022_revocacion_mandato  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 WHERE cvp2022.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_id,

				(SELECT p2022Ganador.color_background  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 LEFT JOIN preguntas_2022_revocacion_mandato p2022Ganador ON p2022Ganador.id= cvp2022.id_pregunta_2022_revocacion_mandato  WHERE cvp2022.tipo = 0 AND p2022Ganador.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_background,

				(SELECT p2022Ganador.color_border  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 LEFT JOIN preguntas_2022_revocacion_mandato p2022Ganador ON p2022Ganador.id= cvp2022.id_pregunta_2022_revocacion_mandato  WHERE cvp2022.tipo = 0 AND p2022Ganador.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_border,

				(SELECT p2022Ganador.nombre_corto  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 LEFT JOIN preguntas_2022_revocacion_mandato p2022Ganador ON p2022Ganador.id= cvp2022.id_pregunta_2022_revocacion_mandato  WHERE cvp2022.tipo = 0 AND p2022Ganador.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_nombre_corto,

				(SELECT p2022Ganador.icono  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 LEFT JOIN preguntas_2022_revocacion_mandato p2022Ganador ON p2022Ganador.id= cvp2022.id_pregunta_2022_revocacion_mandato  WHERE cvp2022.tipo = 0 AND p2022Ganador.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_icono,

				(SELECT p2022Ganador.logo  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 LEFT JOIN preguntas_2022_revocacion_mandato p2022Ganador ON p2022Ganador.id= cvp2022.id_pregunta_2022_revocacion_mandato  WHERE cvp2022.tipo = 0 AND p2022Ganador.tipo = 0 AND cvp2022.id_distrito_federal = dl.id GROUP BY cvp2022.id_pregunta_2022_revocacion_mandato ORDER BY SUM(cvp2022.votos) DESC LIMIT 1) partido_ganador_logo,

				p2022Sistema.id partido_sistema_id,
				p2022Sistema.nombre_corto partido_sistema_corto,

				p2022Sistema.color_border partido_sistema_border,

				p2022Sistema.color_background partido_sistema_background,

				p2022Sistema.logo partido_sistema_logo,

				(SELECT SUM(cvp2022.votos)  FROM casillas_preguntas_2022_revocacion_mandato cvp2022 WHERE cvp2022.tipo = 0 AND cvp2022.id_distrito_federal = dl.id AND cvp2022.id_pregunta_2022_revocacion_mandato = p2022Sistema.id ) partido_sistema_votos,

				(SELECT SUM(cv2022.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cv2022 WHERE cv2022.tipo = 0 AND cv2022.id_distrito_federal = dl.id ) votos_nulos,

				(SELECT SUM(cv2022.votos_can_nreg) FROM casillas_votos_2022_revocacion_mandato cv2022 WHERE cv2022.tipo = 0 AND cv2022.id_distrito_federal = dl.id ) votos_can_nreg,

				(SELECT SUM(cv2022.lista_nominal) FROM casillas_votos_2022_revocacion_mandato cv2022 WHERE cv2022.tipo = 0 AND cv2022.id_distrito_federal = dl.id ) lista_nominal,

				(SELECT SUM(cvp2022.votos) FROM casillas_preguntas_2022_revocacion_mandato cvp2022 WHERE cvp2022.tipo = 0 AND cvp2022.id_distrito_federal = dl.id ) votos_validos

			FROM distritos_federales dl
			LEFT JOIN preguntas_2022_revocacion_mandato p2022Sistema
			ON p2022Sistema.principal = 1
			/*WHERE dl.id = '{$id_distrito_federal}' AND p2022Sistema.tipo = 0 */
			WHERE  p2022Sistema.tipo = 0 
		";
		if($id_distrito_federal !=''){
			$sql .= " AND dl.id = {$id_distrito_federal} ";
		}
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			
			$datos_distritos_federales[$row['id']]=$row;
			//$datos_distritos_federales[$row['id']]['poligonos']=$distritos_federales_parametrosDatosMapa[$row['id']];
			$num=$num+1;
		}
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','','',$id_distrito_federal,'','');


		$sql="SELECT 
				p2018.id AS id_pregunta_2022_revocacion_mandato,
				p2018.clave,
				p2018.nombre_corto,
				p2018.nombre,
				p2018.logo,
				p2018.color_border,
				p2018.color_background,
				'' AS clave_partidos_coaliciones,
				SUM(cvp2018.votos) votos,
				cvp2018.id_seccion_ine
			FROM casillas_preguntas_2022_revocacion_mandato cvp2018 
			LEFT JOIN preguntas_2022_revocacion_mandato p2018 ON cvp2018.id_pregunta_2022_revocacion_mandato = p2018.id
			WHERE cvp2018.tipo = '{$tipo_eleccion}'  AND cvp2018.id_distrito_federal = '{$id_distrito_federal}'
			GROUP BY cvp2018.id_seccion_ine,id_pregunta_2022_revocacion_mandato 
			ORDER BY cvp2018.id_seccion_ine,SUM(cvp2018.votos) DESC
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


		$sql="SELECT 
			si.id AS id_seccion_ine,
			si.id,
			si.clave,
			si.latitud,
			si.longitud,
			(SELECT m.numero FROM distritos_federales m WHERE m.id = si.id_distrito_federal) distrito_federal,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos sic WHERE sic.id_seccion_ine = si.id) ciudadanos_registrados,
			(SELECT p2018.nombre_corto FROM preguntas_2022_revocacion_mandato p2018 WHERE p2018.id = '{$id_pregunta_2022_revocacion_mandato}' ) partido_sistema_nombre_corto,
			(SELECT p2018.clave FROM preguntas_2022_revocacion_mandato p2018 WHERE p2018.id = '{$id_pregunta_2022_revocacion_mandato}' ) partido_sistema_clave,
			si.numero,
			(SELECT SUM(cv2018.lista_nominal) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' ) lista_nominal,
			(SELECT COUNT(*) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' ) casillas,
			(SELECT SUM(cvp2018.votos) FROM casillas_preguntas_2022_revocacion_mandato cvp2018 WHERE cvp2018.id_seccion_ine = si.id AND cvp2018.tipo = '{$tipo_eleccion}' )votos_validos,

			(SELECT SUM(cv2018.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' ) + (SELECT SUM(cv2018.votos_can_nreg) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' ) + (SELECT SUM(cvp2018.votos) FROM casillas_preguntas_2022_revocacion_mandato cvp2018 WHERE cvp2018.id_seccion_ine = si.id AND cvp2018.tipo = '{$tipo_eleccion}' ) AS votos_totales,

			(SELECT SUM(cv2018.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' ) votos_nulos,
			(SELECT SUM(cv2018.votos_can_nreg) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' ) votos_can_nreg,
			
			ROUND(
				(
					(
						(SELECT SUM(cvp2018.votos) FROM casillas_preguntas_2022_revocacion_mandato cvp2018 WHERE cvp2018.id_seccion_ine = si.id AND cvp2018.tipo = '{$tipo_eleccion}' )
						+
						(SELECT SUM(cv2018.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' )
						+
						(SELECT SUM(cv2018.votos_can_nreg) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' )
					)
					/
					(SELECT SUM(cv2018.lista_nominal) FROM casillas_votos_2022_revocacion_mandato cv2018 WHERE cv2018.id_seccion_ine = si.id AND cv2018.tipo = '{$tipo_eleccion}' )
				)*100

				,2

			) AS participacion_ciudadana,si.tipo

		FROM secciones_ine si 
		WHERE 1 AND si.id_distrito_federal = {$id_distrito_federal}  ";
		//echo "<pre>";
		//echo $sql;
		//echo "</pre>";
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
																			'id' => 'x',
																			'clave' => 'NOTIENE',
																			'nombre_corto' => 'NOTIENE',
																			'nombre' => 'NO TIENE',
																			'individual' => 0,
																			'logo' => 'no_data.png',
																			'votos' => 0,
																			'id_pregunta_2022_revocacion_mandato' => 0,
																			'porcentaje' => 0,
																			'coaliciones' => '',
																			'votos_coalicion' => 0,
																		);
				$datos_secciones_ine[$id_seccion_ine]['secundario'] = array(
																			'id' => 'x',
																			'clave' => 'NOTIENE',
																			'nombre_corto' => 'NOTIENE',
																			'nombre' => 'NO TIENE',
																			'individual' => 0,
																			'logo' => 'no_data.png',
																			'votos' => 0,
																			'id_pregunta_2022_revocacion_mandato' => 0,
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
							$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_pregunta_2022_revocacion_mandato'] = $value['id_pregunta_2022_revocacion_mandato'];
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
							$partidos[$row['id_seccion_ine']][$nombre_corto]['id_pregunta_2022_revocacion_mandato'] = $value['id_pregunta_2022_revocacion_mandato']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['logo'] = $value['logo']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['color_background'] = $value['color_background']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['color_border'] = $value['color_border']; 
							$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'] = implode(",", array_keys($nombre_cortos));
							$partidos[$row['id_seccion_ine']][$nombre_corto]['votos_coalicion'] = $total-$value['votos'];
							$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos_array']=$nombre_cortos;
							$partidos[$row['id_seccion_ine']][$nombre_corto]['nombre'] = $value['nombre']; 
						}
					}

					$total = 0;
					if($sistema_coaliciones==false){
						/// aqui entra cuando el partido no tiene caliciones o va solo
						$nombre_corto = $value['nombre_corto'];
						$datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_pregunta_2022_revocacion_mandato'] = $value['id_pregunta_2022_revocacion_mandato'];
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
						$partidos[$row['id_seccion_ine']][$nombre_corto]['id_pregunta_2022_revocacion_mandato'] = $value['id_pregunta_2022_revocacion_mandato']; 
						$partidos[$row['id_seccion_ine']][$nombre_corto]['logo'] = $value['logo'];
						$partidos[$row['id_seccion_ine']][$nombre_corto]['color_background'] = $value['color_background']; 
						$partidos[$row['id_seccion_ine']][$nombre_corto]['color_border'] = $value['color_border'];
						$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'] = implode(",", array_keys($nombre_cortos));
						$partidos[$row['id_seccion_ine']][$nombre_corto]['votos_coalicion'] = $total-$value['votos'];
						$partidos[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos_array']=$nombre_cortos;
						$partidos[$row['id_seccion_ine']][$nombre_corto]['nombre'] = $value['nombre']; 
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
						$partido_ganador_id_pregunta_2022_revocacion_mandato = $valueZ['id_pregunta_2022_revocacion_mandato'];
						$partido_ganador_logo = $valueZ['logo'];
						$partido_ganador_coaliciones = $valueZ['partidos_nombres_cortos'];
						$partido_ganador_individual = $valueZ['individual'];
						$partido_ganador_color_background = $valueZ['color_background'];
						$partido_ganador_color_border = $valueZ['color_border'];
						$partido_ganador_votos_coalicion = $valueZ['votos_coalicion'];
						$partido_ganador_nombres_cortos_array = $valueZ['partidos_nombres_cortos_array'];
						$partido_ganador_nombre = $valueZ['nombre'];
						$partido_ganador_id = $valueZ['id_pregunta_2022_revocacion_mandato'];
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

				if($partido_ganador_id_pregunta_2022_revocacion_mandato == $id_pregunta_2022_revocacion_mandato || $mismacoalicion == true ){
					$tipo=true;
					///busca el 2 lugar excluyendo a si mismo
					/// busca el secundario por si el partido configurado gano
					foreach ($partidos[$row['id_seccion_ine']] as $keyZ => $valueZ) {
						if($valueZ['total'] > $partido_secundario_votos && $valueZ['id_pregunta_2022_revocacion_mandato'] != $partido_ganador_id_pregunta_2022_revocacion_mandato ){ 
							if($datos_secciones_ine[$row['id_seccion_ine']][$keyZ]['partidos_coaliciones'][$partido_ganador_nombre_corto]==''){
								$partido_secundario_votos = $valueZ['total'];
								$partido_secundario_nombre_corto = $keyZ;
								$partido_secundario_clave = $valueZ['clave'];
								$partido_secundario_id_pregunta_2022_revocacion_mandato = $valueZ['id_pregunta_2022_revocacion_mandato'];
								$partido_secundario_logo = $valueZ['logo'];
								$partido_secundario_coaliciones = $valueZ['partidos_nombres_cortos'];
								$partido_secundario_individual = $valueZ['individual'];
								$partido_secundario_color_background = $valueZ['color_background'];
								$partido_secundario_color_border = $valueZ['color_border'];
								$partido_secundario_votos_coalicion = $valueZ['votos_coalicion'];
								$partido_secundario_nombre = $valueZ['nombre'];
								$partido_secundario_id = $valueZ['id_pregunta_2022_revocacion_mandato'];
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
					$partido_secundario_id_pregunta_2022_revocacion_mandato = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_pregunta_2022_revocacion_mandato'];
					$partido_secundario_logo = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['logo'];
					$partido_secundario_coaliciones = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['partidos_nombres_cortos'];
					$partido_secundario_individual = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['individual'];
					$partido_secundario_color_background = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['color_background'];
					$partido_secundario_color_border = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['color_border'];
					$partido_secundario_votos_coalicion = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['coalicion_votos'];
					$partido_secundario_nombre = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['nombre'];
					$partido_secundario_id = $datos_secciones_ine[$row['id_seccion_ine']][$nombre_corto]['id_pregunta_2022_revocacion_mandato'];

				}
				$datos_secciones_ine[$id_seccion_ine]['ganador']['clave'] = $partido_ganador_clave;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['nombre_corto'] = $partido_ganador_nombre_corto;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['individual'] = $partido_ganador_individual;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['logo'] = $partido_ganador_logo;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['votos'] = $partido_ganador_votos;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['id_pregunta_2022_revocacion_mandato'] = $partido_ganador_id_pregunta_2022_revocacion_mandato;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['porcentaje'] = number_format(($partido_ganador_votos / $datos_secciones_ine[$id_seccion_ine]['votos_totales']*100),2,'.',',');
				$datos_secciones_ine[$id_seccion_ine]['ganador']['coaliciones'] = $partido_ganador_coaliciones;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['votos_coalicion'] = $partido_ganador_votos_coalicion;

				$datos_secciones_ine[$id_seccion_ine]['ganador']['color_background'] = $partido_ganador_color_background;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['color_border'] = $partido_ganador_color_border;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['nombre'] = $partido_ganador_nombre;
				$datos_secciones_ine[$id_seccion_ine]['ganador']['id'] = $partido_ganador_id;


				$datos_secciones_ine[$id_seccion_ine]['secundario']['clave'] = $partido_secundario_clave;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['nombre_corto'] = $partido_secundario_nombre_corto;

				$datos_secciones_ine[$id_seccion_ine]['secundario']['individual'] = $partido_secundario_individual;

				$datos_secciones_ine[$id_seccion_ine]['secundario']['logo'] = $partido_secundario_logo;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['votos'] = $partido_secundario_votos;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['id_pregunta_2022_revocacion_mandato'] = $partido_secundario_id_pregunta_2022_revocacion_mandato;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['porcentaje'] = number_format(($partido_secundario_votos / $datos_secciones_ine[$id_seccion_ine]['votos_totales']*100),2,'.',',');
				$datos_secciones_ine[$id_seccion_ine]['secundario']['coaliciones'] = $partido_secundario_coaliciones;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['votos_coalicion'] = $partido_secundario_votos_coalicion;

				$datos_secciones_ine[$id_seccion_ine]['secundario']['color_background'] = $partido_secundario_color_background;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['color_border'] = $partido_secundario_color_border;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['nombre'] = $partido_secundario_nombre;
				$datos_secciones_ine[$id_seccion_ine]['secundario']['id'] = $partido_secundario_id;


				if($tipo=='true'){
					$diferencia = $partido_ganador_votos - $partido_secundario_votos;
				}else{
					$diferencia = $partido_ganador_votos - $partido_secundario_votos;
				}
				$datos_secciones_ine[$id_seccion_ine]['diferencia_votos'] = $diferencia;
			}
		}
	}
	//echo "<pre>";
	//print_r($datos_secciones_ine);
	//echo "</pre>";
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
			height:95px;
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
		.datos_consulta,.datos_consulta_3{
			width:50%;
			float:left;
			height:75px;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		@media screen and (max-width: 1281px) {
			.info_content{
				text-align: center;
			}
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
				height: 80px;
			}
			.datos_consulta{
				width:100%;
				height: 90px;
			}
			.datos_consulta_3{
				width:100%;
				height: 60px;
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
			zoom=11;
			var latitud='<?=$datos_distritos_federales[$id_distrito_federal]['latitud'] ?>';
			var longitud='<?=$datos_distritos_federales[$id_distrito_federal]['longitud'] ?>';
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
					"featureType": "road.federal",
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
			foreach ($distritos_federales_parametrosDatosMapa as $key => $value) {
				if($id_distrito_federal != $key){
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
					if($datos_distritos_federales[$key]['partido_ganador_background']=="" || $key != $id_distrito_federal ){
						$datos_distritos_federales[$key]['partido_ganador_border'] = "000000";
						$datos_distritos_federales[$key]['partido_ganador_background'] = "000000";
					}
					?>
					municipio<?= $key ?> = new google.maps.Polygon({
						paths: [<?= $paths ?>],
						strokeColor: "#<?= $datos_distritos_federales[$key]['partido_ganador_border'] ?>",
						strokeOpacity: 0.8,
						strokeWeight: 1,
						fillColor: "#<?= $datos_distritos_federales[$key]['partido_ganador_background'] ?>",
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
				if($datos_secciones_ine[$key]['ganador']['color_background']=="" ){
					$datos_secciones_ine[$key]['ganador']['color_border'] = "000000";
					$datos_secciones_ine[$key]['ganador']['color_background'] = "000000";
				}
				?>
				secciones_area<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: "#<?= $datos_secciones_ine[$key]['ganador']['color_border'] ?>",
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: "#<?= $datos_secciones_ine[$key]['ganador']['color_background'] ?>",
					fillOpacity: 0.35,
				});
				secciones_area<?= $key ?>.setMap(map);
				<?php
			}
			?>
			var marcadores = [
			<?php
			foreach ($datos_distritos_federales as $key => $value) {
				if($value['id'] != $id_distrito_federal){
					echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$value['ganador']['logo']."' ],";
				}
			}
			foreach ($datos_secciones_ine as $key => $value) {
				echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$value['ganador']['logo']."' ],";
			}
			?>
			];


			///informacion del marcador
			var infoWindowContent = [
					<?php
					foreach ($datos_distritos_federales as $key => $value){
						if($value['id'] != $id_distrito_federal){
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
											<h4>Distrio Federal: '.$value['distrito_federal'].'</h4>
											<div class="info_titulo">
												<h5>Votación 2018</h5>
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
						$div = '<div class="divMapa">
									<div class="info_content">
										<h4>Sección: '.$value['numero'].'</h4>
										<div class="info_titulo">
											<h5>Votación 2022</h5>
										</div>
										<div class="info_seccion_ganador">
											Lista Nominal: <b>'.number_format($value['lista_nominal'], 0, '.', ',').'</b><br>
											Ganador: <b>'.$value['ganador']['nombre_corto'].'</b><br>
										</div> 
										<div class="info_seccion_ganador_button">
											 
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
											Pregunta: <b>'.$value['ganador']['nombre_corto'].'</b><br>
											Votos Ganador: <b>'.number_format($value['ganador']['votos'], 0, '.', ',').'</b><br>
											Votos % Ganador: <b>'.$value['ganador']['porcentaje'].'%</b><br>
										</p>
									</div>
									<div class="logo_partido">
										<center>
											<img src="images/logos_partidos/'.$logo_partido_sistema.'" style="width: 40px ">
										</center>
									</div>
									<div class="datos_partido">
										<p>
											Pregunta: <b>'.$value['secundario']['nombre_corto'].'</b><br>
											Votos Totales: <b>'.number_format($value['secundario']['votos'], 0, '.', ',').'</b><br>
											Votos %: <b>'.$value['secundario']['porcentaje'].'%</b><br>
											Dif. de Votos: <b>'.number_format($value['diferencia_votos'], 0, '.', ',').'</b><br>
										</p>
									</div>';
								$div .= '</div>';
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
				var icon = {
					//url: 'https://labs.google.com/ridefinder/images/mm_20_red.png', // url
					url : 'images/iconos_partidos/'+ marcadores[i][3],
					// width, height
					scaledSize: new google.maps.Size(28, 28), // scaled size
				};

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
	