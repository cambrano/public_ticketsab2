<?php
	@session_start();
	ini_set('max_execution_time', 600); // Aumenta el tiempo de ejecución a 600 segundos
	ini_set('memory_limit', '256M');   // Aumenta el límite de memoria a 256 megabytes
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
	@session_start();
	include_once("../../librerias/excel/xlsxwriter.class.php");
	include_once "../../functions/security.php";
	include_once "../../functions/tool_xhpzab.php";
	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 

	$ruta_ftpExport ='../../ftpFiles/documentosExport/';
	$filename = 'Ciudadanos_checks_-_'.date("Ymd-His").'-'.$gen_id3.$mk_id.'.xlsx';
	/*
	header('Content-disposition: attachment;   filename="'.XLSXWriter::sanitize_filename('');
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	*/
	$writer = new XLSXWriter();
	$keywords = array('Ciudadanos','Data','Información');
	$writer->setTitle('Ciudadanos');
	$writer->setSubject('Información de la base de datos');
	$writer->setAuthor('Ideas');
	$writer->setCompany('Ideas');
	$writer->setKeywords($keywords);
	$writer->setDescription('Información de la base de datos');
	$writer->setTempDir(sys_get_temp_dir());//set custom tempdir
	$columa = array(
		0 => array('row' => 'clave' ,'nombre' => 'Clave' ,'tipo' => 'string','mostrar' => 1 ),
		1 => array('row' => 'seccion' ,'nombre' => 'Sección' ,'tipo' => 'string','mostrar' => 1),
		2 => array('row' => 'distancia_km' ,'nombre' => 'D.(km) Aprox' ,'tipo' => 'string','mostrar' => 1),
		3 => array('row' => 'relacionado' ,'nombre' => 'Relacionado' ,'tipo' => 'string','mostrar' => 1),
		4 => array('row' => 'nombre_completo' ,'nombre' => 'Nombre Completo','tipo' => 'string','mostrar' => 1),
		5 => array('row' => 'check_out_date_hora' ,'nombre' => 'C. OUT' ,'tipo' => 'string','mostrar' => 1),
		6 => array('row' => 'date_nacimiento' ,'nombre' => 'F. Nacimiento' ,'tipo' => 'date','mostrar' => 1),
		7 => array('row' => 'whatsapp' ,'nombre' => 'Whatsapp' ,'tipo' => 'string','mostrar' => 1),
		8 => array('row' => 'telefono' ,'nombre' => 'Teléfono' ,'tipo' => 'string','mostrar' => 1),
		9 => array('row' => 'celular' ,'nombre' => 'Celular' ,'tipo' => 'string','mostrar' => 1),
		10 => array('row' => 'calle' ,'nombre' => 'Calle' ,'tipo' => 'string','mostrar' => 1),
		11 => array('row' => 'colonia' ,'nombre' => 'Colonia' ,'tipo' => 'string','mostrar' => 1),
		12 => array('row' => 'latitud' ,'nombre' => 'Latitud' ,'tipo' => 'string','mostrar' => 1),
		13 => array('row' => 'longitud' ,'nombre' => 'Longitud' ,'tipo' => 'string','mostrar' => 1),
	);
	foreach ($columa as $key => $value) {
		$header[$value['nombre']]=$value['tipo'];
	}

	$search_database = $_POST['excel']['0'];

	$sql = "SELECT
				e.clave,
				si.numero AS seccion,
				e.distancia_km,
				sim.nombre_completo AS relacionado,
				e.nombre_completo,
				sicc2024.check_out_hora,
				e.fecha_nacimiento,
				e.whatsapp,
				e.telefono,
				e.celular,
				e.calle,
				e.colonia,
				e.latitud,
				e.longitud
			FROM
				secciones_ine_ciudadanos e
			LEFT JOIN
				secciones_ine si ON e.id_seccion_ine = si.id
			LEFT JOIN
				secciones_ine_ciudadanos sim ON e.id_seccion_ine_ciudadano_compartido = sim.id
			LEFT JOIN
				secciones_ine_ciudadanos_check_2024 sicc2024 ON sicc2024.id_seccion_ine_ciudadano = e.id
			WHERE
				1 = 1
