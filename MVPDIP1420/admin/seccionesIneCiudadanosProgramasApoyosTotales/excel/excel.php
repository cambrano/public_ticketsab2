<?php
	ini_set('memory_limit', '5048M');
	ini_set('max_execution_time', 0);
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
	include_once "../../functions/plataformas.php";
	include_once "../../functions/tool_xhpzab.php";
	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 

	$filename = 'ProgramasApoyosCiudadanos_-_'.date("Ymd-His").'-'.$gen_id3.$mk_id.'.xlsx';
	$ruta_ftpExport ='../../ftpFiles/documentosExport/';
	/*
	header('Content-disposition: attachment;   filename="'.XLSXWriter::sanitize_filename('');
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	*/
	$writer = new XLSXWriter();
	$keywords = array('Programas Apoyos Ciudadanos','Data','Información');
	$writer->setTitle('Programas Apoyos Ciudadanos');
	$writer->setSubject('Información de la base de datos');
	$writer->setAuthor('Ideas');
	$writer->setCompany('Ideas');
	$writer->setKeywords($keywords);
	$writer->setDescription('Información de la base de datos');
	$writer->setTempDir(sys_get_temp_dir());//set custom tempdir
	$excel_head = array(
		0 => array('row' => 'clave' ,'nombre' => 'Clave' ,'tipo' => 'string','mostrar' => 1 ),
		1 => array('row' => 'folio' ,'nombre' => 'Folio' ,'tipo' => 'string','mostrar' => 1 ),
		2 => array('row' => 'fecha_hora' ,'nombre' => 'Fecha Hora Registro' ,'tipo' => 'string','mostrar' => 1 ),
		3 => array('row' => 'clave_elector' ,'nombre' => 'Clave Elector' ,'tipo' => 'string','mostrar' => 1 ),
		4 => array('row' => 'curp' ,'nombre' => 'C.U.R.P' ,'tipo' => 'string','mostrar' => 1 ),
		5 => array('row' => 'nombre_completo' ,'nombre' => 'Nombre Completo' ,'tipo' => 'string','mostrar' => 1 ),
		6 => array('row' => 'repetido' ,'nombre' => 'Repetido' ,'tipo' => 'string','mostrar' => 1 ),
		7 => array('row' => 'sexo' ,'nombre' => 'Sexo' ,'tipo' => 'string','mostrar' => 1 ),
		8 => array('row' => 'fecha_nacimiento' ,'nombre' => 'Fecha Nacimiento' ,'tipo' => 'string','mostrar' => 1 ),
		9 => array('row' => 'correo_electronico' ,'nombre' => 'Correo Electrónico' ,'tipo' => 'string','mostrar' => 1 ),
		10 => array('row' => 'telefono' ,'nombre' => 'Teléfono' ,'tipo' => 'string','mostrar' => 1 ),
		11 => array('row' => 'celular' ,'nombre' => 'Celular' ,'tipo' => 'string','mostrar' => 1 ),
		12 => array('row' => 'whatsapp' ,'nombre' => 'Whatsapp' ,'tipo' => 'string','mostrar' => 1 ),
		13 => array('row' => 'colonia' ,'nombre' => 'Colonia' ,'tipo' => 'string','mostrar' => 1 ),
		14 => array('row' => 'localidad' ,'nombre' => 'Localidad' ,'tipo' => 'string','mostrar' => 1 ),
		15 => array('row' => 'seccion' ,'nombre' => 'Sección' ,'tipo' => 'integer','mostrar' => 1 ),
		16 => array('row' => 'distrito_local' ,'nombre' => 'Distrito Local' ,'tipo' => 'integer','mostrar' => 1 ),
		17 => array('row' => 'distrito_federal' ,'nombre' => 'Distrito Federal' ,'tipo' => 'integer','mostrar' => 1 ),
		18 => array('row' => 'observaciones' ,'nombre' => 'Observaciones' ,'tipo' => 'string','mostrar' => 1 ),
	);

	foreach ($excel_head as $key => $value) {
		$header[$value['nombre']]=$value['tipo'];
	}

	$sql = "SELECT
		sicpa.id,
		sicpa.clave,
		sicpa.folio,
		sicpa.fecha_hora,
		sic.clave_elector,
		sic.curp,
		sic.nombre_completo,
		IF((SELECT count(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa WHERE sicpa.id_seccion_ine_ciudadano = sic.id AND sicpa.id_programa_apoyo = '4')>1,'repetido','sin repetir') repetido,
		sic.sexo,
		sic.fecha_nacimiento,
		sic.correo_electronico,
		sic.telefono,
		sic.celular,
		sic.whatsapp,
		sic.colonia,
		(SELECT l.localidad FROM localidades l WHERE l.id = sic.id_localidad) localidad,
		(SELECT si.numero FROM secciones_ine si WHERE si.id = sic.id_seccion_ine) seccion,
		(SELECT dl.numero FROM distritos_locales dl WHERE dl.id = sic.id_distrito_local) distrito_local,
		(SELECT df.numero FROM distritos_federales df WHERE df.id = sic.id_distrito_federal) distrito_federal,
		sicpa.observaciones
	FROM secciones_ine_ciudadanos_programas_apoyos sicpa
	LEFT JOIN secciones_ine_ciudadanos sic
	ON sic.id = sicpa.id_seccion_ine_ciudadano
	WHERE 1 = 1";
	$search_database = $_POST['excel'][0];
	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$sql.=" AND pa.clave LIKE '%{$clave}%' ";
	}
	if($tipo_uso_plataforma=='municipio'){
		$sql.= " AND sic.id_municipio ='{$id_municipio}' ";
	}elseif($tipo_uso_plataforma=='distrito_local'){
		$sql.= " AND sic.id_distrito_local ='{$id_distrito_local}' ";
	}elseif($tipo_uso_plataforma=='distrito_federal'){
		$sql.= " AND sic.id_distrito_federal ='{$id_distrito_federal}' ";
	}
	$id_programa_apoyo = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	if($id_programa_apoyo!=''){
		$sql.=" AND sicpa.id_programa_apoyo = '{$id_programa_apoyo}' ";
	}


	$clave=$search_database['clave'];
	if( $clave!="" ){   //name
		$sql.=" AND sicpa.clave LIKE '%{$clave}%' ";
	} 
	$folio=$search_database['folio'];
	if( $folio!="" ){   //name
		$sql.=" AND sicpa.folio LIKE '%{$folio}%' ";
	}
	$clave_elector=$search_database['clave_elector'];
	if( $clave_elector!="" ){   //name
		$sql.=" AND sic.clave_elector LIKE '%{$clave_elector}%' ";
	} 
	$curp=$search_database['curp'];
	if( $curp!="" ){   //name
		$sql.=" AND sic.curp LIKE '%{$curp}%' ";
	}

	$nombre=$search_database['nombre'];
	if( $nombre!="" ){   //name
		$sql.=" AND sic.nombre LIKE '%{$nombre}%' ";
	}

	$apellido_paterno=$search_database['apellido_paterno'];
	if( $apellido_paterno!="" ){   //name
		$sql.=" AND sic.apellido_paterno LIKE '%{$apellido_paterno}%' ";
	}

	$apellido_materno=$search_database['apellido_materno'];
	if( $apellido_materno!="" ){   //name
		$sql.=" AND sic.apellido_materno LIKE '%{$apellido_materno}%' ";
	}



	$status=$search_database['status'];
	if( $status!="" ){   //name
		$sql.=" AND sic.status LIKE '%{$status}%' ";
	} 

	$repetidos=$search_database['repetidos'];
	if( $repetidos!="" ){   //name
		if($repetidos == 1){
			$sql.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa WHERE sicpa.id_seccion_ine_ciudadano = sic.id AND sicpa.id_programa_apoyo = '{$id_programa_apoyo}'  ) > 1 ";
		}else{
			$sql.=" AND (SELECT count(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa WHERE sicpa.id_seccion_ine_ciudadano = sic.id AND sicpa.id_programa_apoyo = '{$id_programa_apoyo}'  ) = 1 ";
		}
	}
	$decryptedQuery = decrypt_ab_checkSin($_COOKIE['AB32BA51']);


	$numero =1;
	$page = 1;
	$result = $conexion->query($sql.$decryptedQuery.' ;');
	$color_reg = 1;
	while($row=$result->fetch_assoc()){
		if($numero == 300001){
			$numero = 1;
		}
		if($numero==1){
			sleep(1);
			$txt = "Programas Apoyos Ciudadanos Pag - ".$page;
			$page ++;
			$writer->writeSheetHeader($txt, $header, ['auto_filter'=>true, 'fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold'] );
		}
		$numero ++;
		unset($row['id']);
		unset($styleRow);
		$marco = $color_reg % 2;
		foreach ($row as $key => $value) {
			if($marco == 0){
				$styleRow[] = array('fill' => '#e9e9e9','color'=>'#000000','border'=>'left,right,top,bottom');
			}else{
				$styleRow[] = array('color'=>'#000000','border'=>'left,right,top,bottom');
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