<?php
	@session_start();
	$pageService=$_POST['pageService'];
	if($pageService=="" || $_POST['pageService'] != $pageService ){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
		die;
	}else{
		$_COOKIE['pageService'];
	}
	$start_time = microtime(true);
	include_once("../../librerias/excel/xlsxwriter.class.php");
	include_once "../../functions/security.php";
	include_once "../../functions/tool_xhpzab.php";
	include_once "../../functions/plataformas.php";
	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 

	$filename = 'Agenda_Gobierno_-_'.date("Ymd-His").'-'.$gen_id3.$mk_id.'.xlsx';
	$ruta_ftpExport ='../../ftpFiles/documentosExport/';
	/*
	header('Content-disposition: attachment;   filename="'.XLSXWriter::sanitize_filename('');
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	*/
	$writer = new XLSXWriter();
	$keywords = array('Agenda de Gobierno','Data','Información');
	$writer->setTitle('Agenda');
	$writer->setSubject('Información de la base de datos');
	$writer->setAuthor('Ideas');
	$writer->setCompany('Ideas');
	$writer->setKeywords($keywords);
	$writer->setDescription('Información de la base de datos');
	$writer->setTempDir(sys_get_temp_dir());//set custom tempdir
	$excel_head = array(
		0 => array('row' => 'clave' ,'nombre' => 'Clave' ,'tipo' => 'string','mostrar' => 1 ),
		1 => array('row' => 'tipo_gira' ,'nombre' => 'Tipo de Agenda' ,'tipo' => 'string','mostrar' => 1 ),
		2 => array('row' => 'dependencia_coordinadora' ,'nombre' => 'Dependencia Coordinadora' ,'tipo' => 'string','mostrar' => 1 ),
		3 => array('row' => 'eje_gobierno' ,'nombre' => 'Eje Gobierno' ,'tipo' => 'string','mostrar' => 1 ),
		4 => array('row' => 'nombre' ,'nombre' => 'Nombre' ,'tipo' => 'string','mostrar' => 1 ),
		5 => array('row' => 'fecha_hora' ,'nombre' => 'Fecha Hora' ,'tipo' => 'string','mostrar' => 1 ),
		6 => array('row' => 'num_beneficiarios' ,'nombre' => 'Num Beneficiarios' ,'tipo' => 'number','mostrar' => 1 ),
		7 => array('row' => 'num_asistentes' ,'nombre' => 'Num Asistentes' ,'tipo' => 'number','mostrar' => 1 ),
		
		/*4 => array('row' => 'dependencias_colaborativas' ,'nombre' => 'Dependencia Colaborativas' ,'tipo' => 'string','mostrar' => 1 ),*/
		
		
		8 => array('row' => 'observaciones' ,'nombre' => 'Observaciones' ,'tipo' => 'string','mostrar' => 1 ),
		9 => array('row' => 'municipio' ,'nombre' => 'Municipio' ,'tipo' => 'string','mostrar' => 1 ),
		10 => array('row' => 'localidad' ,'nombre' => 'Localidad' ,'tipo' => 'string','mostrar' => 1 ),
		11 => array('row' => 'colonia' ,'nombre' => 'Colonia' ,'tipo' => 'string','mostrar' => 1 ), 
		12 => array('row' => 'seccion' ,'nombre' => 'Sección' ,'tipo' => 'string','mostrar' => 1 ),
		13 => array('row' => 'distrito_local' ,'nombre' => 'Distrito Local' ,'tipo' => 'string','mostrar' => 1 ),
		14 => array('row' => 'distrito_federal' ,'nombre' => 'Distrito Federal' ,'tipo' => 'string','mostrar' => 1 ),
	);

	foreach ($excel_head as $key => $value) {
		$header[$value['nombre']]=$value['tipo'];
	}

	$search_database = $_POST['excel']['0'];
	$sql = "
		SELECT
			sia.clave,
			(SELECT d.nombre FROM tipos_giras d WHERE d.id = sia.id_tipo_gira) tipo_gira,
			(SELECT d.nombre FROM dependencias d WHERE d.id = sia.id_dependencia) dependencia_coordinadora,
			(SELECT e.nombre FROM ejes_gobierno e WHERE e.id = sia.id_eje_gobierno ) eje_gobierno,
			sia.nombre,
			(
				SELECT GROUP_CONCAT(DISTINCT loc.fecha_hora ORDER BY loc.fecha_hora SEPARATOR '\n') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS fecha_hora,
			sia.num_beneficiarios,
			sia.num_asistentes,
			sia.observaciones,
			/*(
				SELECT GROUP_CONCAT(d.nombre ORDER BY d.id SEPARATOR ',<br><br> ') 
				FROM dependencias d 
				WHERE FIND_IN_SET(d.id, sia.ids_dependencias) > 0
			) AS dependencias_colaborativas,*/
			(
				SELECT GROUP_CONCAT(DISTINCT m.municipio ORDER BY m.municipio SEPARATOR '\n') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				LEFT JOIN municipios m
				ON loc.id_municipio = m.id
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS municipio,
			(
				SELECT GROUP_CONCAT(DISTINCT l.localidad ORDER BY l.localidad SEPARATOR '\n') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				LEFT JOIN localidades l
				ON loc.id_localidad = l.id
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS localidad,
			
			
			(
				SELECT GROUP_CONCAT(DISTINCT loc.colonia ORDER BY loc.colonia SEPARATOR '\n') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS colonia,
			(
				SELECT GROUP_CONCAT(DISTINCT s.numero ORDER BY s.numero SEPARATOR '\n') 
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				LEFT JOIN secciones_ine s
				ON loc.id_seccion_ine = s.id
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS seccion,
			(
				SELECT GROUP_CONCAT(DISTINCT loc.id_distrito_local ORDER BY loc.id_distrito_local SEPARATOR '\n')  
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS distrito_local,
			(
				SELECT GROUP_CONCAT(DISTINCT loc.id_distrito_federal ORDER BY loc.id_distrito_federal SEPARATOR '\n')  
				FROM secciones_ine_agendas_gobierno_locaciones loc 
				WHERE loc.id_seccion_ine_agenda_gobierno = sia.id
			) AS distrito_federal
		FROM secciones_ine_agendas_gobierno sia
		WHERE 1 = 1  ";
	// getting records as per search parameters
	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_municipio = $id_municipio )";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_distrito_local = $id_distrito_local )";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_distrito_federal = $id_distrito_federal )";
	}
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$sql.=" AND sia.clave LIKE '%{$clave}%' ";
	} 

	$folio=$search_database['folio'];
	if( $folio!="" ){   //name
		$sql.=" AND sia.folio LIKE '%{$folio}%' ";
	}

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$sql.=" AND sia.nombre LIKE '%{$nombre}%' ";
	} 

	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_seccion_ine IN ($id_seccion_ine) )";
	}

	$tipo=$search_database['tipo'];
	$tipo = str_replace('\\', '', $tipo);
	//$porciones = explode(",", $tipo);
	//$values_pdo = "'".implode("','", $porciones)."'";
	if( $tipo!="" ){   //name
		$sql.=" AND sia.tipo IN ($tipo) ";
	}

	$id_tipo_gira=$search_database['id_tipo_gira'];
	$id_tipo_gira = str_replace('\\', '', $id_tipo_gira);
	//$porciones = explode(",", $tipo);
	//$values_pdo = "'".implode("','", $porciones)."'";
	if( $id_tipo_gira!="" ){   //name
		$post_search=true;
		$sql.=" AND sia.id_tipo_gira IN ($id_tipo_gira) ";
	}

	$fecha_1=$search_database['fecha_1'];
	$fecha_2=$search_database['fecha_2'];
	if( $fecha_1 != '' && $fecha_2 == ''){ 
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.fecha <= '{$fecha_1}' )";
	}

	if( $fecha_1 == '' && $fecha_2 != ''){ 
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.fecha  >= '{$fecha_2}' )";
	}
	if( $fecha_1 != '' && $fecha_2 != ''){ 
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.fecha BETWEEN '{$fecha_1}' AND '{$fecha_2}' )";
	}


	$id_municipio=$search_database['id_municipio'];
	if( $id_municipio!="" ){   //name
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_municipio IN ({$id_municipio}) )";
	}

	$id_localidad=$search_database['id_localidad'];
	if( $id_localidad!="" ){   //name
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_localidad IN ({$id_localidad}) )";
	}

	$id_distrito_local=$search_database['id_distrito_local'];
	if( $id_distrito_local!="" ){   //name
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_distrito_local IN ({$id_distrito_local}) )";

	}

	$id_distrito_federal=$search_database['id_distrito_federal'];
	if( $id_distrito_federal!="" ){   //name
		$sql.= " AND EXISTS ( SELECT * FROM secciones_ine_agendas_gobierno_locaciones siagl WHERE siagl.id_seccion_ine_agenda_gobierno = sia.id AND siagl.id_distrito_federal IN ({$id_distrito_federal}) )";
	}
	$decryptedQuery = decrypt_ab_checkSin($_COOKIE['AB32BA51']);
	//echo "<pre>";
	//echo $sql.$decryptedQuery;
	//echo "</pre>";
	//die;
	$numero =1;
	$page = 1;
	$result = $conexion->query($sql.$decryptedQuery.' ;');
	$color_reg = 1;

	$alias_array = array('observaciones','fecha_hora','municipio','localidad','colonia','seccion','distrito_local','distrito_federal');
	while($row=$result->fetch_assoc()){
		if($numero == 300001){
			$numero = 1;
		}
		if($numero==1){
			sleep(1);
			$txt = "Agenda Pag - ".$page;
			$page ++;
			$writer->writeSheetHeader($txt, $header, [
				'auto_filter' => true,             // Activa los filtros automáticos en las columnas.
				'fill' => '#397cb5',               // Aplica un color de fondo (azul).
				'color' => '#FFFFFF',              // Aplica color al texto (blanco).
				'font-style' => 'bold',            // Establece el texto en negrita.
				'widths' => array_fill(0, count($header), 70) // Define un ancho estándar de 20 para cada columna.
			]);
			
		}
		$numero ++;
		unset($row['id']);
		unset($styleRow);
		$marco = $color_reg % 2;
		foreach ($row as $key => $value) {
			if (in_array($key, $alias_array)) {
				if($marco == 0){
					$styleRow[] = array('fill' => '#e9e9e9','color'=>'#000000','border'=>'left,right,top,bottom','halign' => 'left','valign' => 'center','wrap_text' => true);
				}else{
					$styleRow[] = array('color'=>'#000000','border'=>'left,right,top,bottom','halign' => 'left','valign' => 'center','wrap_text' => true);
				}
			}else{
				if($marco == 0){
					$styleRow[] = array('fill' => '#e9e9e9','color'=>'#000000','border'=>'left,right,top,bottom','halign' => 'left','valign' => 'center');
				}else{
					$styleRow[] = array('color'=>'#000000','border'=>'left,right,top,bottom','halign' => 'left','valign' => 'center');
				}
			}
		}
		$color_reg ++;

		$writer->writeSheetRow($txt, $row,$styleRow);
	}
	$writer->writeToFile($ruta_ftpExport.$filename);
	//$writer->writeToStdOut();
	//echo '#'.floor((memory_get_peak_usage())/1024/1024)."MB"."\n";
	//exit();
	$end_time = microtime(true);
	$duration = $end_time - $start_time;
	$minutes = (int)($duration/60)-$hours*60;
	$seconds = (int)$duration-$hours*60*60-$minutes*60; 
	$tiempo =  "Tiempo para generar el archivo: <strong>" .$minutes.' minutos y '.$seconds.' segundos.</strong>';
?>
	<?= $tiempo ?>
	<br>
	<a href="<?= $ruta_ftpExport.$filename ?>" download="<?= $filename ?>"><?= $filename ?></a>
	<script type="text/javascript">
		document.getElementById("loader").style.display = "none";
		$('#text_sub').html('De click al link. Gracias');
		document.getElementById("stop").value=1;
		document.title = "Listo. descargelo";
	</script>