"; 
	// getting records as per search parameters
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$sql.=" AND e.clave LIKE '%{$clave}%' ";
	} 

	$nombre_completo=$search_database['nombre_completo'];
	if( $nombre_completo!="" ){   //name
		$sql.=" AND e.nombre_completo LIKE '%{$nombre_completo}%' ";
	}

	$id_seccion_ine_ciudadano_compartido=$search_database['id_seccion_ine_ciudadano_compartido'];
	if( $id_seccion_ine_ciudadano_compartido!="" ){   //name
		$sql.=" AND e.id_seccion_ine_ciudadano_compartido = '{$id_seccion_ine_ciudadano_compartido}' ";
	}


	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$sql.=" AND e.id_seccion_ine IN ($id_seccion_ine) ";
	}
	$id_cuartel=$search_database['id_cuartel'];
	if( $id_cuartel!="" ){
		$sql.=" AND e.id_cuartel IN ($id_cuartel) ";
	}

	$id_municipio=$search_database['id_municipio'];
	if( $id_municipio!="" ){
		$sql.=" AND e.id_municipio IN ($id_municipio) ";
	}
	$id_localidad=$search_database['id_localidad'];
	if( $id_localidad!="" ){
		$sql.=" AND e.id_localidad IN ($id_localidad) ";
	}
	$id_distrito_local=$search_database['id_distrito_local'];
	if( $id_distrito_local!="" ){
		$sql.=" AND e.id_distrito_local IN ($id_distrito_local) ";
	}
	$id_distrito_federal=$search_database['id_distrito_federal'];
	if( $id_distrito_federal!="" ){
		$sql.=" AND e.id_distrito_federal IN ($id_distrito_federal) ";
	}

	$checks=$search_database['checks'];
	$porciones = explode(",", $checks);
	$cont=1;
	$tipo_check = false;
	$sqlx='';
	foreach ($porciones as $keyT => $valueT) {
		if($valueT==1){
			$sqlx.=" AND EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id AND check_in = 1 )";
		}
		if($valueT==2){
			$tipo_check = true;
			if(count($porciones)>1){
				$sqlx.=" OR EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id AND check_out = 1 )";
			}else{
				$sqlx.=" AND EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id AND check_out = 1 )";
			}
		}

		if($valueT==3){
			$tipo_check = true;
			if(count($porciones)>1){
				$sqlx.=" OR EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id AND sicc2024.check_out = 1 AND sicc2024.check_in = 1  )";
			}else{
				$sqlx.=" AND EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id AND sicc2024.check_out = 1 AND sicc2024.check_in = 1  )";
			}
		}

		if($valueT==4){
			$tipo_check = true;
			if(count($porciones)>1){
				$sqlx.=" OR NOT EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id  )";
			}else{
				$sqlx.=" AND NOT EXISTS  (SELECT * FROM secciones_ine_ciudadanos_check_2024 sicc2024 WHERE sicc2024.id_seccion_ine_ciudadano = e.id  )";
			}
		}		
	}
	$sql.= $sqlx;
	$decryptedQuery = decrypt_ab_checkSin($_COOKIE['AB32BA51']);



	$numero =1;
	$page = 1;
	$result = $conexion->query($sql.$decryptedQuery);
	while($row=$result->fetch_assoc()){
		if($numero == 300001){
			$numero = 1;
		}
		if($numero==1){
			sleep(1);
			$txt = "Pag - ".$page;
			$page ++;
			$writer->writeSheetHeader($txt, $header, ['auto_filter'=>true, 'fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold'] );
		}
		$numero ++;
		$writer->writeSheetRow($txt, $row);
	}
	$writer->writeToFile($ruta_ftpExport.$filename);
	//$writer->writeToStdOut();
	echo 'RAM #'.floor((memory_get_peak_usage())/1024/1024)."MB"."\n";
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
		document.title = "Listo. descargelo";
	</script>