<?php
	@session_start();
	include '../../../functions/security.php';
	include '../../../functions/usuario_permisos.php';
	$moduloAccionPermisos = moduloAccionPermisos('sistema_unico_beneficiarios','cartografias_distritos_locales_2016',$_COOKIE["id_usuario"]);
	if( $moduloAccionPermisos['download'] || $moduloAccionPermisos['all']){
		$pageService=$_GET['cot'];
		$_COOKIE['pageService'];
	}else{
		$pageService = "";
	}
	
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
	if($id_municipio==""){
		include __DIR__."../../../../functions/tool_xhpzab.php";
		$id_municipio = decrypt_ab_checkFinal($_COOKIE['paguinaId_1']);
	}


	$start_time = microtime(true);
	include_once("../../../librerias/excel/xlsxwriter.class.php");
	include_once "../../../functions/security.php";
	include_once "../../../functions/efs.php";

	$length=6; 
	$mk_id=time()*2*36*12;
	$gen_id3 = substr(str_shuffle("ABCDEFGHIJKLMNOPQRSTUVWXYZ01234567890123456789"), 0, $length); 

	$rutaEfs = rutaEfs();
	$archivo_json = $rutaEfs . 'datos_generales_forzar_distrito_local_2016_'.$id_municipio.'-'.$_COOKIE["id_usuario"].'.json';
	if (file_exists($archivo_json)) {
		// Lee el contenido del archivo JSON
		$json_data = file_get_contents($archivo_json);

		// Decodifica el JSON en un array asociativo
		$datos_generales = json_decode($json_data, true);

		if ($datos_generales === null) {
			// Manejar un error en la decodificación si es necesario
			//echo "Error al decodificar el JSON";
		} else {
			// Ahora tienes el array $datos_generales disponible para su uso
			//print_r($datos_generales);
		}
	} else {
		//echo "El archivo JSON no existe en la ruta especificada";
	}
	$archivo_json = $rutaEfs . 'columnas_datos_forzar_distrito_local_2016_'.$id_municipio.'-'.$_COOKIE["id_usuario"].'.json';
	if (file_exists($archivo_json)) {
		// Lee el contenido del archivo JSON
		$json_data = file_get_contents($archivo_json);

		// Decodifica el JSON en un array asociativo
		$columnas_datos = json_decode($json_data, true);

		if ($columnas_datos === null) {
			// Manejar un error en la decodificación si es necesario
			//echo "Error al decodificar el JSON";
		} else {
			// Ahora tienes el array $columnas_datos disponible para su uso
			//print_r($columnas_datos);
		}
	} else {
		//echo "El archivo JSON no existe en la ruta especificada";
	}
	$archivo_json = $rutaEfs . 'columnas_titulos_partidos_forzar_distrito_local_2016_'.$id_municipio.'-'.$_COOKIE["id_usuario"].'.json';
	if (file_exists($archivo_json)) {
		// Lee el contenido del archivo JSON
		$json_data = file_get_contents($archivo_json);

		// Decodifica el JSON en un array asociativo
		$columnas_titulos_partidos = json_decode($json_data, true);

		if ($columnas_titulos_partidos === null) {
			// Manejar un error en la decodificación si es necesario
			//echo "Error al decodificar el JSON";
		} else {
			// Ahora tienes el array $columnas_titulos_partidos disponible para su uso
			//print_r($columnas_titulos_partidos);
		}
	} else {
		//echo "El archivo JSON no existe en la ruta especificada";
	}

	$datos_generales['territorio_nombre'];
	$filename = 'MatrizRentabilidadF_Distrito_LocalMunicipio_'.str_replace(" ", "_", $datos_generales['territorio_nombre']).'_-_'.date("Ymd-His").'-'.$gen_id3.$mk_id.'.xlsx';
	$ruta_ftpExport ='../../../ftpFiles/documentosExport/';
	/*
	header('Content-disposition: attachment;   filename="'.XLSXWriter::sanitize_filename('');
	header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
	header('Content-Transfer-Encoding: binary');
	header('Cache-Control: must-revalidate');
	header('Pragma: public');
	*/
	$writer = new XLSXWriter();
	$keywords = array('Matriz de Rentabilidad F_Distrito_Local Municipio','Data','Información');
	$writer->setTitle('Matriz de Rentabilidad F_Distrito_Local Municipio');
	$writer->setSubject('Información de la base de datos');
	$writer->setAuthor('Ideas');
	$writer->setCompany('Ideas');
	$writer->setKeywords($keywords);
	$writer->setDescription('Información de la base de datos');
	$writer->setTempDir(sys_get_temp_dir());//set custom tempdir

	foreach ($columnas_titulos_partidos as $key => $value) {
        $header[$value['nombre']] = $value['tipo'];
        $columnas[] = $value['row'];
        $style[] = array('auto_filter' => true, 'fill' => $value['fill'], 'color' => '#FFFFFF', 'font-style' => 'bold', 'border' => 'left,right,top,bottom');
    }

	$numero =1;
	$page = 1;

	$color_reg = 1 % 2;

	$color_reg = 1;
	foreach ($columnas_datos as $key => $data) {
		unset($columna_valor);
        unset($styleRow);

        foreach ($data as $keyT => $valueT) {
            $styleRow[] = array('wrap_text' => true, 'valign' => 'top');
            if ($keyT == 'seccion_colonias' || $keyT == 'seccion_localidades' ) {
                $data[$keyT] = "_".str_replace('*_*', "\n_", $valueT);
                
            } else {
                if ($valueT == null) {
                    $data[$keyT] = 0;
                }
            }
        }
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