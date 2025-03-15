<?php
	include __DIR__.'/../../functions/security.php';
	@session_start();
	if(!empty($_POST)){
		include __DIR__.'/../../functions/elecciones.php'; 
		$elecciones = eleccionesModulo('2016');
	}
	//var_dump($_POST);
	$tipo = 4;
	$ano = $elecciones['senador'];
	$perido_fecha_inicial = $_COOKIE['fecha_inicial'];
	$perido_fecha_final = $_COOKIE['fecha_final'];


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

	
	if($perido_fecha_inicial != '' && $perido_fecha_final != ''){
		$sqlCiudadanosRegistrados = " AND DATE(sic.fechaR) BETWEEN '{$perido_fecha_inicial}' AND '{$perido_fecha_final}'";
		$sqlApoyosProgramas =  " AND sicpa.fecha BETWEEN '{$perido_fecha_inicial}' AND '{$perido_fecha_final}'";
		$sqlAccionesObras = " AND sia.fecha_inicio BETWEEN '{$perido_fecha_inicial}' AND '{$perido_fecha_final}'";
		$sqlMilitantes = " AND mp.fecha BETWEEN '{$perido_fecha_inicial}' AND '{$perido_fecha_final}'";
		$sqlFuncionarios = " AND sicc.fecha BETWEEN '{$perido_fecha_inicial}' AND '{$perido_fecha_final}'";
		$sql_giras = " AND sig.fecha BETWEEN '{$perido_fecha_inicial}' AND '{$perido_fecha_final}' ";
	}elseif($perido_fecha_inicial != '' && $perido_fecha_final == ''){

		$sqlCiudadanosRegistrados = " AND DATE(sic.fechaR) <= '{$perido_fecha_inicial}' ";
		$sqlApoyosProgramas =  " AND sicpa.fecha <= '{$perido_fecha_inicial}' ";
		$sqlAccionesObras = " AND sia.fecha_inicio <= '{$perido_fecha_inicial}' ";
		$sqlMilitantes = " AND mp.fecha <= '{$perido_fecha_inicial}' ";
		$sqlFuncionarios = " AND sicc.fecha <= '{$perido_fecha_inicial}' ";
		$sql_giras = " AND sig.fecha <= '{$perido_fecha_inicial}' ";

	}elseif($perido_fecha_inicial == '' && $perido_fecha_final != ''){
		$sqlCiudadanosRegistrados = " AND DATE(sic.fechaR) >= '{$perido_fecha_final}' ";
		$sqlApoyosProgramas =  " AND sicpa.fecha >= '{$perido_fecha_final}' ";
		$sqlAccionesObras = " AND sia.fecha_inicio >= '{$perido_fecha_final}' ";
		$sqlMilitantes = " AND mp.fecha >= '{$perido_fecha_final}' ";
		$sqlFuncionarios = " AND sicc.fecha >= '{$perido_fecha_final}' ";
		$sql_giras = " AND sig.fecha >= '{$perido_fecha_final}' ";
	}else{
		$sqlCiudadanosRegistrados = "";
		$sqlApoyosProgramas =  "";
		$sqlAccionesObras = "";
		$sqlMilitantes = "";
		$sqlFuncionarios = "";
		$sql_giras = "";
	}

	//var_dump($_POST);
	if(!empty($_POST)){
		include __DIR__."/../../functions/municipios_parametros.php"; 
		include __DIR__."/../../functions/municipios.php";
		include __DIR__."/../../functions/secciones_ine_parametros.php";
		include __DIR__."/../../functions/configuracion_matriz_rentabilidad_secciones_ine_2016.php";
		include __DIR__."/../../functions/efs.php";
		$rutaEfs = rutaEfs();

		$configuracion_matriz_rentabilidad_secciones_ine_2016Datos = configuracion_matriz_rentabilidad_secciones_ine_2016Datos();
		$votos_semaforo_amarillo = $configuracion_matriz_rentabilidad_secciones_ine_2016Datos['votos_semaforo_amarillo'];
		$id_tipo_categoria_ciudadano = $configuracion_matriz_rentabilidad_secciones_ine_2016Datos['id_tipo_categoria_ciudadano'] ;// funcionario
		$id_partido_2016 = $configuracion_matriz_rentabilidad_secciones_ine_2016Datos['id_partido_2016_senador'];// Partidos 2016 PRI
		//$id_partido_2016 = $configuracion_matriz['id_partido_2016'] = '1';// Partidos 2016
		$id_partido_legado = $configuracion_matriz_rentabilidad_secciones_ine_2016Datos['id_partido_legado'] = '1';// Partidos Legados
		#$tipo = $configuracion_matriz['tipo_eleccion'] = '1';// 0 - Ayuntamiento | 1 - Distrito Local | 2 - Distrito Federal
		/// en el formulario segun el tipo sera lo que te va mostrar el select sera un onchange para que cambie funcionara igual que el de localidades y municipio el principal seria tipo_eleccion y segun lo que escojas sera los partidos que te salgan 

		function truncar($numero, $digitos){
			$truncar = 10**$digitos;
			return intval($numero * $truncar) / $truncar;
		}
		$zoom="14";
		$orderby = ' ORDER BY fechaR DESC';
		$limit = 'LIMIT 0,84';
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

		$partido_ganador_id = $_POST['searchTable'][0]['partido_ganador_id'];
		$tipo_seccion = $_POST['searchTable'][0]['tipo_seccion'];
		$id_municipio = $_POST['searchTable'][0]['id_municipio'];
		$semaforo = $_POST['searchTable'][0]['semaforo'];
		$id_secciones_ine = $_POST['searchTable'][0]['id_secciones_ine'];
		if($id_seccion_ine == ''){
			$id_seccion_ine = $id_secciones_ine;
		}

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
			WHERE main.id_estado = '{$id_estado}'
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
		//! Obtemos las secciones para validar si existen y si no existen agregarle los partidos que no encontro y colocarles 0
		$sql = "SELECT * FROM secciones_ine WHERE id_municipio = $id_municipio ";
		$result = $conexion->query($sql);
		while($row=$result->fetch_assoc()){
			$secciones_validador_partidos[$row['id']] = $row;
		}
		if($id_seccion_ine!=''){
			$sqlPartidos = " AND cvp.id_seccion_ine IN ({$id_seccion_ine}) ";
			$sqlPartidosRM2019 = " AND cprm.id_seccion_ine IN ({$id_seccion_ine}) ";
		}


		if($id_distrito_local!=''){
			$sqlDistritoLocal = " AND cvp.id_distrito_local IN ({$id_distrito_local}) ";
			$sqlDistritoLocalRM2019 = " AND cprm.id_distrito_local IN ({$id_distrito_local}) ";
		}
		if($id_distrito_federal!=''){
			$sqlDistritoFederal = " AND cvp.id_distrito_federal IN ({$id_distrito_federal}) ";
			$sqlDistritoFederalRM2019 = " AND cprm.id_distrito_federal IN ({$id_distrito_federal}) ";
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
			FROM secciones_ine s
			LEFT JOIN  casillas_votos_partidos_2016 cvp
			ON s.id = cvp.id_seccion_ine
			LEFT JOIN partidos_2016 p
			ON p.id = cvp.id_partido_2016
			WHERE cvp.id_municipio='{$id_municipio}' AND cvp.tipo = '{$tipo}' {$sqlPartidos} {$sqlDistritoLocal} {$sqlDistritoFederal}
			GROUP BY cvp.id_seccion_ine,cvp.id_partido_2016
		";
		$result = $conexion->query($sql);

		while($row=$result->fetch_assoc()){
			$id_seccion_ine_base = $row['id_seccion_ine'];
			$partidos[$row['clave']] = $row['nombre_corto'];
			if($row['clave_partidos_coaliciones'] == ''){
				unset($row['clave_partidos_coaliciones']);
			}
			if($row['principal'] == ''){
				unset($row['principal']);
			}
			#$datos_partidos[$num]=$row;
			//? Colocamos en su arrelgo segun sea el tipo de partido
			if($row['clave_partidos_coaliciones'] != ''){
				$partidos_coaliciones[$row['id_seccion_ine']][$row['clave_partidos_coaliciones']]=$row;
			}else{
				$partidos_sin_coaliciones[$row['id_seccion_ine']][$row['clave']]=$row;
			} 
			$num=$num+1;
		}
		//! Agrga,ps ñps datps de los NODATA
		foreach ($secciones_validador_partidos as $key => $value) {
			if(empty($partidos_coaliciones[$key])){
				///id_seccion sin informacion de casillas
				foreach ($partidos_coaliciones[$id_seccion_ine_base] as $keyT => $valueT) {
					$partidos_coaliciones[$key][$keyT] = $valueT;
					$partidos_coaliciones[$key][$keyT]['votos'] = 0;
					$partidos_coaliciones[$key][$keyT]['id_seccion_ine'] = $key;
				}
				foreach ($partidos_sin_coaliciones[$id_seccion_ine_base] as $keyT => $valueT) {
					$partidos_sin_coaliciones[$key][$keyT] = $valueT;
					$partidos_sin_coaliciones[$key][$keyT]['votos'] = 0;
					$partidos_sin_coaliciones[$key][$keyT]['id_seccion_ine'] = $key;
				}
			}
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
			WHERE cprm.id_municipio = '{$id_municipio}'  {$sqlPartidosRM2019} {$sqlDistritoLocalRM2019} {$sqlDistritoFederalRM2019}
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
				si.id_distrito_local,
				si.id_distrito_federal,
				(SELECT m.municipio FROM municipios m WHERE m.id = si.id_municipio) municipio,
				(SELECT GROUP_CONCAT(DISTINCT CONCAT(ls.localidad) SEPARATOR '*_*' ) FROM localidades_secciones_ine ls WHERE ls.seccion = si.clave AND ls.id_estado='{$id_estado}' ) seccion_localidades,
				(SELECT GROUP_CONCAT(DISTINCT CONCAT(ls.nombre) SEPARATOR '*_*' ) FROM secciones_ine_colonias ls WHERE ls.seccion_ine = si.clave ) seccion_colonias,
				(SELECT COUNT(cv.id) FROM casillas_votos_2016 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') casillas,
				(SELECT SUM(cv.lista_nominal) FROM casillas_votos_2016 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}' ) lista_nominal,
				(SELECT SUM(cv.votos_nulos) FROM casillas_votos_2016 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_nulos,
				(SELECT SUM(cv.votos_can_nreg) FROM casillas_votos_2016 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_can_nreg,
				(SELECT SUM(cv.votos) FROM casillas_votos_partidos_2016 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_validos,

				(SELECT COUNT(sic.id) FROM secciones_ine_ciudadanos sic WHERE sic.id_municipio={$id_municipio} AND sic.id_seccion_ine = si.id {$sqlCiudadanosRegistrados} ) ciudadanos_registrados,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sicpa.id_seccion_ine_ciudadano = sic.id WHERE sic.id_municipio={$id_municipio} AND sic.id_seccion_ine = si.id {$sqlApoyosProgramas} ) apoyos_programas,
				(SELECT COUNT(*) FROM secciones_ine_actividades sia WHERE sia.id_seccion_ine = si.id {$sqlAccionesObras} ) acciones_obras,
				(SELECT COUNT(*) FROM secciones_ine_grupos sig WHERE sig.id_seccion_ine = si.id ) grupos_interes,
				(SELECT COUNT(*) FROM militantes_partidos mp LEFT JOIN secciones_ine_ciudadanos sic ON mp.id_seccion_ine_ciudadano = sic.id WHERE sic.id_municipio={$id_municipio} AND mp.id_partido_legado = '{$id_partido_legado}' AND sic.id_seccion_ine = si.id {$sqlMilitantes}  ) militantes,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_municipio={$id_municipio} AND sicc.id_tipo_categoria_ciudadano = '{$id_tipo_categoria_ciudadano}' AND sicc.id_seccion_ine = si.id  {$sqlFuncionarios} ) funcionarios,

				(SELECT COUNT(*) FROM secciones_ine_giras sig WHERE sig.id_seccion_ine = si.id AND sig.tipo = 'junta' {$sql_giras}  ) juntas,
				(SELECT COUNT(*) FROM secciones_ine_giras sig WHERE sig.id_seccion_ine = si.id AND sig.tipo = 'visita' {$sql_giras}  ) visitas,
				(SELECT COUNT(*) FROM secciones_ine_giras sig WHERE sig.id_seccion_ine = si.id AND sig.tipo = 'caminata' {$sql_giras}  ) caminatas,

				(SELECT SUM(cvrm2019.lista_nominal) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id ) consulta_rvm_lista_nominal,
				(SELECT COUNT(*) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_casillas,
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
		if($id_distrito_local!=''){
			$sql.=" AND si.id_distrito_local IN ({$id_distrito_local}) ";
		}
		if($id_distrito_federal!=''){
			$sql.=" AND si.id_distrito_federal IN ({$id_distrito_federal}) ";
		}
		//echo "<pre>";
		//print_r($sql);
		//echo "</pre>";
		$sql.= ' ORDER BY si.numero ';
		$result = $conexion->query($sql); 
		while($row=$result->fetch_assoc()){

			$datos_municipios[$id_municipio]['latitud'] = $row['latitud'];
			$datos_municipios[$id_municipio]['longitud'] = $row['longitud'];

			$id_secciones_ine_validos[] =  $row['id'];
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
					$pos = strpos($nombre_corto, $array['clave']);
					if ($pos !== false ) {
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

					$datos_secciones_ine[$row['id']]['orden_votos_individual']['graficas']['partidos'][] = $partido;
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['graficas']['votos'][] = $votos;
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['graficas']['background'][] = '#'.$datos_secciones_ine[$row['id']]['partidos'][$partido]['color_background'];

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
				$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['competitividad'] = '0';
			}else{
				if(!empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza']) && !empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza']) ){
					//? sacamos el id del ganador y vemos si pertenece al grupo
					foreach ($datos_secciones_ine[$row['id']]['partidos'] as $nombrePartido => $data) {
						if( $data['id'] == $id_partido_2016){
							$searh_partido = $nombrePartido;
						}
					}
					$partido_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'];
					$votos_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido_primera_fuerza];

					$partido_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'];
					$votos_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido_segunda_fuerza];

					$partido_sistema_fuerza = $searh_partido;
					$votos_sistema_fuerza = $datos_secciones_ine[$row['id']]['partidos'][$searh_partido]['votos_individual'];

					if($partido_primera_fuerza ==$searh_partido){
						//? preguntamos si son iguales para comparar si es amarillo o verde
						//! Rango Rojo 0 a 50
						//! Amarilo 50 a 100
						//! Verde 100 a superior
						$competitividad = $votos_primera_fuerza - $votos_segunda_fuerza;
						if($competitividad >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'verde';
						}elseif ( $competitividad <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						}elseif ($competitividad >=50 || $competitividad <100  ) {
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'amarillo';
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						}
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['rentabilidad'] = $row['votos_totales'] - $votos_primera_fuerza;
					}else{
						$competitividad = $votos_primera_fuerza - $votos_sistema_fuerza;
						$competitividad = $competitividad * -1;
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['rentabilidad'] = $row['votos_totales'] - $votos_sistema_fuerza;
					}
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['competitividad'] = $competitividad;

					$competitividad_rentabilidad_individual['rentabilidad'][$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['rentabilidad']][] = $row['id'];
					$competitividad_rentabilidad_individual['competitividad'][$competitividad][] = $row['id'];


					////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////
					///? esto es para en votos en individual y es para saber las competitividads entre primera y segunda fuerza aunque no sea del
					///? partido primera y segunda fuerza
					/*
					//? sacamos el id del ganador y vemos si pertenece al grupo
					foreach ($datos_secciones_ine[$row['id']]['partidos'] as $nombrePartido => $data) {
						if( $data['id'] == $id_partido_2016){
							$searh_partido = $nombrePartido;
						}
					}
					$partido_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'];
					$votos_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido_primera_fuerza];

					$partido_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'];
					$votos_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido_segunda_fuerza];
					//? buscamos los partidos de coalicon de la primera fuerza
					$partido_primera_fuerza_coaliciones = $datos_secciones_ine[$row['id']]['partidos'][$partido_primera_fuerza]['coaliciones_orden_votos_individual'];

					//? esto es por fuerza en votos en indivodual
					$competitividad = $votos_primera_fuerza - $votos_segunda_fuerza;

					$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['partidos'] = $partido_primera_fuerza.':'.$votos_primera_fuerza.'-'.$partido_segunda_fuerza.':'.$votos_segunda_fuerza;

					if($partido_primera_fuerza ==$searh_partido){
						//? preguntamos si son iguales para comparar si es amarillo o verde
						//! Rango Rojo 0 a 50
						//! Amarilo 50 a 100
						//! Verde 100 a superior
						if($competitividad >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'verde';
						}elseif ( $competitividad <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						}elseif ($competitividad >=50 || $competitividad <100  ) {
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
							if($competitividad >=100){
								$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'verde';
							}elseif ( $competitividad <50 ) {
								$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
							}elseif ($competitividad >=50 || $competitividad <100  ) {
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
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['competitividad'] = $competitividad;
					*/
					////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////
				}
			}
			$validador = 0;
			foreach ($ordena_votos_individual[$row['id']] as $votos => $partidos_array) {
				foreach ($partidos_array as $index => $partido) {
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido]=$votos;

					$datos_secciones_ine[$row['id']]['orden_votos_totales']['graficas']['partidos'][] = $partido;
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['graficas']['votos'][] = $votos;
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['graficas']['background'][] = '#'.$datos_secciones_ine[$row['id']]['partidos'][$partido]['color_background'];

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
				$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['competitividad'] = '0';
			}else{
				if(!empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza']) && !empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza']) ){
					//? sacamos el id del ganador y vemos si pertenece al grupo y vemos su id de partido previa configuracion en config matriz
					foreach ($datos_secciones_ine[$row['id']]['partidos'] as $nombrePartido => $data) {
						if( $data['id'] == $id_partido_2016){
							$searh_partido = $nombrePartido;
						}
					}
					$partido_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'];
					$votos_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido_primera_fuerza];

					$partido_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza'];
					$votos_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido_segunda_fuerza];
					//? buscamos los partidos de coalicon de la primera fuerza
					$partido_primera_fuerza_coaliciones = $datos_secciones_ine[$row['id']]['partidos'][$partido_primera_fuerza]['coaliciones_orden_votos_individual'];
					$competitividad = $votos_primera_fuerza - $votos_segunda_fuerza;



					$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['partidos'] = $partido_primera_fuerza.':'.$votos_primera_fuerza.'-'.$partido_segunda_fuerza.':'.$votos_segunda_fuerza;
					if($partido_primera_fuerza ==$searh_partido){
						//? preguntamos si son iguales para comparar si es amarillo o verde
						//! Rango Rojo 0 a 50
						//! Amarilo 50 a 100
						//! Verde 100 a superior
						if($competitividad >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'verde';
						}elseif ( $competitividad <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						}elseif ($competitividad >=50 || $competitividad <100  ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'amarillo';
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						}
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['rentabilidad'] = $row['votos_totales'] - $votos_primera_fuerza;
					}elseif (!empty($partido_primera_fuerza_coaliciones[$searh_partido])) {
						//? preguntamos si son iguales para comparar si es amarillo o verde
						if($competitividad >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'verde';
						}elseif ( $competitividad <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo1';
						}elseif ($competitividad >=50 || $competitividad <100  ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'amarillo';
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						}
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['rentabilidad'] = $row['votos_totales'] - $partido_segunda_fuerza;
					}else{
						$competitividad = $competitividad * -1;
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['rentabilidad'] = $row['votos_totales'] - $partido_segunda_fuerza;
					}
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['competitividad'] = $competitividad;

					$competitividad_rentabilidad_totales['rentabilidad'][$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['rentabilidad']][] = $row['id'];
					$competitividad_rentabilidad_totales['competitividad'][$competitividad][] = $row['id'];

				}
			}

			//! Importante
			//? Ordenamos los partidos Menor a Mayor
			ksort($ordena_votos_individual[$row['id']]);
			ksort($ordena_votos_totales[$row['id']]);

			foreach ($ordena_votos_individual[$row['id']] as $votos => $partidos_array) {
				foreach ($partidos_array as $index => $partido) {
					$datos_secciones_ine[$row['id']]['orden_votos_individual_menor_mayor']['partidos'][$partido]=$votos;
					$datos_secciones_ine[$row['id']]['orden_votos_individual_menor_mayor']['graficas']['partidos'][] = $partido;
					$datos_secciones_ine[$row['id']]['orden_votos_individual_menor_mayor']['graficas']['votos'][] = $votos;
					$datos_secciones_ine[$row['id']]['orden_votos_individual_menor_mayor']['graficas']['background'][] = '#'.$datos_secciones_ine[$row['id']]['partidos'][$partido]['color_background'];
				}
			}

			foreach ($ordena_votos_individual[$row['id']] as $votos => $partidos_array) {
				foreach ($partidos_array as $index => $partido) {
					$datos_secciones_ine[$row['id']]['orden_votos_totales_menor_mayor']['partidos'][$partido]=$votos;

					$datos_secciones_ine[$row['id']]['orden_votos_totales_menor_mayor']['graficas']['partidos'][] = $partido;
					$datos_secciones_ine[$row['id']]['orden_votos_totales_menor_mayor']['graficas']['votos'][] = $votos;
					$datos_secciones_ine[$row['id']]['orden_votos_totales_menor_mayor']['graficas']['background'][] = '#'.$datos_secciones_ine[$row['id']]['partidos'][$partido]['color_background'];

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
		$rutaEfs = rutaEfs();
		$zoom="14";
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
			WHERE main.id_estado = '{$id_estado}'
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
		//! Obtemos las secciones para validar si existen y si no existen agregarle los partidos que no encontro y colocarles 0
		$sql = "SELECT * FROM secciones_ine WHERE id_municipio = $id_municipio ";
		$result = $conexion->query($sql);
		while($row=$result->fetch_assoc()){
			$secciones_validador_partidos[$row['id']] = $row;
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
			FROM secciones_ine s
			LEFT JOIN  casillas_votos_partidos_2016 cvp
			ON s.id = cvp.id_seccion_ine
			LEFT JOIN partidos_2016 p
			ON p.id = cvp.id_partido_2016
			WHERE cvp.id_municipio='{$id_municipio}' AND cvp.tipo = '{$tipo}' 
			GROUP BY cvp.id_seccion_ine,cvp.id_partido_2016
		";
		$result = $conexion->query($sql);
		unset($partidos_coaliciones);
		unset($partidos_sin_coaliciones);
		while($row=$result->fetch_assoc()){
			$partidos[$row['clave']] = $row['nombre_corto'];
			$id_seccion_ine_base = $row['id_seccion_ine'];
			if($row['clave_partidos_coaliciones'] == ''){
				unset($row['clave_partidos_coaliciones']);
			}
			if($row['principal'] == ''){
				unset($row['principal']);
			}
			#$datos_partidos[$num]=$row;
			//? Colocamos en su arrelgo segun sea el tipo de partido
			if($row['clave_partidos_coaliciones'] != ''){
				$partidos_coaliciones[$row['id_seccion_ine']][$row['clave_partidos_coaliciones']]=$row;
			}else{
				$partidos_sin_coaliciones[$row['id_seccion_ine']][$row['clave']]=$row;
			}
			$num=$num+1;
		}
		//! Agrga,ps ñps datps de los NODATA
		foreach ($secciones_validador_partidos as $key => $value) {
			if(empty($partidos_coaliciones[$key])){
				///id_seccion sin informacion de casillas
				foreach ($partidos_coaliciones[$id_seccion_ine_base] as $keyT => $valueT) {
					$partidos_coaliciones[$key][$keyT] = $valueT;
					$partidos_coaliciones[$key][$keyT]['votos'] = 0;
					$partidos_coaliciones[$key][$keyT]['id_seccion_ine'] = $key;
				}
				foreach ($partidos_sin_coaliciones[$id_seccion_ine_base] as $keyT => $valueT) {
					$partidos_sin_coaliciones[$key][$keyT] = $valueT;
					$partidos_sin_coaliciones[$key][$keyT]['votos'] = 0;
					$partidos_sin_coaliciones[$key][$keyT]['id_seccion_ine'] = $key;
				}
			}
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
				si.id_distrito_local,
				si.id_distrito_federal,
				(SELECT m.municipio FROM municipios m WHERE m.id = si.id_municipio) municipio,
				(SELECT GROUP_CONCAT(DISTINCT CONCAT(ls.localidad) SEPARATOR '*_*' ) FROM localidades_secciones_ine ls WHERE ls.seccion = si.clave AND ls.id_estado='{$id_estado}' ) seccion_localidades,
				(SELECT GROUP_CONCAT(DISTINCT CONCAT(ls.nombre) SEPARATOR '*_*' ) FROM secciones_ine_colonias ls WHERE ls.seccion_ine = si.clave ) seccion_colonias,
				(SELECT COUNT(cv.id) FROM casillas_votos_2016 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') casillas,
				(SELECT SUM(cv.lista_nominal) FROM casillas_votos_2016 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}' ) lista_nominal,
				(SELECT SUM(cv.votos_nulos) FROM casillas_votos_2016 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_nulos,
				(SELECT SUM(cv.votos_can_nreg) FROM casillas_votos_2016 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_can_nreg,
				(SELECT SUM(cv.votos) FROM casillas_votos_partidos_2016 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_validos,

				(SELECT COUNT(sic.id) FROM secciones_ine_ciudadanos sic WHERE sic.id_municipio={$id_municipio} AND sic.id_seccion_ine = si.id {$sqlCiudadanosRegistrados} ) ciudadanos_registrados,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sicpa.id_seccion_ine_ciudadano = sic.id WHERE sic.id_municipio={$id_municipio} AND sic.id_seccion_ine = si.id {$sqlApoyosProgramas} ) apoyos_programas,
				(SELECT COUNT(*) FROM secciones_ine_actividades sia WHERE sia.id_seccion_ine = si.id {$sqlAccionesObras} ) acciones_obras,
				(SELECT COUNT(*) FROM secciones_ine_grupos sig WHERE sig.id_seccion_ine = si.id ) grupos_interes,
				(SELECT COUNT(*) FROM militantes_partidos mp LEFT JOIN secciones_ine_ciudadanos sic ON mp.id_seccion_ine_ciudadano = sic.id WHERE sic.id_municipio={$id_municipio} AND mp.id_partido_legado = '{$id_partido_legado}' AND sic.id_seccion_ine = si.id {$sqlMilitantes}  ) militantes,
				(SELECT COUNT(*) FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_municipio={$id_municipio} AND sicc.id_tipo_categoria_ciudadano = '{$id_tipo_categoria_ciudadano}' AND sicc.id_seccion_ine = si.id  {$sqlFuncionarios} ) funcionarios,

				(SELECT COUNT(*) FROM secciones_ine_giras sig WHERE sig.id_seccion_ine = si.id AND sig.tipo = 'junta' {$sql_giras}  ) juntas,
				(SELECT COUNT(*) FROM secciones_ine_giras sig WHERE sig.id_seccion_ine = si.id AND sig.tipo = 'visita' {$sql_giras}  ) visitas,
				(SELECT COUNT(*) FROM secciones_ine_giras sig WHERE sig.id_seccion_ine = si.id AND sig.tipo = 'caminata' {$sql_giras}  ) caminatas,

				(SELECT SUM(cvrm2019.lista_nominal) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id ) consulta_rvm_lista_nominal,
				(SELECT COUNT(*) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_casillas,
				(SELECT SUM(cvrm2019.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_votos_nulos,
				(SELECT SUM(cvrm2019.votos_can_nreg) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_votos_can_nreg,
				(SELECT SUM(cpvrm2019.votos) FROM casillas_preguntas_2022_revocacion_mandato cpvrm2019 WHERE cpvrm2019.id_seccion_ine = si.id) consulta_rvm_votos_validos


			FROM secciones_ine si
			WHERE si.id_municipio = '$id_municipio'
		";
		$sql.= ' ORDER BY si.numero ';
		//echo "<pre>";
		//print_r($sql);
		//echo "</pre>";
		$result = $conexion->query($sql); 
		while($row=$result->fetch_assoc()){
			$id_secciones_ine_validos[] =  $row['id'];
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
					$pos = strpos($nombre_corto, $array['clave']);
					if ($pos !== false ) {
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

					$datos_secciones_ine[$row['id']]['orden_votos_individual']['graficas']['partidos'][] = $partido;
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['graficas']['votos'][] = $votos;
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['graficas']['background'][] = '#'.$datos_secciones_ine[$row['id']]['partidos'][$partido]['color_background'];

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
				$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['competitividad'] = '0';
			}else{
				if(!empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza']) && !empty($datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza']) ){
					//? sacamos el id del ganador y vemos si pertenece al grupo
					foreach ($datos_secciones_ine[$row['id']]['partidos'] as $nombrePartido => $data) {
						if( $data['id'] == $id_partido_2016){
							$searh_partido = $nombrePartido;
						}
					}
					$partido_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'];
					$votos_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido_primera_fuerza];

					$partido_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'];
					$votos_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido_segunda_fuerza];

					$partido_sistema_fuerza = $searh_partido;
					$votos_sistema_fuerza = $datos_secciones_ine[$row['id']]['partidos'][$searh_partido]['votos_individual'];

					if($partido_primera_fuerza ==$searh_partido){
						//? preguntamos si son iguales para comparar si es amarillo o verde
						//! Rango Rojo 0 a 50
						//! Amarilo 50 a 100
						//! Verde 100 a superior
						$competitividad = $votos_primera_fuerza - $votos_segunda_fuerza;
						if($competitividad >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'verde';
						}elseif ( $competitividad <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						}elseif ($competitividad >=50 || $competitividad <100  ) {
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'amarillo';
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						}
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['rentabilidad'] = $row['votos_totales'] - $votos_primera_fuerza;
					}else{
						$competitividad = $votos_primera_fuerza - $votos_sistema_fuerza;
						$competitividad = $competitividad * -1;
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['rentabilidad'] = $row['votos_totales'] - $votos_sistema_fuerza;
					}
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['competitividad'] = $competitividad;

					$competitividad_rentabilidad_individual['rentabilidad'][$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['rentabilidad']][] = $row['id'];
					$competitividad_rentabilidad_individual['competitividad'][$competitividad][] = $row['id'];


					////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////
					///? esto es para en votos en individual y es para saber las competitividads entre primera y segunda fuerza aunque no sea del
					///? partido primera y segunda fuerza
					/*
					//? sacamos el id del ganador y vemos si pertenece al grupo
					foreach ($datos_secciones_ine[$row['id']]['partidos'] as $nombrePartido => $data) {
						if( $data['id'] == $id_partido_2016){
							$searh_partido = $nombrePartido;
						}
					}
					$partido_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['primera_fuerza'];
					$votos_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido_primera_fuerza];

					$partido_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['segunda_fuerza'];
					$votos_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_individual']['partidos'][$partido_segunda_fuerza];
					//? buscamos los partidos de coalicon de la primera fuerza
					$partido_primera_fuerza_coaliciones = $datos_secciones_ine[$row['id']]['partidos'][$partido_primera_fuerza]['coaliciones_orden_votos_individual'];

					//? esto es por fuerza en votos en indivodual
					$competitividad = $votos_primera_fuerza - $votos_segunda_fuerza;

					$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['partidos'] = $partido_primera_fuerza.':'.$votos_primera_fuerza.'-'.$partido_segunda_fuerza.':'.$votos_segunda_fuerza;

					if($partido_primera_fuerza ==$searh_partido){
						//? preguntamos si son iguales para comparar si es amarillo o verde
						//! Rango Rojo 0 a 50
						//! Amarilo 50 a 100
						//! Verde 100 a superior
						if($competitividad >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'verde';
						}elseif ( $competitividad <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
						}elseif ($competitividad >=50 || $competitividad <100  ) {
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
							if($competitividad >=100){
								$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'verde';
							}elseif ( $competitividad <50 ) {
								$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['color'] = 'rojo';
							}elseif ($competitividad >=50 || $competitividad <100  ) {
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
					$datos_secciones_ine[$row['id']]['orden_votos_individual']['semaforo']['competitividad'] = $competitividad;
					*/
					////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////
					////////////////////////////////////////////////////////////////////////////////////
				}
			}
			$validador = 0;
			foreach ($ordena_votos_individual[$row['id']] as $votos => $partidos_array) {
				foreach ($partidos_array as $index => $partido) {
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido]=$votos;

					$datos_secciones_ine[$row['id']]['orden_votos_totales']['graficas']['partidos'][] = $partido;
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['graficas']['votos'][] = $votos;
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['graficas']['background'][] = '#'.$datos_secciones_ine[$row['id']]['partidos'][$partido]['color_background'];

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
				$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['competitividad'] = '0';
			}else{
				if(!empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza']) && !empty($datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza']) ){
					//? sacamos el id del ganador y vemos si pertenece al grupo y vemos su id de partido previa configuracion en config matriz
					foreach ($datos_secciones_ine[$row['id']]['partidos'] as $nombrePartido => $data) {
						if( $data['id'] == $id_partido_2016){
							$searh_partido = $nombrePartido;
						}
					}
					$partido_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['primera_fuerza'];
					$votos_primera_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido_primera_fuerza];

					$partido_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['segunda_fuerza'];
					$votos_segunda_fuerza = $datos_secciones_ine[$row['id']]['orden_votos_totales']['partidos'][$partido_segunda_fuerza];
					//? buscamos los partidos de coalicon de la primera fuerza
					$partido_primera_fuerza_coaliciones = $datos_secciones_ine[$row['id']]['partidos'][$partido_primera_fuerza]['coaliciones_orden_votos_individual'];
					$competitividad = $votos_primera_fuerza - $votos_segunda_fuerza;



					$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['partidos'] = $partido_primera_fuerza.':'.$votos_primera_fuerza.'-'.$partido_segunda_fuerza.':'.$votos_segunda_fuerza;
					if($partido_primera_fuerza ==$searh_partido){
						//? preguntamos si son iguales para comparar si es amarillo o verde
						//! Rango Rojo 0 a 50
						//! Amarilo 50 a 100
						//! Verde 100 a superior
						if($competitividad >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'verde';
						}elseif ( $competitividad <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						}elseif ($competitividad >=50 || $competitividad <100  ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'amarillo';
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						}
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['rentabilidad'] = $row['votos_totales'] - $votos_primera_fuerza;
					}elseif (!empty($partido_primera_fuerza_coaliciones[$searh_partido])) {
						//? preguntamos si son iguales para comparar si es amarillo o verde
						if($competitividad >=100){
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'verde';
						}elseif ( $competitividad <50 ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo1';
						}elseif ($competitividad >=50 || $competitividad <100  ) {
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'amarillo';
						}else{
							$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						}
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['rentabilidad'] = $row['votos_totales'] - $partido_segunda_fuerza;
					}else{
						$competitividad = $competitividad * -1;
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['color'] = 'rojo';
						$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['rentabilidad'] = $row['votos_totales'] - $partido_segunda_fuerza;
					}
					$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['competitividad'] = $competitividad;

					$competitividad_rentabilidad_totales['rentabilidad'][$datos_secciones_ine[$row['id']]['orden_votos_totales']['semaforo']['rentabilidad']][] = $row['id'];
					$competitividad_rentabilidad_totales['competitividad'][$competitividad][] = $row['id'];

				}
			}

			//! Importante
			//? Ordenamos los partidos Menor a Mayor
			ksort($ordena_votos_individual[$row['id']]);
			ksort($ordena_votos_totales[$row['id']]);

			foreach ($ordena_votos_individual[$row['id']] as $votos => $partidos_array) {
				foreach ($partidos_array as $index => $partido) {
					$datos_secciones_ine[$row['id']]['orden_votos_individual_menor_mayor']['partidos'][$partido]=$votos;
					$datos_secciones_ine[$row['id']]['orden_votos_individual_menor_mayor']['graficas']['partidos'][] = $partido;
					$datos_secciones_ine[$row['id']]['orden_votos_individual_menor_mayor']['graficas']['votos'][] = $votos;
					$datos_secciones_ine[$row['id']]['orden_votos_individual_menor_mayor']['graficas']['background'][] = '#'.$datos_secciones_ine[$row['id']]['partidos'][$partido]['color_background'];
				}
			}

			foreach ($ordena_votos_individual[$row['id']] as $votos => $partidos_array) {
				foreach ($partidos_array as $index => $partido) {
					$datos_secciones_ine[$row['id']]['orden_votos_totales_menor_mayor']['partidos'][$partido]=$votos;

					$datos_secciones_ine[$row['id']]['orden_votos_totales_menor_mayor']['graficas']['partidos'][] = $partido;
					$datos_secciones_ine[$row['id']]['orden_votos_totales_menor_mayor']['graficas']['votos'][] = $votos;
					$datos_secciones_ine[$row['id']]['orden_votos_totales_menor_mayor']['graficas']['background'][] = '#'.$datos_secciones_ine[$row['id']]['partidos'][$partido]['color_background'];

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
		$json_data = json_encode($competitividad_rentabilidad_individual);//Json donde se guarda la informacion para evitar las variables de sesion
		if ($json_data === false) {
			// Manejar el error de codificación JSON si es necesario
			//echo "Error al codificar el JSON";
		} else {
			// Guarda el JSON en un archivo
			$archivo_json = $rutaEfs.'competitividad_rentabilidad_individual_senador_2016_'.$id_municipio.'-'.$_COOKIE["id_usuario"].'.json';
			if (file_put_contents($archivo_json, $json_data)) {
				//echo "Los datos se han guardado en $archivo_json";
			} else {
				//echo "Error al guardar los datos en $archivo_json";
			}
		}
		$json_data = json_encode($competitividad_rentabilidad_totales);//Json donde se guarda la informacion para evitar las variables de sesion
		if ($json_data === false) {
			// Manejar el error de codificación JSON si es necesario
			//echo "Error al codificar el JSON";
		} else {
			// Guarda el JSON en un archivo
			$archivo_json = $rutaEfs.'competitividad_rentabilidad_totales_senador_2016_'.$id_municipio.'-'.$_COOKIE["id_usuario"].'.json';
			if (file_put_contents($archivo_json, $json_data)) {
				//echo "Los datos se han guardado en $archivo_json";
			} else {
				//echo "Error al guardar los datos en $archivo_json";
			}
		}

	}
	$archivo_json = $rutaEfs . 'competitividad_rentabilidad_individual_senador_2016_'.$id_municipio.'-'.$_COOKIE["id_usuario"].'.json';
	if (file_exists($archivo_json)) {
		// Lee el contenido del archivo JSON
		$json_data = file_get_contents($archivo_json);

		// Decodifica el JSON en un array asociativo
		$competitividad_rentabilidad_individual = json_decode($json_data, true);

		if ($competitividad_rentabilidad_individual === null) {
			// Manejar un error en la decodificación si es necesario
			//echo "Error al decodificar el JSON";
		} else {
			// Ahora tienes el array $datos_secciones_ine disponible para su uso
			//print_r($competitividad_rentabilidad_individual);
		}
	} else {
		//echo "El archivo JSON no existe en la ruta especificada";
	}
	$orden = 1;
	$competitividad_rentabilidad_individual = $competitividad_rentabilidad_individual;
	ksort($competitividad_rentabilidad_individual['rentabilidad']);
	foreach ($competitividad_rentabilidad_individual['rentabilidad'] as $rentabilidad => $secciones) {
		foreach ($secciones as $key => $id_seccion_ine) {
			$datos_secciones_ine[$id_seccion_ine]['orden_votos_individual']['semaforo']['rentabilidad_orden'] = $orden;
			$orden ++;
		}
	}

	$orden = 1;
	krsort($competitividad_rentabilidad_individual['competitividad']);
	foreach ($competitividad_rentabilidad_individual['competitividad'] as $rentabilidad => $secciones) {
		foreach ($secciones as $key => $id_seccion_ine) {
			$datos_secciones_ine[$id_seccion_ine]['orden_votos_individual']['semaforo']['competitividad_orden'] = $orden;
			$orden ++;
		}
	}




	if(empty($_POST)){
		$json_data = json_encode($datos_secciones_ine);//Json donde se guarda la informacion para evitar las variables de sesion
		if ($json_data === false) {
			// Manejar el error de codificación JSON si es necesario
			//echo "Error al codificar el JSON";
		} else {
			// Guarda el JSON en un archivo
			$archivo_json = $rutaEfs.'datos_secciones_ine_senador_2016_'.$id_municipio.'-'.$_COOKIE["id_usuario"].'.json';
			if (file_put_contents($archivo_json, $json_data)) {
				//echo "Los datos se han guardado en $archivo_json";
			} else {
				//echo "Error al guardar los datos en $archivo_json";
			}
		}
	}

	
	foreach ($datos_secciones_ine as $id_seccion_ine => $row) {
		$partidos = $row['partidos'];
	}
	$columnas_titulos = array(

		0 => array('row' => 'municipio' ,'nombre' => 'Municipio' ,'tipo' => 'string', 'fill' =>'#397CB5' ),
		1 => array('row' => 'id_distrito_local' ,'nombre' => 'D. Local' ,'tipo' => 'integer', 'fill' =>'#397CB5'),
		2 => array('row' => 'id_distrito_federal' ,'nombre' => 'D. Federal' ,'tipo' => 'integer', 'fill' =>'#397CB5'),
		3 => array('row' => 'seccion_localidades' ,'nombre' => 'Localidad(es)' ,'tipo' => 'string', 'fill' =>'#397CB5' ),
		4 => array('row' => 'seccion_colonias' ,'nombre' => 'Colonia(s)' ,'tipo' => 'string', 'fill' =>'#397CB5' ),
		5 => array('row' => 'numero' ,'nombre' => 'Sección' ,'tipo' => 'integer', 'fill' =>'#397CB5'),
		6 => array('row' => 'tipo' ,'nombre' => 'Tipo Sección' ,'tipo' => 'string', 'fill' =>'#397CB5'),

		7 => array('row' => 'primera_fuerza' ,'nombre' => 'Primera Fuerza' ,'tipo' => 'string', 'fill' =>'#213415'),
		8 => array('row' => 'votos_primera_fuerza' ,'nombre' => 'Votos Primera Fuerza' ,'tipo' => 'integer', 'fill' =>'#213415'),

		9 => array('row' => 'segunda_fuerza' ,'nombre' => 'Segunda Fuerza' ,'tipo' => 'string', 'fill' =>'#355b7d'),
		10 => array('row' => 'votos_segunda_fuerza' ,'nombre' => 'Votos Segunda Fuerza' ,'tipo' => 'integer', 'fill' =>'#355b7d'),

		11 => array('row' => 'sistema' ,'nombre' => 'Sistema' ,'tipo' => 'string', 'fill' =>'#8B8000'),
		12 => array('row' => 'votos_sistema' ,'nombre' => 'Votos Sistema' ,'tipo' => 'integer', 'fill' =>'#8B8000'),
		//? partidps
		13 => array('row' => 'partidos' ,'tipo' => 'integer'),
		///
		14 => array('row' => 'votos_can_nreg' ,'nombre' => 'Votos Can Nrg' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		15 => array('row' => 'votos_nulos' ,'nombre' => 'Votos Nulos' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		16 => array('row' => 'votos_totales' ,'nombre' => 'Votos Totales' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		17 => array('row' => 'lista_nominal' ,'nombre' => 'Lista Nominal' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		18 => array('row' => 'rentabilidad' ,'nombre' => 'Rentabilidad Votos Validos - '.$searh_partido ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		19 => array('row' => 'rentabilidad_posicion' ,'nombre' => 'Rentabilidad' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		20 => array('row' => 'competividad' ,'nombre' => 'Competividad Primera Fuerza - Segunda Fuerza' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		21 => array('row' => 'competividad_posicion' ,'nombre' => 'Competividad' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		22 => array('row' => 'semaforo' ,'nombre' => 'Semáforo' ,'tipo' => 'string', 'fill' =>'#397CB5' ),
		23 => array('row' => 'ciudadanos_registrados' ,'nombre' => 'Ciudadanos Registrados' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		24 => array('row' => 'acciones_obras' ,'nombre' => 'Programas de Inversión' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		25 => array('row' => 'apoyos_programas' ,'nombre' => 'Programas de Gobierno' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		26 => array('row' => 'funcionarios' ,'nombre' => 'Funcionarios' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		27 => array('row' => 'militantes' ,'nombre' => 'Militantes' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		28 => array('row' => 'juntas' ,'nombre' => 'Juntas' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		29 => array('row' => 'visitas' ,'nombre' => 'Visitas' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
		30 => array('row' => 'caminatas' ,'nombre' => 'Caminatas' ,'tipo' => 'integer', 'fill' =>'#397CB5' ),
	);

	foreach ($columnas_titulos as $key => $value) {
		if($value['row']=='partidos'){
			foreach ($partidos as $key => $value) {
				$columnas_titulos_partidos[]= array('row' => $key , 'nombre' => $key ,'tipo' => 'integer', 'fill' =>'#'.$value['color_background'] );
			}
		}else{
			$columnas_titulos_partidos[]=$value;
		}
	}
	$json_data = json_encode($columnas_titulos_partidos);//Json donde se guarda la informacion para evitar las variables de sesion
	if ($json_data === false) {
		// Manejar el error de codificación JSON si es necesario
		//echo "Error al codificar el JSON";
	} else {
		// Guarda el JSON en un archivo
		$archivo_json = $rutaEfs.'columnas_titulos_partidos_senador_2016_'.$id_municipio.'-'.$_COOKIE["id_usuario"].'.json';
		if (file_put_contents($archivo_json, $json_data)) {
			//echo "Los datos se han guardado en $archivo_json";
		} else {
			//echo "Error al guardar los datos en $archivo_json";
		}
	}
	
	foreach ($datos_secciones_ine as $id_seccion_ine => $datos) {
		foreach ($columnas_titulos_partidos  as $key => $value) {
			if($value['row'] == 'rentabilidad'){
				$colum[ $value['row'] ] = $datos['orden_votos_individual']['semaforo']['rentabilidad'] ;
			}elseif ($value['row'] == 'rentabilidad_posicion') {
				$colum[ $value['row'] ] = $datos['orden_votos_individual']['semaforo']['rentabilidad_orden'] ;
			}elseif ($value['row'] == 'tipo') {
				if($datos['tipo']==1){
					$colum[ $value['row'] ] = 'urbano';
				}else{
					$colum[ $value['row'] ] = 'rural';
				}
			}elseif ($value['row'] == 'competividad') {
				$colum[ $value['row'] ] = $datos['orden_votos_individual']['semaforo']['competitividad'] ;
			}elseif ($value['row'] == 'competividad_posicion') {
				$colum[ $value['row'] ] = $datos['orden_votos_individual']['semaforo']['competitividad_orden'] ;
			}elseif ($value['row'] == 'semaforo') {
				$colum[ $value['row'] ] = $datos['orden_votos_individual']['semaforo']['color'] ;
			}elseif ($value['row'] == 'primera_fuerza') {
				$colum[ $value['row'] ] = $datos['orden_votos_individual']['primera_fuerza'];
			}elseif ($value['row'] == 'votos_primera_fuerza') {
				$colum[ $value['row'] ] = $datos['orden_votos_individual']['partidos'][$datos['orden_votos_individual']['primera_fuerza']];
			}elseif ($value['row'] == 'segunda_fuerza') {
				$colum[ $value['row'] ] = $datos['orden_votos_individual']['segunda_fuerza'];
			}elseif ($value['row'] == 'votos_segunda_fuerza') {
				$colum[ $value['row'] ] = $datos['orden_votos_individual']['partidos'][$datos['orden_votos_individual']['segunda_fuerza']];
			}elseif ($value['row'] == 'sistema') {
				if($datos['orden_votos_individual']['sistema']==''){
					$colum[ $value['row'] ] = $searh_partido;
					//$colum[ $value['row'] ] = $datos['orden_votos_individual']['partidos'][$searh_partido];
				}else{
					$colum[ $value['row'] ] = $datos['orden_votos_individual']['sistema'];
					//$colum[ $value['row'] ] = $colum[ $value['row'] ] = $datos['orden_votos_individual']['partidos'][$datos['orden_votos_individual']['sistema']];
				}
			}elseif ($value['row'] == 'votos_sistema') {
				if($datos['orden_votos_individual']['votos_sistema']==''){
					//$colum[ $value['row'] ] = $searh_partido;
					$colum[ $value['row'] ] = $datos['orden_votos_individual']['partidos'][$searh_partido];
				}else{
					//$colum[ $value['row'] ] = $datos['orden_votos_individual']['sistema'];
					$colum[ $value['row'] ] = $colum[ $value['row'] ] = $datos['orden_votos_individual']['partidos'][$datos['orden_votos_individual']['sistema']];
				}
				
			}elseif ($datos[$value['row']]=='') {
				$colum[$value['row']] = $datos['orden_votos_individual']['partidos'][$value['row']];
			}else{
				$colum[$value['row']] = $datos[$value['row']];
			}
		}
		
		if($colum['municipio']!=''){
			//echo "<pre>";
			//print_r($colum);
			$columnas_datos[] = $colum;
			$secciones[] = $datos;
			//echo "</pre>";
		}
		
	}
	
	if(!empty($columnas_datos)){
		$rutaEfs = rutaEfs();
		$json_data = json_encode($columnas_datos);//Json donde se guarda la informacion para evitar las variables de sesion
		if ($json_data === false) {
			// Manejar el error de codificación JSON si es necesario
			//echo "Error al codificar el JSON";
		} else {
			// Guarda el JSON en un archivo
			$archivo_json = $rutaEfs.'columnas_datos_senador_2016_'.$id_municipio.'-'.$_COOKIE["id_usuario"].'.json';
			if (file_put_contents($archivo_json, $json_data)) {
				//echo "Los datos se han guardado en $archivo_json";
			} else {
				//echo "Error al guardar los datos en $archivo_json";
			}
		}
		$json_data = json_encode($secciones);//Json donde se guarda la informacion para evitar las variables de sesion
		if ($json_data === false) {
			// Manejar el error de codificación JSON si es necesario
			//echo "Error al codificar el JSON";
		} else {
			// Guarda el JSON en un archivo
			$archivo_json = $rutaEfs.'secciones_senador_2016_'.$id_municipio.'-'.$_COOKIE["id_usuario"].'.json';
			if (file_put_contents($archivo_json, $json_data)) {
				//echo "Los datos se han guardado en $archivo_json";
			} else {
				//echo "Error al guardar los datos en $archivo_json";
			}
		}
	}

	//! Seccion para buscar las giras
	if(!empty($id_secciones_ine_validos)){
		if(!empty($_POST['mapa'][0]['secciones_ine_giras']) || !empty($_POST['mapa'][0]['secciones_ine_actividades'])  ){
			include __DIR__.'/../../functions/timemex.php';
			include __DIR__."/../../functions/tools.php";
		}
		if(!empty($_POST['mapa'][0]['secciones_ine_giras'])){
			unset($search);
			include __DIR__."/../../functions/secciones_ine_giras.php";
			include __DIR__."/../../functions/secciones_ine_giras_puntos.php";
	
			$perido_fecha_inicial = $_COOKIE['fecha_inicial'];
			$perido_fecha_final = $_COOKIE['fecha_final'];
			$id_secciones_ine_validos;
			$id_secciones_ine_validosx = implode(",", $id_secciones_ine_validos);
			$search['id_seccion_ine'] = $id_secciones_ine_validosx;
			$search['fecha_1'] = $perido_fecha_inicial;
			$search['fecha_2'] = $perido_fecha_final;
			$search['tipo'] = "'".implode("','",explode(',',$_POST['mapa'][0]['secciones_ine_giras']))."'";
			$secciones_ine_girasDatosArray = secciones_ine_girasDatosArray($search);
			foreach ($secciones_ine_girasDatosArray as $key => $value) {
				$colores_array[$value['id']]= coloresRandom('hex');
				$id_seccion_ine_giras[] = $value['id'];
			}
			if(!empty($id_seccion_ine_giras)){
				$secciones_ine_giras_puntosDatosMapa = secciones_ine_giras_puntosDatosMapa('','',$id_seccion_ine_giras);
			}
		}else{
			//include __DIR__."/../../functions/tools.php";
		}
		if(!empty($_POST['mapa'][0]['secciones_ine_actividades'])){
			unset($search);
			include __DIR__."/../../functions/secciones_ine_actividades.php";
			include __DIR__."/../../functions/secciones_ine_actividades_puntos.php";
	
			$perido_fecha_inicial = $_COOKIE['fecha_inicial'];
			$perido_fecha_final = $_COOKIE['fecha_final'];
			$id_secciones_ine_validos;
			$id_secciones_ine_validosx = implode(",", $id_secciones_ine_validos);
			$search['id_seccion_ine'] = $id_secciones_ine_validosx;
			$search['fecha_1'] = $perido_fecha_inicial;
			$search['fecha_2'] = $perido_fecha_final;
			$search['tipo'] = "'".implode("','",explode(',',$_POST['mapa'][0]['secciones_ine_actividades']))."'";
			$secciones_ine_actividadesDatosArray = secciones_ine_actividadesDatosArray($search);
			foreach ($secciones_ine_actividadesDatosArray as $key => $value) {
				$colores_array[$value['id']]= coloresRandom('hex');
				$id_seccion_ine_actividades[] = $value['id'];
			}
			if(!empty($id_seccion_ine_actividades)){
				$secciones_ine_actividades_puntosDatosMapa = secciones_ine_actividades_puntosDatosMapa('','',$id_seccion_ine_actividades);
			}
		}
	}
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
		.info_seccion_ganador_2{
			width:70%;
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
			height:auto;
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
		.datos_left{
			width:30%;
			float:left;
			height:70px;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		.datos_right,.datos_right_bottom{
			width:70%;
			float:left;
			height:70px;
			text-align:left;
			border: 1px solid gray;
			padding: 4px 0px 4px 10px;
		}
		.datos_top{
			width:70%;
			float:left;
			height: auto;
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
				height:auto;
				margin: -10px 0px 0px 10px;
			}
			.info_titulo,.info_seccion_ganador_button{
				width:100%;
			}
			.info_seccion_ganador{
				width:100%;
			}
			.info_seccion_ganador_2{
				width:100%;
				text-align:center;
			}
			.datos_votos{
				width:100%;
				height: auto;
			}
			.datos_top,.datos_right,.datos_left{
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
	<style>

	</style>
	<script type="text/javascript">
		function myMap(){
			zoom=14;
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
			foreach ($datos_municipios as $key => $value) {
				$paths = "";
				foreach ($municipios_parametrosDatosMapa[$value['id']] as $keyT => $valueT) {
					$path = "municipios_".$key."_".$keyT;
					echo $path." = [";
					foreach ($valueT as $keyH => $valueH) {
						echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
					}
					echo "];";
					$paths .= $path.",";
				}
				if($value['partido_ganador_background']=="" || $key != $id_municipio ){
					$value['partido_ganador_border'] = "000000";
					$value['partido_ganador_background'] = "000000";
				}
				$value['partido_ganador_border'] = "000000";
				$value['partido_ganador_background'] = "000000";
				?>
				municipio_area<?= $key ?> = new google.maps.Polygon({
					paths: [<?= $paths ?>],
					strokeColor: "#<?= $value['partido_ganador_border'] ?>",
					strokeOpacity: 0.8,
					strokeWeight: 1,
					fillColor: "#<?= $value['partido_ganador_background'] ?>",
					fillOpacity: 0.35,
				});
				municipio_area<?= $key ?>.setMap(map);
				<?php
			}
			?>
			<?php
			foreach ($datos_secciones_ine as $key => $value){
				unset($coali_primera_fuerza);
				unset($coali_segunda_fuerza);
				unset($texto);

				if(!empty($value['orden_votos_individual']['semaforo']['color'])){
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
									<h5>Localidad(es): '.htmlspecialchars(str_replace('*_*', ' - ', $value['seccion_localidades']), ENT_QUOTES, 'UTF-8').'</h5>
									<h5>Colonia(s): '.htmlspecialchars(str_replace('*_*', ' - ', $value['seccion_colonias']), ENT_QUOTES, 'UTF-8').'</h5>
									<div class="info_titulo">
										<h5>Votación '.$ano.'</h5>
									</div>
									<div class="info_seccion_ganador">
										Lista Nominal: <b>'.number_format($value['lista_nominal'], 0, '.', ',').'</b><br>
										Partido Ganador: <b>'.$datos_primera_fuerza['nombre_corto'].'</b><br>
									</div>
									<div class="info_seccion_ganador_button">
										<div style="background-color:'.$color.';padding:5px;margin-top:2px;text-align:center;color:black">
											<b style="color:black">'.strtoupper($value['orden_votos_individual']['semaforo']['competitividad']).'</b>
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
									Prog. Gob:<b>'.number_format($value['apoyos_programas'], 0, '.', ',').'</b><br>
									Prog. Inv:<b>'.number_format($value['acciones_obras'], 0, '.', ',').'</b><br>
									Ciudadanos:<b>'.number_format($value['ciudadanos_registrados'], 0, '.', ',').'</b><br>
									Funcionarios:<b>'.number_format($value['funcionarios'], 0, '.', ',').'</b><br>
									Grupo Interes:<b>'.number_format($value['grupos_interes'], 0, '.', ',').'</b><br>
								</div>
								<div class="datos">
									Militantes:<b>'.number_format($value['militantes'], 0, '.', ',').'</b><br>
									Juntas:<b>'.number_format($value['juntas'], 0, '.', ',').'</b><br>
									Visitas:<b>'.number_format($value['visitas'], 0, '.', ',').'</b><br>
									Caminatas:<b>'.number_format($value['caminatas'], 0, '.', ',').'</b><br><br>
								</div>';
								//if(revocacion_mandato_2022)
								if(!empty($value['revocacion_mandato'])){
									$participacion_ciudadana = ($value['consulta_rvm_votos_totales'] / $value['consulta_rvm_lista_nominal']) * 100;
									$porcenjate_siga = $value['revocacion_mandato']['orden_preguntas']['SIGA'] / $value['consulta_rvm_votos_totales'] * 100;
									$porcenjate_no_siga = $value['revocacion_mandato']['orden_preguntas']['NO_SIGA'] / $value['consulta_rvm_votos_totales'] * 100;
									$div .= '
										<div class="datos_consulta" style=" background: rgba(190, 195, 201, 0.9) "> 
											<b>Consulta 2022 Revocación Mandato</b>
											<br>
											Lista Nominal: <b>'.number_format($value['consulta_rvm_lista_nominal'], 0, '.', ',').'</b>
											<br>
											Casillas: <b>'.number_format($value['consulta_rvm_casillas'], 0, '.', ',').'</b>
											<br>
											P. Ciudadana: <b> '.number_format($participacion_ciudadana, 2, '.', '').' %</b>
										</div>
										<div class="datos_consulta" style=" background: rgba(190, 195, 201, 0.9) "> 
											Votos Siga: <b>'.number_format($value['revocacion_mandato']['preguntas']['SIGA']['votos'], 0, '.', ',').' ('.number_format($porcenjate_siga, 2, '.', ',').'%)</b>
											<br>
											Votos No Siga: <b>'.number_format($value['revocacion_mandato']['preguntas']['NO_SIGA']['votos'], 0, '.', ',').' ('.number_format($porcenjate_no_siga, 2, '.', ',').'%)</b>
											<br>
											Votos Nulos: <b>'.number_format($value['consulta_rvm_votos_nulos'], 0, '.', ',').'</b>
											<br>
											Votos Totales: <b>'.number_format($value['consulta_rvm_votos_totales'], 0, '.', ',').'</b>
										</div>';
								}
					$div .= '</div>';
					$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);	
					$paths = "";
					foreach ($secciones_ine_parametrosDatosMapa[$value['id']] as $keyT => $valueT) {
						$path = "secciones_ine_".$value['id']."_".$keyT;
						echo $path." = [";
						foreach ($valueT as $keyH => $valueH) {
							echo "{ lat: ".$valueH['latitud'].", lng: ".$valueH['longitud']." },";
						}
						echo "];";
						$paths .= $path.",";
					}
					$semaforo_color = $value['orden_votos_individual']['semaforo']['color'];
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
					secciones_area<?= $value['id'] ?> = new google.maps.Polygon({
						paths: [<?= $paths ?>],
						strokeColor: "#<?= $color_border ?>",
						strokeOpacity: 0.8,
						strokeWeight: 1,
						fillColor: "#<?= $color_background ?>",
						fillOpacity: 0.2,
						zIndex:2,
					});
					//secciones_area<?= $value['id'] ?>.setMap(map);
					secciones_area<?=  $value['id'] ?>.addListener("click", (function(event){
						myLatlng = new google.maps.LatLng("<?= $value['latitud'] ?>","<?= $value['longitud'] ?>"); 
						infoWindow.setContent('<?= $div ?>');
						infoWindow.setPosition(myLatlng);
						infoWindow.open(map);
					}));
					infoWindow = new google.maps.InfoWindow();

					const label<?=  $value['id'] ?> = new google.maps.Marker({
						label: {
							text: '<?= $value['numero'] ?>',
							color: 'white',
							fontSize: '15px'
						},
						icon: {
							url: '',
							size: new google.maps.Size(10, 10),
							anchor: new google.maps.Point(0, 0),
							labelOrigin: new google.maps.Point(0, 0),
							scaledSize: new google.maps.Size(100, 30)
						},
						position: {lat: <?= $value['latitud'] ?>, lng: <?= $value['longitud'] ?>},
						map: null,  // Inicialmente el label no se muestra en el mapa
					});
					<?php
				}
			}
			?>
			<?php
			foreach ($secciones_ine_giras_puntosDatosMapa as $keyId => $valuePuntoData) {
				$color = $colores_array[$keyId];
				echo 'const flightPlanCoordinates_'.$keyId.' = [';
				foreach ($valuePuntoData as $key => $value) {
					echo '{ lat: '.$value['latitud'].', lng: '.$value['longitud'].' },';
				}
				echo '];';
				echo 'const rutasGiras_'.$keyId.' = new google.maps.Polyline({
					path: flightPlanCoordinates_'.$keyId.',
					geodesic: true,
					strokeColor: "#'.$color.'",
					//*strokeColor: "#"+(Math.random() * 0xFFFFFF << 0).toString(16).padStart(6, "0"),
					strokeOpacity: 0.8,
					strokeWeight: 5,
					fillColor: "#FF0000",
					fillOpacity: 0.35,
					zIndex:10000,
				});';
				echo 'rutasGiras_'.$keyId.'.setMap(map);';
			}
			foreach ($secciones_ine_actividades_puntosDatosMapa as $keyId => $valuePuntoData) {
				$color = $colores_array[$keyId];
				echo 'const flightPlanCoordinatesAc_'.$keyId.' = [';
				foreach ($valuePuntoData as $key => $value) {
					echo '{ lat: '.$value['latitud'].', lng: '.$value['longitud'].' },';
				}
				echo '];';
				echo 'const rutasActividades_'.$keyId.' = new google.maps.Polyline({
					path: flightPlanCoordinatesAc_'.$keyId.',
					geodesic: true,
					strokeColor: "#'.$color.'",
					//*strokeColor: "#"+(Math.random() * 0xFFFFFF << 0).toString(16).padStart(6, "0"),
					strokeOpacity: 0.8,
					strokeWeight: 5,
					fillColor: "#FF0000",
					fillOpacity: 0.35,
					zIndex:10000,
				});';
				echo 'rutasActividades_'.$keyId.'.setMap(map);';
			}
			?>



			///marcadores o puntos
			var marcadores = [
			<?php
			foreach ($datos_municipios as $key => $value) {
				if($value['id'] != $id_municipio){
					echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'municipio','municipio.png' ],";
				}
			}
			foreach ($secciones_ine_girasDatosArray as $key => $value) {
				echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'gira','".$value['tipo']."'],";
			}
			foreach ($secciones_ine_actividadesDatosArray as $key => $value) {
				echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'p_inversion','".$value['tipo']."'],";
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
						$diferencias_votos = $value['partido_ganador_votos'] - $value['partido_sistema_votos'];
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
										<h4>Municipio: '.$value['municipio'].'</h4>
										<div class="info_titulo" style="width:100%">
											<h5>Votación '.$ano.'</h5>
										</div>
										<div class="info_seccion_ganador_button" style="width:100%">
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
				?>
				<?php
				foreach ($secciones_ine_girasDatosArray as $key => $value){
					foreach ($value as $keyT => $valueT) {
						if($keyT!='monto_total'){
							//$value[$keyT] = preg_replace('([^A-Za-z0-9 :-])', '', $valueT);
						}
					}
					if($value['superindice'] != ''){
						$sup_indice=$value['unidad'].'<sup>'.$value['superindice'].'</sup>';
					}else{
						$sup_indice=$value['unidad'];
					}
					$div = '<div class="divMapa">
									<div class="info_content">
										<!--<img src="../admin/ftpFiles/files/render.jpg" alt="Image Description" style="width:100%; height:auto;">-->
										<h4>Clave: '.$value['clave'].'</h4>
										<div class="info_titulo">
											<h5>Tipo:</h5>
										</div>
										<div class="info_seccion_ganador_2">
											<h5>'.strtoupper($value['tipo']).'</h5>
										</div>
									</div>
									<div class="datos_top" style="width:100%;">
										Folio: <b>'.$value['folio'].'</b><br>
										Nombre: <b><font style="">'.$value['nombre'].'</font></b><br>
										
									</div>
									<div class="datos_left">
										<p>
											Distrito Local: <b>'.$value['distrito_local'].'</b><br>
											Distrito Federal: <b>'.$value['distrito_federal'].'</b><br>
											Sección: <b>'.$value['seccion'].'</b><br>
										</p>
									</div>
									<div class="datos_right">
										<p>
											Fecha: <b>'.$value['feha'].'|'.fechaNormalSimpleWDDMMAA_ES($value['fecha']).'</b><br>
											Hora: <b>'.$value['hora'].'<br>

											
										</p>
									</div>
									<div class="datos_right_bottom" style="width:100%;">
										Dirección : <b>'.$value['calle'].", ".$value['colonia'].", ".$value['codigo_postal'].", ".$value['municipio'].', '.$estado_nombre.' </b><br>
									</div>
								</div>';
					$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
					?>
						['<?= $div ?>'],
					<?php
				}
				?>
				<?php
				foreach ($secciones_ine_actividadesDatosArray as $key => $value){
					foreach ($value as $keyT => $valueT) {
						if($keyT!='monto_total'){
							//$value[$keyT] = preg_replace('([^A-Za-z0-9 :-])', '', $valueT);
						}
					}
					if($value['superindice'] != ''){
						$sup_indice=$value['unidad'].'<sup>'.$value['superindice'].'</sup>';
					}else{
						$sup_indice=$value['unidad'];
					}
					$div = '<div class="divMapa">
									<div class="info_content">
										<!--<img src="../admin/ftpFiles/files/render.jpg" alt="Image Description" style="width:100%; height:auto;">-->
										<h4>Clave: '.$value['clave'].'</h4>
										<div class="info_titulo">
											<h5>Tipo:</h5>
										</div>
										<div class="info_seccion_ganador">
											<h5>'.strtoupper($value['tipo']).'</h5>
										</div>
										<div class="info_seccion_ganador_button">
											<button class="button button4" onclick="edit('.$value['id'].')">Ver Más</button>
										</div>
									</div>
									<div class="datos_top" style="width:100%;">
										Folio: <b>'.$value['folio'].'</b><br>
										Número de contrato: <b>'.$value['numero_contrato'].'</b><br>
										Cédula: <b>'.$value['cedula'].'</b><br>
										Empresa Adjudicada: <b>'.$value['empresa_adjudicada'].'</b><br>
										Supervisor: <b>'.$value['supervisor'].'</b><br>
										Tipo Infraestructura: <b>'.$value['tipo_infraestructura'].'</b><br>
										Beneficiarios: <b>'.number_format($value['beneficiarios'],0,"",",").'</b><br>
										Monto Total: <b>'.number_format($value['monto_total'],2,".",",").'</b><br>
										Meta: <b>'.number_format($value['meta_cantidad'],0,"",",").' '.$sup_indice.'</b><br>
										Nombre: <b><font style="">'.$value['nombre'].'</font></b><br>
										
									</div>
									<div class="datos_left">
										<p>
											Distrito Local: <b>'.$value['distrito_local'].'</b><br>
											Distrito Federal: <b>'.$value['distrito_federal'].'</b><br>
											Sección: <b>'.$value['seccion'].'</b><br>
										</p>
									</div>
									<div class="datos_right">
										<p>
											Fecha Inicio: <b>'.$value['fecha_inicio'].'|'.fechaNormalSimpleWDDMMAA_ES($value['fecha_inicio']).'</b><br>
											Fecha Final: <b>'.$value['fecha_final'].'|'.fechaNormalSimpleWDDMMAA_ES($value['fecha_final']).'</b><br>

											
										</p>
									</div>
									<div class="datos_right_bottom" style="width:100%;">
										Dirección : <b>'.$value['calle'].", ".$value['colonia'].", ".$value['codigo_postal'].", ".$value['municipio'].', '.$estado_nombre.' </b><br>
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
			var markers = [];
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
					} else if(marcadores[i][3]=='municipio'){
						var icon = {
							//url: 'assets/images/iconos/cd-icon-location.png', // url
							url : 'images/puntos/'+ marcadores[i][4],
							scaledSize: new google.maps.Size(22, 22), // scaled size
						};
					}else if(marcadores[i][3]=='gira'){
						if(marcadores[i][4] == 'visita'){
							var icon = {
								//url: 'assets/images/iconos/cd-icon-location.png', // url
								url : 'images/iconos_partidos/puntero_visita.png',
								scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
							};
						}
						if(marcadores[i][4] == 'caminata'){
							var icon = {
								//url: 'assets/images/iconos/cd-icon-location.png', // url
								url : 'images/iconos_partidos/puntero_caminata.png',
								scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
							};
						}
						if(marcadores[i][4] == 'junta'){
							var icon = {
								//url: 'assets/images/iconos/cd-icon-location.png', // url
								url : 'images/iconos_partidos/puntero_junta.png',
								scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
							};
						}
					}else if(marcadores[i][3]=='p_inversion'){
						if(marcadores[i][4] == 'apoyo'){
							var icon = {
								//url: 'assets/images/iconos/cd-icon-location.png', // url
								url : 'images/iconos_partidos/puntero_apoyo.png',
								scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
							};
						}
						if(marcadores[i][4] == 'obra'){
							var icon = {
								//url: 'assets/images/iconos/cd-icon-location.png', // url
								url : 'images/iconos_partidos/puntero_obra.png',
								scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
							};
						}
						if(marcadores[i][4] == 'accion'){
							var icon = {
								//url: 'assets/images/iconos/cd-icon-location.png', // url
								url : 'images/iconos_partidos/puntero_accion.png',
								scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
							};
						}
					}else{
						var icon = {
							url: 'assets/images/iconos/cd-icon-location.png', // url
							//url : 'images/iconos_partidos/'+ marcadores[i][3],
							scaledSize: new google.maps.Size(20, 22), // scaled size
						};
					}
				}
				marker = new google.maps.Marker({
					position: new google.maps.LatLng(marcadores[i][1], marcadores[i][2]),
					map: map,
					icon: icon,
					visible: false 
				});
				markers.push(marker);
				google.maps.event.addListener(marker, 'click', (function(marker, i) {
					return function() {
						infowindow.setContent(infoWindowContent[i][0]);
						infowindow.open(map, marker);
					}
				})(marker, i));
			}
			// Agregar un listener para detectar cambios en el mapa
			google.maps.event.addListener(map, 'idle', function() {
				// Obtener los límites del mapa
				var bounds = map.getBounds();
				var zoom = map.getZoom();
				for (var i = 0; i < markers.length; i++) {
					if (bounds.contains(markers[i].getPosition())) {
						markers[i].setVisible(true);
					} else {
						markers[i].setVisible(false);
					}
				}
				<?php
					foreach ($datos_secciones_ine as $key => $value) {
						if(!empty($value['orden_votos_individual']['semaforo']['color'])){
							?>
							var vertices = secciones_area<?= $key ?>.getPath().getArray();
							var visible = false;
							for (var i = 0; i < vertices.length; i++) {
								if (bounds.contains(vertices[i])) {
									// Si todos los vértices están dentro de los límites, mostrar el polígono
									var visible = true;
								}
							}
							if(visible){
								secciones_area<?= $key ?>.setMap(map);
							}else{
								//secciones_area<?= $key ?>.setMap(null);
							}
							<?php
						}
					}
				?>
				<?php
					foreach ($datos_secciones_ine as $key => $value) {
						if(!empty($value['orden_votos_individual']['semaforo']['color'])){
							?>
							// Verificar si los marcadores están dentro de los límites del mapa
							if (bounds.contains(label<?= $key ?>.getPosition())) {
								//console.log(map.getZoom())
								if (map.getZoom() >= 13) {
									label<?= $key ?>.setMap(map);
								}else{
									//label<?= $key ?>.setMap(null);
								}
							} else {
								//label<?= $key ?>.setMap(null);
							}
							<?php
						}
					}
				?>
			});
		}
		function getCoordsLimites(marker){ 
			//var latitud=document.getElementById("latitud").value=marker.getPosition().lat();
			// var longitud=document.getElementById("longitud").value=marker.getPosition().lng(); 
		}
	</script> 
	<div id="mapa" style="width:100%;height:410px;"></div>
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBBrai7GSb0T1XxAG4yOFZwnWOcHXCzNaI&callback=myMap"></script>  
	