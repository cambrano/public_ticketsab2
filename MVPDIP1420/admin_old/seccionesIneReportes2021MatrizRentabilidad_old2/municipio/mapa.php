<?php
	include __DIR__.'/../../functions/security.php'; 
	@session_start();
	$tipo = 0;
	$ano = 2022;

	$no_data['NoData'] = array(
		'id' => '0',
		'clave' => 'No Data',
		'nombre_corto' => 'No Data',
		'principal' => 0,
		'logo' => 'no_data.png',
		'color_border' => '',
		'color_background' => '',
		'votos_individual' => 0,
		'coaliciones' => '',
		'votos_coaliciones' => 0,
		'votos_totales' => 0
	);

	//var_dump($_POST);
	if(!empty($_POST)){
		include __DIR__."/../../functions/municipios_parametros.php"; 
		include __DIR__."/../../functions/municipios.php";
		include __DIR__."/../../functions/secciones_ine_parametros.php";
		include __DIR__."/../../functions/configuracion_matriz_rentabilidad_secciones_ine_2021.php";

		$configuracion_matriz_rentabilidad_secciones_ine_2021Datos = configuracion_matriz_rentabilidad_secciones_ine_2021Datos();
		$votos_semaforo_amarillo = $configuracion_matriz_rentabilidad_secciones_ine_2021Datos['votos_semaforo_amarillo'];
		$id_tipo_categoria_ciudadano = $configuracion_matriz_rentabilidad_secciones_ine_2021Datos['id_tipo_categoria_ciudadano'] ;// funcionario
		$id_partido_2021 = $configuracion_matriz_rentabilidad_secciones_ine_2021Datos['id_partido_2021_ayuntamiento'];// Partidos 2021 PRI
		//$id_partido_2021 = $configuracion_matriz['id_partido_2021'] = '1';// Partidos 2021
		$id_partido_legado = $configuracion_matriz['id_partido_legado'] = '1';// Partidos Legados
		$tipo = $configuracion_matriz['tipo_eleccion'] = '1';// 0 - Ayuntamiento | 1 - Distrito Local | 2 - Distrito Federal
		/// en el formulario segun el tipo sera lo que te va mostrar el select sera un onchange para que cambie funcionara igual que el de localidades y municipio el principal seria tipo_eleccion y segun lo que escojas sera los partidos que te salgan 





		function truncar($numero, $digitos){
			$truncar = 10**$digitos;
			return intval($numero * $truncar) / $truncar;
		}
		$zoom="8";
		$orderby = ' ORDER BY fechaR DESC';
		$limit = 'LIMIT 0,84';
		$id_municipio = $_POST['searchTable'][0]['id_municipio'];
		$id_seccion_ine = $_POST['searchTable'][0]['id_seccion_ine'];
		$partido_ganador_id = $_POST['searchTable'][0]['partido_ganador_id'];
		$tipo_seccion = $_POST['searchTable'][0]['tipo_seccion'];
		$id_municipio = $_POST['searchTable'][0]['id_municipio'];
		$semaforo = $_POST['searchTable'][0]['semaforo'];
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
		#$_POST['searchTable'][0]['id_municipio']=$id_municipio;
		$municipios_parametrosDatosMapa = municipios_parametrosDatosMapa('',$id_estado);
		$sql="
			SELECT
				main.id,
				main.clave,
				main.municipio,
				main.latitud,
				main.longitud
			FROM municipios main
			WHERE 1 
		";
		if($id_municipio !=''){
			#$sql .= " AND main.id = {$id_municipio} ";
		}
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			
			$datos_municipios[$row['id']]=$row;
			//$datos_municipios[$row['id']]['poligonos']=$municipios_parametrosDatosMapa[$row['id']];
			$num=$num+1;
		}
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','',$id_municipio,'','','','');
		if($id_seccion_ine!=''){
			$sqlPartidos = " AND cvp.id_seccion_ine IN ({$id_seccion_ine}) ";
			$sqlPartidosRM2019 = " AND cprm.id_seccion_ine IN ({$id_seccion_ine}) ";
		}

		if($id_municipio!=''){
			$sqlMunicipio = " AND cvp.id_municipio = {$id_municipio} ";
			$sqlMunicipioRM2019 = " AND cprm.id_municipio = {$id_municipio} ";
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
				p.principal,
				cvp.id_municipio,
				cvp.id_seccion_ine
			FROM  casillas_votos_partidos_2021 cvp
			LEFT JOIN partidos_2021 p
			ON p.id = cvp.id_partido_2021
			WHERE cvp.id_municipio='{$id_municipio}' AND cvp.tipo = '{$tipo}' {$sqlPartidos} {$sqlMunicipio}
			GROUP BY cvp.id_seccion_ine,cvp.id_partido_2021
		";
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
				$partidos_coaliciones[$row['id_seccion_ine']][$row['nombre_corto']]=$row;
			}else{
				$partidos_sin_coaliciones[$row['id_seccion_ine']][$row['nombre_corto']]=$row;
			} 
			$num=$num+1;
		}
		$sql="SELECT 
					prm.id AS id_pregunta_2022_revocacion_mandato,
					prm.clave,
					prm.nombre_corto,
					prm.logo,
					SUM(cprm.votos) votos,
					cprm.id_seccion_ine
			FROM casillas_preguntas_2022_revocacion_mandato cprm
			LEFT JOIN preguntas_2022_revocacion_mandato prm
			ON cprm.id_pregunta_2022_revocacion_mandato = prm.id
			WHERE cprm.id_municipio = '{$id_municipio}'  {$sqlPartidosRM2019} {$sqlMunicipioRM2019}
			GROUP BY cprm.id_seccion_ine,id_pregunta_2022_revocacion_mandato
			ORDER BY cprm.id_seccion_ine,SUM(cprm.votos) DESC
			";

		$result = $conexion->query($sql);
		while($row=$result->fetch_assoc()){
			$consulta_2022_rnm[$row['id_seccion_ine']][$row['nombre_corto']]=$row;
		}
		$sql="
			SELECT
				si.id,
				si.clave,
				si.numero,
				si.latitud,
				si.longitud,
				si.tipo,
				si.id_municipio,
				(SELECT m.municipio FROM municipios m WHERE m.id = si.id_municipio) municipio,
				(SELECT COUNT(cv.id) FROM casillas_votos_2021 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') casillas,
				(SELECT SUM(cv.lista_nominal) FROM casillas_votos_2021 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}' ) lista_nominal,
				(SELECT SUM(cv.votos_nulos) FROM casillas_votos_2021 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_nulos,
				(SELECT SUM(cv.votos_can_nreg) FROM casillas_votos_2021 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_can_nreg,
				(SELECT SUM(cv.votos) FROM casillas_votos_partidos_2021 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_validos,

				(SELECT COUNT(sic.id) FROM secciones_ine_ciudadanos sic WHERE sic.id_municipio={$id_municipio} AND sic.id_seccion_ine = si.id ) ciudadanos_registrados,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sicpa.id_seccion_ine_ciudadano = sic.id WHERE sic.id_municipio={$id_municipio} AND sic.id_seccion_ine = si.id) apoyos_programas,
				(SELECT COUNT(*) FROM secciones_ine_actividades sia WHERE sia.id_seccion_ine = si.id ) acciones_obras,
				(SELECT COUNT(*) FROM secciones_ine_grupos sig WHERE sig.id_seccion_ine = si.id ) grupos_interes,
				(SELECT COUNT(*) FROM militantes_partidos mp LEFT JOIN secciones_ine_ciudadanos sic ON mp.id_seccion_ine_ciudadano = sic.id WHERE sic.id_municipio={$id_municipio} AND mp.id_partido_legado = '{$id_partido_legado}' AND sic.id_seccion_ine = si.id) militantes,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_municipio={$id_municipio} AND sicc.id_tipo_categoria_ciudadano = '{$id_tipo_categoria_ciudadano}' AND sicc.id_seccion_ine = si.id) funcionarios,


				(SELECT SUM(cvrm2019.lista_nominal) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id ) consulta_rvm_lista_nominal,
				(SELECT COUNT(*) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_casillas_rvm,
				(SELECT SUM(cvrm2019.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_votos_nulos,
				(SELECT SUM(cvrm2019.votos_can_nreg) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_votos_can_nreg,
				(SELECT SUM(cpvrm2019.votos) FROM casillas_preguntas_2022_revocacion_mandato cpvrm2019 WHERE cpvrm2019.id_seccion_ine = si.id) consulta_rvm_votos_validos


			FROM secciones_ine si
			WHERE si.id_municipio = '$id_municipio'
		";
		if($id_seccion_ine!=''){
			$sql.=" AND si.id IN ({$id_seccion_ine}) ";
		}

		if($tipo_seccion!=''){
			$sql.=" AND si.tipo = '{$tipo_seccion}' ";
		}

		if($id_municipio!=''){
			$sql.=" AND si.id_municipio = '{$id_municipio}' ";
		}
		$result = $conexion->query($sql); 
		while($row=$result->fetch_assoc()){
			$row['consulta_rvm_votos_totales'] = $row['consulta_rvm_votos_nulos'] + $row['consulta_rvm_votos_validos'];
			$row['votos_totales'] = $row['votos_nulos'] + $row['votos_can_nreg'] + $row['votos_validos'];
			$row['participacion_ciudadana'] = truncar((($row['votos_totales'] / $row['lista_nominal'])*100), 2);
			$datos_secciones_ine[$row['id']]=$row;
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
					$pos = strpos($nombre_corto, $array['nombre_corto']);
					if ($pos !== false ) {
						$coaliciones_array = explode("_", $nombre_corto);
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
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['id'] = $array['id'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['clave'] = $clave;
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['nombre_corto'] = $array['nombre_corto'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['nombre'] = $array['nombre'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['principal'] = $array['principal'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['logo'] = $array['logo'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['color_border'] = $array['color_border'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['color_background'] = $array['color_background'];

				$datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_individual'] = $array['votos'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['coaliciones_sin_orden'] = $coaliciones;
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_coaliciones'] = $sum_coaliciones;

				//! Importante
				//? Ordenamos las coaliciones por votos en individual
				$total_votos_individual = 0;
				krsort($coalicion_orden_individual);
				foreach ($coalicion_orden_individual as $votos => $partidos_array) {
					foreach ($partidos_array as $index => $partido) {
						$datos_secciones_ine[$row['id']]['partidos'][$clave]['coaliciones_orden_votos_individual'][$partido]=$votos;
						if($clave != $partido){
							$total_votos_individual = $total_votos_individual + $votos;
						}
					}
				}
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_coaliciones_individual'] = $total_votos_individual;
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_totales'] = $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_individual'] + $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_coaliciones'] + $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_coaliciones_individual'] ;


				$ordena_votos_individual[$row['id']][$array['votos']] [] = $clave ;
				$ordena_votos_totales[$row['id']][ $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_totales'] ] [] = $clave ;

				#$partidos_orden_individual[ $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_individual'] ][$array['votos']][] = $array['nombre_corto']; 
			}
			//! Importante
			//? Ordenamos los partidos
			krsort($ordena_votos_individual[$row['id']]);
			krsort($ordena_votos_totales[$row['id']]);
			$validador = 0;
			foreach ($ordena_votos_individual[$row['id']] as $votos => $partidos_array) {
				foreach ($partidos_array as $index => $partido) {
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido]=$votos;
					$validador = $validador + $votos;
					if(empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'])){
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'] = $partido;
						if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal']==1 ){
							$sistema = true;
						}
					}elseif (empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'])  ) {
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'] = $partido;
						if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal']==1 ){
							$sistema = true;
						}
					}else{
						if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal'] == 1 && $sistema == false){
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['sistema'] = $partido;
						}
					}
				}
			}
			if($validador <= 0){
				$datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'] = $datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'] ='NoData';
				$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'gris';
				$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['diferencia'] = '0';
			}else{
				if(!empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza']) && !empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza']) ){
					//? sacamos el id del ganador y vemos si pertenece al grupo
					foreach ($datos_secciones_ine[$row['id']]['partidos'] as $nombrePartido => $data) {
						if( $data['id'] == $id_partido_2021){
							$searh_partido = $nombrePartido;
						}
					}
					$partido_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'];
					$votos_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido_primera_fuerza];

					$partido_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'];
					$votos_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido_segunda_fuerza];
					//? buscamos los partidos de coalicon de la primera fuerza
					$partido_primera_fuerza_coaliciones = $datos_secciones_ine[$row['id']]['partidos'][$partido_primera_fuerza]['coaliciones_orden_votos_individual'];
					$diferencia = $votos_primera_fuerza - $votos_segunda_fuerza;

					$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['partidos'] = $partido_primera_fuerza.':'.$votos_primera_fuerza.'-'.$partido_segunda_fuerza.':'.$votos_segunda_fuerza;
					if($partido_primera_fuerza ==$searh_partido){
						//? preguntamos si son iguales para comparar si es amarillo o verde
						//! Rango Rojo 0 a 50
						//! Amarilo 50 a 100
						//! Verde 100 a superior
						if($diferencia >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'verde';
						}elseif ( $diferencia <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						}elseif ($diferencia >=50 || $diferencia <100  ) {
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'amarillo';
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						}
					}elseif (!empty($partido_primera_fuerza_coaliciones[$searh_partido])) {
						//? preguntamos si son iguales para comparar si es amarillo o verde
						if($partido_primera_fuerza ==$searh_partido){
							//? preguntamos si son iguales para comparar si es amarillo o verde
							//! Rango Rojo 0 a 50
							//! Amarilo 50 a 100
							//! Verde 100 a superior
							if($diferencia >=100){
								$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'verde';
							}elseif ( $diferencia <50 ) {
								$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
							}elseif ($diferencia >=50 || $diferencia <100  ) {
								$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'amarillo';
							}else{
								$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
							}
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						}
					}else{
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
					}
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['diferencia'] = $diferencia;
				}
			}
			$validador = 0;
			foreach ($ordena_votos_individual[$row['id']] as $votos => $partidos_array) {
				foreach ($partidos_array as $index => $partido) {
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido]=$votos;
					$validador = $validador + $votos;
					if(empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'])){
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'] = $partido;
						$primera_fuerza = $partido;
						if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal']==1 ){
							$sistema = true;
						}
					}elseif (empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza']) && empty($datos_secciones_ine[$row['id']]['partidos'][$partido]['coaliciones_sin_orden'][$primera_fuerza]  )  ) {
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza'] = $partido;
						if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal']==1 ){
							$sistema = true;
						}
					}else{
						if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal'] == 1 && $sistema == false){
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['sistema'] = $partido;
						}
					}
				}
			}
			if($validador <= 0){
				$datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza'] = $datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'] ='NoData';
				$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'gris';
				$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['diferencia'] = '0';
			}else{
				if(!empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza']) && !empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza']) ){
					//? sacamos el id del ganador y vemos si pertenece al grupo y vemos su id de partido previa configuracion en config matriz
					foreach ($datos_secciones_ine[$row['id']]['partidos'] as $nombrePartido => $data) {
						if( $data['id'] == $id_partido_2021){
							$searh_partido = $nombrePartido;
						}
					}
					$partido_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'];
					$votos_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido_primera_fuerza];

					$partido_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza'];
					$votos_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido_segunda_fuerza];
					//? buscamos los partidos de coalicon de la primera fuerza
					$partido_primera_fuerza_coaliciones = $datos_secciones_ine[$row['id']]['partidos'][$partido_primera_fuerza]['coaliciones_orden_votos_individual'];
					$diferencia = $votos_primera_fuerza - $votos_segunda_fuerza;

					$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['partidos'] = $partido_primera_fuerza.':'.$votos_primera_fuerza.'-'.$partido_segunda_fuerza.':'.$votos_segunda_fuerza;
					if($partido_primera_fuerza ==$searh_partido){
						//? preguntamos si son iguales para comparar si es amarillo o verde
						//! Rango Rojo 0 a 50
						//! Amarilo 50 a 100
						//! Verde 100 a superior
						if($diferencia >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'verde';
						}elseif ( $diferencia <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						}elseif ($diferencia >=50 || $diferencia <100  ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'amarillo';
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						}
					}elseif (!empty($partido_primera_fuerza_coaliciones[$searh_partido])) {
						//? preguntamos si son iguales para comparar si es amarillo o verde
						if($diferencia >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'verde';
						}elseif ( $diferencia <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo1';
						}elseif ($diferencia >=50 || $diferencia <100  ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'amarillo';
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						}
					}else{
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
					}
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['diferencia'] = $diferencia;
				}
			}


			//! Revocación de mandato 2019
			#ver ererroes
			if(!empty($consulta_2022_rnm[$row['id']])){
				$datos_secciones_ine[$row['id']]['revocacion_mandato']['preguntas']=$consulta_2022_rnm[$row['id']];
				unset($orden_preguntas);
				foreach ($consulta_2022_rnm[$row['id']] as $key => $value) {
					$orden_preguntas[$value['votos']][]=$key;
				}
				$primera_pregunta ='';
				$segunda_pregunta ='';
				krsort($orden_preguntas);
				foreach ($orden_preguntas as $votos => $orden) {
					foreach ($orden as $index => $pregunta) {
						$datos_secciones_ine[$row['id']]['revocacion_mandato']['orden_preguntas'][$pregunta]=$votos;
						if($primera_pregunta==''){
							$primera_pregunta = $pregunta;
							$datos_secciones_ine[$row['id']]['revocacion_mandato']['principales']['prinicipal']=$primera_pregunta;
						}elseif ($primera_fuerza!='' && $segunda_pregunta=='') {
							$segunda_pregunta = $pregunta;
							$datos_secciones_ine[$row['id']]['revocacion_mandato']['principales']['secundaria']=$segunda_pregunta;
						}
					}
				}
			}


			if($partido_ganador_id !=''){
				$partidos_search = explode(',', $partido_ganador_id);
				$primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'];
				$segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'];
				if($primera_fuerza=='NoData'){
					$datos_segunda_fuerza = $datos_primera_fuerza =$no_data['NoData'];
				}else{
					$datos_primera_fuerza = $datos_secciones_ine[$row['id']]['partidos'][$primera_fuerza];
					$datos_segunda_fuerza = $datos_secciones_ine[$row['id']]['partidos'][$segunda_fuerza];
				}
				if( in_array($datos_primera_fuerza['id'], $partidos_search) == false ){
					unset($datos_secciones_ine[$row['id']]);
				}
			}

			if($semaforo != ''){
				if( $datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] != $semaforo){
					unset($datos_secciones_ine[$row['id']]);
				}
			}
		}
		#echo "<textarea>";
		#echo json_encode($datos_secciones_ine);
		#echo "</textarea>";
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

		$_POST['searchTable'][0]['id_municipio']=$id_municipio;
		$municipios_parametrosDatosMapa = municipios_parametrosDatosMapa('',$id_estado);
		$sql="
			SELECT
				main.id,
				main.clave,
				main.municipio,
				main.latitud,
				main.longitud
			FROM municipios main
			WHERE 1 
		";
		if($id_municipio !=''){
			#$sql .= " AND main.id = {$id_municipio} ";
		}
		$result = $conexion->query($sql); 
		$num=0; 
		while($row=$result->fetch_assoc()){
			
			$datos_municipios[$row['id']]=$row;
			//$datos_municipios[$row['id']]['poligonos']=$municipios_parametrosDatosMapa[$row['id']];
			$num=$num+1;
		}
		$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','',$id_municipio,'','','','');
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
				cvp.id_municipio,
				cvp.id_seccion_ine
			FROM  casillas_votos_partidos_2021 cvp
			LEFT JOIN partidos_2021 p
			ON p.id = cvp.id_partido_2021
			WHERE cvp.id_municipio='{$id_municipio}' AND cvp.tipo = '{$tipo}' 
			GROUP BY cvp.id_seccion_ine,cvp.id_partido_2021
		";
		
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
				$partidos_coaliciones[$row['id_seccion_ine']][$row['nombre_corto']]=$row;
			}else{
				$partidos_sin_coaliciones[$row['id_seccion_ine']][$row['nombre_corto']]=$row;
			} 
			$num=$num+1;
		}
		$sql="SELECT 
					prm.id AS id_pregunta_2022_revocacion_mandato,
					prm.clave,
					prm.nombre_corto,
					prm.logo,
					SUM(cprm.votos) votos,
					cprm.id_seccion_ine
			FROM casillas_preguntas_2022_revocacion_mandato cprm
			LEFT JOIN preguntas_2022_revocacion_mandato prm
			ON cprm.id_pregunta_2022_revocacion_mandato = prm.id
			WHERE cprm.id_municipio = '{$id_municipio}'
			GROUP BY cprm.id_seccion_ine,id_pregunta_2022_revocacion_mandato
			ORDER BY cprm.id_seccion_ine,SUM(cprm.votos) DESC
			";
		$result = $conexion->query($sql);
		while($row=$result->fetch_assoc()){
			$consulta_2022_rnm[$row['id_seccion_ine']][$row['nombre_corto']]=$row;
		}
		$sql="
			SELECT
				si.id,
				si.clave,
				si.numero,
				si.latitud,
				si.longitud,
				si.tipo,
				si.id_municipio,
				(SELECT m.municipio FROM municipios m WHERE m.id = si.id_municipio) municipio,
				(SELECT COUNT(cv.id) FROM casillas_votos_2021 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') casillas,
				(SELECT SUM(cv.lista_nominal) FROM casillas_votos_2021 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}' ) lista_nominal,
				(SELECT SUM(cv.votos_nulos) FROM casillas_votos_2021 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_nulos,
				(SELECT SUM(cv.votos_can_nreg) FROM casillas_votos_2021 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_can_nreg,
				(SELECT SUM(cv.votos) FROM casillas_votos_partidos_2021 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_validos,

				(SELECT COUNT(sic.id) FROM secciones_ine_ciudadanos sic WHERE sic.id_municipio={$id_municipio} AND sic.id_seccion_ine = si.id ) ciudadanos_registrados,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sicpa.id_seccion_ine_ciudadano = sic.id WHERE sic.id_municipio={$id_municipio} AND sic.id_seccion_ine = si.id) apoyos_programas,
				(SELECT COUNT(*) FROM secciones_ine_actividades sia WHERE sia.id_seccion_ine = si.id ) acciones_obras,
				(SELECT COUNT(*) FROM secciones_ine_grupos sig WHERE sig.id_seccion_ine = si.id ) grupos_interes,
				(SELECT COUNT(*) FROM militantes_partidos mp LEFT JOIN secciones_ine_ciudadanos sic ON mp.id_seccion_ine_ciudadano = sic.id WHERE sic.id_municipio={$id_municipio} AND mp.id_partido_legado = '{$id_partido_legado}' AND sic.id_seccion_ine = si.id) militantes,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_municipio={$id_municipio} AND sicc.id_tipo_categoria_ciudadano = '{$id_tipo_categoria_ciudadano}' AND sicc.id_seccion_ine = si.id) funcionarios,


				(SELECT SUM(cvrm2019.lista_nominal) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id ) consulta_rvm_lista_nominal,
				(SELECT COUNT(*) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_casillas_rvm,
				(SELECT SUM(cvrm2019.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_votos_nulos,
				(SELECT SUM(cvrm2019.votos_can_nreg) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_votos_can_nreg,
				(SELECT SUM(cpvrm2019.votos) FROM casillas_preguntas_2022_revocacion_mandato cpvrm2019 WHERE cpvrm2019.id_seccion_ine = si.id) consulta_rvm_votos_validos


			FROM secciones_ine si
			WHERE si.id_municipio = '$id_municipio'
		";
		
		$result = $conexion->query($sql); 
		while($row=$result->fetch_assoc()){
			$row['consulta_rvm_votos_totales'] = $row['consulta_rvm_votos_nulos'] + $row['consulta_rvm_votos_validos'];
			$row['votos_totales'] = $row['votos_nulos'] + $row['votos_can_nreg'] + $row['votos_validos'];
			$row['participacion_ciudadana'] = truncar((($row['votos_totales'] / $row['lista_nominal'])*100), 2);
			$datos_secciones_ine[$row['id']]=$row;
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
					$pos = strpos($nombre_corto, $array['nombre_corto']);
					if ($pos !== false ) {
						$coaliciones_array = explode("_", $nombre_corto);
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
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['id'] = $array['id'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['clave'] = $clave;
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['nombre_corto'] = $array['nombre_corto'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['nombre'] = $array['nombre'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['principal'] = $array['principal'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['logo'] = $array['logo'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['color_border'] = $array['color_border'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['color_background'] = $array['color_background'];

				$datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_individual'] = $array['votos'];
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['coaliciones_sin_orden'] = $coaliciones;
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_coaliciones'] = $sum_coaliciones;

				//! Importante
				//? Ordenamos las coaliciones por votos en individual
				$total_votos_individual = 0;
				krsort($coalicion_orden_individual);
				foreach ($coalicion_orden_individual as $votos => $partidos_array) {
					foreach ($partidos_array as $index => $partido) {
						$datos_secciones_ine[$row['id']]['partidos'][$clave]['coaliciones_orden_votos_individual'][$partido]=$votos;
						if($clave != $partido){
							$total_votos_individual = $total_votos_individual + $votos;
						}
					}
				}
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_coaliciones_individual'] = $total_votos_individual;
				$datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_totales'] = $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_individual'] + $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_coaliciones'] + $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_coaliciones_individual'] ;


				$ordena_votos_individual[$row['id']][$array['votos']] [] = $clave ;
				$ordena_votos_totales[$row['id']][ $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_totales'] ] [] = $clave ;

				#$partidos_orden_individual[ $datos_secciones_ine[$row['id']]['partidos'][$clave]['votos_individual'] ][$array['votos']][] = $array['nombre_corto']; 
			}
			//! Importante
			//? Ordenamos los partidos
			krsort($ordena_votos_individual[$row['id']]);
			krsort($ordena_votos_totales[$row['id']]);
			$validador = 0;
			foreach ($ordena_votos_individual[$row['id']] as $votos => $partidos_array) {
				foreach ($partidos_array as $index => $partido) {
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido]=$votos;
					$validador = $validador + $votos;
					if(empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'])){
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'] = $partido;
						if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal']==1 ){
							$sistema = true;
						}
					}elseif (empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'])  ) {
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'] = $partido;
						if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal']==1 ){
							$sistema = true;
						}
					}else{
						if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal'] == 1 && $sistema == false){
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['sistema'] = $partido;
						}
					}
				}
			}
			if($validador <= 0){
				$datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'] = $datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'] ='NoData';
				$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'gris';
				$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['diferencia'] = '0';
			}else{
				if(!empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza']) && !empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza']) ){
					//? sacamos el id del ganador y vemos si pertenece al grupo
					foreach ($datos_secciones_ine[$row['id']]['partidos'] as $nombrePartido => $data) {
						if( $data['id'] == $id_partido_2021){
							$searh_partido = $nombrePartido;
						}
					}
					$partido_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'];
					$votos_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido_primera_fuerza];

					$partido_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'];
					$votos_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido_segunda_fuerza];
					//? buscamos los partidos de coalicon de la primera fuerza
					$partido_primera_fuerza_coaliciones = $datos_secciones_ine[$row['id']]['partidos'][$partido_primera_fuerza]['coaliciones_orden_votos_individual'];
					$diferencia = $votos_primera_fuerza - $votos_segunda_fuerza;

					$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['partidos'] = $partido_primera_fuerza.':'.$votos_primera_fuerza.'-'.$partido_segunda_fuerza.':'.$votos_segunda_fuerza;
					if($partido_primera_fuerza ==$searh_partido){
						//? preguntamos si son iguales para comparar si es amarillo o verde
						//! Rango Rojo 0 a 50
						//! Amarilo 50 a 100
						//! Verde 100 a superior
						if($diferencia >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'verde';
						}elseif ( $diferencia <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						}elseif ($diferencia >=50 || $diferencia <100  ) {
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'amarillo';
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						}
					}elseif (!empty($partido_primera_fuerza_coaliciones[$searh_partido])) {
						//? preguntamos si son iguales para comparar si es amarillo o verde
						if($partido_primera_fuerza ==$searh_partido){
							//? preguntamos si son iguales para comparar si es amarillo o verde
							//! Rango Rojo 0 a 50
							//! Amarilo 50 a 100
							//! Verde 100 a superior
							if($diferencia >=100){
								$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'verde';
							}elseif ( $diferencia <50 ) {
								$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
							}elseif ($diferencia >=50 || $diferencia <100  ) {
								$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'amarillo';
							}else{
								$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
							}
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						}
					}else{
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
					}
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['diferencia'] = $diferencia;
				}
			}
			$validador = 0;
			foreach ($ordena_votos_individual[$row['id']] as $votos => $partidos_array) {
				foreach ($partidos_array as $index => $partido) {
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido]=$votos;
					$validador = $validador + $votos;
					if(empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'])){
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'] = $partido;
						$primera_fuerza = $partido;
						if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal']==1 ){
							$sistema = true;
						}
					}elseif (empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza']) && empty($datos_secciones_ine[$row['id']]['partidos'][$partido]['coaliciones_sin_orden'][$primera_fuerza]  )  ) {
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza'] = $partido;
						if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal']==1 ){
							$sistema = true;
						}
					}else{
						if($datos_secciones_ine[$row['id']]['partidos'][$partido]['principal'] == 1 && $sistema == false){
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['sistema'] = $partido;
						}
					}
				}
			}
			if($validador <= 0){
				$datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza'] = $datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'] ='NoData';
				$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'gris';
				$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['diferencia'] = '0';
			}else{
				if(!empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza']) && !empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza']) ){
					//? sacamos el id del ganador y vemos si pertenece al grupo y vemos su id de partido previa configuracion en config matriz
					foreach ($datos_secciones_ine[$row['id']]['partidos'] as $nombrePartido => $data) {
						if( $data['id'] == $id_partido_2021){
							$searh_partido = $nombrePartido;
						}
					}
					$partido_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'];
					$votos_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido_primera_fuerza];

					$partido_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza'];
					$votos_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido_segunda_fuerza];
					//? buscamos los partidos de coalicon de la primera fuerza
					$partido_primera_fuerza_coaliciones = $datos_secciones_ine[$row['id']]['partidos'][$partido_primera_fuerza]['coaliciones_orden_votos_individual'];
					$diferencia = $votos_primera_fuerza - $votos_segunda_fuerza;

					$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['partidos'] = $partido_primera_fuerza.':'.$votos_primera_fuerza.'-'.$partido_segunda_fuerza.':'.$votos_segunda_fuerza;
					if($partido_primera_fuerza ==$searh_partido){
						//? preguntamos si son iguales para comparar si es amarillo o verde
						//! Rango Rojo 0 a 50
						//! Amarilo 50 a 100
						//! Verde 100 a superior
						if($diferencia >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'verde';
						}elseif ( $diferencia <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						}elseif ($diferencia >=50 || $diferencia <100  ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'amarillo';
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						}
					}elseif (!empty($partido_primera_fuerza_coaliciones[$searh_partido])) {
						//? preguntamos si son iguales para comparar si es amarillo o verde
						if($diferencia >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'verde';
						}elseif ( $diferencia <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo1';
						}elseif ($diferencia >=50 || $diferencia <100  ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'amarillo';
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						}
					}else{
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
					}
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['diferencia'] = $diferencia;
				}
			}


			//! Revocación de mandato 2019
			#ver ererroes
			if(!empty($consulta_2022_rnm[$row['id']])){
				$datos_secciones_ine[$row['id']]['revocacion_mandato']['preguntas']=$consulta_2022_rnm[$row['id']];
				unset($orden_preguntas);
				foreach ($consulta_2022_rnm[$row['id']] as $key => $value) {
					$orden_preguntas[$value['votos']][]=$key;
				}
				$primera_pregunta ='';
				$segunda_pregunta ='';
				krsort($orden_preguntas);
				foreach ($orden_preguntas as $votos => $orden) {
					foreach ($orden as $index => $pregunta) {
						$datos_secciones_ine[$row['id']]['revocacion_mandato']['orden_preguntas'][$pregunta]=$votos;
						if($primera_pregunta==''){
							$primera_pregunta = $pregunta;
							$datos_secciones_ine[$row['id']]['revocacion_mandato']['principales']['prinicipal']=$primera_pregunta;
						}elseif ($primera_fuerza!='' && $segunda_pregunta=='') {
							$segunda_pregunta = $pregunta;
							$datos_secciones_ine[$row['id']]['revocacion_mandato']['principales']['secundaria']=$segunda_pregunta;
						}
					}
				}
			}
		}
	}


	#print_r($datos_secciones_ine);

	#echo "<textarea>";
	#echo json_encode($datos_secciones_ine);
	#echo "</textarea>";
	#echo "<textarea>";
	#echo json_encode($consulta_2022_rnm);
	#echo "</textarea>";


