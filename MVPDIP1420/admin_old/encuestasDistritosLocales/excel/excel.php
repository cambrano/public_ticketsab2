<?php
	@session_start();
	$pageService=$_GET['cot'];
	$_COOKIE['pageService'];
	if($pageService=="" || $_COOKIE['pageService'] != $pageService ){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
		die;
	}else{
		$_COOKIE['pageService'];
	}
	ini_set('memory_limit', '5048M');
	ini_set('max_execution_time', 0);
	$start_time = microtime(true);

	include_once "../../functions/security.php";
	include_once("../../librerias/excel/xlsxwriter.class.php");
	/*
	$pageService = $_GET['cot'];
	$_SESSION['pageService'];
	if($pageService=="" || $_SESSION['pageService'] != $pageService ){
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
	}else{
		$_SESSION['pageService'];
	}
	*/ 

	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789'), 0, $length); 
	$filename = 'Ciudadanos_Encuetas_Distritos_Locales_-_'.date("Ymd-His").'-'.$gen_id3.$mk_id.'.xlsx';
	$ruta_ftpExport ='../../ftpFiles/documentosExport/';

	//preguntas
	
	$writer = new XLSXWriter();
	$keywords = array('Ciudadanos Encuestas Distritos Locales','Data','Información');
	$writer->setTitle('Ciudadanos Encuestas Distritos Locales');
	$writer->setSubject('Información de la base de datos');
	$writer->setAuthor('Ideas');
	$writer->setCompany('Ideas');
	$writer->setKeywords($keywords);
	$writer->setDescription('Información de la base de datos');
	$writer->setTempDir(sys_get_temp_dir());//set custom tempdir

	$numero =1;
	$page = 1;
	$_SESSION['reporte_Sistema']['sql'];
	$result = $conexion->query($_SESSION['reporte_Sistema']['sql'].' ');
	$color_reg = 1;
	while($row=$result->fetch_assoc()){
		if($numero == 300001){
			$numero = 1;
		}
		if($numero==1){ 
			$txt = "Ciudadanos Encuestas Pag - ".$page;
			$page ++;
			$writer->writeSheetHeader($txt, $_SESSION['reporte_Sistema']['preguntas'], [ 'fill'=>'#397cb5','color'=>'#FFFFFF','border'=>'left,right,top,bottom','border-style'=>'dash dot','font-style'=>'bold','valign'=>'center','halign'=>'center'], );
			foreach ($_SESSION['reporte_Sistema']['markMergedCell'] as $key => $value) {
				$writer->markMergedCell($txt, $start_row = 0, $start_col = $value['inicio'], $end_row = 0, $end_col = $value['fin']);
			}
			$writer->writeSheetRow($txt, $_SESSION['reporte_Sistema']['respuestas'],[ 'fill'=>'#78ab78','color'=>'#000000','border'=>'left,right,top,bottom','border-style'=>'think ','font-style'=>'bold']);
			$fin = $value['fin']+1;
			$writer->markMergedCell($txt, $start_row = 0, $start_col = 0, $end_row = 1, $end_col = 0);
			$writer->markMergedCell($txt, $start_row = 0, $start_col = 1, $end_row = 1, $end_col = 1);
			$writer->markMergedCell($txt, $start_row = 0, $start_col = 2, $end_row = 1, $end_col = 2);
			$writer->markMergedCell($txt, $start_row = 0, $start_col = 3, $end_row = 1, $end_col = 3);
			$writer->markMergedCell($txt, $start_row = 0, $start_col = 4, $end_row = 1, $end_col = 4);
			$writer->markMergedCell($txt, $start_row = 0, $start_col = 5, $end_row = 1, $end_col = 5);
			$writer->markMergedCell($txt, $start_row = 0, $start_col = 6, $end_row = 1, $end_col = 6);
			$writer->markMergedCell($txt, $start_row = 0, $start_col = 7, $end_row = 1, $end_col = 7);
			$writer->markMergedCell($txt, $start_row = 0, $start_col = $fin, $end_row = 1, $end_col = $fin);
		}
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
		$numero ++;
	}
	$writer->writeToFile($ruta_ftpExport.$filename);

	//$writer->writeToStdOut();
	echo '#'.floor((memory_get_peak_usage())/1024/1024).'MB';
	//exit();

	$end_time = microtime(true);
	$duration = $end_time - $start_time;
	$minutes = (int)($duration/60)-$hours*60;
	$seconds = (int)$duration-$hours*60*60-$minutes*60; 
	$tiempo =  'Tiempo para generar el archivo: <strong>' .$minutes.' minutos y '.$seconds.' segundos.</strong>';
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