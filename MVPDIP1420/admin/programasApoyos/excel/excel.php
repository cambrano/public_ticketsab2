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
	include_once "../../functions/tool_xhpzab.php";
	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 

	$filename = 'ProgramasApoyos_-_'.date("Ymd-His").'-'.$gen_id3.$mk_id.'.xlsx';
	$ruta_ftpExport ='../../ftpFiles/documentosExport/';
	/*
	header('Content-disposition: attachment;   filename="'.XLSXWriter::sanitize_filename('');
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	*/
	$writer = new XLSXWriter();
	$keywords = array('Programas Apoyos','Data','Información');
	$writer->setTitle('Programas Apoyos');
	$writer->setSubject('Información de la base de datos');
	$writer->setAuthor('Ideas');
	$writer->setCompany('Ideas');
	$writer->setKeywords($keywords);
	$writer->setDescription('Información de la base de datos');
	$writer->setTempDir(sys_get_temp_dir());//set custom tempdir
	$excel_head = array(
		0 => array('row' => 'clave' ,'nombre' => 'Clave' ,'tipo' => 'string','mostrar' => 1 ),
		1 => array('row' => 'folio' ,'nombre' => 'Folio' ,'tipo' => 'string','mostrar' => 1 ),
		2 => array('row' => 'nombre' ,'nombre' => 'Nombre' ,'tipo' => 'string','mostrar' => 1 ),
		3 => array('row' => 'fecha_inicio' ,'nombre' => 'Fecha Inicio' ,'tipo' => 'date','mostrar' => 1 ),
		4 => array('row' => 'fecha_final' ,'nombre' => 'Fecha Final' ,'tipo' => 'date','mostrar' => 1 ),
		5 => array('row' => 'total_beneficiados' ,'nombre' => 'Total Beneficaidos' ,'tipo' => 'integer','mostrar' => 1 ),
		6 => array('row' => 'tipos_territorios' ,'nombre' => 'Tipos Territorios' ,'tipo' => 'string','mostrar' => 1 ),
		7 => array('row' => 'dependencias' ,'nombre' => 'Dependencias' ,'tipo' => 'string','mostrar' => 1 ),
		8 => array('row' => 'descripcion' ,'nombre' => 'Descripcion' ,'tipo' => 'string','mostrar' => 0 ),
	);

	foreach ($excel_head as $key => $value) {
		$header[$value['nombre']]=$value['tipo'];
	}

	$sql = "SELECT 
		pa.id,
		pa.clave,
		pa.folio,
		pa.nombre,
		pa.fecha_inicio,
		pa.fecha_final,
		(SELECT COUNT(*) FROM secciones_ine_ciudadanos_programas_apoyos sicpa LEFT JOIN secciones_ine_ciudadanos sic ON sicpa.id_seccion_ine_ciudadano = sic.id WHERE sicpa.id_programa_apoyo = pa.id {$sql_ciudadano} ) total_beneficiados,
		(SELECT GROUP_CONCAT(tt.nombre SEPARATOR ' | ') FROM programas_apoyos_territorios pat LEFT JOIN tipos_territorios tt ON pat.id_tipo_territorio = tt.id WHERE pat.id_programa_apoyo = pa.id) tipos_territorios,
		(SELECT GROUP_CONCAT(d.nombre SEPARATOR ' | ') FROM programas_apoyos_dependencias pad LEFT JOIN dependencias d ON pad.id_dependencia = d.id WHERE pad.id_programa_apoyo = pa.id) dependencias,
		pa.descripcion
	FROM programas_apoyos pa
	WHERE 1 = 1 ";
	$clave=$_POST['excel'][0]['clave'];
	if( $clave!="" ){   //name
		$post_search=true;
		$sql.=" AND pa.clave LIKE '%{$clave}%' ";
		$sqlContador .= " AND pa.clave LIKE '%{$clave}%' ";
	}
	$folio=$_POST['excel'][0]['folio'];
	if( $folio!="" ){   //name
		$post_search=true;
		$sql.=" AND pa.folio LIKE '%{$folio}%' ";
		$sqlContador .= " AND pa.folio LIKE '%{$folio}%' ";
	}
	$nombre=$_POST['excel'][0]['nombre'];
	if( $nombre!="" ){   //name
		$post_search=true;
		$sql.=" AND pa.nombre LIKE '%{$nombre}%' ";
		$sqlContador .= " AND pa.nombre LIKE '%{$nombre}%' ";
	}
	$id_tipo_territorio=$_POST['excel'][0]['id_tipo_territorio'];
	if( $id_tipo_territorio!="" ){
		$post_search=true;
		$sql.=" AND EXISTS (SELECT * FROM programas_apoyos_territorios pat WHERE pat.id_programa_apoyo = pa.id AND pat.id_tipo_territorio IN ({$id_tipo_territorio}) ) ";
	}
	$id_dependencia=$_POST['excel'][0]['id_dependencia'];
	if( $id_dependencia!="" ){
		$post_search=true;
		$sql.=" AND EXISTS (SELECT * FROM programas_apoyos_dependencias pat WHERE pat.id_programa_apoyo = pa.id AND pat.id_dependencia IN ({$id_dependencia}) ) ";
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
			$txt = "Programas Apoyos Pag - ".$page;
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