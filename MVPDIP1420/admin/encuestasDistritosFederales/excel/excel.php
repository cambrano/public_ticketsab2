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
	include_once "../../functions/usuario_permisos.php";
	include_once "../../functions/tool_xhpzab.php";

	include_once "../../functions/encuestas.php";
	include_once "../../functions/cuestionarios.php";
	include_once "../../functions/cuestionarios_respuestas.php";

	$moduloAccionPermisos = moduloAccionPermisos('encuestas','encuestas',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
	}else{
		?>
		<script type="text/javascript">
			window.close();
		</script>
		<?php
		die;
	}

	include_once "../../functions/security.php";
	include_once("../../librerias/excel/xlsxwriter.class.php");
	include_once "../../functions/tool_xhpzab.php";
 

	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789'), 0, $length); 
	$filename = 'Ciudadanos_Encuetas_Distritos_Federales_-_'.date("Ymd-His").'-'.$gen_id3.$mk_id.'.xlsx';
	$ruta_ftpExport ='../../ftpFiles/documentosExport/';

	//preguntas
	$id_encuesta = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	$encuestaDatos = encuestaDatos($id_encuesta);
	$nombre = $encuestaDatos['nombre'];
	$cuestionarioPreguntaDatos=cuestionarioPreguntaDatos('',$id_encuesta);
	$cuestionario_respuestasIdDatos = cuestionario_respuestasIdDatos('','',$id_encuesta,'');
	$inicio = 8;
	$n =1;
	//$header['Fecha Hora']='YYYY-MM-DD HH:MM:SS';
	$header['Fecha Hora']='string';
	$respuestas[]='';
	$header['Encuesta']='string';
	$respuestas[]='';
	$header['Minicipio']='string';
	$respuestas[]='';
	$header['Distrito']='string';
	$respuestas[]='';
	$header['Sección']='integer';
	$respuestas[]='';
	$header['Nombre Completo']='string';
	$respuestas[]='';
	$header['Sexo']='string';
	$respuestas[]='';
	$header['Edad']='integer';
	$respuestas[]='';
	$sqlexcelSub = "";
	foreach ($cuestionarioPreguntaDatos as $key => $value) {
		$length=5; 
		$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 
		if($value['num_respuestas']==0){
			$value['num_respuestas']=1;
		}
		$header[$value['pregunta']]='string';
		$xfor = $value['num_respuestas'] - 1;
		for ($i=0; $i < $xfor; $i++) { 
			$header[$gen_id3.$i]='string';
		}

		$fin = $inicio + $value['num_respuestas'] - 1;
		$markMergedCell[] = array(
			'inicio' => $inicio,
			'fin' => $fin, 
		);
		$inicio = $inicio + $value['num_respuestas'];
		if($value['campo']=='text'){
			$respuestas[] = '-';
			$sqlexcelSub .= "IFNULL((SELECT sicer.respuesta FROM secciones_ine_ciudadanos_encuestas_respuestas sicer WHERE sicer.id_cuestionario = ".$value['id']." AND sicer.id_seccion_ine_ciudadano_encuesta = sice.id LIMIT 1 ),'-') respuesta".$n.",";
			//echo "<br>";
				$n++;
		}else{
			foreach ($cuestionario_respuestasIdDatos[$value['id']] as $keyT => $valueT) {
				$respuestas[] = $valueT['respuesta'];
				$sqlexcelSub .= "IFNULL((SELECT IF(sicer.respuesta=1,'x',sicer.respuesta) FROM secciones_ine_ciudadanos_encuestas_respuestas sicer WHERE sicer.id_cuestionario_respuesta = ".$valueT['id']." AND sicer.id_seccion_ine_ciudadano_encuesta = sice.id LIMIT 1 ),'-') respuesta".$n.",";
				//echo "<br>";
				$n++;
			}
		}
	}
	$header['_-_Observaciones_-_']='string';
	$respuestas[]='';

	$sqlexcel= "
		SELECT 
			sice.fecha_hora,
			'{$nombre}' nombre_encuesta,
			(SELECT m.municipio FROM municipios m WHERE m.id = sice.id_municipio) municipio,
			(SELECT m.numero FROM distritos_federales m WHERE m.id = sice.id_distrito_federal) distrito,
			(SELECT s.numero FROM secciones_ine s WHERE s.id = sice.id_seccion_ine ) seccion,
			(SELECT sic.nombre_completo FROM secciones_ine_ciudadanos sic WHERE sic.id = sice.id_seccion_ine_ciudadano  ) nombre_completo,
			IF(sice.sexo=1,'Hombre','Mujer') sexo,
			sice.edad,
			{$sqlexcelSub}
			sice.observaciones
			FROM secciones_ine_ciudadanos_encuestas sice WHERE 1

	";
	if( $id_encuesta != "" ){   //name
		$sqlexcel .= " AND sice.id_encuesta = '{$id_encuesta}' ";
	}
	$id_distrito_federal=$_POST['excel'][0]['id_distrito_federal'];
	if( $id_distrito_federal!="" ){
		$sqlexcel.=" AND sice.id_distrito_federal IN ($id_distrito_federal) ";
	}
	$sexo=$_POST['excel'][0]['sexo'];
	if( $sexo!="" ){
		if($sexo=='Hombre'){
			$sexo=1;
		}else{
			$sexo=2;
		}
		$sqlexcel .= " AND sice.sexo = '{$sexo}' ";
	}
	$edad=$_POST['excel'][0]['edad'];
	switch ($edad) {
		case '1':
			$sqlexcel .= " AND sice.edad = 18 ";
			break;
		case '2':
			$sqlexcel .= " AND sice.edad = 19 ";
			break;
		case '3':
			$sqlexcel .= " AND sice.edad BETWEEN 20 AND 24  ";
			break;
		case '4':
			$sqlexcel .= " AND sice.edad BETWEEN 25 AND 29  ";
			break;
		case '5':
			$sqlexcel .= " AND sice.edad BETWEEN 30 AND 34  ";
			break;
		case '6':
			$sqlexcel .= " AND sice.edad BETWEEN 35 AND 39 ";
			break;
		case '7':
			$sqlexcel .= " AND sice.edad BETWEEN 40 AND 44 ";
			break;
		case '8':
			$sqlexcel .= " AND sice.edad BETWEEN 45 AND 49 ";
			break;
		case '9':
			$sqlexcel .= " AND sice.edad BETWEEN 50 AND 54 ";
			break;
		case '10':
			$sqlexcel .= " AND sice.edad BETWEEN 55 AND 59 ";
			break;
		case '11':
			$sqlexcel .= " AND sice.edad BETWEEN 60 AND 64 ";
			break;
		case '12':
			$sqlexcel.=" AND sice.edad > 64 ";
			break;
	}
	$id_seccion_ine=$_POST['excel'][0]['id_seccion_ine'];
	if( $id_seccion_ine!="" ){
		$sqlexcel.=" AND sice.id_seccion_ine IN ($id_seccion_ine) ";
	}
	$decryptedQuery = decrypt_ab_checkSin($_COOKIE['AB32BA51']);
	
	$writer = new XLSXWriter();
	$keywords = array('Ciudadanos Encuestas Distritos Federales','Data','Información');
	$writer->setTitle('Ciudadanos Encuestas Distritos Federales');
	$writer->setSubject('Información de la base de datos');
	$writer->setAuthor('Ideas');
	$writer->setCompany('Ideas');
	$writer->setKeywords($keywords);
	$writer->setDescription('Información de la base de datos');
	$writer->setTempDir(sys_get_temp_dir());//set custom tempdir

	$numero =1;
	$page = 1;
	$result = $conexion->query($sqlexcel.$decryptedQuery.' ;');
	$color_reg = 1;
	while($row=$result->fetch_assoc()){
		if($numero == 300001){
			$numero = 1;
		}
		if($numero==1){ 
			$txt = "Ciudadanos Encuestas Pag - ".$page;
			$page ++;
			$writer->writeSheetHeader($txt, $header, [ 'fill'=>'#397cb5','color'=>'#FFFFFF','border'=>'left,right,top,bottom','border-style'=>'dash dot','font-style'=>'bold','valign'=>'center','halign'=>'center'], );
			foreach ($markMergedCell as $key => $value) {
				$writer->markMergedCell($txt, $start_row = 0, $start_col = $value['inicio'], $end_row = 0, $end_col = $value['fin']);
			}
			$writer->writeSheetRow($txt, $respuestas,[ 'fill'=>'#78ab78','color'=>'#000000','border'=>'left,right,top,bottom','border-style'=>'think ','font-style'=>'bold']);
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