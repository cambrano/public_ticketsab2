<?php
	include __DIR__.'/../../functions/security.php';
	@session_start();
	
	if(!empty($_POST)){
		include '../../functions/elecciones.php'; 
		include __DIR__."/../../functions/distritos_locales_parametros.php"; 
		include __DIR__."/../../functions/distritos_locales.php"; 
		include __DIR__."/../../functions/secciones_ine_parametros.php";
		include __DIR__."/../../functions/configuracion_matriz_rentabilidad_secciones_ine_2024.php";
		include __DIR__."/../../functions/efs.php";

		include __DIR__.'/../../functions/timemex.php';
		include __DIR__."/../../functions/tools.php";

		$elecciones = eleccionesModulo('2024');
		function truncar($numero, $digitos){
			$truncar = 10**$digitos;
			return intval($numero * $truncar) / $truncar;
		}
		$id_distrito_local = $_POST['mapa'][0]['id_distrito_local'];
	}
	//var_dump($_POST);
	// Mostrar errores excepto warnings
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE);
	//echo "<pre>",
	//var_dump($_POST['mapa']);
	//echo "</pre>";
	$tipo = 1;
	$ano = $elecciones['distritos_locales'];

	if($_POST['mapa'][0]['tipo_semaforo']==2){
		$tipo_color_poligono = 'coa';
	}else{
		$tipo_color_poligono = 'ind';
	}

	
	//$tipo_color_poligono = 'ind';
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
		$sql_agendas_gobierno = " AND siagl.fecha BETWEEN '{$perido_fecha_inicial}' AND '{$perido_fecha_final}' ";
	}elseif($perido_fecha_inicial != '' && $perido_fecha_final == ''){
		$sqlCiudadanosRegistrados = " AND DATE(sic.fechaR) <= '{$perido_fecha_inicial}' ";
		$sqlApoyosProgramas =  " AND sicpa.fecha <= '{$perido_fecha_inicial}' ";
		$sqlAccionesObras = " AND sia.fecha_inicio <= '{$perido_fecha_inicial}' ";
		$sqlMilitantes = " AND mp.fecha <= '{$perido_fecha_inicial}' ";
		$sqlFuncionarios = " AND sicc.fecha <= '{$perido_fecha_inicial}' ";
		$sql_giras = " AND sig.fecha <= '{$perido_fecha_inicial}' ";
		$sql_agendas_gobierno = " AND siagl.fecha <= '{$perido_fecha_inicial}' ";
	}elseif($perido_fecha_inicial == '' && $perido_fecha_final != ''){
		$sqlCiudadanosRegistrados = " AND DATE(sic.fechaR) >= '{$perido_fecha_final}' ";
		$sqlApoyosProgramas =  " AND sicpa.fecha >= '{$perido_fecha_final}' ";
		$sqlAccionesObras = " AND sia.fecha_inicio >= '{$perido_fecha_final}' ";
		$sqlMilitantes = " AND mp.fecha >= '{$perido_fecha_final}' ";
		$sqlFuncionarios = " AND sicc.fecha >= '{$perido_fecha_final}' ";
		$sql_giras = " AND sig.fecha >= '{$perido_fecha_final}' ";
		$sql_agendas_gobierno = " AND siagl.fecha >= '{$perido_fecha_final}' ";
	}else{
		$sqlCiudadanosRegistrados = "";
		$sqlApoyosProgramas =  "";
		$sqlAccionesObras = "";
		$sqlMilitantes = "";
		$sqlFuncionarios = "";
		$sql_giras = "";
		$sql_agendas_gobierno = "";
	}

	//var_dump($_POST);
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
	$sql = "
		SELECT 
			SUM(votos_nulos) suma_votos_nulos,
			SUM(votos_can_nreg) suma_votos_can_nreg,
			SUM(lista_nominal) suma_lista_nominal,
			(SELECT SUM(cvp2024.votos) FROM casillas_votos_partidos_2024 cvp2024 WHERE cvp2024.id_distrito_local = {$id_distrito_local}  AND cvp2024.tipo={$tipo} ) suma_votos_validos
		FROM casillas_votos_2024 cv2024
		WHERE cv2024.id_distrito_local = {$id_distrito_local} AND cv2024.tipo={$tipo};
	";
	
	$resultado = $conexion->query($sql);
	$row=$resultado->fetch_assoc();
	$row['suma_votos_totales'] = $row['suma_votos_nulos'] + $row['suma_votos_can_nreg'] + $row['suma_votos_validos'];
	$totales=$row; 

	//!obtenemos la informacion de los partidos en solitario
	$sql = "
		SELECT 
			p2024.id,
			p2024.clave,
			p2024.nombre_corto,
			p2024.nombre,
			p2024.logo,
			p2024.icono,
			p2024.color_border,
			p2024.color_background,
			p2024.principal,
			p2024.clave_partidos_coaliciones
		FROM partidos_2024 p2024
		WHERE p2024.tipo={$tipo};
	";
	$result = $conexion->query($sql); 
	while ($row = $result->fetch_assoc()) {
		if($row['clave_partidos_coaliciones'] ==""){
			$partidos_individuales[$row['clave']] = $row;
		}else{
			$partidos_coaliciones[$row['clave']] = $row;
		}
	}
	
		// Arrays iniciales
	// Combinar claves relacionadas
	$grupos = [];
	foreach ($partidos_coaliciones as $coalicion) {
		$keys = explode(',', $coalicion['clave_partidos_coaliciones']);
		$grupos[] = $keys;
	}

	// Agrupar coaliciones relacionadas
	function unirGrupos($grupos)
	{
		$result = [];
		foreach ($grupos as $grupo) {
			$added = false;
			foreach ($result as &$res) {
				if (array_intersect($res, $grupo)) {
					$res = array_unique(array_merge($res, $grupo));
					$added = true;
					break;
				}
			}
			if (!$added) {
				$result[] = $grupo;
			}
		}
		return $result;
	}

	$gruposUnidos = unirGrupos($grupos);

	// Asignar identificadores únicos
	$contador = 'a';
	$coalicionesConGrupo_solo = [];
	$identificadoresFinales = [];

	// Asignar grupos para las coaliciones unidas
	foreach ($gruposUnidos as $grupo) {
		$identificador = "coalicion_$contador";
		foreach ($grupo as $clave) {
			if (isset($partidos_individuales[$clave])) {
				$partidos_individuales[$clave]['grupos'] = $identificador;
			}
		}
		$coalicionesConGrupo_solo[$identificador] = $grupo;
		$contador++;
	}

	// Asignar grupos únicos a los que no estén en ningún grupo
	foreach ($partidos_individuales as $clave => $partido) {
		if (empty($partido['grupos'])) {
			$identificador = "coalicion_$contador";
			$partidos_individuales[$clave]['grupos'] = $identificador;
			$coalicionesConGrupo_solo[$identificador] = [$clave];
			$contador++;
		}
	}

	foreach ($coalicionesConGrupo_solo as $key => $value) {
		$coalicion_datos_ = array();
		foreach ($value as $keyT => $valueT) {
			//key del coa_a
			//valueT partidos
			$coalicion_datos[$key][$valueT] = 1;
			
			foreach ($partidos_coaliciones as $coalicion) {
				$keys = explode(',', $coalicion['clave_partidos_coaliciones']);
				if (in_array($valueT, $keys)) {
					//$coalicion_datos[$key][$coalicion['clave_partidos_coaliciones']] = $coalicion['clave_partidos_coaliciones'];
					$coalicion_datos_[$coalicion['clave_partidos_coaliciones']] = 2;
				}
			}
			
		}
		foreach ($coalicion_datos_ as $keyH => $valueH) {
			$coalicion_datos[$key][$keyH] = 2;
		}
	}
	foreach ($coalicion_datos as $key => $value) {
		foreach ($value as $keyT => $valueT) {
			$coalicion_partidos_datos[$keyT] = $key;
		}
	}

	//echo "<pre>";
	//var_dump($coalicion_datos);
	//echo "</pre>";
	//! coalicion_datos con este esta el array de los coalcion tomando el key como la coalicion
	//! coalicion_partidos_datos es el facil con la clave y el resultado es el grupo

	//! agregamos a cada partido en indivudual sus coalicion si tiene 
	foreach ($partidos_individuales as $clave => $datos_ind) {
		foreach ($partidos_coaliciones as $clave_coa => $datos_coa) {
			$claves_coa_array = explode(",", $clave_coa);
			if (in_array($clave, $claves_coa_array)) {
				foreach ($claves_coa_array as $key => $value) {
					$partidos_individuales[$clave]['coaliciones_individuales'][$value]['id'] = $partidos_individuales[$value]['id'] ;
					$partidos_individuales[$clave]['coaliciones_individuales'][$value]['nombre_corto'] = $partidos_individuales[$value]['nombre_corto'] ;
				}
				$partidos_individuales[$clave]['coaliciones_grupales'][$clave_coa]['id'] = $partidos_coaliciones[$clave_coa]['id'] ;
				$partidos_individuales[$clave]['coaliciones_grupales'][$clave_coa]['nombre_corto'] = $partidos_coaliciones[$clave_coa]['nombre_corto'] ;
			}
		}
	}

	//!obtenemos los partidos del 2024 segun el tipo
	$sql = "
		SELECT 
			p2024.id AS id_partido_2024,
			p2024.clave,
			p2024.nombre_corto,
			p2024.nombre,
			p2024.logo,
			p2024.icono,
			p2024.color_border,
			p2024.color_background,
			p2024.principal,
			p2024.clave_partidos_coaliciones,
			cvp2024.id_seccion_ine,
			cvp2024.clave_seccion_ine,
			cvp2024.votos,
			cvp2024.id_distrito_local,
			cvp2024.clave_distrito_local,
			cvp2024.id_distrito_federal,
			cvp2024.clave_distrito_federal
		FROM partidos_2024 p2024
		LEFT JOIN casillas_votos_partidos_2024 cvp2024
		ON p2024.id = cvp2024.id_partido_2024
		WHERE cvp2024.id_distrito_local = {$id_distrito_local} AND p2024.tipo={$tipo} AND cvp2024.tipo = {$tipo};
	";
	$result = $conexion->query($sql); 
	while ($row = $result->fetch_assoc()) {
		if ($row['principal'] == 1) {
			$principal_clave = $row['clave'];
			$principal_background = "#".$row['color_background'];
		}
	
		$is_individual = $row['clave_partidos_coaliciones'] === "";
		$target_array = $is_individual ? 'partidos_2024_individual_array' : 'partidos_2024_coalicion_array';
		$target_totals = $is_individual ? 'partidos_2024_individual_totales' : 'partidos_2024_coalicion_totales';
		$key_clave = $is_individual ? $row['clave'] : $row['clave_partidos_coaliciones'];
	
		// Almacenar datos en el arreglo correspondiente
		${$target_array}[$row['id_seccion_ine']][$key_clave][] = $row;
	
		// Acumular los votos totales
		if (!isset(${$target_totals}[$row['id_seccion_ine']][$key_clave]['votos_totales'])) {
			${$target_totals}[$row['id_seccion_ine']][$key_clave]['votos_totales'] = 0;
		}
		${$target_totals}[$row['id_seccion_ine']][$key_clave]['votos_totales'] += $row['votos'];
	}
	

	// Procesar datos para partidos individuales
	foreach ($partidos_2024_individual_array as $id_seccion => $partidos) {
		foreach ($partidos as $clave => $rows) {
			$total_votos = $partidos_2024_individual_totales[$id_seccion][$clave]['votos_totales'];
			foreach ($rows as $row) {
				$row['votos'] = $total_votos;
				$row['coaliciones_individuales'] = $partidos_individuales[$clave]['coaliciones_individuales'];
				$row['coaliciones_grupales'] = $partidos_individuales[$clave]['coaliciones_grupales'];

				$partidos_2024_individual[$id_seccion][$clave] = $row;
				$partidos_2024_individual_base[$id_seccion][$clave] = $row;
			}
		}
	}
	
	// Procesar datos para coaliciones
	foreach ($partidos_2024_coalicion_array as $id_seccion => $coaliciones) {
		foreach ($coaliciones as $clave => $rows) {
			$total_votos = $partidos_2024_coalicion_totales[$id_seccion][$clave]['votos_totales'];
			foreach ($rows as $row) {
				$row['votos'] = $total_votos;
				$partidos_2024_coalicion[$id_seccion][$clave] = $row;
				$partidos_2024_coalicion_base[$id_seccion][$clave] = $row;
			}
		}
	}

	//! Agregamos los votos de los indivuals y de los caliciones para tener todas las combinaciones
	
	foreach ($partidos_2024_individual[$id_seccion] as $key => $value) {
		$partidos_2024_individual[$id_seccion][$key]['coaliciones_individuales'];
		foreach ($value['coaliciones_individuales'] as $keyT => $valueT) {
			//echo $keyT;
			unset($partidos_2024_individual_base[$id_seccion][$keyT]['coaliciones_individuales']);
			unset($partidos_2024_individual_base[$id_seccion][$keyT]['coaliciones_grupales']);
			$partidos_2024_individual[$id_seccion][$key]['coaliciones_individuales'][$keyT]=$partidos_2024_individual_base[$id_seccion][$keyT];
		}

		foreach ($value['coaliciones_grupales'] as $keyT => $valueT) {
			//echo $keyT;
			unset($partidos_2024_individual_base[$id_seccion][$keyT]['coaliciones_individuales']);
			unset($partidos_2024_individual_base[$id_seccion][$keyT]['coaliciones_grupales']);
			$partidos_2024_individual[$id_seccion][$key]['coaliciones_grupales'][$keyT]=$partidos_2024_coalicion_base[$id_seccion][$keyT];
		}
	}


	$_POST['searchTable'][0]['id_distrito_local']=$id_distrito_local;
	$distritos_locales_parametrosDatosMapa = distritos_locales_parametrosDatosMapa();
	$sql="
		SELECT
			main.id,
			main.clave,
			main.numero,
			main.latitud,
			main.longitud
		FROM distritos_locales main
		WHERE 1 
	";
	if($id_distrito_local !=''){
		#$sql .= " AND main.id = {$id_distrito_local} ";
	}
	$result = $conexion->query($sql); 
	$num=0; 
	while($row=$result->fetch_assoc()){
		
		$datos_distritos_locales[$row['id']]=$row;
		//$datos_distritos_locales[$row['id']]['poligonos']=$distritos_locales_parametrosDatosMapa[$row['id']];
		$num=$num+1;
	}
	$secciones_ine_parametrosDatosMapa = secciones_ine_parametrosDatosMapa('','','',$id_distrito_local,'','','');
	
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
			(SELECT COUNT(cv.id) FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') casillas,
			(SELECT SUM(cv.lista_nominal) FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}' ) lista_nominal,
			(SELECT SUM(cv.votos_nulos) FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_nulos,
			(SELECT SUM(cv.votos_can_nreg) FROM casillas_votos_2024 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_can_nreg,
			(SELECT SUM(cv.votos) FROM casillas_votos_partidos_2024 cv WHERE cv.id_seccion_ine = si.id AND cv.tipo='{$tipo}') votos_validos,

			(SELECT COUNT(sic.id) FROM secciones_ine_ciudadanos sic WHERE sic.id_distrito_local={$id_distrito_local} AND sic.id_seccion_ine = si.id {$sqlCiudadanosRegistrados} ) ciudadanos_registrados,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sicpa.id_seccion_ine_ciudadano = sic.id WHERE sic.id_distrito_local={$id_distrito_local} AND sic.id_seccion_ine = si.id {$sqlApoyosProgramas} ) apoyos_programas,
			(SELECT COUNT(*) FROM secciones_ine_actividades sia WHERE sia.id_seccion_ine = si.id {$sqlAccionesObras} ) acciones_obras,
			(SELECT COUNT(*) FROM secciones_ine_grupos sig WHERE sig.id_seccion_ine = si.id ) grupos_interes,
			(SELECT COUNT(*) FROM militantes_partidos mp LEFT JOIN secciones_ine_ciudadanos sic ON mp.id_seccion_ine_ciudadano = sic.id WHERE sic.id_distrito_local={$id_distrito_local} AND mp.id_partido_legado = '{$id_partido_legado}' AND sic.id_seccion_ine = si.id {$sqlMilitantes}  ) militantes,
			(SELECT COUNT(*) FROM secciones_ine_ciudadanos_categorias sicc WHERE sicc.id_distrito_local={$id_distrito_local} AND sicc.id_tipo_categoria_ciudadano = '{$id_tipo_categoria_ciudadano}' AND sicc.id_seccion_ine = si.id  {$sqlFuncionarios} ) funcionarios,

			(SELECT COUNT(*) FROM secciones_ine_giras sig WHERE sig.id_seccion_ine = si.id AND sig.tipo = 'junta' {$sql_giras}  ) juntas,
			(SELECT COUNT(*) FROM secciones_ine_giras sig WHERE sig.id_seccion_ine = si.id AND sig.tipo = 'visita' {$sql_giras}  ) visitas,
			(SELECT COUNT(*) FROM secciones_ine_giras sig WHERE sig.id_seccion_ine = si.id AND sig.tipo = 'caminata' {$sql_giras}  ) caminatas,

			(SELECT COUNT(*) FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE  siagl.id_seccion_ine=si.id {$sql_agendas_gobierno} ) eventos_agenda_gobierno,

			(SELECT SUM(cvrm2019.lista_nominal) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id ) consulta_rvm_lista_nominal,
			(SELECT COUNT(*) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_casillas,
			(SELECT SUM(cvrm2019.votos_nulos) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_votos_nulos,
			(SELECT SUM(cvrm2019.votos_can_nreg) FROM casillas_votos_2022_revocacion_mandato cvrm2019 WHERE cvrm2019.id_seccion_ine = si.id) consulta_rvm_votos_can_nreg,
			(SELECT SUM(cpvrm2019.votos) FROM casillas_preguntas_2022_revocacion_mandato cpvrm2019 WHERE cpvrm2019.id_seccion_ine = si.id) consulta_rvm_votos_validos,

			(SELECT COUNT(*) FROM manzanas_ine mi WHERE mi.id_seccion_ine = si.id ) manzanas
		FROM secciones_ine si
		WHERE si.id_distrito_local = '$id_distrito_local'
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
	$sql.= ' ORDER BY si.numero ASC ';
	$result = $conexion->query($sql); 
	//echo $principal_clave;
	//echo "<br>";
	$num = 0;
	while($row=$result->fetch_assoc()){
		if($num==0){
			$latitud_sugerido = $row['latitud'];
			$longitud_sugerido = $row['longitud'];
			$num ++;
		}
		if($row['tipo']==1){
			$row['seccion_tipo'] = 'Urbana';
		}else{
			$row['seccion_tipo'] = 'Rural';
		}
		//!votos totales
		$row['votos_totales'] = $row['votos_nulos'] + $row['votos_can_nreg'] + $row['votos_validos'];
		$row['votos_totales_suma'] = $totales['suma_votos_totales'];
		if ($totales['suma_votos_totales'] != 0) {
			$row['votos_totales_porcentaje'] = number_format(($row['votos_totales'] / $totales['suma_votos_totales']) * 100, 2, '.', '');
		} else {
			$row['votos_totales_porcentaje'] = 0.00; ; // O asignar otro valor predeterminado.
		}
		
		$row['lista_nominal_suma'] = $totales['suma_lista_nominal'];
		// Evitar división por cero en lista_nominal_porcentaje
		if ($totales['suma_lista_nominal'] != 0) {
			$row['lista_nominal_porcentaje'] = number_format(($row['lista_nominal'] / $totales['suma_lista_nominal']) * 100, 2, '.', '');
		} else {
			$row['lista_nominal_porcentaje'] = 0.00; ; // O asignar un valor alternativo
		}

		// Evitar división por cero en participacion
		if ($row['lista_nominal'] != 0) {
			$row['participacion_ciudadana'] = number_format(($row['votos_totales'] / $row['lista_nominal']) * 100, 2, '.', '');
		} else {
			$row['participacion_ciudadana'] = 0.00; ; // O asignar un valor alternativo
		}
		$row['gps'] = $row['latitud'].",".$row['longitud'];
		$coalicion_principal = array();
		$coalicion_principal_partidos = array();

		$tipo_analisis = 2; // 1 - Unitario 2 - Coalición
		//! obtenemos la coalicion del sistema
		if($tipo_analisis==2){
			//! Coalición
			foreach ($partidos_2024_coalicion[$row['id']] as $claves_partido_2024 => $datos) {
				//! convertimos la claves_partido_2024 en array
				$claves_partidos_2024_array = array();
				$claves_partidos_2024_array = explode(",", $claves_partido_2024);

				//! vemos que en el campo coalicion de los partidos este el individual para saber los demas con los que coincide.
				if (in_array($principal_clave, $claves_partidos_2024_array)) {
					//! encontramos los partidos que tiene coalicion
					foreach ($claves_partidos_2024_array as $clave_partido => $string) {
						$coalicion_principal[$clave_partido] = $string;
					}
				}
			}
			if(!empty($coalicion_principal)){
				//! Aqui hacemos un foreach de las coaliciones que guardamos en indivudual para obtener la informacion de los partidos individual.
				foreach ($coalicion_principal as $key => $clave_partido) {
					//! los individuales
					$partidos_principal_individuales[$clave_partido] = $partidos_2024_individual[$row['id']][$clave_partido];
				}

				//! obtemos los de clave_partidos_coaliciones para saber cuales pertenecen al grupo
				$partidos_principal_coalicion = array();
				foreach ($partidos_principal_individuales as $clave_partido_2024 => $datos) {


					foreach ($partidos_2024_coalicion[$row['id']] as $claves_partido_2024 => $value) {
						$claves_partidos_2024_array = array();
						$claves_partidos_2024_array = explode(",", $claves_partido_2024);
						if (in_array($principal_clave, $claves_partidos_2024_array)) {
							$partidos_principal_coalicion[$claves_partido_2024] = $value;
						}
					}
				}

				foreach ($partidos_2024_individual[$row['id']] as $key => $value) {
					if($key == $principal_clave){
						$partido_2024_individual[$row['id']][$key] = $value;
						$principal_nombre_corto_individual = $value['nombre_corto'];
						$principal_votos_totales_individual = $value['votos'];
						$principal_logo_individual = $value['logo'];
						
					}
				}
				
				$row['principal_clave_individual'] = $principal_clave;
				$row['principal_nombre_corto_individual'] = $principal_nombre_corto_individual;
				$row['principal_logo_individual'] = $principal_logo_individual;
				$row['principal_votos_totales_individual'] = $principal_votos_totales_individual;
				$principal_votos_totales_individuales_coalicion = 0;
				$principal_nombre_corto_individuales_coalicion = array();
				foreach ($partidos_principal_individuales as $key => $value) {
					if($key!=$principal_clave){
						$principal_votos_totales_individuales_coalicion = $principal_votos_totales_individuales_coalicion + $value['votos'];
						$principal_nombre_corto_individuales_coalicion[] = $value['nombre_corto'];
					}
				}
				$row['principal_nombre_corto_individuales_coalicion'] = implode(',',$principal_nombre_corto_individuales_coalicion);
				$row['principal_votos_totales_individuales_coalicion'] = $principal_votos_totales_individuales_coalicion;
				$votos_totales_coalicion = 0;
				foreach ($partidos_principal_coalicion as $key => $value) {
					$votos_totales_coalicion = $votos_totales_coalicion + $value['votos'];
				}
				if(!empty($principal_nombre_corto_individuales_coalicion)){
					$principal_nombre_corto_coalicion = $principal_nombre_corto_individual.",".implode(',',$principal_nombre_corto_individuales_coalicion);
				}
				$row['principal_nombre_corto_coalicion'] = $principal_nombre_corto_coalicion;
				$row['principal_votos_totales_coalicion'] = $votos_totales_coalicion;
				$row['principal_votos_totales'] = $row['principal_votos_totales_individual'] + $row['principal_votos_totales_individuales_coalicion'] + $row['principal_votos_totales_coalicion'];
				
				

				if ($row['votos_totales'] != 0) {
					$row['principal_individual_votos_totales_porcentaje'] = number_format(($row['principal_votos_totales_individual'] / $row['votos_totales']) * 100, 2, '.', '');
					$row['principal_coalicion_votos_totales_porcentaje'] = number_format(($row['principal_votos_totales'] / $row['votos_totales']) * 100, 2, '.', '');
				} else {
					$row['principal_individual_votos_totales_porcentaje'] = 0.00; // O cualquier valor por defecto que consideres adecuado.
					$row['principal_coalicion_votos_totales_porcentaje'] = 0.00; // O cualquier valor por defecto que consideres adecuado.
				}

				// Semáforo para rendimiento individual
				if ($row['principal_individual_votos_totales_porcentaje'] >= 40.0) {
					$color = "VERDE"; // Excelente
				} elseif ($row['principal_individual_votos_totales_porcentaje'] >= 30.0) {
					$color = "AMARILLO"; // Aceptable
				} elseif ($row['principal_individual_votos_totales_porcentaje'] >= 0.1) {
					$color = "ROJO"; // Bajo
				} else {
					$color = "GRIS"; // Muy bajo o nulo
				}
				$row['seccion_ine_semaforo_individual'] = $color;
				$row['seccion_ine_semaforo_individual_'] = $color;

				// Semáforo para rendimiento en coalición
				if ($row['principal_coalicion_votos_totales_porcentaje'] >= 50.0) {
					$color1 = "VERDE"; // Dominancia
				} elseif ($row['principal_coalicion_votos_totales_porcentaje'] >= 45.0) {
					$color1 = "AMARILLO"; // Competitivo
				} elseif ($row['principal_coalicion_votos_totales_porcentaje'] >= 0.1) {
					$color1 = "ROJO"; // Débil
				} else {
					$color1 = "GRIS"; // Margen muy reducido
				}
				$row['seccion_ine_semaforo_coalicion'] = $color1;
				$row['seccion_ine_semaforo_coalicion_'] = $color1;

				//! array para mostrar los partidos
				//! array para mostrar los partidos
				//! array para mostrar los partidos
				//$row['principal_partidos_individuales'] = $partidos_principal_individuales;
				//$row['principal_partidos_coalicion'] = $partidos_principal_coalicion;
				//$row['partidos_coaliciones_array'] = $coalicion_datos;
				//$row['partidos_coaliciones_key_array'] = $coalicion_partidos_datos;
				$partidos_indiviuales = $partidos_2024_individual[$row['id']];
				//$row['partidos_indiviuales'] = $partidos_2024_individual[$row['id']];
				$partidos_coaliciones = $partidos_2024_coalicion[$row['id']];
				//$row['partidos_coaliciones'] = $partidos_2024_coalicion[$row['id']];

				$peso_electoral[$row['id']] = $row['votos_totales_porcentaje'];
				//! Primera fuerza segun el partido en lo individual sin coaliciones
				$partidos_2024_individual;
				

				$maxVotos = 0; // Máxima cantidad de votos (primer lugar)
				$segundoMaxVotos = 0; // Segunda máxima cantidad de votos (segundo lugar)
				$partidoGanador = ''; // Partido con más votos
				$partidoSegundoLugar = ''; // Partido en segundo lugar

				// Ordenar partidos por votos de mayor a menor
				usort($partidos_2024_individual[$row['id']], function ($a, $b) {
					return $b['votos'] <=> $a['votos'];
				});
				unset($coa_orden);
				foreach ($coalicion_datos as $keyPartido => $valueTipo) {
					unset($coa_array);
					$total = 0;
					unset($coa_orden_ck);
					$coa = 0;
					$total_coa = 0;
					foreach ($valueTipo as $key => $tipo) {
						if($tipo==1){
							$nombre_corto = $partidos_indiviuales[$key]['nombre_corto'];
							$coa_array[$nombre_corto]=$nombre_corto;
							$total = $total + $partidos_indiviuales[$key]['votos'];
							$coa_orden_ck[$nombre_corto]['votos'] = $partidos_indiviuales[$key]['votos'];
							$coa_orden_ck[$nombre_corto]['tipo'] = 1;
						}else{
							$total = $total + $partidos_coaliciones[$key]['votos'];
							$nombre_corto = $partidos_coaliciones[$key]['nombre_corto'];
							$total_coa = $total_coa + $partidos_coaliciones[$key]['votos'];
						}
					}
					$coa_nombre_implode = implode('-',$coa_array);
					$coa_orden[$coa_nombre_implode] = $total; 
					
					$coa_orden_ck[$coa_nombre_implode]['votos'] = $total_coa;
					$coa_orden_ck[$coa_nombre_implode]['tipo'] = 2;
					$coa_orden_partidos[$coa_nombre_implode] = $coa_orden_ck; 
					$coa_orden_partidos[$coa_nombre_implode]['votos_totales'] = $total;
				}
				arsort($coa_orden);
				//! array para mostrar los partidos
				//! array para mostrar los partidos
				//! array para mostrar los partidos
				//$row['coaliciones_partidos_orden'] = $coa_orden_partidos;
				// Verifica que existan al menos dos partidos
				if (count($partidos_2024_individual[$row['id']]) >= 2) {
					// Primera fuerza
					$partidoPrimeraFuerza = $partidos_2024_individual[$row['id']][0];
					$row['partido_primera_fuerza'] = $partidoPrimeraFuerza['nombre_corto'];
					$row['partido_primera_fuerza_votos'] = $partidoPrimeraFuerza['votos'];
					$row['partido_primera_fuerza_logo'] = $partidoPrimeraFuerza['logo'];

					$row['partido_primera_dif_principal'] = $partidoPrimeraFuerza['votos'] - $row['principal_votos_totales_individual'];

					// Segunda fuerza
					$partidoSegundaFuerza = $partidos_2024_individual[$row['id']][1];
					$row['partido_segunda_fuerza'] = $partidoSegundaFuerza['nombre_corto'];
					$row['partido_segunda_fuerza_votos'] = $partidoSegundaFuerza['votos'];
					$row['partido_segunda_fuerza_logo'] = $partidoSegundaFuerza['logo'];

					// Obtener las primeras dos claves y valores
					$keys = array_keys($coa_orden);

					$row['coalicion_primera_fuerza'] = $keys[0];
					$row['coalicion_primera_fuerza_votos'] = $coa_orden[$keys[0]];
					$row['coalicion_primera_fuerza_datos'] = $coa_orden_partidos[$keys[0]];

					$row['coalicion_primera_dif_principal'] = $coa_orden[$keys[0]] - $row['principal_votos_totales'];

					$row['coalicion_segunda_fuerza'] = $keys[1];
					$row['coalicion_segunda_fuerza_votos'] = $coa_orden[$keys[1]];
					$row['coalicion_segunda_fuerza_datos'] = $coa_orden_partidos[$keys[1]];

				} else {
					$row['partido_primera_fuerza'] = "No Data";
					$row['partido_primera_fuerza_votos'] = "0";
					$row['partido_primera_fuerza_logo'] = "no_data.png";
					$row['partido_primera_dif_principal'] = "0";

					$row['partido_segunda_fuerza'] = "No Data";
					$row['partido_segunda_fuerza_votos'] = "0";
					$row['partido_segunda_fuerza_logo'] = "no_data.png";

					$row['coalicion_primera_fuerza'] = "No Data";
					$row['coalicion_primera_fuerza_votos'] = "0";
					$row['coalicion_primera_dif_principal'] = "0";
					

					$row['coalicion_segunda_fuerza'] = "No Data";
					$row['coalicion_segunda_fuerza_votos'] = "0";
				}

				//$row['partidos_orden_mayor_menor'] = $partidos_2024_individual[$row['id']];

					//! se multiplica por 0.5 para darle un equilibreio pero puede ser 0.3 y 0.7 el caso es que de 1 al final
				if (!empty($row['votos_totales']) && $row['votos_totales'] != 0) {
					$rentabilidad_participacion_votos_partido_individual_porcentaje = ($row['principal_individual_votos_totales_porcentaje'] * 0.5) + ($row['participacion_ciudadana'] * 0.5);
					$rentabilidad_participacion_votos_partido_coalicion_porcentaje = ($row['principal_coalicion_votos_totales_porcentaje'] * 0.5) + ($row['participacion_ciudadana'] * 0.5);
				} else {
					$rentabilidad_participacion_votos_partido_individual_porcentaje = 0.00; // Valor por defecto si votos_totales es 0
					$rentabilidad_participacion_votos_partido_coalicion_porcentaje = 0.00; // Valor por defecto si votos_totales es 0
				}
				$row['rentabilidad_participacion_votos_partido_individual_porcentaje'] = number_format($rentabilidad_participacion_votos_partido_individual_porcentaje, 2, '.', '');
				$rentabilidad_individual[$row['id']] = $row['rentabilidad_participacion_votos_partido_individual_porcentaje'];
				
				$row['rentabilidad_participacion_votos_partido_coalicion_porcentaje'] = number_format($rentabilidad_participacion_votos_partido_coalicion_porcentaje, 2, '.', '');
				$rentabilidad_coalicion[$row['id']] = $row['rentabilidad_participacion_votos_partido_coalicion_porcentaje'];
				
				$row['margen_victoria_individual'];
				$row['margen_victoria_coalicion'];

				//! copiamos todos los partidos orden segun el coa e individual.
				unset($partidos_rec);
				foreach ($coalicion_datos as $key_coa => $clave_partido_ind_coa) {
					unset($partido_nombre_array);
					$votos_totales_columna_coa = 0;
					$votos_totales_columnas = 0;
					$tiene_coa = false;
					$background_color = "";
					
					foreach ($clave_partido_ind_coa as $key_partido => $value_partido) {
						if($value_partido==1){
							$row[$partidos_indiviuales[$key_partido]['nombre_corto']] = $partidos_indiviuales[$key_partido]['votos'];
							$votos_totales_columnas = $votos_totales_columnas + $partidos_indiviuales[$key_partido]['votos'];

							$partidos_rec[1][$partidos_indiviuales[$key_partido]['nombre_corto']] = $partidos_indiviuales[$key_partido]['votos'];

							if($partidos_indiviuales[$key_partido]['nombre_corto'] != ""){
								$partido_nombre_array[] = $partidos_indiviuales[$key_partido]['nombre_corto'];
								$partidos_titulos[$partidos_indiviuales[$key_partido]['nombre_corto']] = array(
									'row' => $partidos_indiviuales[$key_partido]['nombre_corto'],
									'nombre' => $partidos_indiviuales[$key_partido]['nombre_corto'],
									'tipo' => 'integer',
									'fill' => "#".$partidos_indiviuales[$key_partido]['color_background'],
									'color' => '#FFFFFF',
								);
							}
							
							if($background_color==""){
								$background_color = "#".$partidos_indiviuales[$key_partido]['color_background'];
							}
						}else{
							$tiene_coa = true;
							$votos_totales_columna_coa = $votos_totales_columna_coa + $partidos_coaliciones[$key_partido]['votos'];
						}
					}
					//metemos el total de la coa
					if($tiene_coa==true){
						$string = implode('-', $partido_nombre_array);
						$row[$string.'_coa'] = $votos_totales_columna_coa;
						$partidos_rec[2][$string] = $votos_totales_columna_coa;
						$row[$string."_totales"] = $votos_totales_columna_coa + $votos_totales_columnas;
						if($partido_nombre_array != ""){
							$partidos_titulos[$string.'_coa'] = array(
								'row' => $string.'_coa',
								'nombre' => $string.'_coa',
								'tipo' => 'integer',
								'fill' => $background_color,
								'color' => '#FFFFFF',
							);

							$partidos_titulos[$string."_totales"] = array(
								'row' => $string."_totales",
								'nombre' => $string." V. Total",
								'tipo' => 'integer',
								'fill' => $background_color,
								'color' => '#FFFFFF',
							);
						}
					}
				}
				$row['partidos_show'] = $partidos_rec;
			}else{
				//! obtemos los de clave_partidos_coaliciones para saber cuales pertenecen al grupo
				//! Solo tomamos el principaol
				foreach ($partidos_2024_individual[$row['id']] as $key => $value) {
					if($key == $principal_clave){
						$partido_2024_individual[$row['id']][$key] = $value;
						$principal_nombre_corto_individual = $value['nombre_corto'];
						$principal_votos_totales_individual = $value['votos'];
						$principal_logo_individual = $value['logo'];
					}
				}

				$row['principal_clave_individual'] = $principal_clave;
				$row['principal_nombre_corto_individual'] = $principal_nombre_corto_individual;
				$row['principal_logo_individual'] = $principal_logo_individual;
				$row['principal_votos_totales_individual'] = $principal_votos_totales_individual;
				$row['principal_nombre_corto_individuales_coalicion'] = null;
				$row['principal_votos_totales_individuales_coalicion'] = 0;


				$row['principal_nombre_corto_coalicion'] = null;
				$row['principal_votos_totales_coalicion'] = 0;
				$row['principal_votos_totales'] = $row['principal_votos_totales_individual'] ;
				if ($row['votos_totales'] != 0) {
					$row['principal_individual_votos_totales_porcentaje'] = number_format(($row['principal_votos_totales_individual'] / $row['votos_totales']) * 100, 2, '.', '');
					$row['principal_coalicion_votos_totales_porcentaje'] = 0.00; // O cualquier valor por defecto que consideres adecuado.
				} else {
					$row['principal_individual_votos_totales_porcentaje'] = 0.00; // O cualquier valor por defecto que consideres adecuado.
					$row['principal_coalicion_votos_totales_porcentaje'] = 0.00; // O cualquier valor por defecto que consideres adecuado.
				}
				// Semáforo para rendimiento individual
				if ($row['principal_individual_votos_totales_porcentaje'] >= 40.0) {
					$color = "VERDE"; // Excelente
				} elseif ($row['principal_individual_votos_totales_porcentaje'] >= 30.0) {
					$color = "AMARILLO"; // Aceptable
				} elseif ($row['principal_individual_votos_totales_porcentaje'] >= 0.0) {
					$color = "ROJO"; // Bajo
				} else {
					$color = "GRIS"; // Muy bajo o nulo
				}
				$row['seccion_ine_semaforo_individual'] = $color;
				$row['seccion_ine_semaforo_individual_'] = $color;

				// Semáforo para rendimiento en coalición
				if ($row['principal_coalicion_votos_totales_porcentaje'] >= 50.0) {
					$color = "VERDE"; // Dominancia
				} elseif ($row['principal_coalicion_votos_totales_porcentaje'] >= 45.0) {
					$color = "AMARILLO"; // Competitivo
				} elseif ($row['principal_coalicion_votos_totales_porcentaje'] >= 0.0) {
					$color = "ROJO"; // Débil
				} else {
					$color = "GRIS"; // Margen muy reducido
				}
				$row['seccion_ine_semaforo_coalicion'] = $color;
				$row['seccion_ine_semaforo_individual_'] = $color;

				//$row['principal_partidos_individuales'] = $partidos_principal_individuales;
				//$row['principal_partidos_coalicion'] = $partidos_principal_coalicion;
				//$row['partidos_coaliciones_array'] = $coalicion_datos;
				//$row['partidos_coaliciones_key_array'] = $coalicion_partidos_datos;
				$partidos_indiviuales = $partidos_2024_individual[$row['id']];
				//$row['partidos_indiviuales'] = $partidos_2024_individual[$row['id']];
				$partidos_coaliciones = $partidos_2024_coalicion[$row['id']];
				//$row['partidos_coaliciones'] = $partidos_2024_coalicion[$row['id']];

				$peso_electoral[$row['id']] = $row['votos_totales_porcentaje'];
				//! Primera fuerza segun el partido en lo individual sin coaliciones
				$partidos_2024_individual;
				

				$maxVotos = 0; // Máxima cantidad de votos (primer lugar)
				$segundoMaxVotos = 0; // Segunda máxima cantidad de votos (segundo lugar)
				$partidoGanador = ''; // Partido con más votos
				$partidoSegundoLugar = ''; // Partido en segundo lugar

				// Ordenar partidos por votos de mayor a menor
				usort($partidos_2024_individual[$row['id']], function ($a, $b) {
					return $b['votos'] <=> $a['votos'];
				});
				
				foreach ($coalicion_datos as $keyPartido => $valueTipo) {
					unset($coa_array);
					$total = 0;
					unset($coa_orden_ck);
					$coa = 0;
					$total_coa = 0;
					foreach ($valueTipo as $key => $tipo) {
						if($tipo==1){
							$nombre_corto = $partidos_indiviuales[$key]['nombre_corto'];
							$coa_array[$nombre_corto]=$nombre_corto;
							$total = $total + $partidos_indiviuales[$key]['votos'];
							$coa_orden_ck[$nombre_corto]['votos'] = $partidos_indiviuales[$key]['votos'];
							$coa_orden_ck[$nombre_corto]['tipo'] = 1;
						}else{
							$total = $total + $partidos_coaliciones[$key]['votos'];
							$nombre_corto = $partidos_coaliciones[$key]['nombre_corto'];
							$total_coa = $total_coa + $partidos_coaliciones[$key]['votos'];
						}
					}
					$coa_nombre_implode = implode('-',$coa_array);
					$coa_orden[$coa_nombre_implode] = $total; 
					
					$coa_orden_ck[$coa_nombre_implode]['votos'] = $total_coa;
					$coa_orden_ck[$coa_nombre_implode]['tipo'] = 2;
					$coa_orden_partidos[$coa_nombre_implode] = $coa_orden_ck; 
					$coa_orden_partidos[$coa_nombre_implode]['votos_totales'] = $total;
				}
				arsort($coa_orden);
				//! array para mostrar los partidos
				//! array para mostrar los partidos
				//! array para mostrar los partidos
				//$row['coaliciones_partidos_orden'] = $coa_orden_partidos;
				// Verifica que existan al menos dos partidos
				if (count($partidos_2024_individual[$row['id']]) >= 2) {
					// Primera fuerza
					$partidoPrimeraFuerza = $partidos_2024_individual[$row['id']][0];
					$row['partido_primera_fuerza'] = $partidoPrimeraFuerza['nombre_corto'];
					$row['partido_primera_fuerza_votos'] = $partidoPrimeraFuerza['votos'];
					$row['partido_primera_fuerza_logo'] = $partidoPrimeraFuerza['logo'];

					$row['partido_primera_dif_principal'] = $partidoPrimeraFuerza['votos'] - $row['principal_votos_totales_individual'];

					// Segunda fuerza
					$partidoSegundaFuerza = $partidos_2024_individual[$row['id']][1];
					$row['partido_segunda_fuerza'] = $partidoSegundaFuerza['nombre_corto'];
					$row['partido_segunda_fuerza_votos'] = $partidoSegundaFuerza['votos'];
					$row['partido_segunda_fuerza_logo'] = $partidoSegundaFuerza['logo'];

					// Obtener las primeras dos claves y valores
					$keys = array_keys($coa_orden);

					$row['coalicion_primera_fuerza'] = $keys[0];
					$row['coalicion_primera_fuerza_votos'] = $coa_orden[$keys[0]];
					$row['coalicion_primera_fuerza_datos'] = $coa_orden_partidos[$keys[0]];

					$row['coalicion_primera_dif_principal'] = $coa_orden[$keys[0]] - $row['principal_votos_totales'];

					$row['coalicion_segunda_fuerza'] = $keys[1];
					$row['coalicion_segunda_fuerza_votos'] = $coa_orden[$keys[1]];
					$row['coalicion_segunda_fuerza_datos'] = $coa_orden_partidos[$keys[1]];

				} else {
					$row['partido_primera_fuerza'] = "No Data";
					$row['partido_primera_fuerza_votos'] = "0";
					$row['partido_primera_fuerza_logo'] = "no_data.png";
					$row['partido_primera_dif_principal'] = "0";

					$row['partido_segunda_fuerza'] = "No Data";
					$row['partido_segunda_fuerza_votos'] = "0";
					$row['partido_segunda_fuerza_logo'] = "no_data.png";

					$row['coalicion_primera_fuerza'] = "No Data";
					$row['coalicion_primera_fuerza_votos'] = "0";
					$row['coalicion_primera_dif_principal'] = "0";
					

					$row['coalicion_segunda_fuerza'] = "No Data";
					$row['coalicion_segunda_fuerza_votos'] = "0";
				}

				//$row['partidos_orden_mayor_menor'] = $partidos_2024_individual[$row['id']];
				//! se multiplica por 0.5 para darle un equilibreio pero puede ser 0.3 y 0.7 el caso es que de 1 al final
				if (!empty($row['votos_totales']) && $row['votos_totales'] != 0) {
					$rentabilidad_participacion_votos_partido_individual_porcentaje = ($row['principal_individual_votos_totales_porcentaje'] * 0.5) + ($row['participacion_ciudadana'] * 0.5);
					$rentabilidad_participacion_votos_partido_coalicion_porcentaje = 0;
				} else {
					$rentabilidad_participacion_votos_partido_individual_porcentaje = 0.00; // Valor por defecto si votos_totales es 0
					$rentabilidad_participacion_votos_partido_coalicion_porcentaje = 0.00; // Valor por defecto si votos_totales es 0
				}
				$row['rentabilidad_participacion_votos_partido_individual_porcentaje'] = number_format($rentabilidad_participacion_votos_partido_individual_porcentaje, 2, '.', '');
				$rentabilidad_individual[$row['id']] = $row['rentabilidad_participacion_votos_partido_individual_porcentaje'];
				
				$row['rentabilidad_participacion_votos_partido_coalicion_porcentaje'] = number_format($rentabilidad_participacion_votos_partido_coalicion_porcentaje, 2, '.', '');
				$rentabilidad_coalicion[$row['id']] = $row['rentabilidad_participacion_votos_partido_coalicion_porcentaje'];
				
				$row['margen_victoria_individual'];
				$row['margen_victoria_coalicion'];

				//! copiamos todos los partidos orden segun el coa e individual.
				unset($partidos_rec);
				foreach ($coalicion_datos as $key_coa => $clave_partido_ind_coa) {
					unset($partido_nombre_array);
					$votos_totales_columna_coa = 0;
					$votos_totales_columnas = 0;
					$tiene_coa = false;
					$background_color = "";
					foreach ($clave_partido_ind_coa as $key_partido => $value_partido) {
						if($value_partido==1){
							$row[$partidos_indiviuales[$key_partido]['nombre_corto']] = $partidos_indiviuales[$key_partido]['votos'];
							$votos_totales_columnas = $votos_totales_columnas + $partidos_indiviuales[$key_partido]['votos'];
							
							$partidos_rec[$partidos_indiviuales[$key_partido]['nombre_corto']] = $partidos_indiviuales[$key_partido]['votos'];
							
							if($partidos_indiviuales[$key_partido]['nombre_corto'] != ""){
								$partido_nombre_array[] = $partidos_indiviuales[$key_partido]['nombre_corto'];
								$partidos_titulos[$partidos_indiviuales[$key_partido]['nombre_corto']] = array(
									'row' => $partidos_indiviuales[$key_partido]['nombre_corto'],
									'nombre' => $partidos_indiviuales[$key_partido]['nombre_corto'],
									'tipo' => 'integer',
									'fill' => "#".$partidos_indiviuales[$key_partido]['color_background'],
									'color' => '#FFFFFF',
								);
							}
							
							if($background_color==""){
								$background_color = "#".$partidos_indiviuales[$key_partido]['color_background'];
							}
						}else{
							$tiene_coa = true;
							$votos_totales_columna_coa = $votos_totales_columna_coa + $partidos_coaliciones[$key_partido]['votos'];
						}
					}
					//metemos el total de la coa
					if($tiene_coa==true){
						$string = implode('-', $partido_nombre_array);
						$row[$string.'_coa'] = $votos_totales_columna_coa;
						$partidos_rec[$string] = $votos_totales_columna_coa;
						$row[$string."_totales"] = $votos_totales_columna_coa + $votos_totales_columnas;
						if($partido_nombre_array != ""){
							$partidos_titulos[$string.'_coa'] = array(
								'row' => $string.'_coa',
								'nombre' => $string,
								'tipo' => 'integer',
								'fill' => '#e9e9e9',
								'color' => '#161616',
							);

							$partidos_titulos[$string."_totales"] = array(
								'row' => $string."_totales",
								'nombre' => $string." V. Total",
								'tipo' => 'integer',
								'fill' => '#e9e9e9',
								'color' => '#161616',
							);
						}
					}
				}
				$row['partidos_show'] = $partidos_rec;
			}
		}else{
			//no va nada aqui
		}
		
		$seccion_ine_datos[$row['id']] = $row;
	}
	/////
	arsort($rentabilidad_individual);
	$num = 1;
	foreach ($rentabilidad_individual as $key => $value) {
		$rentabilidad_individual_orden[$key] = $num;
		$num ++;
	}

	arsort($rentabilidad_coalicion);
	$num = 1;
	foreach ($rentabilidad_coalicion as $key => $value) {
		$rentabilidad_coalicion_orden[$key] = $num;
		$num ++;
	}
	
	foreach ($seccion_ine_datos as $key => $value) {
		$seccion_ine_datos[$key]['rentabilidad_participacion_votos_partido_individual_porcentaje_orden'] = $rentabilidad_individual_orden[$key];
		$seccion_ine_datos[$key]['rentabilidad_participacion_votos_partido_coalicion_porcentaje_orden'] = $rentabilidad_coalicion_orden[$key];
	}


	//! Ordena segun el tipo A,B,C segun el peso eletoral.
	// Paso 2: Calcular el total de los valores
	// Filtra las claves con valores mayor a 0
	// Ordena el array de mayor a menor
	arsort($peso_electoral);

	// Calculamos las sumas acumuladas
	$totalSum = array_sum($peso_electoral);
	$thresholdA = $totalSum * 0.50; // 50%
	$thresholdB = $totalSum * 0.30; // 30%
	$thresholdC = $totalSum * 0.20; // 20%

	$currentSumA = 0;
	$currentSumB = 0;
	$currentSumC = 0;

	$A = [];
	$B = [];
	$C = [];

	// Asignación a A, B o C basado en el porcentaje acumulado
	foreach ($peso_electoral as $key => $value) {
		if ($currentSumA < $thresholdA) {
			$A[$key] = $value;
			$currentSumA += $value;
		} elseif ($currentSumB < $thresholdB) {
			$B[$key] = $value;
			$currentSumB += $value;
		} else {
			$C[$key] = $value;
			$currentSumC += $value;
		}
	}
	// Asignando claves de acuerdo a las categorías
	//echo "A (" . number_format($currentSumA / $totalSum * 100, 2) . "%):\n";
	//print_r($A);

	//echo "\nB (" . number_format($currentSumB / $totalSum * 100, 2) . "%):\n";
	//print_r($B);

	//echo "\nC (" . number_format($currentSumC / $totalSum * 100, 2) . "%):\n";
	//print_r($C);
	foreach ($A as $key => $value) {
		$seccion_semaforo_tipo[$key] = 'A';
		$seccion_ine_datos[$key]['prioridad'] = 'A';
	}
	foreach ($B as $key => $value) {
		$seccion_semaforo_tipo[$key] = 'B';
		$seccion_ine_datos[$key]['prioridad'] = 'B';
	}
	foreach ($C as $key => $value) {
		$seccion_semaforo_tipo[$key] = 'C';
		$seccion_ine_datos[$key]['prioridad'] = 'C';
	}
	
	$columnas_titulos1 = array(

		['row' => 'municipio', 'nombre' => 'Municipio', 'tipo' => 'string' , 'fill' => '#525252', 'color' => '#FFFFFF'],
		['row' => 'id_distrito_local', 'nombre' => 'Distrito Local', 'tipo' => 'integer' , 'fill' => '#525252', 'color' => '#FFFFFF'],
		['row' => 'id_distrito_federal', 'nombre' => 'Distrito Federal', 'tipo' => 'integer' , 'fill' => '#525252', 'color' => '#FFFFFF'],
		['row' => 'seccion_localidades', 'nombre' => 'Localidad(es)', 'tipo' => 'string' , 'fill' => '#525252', 'color' => '#FFFFFF'],
		['row' => 'seccion_colonias', 'nombre' => 'Colonia(s)', 'tipo' => 'string' , 'fill' => '#525252', 'color' => '#FFFFFF'],
		['row' => 'numero', 'nombre' => 'Sección', 'tipo' => 'integer' , 'fill' => '#525252', 'color' => '#FFFFFF'],
		['row' => 'seccion_tipo', 'nombre' => 'Tipo Sección', 'tipo' => 'string' , 'fill' => '#525252', 'color' => '#FFFFFF'],
		['row' => 'manzanas', 'nombre' => 'Manzanas', 'tipo' => 'integer' , 'fill' => '#525252', 'color' => '#FFFFFF'],
		['row' => 'gps', 'nombre' => 'Ubicación GPS', 'tipo' => 'string' , 'fill' => '#525252', 'color' => '#FFFFFF'],
		['row' => 'partido_primera_fuerza', 'nombre' => '1era Fuerza', 'tipo' => 'string' , 'fill' => '#ffffbf', 'color' => '#161616'],
		['row' => 'partido_primera_fuerza_votos', 'nombre' => '1era Fuerza Votos', 'tipo' => 'integer' , 'fill' => '#ffffbf', 'color' => '#161616'],
		['row' => 'partido_segunda_fuerza', 'nombre' => '2da Fuerza', 'tipo' => 'string' , 'fill' => '#ffffbf', 'color' => '#161616'],
		['row' => 'partido_segunda_fuerza_votos', 'nombre' => '2da Fuerza Votos', 'tipo' => 'integer' , 'fill' => '#ffffbf', 'color' => '#161616'],
	);
	foreach ($partidos_titulos as $key => $value) {
		$columnas_titulos2[] = $value;
	}

	$columnas_titulos3 = array(
		['row' => 'casillas', 'nombre' => 'Casillas', 'tipo' => 'integer' , 'fill' => '#561436', 'color' => '#FFFFFF'],
		['row' => 'votos_validos', 'nombre' => 'V. Validos', 'tipo' => 'integer' , 'fill' => '#561436', 'color' => '#FFFFFF'],
		['row' => 'votos_can_nreg', 'nombre' => 'V. C N R', 'tipo' => 'integer' , 'fill' => '#561436', 'color' => '#FFFFFF'],
		['row' => 'votos_nulos', 'nombre' => 'V. Nulos', 'tipo' => 'integer' , 'fill' => '#561436', 'color' => '#FFFFFF'],
		['row' => 'votos_totales', 'nombre' => 'V. Totales', 'tipo' => 'integer' , 'fill' => '#561436', 'color' => '#FFFFFF'],
		['row' => 'votos_totales_porcentaje', 'nombre' => 'V. Totales % Sum Total', 'tipo' => 'integer' , 'fill' => '#561436', 'color' => '#FFFFFF'],
		['row' => 'lista_nominal', 'nombre' => 'LN', 'tipo' => 'integer' , 'fill' => '#561436', 'color' => '#FFFFFF'],
		['row' => 'lista_nominal_porcentaje', 'nombre' => 'LN % Sum Total', 'tipo' => 'integer' , 'fill' => '#561436', 'color' => '#FFFFFF'],
		['row' => 'participacion_ciudadana', 'nombre' => 'Participación C.', 'tipo' => 'integer' , 'fill' => '#561436', 'color' => '#FFFFFF'],
		['row' => 'prioridad', 'nombre' => 'Prioridad Electoral', 'tipo' => 'string' , 'fill' => '#561436', 'color' => '#FFFFFF'],
		['row' => 'ciudadanos_registrados', 'nombre' => 'Ciudadanos Reg', 'tipo' => 'integer' , 'fill' => '#0d503c', 'color' => '#FFFFFF'],
		['row' => 'funcionarios', 'nombre' => 'Funcionarios', 'tipo' => 'integer' , 'fill' => '#0d503c', 'color' => '#FFFFFF'],
		['row' => 'militantes', 'nombre' => 'Militantes', 'tipo' => 'integer' , 'fill' => '#0d503c', 'color' => '#FFFFFF'],
		['row' => 'grupos_interes', 'nombre' => 'Grupos', 'tipo' => 'integer' , 'fill' => '#0d503c', 'color' => '#FFFFFF'],
		['row' => 'apoyos_programas', 'nombre' => 'Programas Gob', 'tipo' => 'integer' , 'fill' => '#0d503c', 'color' => '#FFFFFF'],
		['row' => 'acciones_obras', 'nombre' => 'Acciones', 'tipo' => 'integer' , 'fill' => '#0d503c', 'color' => '#FFFFFF'],
		['row' => 'eventos_agenda_gobierno', 'nombre' => 'E. Agenda Gob', 'tipo' => 'integer' , 'fill' => '#0d503c', 'color' => '#FFFFFF'],
		['row' => 'principal_nombre_corto_individual', 'nombre' => 'Principal Partido', 'tipo' => 'string' , 'fill' => $principal_background, 'color' => '#FFFFFF'],
		['row' => 'principal_votos_totales_individual', 'nombre' => 'Votos Totales Individual', 'tipo' => 'integer' , 'fill' => $principal_background, 'color' => '#FFFFFF'],
		['row' => 'principal_nombre_corto_individuales_coalicion', 'nombre' => 'Coaliciones Individuales', 'tipo' => 'string' , 'fill' => $principal_background, 'color' => '#FFFFFF'],
		['row' => 'principal_votos_totales_individuales_coalicion', 'nombre' => 'Votos Totales Ind. Coa.', 'tipo' => 'integer' , 'fill' => $principal_background, 'color' => '#FFFFFF'],
		['row' => 'principal_nombre_corto_coalicion', 'nombre' => 'Coaliciones', 'tipo' => 'string' , 'fill' => $principal_background, 'color' => '#FFFFFF'],
		['row' => 'principal_votos_totales_coalicion', 'nombre' => 'Coalición Votos', 'tipo' => 'integer' , 'fill' => $principal_background, 'color' => '#FFFFFF'],
		['row' => 'principal_votos_totales', 'nombre' => 'Votos Totales', 'tipo' => 'integer' , 'fill' => $principal_background, 'color' => '#FFFFFF'],
		['row' => 'principal_individual_votos_totales_porcentaje', 'nombre' => 'Votos Totales Individual %', 'tipo' => 'integer' , 'fill' => $principal_background, 'color' => '#FFFFFF'],
		['row' => 'principal_coalicion_votos_totales_porcentaje', 'nombre' => 'Votos Totales Coalición %', 'tipo' => 'integer' , 'fill' => $principal_background, 'color' => '#FFFFFF'],
		['row' => 'seccion_ine_semaforo_individual', 'nombre' => 'Semáforos Individual', 'tipo' => 'string' , 'fill' => '#b02a37', 'color' => '#FFFFFF'],
		['row' => 'seccion_ine_semaforo_coalicion', 'nombre' => 'Semáforos Coalición', 'tipo' => 'string' , 'fill' => '#b02a37', 'color' => '#FFFFFF'],
		['row' => 'rentabilidad_participacion_votos_partido_individual_porcentaje', 'nombre' => 'Rentabilidad Individual %', 'tipo' => 'integer' , 'fill' => '#495057', 'color' => '#FFFFFF'],
		['row' => 'rentabilidad_participacion_votos_partido_individual_porcentaje_orden', 'nombre' => 'Rentabilidad Individual Orden', 'tipo' => 'integer' , 'fill' => '#495057', 'color' => '#FFFFFF'],
		['row' => 'rentabilidad_participacion_votos_partido_coalicion_porcentaje', 'nombre' => 'Rentabilidad Coalición %', 'tipo' => 'integer' , 'fill' => '#495057', 'color' => '#FFFFFF'],
		['row' => 'rentabilidad_participacion_votos_partido_coalicion_porcentaje_orden', 'nombre' => 'Rentabilidad Coalición Orden', 'tipo' => 'integer' , 'fill' => '#495057', 'color' => '#FFFFFF'],
	);

	$columnas_titulos = array_merge(
		$columnas_titulos1,
		$columnas_titulos2,
		$columnas_titulos3
	);
	/*
	echo "<table border=1 style='table-layout: auto; width: 100%'>";
	echo "<tr>";
	foreach ($columnas_titulos as $key => $titulo) {
		echo "<td style='background-color:".$titulo['fill']."; padding:10px; color:".$titulo['color']."' >";
		echo $titulo['nombre'];
		echo "</td>";
	}
	echo "</tr>";
	foreach ($seccion_ine_datos as $seccion => $data) {
		echo "<tr>";
		foreach ($columnas_titulos as $key => $titulo) {
			echo "<td>";
			echo $data[$titulo['row']];
			echo "</td>";
		}
		echo "</tr>";
		break;
	}
	echo "</table>";
	*/
	/*
	'id_seccion_ine' : id_seccion_ine,
	'id_municipio' : <?= $id_municipio ?>,
	'id_distrito_local' : id_distrito_local,
	'id_distrito_federal' : id_distrito_federal,
	'partido_ganador_individual' : partido_ganador_individual,
	'partido_ganador_coalicion' : partido_ganador_coalicion,
	'semaforo_individual' : semaforo_individual,
	'semaforo_coalicion' : semaforo_coalicion,
	'tipo_seccion' : tipo_seccion,
	'prioridad' : prioridad,
	'secciones_ine_agendas_gobierno' : secciones_ine_agendas_gobierno,
	'secciones_ine_actividades' : secciones_ine_actividades,
	*/

	
	if(!empty($_POST)){
		// Función para convertir string en array eliminando valores vacíos
		function convertToArray($string) {
			return array_filter(
				explode(",", $string),
				function ($value) {
					return $value !== '';
				}
			);
		}

		$id_seccion_ine_array = convertToArray($_POST['mapa'][0]['id_seccion_ine']);
		$id_municipio_array = convertToArray($_POST['mapa'][0]['id_municipio']);
		$id_distrito_local_array = convertToArray($_POST['mapa'][0]['id_distrito_local']);
		$id_distrito_federal_array = convertToArray($_POST['mapa'][0]['id_distrito_federal']);
		$partido_ganador_individual_array = convertToArray($_POST['mapa'][0]['partido_ganador_individual']);
		$partido_ganador_coalicion_array = convertToArray($_POST['mapa'][0]['partido_ganador_coalicion']);
		$semaforo_individual_array = convertToArray($_POST['mapa'][0]['semaforo_individual']);
		$semaforo_coalicion_array = convertToArray($_POST['mapa'][0]['semaforo_coalicion']);
		$tipo_seccion_array = convertToArray($_POST['mapa'][0]['tipo_seccion']);
		$prioridad_array = convertToArray($_POST['mapa'][0]['prioridad']);
		$num = 0;
		foreach ($seccion_ine_datos as $seccion => $data) {
			//! se coloca la id de seccion para las busquedas posteriores
			$id_secciones_ine_validos[] = $seccion;
			$borra_seccion = false;
			if(!empty($id_seccion_ine_array)){
				if (!in_array($seccion, $id_seccion_ine_array)) {
					//unset($seccion_ine_datos[$seccion]);
					$borra_seccion = true;
				}
			}
			if(!empty($id_municipio_array)){
				if (!in_array($data['id_municipio'], $id_municipio_array)) {
					//unset($seccion_ine_datos[$seccion]);
					$borra_seccion = true;
				}
			}
			if(!empty($id_distrito_local_array)){
				if (!in_array($data['id_distrito_local'], $id_distrito_local_array)) {
					//unset($seccion_ine_datos[$seccion]);
					$borra_seccion = true;
				}
			}
			if(!empty($id_distrito_federal_array)){
				if (!in_array($data['id_distrito_federal'], $id_distrito_federal_array)) {
					//unset($seccion_ine_datos[$seccion]);
					$borra_seccion = true;
				}
			}
			if(!empty($partido_ganador_individual_array)){
				if (!in_array($data['partido_primera_fuerza'], $partido_ganador_individual_array)) {
					//unset($seccion_ine_datos[$seccion]);
					$borra_seccion = true;
				}
			}
			if(!empty($partido_ganador_coalicion_array)){
				if (!in_array($data['coalicion_primera_fuerza'], $partido_ganador_coalicion_array)) {
					//unset($seccion_ine_datos[$seccion]);
					$borra_seccion = true;
				}
			}
			if(!empty($semaforo_individual_array)){
				if (!in_array($data['seccion_ine_semaforo_individual'], $semaforo_individual_array)) {
					//unset($seccion_ine_datos[$seccion]);
					$borra_seccion = true;
				}
			}
			if(!empty($semaforo_coalicion_array)){
				if (!in_array($data['seccion_ine_semaforo_coalicion'], $semaforo_coalicion_array)) {
					//unset($seccion_ine_datos[$seccion]);
					$borra_seccion = true;
				}
			}
			if(!empty($tipo_seccion_array)){
				if (!in_array($data['tipo'], $tipo_seccion_array)) {
					//unset($seccion_ine_datos[$seccion]);
					$borra_seccion = true;
				}
			}
			if(!empty($prioridad_array)){
				if (!in_array($data['prioridad'], $prioridad_array)) {
					//unset($seccion_ine_datos[$seccion]);
					$borra_seccion = true;
				}
			}
			//! si no cumple borra y oculta para que se vea oscuro igual toma el primero para centrar el mapa
			if($borra_seccion){
				unset($seccion_ine_datos[$seccion]['seccion_ine_semaforo_individual_']);
				unset($seccion_ine_datos[$seccion]['seccion_ine_semaforo_coalicion_']);
			}else{
				$seccion_ine_json[$seccion] = $seccion_ine_datos[$seccion];
				if($num==0){
					$latitud_sugerido = $data['latitud'];
					$longitud_sugerido = $data['longitud'];
					$num ++;
				}
			}
		}

		if(!empty($id_secciones_ine_validos)){
			if(!empty($_POST['mapa'][0]['secciones_ine_giras']) || !empty($_POST['mapa'][0]['secciones_ine_actividades'])  ){
				
			}
			/*
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
			}
			*/
			if(!empty($_POST['mapa'][0]['secciones_ine_agendas_gobierno'])){
				unset($search);
				include __DIR__."/../../functions/secciones_ine_agendas_gobierno.php";
				//include __DIR__."/../../functions/secciones_ine_agendas_gobierno_locaciones.php";
		
				$perido_fecha_inicial = $_COOKIE['fecha_inicial'];
				$perido_fecha_final = $_COOKIE['fecha_final'];
				$id_secciones_ine_validos;
				$id_secciones_ine_validosx = implode(",", $id_secciones_ine_validos);
				$search['id_seccion_ine'] = $id_secciones_ine_validosx;
				$search['fecha_1'] = $perido_fecha_inicial;
				$search['fecha_2'] = $perido_fecha_final;
				$search['id_tipo_gira'] = "'".implode("','",explode(',',$_POST['mapa'][0]['secciones_ine_agendas_gobierno']))."'";
				$secciones_ine_agendas_gobiernoDatosArray = secciones_ine_agendas_gobiernoDatosArray($search);
				
			}
			if(!empty($_POST['mapa'][0]['secciones_ine_actividades'])){
				unset($search);
				include __DIR__."/../../functions/secciones_ine_actividades.php";
				include __DIR__."/../../functions/secciones_ine_actividades_puntos.php";
		
				$perido_fecha_inicial = $_COOKIE['fecha_inicial'];
				$perido_fecha_final = $_COOKIE['fecha_final'];
				$id_secciones_ine_validos;
				$id_secciones_ine_validosx = implode(",", $id_secciones_ine_validos);
				$id_secciones_ine_validosx;
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

	}else{
		$seccion_ine_json = $seccion_ine_datos;
	}
	
	//echo "<pre>";
	//var_dump($seccion_ine_datos);
	//echo "</pre>";


	$json_data = json_encode($seccion_ine_json, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	if ($json_data === false) {
		// Manejar el error de codificación JSON si es necesario
		//echo "Error al codificar el JSON";
	} else {
		// Guarda el JSON en un archivo
		$archivo_json = $rutaEfs.'distrito_local_2024_'.$id_distrito_federal.'-'.$_COOKIE["id_usuario"].'.json';
		if (file_put_contents($archivo_json, $json_data)) {
			//echo "Los datos se han guardado en $archivo_json";
		} else {
			//echo "Error al guardar los datos en $archivo_json";
		}
	}
	$json_data = json_encode($columnas_titulos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
	if ($json_data === false) {
		// Manejar el error de codificación JSON si es necesario
		//echo "Error al codificar el JSON";
	} else {
		// Guarda el JSON en un archivo
		$archivo_json = $rutaEfs.'titulos_distrito_local_2024_-'.$id_distrito_federal.'-'.$_COOKIE["id_usuario"].'.json';
		if (file_put_contents($archivo_json, $json_data)) {
			//echo "Los datos se han guardado en $archivo_json";
		} else {
			//echo "Error al guardar los datos en $archivo_json";
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
			height:155px;
			text-align:center;
			border: 1px solid #e5e5e5;
			padding: 2px;
			background-color:#e5e5e5;
			vertical-align: middle;
		}
		.info_seccion_ganador{
			width:40%;
			float:left;
			height:155px;
			text-align:left;
			border: 1px solid #cecece;
			padding: 6px 0px 0px 4px ;
			background-color:#cecece;
		}
		.info_seccion_informacion{
			width:70%;
			float:left;
			height:140px;
			text-align:left;
			border: 1px solid #cecece;
			padding: 6px 0px 0px 4px ;
			background-color:#cecece;
		}
		.info_seccion_ganador_2{
			width:70%;
			float:left;
			height:140px;
			text-align:left;
			border: 1px solid #cecece;
			padding: 6px 0px 0px 4px ;
			background-color:#cecece;
		}
		.info_seccion_ganador_button{
			width:30%;
			float:left;
			height:155px;
			text-align:left;
			border: 1px solid #cecece;
			padding: 6px 5px 0px 5px ;
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
			height:150px;
			text-align:left;
			border: 1px solid gray;
			padding: 10px 0px 2px 5px;
			/*background-color:#e36962;*/
			color:black;
		}
		.datos_partido{
			width:70%;
			float:left;
			height:150px;
			text-align:left;
			border: 1px solid gray;
			padding: 5px 0px 2px 5px;
			/*background-color:#e36962;*/
			color:black;
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
		
		
		/* Ocultar el botón de cierre del infoWindow */
		
		/* Tamaño estándar del botón de cierre del infoWindow */
		/* Ajusta el área roja */

		/* Estiliza la "X" correctamente */
		.gm-style-iw .gm-ui-close,
		.gm-style .gm-ui-hover-effect {
			width: 100% !important; /* Tamaño cuadrado para la "X" */
			height: 40px !important; /* Altura acorde al área roja */
			background-color: rgba(176,176,176,0.6) !important; /* Fondo transparente */
			color: #fff !important; /* Color blanco para la "X" */
			font-size: 16px !important; /* Tamaño visible para la "X" */
			text-align: right; /* Alineación centrada */
			display: flex !important; /* Activa flexbox para mejor alineación */
			align-items: right !important; /* Centra verticalmente */
			justify-content: right !important; /* Centra horizontalmente */
			position: absolute !important; /* Posiciona la "X" */
			top: 0 !important; /* Fija la "X" al borde superior */
			right: 0 !important; /* Fija la "X" al borde derecho */
			cursor: pointer; /* Cambia el cursor a "mano" */
		}

		/* Ajusta la altura del globo para que la barra roja se vea completa */
		.gm-style-iw {
			min-height: 60px !important; /* Asegura que el área roja sea suficientemente alta */
			padding-top: 30px !important; /* Ajusta la altura del contenido */
		}

		@media screen and (max-width: 1281px) {
			.info_content{
				text-align: center;
			}
			.divMapaTerritorio{
				width:167px;
				height:200px;
				margin: -10px 0px 0px 10px;
			}
			.divMapa{
				width:167px;
				height:auto;
				margin: -10px 0px 0px 10px;
			}
			.info_titulo{
				width:100%;
				height:50px;
			}
			.info_seccion_ganador_button{
				width:100%;
				height:120px;
				background-color:#a0a0a0;
			}
			.info_seccion_ganador,.info_seccion_informacion{
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
			var latitud='<?= $latitud_sugerido ?>';
			var longitud='<?= $longitud_sugerido ?>';
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
				foreach ($datos_distritos_locales as $key => $value) {
					$paths = [];
				
					foreach ($distritos_locales_parametrosDatosMapa[$value['id']] as $keyT => $valueT) {
						$path = "distritos_locales_polygon".$key."_".$keyT;
						echo "var " . $path . " = [";
				
						$coordenadas = [];
						foreach ($valueT as $keyH => $valueH) {
							$coordenadas[] = "{ lat: " . $valueH['latitud'] . ", lng: " . $valueH['longitud'] . " }";
						}
						
						// Cierra el polígono si hay coordenadas
						if (!empty($coordenadas)) {
							$coordenadas[] = $coordenadas[0];
						}
				
						echo implode(",", $coordenadas); // Genera las coordenadas sin coma extra
						echo "];\n";
				
						$paths[] = $path; // Guarda el nombre de la variable para usarlo en el polígono
					}
				
					// Verificación de colores predeterminados
					if (empty($value['partido_ganador_background']) || $key != $id_distrito_local) {
						$value['partido_ganador_border'] = "000000";
						$value['partido_ganador_background'] = "000000";
					}
				
					?>
					var distrito_local_area<?= $key ?> = new google.maps.Polygon({
						paths: [<?= implode(",", $paths) ?>], // Asegura que paths se imprime correctamente sin comas extra
						strokeColor: "#<?= $value['partido_ganador_border'] ?>",
						strokeOpacity: 0.8,
						strokeWeight: 1,
						fillColor: "#<?= $value['partido_ganador_background'] ?>",
						fillOpacity: 0.35,
					});
					distrito_local_area<?= $key ?>.setMap(map);
					<?php
				}
				
				
				
				foreach ($seccion_ine_datos as $key => $value){
					//definimos los colores del semaforo coalicion
					if($value['seccion_ine_semaforo_coalicion']=='ROJO'){
						$color = 'rgba(255, 105, 97, 0.9)';
					}elseif ($value['seccion_ine_semaforo_coalicion']=='AMARILLO') {
						$color = 'rgba(253, 253, 150, 0.9)';;
					}elseif ($value['seccion_ine_semaforo_coalicion']=='GRIS') {
						$color = 'rgba(141, 141, 141, 0.9)';;
					}elseif ($value['seccion_ine_semaforo_coalicion']=='VERDE') {
						$color = 'rgba(119, 221, 119, 0.9)';;
					}else{
						$color = 'rgba(0, 0, 0, 0.9)';
					}
					if($value['seccion_ine_semaforo_individual']=='ROJO'){
						$color1 = 'rgba(255, 105, 97, 0.9)';
					}elseif ($value['seccion_ine_semaforo_individual']=='AMARILLO') {
						$color1 = 'rgba(253, 253, 150, 0.9)';;
					}elseif ($value['seccion_ine_semaforo_individual']=='GRIS') {
						$color1 = 'rgba(141, 141, 141, 0.9)';;
					}elseif ($value['seccion_ine_semaforo_individual']=='VERDE') {
						$color1 = 'rgba(119, 221, 119, 0.9)';;
					}else{
						$color1 = 'rgba(0, 0, 0, 0.9)';
					}

					//! mensaje de infoWindow
					$div = '<div class="divMapa">
								<div class="info_content">
									<h4>Sección: '.$value['numero'].'</h4>
									<h5>Localidad(es): '.htmlspecialchars(str_replace('*_*', ' - ', $value['seccion_localidades']), ENT_QUOTES, 'UTF-8').'</h5>
									<h5>Colonia(s): '.htmlspecialchars(str_replace('*_*', ' - ', $value['seccion_colonias']), ENT_QUOTES, 'UTF-8').'</h5>
									<div class="info_titulo">
										<h5>Votación '.$ano.'</h5>
									</div>
									<div class="info_seccion_ganador">
										Tipo Sección: <b>'.$value['seccion_tipo'].'</b><br>
										Prioridad: <b>'.$value['prioridad'].'</b><br>
										Peso Electoral: <b>'.$value['votos_totales_porcentaje'].'%</b><br>
										Lista Nominal: <b>'.number_format($value['lista_nominal'], 0, '.', ',').'</b><br>
										Lista Nominal % Total: <b>'.$value['lista_nominal_porcentaje'].'%</b><br>
										Partido Ganador: <b>'.$value['partido_primera_fuerza'].'</b><br>
										Coalición Ganador: <br><b>'.$value['coalicion_primera_fuerza'].'</b><br>
									</div>
									<div class="info_seccion_ganador_button">
										<div style="text-align:center;color:black">
											% Votación Total
										</div>
										<div style="background-color:'.$color1.';padding:5px;margin-top:2px;text-align:center;color:black">
											Partido :<b style="color:black; background-color:'.$color1.'">'.$value['principal_individual_votos_totales_porcentaje'].'%</b>
											<br>
											Dif :<b style="color:black; background-color:'.$color1.'">'.$value['partido_primera_dif_principal'].' Votos</b>
										</div>
										<div style="background-color:'.$color.';padding:5px;margin-top:2px;text-align:center;color:black">
											Coalición :<b style="color:black; background-color:'.$color.'">'.$value['principal_coalicion_votos_totales_porcentaje'].'%</b>
											<br>
											Dif :<b style="color:black; background-color:'.$color.'">'.$value['coalicion_primera_dif_principal'].' Votos</b>
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
										<img src="images/logos_partidos/'.$value['principal_logo_individual'].'" style="width: 30px ">
									</div>
									<p style="padding:0px;text-align:left;">
										Votos Individual: <b>'.number_format($value['principal_votos_totales_individual'], 0, '.', ',').'</b><br>
										Votos Coalición Ind: <b>'.number_format($value['principal_votos_totales_individuales_coalicion'], 0, '.', ',').'</b><br>
										Votos Coalición Boletas: <b>'.number_format($value['principal_votos_totales_coalicion'], 0, '.', ',').'</b><br>
										Votos Total: <b>'.number_format($value['principal_votos_totales'], 0, '.', ',').'</b><br>
										Coaliciones: <b>'.$value['principal_nombre_corto_coalicion'].'</b><br>
									</p>
								</div>';
						$div .= '<div class="logo_partido">
									<center>
										<div style="width:100%;padding:2px">
											Partido Primera Fuerza<br>
										</div>
										<img src="images/logos_partidos/'.$value['partido_primera_fuerza_logo'].'" style="width: 40px ">
										<br>
										<b>'.$value['partido_primera_fuerza'].'</b>
									</center>
									<p style="padding:0px;text-align:center;">
										Votos : <b>'.number_format($value['partido_primera_fuerza_votos'], 0, '.', ',').'</b><br>
									</p>
								</div>
								<div class="datos_partido">
									<p style="padding:0px;text-align:left;">
										<center>
											Coalición Primera Fuerza<br>
											<b>'.$value['coalicion_primera_fuerza'].'</b>
										</center>
										<p style="padding:0px;text-align:center;">
											Votos Totales : <b>'.number_format($value['coalicion_primera_fuerza_votos'], 0, '.', ',').'</b><br>
										</p>
									</p>';
									$div .= '<div style="width:100%; text-align:center; display:flex; justify-content:center; flex-wrap:wrap;">';
										foreach ($value['coalicion_primera_fuerza_datos'] as $partido => $datos) {
											if ($partido != 'votos_totales') {
												if ($datos['tipo'] == 1) {
													// Cada div individual
													$div .= '<div style="padding:5px; text-align:center; margin:1px; border:1px solid #ddd; border-radius:5px;">';
													$div .= "<b>" . $partido . " : </b>";
													$div .= number_format($datos['votos'], 0, '.', ',');
													$div .= "</div>";
												}
												if ($datos['tipo'] == 2) {
													// Cada div individual
													$div .= '<div style="padding:5px; text-align:center; margin:1px; border:1px solid #ddd; border-radius:5px;">';
													$div .= "<b> Coalición Boletas : </b>";
													$div .= number_format($datos['votos'], 0, '.', ',');
													$div .= "</div>";
												}
											}
										}
									$div .= "</div>
								</div>";

								$div .= '<div class="logo_partido">
									<center>
										<div style="width:100%;padding:2px">
											Partido Segunda Fuerza<br>
										</div>
										<img src="images/logos_partidos/'.$value['partido_segunda_fuerza_logo'].'" style="width: 40px ">
										<br>
										<b>'.$value['partido_segunda_fuerza'].'</b>
									</center>
									<p style="padding:0px;text-align:center;">
										Votos : <b>'.number_format($value['partido_segunda_fuerza_votos'], 0, '.', ',').'</b><br>
									</p>
								</div>
								<div class="datos_partido">
									<p style="padding:0px;text-align:left;">
										<center>
											Coalición Segunda Fuerza<br>
											<b>'.$value['coalicion_segunda_fuerza'].'</b>
										</center>
										<p style="padding:0px;text-align:center;">
											Votos Totales : <b>'.number_format($value['coalicion_segunda_fuerza_votos'], 0, '.', ',').'</b><br>
										</p>
									</p>';
									$div .= '<div style="width:100%; text-align:center; display:flex; justify-content:center; flex-wrap:wrap;">';
										foreach ($value['coalicion_segunda_fuerza_datos'] as $partido => $datos) {
											if ($partido != 'votos_totales') {
												if ($datos['tipo'] == 1) {
													// Cada div individual
													$div .= '<div style="padding:5px; text-align:center; margin:1px; border:1px solid #ddd; border-radius:5px;">';
													$div .= "<b>" . $partido . " : </b>";
													$div .= number_format($datos['votos'], 0, '.', ',');
													$div .= "</div>";
												}
												if ($datos['tipo'] == 2) {
													// Cada div individual
													$div .= '<div style="padding:5px; text-align:center; margin:1px; border:1px solid #ddd; border-radius:5px;">';
													$div .= "<b> Coalición Boletas : </b>";
													$div .= number_format($datos['votos'], 0, '.', ',');
													$div .= "</div>";
												}
											}
										}
									$div .= "</div>
								</div>";

						$div .= '<div class="datos"> 
									Prog. Gob:<b>'.number_format($value['apoyos_programas'], 0, '.', ',').'</b><br>
									Prog. Inv:<b>'.number_format($value['acciones_obras'], 0, '.', ',').'</b><br>
									Ciudadanos:<b>'.number_format($value['ciudadanos_registrados'], 0, '.', ',').'</b><br>
									Funcionarios:<b>'.number_format($value['funcionarios'], 0, '.', ',').'</b><br>
									Grupo Interes:<b>'.number_format($value['grupos_interes'], 0, '.', ',').'</b><br>
								</div>
								<div class="datos">
									Militantes:<b>'.number_format($value['militantes'], 0, '.', ',').'</b><br>
									<p style="display:none">
									Juntas:<b>'.number_format($value['juntas'], 0, '.', ',').'</b>©
									Visitas:<b>'.number_format($value['visitas'], 0, '.', ',').'</b><br>
									Caminatas:<b>'.number_format($value['caminatas'], 0, '.', ',').'</b><br><br>
									</p>
									Eventos Agenda Gobierno:<b>'.number_format($value['eventos_agenda_gobierno'], 0, '.', ',').'</b><br><br>
									<br>
									<br>
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
						echo $path . " = [";
						
						$coordenadas = [];
						foreach ($valueT as $keyH => $valueH) {
							$coordenadas[] = "{ lat: " . $valueH['latitud'] . ", lng: " . $valueH['longitud'] . " }";
						}
						
						// Cierra el polígono agregando el primer punto nuevamente
						if (!empty($coordenadas)) {
							$coordenadas[] = $coordenadas[0];
						}
						
						echo implode(",", $coordenadas);
						echo "];\n";
						
						$paths .= $path . ",";
					}
					$mostrar_info = false;
					// Determinar los colores basados en el semáforo
					if ($value['seccion_ine_semaforo_individual_'] == "") {
						$strokeColor = 'rgba(4, 2, 2, 0.9)';
						$fillColor = 'rgba(213, 210, 210, 0.2)';
						$strokeWeight = 2;
					} else {
						$strokeWeight = 1;
						if ($tipo_color_poligono == 'coa') {
							$strokeColor = $color;
							$fillColor = $color;
						} else {
							$strokeColor = $color1;
							$fillColor = $color1;
						}
						$mostrar_info = true;
					}
					?>

					secciones_area<?= $value['id'] ?> = new google.maps.Polygon({
						paths: [<?= rtrim($paths, ",") ?>], // Evitar coma extra al final
						strokeColor: "<?= $strokeColor ?>",
						strokeOpacity: 0.8,
						strokeWeight: <?= $strokeWeight ?>,
						fillColor: "<?= $fillColor ?>",
						fillOpacity: 0.35,
						zIndex: 2
					});
					secciones_area<?= $value['id'] ?>.setMap(map);

					<?php
					if($mostrar_info == true){
						?>
						secciones_area<?=  $value['id'] ?>.addListener("click", (function(event){
							myLatlng = new google.maps.LatLng("<?= $value['latitud'] ?>","<?= $value['longitud'] ?>"); 
							infoWindow.setContent('<?= $div ?>');
							infoWindow.setPosition(myLatlng);
							infoWindow.open(map);
						}));
						infoWindow = new google.maps.InfoWindow();
						<?php
					}
					?>
					const label<?=  $key ?> = new google.maps.Marker({
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
			foreach ($datos_distritos_locales as $key => $value) {
				if($value['id'] != $id_distrito_federal){
					echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'numero','".$value['numero'].".png' ],";
				}
			}
			foreach ($secciones_ine_girasDatosArray as $key => $value) {
				echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'gira','".$value['tipo']."'],";
			}
			foreach ($secciones_ine_agendas_gobiernoDatosArray as $keyP => $valueP) {
				foreach ($valueP['locaciones'] as $keyT => $valueT) {
					echo "['".$valueP['id']."', ".$valueT['latitud'].", ".$valueT['longitud'].",'agenda_gob','".$valueT['tipo']."'],";
					$puntos[]=1;
				}
			}
			foreach ($secciones_ine_actividadesDatosArray as $key => $value) {
				echo "['".$value['id']."', ".$value['latitud'].", ".$value['longitud'].",'p_inversion','".$value['tipo']."'],";
			}
			
			?>
			
			];
			///informacion del marcador
			var infoWindowContent = [
				<?php
				foreach ($datos_distritos_locales as $key => $value){
					if($value['id'] != $id_distrito_local){
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
										<h4>Distrito Federal: '.$value['numero'].'</h4>
										<div class="info_titulo">
											<h5>Votación '.$ano.'</h5>
										</div>
										<div class="info_seccion_ganador">
										</div>
										<div class="info_seccion_ganador_button">
											<button class="button button4" onclick="verMasDistritoFederal('.$value['id'].')">Ver Más</button>
										</div>
									</div>
								</div>';
						$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
						?>
						['<?= $div ?>'],
						<?php
					}
				}
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
										<div class="info_seccion_informacion">
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
				foreach ($secciones_ine_agendas_gobiernoDatosArray as $keyKK => $value){
					foreach ($value['locaciones'] as $key => $valueKK) {
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
											<h4>Clave: '.$value['clave'].'</h4>
											<div class="info_titulo">
												<h5>Tipo:</h5>
											</div>
											<div class="info_seccion_informacion">
												<h5>'.mb_strtoupper($value['tipo_gira'], 'UTF-8').'</h5>
											</div>
											<div class="info_seccion_ganador_button" style="display:none">
												<button class="button button4" onclick="edit('.$value['id'].')">Ver Más</button>
											</div>
										</div>
										<div class="datos_top" style="width:100%;">
											Nombre: <b><font style="">'.$value['nombre'].'</font></b><br>
											Eje Gobierno: <b><font style="">'.$value['eje_gobierno'].'</font></b><br>
											Dependencia: <b><font style="">'.$value['dependencia_coordinadora'].'</font></b><br><br>
											Num Asistentes: <b><font style="">'.$value['num_asistentes'].'</font></b><br>
											Num Beneficiario: <b><font style="">'.$value['num_beneficiarios'].'</font></b><br>
										</div>
										<div class="datos_top" style="width:100%;">
											<p>
												Distrito Local(es): <b>'.$value['distrito_local'].'</b><br>
												Distrito Federal(es): <b>'.$value['distrito_federal'].'</b><br>
												Sección(es): <b>'.$value['seccion'].'</b><br>
											</p>
										</div>
										<div class="datos_top" style="width:100%;">
											<p>
												';
												$lastKey = array_key_last($value['locaciones']); // Obtener la última clave del array
												if(!empty($value['locaciones'])){
													$div .= "Evento(s)<br>";
												}
												foreach ($value['locaciones'] as $keyT => $valueT) {
													$fechas = explode(" ", $valueT['fecha_hora']);
													$div .= "<b>".fechaNormalSimpleWDDMMAA_ES($fechas[0])."<br>".$fechas[1]."</b><br>";
													// Agregar <br><br> solo si no es el último elemento
													$div .= "Sección: <b>".$valueT['seccion_ine']."</b><br>";
													$div .= 'Dirección : <b>'.$valueT['calle'].", ".$valueT['colonia'].", ".$valueT['codigo_postal'].", ".$valueT['municipio'].', '.$estado_nombre.' </b>';
													if ($keyT !== $lastKey) {
														$div .= "<br><br>";
													}
												}
											$div .= '
											</p>
										</div>
									</div>';
						$div = preg_replace("/[\r\n|\n|\r]+/", " ", $div);
						?>
						['<?= $div ?>'],
						<?php	# code...
					}
				}
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
										<div class="info_seccion_informacion">
											<h5>'.strtoupper($value['tipo']).'</h5>
										</div>
										<div class="info_seccion_ganador_button" style="display:none">
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
						if(marcadores[i][4] == ''){
							var icon = {
								//url: 'assets/images/iconos/cd-icon-location.png', // url
								url : 'images/iconos_partidos/puntero_junta.png',
								scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
							};
						}
					}else if(marcadores[i][3]=='agenda_gob'){
						var icon = {
							//url: 'assets/images/iconos/cd-icon-location.png', // url
							url : 'images/iconos_partidos/puntero_junta.png',
							scaledSize: new google.maps.Size(42, 42), // scaled size // width, height
						};
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
					foreach ($seccion_ine_datos as $key => $value) {
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
				?>
				<?php
					foreach ($seccion_ine_datos as $key => $value) {
						?>
						// Verificar si los marcadores están dentro de los límites del mapa
						if (bounds.contains(label<?= $key ?>.getPosition())) {
							//console.log(map.getZoom())
							//console.log(map.getZoom())
							if (map.getZoom() >= 10) {
								label<?= $key ?>.setMap(map);
							}else{
								//label<?= $key ?>.setMap(null);
							}
						} else {
							//label<?= $key ?>.setMap(null);
						}
						<?php
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
	