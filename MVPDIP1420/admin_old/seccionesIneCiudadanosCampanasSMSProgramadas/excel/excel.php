<?php
	$pageService=$_GET['cot'];
	if($pageService=="" && $_SESSION['pageService'] != $pageService ){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
	}else{
		$_SESSION['pageService'];
	}
	$start_time = microtime(true);
	@session_start();

	include_once("../../librerias/excel/xlsxwriter.class.php");
	include_once "../../functions/security.php";
	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 

	$filename = 'Ciudadanos_SMS-_'.date("Ymd-His").'-'.$gen_id3.$mk_id.'.xlsx';
	/*
	header('Content-disposition: attachment;   filename="'.XLSXWriter::sanitize_filename('');
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	*/
	$writer = new XLSXWriter();
	$keywords = array('Ciudadanos SMS','Data','Información');
	$writer->setTitle('Ciudadanos SMS');
	$writer->setSubject('Información de la base de datos');
	$writer->setAuthor('Ideas');
	$writer->setCompany('Ideas');
	$writer->setKeywords($keywords);
	$writer->setDescription('Información de la base de datos');
	$writer->setTempDir(sys_get_temp_dir());//set custom tempdir
	foreach ($_SESSION['reporte_Sistema']['columnas_nombres'] as $key => $value) {
		$header[$value['nombre']]=$value['tipo'];
	}
	///solo se sube 1 para el valor
	$numero =1;
	$page = 1;
	$result = $conexion->query($_SESSION['reporte_Sistema']['sql']);
	while($row=$result->fetch_assoc()){
		if($numero == 300001){
			$numero = 1;
		}
		if($numero==1){
			sleep(1);
			$txt = "Ciudadanos SMS Pag - ".$page;
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