?>
	<style type="text/css">
		.divMapa{
			width:450px;
			height:200px;
			margin: -10px 0px 0px 10px;
		}
		.divMapaTerritorio{
			width:350px;
			height:100px;
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
			height:139px;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		.logo_partido{
			width:30%;
			float:left;
			height:95px;
			text-align:left;
			border: 1px solid #00923f;
			padding: 10px 0px 2px 5px;
			background-color:#e36962;
			color:white;
		}
		.datos_partido{
			width:70%;
			float:left;
			height:95px;
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
			.divMapaTerritorio{
				width:167px;
				height:200;
				margin: -10px 0px 0px 10px;
			}
			.divMapa{
				width:167px;
				height:460px;
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
				height: auto;
			}
			.datos{
				width:100%;
				height: auto;
			}
			.datos_consulta{
				width:100%;
				height: auto;
			}
			.datos_consulta_3{
				width:100%;
				height: auto;
			}
			.logo_partido{
				width:100%;
				height: auto;
			}
			.datos_partido{
				width:100%;
				height: auto;
			}
			.gm-style-iw  { 
				min-width: 110px !important; 
				padding: 22px 12px 2px 0px !important;
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
			var latitud='<?=$datos_municipios[$id_municipio]['latitud'] ?>';
			var longitud='<?=$datos_municipios[$id_municipio]['longitud'] ?>';
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
			foreach ($municipios_parametrosDatosMapa as $key => $value) {
				$municipiosDatosMapa[$key]['numero'];
				$municipiosDatosMapa[$key]['latitud'];
				$municipiosDatosMapa[$key]['longitud'];
				$paths = "";
				foreach ($value as $keyT => $valueT) {
					$path = "municipios_".$key."_".$keyT;
					echo $path." = [";
					foreach ($valueT as $keyH => $valueH) {
						echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
					}
					echo "];";

					$paths .= $path.",";
				}
				if($datos_municipios[$key]['partido_ganador_background']=="" || $key != $id_municipio ){
					$datos_municipios[$key]['partido_ganador_border'] = "000000";
					$datos_municipios[$key]['partido_ganador_background'] = "000000";
				}

				$datos_municipios[$key]['partido_ganador_border'] = "000000";
				$datos_municipios[$key]['partido_ganador_background'] = "000000";

				?>
				distritos_area<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: "#<?= $datos_municipios[$key]['partido_ganador_border'] ?>",
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: "#<?= $datos_municipios[$key]['partido_ganador_background'] ?>",
					fillOpacity: 0.35,
				});
				distritos_area<?= $key ?>.setMap(map);
				<?php
			}
			?>


			<?php
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

				$semaforo_color = $datos_secciones_ine[$key]['orden_votos_individual']['semaforo']['color'];
				
				if($semaforo_color == 'verde'){
					$color_border = $color_background = "00ff00";
				}elseif ($semaforo_color == 'amarillo') {
					$color_border = $color_background = "ffff00";
				}elseif ($semaforo_color == 'rojo') {
					$color_border = $color_background = "FF0000";
				}elseif ($semaforo_color == 'gris') {
					$color_border = $color_background = "8d8d8d";
				}else{
					$color_border = $color_background = "000000";
				}

				


				?>
				secciones_area<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: "#<?= $color_border ?>",
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: "#<?= $color_background ?>",
					fillOpacity: 0.35,
				});
				secciones_area<?= $key ?>.setMap(map);
				<?php
			}
			?>



			///marcadores o puntos
			var marcadores = [
			<?php
			foreach ($datos_municipios as $key => $value) {
				if($value['id'] != $id_municipio){
					echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'numero','".$value['numero'].".png' ],";
				}
			}
			foreach ($datos_secciones_ine as $key => $value) {
				$primera_fuerza = $value['orden_votos_individual']['primera_fuerza'];
				if($primera_fuerza =='NoData'){
					$logo = $no_data[$primera_fuerza]['logo'];
				}else{
					$logo = $value['partidos'][$primera_fuerza]['logo'] ;
				}

				echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'".$logo."' ],";
			}
			?>
			];
			///informacion del marcador
			var infoWindowContent = [
				<?php
				foreach ($datos_municipios as $key => $value){
					if($value['id'] != $id_municipio){
						$votos_totales = 0;
						$votos_totales = $value['votos_validos'] + $value['votos_nulos'] +$value['votos_can_nreg'];
						$porcentaje_partido_ganador = ($value['partido_ganador_votos'] / $votos_totales)*100;
						$porcentaje_partido_ganador = truncar($porcentaje_partido_ganador, 2);
						$porcentaje_partido_sistema = ($value['partido_sistema_votos'] / $votos_totales )*100;
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
						$div = '<div class="divMapaTerritorio">
									<div class="info_content">
										<h4>Municipio: '.$value['numero'].'</h4>
										<div class="info_titulo">
											<h5>Votación '.$ano.'</h5>
										</div>
										<div class="info_seccion_ganador">
										</div>
										<div class="info_seccion_ganador_button">
											<button class="button button4" onclick="verMasMunicipio('.$value['id'].')">Ver Más</button>
										</div>
									</div>
								</div>';
						$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
						?>
						['<?= $div ?>'],
						<?php
					}
				}
				foreach ($datos_secciones_ine as $key => $value){
					unset($coali_primera_fuerza);
					unset($coali_segunda_fuerza);
					unset($texto);

					$primera_fuerza = $value['orden_votos_individual']['primera_fuerza'];
					$segunda_fuerza = $value['orden_votos_individual']['segunda_fuerza'];
					if($primera_fuerza=='NoData'){
						$datos_segunda_fuerza = $datos_primera_fuerza =$no_data['NoData'];
					}else{
						$datos_primera_fuerza = $value['partidos'][$primera_fuerza];
						$datos_segunda_fuerza = $value['partidos'][$segunda_fuerza];
						foreach ($datos_primera_fuerza['coaliciones_orden_votos_individual'] as $partido => $votos) {
							if($primera_fuerza != $partido){
								$texto[] = $partido.': '.$votos;
							}
						}
						$coali_primera_fuerza = implode(", ", $texto);
						unset($texto);
						foreach ($datos_segunda_fuerza['coaliciones_orden_votos_individual'] as $partido => $votos) {
							if($segunda_fuerza != $partido){
								$texto[] = $partido.': '.$votos;
							}
						}
						$coali_segunda_fuerza = implode(", ", $texto);
						unset($texto);
					}


					if($value['orden_votos_individual']['semaforo']['color']=='rojo'){
						$color = 'rgba(255, 105, 97, 0.9)';
					}elseif ($value['orden_votos_individual']['semaforo']['color']=='amarillo') {
						$color = 'rgba(253, 253, 150, 0.9)';;
					}elseif ($value['orden_votos_individual']['semaforo']['color']=='gris') {
						$color = 'rgba(141, 141, 141, 0.9)';;
					}elseif ($value['orden_votos_individual']['semaforo']['color']=='verde') {
						$color = 'rgba(119, 221, 119, 0.9)';;
					}else{
						$color = 'rgba(0, 0, 0, 0.9)';
					}

					$div = '<div class="divMapa">
								<div class="info_content">
									<h4>Sección: '.$value['numero'].'</h4>
									<div class="info_titulo">
										<h5>Votación '.$ano.'</h5>
									</div>
									<div class="info_seccion_ganador">
										Lista Nominal: <b>'.number_format($value['lista_nominal'], 0, '.', ',').'</b><br>
										Partido Ganador: <b>'.$datos_primera_fuerza['nombre_corto'].'</b><br>
									</div>
									<div class="info_seccion_ganador_button">
										<div style="background-color:'.$color.';padding:5px;margin-top:2px;text-align:center;color:black">
											<b style="color:white">'.strtoupper($value['orden_votos_individual']['semaforo']['diferencia']).'</b>
										</div>
									</div>
								</div>
								<div class="datos_votos">
									<p>
										Casillas: <b>'.number_format($value['casillas'], 0, '.', ',').'</b><br><br>
										Votos Validos: <b>'.number_format($value['votos_validos'], 0, '.', ',').'</b><br>
										Votos Nulos: <b>'.number_format($value['votos_nulos'], 0, '.', ',').'</b><br>
										Votos CAN NREG: <b>'.number_format($value['votos_can_nreg'], 0, '.', ',').'</b><br>
										Votos Totales: <b>'.number_format($value['votos_totales'], 0, '.', ',').'</b><br>
										P. Ciudadana: <b>'.number_format($value['participacion_ciudadana'], 2, '.', ',').'%</b><br>
									</p>
								</div>
								<div class="datos_votos">
									<div style="width:100%;text-align:center;padding:0px">
										<img src="images/logos_partidos/'.$datos_primera_fuerza['logo'].'" style="width: 30px ">
									</div>
									<p style="padding:0px;text-align:left;">
										Votos Individual: <b>'.number_format($datos_primera_fuerza['votos_individual'], 0, '.', ',').'</b><br>
										Votos Coalición Ind: <b>'.number_format($datos_primera_fuerza['votos_coaliciones_individual'], 0, '.', ',').'</b><br>
										Votos Coalición Boletas: <b>'.number_format($datos_primera_fuerza['votos_coaliciones'], 0, '.', ',').'</b><br>
										Votos Total: <b>'.number_format($datos_primera_fuerza['votos_totales'], 0, '.', ',').'</b><br>
										Coaliciones: <b>'.$coali_primera_fuerza.'</b><br>
									</p>
								</div>
								<div class="logo_partido">
									<center>
										<img src="images/logos_partidos/'.$datos_segunda_fuerza['logo'].'" style="width: 40px ">
									</center>
								</div>
								<div class="datos_partido">
									<p style="padding:0px;text-align:left;">
										Votos Individual: <b>'.number_format($datos_segunda_fuerza['votos_individual'], 0, '.', ',').'</b><br>
										Votos Coalición Ind: <b>'.number_format($datos_segunda_fuerza['votos_coaliciones_individual'], 0, '.', ',').'</b><br>
										Votos Coalición Boletas: <b>'.number_format($datos_segunda_fuerza['votos_coaliciones'], 0, '.', ',').'</b><br>
										Votos Total: <b>'.number_format($datos_segunda_fuerza['votos_totales'], 0, '.', ',').'</b><br>
										Coaliciones: <b>'.$coali_segunda_fuerza.'</b><br>
									</p>
								</div>
								<div class="datos"> 
									Prog. Gob:<b>'.number_format($value['ciudadanos_registrados'], 0, '.', ',').'</b><br>
									Prog. Inv:<b>'.number_format($value['acciones_obras'], 0, '.', ',').'</b><br>
									Ciudadanos:<b>'.number_format($value['ciudadanos_registrados'], 0, '.', ',').'</b><br>
								</div>
								<div class="datos">
									Grupo Interes:<b>'.number_format($value['grupos_interes'], 0, '.', ',').'</b><br>
									Militantes:<b>'.number_format($value['militantes'], 0, '.', ',').'</b><br>
									Funcionarios:<b>'.number_format($value['funcionarios'], 0, '.', ',').'</b><br>
								</div>';
								//if(revocacion_mandato_2022)
								if(!empty($value['revocacion_mandato_2022'])){
									$participacion_ciudadana = ($value['consulta_rvm_2022_votos_validos'] / $value['consulta_rvm_2022_lista_nominal']) * 100;
									$porcenjate_siga = $value['revocacion_mandato_2022']['orden_preguntas']['SIGA'] / $value['consulta_rvm_2022_votos_validos'] * 100;
									$porcenjate_no_siga = $value['revocacion_mandato_2022']['orden_preguntas']['NO_SIGA'] / $value['consulta_rvm_2022_votos_validos'] * 100;
									$div .= '
										<div class="datos_consulta" style=" background: rgba(190, 195, 201, 0.9) "> 
											Consulta 2022 Revocación Mandato
											<br>
											Lista Nominal: <b>'.number_format($value['consulta_rvm_2022_lista_nominal'], 0, '.', ',').'</b>
											<br>
											Casillas: <b>'.number_format($value['consulta_rvm_2022_casillas_rvm_2019'], 0, '.', ',').'</b>
											<br>
											P. Ciudadana: <b> '.number_format($value['consulta_2022_rnm']['participacion_ciudadana'], 2, '.', '').' %</b>
										</div>
										<div class="datos_consulta" style=" background: rgba(190, 195, 201, 0.9) "> 
											Votos Siga: <b>'.number_format($value['revocacion_mandato_2022']['orden_preguntas']['SIGA'], 0, '.', ',').' ('.number_format($porcenjate_siga, 2, '.', ',').'%)</b>
											<br>
											Votos No Siga: <b>'.number_format($value['revocacion_mandato_2022']['orden_preguntas']['NO_SIGA'], 0, '.', ',').' ('.number_format($porcenjate_no_siga, 2, '.', ',').'%)</b>
											<br>
											Votos Nulos: <b>'.number_format($value['consulta_rvm_2022_votos_nulos'], 0, '.', ',').'</b>
											<br>
											Votos Totales: <b>'.number_format($value['consulta_rvm_2022_votos_validos'], 0, '.', ',').'</b>
										</div>';
								}
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
				if(marcadores[i][3]==''){
					var icon = {
						//url: 'assets/images/iconos/cd-icon-location.png', // url
						scaledSize: new google.maps.Size(20, 22), // scaled size
					};
				}else{
					if(marcadores[i][3]=='numero'){
						var icon = {
							//url: 'assets/images/iconos/cd-icon-location.png', // url
							url : 'images/puntos_numeros/'+ marcadores[i][4],
							scaledSize: new google.maps.Size(20, 22), // scaled size
						};
					}else{
						var icon = {
							//url: 'assets/images/iconos/cd-icon-location.png', // url
							url : 'images/iconos_partidos/'+ marcadores[i][3],
							scaledSize: new google.maps.Size(20, 22), // scaled size
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
	