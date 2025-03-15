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
	$start_time = microtime(true);
	include_once("../../../librerias/excel/xlsxwriter.class.php");
	include_once "../../../functions/security.php";
	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 

	$datos_generales = $_SESSION['reporte_Sistema']['pdf']['datos_generales'];
	$datos_generales['territorio_nombre'];
	$filename = 'MatrizRentabilidadDistritoLocal_'.$datos_generales['territorio_nombre'].'_-_'.date("Ymd-His").'-'.$gen_id3.$mk_id.'.xlsx';
	$ruta_ftpExport ='../../../ftpFiles/documentosExport/';
	/*
	header('Content-disposition: attachment;   filename="'.XLSXWriter::sanitize_filename('');
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	*/
	$writer = new XLSXWriter();
	$keywords = array('Matriz de Rentabilidad Municipio','Data','Información');
	$writer->setTitle('Matriz de Rentabilidad Municipio');
	$writer->setSubject('Información de la base de datos');
	$writer->setAuthor('Ideas');
	$writer->setCompany('Ideas');
	$writer->setKeywords($keywords);
	$writer->setDescription('Información de la base de datos');
	$writer->setTempDir(sys_get_temp_dir());//set custom tempdir

	foreach ($_SESSION['reporte_Sistema']['excel']['columnas_titulos'] as $key => $value) {
		$header[$value['nombre']] = $value['tipo'];
		$columnas[] = $value['row'];
		$style[] = array('auto_filter'=>true, 'fill'=>$value['fill'],'color'=>'#FFFFFF','font-style'=>'bold','border'=>'left,right,top,bottom');
	}

	$numero =1;
	$page = 1;

	$color_reg = 1 % 2;

	$color_reg = 1;
	foreach ($_SESSION['reporte_Sistema']['excel']['columnas_datos'] as $key => $data) {
		unset($columna_valor);
		unset($styleRow);
		foreach ($columnas as $id => $value) {
			$id = $value;
			if($id == 'semaforo'){
				if($data[$id]=='verde'){
					$styleRow[] = array('fill'=>'#008f39','color'=>'#FFFFFF','border'=>'left,right,top,bottom');
				}elseif ($data[$id]=='amarillo') {
					$styleRow[] = array('fill'=>'#fce903','color'=>'#000000','border'=>'left,right,top,bottom');
				}elseif ($data[$id]=='rojo') {
					$styleRow[] = array('fill'=>'#e71837','color'=>'#FFFFFF','border'=>'left,right,top,bottom');
				}elseif ($data[$id]=='gris') {
					$styleRow[] = array('fill'=>'#909090','color'=>'#FFFFFF','border'=>'left,right,top,bottom');
				}else{
					$styleRow[] = array('fill'=>'#000000','color'=>'#FFFFFF','border'=>'left,right,top,bottom');
				}
			}else{

				$marco = $color_reg % 2;
				if($marco == 0){
					$styleRow[] = array('fill' => '#e9e9e9','color'=>'#000000','border'=>'left,right,top,bottom');
				}else{
					$styleRow[] = array('color'=>'#000000','border'=>'left,right,top,bottom');
				}
			}
			$columna_valor[] = $data[$id];
		}
		$color_reg ++;
		if($numero == 300001){
			$numero = 1;
		}
		if($numero==1){
			sleep(1);
			$txt = "Matriz Rentabilidad Pag - ".$page;
			$page ++;
			$writer->writeSheetHeader($txt, $header, $style );
		}
		$numero ++;
		$writer->writeSheetRow($txt, $columna_valor,$styleRow);
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