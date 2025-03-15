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
	@session_start();
	include_once("../../librerias/excel/xlsxwriter.class.php");
	include_once "../../functions/security.php";
	include_once "../../functions/tool_xhpzab.php";
	include_once "../../functions/plataformas.php";
	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 

	$filename = 'Ciudadanos_Mailing-_'.date("Ymd-His").'-'.$gen_id3.$mk_id.'.xlsx';
	/*
	header('Content-disposition: attachment;   filename="'.XLSXWriter::sanitize_filename('');
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	*/
	$writer = new XLSXWriter();
	$keywords = array('Ciudadanos Mailing','Data','Información');
	$writer->setTitle('Ciudadanos Mailing');
	$writer->setSubject('Información de la base de datos');
	$writer->setAuthor('Ideas');
	$writer->setCompany('Ideas');
	$writer->setKeywords($keywords);
	$writer->setDescription('Información de la base de datos');
	$writer->setTempDir(sys_get_temp_dir());//set custom tempdir
	$excel_head = array(
		0 => array('row' => 'fechaR' ,'nombre' => 'Fecha Registro' ,'tipo' => 'datetime','mostrar' => 1 ),
		1 => array('row' => 'tipo' ,'nombre' => 'Tipo' ,'tipo' => 'string','mostrar' => 1),
		2 => array('row' => 'nombre' ,'nombre' => 'Campaña' ,'tipo' => 'string','mostrar' => 1),
		3 => array('row' => 'nombre_completo' ,'nombre' => 'Ciudadano' ,'tipo' => 'string','mostrar' => 1),
		4 => array('row' => 'correo_electronico' ,'nombre' => 'Correo Electrónico','tipo' => '@','mostrar' => 1),
		5 => array('row' => 'fecha_hora_envio' ,'nombre' => 'Envío' ,'tipo' => 'datetime','mostrar' => 1),
		6 => array('row' => 'fecha_hora_leido' ,'nombre' => 'Leído' ,'tipo' => 'datetime','mostrar' => 1),
		7 => array('row' => 'ip' ,'nombre' => 'IP' ,'tipo' => 'string','mostrar' => 1),
		8 => array('row' => 'municipio' ,'nombre' => 'Municipio' ,'tipo' => 'string','mostrar' => 1),
		9 => array('row' => 'distrito_local' ,'nombre' => 'D. Local' ,'tipo' => 'string','mostrar' => 1),
		10 => array('row' => 'distrito_federal' ,'nombre' => 'D. Federal' ,'tipo' => 'string','mostrar' => 1),
		11 => array('row' => 'seccion' ,'nombre' => 'Sección' ,'tipo' => 'string','mostrar' => 1),
		12 => array('row' => 'loc' ,'nombre' => 'Loc IP' ,'tipo' => 'string','mostrar' => 1),
		13=> array('row' => 'loc_script' ,'nombre' => 'Loc GPS' ,'tipo' => 'string','mostrar' => 1),
		14 => array('row' => 'status' ,'nombre' => 'Estatus' ,'tipo' => 'string','mostrar' => 1), 
	);

	foreach ($excel_head as $key => $value) {
		$header[$value['nombre']]=$value['tipo'];
	}

	$search_database = $_POST['excel']['0'];
	$sql = "
		SELECT 
			siccmp.fechaR,
			siccmp.id,
			siccmp.loc,
			siccmp.loc_script,
			CASE
				WHEN siccmp.tipo = 1 THEN 'bienvenida'
				WHEN siccmp.tipo = 3 THEN 'encuesta'
				ELSE 'programada'
			END tipo,

			(SELECT sic.nombre_completo FROM secciones_ine_ciudadanos sic WHERE sic.id= siccmp.id_seccion_ine_ciudadano) nombre_completo,
			(SELECT sic.correo_electronico FROM secciones_ine_ciudadanos sic WHERE sic.id= siccmp.id_seccion_ine_ciudadano) correo_electronico,
			(SELECT cm.nombre FROM campanas_mailing cm WHERE cm.id= siccmp.id_campana_mailing) nombre,
			siccmp.fecha_hora_envio,
			siccmp.fecha_hora_leido,
			siccmp.ip,
			(SELECT s.numero FROM secciones_ine s WHERE s.id =siccmp.id_seccion_ine) seccion,
			(SELECT dl.numero FROM distritos_locales dl WHERE dl.id =siccmp.id_distrito_local) distrito_local,
			(SELECT df.numero FROM distritos_federales df WHERE df.id =siccmp.id_distrito_federal) distrito_federal,
			(SELECT m.municipio FROM municipios m WHERE m.id =siccmp.id_municipio) municipio,
			CASE
				WHEN siccmp.status = 1 THEN 'Enviado'
				WHEN siccmp.status = 2 THEN 'No Enviado'
				WHEN siccmp.status = 3 THEN 'Leido'
				WHEN siccmp.status = 4 THEN 'Cancelado'
				ELSE 'pendiente'
			END status,

			siccmp.status status_check
		FROM secciones_ine_ciudadanos_campanas_mailing_programadas siccmp WHERE 1 = 1  "; 
		if($tipo_uso_plataforma=='municipio'){
			$sql.= " AND siccmp.id_municipio ='{$id_municipio}' ";
		}elseif($tipo_uso_plataforma=='distrito_local'){
			$sql.= " AND siccmp.id_distrito_local ='{$id_distrito_local}' ";
		}elseif($tipo_uso_plataforma=='distrito_federal'){
			$sql.= " AND siccmp.id_distrito_federal ='{$id_distrito_federal}' ";
		}
	// getting records as per search parameters


	$status=$search_database['status'];
	if( $status!="" ){   //name
		$sql.=" AND siccmp.status = '{$status}' ";
	}

	$tipo=$search_database['tipo'];
	if( $tipo!="" ){   //name
		$sql.=" AND siccmp.tipo = '{$tipo}' ";
	}

	$id_seccion_ine=$search_database['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$sql.=" AND siccmp.id_seccion_ine IN ($id_seccion_ine) ";
	}

	$decryptedQuery = decrypt_ab_checkSin($_COOKIE['AB32BA51']);
	///solo se sube 1 para el valor
	$numero =1;
	$page = 1;
	$result = $conexion->query($sql.$decryptedQuery.' ;');
	while($row=$result->fetch_assoc()){
		if($numero == 300001){
			$numero = 1;
		}
		if($numero==1){
			sleep(1);
			$txt = "Ciudadanos Pag - ".$page;
			$page ++;
			$writer->writeSheetHeader($txt, $header, ['auto_filter'=>true, 'fill'=>'#397cb5','color'=>'#FFFFFF','font-style'=>'bold'] );
		}
		$numero ++;
		$writer->writeSheetRow($txt, $row);
	}
	$writer->writeToFile($filename); 
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
	<a href="<?= $filename ?>" download="<?= $filename ?>"><?= $filename ?></a>
	<script type="text/javascript">
		document.getElementById("loader").style.display = "none";
		$('#text_sub').html('De click al link. Gracias');
		document.getElementById("stop").value=1;
		document.title = "Listo. descargelo";
	</script>